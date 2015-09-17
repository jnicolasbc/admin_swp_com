<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConfigDaysTable extends Migration {

	public function up()
	{
		Schema::create('config_days', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('agenda_id')->unsigned();
			$table->integer('day')->index();
			$table->time('starttime_am');
			$table->time('endtime_am')->nullable();
			$table->time('lunch_start');
			$table->time('lunch_end');
			$table->time('starttime_pm');
			$table->time('endtime_pm');
			$table->integer('is_day_off');
		});
	}

	public function down()
	{
		Schema::drop('config_days');
	}
}