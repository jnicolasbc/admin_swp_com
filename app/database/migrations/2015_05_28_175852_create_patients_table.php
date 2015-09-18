<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePatientsTable extends Migration {

	public function up()
	{
		Schema::create('patients', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->index();
			$table->integer('main')->index();
			$table->enum('relationship', array('son', 'daughter', 'wife', 'husband'))->nullable()->index();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('patients');
	}
}