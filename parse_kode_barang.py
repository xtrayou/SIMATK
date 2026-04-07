import re
import json

txt_file = r"C:\laragon\www\skripsi\simatk\Perbu 44 Tahun 2012 Kode barang.txt"
json_file = r"C:\laragon\www\skripsi\simatk\public\dataexport\kode_barang.json"
kode_barang_list = []

with open(txt_file, 'r', encoding='utf-8') as f:
    for line in f:
        line = line.strip()
        # Look for lines formatted like "8 | 01 | 01 | 01 | 001 |  | Aspal"
        if line.startswith('8 |'):
            parts = [p.strip() for p in line.split('|')]
            if len(parts) >= 7:
                kode = f"{parts[0]}.{parts[1]}.{parts[2]}.{parts[3]}.{parts[4]}"
                nama = parts[6]
                if nama:
                    kode_barang_list.append({"kode": kode, "nama": nama})

# Ensure directory exists
import os
os.makedirs(os.path.dirname(json_file), exist_ok=True)

with open(json_file, 'w', encoding='utf-8') as f:
    json.dump(kode_barang_list, f, indent=4)

print(f"Extracted {len(kode_barang_list)} item codes to {json_file}")
