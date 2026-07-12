# Modul Machine Learning - Bimbingan Belajar "Peta Ilmu"

Modul Machine Learning (ML) mandiri ini dikembangkan menggunakan **Python 3.12+**, algoritma **Random Forest Classifier** (`scikit-learn`), dan framework **Flask API**. Modul ini dirancang khusus untuk memprediksi program bimbingan belajar terbaik bagi siswa berdasarkan riwayat data akademik dan perilaku belajar.

---

## Struktur Folder

```text
ML/
├── dataset.csv          # Dataset latih dan uji berisi 250 record realistis
├── train.py             # Script untuk preprocessing, training model, dan evaluasi
├── app.py               # Flask API server untuk memproses request prediksi
├── test_api.py          # Client script sederhana untuk menguji endpoint Flask API
├── requirements.txt     # Daftar pustaka (dependencies) Python yang dibutuhkan
├── model.pkl            # File model terlatih hasil serialisasi (dibuat setelah training)
├── label_encoder.pkl    # File LabelEncoder hasil serialisasi (dibuat setelah training)
└── README.md            # Dokumentasi panduan teknis (file ini)
```

---

## Spesifikasi Dataset (`dataset.csv`)

Dataset berisi **250 baris data akademik siswa** dengan kolom-kolom berikut:

1.  **`nilai`**: Nilai akhir pembelajaran siswa (skala numerik `0` - `100`).
2.  **`tingkat_kesulitan`**: Hambatan siswa terhadap materi (`Mudah`, `Sedang`, `Sulit`).
3.  **`jam_belajar`**: Durasi waktu belajar mandiri per hari (skala numerik `1` - `5` jam).
4.  **`kehadiran`**: Persentase kehadiran kelas bimbel (`Baik`, `Cukup`, `Kurang`).
5.  **`gaya_belajar`**: Gaya belajar dominan siswa (`Visual`, `Auditori`, `Kinestetik`).
6.  **`rekomendasi`** (Target Label):
    *   `Pendampingan Intensif` (Siswa butuh pendampingan khusus 1-on-1 dengan tutor)
    *   `Program Intensif` (Siswa butuh pemadatan materi reguler tambahan)
    *   `Program Reguler` (Siswa cukup mengikuti kelas bimbingan standar)

Pola klasifikasi dilatih secara logis untuk merefleksikan kebutuhan bimbingan belajar yang akurat.

---

## Cara Instalasi & Penggunaan

Ikuti langkah-langkah di bawah ini untuk menjalankan modul secara mandiri:

### Langkah 1: Persiapan Lingkungan & Pustaka

Buka terminal/command prompt pada direktori proyek `/ML` lalu instal semua library yang diperlukan:

```bash
# Masuk ke direktori ML jika belum berada di dalamnya
cd ML

# Menginstal dependensi yang tertera pada requirements.txt
pip install -r requirements.txt
```

> [!NOTE]
> Sangat disarankan untuk menggunakan Python Virtual Environment (`venv`) agar library tidak bentrok dengan global environment.
> Perintah pembuatan venv: `python -m venv venv` lalu aktifkan sebelum menginstal requirements.

### Langkah 2: Melatih Model (Training)

Jalankan perintah berikut untuk melatih model Random Forest menggunakan dataset:

```bash
python train.py
```

Setelah dijalankan, sistem akan:
*   Membaca `dataset.csv`.
*   Melakukan encoding fitur kategorikal menjadi numerik.
*   Membagi dataset (80% training, 20% testing).
*   Melatih model dan mencetak hasil evaluasi model (Accuracy, Confusion Matrix, Classification Report).
*   Menghasilkan file model terlatih: `model.pkl` dan `label_encoder.pkl`.

### Langkah 3: Menjalankan API Server (Flask)

Untuk mengaktifkan Flask API web server guna melayani prediksi eksternal, jalankan:

```bash
python app.py
```

Server akan aktif pada alamat default: `http://127.0.0.1:5000`

### Langkah 4: Pengujian Prediksi API

Biarkan server Flask tetap berjalan di terminal pertama, lalu buka terminal baru (command prompt) dan jalankan script testing:

```bash
# Pastikan berada di direktori ML
python test_api.py
```

Script akan mengirimkan request `POST` berformat JSON ke endpoint `/predict` dengan berbagai skenario data siswa.

#### Contoh Request API:
*   **Method**: `POST`
*   **URL**: `http://127.0.0.1:5000/predict`
*   **Headers**: `Content-Type: application/json`
*   **Body (JSON)**:
    ```json
    {
      "nilai": 80,
      "tingkat_kesulitan": "Sedang",
      "jam_belajar": 3,
      "kehadiran": "Baik",
      "gaya_belajar": "Visual"
    }
    ```

#### Contoh Respon API:
*   **Status Code**: `200 OK`
*   **Body (JSON)**:
    ```json
    {
      "rekomendasi": "Program Reguler"
    }
    ```
