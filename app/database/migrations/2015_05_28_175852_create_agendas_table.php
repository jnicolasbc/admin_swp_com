<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAgendasTable extends Migration {

	public function up()
	{
		Schema::create('agendas', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('doctor_id')->index();
			$table->string('name');
			$table->string('description');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('agendas');
	}
}