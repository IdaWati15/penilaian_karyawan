<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaVariabel extends Model
{
    use HasFactory;
    protected $table = 'kriteria_variabel';
    protected $fillable = ['id', 'k1', 'k2', 'k3', 'k4', 'k5', 'k6', 'k7', 'k8', 'k9'];
}
