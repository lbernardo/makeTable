<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 18/02/2018
 * Time: 23:48
 */
namespace MakeTable;

class Column{
    var $type;
    var $name;
    var $length;
    var $null = true;
    var $default = null;
    var $primaryKey = false;
    var $autoIncrement = false;

    /**
     * Column constructor.
     * @param $name
     * @param $length
     * @param $type
     * @return $this;
     */
    public function __construct($name,$length = 255,$type)
    {
        $this->type = $type;
        $this->name = $name;
        $this->length = $length;
        return $this;
    }


    /**
     * Set default
     * @param null $default
     * @return $this
     */
    public function setDefault($default = null)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * Set is null
     * @param bool $null
     * @return $this
     */
    public function isNull($null = true)
    {
        $this->null = $null;
        return $this;
    }


    /**
     * Autoincrement
     * @return $this
     */
    public function autoincrement()
    {
        $this->autoIncrement = true;
        $this->primaryKey = true;
        return $this;
    }

}