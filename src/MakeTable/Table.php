<?php
namespace MakeTable;

class Table extends Struct {

    var $table;
    var $type;

    public function __construct($table, $type)
    {
        $this->setTable($table);
        $this->setType($type);
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param mixed $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }


    public function make(){

        // Check Type
        if ($this->getType() == "create") {
            $cols = implode(",",$this->getCols());
            $sql = "CREATE TABLE `{$this->getTable()}` ({$cols})";
            // Check Exists Primary Key
            if ($this->primaryKey) {
                $sql.=" PRIMARY KEY (`{$this->primaryKey}`)";
            }
        }


        return $sql;


    }



    /**
     * Create new table
     * @param $table
     * @param $callback
     */
    public static function create($table, $callback)
    {
        $Table = new Table($table,'create');
        // Callback
        $callback($Table);
    }

    /**
     * Execute SQL
     * @param string $db
     * @param null $sql
     */
    public function execute($db = 'default',$sql = null)
    {
        if ($sql == null) {
            $sql = $this->make();
        }

        $database = $GLOBALS['config'][$db];
        $PDO = new PDO("mysql:host={$database['host']};dbname={$database['dbname']}",$database['user'],$database['pass']);

        if ($PDO->query($sql)){
            print "\033[01;32m{$sql} [OK]\033[0m\n";
        }else{
            print "\033[01;31m{$sql} [ERRO]\033[0m\n";
        }

    }

}