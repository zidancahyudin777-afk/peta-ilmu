import matplotlib.pyplot as plt
import numpy as np
import os


current_dir = os.path.dirname(os.path.abspath(__file__))
os.chdir(current_dir)



data = np.array([
    [0.88, 0.88, 0.88],  
    [0.83, 0.94, 0.88],  
    [0.80, 0.71, 0.75]   
])

classes = ['Dasar', 'Mahir', 'Menengah']
metrics = ['Precision', 'Recall', 'F1-Score']


plt.figure(figsize=(8, 5), dpi=300)

im = plt.imshow(data, interpolation='nearest', cmap=plt.cm.GnBu, vmin=0.5, vmax=1.0)
plt.title('Classification Report - Random Forest Classifier', fontsize=14, fontweight='bold', pad=20)
plt.colorbar(im, fraction=0.046, pad=0.04)

plt.xticks(np.arange(len(metrics)), metrics, fontsize=11)
plt.yticks(np.arange(len(classes)), classes, fontsize=11)


plt.xlabel('Metrics', fontsize=12, fontweight='semibold', labelpad=15)
plt.ylabel('Classes', fontsize=12, fontweight='semibold', labelpad=15)


for i in range(data.shape[0]):
    for j in range(data.shape[1]):
        plt.text(j, i, f"{data[i, j]:.2f}",
                 horizontalalignment="center",
                 verticalalignment="center",
                 color="white" if data[i, j] > 0.85 else "black",
                 fontsize=14,
                 fontweight='bold')

plt.tight_layout()


output_ml_path = 'classification_report.png'
plt.savefig(output_ml_path, bbox_inches='tight')
print(f"Saved classification report plot to: {os.path.abspath(output_ml_path)}")


public_images_dir = os.path.join(current_dir, '..', 'public', 'images')
if os.path.exists(public_images_dir):
    output_public_path = os.path.join(public_images_dir, 'classification_report.png')
    plt.savefig(output_public_path, bbox_inches='tight')
    print(f"Saved classification report plot to: {os.path.abspath(output_public_path)}")

plt.close()
