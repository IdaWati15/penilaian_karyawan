<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;
    protected $table = 'periode';
    protected $fillable = ['id', 'bulan', 'is_active', 'is_active'];
    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'id_periode', 'id');
    }

}
