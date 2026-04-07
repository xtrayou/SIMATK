import json

with open(r'c:\laragon\www\skripsi\simatk\public\dataexport\kode_barang.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

seeder_content = f"""<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KodeBarangSeeder extends Seeder
{{
    public function run()
    {{
        // Kosongkan tabel kode_barang terlebih dahulu
        $this->db->table('kode_barang')->emptyTable();

        $data = [\n"""

for i, item in enumerate(data):
    nama = item['nama'].replace("'", "\\'")
    kode = item['kode']
    
    seeder_content += f"            [\n"
    seeder_content += f"                'kode'       => '{kode}',\n"
    seeder_content += f"                'nama'       => '{nama}',\n"
    seeder_content += f"                'created_at' => date('Y-m-d H:i:s'),\n"
    seeder_content += f"                'updated_at' => date('Y-m-d H:i:s'),\n"
    seeder_content += f"            ],\n"

seeder_content += """        ];

        // Insert Batch
        $this->db->table('kode_barang')->insertBatch($data);
    }
}
"""

with open(r'c:\laragon\www\skripsi\simatk\app\Database\Seeds\KodeBarangSeeder.php', 'w', encoding='utf-8') as f:
    f.write(seeder_content)
print("Seeder file KodeBarangSeeder.php generated successfully.")
