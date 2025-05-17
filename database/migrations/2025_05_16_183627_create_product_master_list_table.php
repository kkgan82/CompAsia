<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    //Run the migrations.
    public function up(): void
    {
        Schema::create('product_master_list', function (Blueprint $table) {
            $table->id();
            $table->string('product_id', 10)->unique();
            $table->string('types', 50)->nullable();
            $table->string('brand', 50)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('capacity', 50)->nullable();
            $table->integer('quantity')->default(0);
            $table->timestamps();
        });

        // Insert sample data
        DB::table('product_master_list')->insert([
            ['product_id' => '4450', 'types' => 'Smartphone', 'brand' => 'Apple', 'model' => 'iPhone SE', 'capacity' => '2GB/16GB', 'quantity' => 13],
            ['product_id' => '4768', 'types' => 'Smartphone', 'brand' => 'Apple', 'model' => 'iPhone SE', 'capacity' => '2GB/32GB', 'quantity' => 30],
            ['product_id' => '4451', 'types' => 'Smartphone', 'brand' => 'Apple', 'model' => 'iPhone SE', 'capacity' => '2GB/64GB', 'quantity' => 20],
            ['product_id' => '4574', 'types' => 'Smartphone', 'brand' => 'Apple', 'model' => 'iPhone SE', 'capacity' => '2GB/128GB', 'quantity' => 16],
            ['product_id' => '6039', 'types' => 'Smartphone', 'brand' => 'Apple', 'model' => 'iPhone SE (2020)', 'capacity' => '3GB/64GB', 'quantity' => 18],
        ]);
    }

    //Reverse the migrations.
    public function down(): void
    {
        Schema::dropIfExists('product_master_list');
    }
};