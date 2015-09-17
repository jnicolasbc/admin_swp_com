<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAddressesTable extends Migration {

	public function up()
	{
		Schema::create('addresses', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('city_id');
			$table->string('my_address');
		});
	}

	public function down()
	{
		Schema::drop('addresses');
	}
}