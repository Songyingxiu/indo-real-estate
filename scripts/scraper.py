import json
import os

# Ensure the output directory exists
output_dir = os.path.join(os.path.dirname(__file__), '../writable/uploads')
os.makedirs(output_dir, exist_ok=True)

# Sample extracted data structure
mock_properties = [
    {
        "title": "Modern Villa in Seminyak",
        "price_raw": "Rp 4.500.000.000",
        "location": "Seminyak, Badung, Bali",
        "bedrooms": 3,
        "image_url": "https://images.unsplash.com/photo-1613977257363-707ba9348227"
    }
]

output_file = os.path.join(output_dir, 'bali_seed_data.json')

with open(output_file, 'w') as f:
    json.dump(mock_properties, f, indent=4)

print(f"File successfully created at: {output_file}")