<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;
    protected $table = 'bidang';
    protected $fillable = ['bidang'];
    public $timestamps = true; // Enable timestamps (default is true, so this line is optional)
}
