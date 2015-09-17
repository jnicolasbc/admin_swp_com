<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsTable extends Migration {

	public function up()
	{
		Schema::create('notifications', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->index();
			$table->string('type');
			$table->string('message');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('notifications');
	}
}