import json
import os
import random
from datetime import datetime, timedelta

def escape(val):
    if isinstance(val, str):
        # Escape single quotes for SQL
        return "'" + val.replace("'", "''") + "'"
    elif val is None:
        return "NULL"
    return str(val)

# Paths
json_path = os.path.join(os.path.dirname(__file__), '../writable/uploads/massive_seed_data.json')
sql_path = os.path.join(os.path.dirname(__file__), 'seed.sql')

if not os.path.exists(json_path):
    print("Error: JSON file not found. Run scraper.py first.")
    exit()

with open(json_path, 'r') as f:
    properties = json.load(f)

sql = []
sql.append("SET FOREIGN_KEY_CHECKS=0;\n")

# 1. Truncate Tables
tables = ['properties', 'property_images', 'property_features', 'property_feature_map', 
          'points_of_interest', 'inquiries', 'saved_properties', 'saved_searches', 
          'advertisements', 'agent_verifications', 'property_verifications', 
          'offline_payments', 'subscriptions', 'ci_sessions', 'states', 'cities', 'zipcodes', 'users']
for table in tables:
    sql.append(f"TRUNCATE TABLE {table};")
sql.append("\n")

# 2. Insert Users
sql.append("-- Insert Users")
users = [
    "(1, 4, 'Reza', 'Avanluna', '081234567890', 'reza@estate.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Active', NOW())",
    "(2, 3, 'Taka', 'Radjiman', '081234567891', 'taka@agent.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Active', NOW())",
    "(3, 2, 'Amacia', 'Michella', '081234567892', 'amacia@owner.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Active', NOW())",
    "(4, 2, 'Miyu', 'Ottavia', '081234567893', 'miyu@owner.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Active', NOW())",
    "(5, 3, 'Bonnivier', 'Pranaja', '081234567894', 'bonni@agent.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Active', NOW())",
    "(6, 1, 'Riksa', 'Dhirendra', '081234567895', 'riksa@buyer.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Active', NOW())",
    "(7, 1, 'Etna', 'Crimson', '081234567896', 'etna@buyer.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Active', NOW())"
]
sql.append("INSERT INTO users (id, role_id, first_name, last_name, phone_number, email, password, status, created_date) VALUES")
sql.append(",\n".join(users) + ";\n")

owner_ids = [2, 3, 4, 5]
buyer_ids = [6, 7]

state_map = {}
city_map = {}
type_map = {}
state_id_counter = 1
city_id_counter = 1
type_id_counter = 1
property_id_counter = 1

sql.append("-- Insert Properties, Images, POIs, and Inquiries")

for prop in properties:
    # Handle States
    state_name = prop['province_name']
    if state_name not in state_map:
        state_map[state_name] = state_id_counter
        sql.append(f"INSERT INTO states (id, name, status) VALUES ({state_id_counter}, {escape(state_name)}, 'Active');")
        state_id_counter += 1
    state_id = state_map[state_name]

    # Handle Cities
    city_name = prop['city_name']
    if city_name not in city_map:
        city_map[city_name] = city_id_counter
        sql.append(f"INSERT INTO cities (id, state_id, name, status) VALUES ({city_id_counter}, {state_id}, {escape(city_name)}, 'Active');")
        city_id_counter += 1
    city_id = city_map[city_name]

    # Handle Types
    type_name = prop['property_type_name']
    if type_name not in type_map:
        type_map[type_name] = type_id_counter
        sql.append(f"INSERT INTO property_types (id, type_name, status) VALUES ({type_id_counter}, {escape(type_name)}, 'Active');")
        type_id_counter += 1
    type_id = type_map[type_name]

    # Handle Property
    owner_id = random.choice(owner_ids)
    created_date = (datetime.now() - timedelta(days=random.randint(1, 30))).strftime('%Y-%m-%d %H:%M:%S')
    
    # Generate unique slug
    import re
    base_slug = re.sub(r'[^a-z0-9]+', '-', prop['title'].lower()).strip('-')
    
    sql.append(f"INSERT INTO properties (id, owner_id, city_id, property_type_id, title, slug, description, listing_type, tax_price, bed, bath, total_area, latitude, longitude, status, approval_status, created_date) VALUES ({property_id_counter}, {owner_id}, {city_id}, {type_id}, {escape(prop['title'])}, {escape(base_slug)}, {escape(prop['description'])}, {escape(prop['listing_type'])}, {prop['tax_price']}, {prop['bed']}, {prop['bath']}, {prop['total_area']}, {prop['latitude']}, {prop['longitude']}, 'Active', 'Published', {escape(created_date)});")

    # Handle Images
    for idx, img in enumerate(prop['images']):
        is_primary = 1 if idx == 0 else 0
        sql.append(f"INSERT INTO property_images (property_id, image_path, is_primary) VALUES ({property_id_counter}, {escape(img)}, {is_primary});")

    # Handle POIs
    for poi in prop['pois']:
        sql.append(f"INSERT INTO points_of_interest (property_id, name, category, latitude, longitude, distance_km, created_at) VALUES ({property_id_counter}, {escape(poi['name'])}, {escape(poi['category'])}, {poi['latitude']}, {poi['longitude']}, {poi['distance_km']}, NOW());")

    # Handle Inquiries (25% chance)
    if random.randint(1, 100) <= 25:
        buyer_id = random.choice(buyer_ids)
        status = random.choice(['Pending', 'In Discussion', 'Negotiating', 'Under Contract'])
        msg = 'Hi, I saw this property on HuniKita and I am very interested. Can we schedule a viewing?'
        sql.append(f"INSERT INTO inquiries (property_id, sender_id, receiver_id, message, status, created_at) VALUES ({property_id_counter}, {buyer_id}, {owner_id}, {escape(msg)}, {escape(status)}, NOW());")

    property_id_counter += 1

sql.append("\nSET FOREIGN_KEY_CHECKS=1;\n")

with open(sql_path, 'w') as f:
    f.write("\n".join(sql))

print(f"Success! {sql_path} has been generated.")