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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('ユーザーID');
            $table->string('name', 10)->comment('ユーザーネーム');
            $table->string('password', 60)->comment('パスワード');
            $table->string('email', 255)->unique()->comment('メールアドレス');
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
        Schema::dropIfExists('users');
    }
};