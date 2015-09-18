<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLanguagesTable extends Migration {

	public function up()
	{
		Schema::create('languages', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name')->index();
			$table->string('es');
			$table->string('en')->index();
		});
	}

	public function down()
	{
		Schema::drop('languages');
	}
}