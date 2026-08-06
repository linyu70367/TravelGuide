<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('views', function (Blueprint $table) {
            $table->id();
            $table->string('name');                                // 必填 (美食/景點名稱)
            $table->string('city')->nullable();                    // 允許空值
            $table->string('town')->nullable();                    // 允許空值
            $table->string('address')->nullable();                 // 允許空值
            $table->integer('typeId')->default(1);                 // 預設型態 1 (或改為 ->nullable())
            $table->string('brief')->nullable();                   // 允許空值 (簡介)
            $table->text('content')->nullable();                   // 建議改為 text 並允許空值 (長文章內容)
            $table->string('tel')->nullable();                     // 允許空值
            $table->integer('like')->default(0);                   // 建議給予預設值 0 (按讚數)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('views');
    }
};
