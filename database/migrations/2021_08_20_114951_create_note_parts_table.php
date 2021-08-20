<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note_parts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('note_id')->comment('ノートID');
            $table->unsignedBigInteger('note_part_name_id')->comment('部品ID');
            $table->string('text')->comment('テキスト');
            $table->string('url')->comment('URL');
            $table->timestamps();

            $table->foreign('note_id')
            ->references('id')->on('notes') //存在しないidの登録は不可
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            $table->foreign('note_part_name_id')
            ->references('id')->on('note_part_names') //存在しないidの登録は不可
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
        Schema::dropIfExists('note_parts');
    }
}
