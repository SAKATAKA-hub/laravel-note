<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->string('title',100)->comment('題名');
            $table->string('main_image',100)->comment('メイン画像')
            ->nullable()->default(null);
            $table->string('main_color',100)->comment('メインカラー');
            $table->string('tags',100)->comment('＃タグ');
            $table->unsignedBigInteger('user_id')->comment('user ID');
            $table->timestamps();

            $table->foreign('user_id')
            ->references('id')->on('users') //存在しないidの登録は不可
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notes');
    }
}
