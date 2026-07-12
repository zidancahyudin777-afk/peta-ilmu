import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from sklearn.preprocessing import OrdinalEncoder, LabelEncoder
from sklearn.metrics import accuracy_score, confusion_matrix, classification_report
import joblib
import os

# Set working directory to the script's directory
os.chdir(os.path.dirname(os.path.abspath(__file__)))

# Load dataset
dataset_path = 'dataset.csv'
if not os.path.exists(dataset_path):
    raise FileNotFoundError(f"Dataset not found at: {os.path.abspath(dataset_path)}")

df = pd.read_csv(dataset_path)

# Split features and target label
X = df[['nilai', 'tingkat_kesulitan', 'jam_belajar', 'kehadiran', 'gaya_belajar']]
y = df['rekomendasi']

# Split data into training and test sets (80% train, 20% test) using stratification
# Stratification ensures that the train and test sets have the same proportion of target classes
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42, stratify=y)

print("Melakukan Preprocessing & Encoding...")

# Define and fit OrdinalEncoders for categorical features on training data ONLY (prevent data leakage)
# - tingkat_kesulitan: Mudah (0) -> Sedang (1) -> Sulit (2)
# - kehadiran: Kurang (0) -> Cukup (1) -> Baik (2)
# - gaya_belajar: Auditori (0) -> Kinestetik (1) -> Visual (2) (mapped consistently to preserve 1D column structure)
oe_kesulitan = OrdinalEncoder(categories=[['Mudah', 'Sedang', 'Sulit']])
oe_kehadiran = OrdinalEncoder(categories=[['Kurang', 'Cukup', 'Baik']])
oe_gaya = OrdinalEncoder(categories=[['Auditori', 'Kinestetik', 'Visual']])

X_train_encoded = X_train.copy()
X_test_encoded = X_test.copy()

X_train_encoded['tingkat_kesulitan'] = oe_kesulitan.fit_transform(X_train[['tingkat_kesulitan']])
X_test_encoded['tingkat_kesulitan'] = oe_kesulitan.transform(X_test[['tingkat_kesulitan']])

X_train_encoded['kehadiran'] = oe_kehadiran.fit_transform(X_train[['kehadiran']])
X_test_encoded['kehadiran'] = oe_kehadiran.transform(X_test[['kehadiran']])

X_train_encoded['gaya_belajar'] = oe_gaya.fit_transform(X_train[['gaya_belajar']])
X_test_encoded['gaya_belajar'] = oe_gaya.transform(X_test[['gaya_belajar']])

# Fit LabelEncoder for target variable on training data ONLY
le_rekomendasi = LabelEncoder()
y_train_encoded = le_rekomendasi.fit_transform(y_train)
y_test_encoded = le_rekomendasi.transform(y_test)

# Store encoders in a dictionary for saving (maintaining interface compatibility)
label_encoders = {
    'tingkat_kesulitan': oe_kesulitan,
    'kehadiran': oe_kehadiran,
    'gaya_belajar': oe_gaya,
    'rekomendasi': le_rekomendasi
}

print(f"Jumlah data training: {len(X_train_encoded)}")
print(f"Jumlah data testing: {len(X_test_encoded)}")

# Initialize and train Random Forest Classifier
# Regularize tree depth and sample split sizes to avoid overfitting on the training set
print("Melatih model Random Forest Classifier...")
rf_model = RandomForestClassifier(
    n_estimators=100, 
    max_depth=3, 
    min_samples_split=10, 
    min_samples_leaf=5, 
    random_state=42
)
rf_model.fit(X_train_encoded, y_train_encoded)

# Predict outcomes for the test set
y_pred = rf_model.predict(X_test_encoded)

# Calculate and display metrics
accuracy = accuracy_score(y_test_encoded, y_pred)
conf_matrix = confusion_matrix(y_test_encoded, y_pred)
class_report = classification_report(
    y_test_encoded, 
    y_pred, 
    target_names=label_encoders['rekomendasi'].classes_
)

print("\n" + "="*50)
print("HASIL EVALUASI MODEL:")
print("="*50)
print(f"Accuracy Score: {accuracy:.4%}")
print("\nConfusion Matrix:")
print(conf_matrix)
print("\nClassification Report:")
print(class_report)
print("="*50)

# Export models to pickle files
print("Menyimpan model ke model.pkl...")
joblib.dump(rf_model, 'model.pkl')

print("Menyimpan Encoder ke label_encoder.pkl...")
joblib.dump(label_encoders, 'label_encoder.pkl')

print("Proses training selesai dan file model berhasil disimpan.")
