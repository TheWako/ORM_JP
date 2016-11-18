<?php

require('./src/database.php');

class Entity
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }
    public function add()
    {
        foreach ($this->db_fields as $key) {
            $data[$key] = $this->$key;
        }
        $this->db->insert($this->entity_table, $data);
    }
    public function update()
    {
        foreach ($this->db_fields as $key) {
            if (!is_null($this->key)) {
                $data[$key] = $this->$key;
            }
        }
        $where = ' ';
        foreach ($primary_keys as $key) {
            $where .= ' '.$key.' = '.$this->$key.' &&';
        }
        $where = rtrim($where, '&');
        $this->db->update($this->entity_table, $data, $where);
    }
    public function remove()
    {
        $where = ' ';
        foreach ($this->primary_keys as $key) {
            $where .= ' '.$key.' = '.$this->$key.' &&';
        }
        $where = rtrim($where, '&');
        $this->db->delete($this->entity_table, $where);
    }
}