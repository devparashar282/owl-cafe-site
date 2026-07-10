import mysql.connector

try:
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="owl_cafe"
    )
    cursor = conn.cursor(dictionary=True)
    cursor.execute("""
        SELECT c.name as category, m.name as item, m.image 
        FROM menu m
        JOIN categories c ON m.category_id = c.id
        WHERE m.image IS NULL OR m.image = '' OR m.image LIKE '%default%'
        ORDER BY c.name, m.name
    """)
    rows = cursor.fetchall()
    if rows:
        print("Items missing images:")
        for r in rows:
            print(f"- {r['category']}: {r['item']} (Current image: {r['image']})")
    else:
        print("All items currently have some image set!")
        
    cursor.close()
    conn.close()

except Exception as e:
    print(f"Error: {e}")
