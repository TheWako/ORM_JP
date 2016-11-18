<?php

require('./src/Entity.php');

class User extends Entity
{
    public $ID;
    public $FName;
    public $LName;
    public $Age;
    public $entity_table = 'User';
    public $entity_class = 'User';
    public $db_fields = array('ID', 'FName', 'LName', 'Age');
    public $primary_keys = array('ID');
    public function info()
    {
        return '#'.$this->ID.':'.$this->FName.' '.$this->LName.' '.$this->Age;
    }
}