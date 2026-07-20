import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from sklearn.preprocessing import OrdinalEncoder, LabelEncoder
from sklearn.metrics import accuracy_score, confusion_matrix, classification_report
import joblib
import os


os.chdir(os.path.dirname(os.path.abspath(__file__)))


dataset_path = 'dataset_v2.csv'
if not os.path.exists(dataset_path):
    raise FileNotFoundError(f"Dataset not found at: {os.path.abspath(dataset_path)}")

df = pd.read_csv(dataset_path)


X = df[['nilai_tugas', 'nilai_kuis', 'kehadiran', 'study_duration', 'tingkat_kesulitan']]
y = df['rekomendasi']



X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42, stratify=y)

print("Melakukan Preprocessing & Encoding...")




oe_kesulitan = OrdinalEncoder(categories=[['Mudah', 'Sedang', 'Sulit']])
oe_kehadiran = OrdinalEncoder(categories=[['Kurang', 'Cukup', 'Baik']])

X_train_encoded = X_train.copy()
X_test_encoded = X_test.copy()

X_train_encoded['tingkat_kesulitan'] = oe_kesulitan.fit_transform(X_train[['tingkat_kesulitan']])
X_test_encoded['tingkat_kesulitan'] = oe_kesulitan.transform(X_test[['tingkat_kesulitan']])

X_train_encoded['kehadiran'] = oe_kehadiran.fit_transform(X_train[['kehadiran']])
X_test_encoded['kehadiran'] = oe_kehadiran.transform(X_test[['kehadiran']])


le_rekomendasi = LabelEncoder()
y_train_encoded = le_rekomendasi.fit_transform(y_train)
y_test_encoded = le_rekomendasi.transform(y_test)


label_encoders = {
    'tingkat_kesulitan': oe_kesulitan,
    'kehadiran': oe_kehadiran,
    'rekomendasi': le_rekomendasi
}

print(f"Jumlah data training: {len(X_train_encoded)}")
print(f"Jumlah data testing: {len(X_test_encoded)}")


print("Melatih model Random Forest Classifier...")
rf_model = RandomForestClassifier(
    n_estimators=100, 
    max_depth=3, 
    min_samples_split=10, 
    min_samples_leaf=5, 
    random_state=42
)
rf_model.fit(X_train_encoded, y_train_encoded)


y_pred = rf_model.predict(X_test_encoded)


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


importances = rf_model.feature_importances_
feature_names = X.columns
feature_importance_df = pd.DataFrame({
    'Feature': feature_names,
    'Importance': importances
}).sort_values(by='Importance', ascending=False)
print("Feature Importance:")
print(feature_importance_df.to_string(index=False))
print("="*50)


print("Menyimpan model ke model.pkl...")
joblib.dump(rf_model, 'model.pkl')

print("Menyimpan Encoder ke label_encoder.pkl...")
joblib.dump(label_encoders, 'label_encoder.pkl')

print("Proses training selesai dan file model berhasil disimpan.")
