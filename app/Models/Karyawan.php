<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;
    protected $table = 'karyawan';
    protected $fillable = ['id', 'nama_karyawan', 'bidang', 'jabatan', 'nrp'];

    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'id_karyawan', 'id');
    }
}
