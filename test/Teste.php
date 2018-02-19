<?php

require_once __DIR__."/../vendor/autoload.php";

use MakeTable\Table;


Table::create("lis_talk",function ($table){
    $table->autoIncrement("id");
    $table->varchar("nome");
    $table->varchar('sobrenome');
    $table->int("telefone",25);
    $table->date("data");
    $table->dateTime("dataHora");
    $table->text("meuTexto");
    $table->longText("longTexto");
    $table->mediumText("textoMedio");
    print $table->make();
});