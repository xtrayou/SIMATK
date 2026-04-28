<?php

namespace App\Services\MasterData;

use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BarangExportService
{
    /**
     * Ekspor daftar barang ke Excel.
     *
     * @param array $products
     */
    public function exportExcel(array $products): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Kode Barang')
            ->setCellValue('C1', 'Nama Barang')
            ->setCellValue('D1', 'Kategori')
            ->setCellValue('E1', 'Stok')
            ->setCellValue('F1', 'Satuan')
            ->setCellValue('G1', 'Harga');

        $rowNum = 2;
        foreach ($products as $idx => $row) {
            $sheet->setCellValue('A' . $rowNum, $idx + 1)
                ->setCellValue('B' . $rowNum, $row['sku'])
                ->setCellValue('C' . $rowNum, $row['name'])
                ->setCellValue('D' . $rowNum, $row['category_name'])
                ->setCellValue('E' . $rowNum, $row['current_stock'])
                ->setCellValue('F' . $rowNum, $row['unit'])
                ->setCellValue('G' . $rowNum, $row['price']);
            $rowNum++;
        }

        $fileName = 'Laporan_Stok_' . date('Ymd') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Ekspor daftar barang ke PDF.
     *
     * @param array $products
     */
    public function exportPdf(array $products): void
    {
        $html = "<h2>Laporan Inventaris Barang</h2>";
        $html .= "<table border='1' width='100%' cellpadding='5' style='border-collapse:collapse;'>
                    <thead>
                        <tr style='background:#f2f2f2;'>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Unit</th>
                        </tr>
                    </thead>
                    <tbody>";

        foreach ($products as $idx => $row) {
            $html .= "<tr>
                        <td>" . ($idx + 1) . "</td>
                        <td>{$row['sku']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['category_name']}</td>
                        <td>{$row['current_stock']}</td>
                        <td>{$row['unit']}</td>
                      </tr>";
        }
        $html .= "</tbody></table>";

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('Laporan_Stok_' . date('Ymd') . '.pdf', ["Attachment" => true]);
        exit;
    }

    /**
     * Ekspor detail satu barang sebagai PDF.
     *
     * @param array $product
     */
    public function exportSingle(array $product): void
    {
        $html = "<h2>Detail Barang</h2>";
        $html .= "<table border='1' width='100%' cellpadding='8' style='border-collapse:collapse;'>";
        $html .= "<tr><th align='left' width='35%'>Nama Barang</th><td>{$product['name']}</td></tr>";
        $html .= "<tr><th align='left'>Kode Barang</th><td>{$product['sku']}</td></tr>";
        $html .= "<tr><th align='left'>Kategori</th><td>{$product['category_name']}</td></tr>";
        $html .= "<tr><th align='left'>Stok</th><td>{$product['current_stock']} {$product['unit']}</td></tr>";
        $html .= "<tr><th align='left'>Harga</th><td>Rp " . number_format((float) $product['price'], 0, ',', '.') . "</td></tr>";
        $html .= "<tr><th align='left'>Deskripsi</th><td>" . ($product['description'] ?: '-') . "</td></tr>";
        $html .= "</table>";

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('Barang_' . $product['sku'] . '.pdf', ['Attachment' => true]);
        exit;
    }
}
