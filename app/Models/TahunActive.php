<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunActive extends Model
{
    use HasFactory;
    protected $table = 'tahun_active';
    protected $fillable = ['tahun_active'];
}
