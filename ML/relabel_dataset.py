"""
Script untuk merelabel dataset.csv dari 3 kategori lama ke 4 kategori baru.

Mapping:
  Pendampingan Intensif  -> Program Remedial Intensif
  Program Intensif       -> Pendampingan Akademik
  Program Reguler (nilai < 85) -> Program Reguler
  Program Reguler (nilai >= 85) -> Program Pengayaan
"""
import pandas as pd
import os

os.chdir(os.path.dirname(os.path.abspath(__file__)))

df = pd.read_csv('dataset.csv')

print("Distribusi kategori SEBELUM relabeling:")
print(df['rekomendasi'].value_counts())

def remap_label(row):
    old = row['rekomendasi']
    nilai = row['nilai']
    if old == 'Pendampingan Intensif':
        return 'Program Remedial Intensif'
    elif old == 'Program Intensif':
        return 'Pendampingan Akademik'
    elif old == 'Program Reguler':
        if nilai >= 85:
            return 'Program Pengayaan'
        else:
            return 'Program Reguler'
    return old  # fallback, tidak diharapkan terjadi

df['rekomendasi'] = df.apply(remap_label, axis=1)

print("\nDistribusi kategori SETELAH relabeling:")
print(df['rekomendasi'].value_counts())

df.to_csv('dataset.csv', index=False)
print("\ndataset.csv berhasil diupdate!")
