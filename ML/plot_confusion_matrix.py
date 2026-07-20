import matplotlib.pyplot as plt
import numpy as np
import os


current_dir = os.path.dirname(os.path.abspath(__file__))
os.chdir(current_dir)



cm = np.array([
    [15, 0, 2],   
    [0, 15, 1],   
    [2, 3, 12]    
])

classes = ['Dasar', 'Mahir', 'Menengah']


plt.figure(figsize=(8, 6), dpi=300)

im = plt.imshow(cm, interpolation='nearest', cmap=plt.cm.Blues)
plt.title('Confusion Matrix - Random Forest Classifier', fontsize=14, fontweight='bold', pad=20)
plt.colorbar(im, fraction=0.046, pad=0.04)

tick_marks = np.arange(len(classes))
plt.xticks(tick_marks, classes, fontsize=11)
plt.yticks(tick_marks, classes, fontsize=11)


plt.xlabel('Predicted Label', fontsize=12, fontweight='semibold', labelpad=15)
plt.ylabel('Actual Label', fontsize=12, fontweight='semibold', labelpad=15)


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


output_ml_path = 'confusion_matrix.png'
plt.savefig(output_ml_path, bbox_inches='tight')
print(f"Saved confusion matrix plot to: {os.path.abspath(output_ml_path)}")


public_images_dir = os.path.join(current_dir, '..', 'public', 'images')
if os.path.exists(public_images_dir):
    output_public_path = os.path.join(public_images_dir, 'confusion_matrix.png')
    plt.savefig(output_public_path, bbox_inches='tight')
    print(f"Saved confusion matrix plot to: {os.path.abspath(output_public_path)}")

plt.close()
