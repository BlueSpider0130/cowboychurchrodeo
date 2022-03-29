<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            

            $table->bigInteger('contestant_id')->unsigned();
            $table->foreign('contestant_id')
                ->references('id')
                ->on('contestants')
                ->onDelete('cascade');

            $table->bigInteger('series_id')->unsigned();
            $table->foreign('series_id')
                ->references('id')
                ->on('series')
                ->onDelete('cascade');      

            $table->text('notes')->nullable();

            $table->boolean('pending')->default(0);

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
        Schema::dropIfExists('memberships');
    }
}
