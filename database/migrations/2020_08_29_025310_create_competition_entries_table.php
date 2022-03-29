<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitionEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competition_entries', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('contestant_id')->unsigned();         
            $table->foreign('contestant_id')
                ->references('id')
                ->on('contestants')
                ->onDelete('cascade');     

            $table->bigInteger('competition_id')->unsigned();         
            $table->foreign('competition_id')
                ->references('id')
                ->on('competitions')
                ->onDelete('cascade'); 

            $table->bigInteger('instance_id')->unsigned()->nullable();  
            $table->foreign('instance_id')
                ->references('id')
                ->on('competition_instances')
                ->onDelete('set null');

            $table->string('position')->nullable();
            $table->text('requested_teammate')->nullable();

            $table->boolean('no_fee')->default(false);
            $table->boolean('no_score')->default(false);

            $table->string('score')->nullable();
            
            $table->boolean('paid')->default(0);
            $table->bigInteger('payment_id')->unsigned()->nullable();
            $table->foreign('payment_id')
                ->references('id')
                ->on('payments')
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
        Schema::dropIfExists('competition_entries');
    }
}
