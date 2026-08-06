import json
import os
import random
import uuid

# Comprehensive representation of Indonesian Provinces & Major Cities nationwide
dataset = [
    {
        "province": "DKI Jakarta", "province_slug": "dki-jakarta",
        "cities": [
            {"name": "Jakarta Selatan", "slug": "jakarta-selatan", "zip": "12110", "lat": -6.2615, "lng": 106.8106},
            {"name": "Jakarta Pusat", "slug": "jakarta-pusat", "zip": "10110", "lat": -6.1805, "lng": 106.8284},
            {"name": "Jakarta Barat", "slug": "jakarta-barat", "zip": "11110", "lat": -6.1683, "lng": 106.7588},
        ]
    },
    {
        "province": "Jawa Barat", "province_slug": "jawa-barat",
        "cities": [
            {"name": "Bandung", "slug": "bandung", "zip": "40111", "lat": -6.9175, "lng": 107.6191},
            {"name": "Bogor", "slug": "bogor", "zip": "16111", "lat": -6.5971, "lng": 106.7902},
            {"name": "Bekasi Selatan", "slug": "bekasi-selatan", "zip": "17141", "lat": -6.2625, "lng": 106.9749}
        ]
    },
    {
        "province": "Jawa Tengah", "province_slug": "jawa-tengah",
        "cities": [
            {"name": "Semarang", "slug": "semarang", "zip": "50111", "lat": -6.9932, "lng": 110.4203},
            {"name": "Surakarta", "slug": "surakarta", "zip": "57111", "lat": -7.5666, "lng": 110.8266},
        ]
    },
    {
        "province": "Jawa Timur", "province_slug": "jawa-timur",
        "cities": [
            {"name": "Surabaya", "slug": "surabaya", "zip": "60111", "lat": -7.2504, "lng": 112.7688},
            {"name": "Malang", "slug": "malang", "zip": "65111", "lat": -7.9797, "lng": 112.6304},
        ]
    },
    {
        "province": "Banten", "province_slug": "banten",
        "cities": [
            {"name": "Tangerang", "slug": "tangerang", "zip": "15111", "lat": -6.1702, "lng": 106.6403},
        ]
    },
    {
        "province": "DI Yogyakarta", "province_slug": "di-yogyakarta",
        "cities": [
            {"name": "Yogyakarta", "slug": "yogyakarta", "zip": "55111", "lat": -7.7956, "lng": 110.3695},
        ]
    },
    {
        "province": "Bali", "province_slug": "bali",
        "cities": [
            {"name": "Denpasar", "slug": "denpasar", "zip": "80111", "lat": -8.6705, "lng": 115.2128},
            {"name": "Badung", "slug": "badung", "zip": "80361", "lat": -8.5816, "lng": 115.1772},
        ]
    },
    {
        "province": "Sumatera Utara", "province_slug": "sumatera-utara",
        "cities": [
            {"name": "Medan", "slug": "medan", "zip": "20111", "lat": 3.5952, "lng": 98.6722},
        ]
    },
    {
        "province": "Sumatera Selatan", "province_slug": "sumatera-selatan",
        "cities": [
            {"name": "Palembang", "slug": "palembang", "zip": "30111", "lat": -2.9909, "lng": 104.7566},
        ]
    },
    {
        "province": "Sulawesi Selatan", "province_slug": "sulawesi-selatan",
        "cities": [
            {"name": "Makassar", "slug": "makassar", "zip": "90111", "lat": -5.1477, "lng": 119.4327},
        ]
    },
    {
        "province": "Kalimantan Timur", "province_slug": "kalimantan-timur",
        "cities": [
            {"name": "Balikpapan", "slug": "balikpapan", "zip": "76111", "lat": -1.2379, "lng": 116.8529},
            {"name": "Samarinda", "slug": "samarinda", "zip": "75111", "lat": -0.5022, "lng": 117.1536}
        ]
    },
    {
        "province": "Papua", "province_slug": "papua",
        "cities": [
            {"name": "Jayapura", "slug": "jayapura", "zip": "99111", "lat": -2.5337, "lng": 140.7181},
        ]
    }
]

property_types = ["House", "Villa", "Apartment", "Townhouse"]
adjectives = ["Modern Tropical", "Luxury Minimalist", "Cozy Family", "Spacious Premier", "Exclusive Hillside", "Grand Estate", "Urban Chic", "Classic Heritage"]

sample_images = [
    "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&q=80",
    "https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&q=80",
    "https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80",
    "https://images.unsplash.com/photo-1600607687920-4e2a09be1587?w=800&q=80",
    "https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&q=80",
    "https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800&q=80",
    "https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&q=80"
]

# Detailed POI Categories
poi_categories = [
    "Hospital", "Shopping Mall", "Supermarket", "Train Station", 
    "Kindergarten", "Playgroup", "Elementary School", "Middle School", "High School"
]

generated_properties = []

for state in dataset:
    for city in state["cities"]:
        for i in range(10): # 10 properties per city
            ptype = random.choice(property_types)
            adj = random.choice(adjectives)
            
            # Guarantee uniqueness with a short hash
            unique_id = str(uuid.uuid4())[:6].upper()
            title = f"{adj} {ptype} in {city['name']} - {unique_id}"

            lat_offset = random.uniform(-0.015, 0.015)
            lng_offset = random.uniform(-0.015, 0.015)
            prop_lat = round(city["lat"] + lat_offset, 6)
            prop_lng = round(city["lng"] + lng_offset, 6)

            # Generate 4 POIs slightly offset from the property
            pois = []
            for _ in range(4):
                poi_lat = round(prop_lat + random.uniform(-0.005, 0.005), 6)
                poi_lng = round(prop_lng + random.uniform(-0.005, 0.005), 6)
                cat = random.choice(poi_categories)
                pois.append({
                    "name": f"{city['name']} Central {cat}",
                    "category": cat,
                    "latitude": poi_lat,
                    "longitude": poi_lng,
                    "distance_km": round(random.uniform(0.2, 4.0), 1)
                })

            generated_properties.append({
                "title": title,
                "description": f"Beautiful {ptype.lower()} located in the prime area of {city['name']}, {state['province']}. Features high-end finishes and close proximity to public facilities.",
                "property_type_name": ptype,
                "province_name": state["province"],
                "province_slug": state["province_slug"],
                "city_name": city["name"],
                "city_slug": city["slug"],
                "zipcode": city["zip"],
                "listing_type": random.choice(["Sale", "Rent"]),
                "latitude": prop_lat,
                "longitude": prop_lng,
                "bed": random.randint(2, 6),
                "bath": random.randint(1, 4),
                "total_area": random.randint(100, 500),
                "tax_price": random.randint(15, 120) * 100000000, 
                "images": random.sample(sample_images, 3),
                "pois": pois
            })

output_dir = os.path.join(os.path.dirname(__file__), '../writable/uploads')
os.makedirs(output_dir, exist_ok=True)
output_file = os.path.join(output_dir, 'massive_seed_data.json')

with open(output_file, 'w') as f:
    json.dump(generated_properties, f, indent=4)

print(f"Generated {len(generated_properties)} unique properties with detailed POIs saved to {output_file}")