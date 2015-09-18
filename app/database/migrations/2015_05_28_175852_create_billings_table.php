<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBillingsTable extends Migration {

	public function up()
	{
		Schema::create('billings', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->index();
			$table->string('description');
			$table->decimal('amount', 8,2);
			$table->timestamps();
			$table->date('date');
		});
	}

	public function down()
	{
		Schema::drop('billings');
	}
}