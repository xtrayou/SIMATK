import fitz

for pdf_name in ['Perbu 44 Tahun 2012 Kode barang.pdf', 'Perbu 44 Tahun 2012 Lampiran I.pdf']:
    pdf_path = rf'C:\laragon\www\skripsi\simatk\public\kodebarang\{pdf_name}'
    out_path = rf'C:\laragon\www\skripsi\simatk\{pdf_name.replace(".pdf","")}.txt'
    
    doc = fitz.open(pdf_path)
    with open(out_path, 'w', encoding='utf-8') as f:
        f.write(f"FILE: {pdf_name}\nPages: {len(doc)}\n{'='*80}\n\n")
        for page_num in range(len(doc)):
            page = doc[page_num]
            f.write(f"\n{'='*40} PAGE {page_num+1} {'='*40}\n")
            
            # Try table extraction first
            tables = page.find_tables()
            if tables.tables:
                for t_idx, table in enumerate(tables.tables):
                    f.write(f"\n[Table {t_idx+1}]\n")
                    data = table.extract()
                    for row in data:
                        cleaned = [str(cell).strip() if cell else '' for cell in row]
                        f.write(' | '.join(cleaned) + '\n')
            else:
                f.write(page.get_text() + '\n')
        doc.close()
    print(f"Done: {out_path}")
