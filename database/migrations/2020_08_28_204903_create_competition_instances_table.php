<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitionInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competition_instances', function (Blueprint $table) {
            $table->id();
            
            $table->bigInteger('competition_id')->unsigned();
            $table->foreign('competition_id')
                ->references('id')->on('competitions')
                ->onDelete('cascade');

            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->boolean('all_day')->default(true);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competition_instances');
    }
}
