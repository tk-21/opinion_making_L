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
        Schema::create('topics', function (Blueprint $table) {
            $table->id()->comment('トピックID');
            $table->text('title')->comment('トピックタイトル');
            $table->text('body')->comment('トピック本文');
            $table->text('position')->comment('トピックに対して自らがとるポジション');
            $table->boolean('status')->default(false)->comment('完了フラグ（1:完了　0:未完了）');
            $table->foreignId('category_id')->nullable()->comment('カテゴリーID');
            $table->foreignId('user_id')->comment('作成したユーザーID');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('トピック');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topics');
    }
};
