<?php

namespace App\Imports;

use App\Models\Datainvestasi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class DataInvestasiImport implements ToModel, WithStartRow, SkipsEmptyRows
{
    /**
     * Tentukan baris awal untuk membaca data.
     * Jika baris 1 ada judul, maka pakai 2.
     * Jika file langsung isi data, ubah ke return 1.
     */
    public function startRow(): int
{
    return 2; // lewati baris header
}

public function model(array $row)
{
    if (!isset($row[0]) || !is_numeric($row[0])) {
        return null; // skip kalau tahun kosong atau bukan angka
    }

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
}