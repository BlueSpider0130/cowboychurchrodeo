<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('organization_id')->unsigned();      
            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations')
                ->onDelete('cascade');

            $table->bigInteger('event_id')->unsigned()->nullable();
            $table->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onDelete('cascade');

            $table->bigInteger('group_id')->unsigned()->nullable();
            $table->foreign('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('set null');                                    

            $table->bigInteger('rodeo_id')->unsigned()->nullable();
            $table->foreign('rodeo_id')
                ->references('id')
                ->on('rodeos')
                ->onDelete('cascade');

            $table->string('name')->nullable(); 
            $table->text('description')->nullable(); 
            $table->decimal('entry_fee', 12, 2)->nullable();   

            $table->integer('max_entries')->nullable();
            $table->integer('max_entries_per_contestant')->nullable()->default(1);
                        
            $table->boolean('membership_required')->default(false);

            $table->dateTime('registration_opens_at')->nullable();
            $table->dateTime('registration_closes_at')->nullable();
            $table->timestamps();

            $table->unique(['rodeo_id', 'event_id', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competitions');
    }
}
