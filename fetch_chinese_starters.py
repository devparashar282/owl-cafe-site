import requests
import mysql.connector
import os
import time

items = {
    "Cheesy Fries": "https://loremflickr.com/600/600/fries,cheese/all",
    "Chicken 65": "https://loremflickr.com/600/600/chicken,spicy,fried,red/all",
    "Chicken Fry": "https://loremflickr.com/600/600/fried,chicken,pieces/all",
    "Chicken Lollipop (3 pcs)": "https://loremflickr.com/600/600/chicken,lollipop,fried/all",
    "Chicken Lollipop (Full)": "https://loremflickr.com/600/600/chicken,lollipop,plate/all",
    "Chicken Popcorn": "https://loremflickr.com/600/600/popcorn,chicken,fried/all",
    "Chicken Strips": "https://loremflickr.com/600/600/chicken,strips,fried/all",
    "Chicken Wings": "https://loremflickr.com/600/600/chicken,wings,fried/all",
    "Chilly Chicken (Dry)": "https://loremflickr.com/600/600/chilli,chicken,dry/all",
    "Chilly Chicken (Gravy)": "https://loremflickr.com/600/600/chilli,chicken,gravy/all",
    "Chilly Paneer": "https://loremflickr.com/600/600/chilli,paneer,spicy/all",
    "Chilly Potato": "https://loremflickr.com/600/600/chilli,potato,spicy/all",
    "Crispy Corn": "https://loremflickr.com/600/600/crispy,corn,fried/all",
    "Drums of Heaven (Half/Full)": "https://loremflickr.com/600/600/drums,chicken,spicy,fried/all",
    "French Fries": "https://loremflickr.com/600/600/french,fries/all",
    "Honey Chilly Potato": "https://loremflickr.com/600/600/honey,chilli,potato/all",
    "Lemon Chicken": "https://loremflickr.com/600/600/lemon,chicken,dry/all",
    "Lemon Paneer": "https://loremflickr.com/600/600/lemon,paneer,dry/all",
    "Peri Peri Fries": "https://loremflickr.com/600/600/fries,spicy,red/all",
    "Veg Manchurian (Dry)": "https://loremflickr.com/600/600/veg,manchurian,dry/all",
    "Veg Manchurian (Gravy)": "https://loremflickr.com/600/600/veg,manchurian,gravy/all"
}

try:
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="owl_cafe"
    )
    cursor = conn.cursor(dictionary=True)
    menu_dir = 'assets/images/menu'
    os.makedirs(menu_dir, exist_ok=True)
    
    seed = int(time.time())

    for name, base_url in items.items():
        slug = name.lower().replace(" ", "_").replace("(", "").replace(")", "").replace("/", "_")
        filename = f"{slug}.jpg"
        filepath = os.path.join(menu_dir, filename)
        url = f"{base_url}?random={seed}"
        seed += 1
        
        # Download image
        print(f"Downloading {name} from {url}...")
        response = requests.get(url, timeout=15)
        if response.status_code == 200:
            with open(filepath, 'wb') as f:
                f.write(response.content)
            
            # Update DB
            cursor.execute("UPDATE menu SET image = %s WHERE name = %s", (f"menu/{filename}", name))
            print(f"Success: {name} updated to {filename}")
        else:
            print(f"Failed to download {name}")

    conn.commit()
    cursor.close()
    conn.close()
    print("Done updating Chinese Starters items.")

except Exception as e:
    print(f"Error: {e}")
