import os
import logging
import numpy as np
import pandas as pd
import joblib
import tensorflow as tf
from flask import Flask, request, jsonify
from flask_cors import CORS
from typing import Dict, Optional

# -----------------------
# App Setup
# -----------------------
app = Flask(__name__)
CORS(app, resources={r"/predict": {
    "origins": ["http://localhost:8000"]
}})  # Update for your Laravel domain
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[logging.StreamHandler(),
              logging.FileHandler('app.log')])
logger = logging.getLogger(__name__)

# -----------------------
# Load Models & Scaler
# -----------------------
MODEL_DIR = './outputs'
FEATURE_COLS = None
scaler = None
base_models = {}
meta_learner = None
try:
    scaler = joblib.load(os.path.join(MODEL_DIR, 'scaler.joblib'))
    base_models = {
        'RandomForest':
        joblib.load(os.path.join(MODEL_DIR, 'base_model_RandomForest.joblib')),
        'XGBoost':
        joblib.load(os.path.join(MODEL_DIR, 'base_model_XGBoost.joblib')),
        'LightGBM':
        joblib.load(os.path.join(MODEL_DIR, 'base_model_LightGBM.joblib')),
        'CatBoost':
        joblib.load(os.path.join(MODEL_DIR, 'base_model_CatBoost.joblib')),
        'MLP':
        joblib.load(os.path.join(MODEL_DIR, 'base_model_MLP.joblib'))
    }
    meta_learner = tf.keras.models.load_model(
        os.path.join(MODEL_DIR, 'stacking_stroke_model.h5'))
    FEATURE_COLS = joblib.load(
        os.path.join(MODEL_DIR, 'feature_columns.joblib'))
    logger.info("Models and features loaded successfully.")
    logger.info(f"TensorFlow version: {tf.__version__}")
except Exception as e:
    logger.error(f"Error loading models: {e}")
    raise

# -----------------------
# Category Mappings
# -----------------------
CATEGORICAL_MAPS = {
    'gender': {
        'male': 0,
        'female': 1,
        'Male': 0,
        'Female': 1
    },
    'ever_married': {
        'no': 0,
        'yes': 1,
        'No': 0,
        'Yes': 1
    },
    'Residence_type': {
        'rural': 0,
        'urban': 1,
        'Rural': 0,
        'Urban': 1
    },
    'work_type_options':
    ['Govt_job', 'Never_worked', 'Private', 'Self-employed', 'children'],
    'smoking_status_options':
    ['formerly smoked', 'never smoked', 'smokes', 'Unknown']
}
DEFAULT_VALUES = {'age': 40.0, 'avg_glucose_level': 100.0, 'bmi': 25.0}
REQUIRED_FIELDS = [
    'age', 'gender', 'hypertension', 'heart_disease', 'ever_married',
    'work_type', 'Residence_type', 'avg_glucose_level', 'bmi', 'smoking_status'
]


# -----------------------
# Preprocessing Function
# -----------------------
def preprocess_input(data: Dict) -> Optional[pd.DataFrame]:
    try:
        missing_fields = [
            field for field in REQUIRED_FIELDS if field not in data
        ]
        if missing_fields:
            raise ValueError(f"Missing required fields: {missing_fields}")
        df = pd.DataFrame([data])
        numerical_cols = ['age', 'avg_glucose_level', 'bmi']
        for col in numerical_cols:
            if col in df.columns:
                df[col] = pd.to_numeric(df[col], errors='coerce')
                if df[col].isna().all():
                    df[col] = df[col].fillna(DEFAULT_VALUES[col])
                else:
                    df[col] = df[col].fillna(df[col].median(skipna=True))
        df[numerical_cols] = scaler.transform(df[numerical_cols])
        for col, mapping in [
            ('gender', CATEGORICAL_MAPS['gender']),
            ('ever_married', CATEGORICAL_MAPS['ever_married']),
            ('Residence_type', CATEGORICAL_MAPS['Residence_type'])
        ]:
            if col in df.columns:
                if df[col].iloc[0] not in mapping:
                    raise ValueError(
                        f"Invalid value for {col}: {df[col].iloc[0]}")
                df[col] = df[col].map(mapping)
        for col in ['hypertension', 'heart_disease']:
            if col in df.columns:
                df[col] = pd.to_numeric(df[col],
                                        errors='coerce').fillna(0).astype(int)
        for cat_col, options in [
            ('work_type', CATEGORICAL_MAPS['work_type_options']),
            ('smoking_status', CATEGORICAL_MAPS['smoking_status_options'])
        ]:
            if cat_col in df.columns:
                if df[cat_col].iloc[0] not in options:
                    raise ValueError(
                        f"Invalid value for {cat_col}: {df[cat_col].iloc[0]}")
                df = pd.get_dummies(df, columns=[cat_col], drop_first=False)
                for opt in options:
                    col_name = f"{cat_col}_{opt}"
                    if col_name not in df.columns:
                        df[col_name] = 0
        logger.info(f"Processed input columns: {df.columns.tolist()}")
        df = df.reindex(columns=FEATURE_COLS, fill_value=0)
        return df
    except Exception as e:
        logger.error(f"Error in preprocessing: {e}")
        return None


# -----------------------
# Prediction Endpoint
# -----------------------
@app.route('/predict', methods=['POST'])
def predict():
    try:
        data = request.get_json()
        logger.info(f"Received input data: {data}")
        if not data:
            return jsonify({'error': 'No input data provided'}), 400
        X_input = preprocess_input(data)
        if X_input is None:
            return jsonify({'error': 'Invalid input data'}), 400
        meta_features = []
        for name, model in base_models.items():
            try:
                if hasattr(model, 'predict_proba'):
                    proba = model.predict_proba(X_input)[:, 1]
                    meta_features.append(proba.flatten())
                else:
                    logger.warning(
                        f"Model {name} does not support predict_proba, skipping."
                    )
                    continue
            except Exception as e:
                logger.error(f"Error predicting with {name}: {e}")
                return jsonify(
                    {'error': f"Prediction failed for model {name}"}), 500
        meta_features = np.vstack(meta_features).T
        expected_shape = (1, len(base_models))
        if meta_features.shape != expected_shape:
            logger.error(
                f"Unexpected meta-features shape: {meta_features.shape}")
            return jsonify({
                'error':
                'Internal server error: Invalid meta-features shape'
            }), 500
        probability = float(
            meta_learner.predict(meta_features, verbose=0)[0][0])
        risk_level = 'High' if probability > 0.5 else 'Low'
        return jsonify({
            'risk_probability': probability,
            'risk_level': risk_level,
            'message': 'Prediction successful'
        })
    except Exception as e:
        logger.exception("Error during prediction")
        return jsonify({
            'error': 'Internal server error',
            'details': str(e)
        }), 500


# -----------------------
# Main Entry
# -----------------------
if __name__ == '__main__':
    app.run(debug=False, host='0.0.0.0', port=5000)
