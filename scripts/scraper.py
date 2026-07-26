import json
import os
import random

# Geographic Taxonomies matching mentor requirements
dataset = [
    {
        "province": "Bali",
        "province_slug": "bali",
        "cities": [
            {"name": "Denpasar", "slug": "denpasar", "zip": "80111", "lat": -8.6705, "lng": 115.2128},
            {"name": "Badung", "slug": "badung", "zip": "80361", "lat": -8.5816, "lng": 115.1772},
            {"name": "Gianyar", "slug": "gianyar", "zip": "80511", "lat": -8.5385, "lng": 115.3259},
            {"name": "Buleleng", "slug": "buleleng", "zip": "81111", "lat": -8.1120, "lng": 115.0884},
            {"name": "Tabanan", "slug": "tabanan", "zip": "82111", "lat": -8.5372, "lng": 115.1257}
        ]
    },
    {
        "province": "Jawa Barat",
        "province_slug": "jawa-barat",
        "cities": [
            {"name": "Bandung", "slug": "bandung", "zip": "40111", "lat": -6.9175, "lng": 107.6191},
            {"name": "Bogor", "slug": "bogor", "zip": "16111", "lat": -6.5971, "lng": 106.7902},
            {"name": "Depok", "slug": "depok", "zip": "16411", "lat": -6.4025, "lng": 106.8227},
            {"name": "Bekasi", "slug": "bekasi", "zip": "17111", "lat": -6.2383, "lng": 106.9756},
            {"name": "Cimahi", "slug": "cimahi", "zip": "40511", "lat": -6.8725, "lng": 107.5456}
        ]
    },
    {
        "province": "DKI Jakarta",
        "province_slug": "dki-jakarta",
        "cities": [
            {"name": "Jakarta Selatan", "slug": "jakarta-selatan", "zip": "12110", "lat": -6.2615, "lng": 106.8106},
            {"name": "Jakarta Pusat", "slug": "jakarta-pusat", "zip": "10110", "lat": -6.1805, "lng": 106.8284},
            {"name": "Jakarta Barat", "slug": "jakarta-barat", "zip": "11110", "lat": -6.1683, "lng": 106.7588},
            {"name": "Jakarta Utara", "slug": "jakarta-utara", "zip": "14110", "lat": -6.1384, "lng": 106.8869},
            {"name": "Jakarta Timur", "slug": "jakarta-timur", "zip": "13110", "lat": -6.2250, "lng": 106.9004}
        ]
    }
]

property_types = ["House", "Villa", "Apartment", "Townhouse"]
adjectives = ["Modern Tropical", "Luxury Minimalist", "Cozy Family", "Spacious Premier", "Exclusive Hillside", "Grand Estate"]

sample_images = [
    "https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800&q=80",
    "https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&q=80",
    "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&q=80",
    "https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&q=80",
    "https://images.unsplash.com/photo-1600607687920-4e2a09be1587?w=800&q=80"
]

generated_properties = []

# Generate 15 properties per city (225 properties total)
for state in dataset:
    for city in state["cities"]:
        for i in range(15):
            ptype = random.choice(property_types)
            adj = random.choice(adjectives)
            
            # Map clustering offsets (+/- ~2km radius)
            lat_offset = random.uniform(-0.025, 0.025)
            lng_offset = random.uniform(-0.025, 0.025)

            generated_properties.append({
                "title": f"{adj} {ptype} in {city['name']}",
                "description": f"Beautiful {ptype.lower()} located in the prime area of {city['name']}, {state['province']}.",
                "property_type_name": ptype,
                "province_name": state["province"],
                "province_slug": state["province_slug"],
                "city_name": city["name"],
                "city_slug": city["slug"],
                "zipcode": city["zip"],
                "listing_type": random.choice(["Sale", "Rent"]),
                "latitude": round(city["lat"] + lat_offset, 6),
                "longitude": round(city["lng"] + lng_offset, 6),
                "bed": random.randint(2, 6),
                "bath": random.randint(1, 4),
                "total_area": random.randint(100, 500),
                "total_land_area": random.randint(120, 600),
                "tax_price": random.randint(15, 120) * 100000000, # Realistic IDR values
                "images": random.sample(sample_images, 3) # 3 photos per property
            })

output_dir = os.path.join(os.path.dirname(__file__), '../writable/uploads')
os.makedirs(output_dir, exist_ok=True)
output_file = os.path.join(output_dir, 'massive_seed_data.json')

with open(output_file, 'w') as f:
    json.dump(generated_properties, f, indent=4)

print(f"Generated {len(generated_properties)} properties saved to {output_file}")