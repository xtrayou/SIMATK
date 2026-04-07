import json

with open(r'C:\laragon\www\skripsi\simatk\public\dataexport\kode_barang.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

kategori_list = []
for item in data:
    parts = item['kode'].split('.')
    if len(parts) == 5:
        gol, bid, kel, subkel, subsub = parts
        # Ambil entri SUBKEL level: subsub=000, subkel tidak 00, kel tidak 00
        if subsub == '000' and subkel != '00' and kel != '00':
            nama = item['nama'].strip()
            kode = item['kode']
            # Buat deskripsi dari kode BID
            bid_prefix = f'{gol}.{bid}'
            deskripsi_map = {
                '8.01': 'Barang Pakai Habis',
                '8.02': 'Barang Tak Habis Pakai',
                '8.03': 'Barang Bekas Dipakai',
            }
            desc = deskripsi_map.get(bid_prefix, 'Persediaan Barang')
            kategori_list.append({'kode': kode, 'nama': nama, 'desc': desc})

with open(r'C:\laragon\www\skripsi\simatk\kategori_output.txt', 'w', encoding='utf-8') as f:
    f.write(f"Total: {len(kategori_list)}\n\n")
    for k in kategori_list:
        f.write(f"  {k['kode']}  |  {k['nama']}  |  {k['desc']}\n")

print(f"Done. Total: {len(kategori_list)} kategori")
