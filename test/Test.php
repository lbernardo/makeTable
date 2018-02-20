<?php

require_once __DIR__."/../vendor/autoload.php";

use MakeTable\Table;


class Version22201919 {
    public function up(){
        $Table = Table::alter("lis_talk");
        $Table->varchar("name",255);
        $Table->dateTime("date");
    }
}

$d = new Version22201919();
$d->up();