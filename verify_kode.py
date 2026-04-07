import json

with open(r'C:\laragon\www\skripsi\simatk\public\dataexport\kode_barang.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

print(f"Total items extracted: {len(data)}")

print("\n--- 20 PERTAMA ---")
for d in data[:20]:
    print(f"  {d['kode']}  {d['nama']}")

print("\n--- 20 TERAKHIR ---")
for d in data[-20:]:
    print(f"  {d['kode']}  {d['nama']}")

# Check for known items from ATK section
print("\n--- CEK ATK (8.01.03.01.xxx) ---")
atk = [d for d in data if d['kode'].startswith('8.01.03.01')]
for d in atk:
    print(f"  {d['kode']}  {d['nama']}")

print("\n--- CEK KERTAS (8.01.03.02.xxx) ---")
kertas = [d for d in data if d['kode'].startswith('8.01.03.02')]
for d in kertas:
    print(f"  {d['kode']}  {d['nama']}")

print("\n--- CEK BAHAN KOMPUTER (8.01.03.04.xxx) ---")
komputer = [d for d in data if d['kode'].startswith('8.01.03.04')]
for d in komputer:
    print(f"  {d['kode']}  {d['nama']}")

# Count by major category
print("\n--- KATEGORI UTAMA ---")
categories = {}
for d in data:
    parts = d['kode'].split('.')
    if len(parts) >= 3:
        cat = '.'.join(parts[:3])
        categories[cat] = categories.get(cat, 0) + 1
for cat, count in sorted(categories.items()):
    print(f"  {cat}: {count} items")
