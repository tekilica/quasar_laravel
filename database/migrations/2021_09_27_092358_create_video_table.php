<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('author')->nullable();
            $table->string('agency')->nullable();
            $table->string('platform')->nullable();
            $table->boolean('is_nsfw');
            $table->foreignId('uploaded_by')->constrained('user');
            $table->foreignId('last_edited_by')->nullable()->constrained('user');
            $table->string('file');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video');
    }
}
