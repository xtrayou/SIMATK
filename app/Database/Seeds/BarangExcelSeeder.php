<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BarangExcelSeeder extends Seeder
{
    public function run()
    {
        // Empty table first
        $this->db->table('products')->emptyTable();
        
        $categories = $this->db->table('categories')->get()->getResultArray();
        $catMap = [];
        foreach($categories as $c) {
            if(preg_match('/Kode: ([\d]+)/', $c['description'], $matches)) {
                $catMap[$matches[1]] = $c['id'];
            }
        }

        $data = [];

        // Determine Category ID
        $kodeBarang = '8010302004';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Amplop Coklat F4 Polos',
            'sku'           => '8010302004',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 46454,
            'cost_price'    => 46454,
            'min_stock'     => 5,
            'current_stock' => 2,
            'stock_baik'    => 2,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010302004';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Amplop Polos Putih No.104',
            'sku'           => '8010302004002',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 19592,
            'cost_price'    => 19592,
            'min_stock'     => 5,
            'current_stock' => 1,
            'stock_baik'    => 1,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010302004';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Amplop Polos Putih No.110',
            'sku'           => '8010302004003',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 22089,
            'cost_price'    => 22089,
            'min_stock'     => 5,
            'current_stock' => 2,
            'stock_baik'    => 2,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010302004';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Amplop Polos Putih No.90',
            'sku'           => '8010302004004',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 26307,
            'cost_price'    => 26307,
            'min_stock'     => 5,
            'current_stock' => 2,
            'stock_baik'    => 2,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301001';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Balliner Biru Bergaris Medium',
            'sku'           => '8010301001',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 19601,
            'cost_price'    => 19601,
            'min_stock'     => 5,
            'current_stock' => 15,
            'stock_baik'    => 15,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301001';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Balliner Hitam Bergaris Medium',
            'sku'           => '8010301001002',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 19601,
            'cost_price'    => 19601,
            'min_stock'     => 5,
            'current_stock' => 50,
            'stock_baik'    => 50,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301001';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Ballpoin Warna Biru',
            'sku'           => '8010301001003',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 4440,
            'cost_price'    => 4440,
            'min_stock'     => 5,
            'current_stock' => 40,
            'stock_baik'    => 40,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301001';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Ballpoin Warna Hitam',
            'sku'           => '8010301001004',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 4440,
            'cost_price'    => 4440,
            'min_stock'     => 5,
            'current_stock' => 62,
            'stock_baik'    => 62,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301006';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Bantex Box File Warna Maroon',
            'sku'           => '8010301006',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 55691,
            'cost_price'    => 55691,
            'min_stock'     => 5,
            'current_stock' => 5,
            'stock_baik'    => 5,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301006';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Bantex Ordner Warna Maroon',
            'sku'           => '8010301006002',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 55691,
            'cost_price'    => 55691,
            'min_stock'     => 5,
            'current_stock' => 8,
            'stock_baik'    => 8,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010306002';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Baterai 9 volt',
            'sku'           => '8010306002',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 41625,
            'cost_price'    => 41625,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010306002';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Baterai Alkaline Size AA LR6 1.5V Isi 2',
            'sku'           => '8010306002002',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 14985,
            'cost_price'    => 14985,
            'min_stock'     => 5,
            'current_stock' => 6,
            'stock_baik'    => 6,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010306002';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Baterai Alkaline Size AAA LR6 1.5V Isi 2',
            'sku'           => '8010306002003',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 27956,
            'cost_price'    => 27956,
            'min_stock'     => 5,
            'current_stock' => 2,
            'stock_baik'    => 2,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301003';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Binder Clip 105',
            'sku'           => '8010301003',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 4079,
            'cost_price'    => 4079,
            'min_stock'     => 5,
            'current_stock' => 10,
            'stock_baik'    => 10,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301003';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Binder Clip 107',
            'sku'           => '8010301003002',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 3441,
            'cost_price'    => 3441,
            'min_stock'     => 5,
            'current_stock' => 12,
            'stock_baik'    => 12,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301003';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Binder Clip 111',
            'sku'           => '8010301003003',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 6660,
            'cost_price'    => 6660,
            'min_stock'     => 5,
            'current_stock' => 13,
            'stock_baik'    => 13,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301003';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Binder Clip 155',
            'sku'           => '8010301003004',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 8270,
            'cost_price'    => 8270,
            'min_stock'     => 5,
            'current_stock' => 9,
            'stock_baik'    => 9,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301003';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Binder Clip 200',
            'sku'           => '8010301003005',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 14874,
            'cost_price'    => 14874,
            'min_stock'     => 5,
            'current_stock' => 5,
            'stock_baik'    => 5,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301003';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Binder Clip 260 (1 dus = 12 pcs)',
            'sku'           => '8010301003006',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 19425,
            'cost_price'    => 19425,
            'min_stock'     => 5,
            'current_stock' => 18,
            'stock_baik'    => 18,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Buku Ekspedisi Ukuran Folio (Besar)',
            'sku'           => '8010301999',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 12654,
            'cost_price'    => 12654,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Carry file zipper bag',
            'sku'           => '8010301999002',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 21978,
            'cost_price'    => 21978,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301008';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Cutter pisau besar L.500',
            'sku'           => '8010301008',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 17205,
            'cost_price'    => 17205,
            'min_stock'     => 5,
            'current_stock' => 2,
            'stock_baik'    => 2,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301008';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Cutter pisau Kecil L.300',
            'sku'           => '8010301008002',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 6549,
            'cost_price'    => 6549,
            'min_stock'     => 5,
            'current_stock' => 1,
            'stock_baik'    => 1,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Dispenser Tape Sedang No. 50',
            'sku'           => '8010301999003',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 31635,
            'cost_price'    => 31635,
            'min_stock'     => 5,
            'current_stock' => 1,
            'stock_baik'    => 1,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Double Tape Ukuran 1 Inchi',
            'sku'           => '8010301999004',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 6105,
            'cost_price'    => 6105,
            'min_stock'     => 5,
            'current_stock' => 3,
            'stock_baik'    => 3,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Double Tape Ukuran 2 Inchi',
            'sku'           => '8010301999005',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 12210,
            'cost_price'    => 12210,
            'min_stock'     => 5,
            'current_stock' => 4,
            'stock_baik'    => 4,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010304006';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Flashdisk 128 Gb',
            'sku'           => '8010304006',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 207570,
            'cost_price'    => 207570,
            'min_stock'     => 5,
            'current_stock' => 3,
            'stock_baik'    => 3,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Glossy Photo Paper A4 isi 20',
            'sku'           => '8010301999006',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 29859,
            'cost_price'    => 29859,
            'min_stock'     => 5,
            'current_stock' => 4,
            'stock_baik'    => 4,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301003';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Gunting Kertas Besar',
            'sku'           => '8010301003007',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 14319,
            'cost_price'    => 14319,
            'min_stock'     => 5,
            'current_stock' => 2,
            'stock_baik'    => 2,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301003';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Gunting Kertas Kecil',
            'sku'           => '8010301003008',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 11378,
            'cost_price'    => 11378,
            'min_stock'     => 5,
            'current_stock' => 3,
            'stock_baik'    => 3,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301008';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Isi Cutter Besar L500',
            'sku'           => '8010301008003',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 5826,
            'cost_price'    => 5826,
            'min_stock'     => 5,
            'current_stock' => 1,
            'stock_baik'    => 1,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301008';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Isi Cutter Kecil L300',
            'sku'           => '8010301008004',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 4440,
            'cost_price'    => 4440,
            'min_stock'     => 5,
            'current_stock' => 1,
            'stock_baik'    => 1,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301012';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Isi Stapler No. 10 (Kecil)',
            'sku'           => '8010301012',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 62049,
            'cost_price'    => 62049,
            'min_stock'     => 5,
            'current_stock' => 7,
            'stock_baik'    => 7,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Isi Pensil Mekanik',
            'sku'           => '8010301999007',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 72150,
            'cost_price'    => 72150,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Kalkulator 16 Digit',
            'sku'           => '8010301999008',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 266400,
            'cost_price'    => 266400,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010302001';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Kertas HVS A4 70 Grm',
            'sku'           => '8010302001',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 73815,
            'cost_price'    => 73815,
            'min_stock'     => 5,
            'current_stock' => 5,
            'stock_baik'    => 5,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010302001';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Kertas HVS A4 80 Grm',
            'sku'           => '8010302001002',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 72150,
            'cost_price'    => 72150,
            'min_stock'     => 5,
            'current_stock' => 1,
            'stock_baik'    => 1,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010302001';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Kertas HVS F4 70 Grm',
            'sku'           => '8010302001003',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 72150,
            'cost_price'    => 72150,
            'min_stock'     => 5,
            'current_stock' => 3,
            'stock_baik'    => 3,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010302001';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Kertas HVS F4 80 Grm',
            'sku'           => '8010302001004',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 88800,
            'cost_price'    => 88800,
            'min_stock'     => 5,
            'current_stock' => 2,
            'stock_baik'    => 2,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301003';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Kertas sticker Ukuran A4',
            'sku'           => '8010301003009',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 32745,
            'cost_price'    => 32745,
            'min_stock'     => 5,
            'current_stock' => 2,
            'stock_baik'    => 2,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Keyboard Komputer K120',
            'sku'           => '8010301999009',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 200910,
            'cost_price'    => 200910,
            'min_stock'     => 5,
            'current_stock' => 1,
            'stock_baik'    => 1,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Lakban Bening 24 Milimeter',
            'sku'           => '8010301999010',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 11543,
            'cost_price'    => 11543,
            'min_stock'     => 5,
            'current_stock' => 3,
            'stock_baik'    => 3,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Lakban Bening 48 Milimeter',
            'sku'           => '8010301999011',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 14430,
            'cost_price'    => 14430,
            'min_stock'     => 5,
            'current_stock' => 5,
            'stock_baik'    => 5,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Lakban Hitam 24 Milimeter',
            'sku'           => '8010301999012',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 11655,
            'cost_price'    => 11655,
            'min_stock'     => 5,
            'current_stock' => 1,
            'stock_baik'    => 1,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Lakban Hitam 48 Milimeter',
            'sku'           => '8010301999013',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 18315,
            'cost_price'    => 18315,
            'min_stock'     => 5,
            'current_stock' => 2,
            'stock_baik'    => 2,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Lem Fox',
            'sku'           => '8010301999014',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 18815,
            'cost_price'    => 18815,
            'min_stock'     => 5,
            'current_stock' => 1,
            'stock_baik'    => 1,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Lem Fox Stik PVAc Botol 60 gr',
            'sku'           => '8010301999015',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 15540,
            'cost_price'    => 15540,
            'min_stock'     => 5,
            'current_stock' => 2,
            'stock_baik'    => 2,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301006';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Map Daichi Bussines File F4 (Daiichi Clear Sleeves 9002 )',
            'sku'           => '8010301006003',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 52725,
            'cost_price'    => 52725,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010304010';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Mouse WIRELLES',
            'sku'           => '8010304010',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 173583,
            'cost_price'    => 173583,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Note Book (Agenda)',
            'sku'           => '8010301999016',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 40848,
            'cost_price'    => 40848,
            'min_stock'     => 5,
            'current_stock' => 1,
            'stock_baik'    => 1,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Paper Clip Trigonal No.1',
            'sku'           => '8010301999017',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 2775,
            'cost_price'    => 2775,
            'min_stock'     => 5,
            'current_stock' => 5,
            'stock_baik'    => 5,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Paper Clip Trigonal No.3',
            'sku'           => '8010301999018',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 4995,
            'cost_price'    => 4995,
            'min_stock'     => 5,
            'current_stock' => 8,
            'stock_baik'    => 8,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Paper Clip Trigonal No.5',
            'sku'           => '8010301999019',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 11100,
            'cost_price'    => 11100,
            'min_stock'     => 5,
            'current_stock' => 7,
            'stock_baik'    => 7,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301007';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Penggaris Besi 30 Cm',
            'sku'           => '8010301007',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 7659,
            'cost_price'    => 7659,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Penghapus Pensil',
            'sku'           => '8010301999020',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 3108,
            'cost_price'    => 3108,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Penghapus Whiteboard',
            'sku'           => '8010301999021',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 10545,
            'cost_price'    => 10545,
            'min_stock'     => 5,
            'current_stock' => 5,
            'stock_baik'    => 5,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Pensil Mekanik',
            'sku'           => '8010301999022',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 19532,
            'cost_price'    => 19532,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Pos It Sign Here 3M',
            'sku'           => '8010301999023',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 24975,
            'cost_price'    => 24975,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Post It Flags',
            'sku'           => '8010301999024',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 26196,
            'cost_price'    => 26196,
            'min_stock'     => 5,
            'current_stock' => 3,
            'stock_baik'    => 3,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Post It Warna Warni Ukuran Sedang',
            'sku'           => '8010301999025',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 26196,
            'cost_price'    => 26196,
            'min_stock'     => 5,
            'current_stock' => 2,
            'stock_baik'    => 2,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301006';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Snel Hecter Map Warna Biru, Merah, Kuning, Hijau dan Orange (map plastik bening)',
            'sku'           => '8010301006004',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 31080,
            'cost_price'    => 31080,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Spidol Permanent',
            'sku'           => '8010301999026',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 108780,
            'cost_price'    => 108780,
            'min_stock'     => 5,
            'current_stock' => 2,
            'stock_baik'    => 2,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Spidol Whiteboard Biru',
            'sku'           => '8010301999027',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 194250,
            'cost_price'    => 194250,
            'min_stock'     => 5,
            'current_stock' => 1,
            'stock_baik'    => 1,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Spidol Whiteboard Hitam',
            'sku'           => '8010301999028',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 194250,
            'cost_price'    => 194250,
            'min_stock'     => 5,
            'current_stock' => 1,
            'stock_baik'    => 1,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Stabillo warna Kuning',
            'sku'           => '8010301999029',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 14430,
            'cost_price'    => 14430,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Stabillo warna Merah Muda',
            'sku'           => '8010301999030',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 14430,
            'cost_price'    => 14430,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301012';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Stapler No. 10 (Kecil)',
            'sku'           => '8010301012002',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 29859,
            'cost_price'    => 29859,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301012';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Stapler No. 3 (Sedang)',
            'sku'           => '8010301012003',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 29415,
            'cost_price'    => 29415,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Stationer (Tempat ATK)',
            'sku'           => '8010301999031',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 66600,
            'cost_price'    => 66600,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Tip-Ex',
            'sku'           => '8010301999032',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 7770,
            'cost_price'    => 7770,
            'min_stock'     => 5,
            'current_stock' => 3,
            'stock_baik'    => 3,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301003';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Tip-Ex Kertas (Correction Tape)',
            'sku'           => '8010301003010',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 13208,
            'cost_price'    => 13208,
            'min_stock'     => 5,
            'current_stock' => 2,
            'stock_baik'    => 2,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Tray 3 Susun Elegan Transferan',
            'sku'           => '8010301999033',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 160950,
            'cost_price'    => 160950,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'ORICO W6PH4 BK USB 3.0 4 port',
            'sku'           => '8010301999034',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 177600,
            'cost_price'    => 177600,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301001';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Faster Pulpen Cetek C6 Black 0.7mm',
            'sku'           => '8010301001005',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 4218,
            'cost_price'    => 4218,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Tom & Jerry Label Stiker No.100',
            'sku'           => '8010301999035',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 8880,
            'cost_price'    => 8880,
            'min_stock'     => 5,
            'current_stock' => 1,
            'stock_baik'    => 1,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301012';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Bantex Staples Remover',
            'sku'           => '8010301012004',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 32190,
            'cost_price'    => 32190,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301007';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Penggaris Besi 60cm',
            'sku'           => '8010301007002',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 16262,
            'cost_price'    => 16262,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Staedtler Pencil 2B',
            'sku'           => '8010301999036',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 3885,
            'cost_price'    => 3885,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Rautan Pensil',
            'sku'           => '8010301999037',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 149850,
            'cost_price'    => 149850,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Tom & Jerry Label Stiker No.101',
            'sku'           => '8010301999038',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 11100,
            'cost_price'    => 11100,
            'min_stock'     => 5,
            'current_stock' => 1,
            'stock_baik'    => 1,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Tom & Jerry Label Stiker No.120',
            'sku'           => '8010301999039',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 10545,
            'cost_price'    => 10545,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Tom & Jerry Label Stiker No.121',
            'sku'           => '8010301999040',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 16095,
            'cost_price'    => 16095,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Clear Holder Ukuran Folio Isi 40 Lembar',
            'sku'           => '8010301999041',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 34535,
            'cost_price'    => 34535,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301012';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Isi Staples Tembak (23/6, 23/8, 23/10, T-8, dan T-10)',
            'sku'           => '8010301012005',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 248021,
            'cost_price'    => 248021,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301012';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Isi Stapler No. 3 (Sedang)',
            'sku'           => '8010301012006',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 188370,
            'cost_price'    => 188370,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301003';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Kertas Double Folio Bergaris',
            'sku'           => '8010301003011',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 63398,
            'cost_price'    => 63398,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301003';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Kertas Foto Inkjet Paper',
            'sku'           => '8010301003012',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 42753,
            'cost_price'    => 42753,
            'min_stock'     => 5,
            'current_stock' => 1,
            'stock_baik'    => 1,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010302001';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Kertas HVS Berwarna A4 Biru',
            'sku'           => '8010302001005',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 92929,
            'cost_price'    => 92929,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010302001';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Kertas HVS Berwarna A4 Hijau',
            'sku'           => '8010302001006',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 92929,
            'cost_price'    => 92929,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010302001';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Kertas HVS Berwarna A4 Kuning',
            'sku'           => '8010302001007',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 92929,
            'cost_price'    => 92929,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301003';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Kertas (kertas concorde) putih',
            'sku'           => '8010301003013',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 125000,
            'cost_price'    => 125000,
            'min_stock'     => 5,
            'current_stock' => 1,
            'stock_baik'    => 1,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Pointer',
            'sku'           => '8010301999042',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 662745,
            'cost_price'    => 662745,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Porvorator (Pembolong Sedang)',
            'sku'           => '8010301999043',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 20407,
            'cost_price'    => 20407,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Spidol Whiteboard Merah',
            'sku'           => '8010301999044',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 62715,
            'cost_price'    => 62715,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301012';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Stapler Kangaro 23/10 mm (tembak Besar)',
            'sku'           => '8010301012007',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 266500,
            'cost_price'    => 266500,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Hdmi To VGA',
            'sku'           => '8010301999045',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 200140,
            'cost_price'    => 200140,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Buku Ekspedisi Sedang',
            'sku'           => '8010301999046',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 12558,
            'cost_price'    => 12558,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'EPSON 664 Black',
            'sku'           => '8010301999047',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 114085,
            'cost_price'    => 114085,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'EPSON 664 magenta',
            'sku'           => '8010301999048',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 114085,
            'cost_price'    => 114085,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'EPSON 664 yellow',
            'sku'           => '8010301999049',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 114085,
            'cost_price'    => 114085,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'EPSON 664 blue',
            'sku'           => '8010301999050',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 114085,
            'cost_price'    => 114085,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'EPSON 673 Black',
            'sku'           => '8010301999051',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 197340,
            'cost_price'    => 197340,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'EPSON 673 Colour',
            'sku'           => '8010301999052',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 197340,
            'cost_price'    => 197340,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301002';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Tinta Stempel Automatic Warna Biru',
            'sku'           => '8010301002',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 10118,
            'cost_price'    => 10118,
            'min_stock'     => 5,
            'current_stock' => 2,
            'stock_baik'    => 2,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301002';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Tinta Stempel Automatic Warna Ungu',
            'sku'           => '8010301002002',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 10118,
            'cost_price'    => 10118,
            'min_stock'     => 5,
            'current_stock' => 2,
            'stock_baik'    => 2,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301002';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Tinta Stempel Pyramid Biru',
            'sku'           => '8010301002003',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 8634,
            'cost_price'    => 8634,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301002';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Tinta Stempel Pyramid Ungu',
            'sku'           => '8010301002004',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 8634,
            'cost_price'    => 8634,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'HP 680 Colour',
            'sku'           => '8010301999053',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 210000,
            'cost_price'    => 210000,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'EPSON 003 Black',
            'sku'           => '8010301999054',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 88800,
            'cost_price'    => 88800,
            'min_stock'     => 5,
            'current_stock' => 3,
            'stock_baik'    => 3,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'EPSON 003 magenta',
            'sku'           => '8010301999055',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 88800,
            'cost_price'    => 88800,
            'min_stock'     => 5,
            'current_stock' => 3,
            'stock_baik'    => 3,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'EPSON 003 yellow',
            'sku'           => '8010301999056',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 88800,
            'cost_price'    => 88800,
            'min_stock'     => 5,
            'current_stock' => 3,
            'stock_baik'    => 3,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'EPSON 003 blue',
            'sku'           => '8010301999057',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 88800,
            'cost_price'    => 88800,
            'min_stock'     => 5,
            'current_stock' => 3,
            'stock_baik'    => 3,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'EPSON 001 Black',
            'sku'           => '8010301999058',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 95460,
            'cost_price'    => 95460,
            'min_stock'     => 5,
            'current_stock' => 2,
            'stock_baik'    => 2,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'EPSON 001 magenta',
            'sku'           => '8010301999059',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 96570,
            'cost_price'    => 96570,
            'min_stock'     => 5,
            'current_stock' => 2,
            'stock_baik'    => 2,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'EPSON 001 Cyan',
            'sku'           => '8010301999060',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 96570,
            'cost_price'    => 96570,
            'min_stock'     => 5,
            'current_stock' => 2,
            'stock_baik'    => 2,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'EPSON 001 Yellow',
            'sku'           => '8010301999061',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 96570,
            'cost_price'    => 96570,
            'min_stock'     => 5,
            'current_stock' => 2,
            'stock_baik'    => 2,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'HP 85A Black Laserjet',
            'sku'           => '8010301999062',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 577200,
            'cost_price'    => 577200,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'HP 680 Black',
            'sku'           => '8010301999063',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 154290,
            'cost_price'    => 154290,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'HP Deksjet Ink Advantage 678 Black',
            'sku'           => '8010301999064',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 143190,
            'cost_price'    => 143190,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'HP Deksjet Ink Advantage 678 Tricolour',
            'sku'           => '8010301999065',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 147075,
            'cost_price'    => 147075,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'EPSON 008 Black Pigment',
            'sku'           => '8010301999066',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 327450,
            'cost_price'    => 327450,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301002';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'TintaHP GT 51 Black',
            'sku'           => '8010301002005',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 149850,
            'cost_price'    => 149850,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301002';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'TintaHP GT 51 Colour',
            'sku'           => '8010301002006',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 158397,
            'cost_price'    => 158397,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'HP LaserJet Toner Cartridge 76A CF276A Black',
            'sku'           => '8010301999067',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 1720500,
            'cost_price'    => 1720500,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'HP LaserJet Toner Cartridge 17A CF217A Black',
            'sku'           => '8010301999068',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 1154400,
            'cost_price'    => 1154400,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Toner Epson T9481',
            'sku'           => '8010301999069',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 954600,
            'cost_price'    => 954600,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Toner Epson T9482',
            'sku'           => '8010301999070',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 997890,
            'cost_price'    => 997890,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Toner Epson T9483',
            'sku'           => '8010301999071',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 1010100,
            'cost_price'    => 1010100,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Toner Epson T9484',
            'sku'           => '8010301999072',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 1010100,
            'cost_price'    => 1010100,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301002';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Tinta Printer untuk Epson L6270 Hitam',
            'sku'           => '8010301002007',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 133200,
            'cost_price'    => 133200,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301002';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Tinta Printer untuk Epson L6270 Biru',
            'sku'           => '8010301002008',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 133200,
            'cost_price'    => 133200,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301002';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Tinta Printer untuk Epson L6270 Kuning',
            'sku'           => '8010301002009',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 133200,
            'cost_price'    => 133200,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301002';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Tinta Printer untuk Epson L6270 Merah',
            'sku'           => '8010301002010',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 133200,
            'cost_price'    => 133200,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'HP DeskJet Colour',
            'sku'           => '8010301999073',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 205350,
            'cost_price'    => 205350,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'HP DeskJet Black',
            'sku'           => '8010301999074',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 183150,
            'cost_price'    => 183150,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'HP DeskJet Cartridge',
            'sku'           => '8010301999075',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 288600,
            'cost_price'    => 288600,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301999';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'HP LaserJet Toner 107 A',
            'sku'           => '8010301999076',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 788100,
            'cost_price'    => 788100,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301002';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Tinta Brother DCP-T720DW BT5000C (Cyan)',
            'sku'           => '8010301002011',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 144300,
            'cost_price'    => 144300,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301002';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Tinta Brother DCP-T720DW BT5000M (Meganta)',
            'sku'           => '8010301002012',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 144300,
            'cost_price'    => 144300,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301002';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Tinta Brother DCP-T720DW BT5000Y(Yellow)',
            'sku'           => '8010301002013',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 144300,
            'cost_price'    => 144300,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Determine Category ID
        $kodeBarang = '8010301002';
        $categoryId = 1; // Default
        if (strlen($kodeBarang) >= 3) {
            $parentKode = substr($kodeBarang, 0, strlen($kodeBarang) - 3) . '000';
            if (isset($catMap[$parentKode])) {
                $categoryId = $catMap[$parentKode];
            }
        }

        $data[] = [
            'name'          => 'Tinta Brother DCP-T720DW BTD60BK (Hitam)',
            'sku'           => '8010301002014',
            'category_id'   => $categoryId,
            'description'   => 'Hasil Stock Opname',
            'price'         => 144300,
            'cost_price'    => 144300,
            'min_stock'     => 5,
            'current_stock' => 0,
            'stock_baik'    => 0,
            'stock_rusak'   => 0,
            'unit'          => 'Pcs',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        $this->db->table('products')->insertBatch($data);
    }
}
