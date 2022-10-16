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
            $table->integer('status')->default('0')->comment('完了フラグ（1:完了　0:未完了）');
            $table->integer('category_id')->comment('カテゴリーID');
            $table->string('user_id', 10)->comment('作成したユーザーID');
            $table->timestamp('created_at')->useCurrent()->nullable(false)->comment('作成日時');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable(false)->comment('更新日時');
            $table->timestamp('deleted_at')->nullable()->comment('削除日時');
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
