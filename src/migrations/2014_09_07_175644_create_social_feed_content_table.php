<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialFeedContentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('social_feed_content', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('content_id')->unique();
            $table->integer('content_created');
            $table->text('content_text')->nullable();
            $table->string('content_creator')->nullable();
            $table->text('content_creator_name')->nullable();
            $table->integer('shown')->default(0);
            $table->text('hashtags')->nullable();
            $table->integer('approved')->default(0);
            $table->string('media_url')->nullable();
            $table->string('content_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('social_feed_content');
	}

}
