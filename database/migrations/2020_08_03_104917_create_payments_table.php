<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->decimal('amount', 12, 2);
            $table->decimal('tax', 12, 2)->nullable();
            $table->text('notes')->nullable();
            
            $table->string('method')->nullable();

            $table->bigInteger('payer_user_id')->unsigned()->nullable();
            $table->foreign('payer_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');            

            $table->bigInteger('created_by_user_id')->unsigned()->nullable();
            $table->foreign('created_by_user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('payments');
    }
}
