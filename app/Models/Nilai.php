<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;
    protected $table = 'nilai';
    protected $fillable = ['id', 'id_karyawan', 'id_periode', 'n1', 'n2', 'n3', 'n4', 'n5', 'n6', 'n7', 'n8', 'n9', 'n_final', 'periodes'];

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'id_periode', 'id');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id');
    }

    // Handle dynamic attributes
    public function __get($key)
    {
        if (preg_match('/^n\d+$/', $key)) {
            return $this->attributes[$key] ?? null;
        }

        return parent::__get($key);
    }
}
