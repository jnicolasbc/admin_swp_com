<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClinicsTable extends Migration {

	public function up()
	{
		Schema::create('clinics', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->index();
			$table->string('name')->index();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('clinics');
	}
}