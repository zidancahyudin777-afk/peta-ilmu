import matplotlib.pyplot as plt
import numpy as np
import os

# Set target directory
current_dir = os.path.dirname(os.path.abspath(__file__))
os.chdir(current_dir)

# Feature Importance data
features = ['nilai_kuis', 'nilai_tugas', 'kehadiran', 'tingkat_kesulitan', 'study_duration']
importances = [0.434711, 0.363417, 0.159689, 0.027347, 0.014836]

# Sort features and importances in ascending order for horizontal bar chart
indices = np.argsort(importances)
sorted_features = [features[i] for i in indices]
sorted_importances = [importances[i] for i in indices]

# Create plot
plt.figure(figsize=(9, 5), dpi=300)
# Use a beautiful steel blue / teal color palette
colors = plt.cm.Blues(np.linspace(0.4, 0.8, len(sorted_importances)))
bars = plt.barh(sorted_features, sorted_importances, color=colors, edgecolor='none', height=0.6)

plt.title('Feature Importance - Random Forest Classifier', fontsize=14, fontweight='bold', pad=20)
plt.xlabel('Importance Score', fontsize=12, fontweight='semibold', labelpad=15)
plt.ylabel('Features', fontsize=12, fontweight='semibold', labelpad=15)
plt.xlim(0, 0.5)

# Add values at the end of each bar
for bar in bars:
    width = bar.get_width()
    plt.text(width + 0.005, bar.get_y() + bar.get_height()/2, f"{width:.4f} ({width:.2%})",
             va='center', ha='left', fontsize=10, fontweight='semibold')

plt.tight_layout()

# Save to ML directory
output_ml_path = 'feature_importance.png'
plt.savefig(output_ml_path, bbox_inches='tight')
print(f"Saved feature importance plot to: {os.path.abspath(output_ml_path)}")

# Save to Laravel public images directory
public_images_dir = os.path.join(current_dir, '..', 'public', 'images')
if os.path.exists(public_images_dir):
    output_public_path = os.path.join(public_images_dir, 'feature_importance.png')
    plt.savefig(output_public_path, bbox_inches='tight')
    print(f"Saved feature importance plot to: {os.path.abspath(output_public_path)}")

plt.close()
