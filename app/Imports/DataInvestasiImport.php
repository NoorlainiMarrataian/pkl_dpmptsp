<?php

namespace App\Imports;

use App\Models\Datainvestasi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class DataInvestasiImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    /**
     * Pastikan baris pertama di Excel adalah HEADER dengan nama kolom:
     * tahun, periode, status_penanaman_modal, regional, negara, sektor_utama,
     * nama_sektor, deskripsi_kbli_2digit, provinsi, kabupaten_kota,
     * wilayah_jawa, pulau, investasi_rp_juta, investasi_us_ribu, jumlah_tki
     */
    public function model(array $row)
    {
        // Lewati baris yang tidak punya 'tahun'
        if (!isset($row['tahun'])) {
            return null;
        }

        return new Datainvestasi([
            'tahun'                  => (int) ($row['tahun'] ?? null),
            'periode'                => $row['periode'] ?? null,
            'status_penanaman_modal' => $row['status_penanaman_modal'] ?? null,
            'regional'               => $row['regional'] ?? null,
            'negara'                 => $row['negara'] ?? null,
            'sektor_utama'           => $row['sektor_utama'] ?? null,
            'nama_sektor'            => $row['nama_sektor'] ?? null,
            'deskripsi_kbli_2digit'  => $row['deskripsi_kbli_2digit'] ?? null,
            'provinsi'               => $row['provinsi'] ?? null,
            'kabupaten_kota'         => $row['kabupaten_kota'] ?? null,
            'wilayah_jawa'           => $row['wilayah_jawa'] ?? null,
            'pulau'                  => $row['pulau'] ?? null,
            'investasi_rp_juta'      => $row['investasi_rp_juta'] ?? null,
            'investasi_us_ribu'      => $row['investasi_us_ribu'] ?? null,
            'jumlah_tki'             => $row['jumlah_tki'] ?? null,
        ]);
    }
}
