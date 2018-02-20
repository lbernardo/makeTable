<?php
namespace MakeTable;

class Table extends Struct {

    var $table;
    var $type;
    var $db;


    /**
     * Table constructor.
     * @param $table
     * @param string $db
     * @param string $type
     */
    public function __construct($table,$db = 'default',$type = 'create')
    {
        $this->table = $table;
        $this->db = $db;
        $this->type = $type;
    }

    public function make()
    {
       if ($this->type == 'create') {

           // Verify primary
           if ($this->primaryKey){
               $primary = ", PRIMARY KEY ({$this->primaryKey})";
           }

           $sql = "CREATE TABLE `{$this->table}`  (".implode(",",$this->getCols())." {$primary})";
       } elseif ($this->type == 'alter') {
           $sql = "ALTER TABLE `{$this->table}` ADD ".implode(" ADD ",$this->getCols());
       } elseif ($this->type == 'drop') {
           $sql  = "DROP TABLE `{$this->table}` ";
       }

       return $sql;
    }


    /**
     * Execute SQL
     * @param null $sql
     */
    public function execute($sql = null)
    {
        if ($sql == null) {
            $sql = $this->make();
        }

        $config = $GLOBALS['__mk_config__'][$this->db];

        if (!$config) {
            print "\033[01;31mError Database config\033[0m\n";
        }

        $PDO = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']}",$config['username'],$config['password']);

        $PDO->query($sql);

    }


    /**
     * Create table
     * @param $table
     * @param string $db
     * @return Table
     */
    public static function create($table,$db = 'default')
    {
        $Table = new Table($table,$db);
        return $Table;
    }



    /**
     * Alter table
     * @param $table
     * @param string $db
     * @return Table
     */
    public static function alter($table,$db = 'default')
    {
        $Table = new Table($table,$db,'alter');
        return $Table;
    }

    /**
     * Drop table
     * @param $table
     * @param $db
     * @return Table
     */
    public static function drop($table,$db = 'default'){
        $Table = new Table($table,$db,'drop');
        $Table->execute();
    }



}