<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datainvestasi extends Model
{
    use HasFactory;
    protected $fillable = [  
       'tahun','periode','status_penanaman_modal','regional','negara','sektor_utama','nama_sektor','deskripsi_kbli_2digit','provinsi','kabupaten_kota','wilayah_jawa','pulau','investasi_rp_juta','investasi_us_ribu','jumlah_tki',
    ];
}