import json

with open(r'c:\laragon\www\skripsi\simatk\public\dataexport\kode_barang.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

kategori_list = []
for item in data:
    parts = item['kode'].split('.')
    if len(parts) == 5:
        gol, bid, kel, subkel, subsub = parts
        # Ambil entri SUBKEL level: subsub=000, subkel tidak 00, kel tidak 00
        if subsub == '000' and subkel != '00' and kel != '00':
            nama = item['nama'].strip().replace("'", "\\'")
            kode = item['kode']
            # Buat deskripsi dari kode BID
            bid_prefix = f'{gol}.{bid}'
            deskripsi_map = {
                '8.01': 'Barang Pakai Habis',
                '8.02': 'Barang Tak Habis Pakai',
                '8.03': 'Barang Bekas Dipakai',
            }
            desc = deskripsi_map.get(bid_prefix, 'Persediaan Barang')
            
            # Gabungkan nama dengan kodenya agar unik dan jelas
            # name = f"{kode} - {nama}"
            # Sebaiknya nama = nama, description = desc + " | Kode: " + kode
            kategori_list.append({
                'name': nama,
                'description': f"{desc} | Kode: {kode}"
            })

seeder_content = f"""<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KategoriPerbuSeeder extends Seeder
{{
    public function run()
    {{
        // Kosongkan tabel categories terlebih dahulu (opsional)
        // Jika tidak ingin menghapus data lama, comment baris di bawah
        $this->db->table('categories')->emptyTable();

        $data = [
"""

for k in kategori_list:
    seeder_content += f"            [\n"
    seeder_content += f"                'name'        => '{k['name']}',\n"
    seeder_content += f"                'description' => '{k['description']}',\n"
    seeder_content += f"                'is_active'   => 1,\n"
    seeder_content += f"                'created_at'  => date('Y-m-d H:i:s'),\n"
    seeder_content += f"                'updated_at'  => date('Y-m-d H:i:s'),\n"
    seeder_content += f"            ],\n"

seeder_content += """        ];

        // Using Query Builder
        $this->db->table('categories')->insertBatch($data);
    }
}
"""

with open(r'c:\laragon\www\skripsi\simatk\app\Database\Seeds\KategoriPerbuSeeder.php', 'w', encoding='utf-8') as f:
    f.write(seeder_content)
