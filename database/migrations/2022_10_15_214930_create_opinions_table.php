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
        Schema::create('opinions', function (Blueprint $table) {
            $table->id()->comment('意見ID');
            $table->text('opinion')->comment('意見本文');
            $table->text('reason')->comment('その理由');
            $table->foreignId('topic_id')->comment('トピックID');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('最終的な自分の意見');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opinions');
    }
};
