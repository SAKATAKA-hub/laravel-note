<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name',50)->default('tags');
            $table->string('value',50);
            $table->string('text',50);
            $table->unsignedBigInteger('user_id');
            $table->boolean('checked')->default(0);
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
        Schema::dropIfExists('tags');
    }
}
