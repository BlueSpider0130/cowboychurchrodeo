<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamRopingEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_roping_entries', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('header_entry_id');
            $table->bigInteger('heeler_entry_id');

            $table->bigInteger('instance_id')->unsigned()->nullable();  
            $table->foreign('instance_id')
                ->references('id')
                ->on('competition_instances')
                ->onDelete('set null');          

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
        Schema::dropIfExists('team_roping_entries');
    }
}
