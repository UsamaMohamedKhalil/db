<?php
namespace Db\Firstpack;
//interface  dbContract
//{
//    public function insert($data);
//    public function update($data);
//    public function delete();
//    public function select($columns = "*");
//    public function execute();
//}
class db
{
    private $nameTable;
    private $sql;
    private $connection;
    public function __construct($host, $user, $pass, $db,$table){
        $this->connection = mysqli_connect($host, $user, $pass, $db);
        $this->nameTable = $table;
    }
    public function insert($data)
    {
        $columns ="";
        $values = "";
        foreach($data as $column => $value){
            $columns .= "`$column`,";
            $values .= "'$value',";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");
        $this->sql = "INSERT INTO $this->nameTable ($columns) VALUES ($values)";
        return $this;
    }

    public function update($data)
    {
        $rows = "";
        foreach ($data as $column => $value) {
            $rows .= "`$column` = '$value', ";
        }
        // Trim the trailing comma and space
        $rows = rtrim($rows, ", ");

        // Build the SQL update statement
        $this->sql = "UPDATE $this->nameTable SET $rows";
        return $this;  // This allows method chaining
    }

    public function delete()
    {
        $this->sql = "DELETE FROM `$this->nameTable`";
        return $this;
    }

    public function select($columns = "*")
    {
        $this->sql = "SELECT $columns FROM $this->nameTable";
        return $this;
    }
    public function where($column,$operator ,$value){
        $this->sql .= " WHERE `$column` $operator '$value'";
        return $this;
    }
    public function andWhere($column,$operator ,$value){
        $this->sql .= " AND `$column` $operator '$value'";
        return $this;
    }
    public function orWhere($column,$operator ,$value){
        $this->sql .= " OR `$column` $operator '$value'";
        return $this;
    }
    public function execute()
    {
        mysqli_query($this->connection, $this->sql);
       return mysqli_affected_rows($this->connection);
    }
    public function fetch()
    {
        $query = mysqli_query($this->connection, $this->sql);
        return mysqli_fetch_all($query,MYSQLI_ASSOC);
    }
    public function get()
    {
        $query = mysqli_query($this->connection, $this->sql);
        return mysqli_fetch_assoc($query);
    }
}