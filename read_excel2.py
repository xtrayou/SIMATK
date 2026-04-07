import pandas as pd

file_path = r'c:\laragon\www\skripsi\simatk\public\laporan bulanan\STOCK OPNAME PERSEDIAAN FASILKOM 2025.xlsx'
df = pd.read_excel(file_path, sheet_name=0, header=None)

# print out row 0 to 20
print(df.head(20).to_string())
