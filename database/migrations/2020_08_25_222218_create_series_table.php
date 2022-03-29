<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('organization_id')->unsigned();
            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations')
                ->onDelete('cascade');
            
            $table->string('name')->nullable();
            $table->string('description')->nullable();            
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable(); 
            $table->decimal('membership_fee', 12, 2)->nullable();
            $table->dateTime('membership_closes_at')->nullable();
            $table->boolean('draft')->default(false);
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
        Schema::dropIfExists('series');
    }
}
