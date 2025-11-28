<?php

namespace App\Imports;

use App\Models\Datainvestasi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Exception;

class DataInvestasiImport implements ToModel, WithStartRow, SkipsEmptyRows, WithEvents
{
    private $hasValidData = false;

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        // ❗ Validasi kolom sesuai template
        if (count($row) < 15) {
            throw new Exception("Format template tidak sesuai. Kolom tidak lengkap.");
        }

        // ❗ Validasi minimal 1 baris bernilai
        if (isset($row[0]) && is_numeric($row[0])) {
            $this->hasValidData = true;

            return new Datainvestasi([
                'tahun'                  => (int) $row[0],
                'periode'                => $row[1] ?? null,
                'status_penanaman_modal' => $row[2] ?? null,
                'regional'               => $row[3] ?? null,
                'negara'                 => $row[4] ?? null,
                'sektor_utama'           => $row[5] ?? null,
                'nama_sektor'            => $row[6] ?? null,
                'deskripsi_kbli_2digit'  => $row[7] ?? null,
                'provinsi'               => $row[8] ?? null,
                'kabupaten_kota'         => $row[9] ?? null,
                'wilayah_jawa'           => $row[10] ?? null,
                'pulau'                  => $row[11] ?? null,
                'investasi_rp_juta'      => $row[12] ?? null,
                'investasi_us_ribu'      => $row[13] ?? null,
                'jumlah_tki'             => $row[14] ?? null,
            ]);
        }

        return null;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function() {
                if (!$this->hasValidData) {
                    throw new Exception("File Excel kosong atau tidak memiliki baris data yang valid.");
                }
            }
        ];
    }
}
