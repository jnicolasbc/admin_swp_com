<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAppointmentsTable extends Migration {

	public function up()
	{
		Schema::create('appointments', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('patient_id')->index();
			$table->integer('agenda_id')->index();
			$table->timestamp('start_date');
			$table->timestamp('end_date');
			$table->string('reason');
			$table->enum('turn', array('am.pm'))->index();
			$table->enum('state', array('pending', 'canceled', 'delayed'))->index();
			$table->time('last_date_update');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('appointments');
	}
}