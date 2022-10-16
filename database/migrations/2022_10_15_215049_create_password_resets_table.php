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
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email', 50)->comment('メールアドレス');
            $table->string('token', 80)->comment('トークン');
            $table->timestamp('token_sent_at')->useCurrent()->useCurrentOnUpdate()->comment('トークン送信日時');
            $table->primary('email');
            $table->comment('パスワードリセット');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
    }
};
