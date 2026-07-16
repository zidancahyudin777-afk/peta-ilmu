import matplotlib.pyplot as plt
import numpy as np
import os

# Set target directory
current_dir = os.path.dirname(os.path.abspath(__file__))
os.chdir(current_dir)

# Confusion Matrix data
# Classes: ['Dasar', 'Mahir', 'Menengah']
cm = np.array([
    [15, 0, 2],   # Actual: Dasar
    [0, 15, 1],   # Actual: Mahir
    [2, 3, 12]    # Actual: Menengah
])

classes = ['Dasar', 'Mahir', 'Menengah']

# Create plot
plt.figure(figsize=(8, 6), dpi=300)
# Use a premium color map (Blues)
im = plt.imshow(cm, interpolation='nearest', cmap=plt.cm.Blues)
plt.title('Confusion Matrix - Random Forest Classifier', fontsize=14, fontweight='bold', pad=20)
plt.colorbar(im, fraction=0.046, pad=0.04)

tick_marks = np.arange(len(classes))
plt.xticks(tick_marks, classes, fontsize=11)
plt.yticks(tick_marks, classes, fontsize=11)

# Style labels
plt.xlabel('Predicted Label', fontsize=12, fontweight='semibold', labelpad=15)
plt.ylabel('Actual Label', fontsize=12, fontweight='semibold', labelpad=15)

# Add values inside cells with adaptive coloring based on threshold
thresh = cm.max() / 2.
for i in range(cm.shape[0]):
    for j in range(cm.shape[1]):
        plt.text(j, i, format(cm[i, j], 'd'),
                 horizontalalignment="center",
                 verticalalignment="center",
                 color="white" if cm[i, j] > thresh else "black",
                 fontsize=14,
                 fontweight='bold')

plt.tight_layout()

# Save to ML directory
output_ml_path = 'confusion_matrix.png'
plt.savefig(output_ml_path, bbox_inches='tight')
print(f"Saved confusion matrix plot to: {os.path.abspath(output_ml_path)}")

# Save to Laravel public images directory as well
public_images_dir = os.path.join(current_dir, '..', 'public', 'images')
if os.path.exists(public_images_dir):
    output_public_path = os.path.join(public_images_dir, 'confusion_matrix.png')
    plt.savefig(output_public_path, bbox_inches='tight')
    print(f"Saved confusion matrix plot to: {os.path.abspath(output_public_path)}")

plt.close()
