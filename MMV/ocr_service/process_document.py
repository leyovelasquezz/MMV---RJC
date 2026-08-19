"""RJC OCR worker for document readability and completeness signals."""
import os
import sys
from PIL import Image, UnidentifiedImageError
import pytesseract

def extract(path):
    extension = os.path.splitext(path)[1].lower()
    if extension not in {'.jpg', '.jpeg', '.png'}:
        return {'ocr_text':'', 'verification_status':'Needs Review', 'verification_notes':'This file type requires a manual completeness and document-quality check.'}
    try:
        text = pytesseract.image_to_string(Image.open(path))
    except (OSError, UnidentifiedImageError, pytesseract.TesseractNotFoundError):
        return {'ocr_text':'', 'verification_status':'Unreadable', 'verification_notes':'Text could not be read for the automated completeness and document-quality check.'}
    status = 'Complete' if len(text.strip()) >= 20 else 'Unreadable'
    note = 'Automated completeness and document-quality check completed.' if status == 'Complete' else 'Too little readable text was found for the automated completeness and document-quality check.'
    return {'ocr_text':text, 'verification_status':status, 'verification_notes':note}
if __name__=='__main__': print(extract(sys.argv[1]))
