<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDoctorFavoriteTable extends Migration {

	public function up()
	{
		Schema::create('doctor_favorite', function(Blueprint $table) {
			$table->integer('doctor_id');
			$table->integer('patient_id');
		});
	}

	public function down()
	{
		Schema::drop('doctor_favorite');
	}
}