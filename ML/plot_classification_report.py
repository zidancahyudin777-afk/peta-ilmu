import matplotlib.pyplot as plt
import numpy as np
import os

# Set target directory
current_dir = os.path.dirname(os.path.abspath(__file__))
os.chdir(current_dir)

# Classification Report data
# Format: [Precision, Recall, F1-Score]
data = np.array([
    [0.88, 0.88, 0.88],  # Dasar
    [0.83, 0.94, 0.88],  # Mahir
    [0.80, 0.71, 0.75]   # Menengah
])

classes = ['Dasar', 'Mahir', 'Menengah']
metrics = ['Precision', 'Recall', 'F1-Score']

# Create plot
plt.figure(figsize=(8, 5), dpi=300)
# Use GnBu color map (Green-Blue) for distinct and clean presentation
im = plt.imshow(data, interpolation='nearest', cmap=plt.cm.GnBu, vmin=0.5, vmax=1.0)
plt.title('Classification Report - Random Forest Classifier', fontsize=14, fontweight='bold', pad=20)
plt.colorbar(im, fraction=0.046, pad=0.04)

plt.xticks(np.arange(len(metrics)), metrics, fontsize=11)
plt.yticks(np.arange(len(classes)), classes, fontsize=11)

# Style labels
plt.xlabel('Metrics', fontsize=12, fontweight='semibold', labelpad=15)
plt.ylabel('Classes', fontsize=12, fontweight='semibold', labelpad=15)

# Add values inside cells
for i in range(data.shape[0]):
    for j in range(data.shape[1]):
        plt.text(j, i, f"{data[i, j]:.2f}",
                 horizontalalignment="center",
                 verticalalignment="center",
                 color="white" if data[i, j] > 0.85 else "black",
                 fontsize=14,
                 fontweight='bold')

plt.tight_layout()

# Save to ML directory
output_ml_path = 'classification_report.png'
plt.savefig(output_ml_path, bbox_inches='tight')
print(f"Saved classification report plot to: {os.path.abspath(output_ml_path)}")

# Save to Laravel public images directory
public_images_dir = os.path.join(current_dir, '..', 'public', 'images')
if os.path.exists(public_images_dir):
    output_public_path = os.path.join(public_images_dir, 'classification_report.png')
    plt.savefig(output_public_path, bbox_inches='tight')
    print(f"Saved classification report plot to: {os.path.abspath(output_public_path)}")

plt.close()
