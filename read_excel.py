import pandas as pd

file_path = r'c:\laragon\www\skripsi\simatk\public\laporan bulanan\STOCK OPNAME PERSEDIAAN FASILKOM 2025.xlsx'
df = pd.read_excel(file_path, sheet_name=None)

for sheet_name, sheet_df in df.items():
    print(f"Sheet: {sheet_name}")
    print(f"Columns: {sheet_df.columns.tolist()}")
    print("First 5 rows:")
    print(sheet_df.head())
    print("-" * 50)
