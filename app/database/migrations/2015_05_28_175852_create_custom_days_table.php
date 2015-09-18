<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomDaysTable extends Migration {

	public function up()
	{
		Schema::create('custom_days', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('agenda_id');
			$table->tinyInteger('starttime_am');
			$table->time('endtime_am');
			$table->integer('lunch_start');
			$table->time('lunch_end');
			$table->string('starttime_pm');
			$table->time('endtime_pm');
			$table->integer('is_day_off');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('custom_days');
	}
}