<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPengunduhan extends Model
{
    use HasFactory;

    protected $table = 'log_pengunduhan';
    protected $primaryKey = 'id_download';

    protected $fillable = [
        'kategori_pengunduh',
        'nama_instansi',
        'email_pengunduh',
        'telpon',
        'keperluan',
        'waktu_download',
    ];
}
