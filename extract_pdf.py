import fitz
import json
import re

# Try both PDFs
for pdf_name in ['Perbu 44 Tahun 2012 Kode barang.pdf', 'Perbu 44 Tahun 2012 Lampiran I.pdf']:
    pdf_path = rf'C:\laragon\www\skripsi\simatk\public\kodebarang\{pdf_name}'
    print(f"\n{'='*60}")
    print(f"FILE: {pdf_name}")
    print(f"{'='*60}")
    
    doc = fitz.open(pdf_path)
    
    for page_num in range(min(5, len(doc))):
        page = doc[page_num]
        
        # Try table extraction
        tables = page.find_tables()
        if tables.tables:
            print(f"\n--- PAGE {page_num+1} (TABLES FOUND: {len(tables.tables)}) ---")
            for t_idx, table in enumerate(tables.tables):
                print(f"\nTable {t_idx+1}:")
                data = table.extract()
                for row in data[:30]:  # limit rows
                    # Clean row
                    cleaned = [str(cell).strip() if cell else '' for cell in row]
                    if any(cleaned):
                        print(' | '.join(cleaned))
        else:
            print(f"\n--- PAGE {page_num+1} (NO TABLES, raw text) ---")
            # Get text blocks
            blocks = page.get_text("blocks")
            for b in blocks[:20]:
                text = b[4].strip()
                if text:
                    print(text)
    
    doc.close()
