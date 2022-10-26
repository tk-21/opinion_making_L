<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id()->comment('カテゴリーID');
            $table->text('name')->comment('カテゴリー名');
            $table->foreignId('user_id')->comment('ユーザーID');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('カテゴリー');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
