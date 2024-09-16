<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AddKriteriaColumn extends Command
{
    protected $signature = 'add:kriteria-column {name}';
    protected $description = 'Add a new kriteria column to the nilai table';

    public function handle()
    {
        $name = $this->argument('name');
        Schema::table('nilai', function (Blueprint $table) use ($name) {
            $table->integer($name)->nullable();
        });

        $this->info("Column {$name} added to nilai table.");
    }
}