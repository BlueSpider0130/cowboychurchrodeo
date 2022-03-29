<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRodeosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rodeos', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('organization_id')->unsigned();          
            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations')
                ->onDelete('cascade');

            $table->bigInteger('series_id')->unsigned()->nullable();
            $table->foreign('series_id')
                ->references('id')
                ->on('series')
                ->onDelete('cascade');

            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->decimal('office_fee', 12, 2)->nullable();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();            
            $table->dateTime('opens_at')->nullable();
            $table->dateTime('closes_at')->nullable();

            $table->boolean('membership_required')->default(false);
            
            $table->dateTime('published_at')->nullable();
            
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
        Schema::dropIfExists('rodeos');
    }
}
