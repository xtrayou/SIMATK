<?php

namespace App\Models;

class KodeBarangModel
{
    /**
     * Get all item codes from the JSON file
     */
    public function getAll(): array
    {
        return $this->loadFromJson();
    }

    /**
     * Search kode barang by keyword (kode or nama)
     */
    public function cariKodeBarang(string $keyword = ''): array
    {
        $data = $this->loadFromJson();

        if ($keyword === '') {
            return $data;
        }

        $keyword = strtolower(trim($keyword));
        
        return array_filter($data, function ($item) use ($keyword) {
            $kodeMatch = strpos(strtolower($item['kode']), $keyword) !== false;
            $namaMatch = strpos(strtolower($item['nama']), $keyword) !== false;
            
            return $kodeMatch || $namaMatch;
        });
    }

    /**
     * Load JSON data
     */
    private function loadFromJson(): array
    {
        $filePath = FCPATH . 'dataexport/kode_barang.json';
        
        if (!file_exists($filePath)) {
            return [];
        }

        $json = file_get_contents($filePath);
        $data = json_decode($json, true);

        if (!is_array($data)) {
            return [];
        }

        return $data;
    }
}
