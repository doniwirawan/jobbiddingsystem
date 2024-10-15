<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->enum('entity', ['Corp', 'Weddings', 'Studio']);
            $table->enum('type', ['Photography', 'Videography']);
            $table->decimal('rate', 8, 2);
            $table->enum('role', ['Primary', 'Secondary']);
            $table->text('remarks')->nullable();
            $table->enum('status', ['Open', 'Closed']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
}

