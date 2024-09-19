<?php
class DB{ 
    private $servername = "localhost";
    private $username = "debian-sys-maint"; 
    private $password = "0DXddFBx19pUUQ6F"; 
    private $dbname = "Product_inventory";
    private $table      = "ProductInvoice"; 

    
    public function __construct(){ 
        if(!isset($this->db)){ 
            // Connect to the database 
            $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname); 
            if($conn->connect_error){ 
                die("Failed to connect with MySQL: " . $conn->connect_error); 
            }else{ 
                $this->db = $conn; 
            } 
        } 
    } 
     
    /* 
     * Returns rows from the database based on the conditions 
     * @param array select, where, order_by, limit and return_type conditions 
     */ 
    public function getRows($conditions = array()){ 
        if(array_key_exists("where", $conditions)) {
            $whereSql = ''; 
            $i = 0;
        
            foreach($conditions['where'] as $key => $value){
                $pre = ($i > 0) ? ' AND ' : ' WHERE ';
                $whereSql .= $pre . $key . " = '" . $this->db->real_escape_string($value) . "'";
                $i++;
            }
        
            // Constructing the SQL query with the WHERE clause
            $sql = "SELECT pi.id, pi.invoiceid, i.product_name, pi.quantity, pi.price, c.Company, po.status, pi.notes 
                    FROM PurchaseOrders po 
                    RIGHT JOIN ProductInvoice pi ON pi.invoiceid = po.ID
                    INNER JOIN Inventory i ON i.id = pi.product
                    INNER JOIN CONTACTS c ON po.supplierid = c.id" . $whereSql;
        
        
            // Execute the query (assuming you're executing it later in the code)
            // $result = $this->db->query($sql);
        
            $response['msg'] = "Condition applied: " . json_encode($conditions['where']);
        } else {
            $response['msg'] = "No conditions provided.";
        }
        
        if(array_key_exists("order_by",$conditions)){ 
            $sql .= ' ORDER BY '.$conditions['order_by'];  
        }else{ 
            $sql .= " ORDER BY id ASC ";  
        } 
         
        if(array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){ 
            $sql .= ' LIMIT '.$conditions['start'].','.$conditions['limit'];  
        }elseif(!array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){ 
            $sql .= ' LIMIT '.$conditions['limit'];  
        } 
         
        $result = $this->db->query($sql); 

         
        if(array_key_exists("return_type", $conditions) && $conditions['return_type'] != 'all'){ 
            switch($conditions['return_type']){ 
                case 'count': 
                    $data = $result->num_rows; 
                    break; 
                case 'single': 
                    $data = $result->fetch_assoc(); 
                    break; 
                default: 
                    $data = ''; 
            } 
        }else{ 
            if($result->num_rows > 0){ 
                while($row = $result->fetch_assoc()){ 
                    $data[] = $row; 
                } 
            } 
        } 
        return !empty($data)?$data:false; 
    } 
     
    /* 
     * Update data into the database 
     * @param array the data for updating into the table 
     * @param array where condition on updating data 
     */ 
    public function update($data, $conditions){ 
        if(!empty($data) && is_array($data)){ 
            $colvalSet = ''; 
            $whereSql = ''; 
            $i = 0; 
            if(!array_key_exists('modified',$data)){ 
                $data['modified'] = date("Y-m-d H:i:s"); 
            } 
            foreach($data as $key=>$val){ 
                $pre = ($i > 0)?', ':''; 
                $colvalSet .= $pre.$key."='".$this->db->real_escape_string($val)."'"; 
                $i++; 
            } 
            if(!empty($conditions)&& is_array($conditions)){ 
                $whereSql .= ' WHERE '; 
                $i = 0; 
                foreach($conditions as $key => $value){ 
                    $pre = ($i > 0)?' AND ':''; 
                    $whereSql .= $pre.$key." = '".$value."'"; 
                    $i++; 
                } 
            } 
            $query = "UPDATE {$this->table} SET ".$colvalSet.$whereSql; 
            $update = $this->db->query($query); 
            return $update?$this->db->affected_rows:false; 
        }else{ 
            return false; 
        } 
    } 
     
    /* 
     * Delete data from the database 
     * @param array where condition on deleting data 
     */ 
    public function delete($conditions){ 
        $whereSql = ''; 
        if(!empty($conditions)&& is_array($conditions)){ 
            $whereSql .= ' WHERE '; 
            $i = 0; 
            foreach($conditions as $key => $value){ 
                $pre = ($i > 0)?' AND ':''; 
                $whereSql .= $pre.$key." = '".$value."'"; 
                $i++; 
            } 
        } 
        $query = "DELETE FROM {$this->table} $whereSql"; 
        $delete = $this->db->query($query); 
        return $delete?true:false; 
    } 
} 
 
?>