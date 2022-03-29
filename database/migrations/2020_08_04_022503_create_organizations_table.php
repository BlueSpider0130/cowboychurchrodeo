<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();

            $table->string('type')->nullable();
            
            $table->string('name')->unique();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode')->nullable();
            $table->char('country_code', 2)->default('US');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->text('admin_notes')->nullable();

            $table->text('site_description')->nullable();
            $table->string('site_title')->nullable();
            
            $table->string('site_logo_path')->nullable();
            $table->string('site_banner_path')->nullable();
            $table->boolean('site_header_banner_show')->default(0);
    
            $table->string('site_font_family')->nullable();
            $table->string('site_font_size')->nullable();
            $table->string('site_text_color')->nullable();
            $table->string('site_background_color')->nullable();

            $table->string('site_header_font_family')->nullable();
            $table->string('site_header_font_size')->nullable();
            $table->string('site_header_text_color')->nullable();
            $table->string('site_header_background_color')->nullable();
            
            $table->boolean('site_footer_show')->default(0);
            $table->text('site_footer_content')->nullable();
            $table->string('site_footer_font_family')->nullable();
            $table->string('site_footer_font_size')->nullable();
            $table->string('site_footer_text_color')->nullable();
            $table->string('site_footer_background_color')->nullable();            

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
        Schema::dropIfExists('organizations');
    }
}
