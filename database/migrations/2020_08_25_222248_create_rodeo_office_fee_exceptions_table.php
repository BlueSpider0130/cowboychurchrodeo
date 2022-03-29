<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRodeoOfficeFeeExceptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rodeo_office_fee_exceptions', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('rodeo_id')->unsigned();          
            $table->foreign('rodeo_id')
                ->references('id')
                ->on('rodeos')
                ->onDelete('cascade');

            $table->bigInteger('not_applicable_id')->unsigned();
            $table->string('not_applicable_type');
            
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
        Schema::dropIfExists('rodeo_office_fee_exceptions');
    }
}
