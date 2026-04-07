import pymysql
import json

# Update JSON
with open(r'c:\laragon\www\skripsi\simatk\public\dataexport\kode_barang.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

for item in data:
    item['kode'] = item['kode'].replace('.', '')

with open(r'c:\laragon\www\skripsi\simatk\public\dataexport\kode_barang.json', 'w', encoding='utf-8') as f:
    json.dump(data, f, indent=4)
print("JSON updated")

# Update DB
conn = pymysql.connect(host='localhost', user='root', password='', db='db_simatk')
try:
    with conn.cursor() as cursor:
        cursor.execute("UPDATE kode_barang SET kode = REPLACE(kode, '.', '')")
        cursor.execute("UPDATE products SET sku = REPLACE(sku, '.', '')")
        cursor.execute("UPDATE categories SET description = REPLACE(description, '.', '')")
    conn.commit()
    print("DB updated")
finally:
    conn.close()
