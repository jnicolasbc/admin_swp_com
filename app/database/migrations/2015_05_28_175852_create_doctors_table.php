<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDoctorsTable extends Migration {

	public function up()
	{
		Schema::create('doctors', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->index();
			$table->integer('clinic_id')->index();
			$table->integer('state')->index();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('doctors');
	}
}