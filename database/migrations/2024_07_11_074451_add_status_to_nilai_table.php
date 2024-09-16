<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->string('status')->default('belum dikartap');
        });
    }
    
    public function down()
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};