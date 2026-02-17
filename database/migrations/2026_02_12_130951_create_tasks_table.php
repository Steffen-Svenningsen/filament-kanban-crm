<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');                         // Card title
            $table->string('status');                        // Column identifier
            $table->flowforgePositionColumn();               // Drag-and-drop ordering
            $table->timestamps();
        });
    }
};
