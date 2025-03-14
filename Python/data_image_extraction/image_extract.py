import pytesseract
from PIL import Image
pytesseract.pytesseract.tesseract_cmd = r"C:\Program Files\Tesseract-OCR\tesseract.exe"

print(pytesseract.get_tesseract_version())  # Verify installation

# Load an image of the document
image = Image.open("id.jpg")

# Extract text
text = pytesseract.image_to_string(image)

print("Extracted Text:", text)
