<?php
namespace MakeTable;

class Struct{

    protected $cols = [];
    var $primaryKey;
    const CURRENT_DATETIME = 0x000;
    const CURRENT_DATE = 0x001;


    /**
     * Return list array
     * @return array
     */
    public function getCols()
    {
        $arr = [];
        foreach ($this->cols as $col) {
            // Check length
            $length = ($col['length']!=null) ? "({$col['length']}) " : null;
            // Check is null
            $null = ($col['null']==false) ? "NOT NULL" : "NULL";
            // Check default value
            $default = ($col['default']!=null) ? "DEFAULT {$col['default']} " : null;
            // Auto Increment
            $autoincrement = ($col['autoincrement']) ? " AUTO_INCREMENT " : null;

            $str = "`{$col['name']}` {$col['type']} {$length} {$null} {$default} $autoincrement";

            array_push($arr,$str);
        }

        return $arr;
    }

    /**
     * Create varchar
     * @param $name
     * @param int $length
     * @param bool $null
     * @param null $default
     */
    public function varchar($name,$length = 255,$null = true,$default = null)
    {
        $this->addCol("VARCHAR",$name,$length,$null,$default);
    }

    /**
     * Create AutoIncrement
     * @param $name
     */
    public function autoIncrement($name)
    {
        $this->addCol("INT",$name,11,false,null,true);
        $this->primaryKey = $name;
    }

    /**
     * Create int
     * @param $name
     * @param int $length
     * @param bool $null
     * @param int $default
     */
    public function int($name,$length = 11,$null = true,$default = 0)
    {
        $this->addCol("INT",$name,$length,$null,$default);
    }

    /**
     * Create Text
     * @param $name
     * @param bool $null
     * @param null $default
     */
    public function text($name,$null = true,$default = null)
    {
        $this->addCol("TEXT",$name,null,$null,$default);
    }


    /**
     * Create MediumText
     * @param $name
     * @param bool $null
     * @param null $default
     */
    public function mediumText($name,$null = true,$default = null)
    {
        $this->addCol("MEDIUMTEXT",$name,null,$null,$default);
    }

    /**
     * Create Longtext
     * @param $name
     * @param bool $null
     * @param null $default
     */
    public function longText($name,$null = true,$default = null)
    {
        $this->addCol("LONGTEXT",$name,null,$null,$default);
    }

    /**
     * Create date
     * @param $name
     * @param null $null
     * @param null $default
     */
    public function date($name,$null = null, $default = null)
    {
        $this->addCol("DATE",$name,null,$null,$default);
    }

    /**
     * Create datetime
     * @param $name
     * @param $null
     * @param null $default
     */
    public function dateTime($name,$null =false,$default = null)
    {
        $this->addCol("DATETIME",$name,null,$null,$default);
    }

    /**
     * Create timestamp
     * @param $name
     */
    public function timestamp($name)
    {
        $this->addCol("TIMESTAMP",$name,null,null,null);
    }

    /**
     * Adicionar coluna
     * @param $type
     * @param $name
     * @param int $length
     * @param bool $null
     * @param null $default
     * @param bool $autoincrement
     */
    public function addCol($type,$name,$length = 255,$null = false,$default =null,$autoincrement = false)
    {
        array_push($this->cols,[
            "type" => $type,
            "name" => $name,
            "length" => $length,
            "null" => $null,
            "default" => $default,
            "autoincrement" => $autoincrement
        ]);

    }
}