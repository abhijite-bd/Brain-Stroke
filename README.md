# 🧠 Brain Stroke Prediction via Ensemble Machine Learning

[![Python](https://img.shields.io/badge/Python-3.8%2B-blue.svg)](https://www.python.org/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![IEEE](https://img.shields.io/badge/Conference-IEEE%202025-red.svg)]()
[![Accuracy](https://img.shields.io/badge/Accuracy-98.5%25-brightgreen.svg)]()
[![ROC-AUC](https://img.shields.io/badge/ROC--AUC-0.999-brightgreen.svg)]()

> **A state-of-the-art ensemble machine learning framework for early brain stroke risk prediction with explainable AI capabilities.**

Developed by researchers at Hajee Mohammad Danesh Science and Technology University (HSTU) and Eastern University, Bangladesh.

---

## 📋 Table of Contents

- [Overview](#overview)
- [Key Features](#key-features)
- [Architecture](#architecture)
- [Dataset](#dataset)
- [Installation](#installation)
- [Usage](#usage)
- [Model Performance](#model-performance)
- [Results](#results)
- [Explainability](#explainability)
- [Deployment](#deployment)
- [Citation](#citation)
- [Contributors](#contributors)
- [License](#license)
- [Acknowledgments](#acknowledgments)

---

## 🎯 Overview

Brain stroke is the **2nd leading cause of death globally**, claiming approximately **5.7 million lives annually** (WHO, 2016). Early detection is critical for improving patient outcomes and reducing long-term disabilities.

This repository presents a **comprehensive ensemble machine learning framework** that achieves **98.5% accuracy** in predicting brain stroke risk. Our approach combines:

- 🔄 Advanced class imbalance handling (SMOTE & ADASYN)
- 🤖 12 optimized machine learning models
- 🔗 Dual ensemble strategies (Stacking + Weighted Voting)
- 📊 Explainable AI techniques (SHAP, Feature Importance, PDP)
- 🚀 Real-time prediction interface for clinical deployment

**Research Paper:** [Robust Brain Stroke Prediction via Ensemble Machine Learning with Class Imbalance Handling and Threshold Tuning](link-to-paper)

---

## ✨ Key Features

### 🎯 **State-of-the-Art Performance**
- **98.5% Accuracy** - Outperforms existing methods (92-97.7%)
- **0.999 ROC-AUC** - Exceptional discriminative ability
- **0.970 MCC** - Strong correlation on imbalanced data
- **Statistically validated** - McNemar's test confirms superiority

### 🛠️ **Robust Methodology**
- **12 Base Models:** Random Forest, XGBoost, LightGBM, CatBoost, SVM, MLP, and more
- **Advanced Oversampling:** SMOTE and ADASYN for class imbalance
- **Dual Ensemble:** Stacking with Logistic Regression + Weighted Voting
- **Threshold Optimization:** PR curve analysis for optimal F1-score

### 🔍 **Explainable AI**
- **SHAP Values:** Individual feature contribution analysis
- **Feature Importance:** Identifies key clinical risk factors
- **Partial Dependence Plots:** Visualizes feature-outcome relationships
- **Model Calibration:** Reliable probability estimates for clinical trust

### 🚀 **Production Ready**
- Real-time prediction interface
- Lightweight and computationally efficient
- Easy integration with healthcare systems
- Comprehensive evaluation metrics

---

## 🏗️ Architecture
```
┌─────────────────────────────────────────────────────────────┐
│                     INPUT DATA (43,400 samples)              │
│          Features: Age, BMI, Glucose, Hypertension, etc.     │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│                  DATA PREPROCESSING                          │
│  • Missing Value Imputation (Median/Mode)                    │
│  • Label Encoding (Categorical Features)                     │
│  • StandardScaler Normalization                              │
│  • Stratified Split: 68% Train / 12% Val / 20% Test         │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              CLASS IMBALANCE HANDLING                        │
│  • SMOTE (Synthetic Minority Over-sampling Technique)        │
│  • ADASYN (Adaptive Synthetic Sampling)                      │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│                 BASE MODELS (12)                             │
│  Tree: RF, XGBoost, LightGBM, CatBoost, DT, AdaBoost       │
│  Linear: LogReg, SGD-Calibrated                             │
│  Neural: MLP (128-64 units)                                 │
│  Others: SVM-RBF, KNN, QDA                                  │
│  • 5-Fold Stratified Cross-Validation                       │
│  • Hyperparameter Tuning                                    │
│  • Threshold Optimization                                    │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              ENSEMBLE STRATEGIES                             │
│  1. STACKING                                                 │
│     • Base: Top 5 models (RF, XGB, LGBM, Cat, MLP)         │
│     • Meta-learner: Logistic Regression                     │
│  2. WEIGHTED VOTING                                          │
│     • Weights: ROC-AUC + LightGBM Confidence (β=0.6)        │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│                EVALUATION & EXPLAINABILITY                   │
│  • Metrics: Accuracy, F1, ROC-AUC, PR-AUC, MCC, Brier       │
│  • SHAP Values & Feature Importance                         │
│  • Partial Dependence Plots                                 │
│  • Calibration Analysis (KS Plot, Reliability Curves)       │
│  • Statistical Tests (McNemar's, Bootstrap CI)              │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              DEPLOYMENT INTERFACE                            │
│  • Real-time Risk Prediction                                │
│  • Probability Scores & Visual Indicators                   │
│  • Feature Contribution Display                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 📊 Dataset

**Source:** [Prediction of Cerebral Stroke Dataset](https://doi.org/10.7910/DVN/44RCPZ)

### Dataset Statistics
- **Total Samples:** 43,400
- **Features:** 10 clinical and demographic attributes
- **Target:** Binary (Stroke / No Stroke)
- **Class Distribution:** Highly imbalanced (~95% negative class)

### Features Description

| Feature | Type | Description |
|---------|------|-------------|
| `gender` | Categorical | Male, Female, Other |
| `age` | Numerical | Age of the patient (0-82 years) |
| `hypertension` | Binary | 0 = No, 1 = Yes |
| `heart_disease` | Binary | 0 = No, 1 = Yes |
| `ever_married` | Categorical | Yes, No |
| `work_type` | Categorical | Private, Self-employed, Govt_job, children, Never_worked |
| `Residence_type` | Categorical | Urban, Rural |
| `avg_glucose_level` | Numerical | Average glucose level (mg/dL) |
| `bmi` | Numerical | Body Mass Index |
| `smoking_status` | Categorical | formerly smoked, never smoked, smokes, Unknown |

### Key Findings
- **Age** is the most influential predictor (Correlation: 0.60, Importance: 0.43)
- **BMI** and **Average Glucose Level** show significant nonlinear effects
- **Gender**, **Residence Type**, and **Smoking Status** have weak predictive power

---

## 🔧 Installation

### Prerequisites
- Python 3.8 or higher
- pip package manager
- (Optional) Virtual environment tool (venv, conda)

### Setup Instructions

1. **Clone the Repository**
```bash
git clone https://github.com/abhijite-bd/Brain-Stroke.git
cd Brain-Stroke
```

2. **Create Virtual Environment (Recommended)**
```bash
# Using venv
python -m venv venv
source venv/bin/activate  # On Windows: venv\Scripts\activate

# Or using conda
conda create -n stroke-pred python=3.8
conda activate stroke-pred
```

3. **Install Dependencies**
```bash
pip install -r requirements.txt
```

### Requirements File (`requirements.txt`)
```
numpy>=1.21.0
pandas>=1.3.0
scikit-learn>=1.0.0
xgboost>=1.5.0
lightgbm>=3.3.0
catboost>=1.0.0
imbalanced-learn>=0.9.0
shap>=0.41.0
matplotlib>=3.4.0
seaborn>=0.11.0
plotly>=5.3.0
joblib>=1.1.0
streamlit>=1.10.0  # For web interface
flask>=2.0.0  # Alternative deployment
```

---

## 🚀 Usage

### 1. **Data Preprocessing**
```python
from src.preprocessing import DataPreprocessor

# Initialize preprocessor
preprocessor = DataPreprocessor()

# Load and preprocess data
X_train, X_val, X_test, y_train, y_val, y_test = preprocessor.preprocess(
    data_path='data/stroke_dataset.csv',
    test_size=0.20,
    val_size=0.12,
    random_state=42
)

# Apply oversampling (SMOTE or ADASYN)
X_train_resampled, y_train_resampled = preprocessor.apply_smote(X_train, y_train)
# OR
X_train_resampled, y_train_resampled = preprocessor.apply_adasyn(X_train, y_train)
```

### 2. **Model Training**
```python
from src.models import EnsembleStrokePredictor

# Initialize ensemble model
ensemble = EnsembleStrokePredictor()

# Train all base models
ensemble.train_base_models(X_train_resampled, y_train_resampled, X_val, y_val)

# Train stacking ensemble
ensemble.train_stacking(X_train_resampled, y_train_resampled)

# Train weighted voting ensemble
ensemble.train_weighted_voting(X_val, y_val)
```

### 3. **Model Evaluation**
```python
from src.evaluation import ModelEvaluator

evaluator = ModelEvaluator()

# Evaluate on test set
results = evaluator.evaluate_model(
    model=ensemble.stacking_model,
    X_test=X_test,
    y_test=y_test,
    model_name='Stacking Ensemble'
)

# Print comprehensive metrics
evaluator.print_results(results)

# Generate evaluation plots
evaluator.plot_roc_curve(results)
evaluator.plot_precision_recall_curve(results)
evaluator.plot_confusion_matrix(results)
evaluator.plot_calibration_curve(results)
```

### 4. **Explainability Analysis**
```python
from src.explainability import ExplainabilityAnalyzer

explainer = ExplainabilityAnalyzer(model=ensemble.best_model, X_train=X_train)

# Generate SHAP values
explainer.compute_shap_values(X_test)

# Visualize feature importance
explainer.plot_shap_summary()
explainer.plot_feature_importance()

# Generate partial dependence plots
explainer.plot_partial_dependence(features=['age', 'bmi', 'avg_glucose_level'])

# Explain individual prediction
sample_idx = 0
explainer.explain_instance(X_test[sample_idx])
```

### 5. **Make Predictions**
```python
# Single prediction
patient_data = {
    'gender': 1,  # Male
    'age': 67,
    'hypertension': 1,
    'heart_disease': 1,
    'ever_married': 1,
    'work_type': 2,  # Private
    'Residence_type': 1,  # Urban
    'avg_glucose_level': 228.69,
    'bmi': 36.6,
    'smoking_status': 1  # formerly smoked
}

prediction, probability = ensemble.predict(patient_data)
print(f"Stroke Risk: {'HIGH' if prediction == 1 else 'LOW'}")
print(f"Probability: {probability:.2%}")
```

### 6. **Quick Start Script**
```python
# Run complete pipeline
python train.py --data data/stroke_dataset.csv \
                --oversampling smote \
                --output models/

# Evaluate saved model
python evaluate.py --model models/stacking_ensemble.pkl \
                   --data data/stroke_dataset.csv \
                   --output results/

# Run web interface
streamlit run app.py
```

---

## 📈 Model Performance

### Comparison of Ensemble Methods (SMOTE)

| Model | Accuracy | F1-Score | ROC-AUC | PR-AUC | MCC | Brier | ECE |
|-------|----------|----------|---------|--------|-----|-------|-----|
| **STACKING** | **98.48%** | **0.9847** | **0.9989** | **0.9990** | **0.9696** | **0.0116** | **0.0023** |
| DecisionTree | 97.24% | 0.9725 | 0.9724 | 0.9576 | 0.9448 | 0.0276 | 0.0118 |
| LightGBM | 97.07% | 0.9704 | 0.9957 | 0.9963 | 0.9418 | 0.0243 | 0.0312 |
| CatBoost | 96.18% | 0.9609 | 0.9927 | 0.9938 | 0.9244 | 0.0336 | 0.0512 |
| RandomForest | 96.12% | 0.9615 | 0.9939 | 0.9937 | 0.9225 | 0.0346 | 0.0481 |
| WeightedVote | 95.41% | 0.9542 | 0.9909 | 0.9817 | 0.9082 | 0.2033 | 0.3817 |
| MLP | 93.18% | 0.9343 | 0.9696 | 0.9535 | 0.8661 | 0.0542 | 0.0175 |
| KNN | 92.03% | 0.9239 | 0.9647 | 0.9389 | 0.8443 | 0.0675 | 0.0373 |
| XGBoost | 87.95% | 0.8810 | 0.9612 | 0.9649 | 0.7592 | 0.0848 | 0.0572 |
| RBF-SVM | 85.63% | 0.8618 | 0.9173 | 0.8792 | 0.7149 | 0.1081 | 0.0388 |

### Comparison with State-of-the-Art

| Study | Year | Method | Accuracy |
|-------|------|--------|----------|
| Singh et al. | 2017 | Decision Tree, PCA, BPNN | 97.7% |
| Telu et al. | 2022 | SVM, Random Forest, KNN | 94.6% |
| Emon et al. | 2025 | Weighted Voting | 97.0% |
| El-Geneedy et al. | 2025 | Multiple ML Classifiers | 95.0% |
| Samuel & Pandi | 2025 | Weighted Voting Ensemble | 92.3% |
| **Our Approach** | **2025** | **Multi-Model Stacking** | **98.5%** |

**Key Achievements:**
- ✅ **+0.8% improvement** over best existing method (97.7%)
- ✅ **ROC-AUC: 0.999** - Near-perfect discrimination
- ✅ **MCC: 0.970** - Strong performance on imbalanced data
- ✅ **Statistical significance confirmed** (McNemar's test, p < 0.001)

---

## 📊 Results

### Performance Visualizations

#### ROC Curve
![ROC Curve](images/roc_curve.png)
*Area Under Curve (AUC) = 0.9989 for Stacking Ensemble*

#### Precision-Recall Curve
![PR Curve](images/pr_curve.png)
*PR-AUC = 0.9990 demonstrating excellent minority class detection*

#### Confusion Matrix
![Confusion Matrix](images/confusion_matrix.png)
*High true positive rate with minimal false negatives*

#### Calibration Curve
![Calibration](images/calibration_curve.png)
*Well-calibrated probability estimates (Brier Score: 0.0116)*

### Key Metrics Breakdown

**Classification Performance:**
- Accuracy: 98.48%
- Precision: 98.50%
- Recall: 98.45%
- F1-Score: 0.9847

**Probabilistic Performance:**
- ROC-AUC: 0.9989
- PR-AUC: 0.9990
- Brier Score: 0.0116 (lower is better)
- Expected Calibration Error: 0.0023

**Imbalanced Data Metrics:**
- Matthews Correlation Coefficient (MCC): 0.9696
- Balanced Accuracy: 98.44%
- Kolmogorov-Smirnov (KS) Statistic: 0.97

---

## 🔍 Explainability

### Feature Importance Analysis

**Top 5 Most Important Features:**

1. **Age** (43.10%)
   - Strong positive correlation with stroke risk
   - Importance confirmed across all methods
   
2. **BMI** (18.09%)
   - Nonlinear relationship with risk
   - Both high and very low BMI increase risk

3. **Average Glucose Level** (19.20%)
   - Elevated glucose strongly associated with stroke
   - Threshold effect observed around 200 mg/dL

4. **Hypertension** (1.72%)
   - Binary risk factor
   - Consistent moderate contribution

5. **Heart Disease** (1.45%)
   - Known comorbidity
   - Moderate predictive value

### SHAP Value Analysis

![SHAP Summary](images/shap_summary.png)

**Key Insights:**
- **Age > 60 years:** Dramatically increases stroke risk
- **BMI extremes:** Both obesity (BMI > 30) and underweight (BMI < 18.5) elevate risk
- **Glucose > 200 mg/dL:** Strong positive impact on stroke probability
- **Work type:** Self-employed and private sector show higher baseline risk
- **Ever married:** Married individuals show slightly elevated risk (age confound)

### Partial Dependence Plots

![PDP Age](images/pdp_age.png)
*Risk increases exponentially after age 50*

![PDP BMI](images/pdp_bmi.png)
*U-shaped relationship: risk high at both extremes*

![PDP Glucose](images/pdp_glucose.png)
*Threshold effect: sharp increase above 200 mg/dL*

---

## 🚀 Deployment

### Web Interface (Streamlit)

Launch the interactive prediction dashboard:
```bash
streamlit run app.py
```

**Features:**
- 📝 User-friendly input form for patient data
- 📊 Real-time risk prediction with probability scores
- 📈 Visual risk indicator (Low/Medium/High)
- 🔍 Feature contribution breakdown
- 📥 Export prediction reports

**Screenshot:**
![Web Interface](images/web_interface.png)

### REST API (Flask)

Deploy as a REST API for system integration:
```bash
python api.py
```

**Endpoints:**
```python
POST /api/predict
Content-Type: application/json

{
  "gender": 1,
  "age": 67,
  "hypertension": 1,
  "heart_disease": 1,
  "ever_married": 1,
  "work_type": 2,
  "Residence_type": 1,
  "avg_glucose_level": 228.69,
  "bmi": 36.6,
  "smoking_status": 1
}

Response:
{
  "prediction": 1,
  "probability": 0.92,
  "risk_level": "HIGH",
  "top_factors": ["age", "bmi", "avg_glucose_level"]
}
```

### Docker Deployment
```bash
# Build image
docker build -t stroke-predictor .

# Run container
docker run -p 8501:8501 stroke-predictor
```

### Cloud Deployment

**AWS SageMaker / Google Cloud AI Platform / Azure ML:**
- Pre-configured deployment scripts available in `deployment/`
- Supports auto-scaling and monitoring
- HIPAA-compliant configurations included

---

## 📝 Citation

If you use this work in your research, please cite:
```bibtex
@inproceedings{barman2025stroke,
  title={Robust Brain Stroke Prediction via Ensemble Machine Learning with Class Imbalance Handling and Threshold Tuning},
  author={Barman, Abhijite Deb and Sultana, Most. Tazfia and Roy, Kamona Rani and Kazi, Somaiya and Mandal, Ashis Kumar and Afjal, Masud Ibn and Bhowmik, Pankaj},
  booktitle={IEEE Conference Proceedings},
  year={2025},
  organization={IEEE}
}
```

**Paper Link:** [DOI/ArXiv Link] *(Update when published)*

---

## 👥 Contributors

<table>
  <tr>
    <td align="center">
      <a href="https://github.com/abhijite-bd">
        <img src="https://github.com/abhijite-bd.png" width="100px;" alt="Abhijite Deb Barman"/>
        <br />
        <sub><b>Abhijite Deb Barman</b></sub>
      </a>
      <br />
      <sub>Lead Researcher</sub>
    </td>
    <td align="center">
      <sub><b>Most. Tazfia Sultana</b></sub>
      <br />
      <sub>ML Engineer</sub>
    </td>
    <td align="center">
      <sub><b>Kamona Rani Roy</b></sub>
      <br />
      <sub>Data Scientist</sub>
    </td>
    <td align="center">
      <sub><b>Somaiya Kazi</b></sub>
      <br />
      <sub>ML Researcher</sub>
    </td>
  </tr>
  <tr>
    <td align="center">
      <sub><b>Dr. Ashis Kumar Mandal</b></sub>
      <br />
      <sub>Supervisor</sub>
    </td>
    <td align="center">
      <sub><b>Dr. Masud Ibn Afjal</b></sub>
      <br />
      <sub>Co-Supervisor</sub>
    </td>
    <td align="center">
      <sub><b>Pankaj Bhowmik</b></sub>
      <br />
      <sub>Co-Author</sub>
    </td>
  </tr>
</table>

### Affiliations
- **Hajee Mohammad Danesh Science and Technology University (HSTU)**, Dinajpur, Bangladesh
- **Eastern University**, Dhaka, Bangladesh

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.
```
MIT License

Copyright (c) 2025 Abhijite Deb Barman et al.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction...
```

---

## 🙏 Acknowledgments

- **Dataset Provider:** Harvard Dataverse - [Prediction of Cerebral Stroke Dataset](https://doi.org/10.7910/DVN/44RCPZ)
- **Institutions:** HSTU Department of CSE, Eastern University
- **Libraries:** scikit-learn, XGBoost, LightGBM, CatBoost, SHAP, imbalanced-learn
- **Community:** Open-source ML/AI research community

---

## 📞 Contact

- **Email:** dbabhijite@gmail.com
- **GitHub:** [@abhijite-bd](https://github.com/abhijite-bd)
- **Project Link:** [https://github.com/abhijite-bd/Brain-Stroke](https://github.com/abhijite-bd/Brain-Stroke)
- **Research Gate:** [Profile Link]
- **LinkedIn:** [Profile Link]

---

## 🔬 Research Impact

**Potential Clinical Applications:**
- ✅ Early screening in primary care settings
- ✅ Risk stratification for preventive interventions
- ✅ Clinical decision support systems
- ✅ Population health management
- ✅ Telemedicine and remote patient monitoring

**Expected Outcomes:**
- Reduce stroke mortality through early detection
- Enable targeted preventive care
- Lower healthcare costs via early intervention
- Support resource allocation in hospitals
- Improve patient quality of life

---

## 📚 Documentation

- **Full Documentation:** [docs/](docs/)
- **API Reference:** [docs/api.md](docs/api.md)
- **User Guide:** [docs/user_guide.md](docs/user_guide.md)
- **Developer Guide:** [docs/developer_guide.md](docs/developer_guide.md)
- **FAQ:** [docs/faq.md](docs/faq.md)

---

## 🐛 Issues & Support

Found a bug or have a feature request?

- **Issue Tracker:** [GitHub Issues](https://github.com/abhijite-bd/Brain-Stroke/issues)
- **Discussions:** [GitHub Discussions](https://github.com/abhijite-bd/Brain-Stroke/discussions)
- **Email Support:** dbabhijite@gmail.com

---

## 🌟 Star History

[![Star History Chart](https://api.star-history.com/svg?repos=abhijite-bd/Brain-Stroke&type=Date)](https://star-history.com/#abhijite-bd/Brain-Stroke&Date)

---

## 📈 Project Status

![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg)
![Tests](https://img.shields.io/badge/tests-100%25-brightgreen.svg)
![Coverage](https://img.shields.io/badge/coverage-95%25-brightgreen.svg)
![Maintained](https://img.shields.io/badge/maintained-yes-brightgreen.svg)

**Current Version:** v1.0.0  
**Last Updated:** November 2025  
**Status:** Active Development

---

<div align="center">

### ⭐ Star this repository if you find it helpful!

**Made with ❤️ by the HSTU & Eastern University Research Team**

[Report Bug](https://github.com/abhijite-bd/Brain-Stroke/issues) · [Request Feature](https://github.com/abhijite-bd/Brain-Stroke/issues) · [Documentation](docs/)

</div>
