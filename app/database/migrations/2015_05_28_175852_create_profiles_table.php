<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProfilesTable extends Migration {

	public function up()
	{
		Schema::create('profiles', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->index();
			$table->integer('address_id');
			$table->string('picture');
			$table->timestamps();
			$table->string('phone', 10);
		});
	}

	public function down()
	{
		Schema::drop('profiles');
	}
}