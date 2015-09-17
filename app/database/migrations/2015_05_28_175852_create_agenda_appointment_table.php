<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAgendaAppointmentTable extends Migration {

	public function up()
	{
		Schema::create('agenda_appointment', function(Blueprint $table) {
			$table->integer('agenda_id');
			$table->integer('appointment_id');
		});
	}

	public function down()
	{
		Schema::drop('agenda_appointment');
	}
}