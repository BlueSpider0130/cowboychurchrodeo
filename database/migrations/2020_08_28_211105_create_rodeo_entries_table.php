<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRodeoEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rodeo_entries', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('contestant_id')->unsigned();
            $table->foreign('contestant_id')
                ->references('id')
                ->on('contestants')
                ->onDelete('cascade');

            $table->bigInteger('rodeo_id')->unsigned();
            $table->foreign('rodeo_id')
                ->references('id')
                ->on('rodeos')
                ->onDelete('cascade');              

            $table->text('check_in_notes')->nullable();

            $table->text('checked_in_notes')->nullable();
            $table->dateTime('checked_in_at')->nullable();

            $table->bigInteger('checked_in_by_user_id')->unsigned()->nullable();
            $table->foreign('checked_in_by_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');            

            $table->boolean('paid')->default(0);
            $table->bigInteger('payment_id')->unsigned()->nullable();
            $table->foreign('payment_id')
                ->references('id')
                ->on('payments')
                ->onDelete('set null');    

            $table->timestamps();

            $table->unique(['contestant_id', 'rodeo_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rodeo_entries');
    }
}
