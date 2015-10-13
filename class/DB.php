<?php

class DB {
 
    protected $db_name = '';
    protected $db_user = '';
    protected $db_pass = '';
    protected $db_host = '';
	var $connection;
	
    public function connect() {
        $conn = mysqli_connect($this->db_host, $this->db_user, $this->db_pass,$this->db_name);
		
        if(!$conn)// testing the connection
        {  
            die ("Cannot connect to the database");
        }

        else
        {
		$this->connection = $conn;
        }
		return $this->connection;
    }
 

    public function processRowSet($rowSet, $singleRow=false)
    {
        $resultArray = array();
        while($row = mysqli_fetch_assoc($rowSet))
        {
            array_push($resultArray, $row);
        }
 
        if($singleRow === true)
            return $resultArray[0];
 
        return $resultArray;
    }
 

    public function select($table, $where) {
        $sql = "SELECT * FROM $table WHERE $where";
        $result = mysqli_query($this->connection,$sql);
        if(mysqli_num_rows($result) == 1)
            return $this->processRowSet($result, true);
 
        return $this->processRowSet($result);
    }
 

    public function update($sql) {
		$result = mysqli_query($this->connection,$sql);
 
       return $result;
    }


    public function insert($sql) {

       $result = mysqli_query($this->connection,$sql);
    
       return $this->connection->insert_id;
 
    }
	
	public function delete($sql) {

       $result = mysqli_query($this->connection,$sql);
 
       return $result;
 
    }
 
}
 
?>