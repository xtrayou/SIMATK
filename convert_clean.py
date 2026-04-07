import pandas as pd
import json

# Load kode_barang.json (now without dots)
with open(r'c:\laragon\www\skripsi\simatk\public\dataexport\kode_barang.json', 'r', encoding='utf-8') as f:
    kode_barang_list = json.load(f)

def find_best_kode(name):
    name_lower = name.lower()
    
    # Custom mappings mapping to no-dot versions
    if 'kertas hvs' in name_lower: return '8010302001' # Kertas HVS
    if 'amplop' in name_lower: return '8010302004' # Amplop
    if 'baterai' in name_lower: return '8010306002' # Baterai
    if 'binder' in name_lower or 'kertas' in name_lower: return '8010301003' # Penjepit kertas
    if 'tinta' in name_lower: return '8010301002' # Tinta tulis
    if 'pulpen' in name_lower or 'ballpoin' in name_lower or 'balliner' in name_lower: return '8010301001' # Alat Tulis
    if 'cutter' in name_lower: return '8010301008' # Cutter
    if 'stapler' in name_lower or 'staples' in name_lower: return '8010301012' # Staples
    if 'map' in name_lower or 'ordner' in name_lower or 'box file' in name_lower: return '8010301006' # Ordner dan Map
    if 'flashdisk' in name_lower or 'flash disk' in name_lower: return '8010304006' # USB/Flash Disk
    
    for kb in kode_barang_list:
        kb_nama = kb['nama'].lower()
        if kb_nama in name_lower or name_lower in kb_nama:
            return kb['kode']
            
    return '8010301999' # Alat Tulis Kantor Lainnya

file_path = r'c:\laragon\www\skripsi\simatk\public\laporan bulanan\STOCK OPNAME PERSEDIAAN FASILKOM 2025.xlsx'
df = pd.read_excel(file_path, sheet_name=0, header=9)

products = []
for index, row in df.iterrows():
    try:
        # Pengecekan ketat: Kolom No harus bisa diconvert ke integer (berarti itu baris barang valid, bukan tanda tangan dekan dll)
        no_val = row.iloc[0]
        if pd.isna(no_val):
            continue
        int(no_val)
    except:
        continue # Bukan baris produk yang valid (contoh: tanda tangan dekan, total, dll)

    name = str(row.iloc[1]).strip()
    if name == 'nan' or name == '':
        continue
        
    try:
        qty = float(row.iloc[2]) if not pd.isna(row.iloc[2]) else 0
        price = float(row.iloc[3]) if not pd.isna(row.iloc[3]) else 0
        baik = float(row.iloc[5]) if not pd.isna(row.iloc[5]) and str(row.iloc[5]).strip() != '' else qty
        rusak = float(row.iloc[6]) if not pd.isna(row.iloc[6]) and str(row.iloc[6]).strip() != '' else 0
        
        sku = find_best_kode(name)
        
        products.append({
            'name': name.replace("'", "\\'"),
            'sku': sku,
            'qty': int(qty),
            'price': int(price),
            'baik': int(baik),
            'rusak': int(rusak)
        })
    except:
        continue

# Buat Seeder
seeder_content = f"""<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProdukExcelSeeder extends Seeder
{{
    public function run()
    {{
        // Empty table first
        $this->db->table('products')->emptyTable();
        
        $categories = $this->db->table('categories')->get()->getResultArray();
        $catMap = [];
        foreach($categories as $c) {{
            if(preg_match('/Kode: ([\d]+)/', $c['description'], $matches)) {{
                $catMap[$matches[1]] = $c['id'];
            }}
        }}

        $data = [];
"""

sku_counts = {}

for p in products:
    base_sku = p['sku']
    if base_sku not in sku_counts:
        sku_counts[base_sku] = 1
        sku = base_sku
    else:
        sku_counts[base_sku] += 1
        sku = f"{base_sku}{sku_counts[base_sku]:03d}"
        
    seeder_content += f"""
        // Determine Category ID
        $kodeBarang = '{p['sku']}';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {{
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {{
                $categoryId = $catMap[$parentKode];
            }}
        }}

        $data[] = [
            'name'          => '{p['name']}',
            'sku'           => '{sku}',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
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
        $this->db->table('products')->insertBatch($data);
    }
}
"""

with open(r'c:\laragon\www\skripsi\simatk\app\Database\Seeds\ProdukExcelSeeder.php', 'w', encoding='utf-8') as f:
    f.write(seeder_content)
print(f"Seeder generated with {len(products)} valid products!")
