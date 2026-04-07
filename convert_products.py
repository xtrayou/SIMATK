import pandas as pd
import math

file_path = r'c:\laragon\www\skripsi\simatk\public\laporan bulanan\STOCK OPNAME PERSEDIAAN FASILKOM 2025.xlsx'
df = pd.read_excel(file_path, sheet_name=0, header=9)

# Kolom 0: No
# Kolom 1: Jenis Barang
# Kolom 2: Jumlah
# Kolom 3: Harga Satuan
# Kolom 4: Total Harga
# Kolom 5: Baik
# Kolom 6: Rusak /Usang

products = []
for index, row in df.iterrows():
    name = str(row.iloc[1]).strip()
    if name == 'nan' or name == '' or 'Laporan' in name or 'TOTAL' in name:
        continue
        
    try:
        qty = float(row.iloc[2]) if not pd.isna(row.iloc[2]) else 0
        price = float(row.iloc[3]) if not pd.isna(row.iloc[3]) else 0
        baik = float(row.iloc[5]) if not pd.isna(row.iloc[5]) else qty # Default to qty if empty
        rusak = float(row.iloc[6]) if not pd.isna(row.iloc[6]) else 0
        
        # Determine "category_id" later dynamically in the seeder
        products.append({
            'name': name.replace("'", "\\'"),
            'qty': int(qty),
            'price': int(price),
            'baik': int(baik),
            'rusak': int(rusak)
        })
    except:
        continue

seeder_content = f"""<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProdukExcelSeeder extends Seeder
{{
    public function run()
    {{
        // Find category ID for "ALAT TULIS KANTOR"
        $category = $this->db->table('categories')->like('name', 'ALAT TULIS')->get()->getRow();
        $categoryId = $category ? $category->id : 1;
        
        // Coba cocokin dengan kode_barang terdekat (secara sederhana kita pake kode generic ATK)
        $genericSku = '8.01.03.01.999'; 

        $data = [];
"""

for p in products:
    seeder_content += f"""
        $data[] = [
            'name'          => '{p['name']}',
            'sku'           => $genericSku,
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname Excel',
            'price'         => {p['price']},
            'cost_price'    => {p['price']},
            'min_stock'     => 5,
            'current_stock' => {p['qty']},
            'stock_baik'    => {p['baik']},
            'stock_rusak'   => {p['rusak']},
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];"""

seeder_content += """
        // Insert Batch
        $this->db->table('products')->insertBatch($data);
    }
}
"""

with open(r'c:\laragon\www\skripsi\simatk\app\Database\Seeds\ProdukExcelSeeder.php', 'w', encoding='utf-8') as f:
    f.write(seeder_content)
print("Seeder generated!")
