import pymysql

conn = pymysql.connect(host='localhost', user='root', password='', db='db_simatk')
try:
    with conn.cursor() as cursor:
        cursor.execute("SET FOREIGN_KEY_CHECKS=0")
        cursor.execute("TRUNCATE TABLE products")
        cursor.execute("TRUNCATE TABLE stock_movements")
        cursor.execute("SET FOREIGN_KEY_CHECKS=1")
    conn.commit()
    print("Tables truncated!")
finally:
    conn.close()
