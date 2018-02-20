<?php

require_once __DIR__."/../vendor/autoload.php";
use MakeTable\Table;

class v1519090093_CreateTable {
	public function up()
	{
	    $table = Table::create("user");
	    $table->autoIncrement("id");
	    $table->varchar('login');
	    $table->varchar('password');
	    $table->execute();
	}

	public function down()
	{

	}

}