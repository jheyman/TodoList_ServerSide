<?php
 
  // Set default timezone
  date_default_timezone_set('UTC');
 
  try {
    /**************************************
    * Create databases and                *
    * open connections                    *
    **************************************/
 
    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:todolist.sqlite3');
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, 
                            PDO::ERRMODE_EXCEPTION);
 
    /**************************************
    * Create tables                       *
    **************************************/

    // Create table messages
    $file_db->exec("CREATE TABLE IF NOT EXISTS todolist (
                    item TEXT,
                    creationdate DATETIME,
                    priority INTEGER)");
 
    // Array with some test data to insert to database             
    
    $items = array(
                  array('item' => 'testItemTODO1',
                        'creationdate' => '2015-08-24 01:02:03',
                        'priority' => 1),
                  array('item' => 'testItemTODO2',
                        'creationdate' => '2015-08-24 01:02:04',
                        'priority' => 2),
                  array('item' => 'testItemTODO3',
                        'creationdate' => '2015-08-24 01:02:05',
                        'priority' => 3),
                );
  
    // Prepare INSERT statement to SQLite3 file db
    $insert = "INSERT INTO todolist (item, creationdate, priority) 
                VALUES (:item, :creationdate, :priority)";
    $stmt = $file_db->prepare($insert);

    // Bind parameters to statement variables
    $stmt->bindParam(':item', $item);
    $stmt->bindParam(':creationdate', $creationdate);
    $stmt->bindParam(':priority', $priority);

    // Loop thru all messages and execute prepared insert statement
    foreach ($items as $i) {
      // Set values to bound variables
      $item =  $i['item'];
      $creationdate = $i['creationdate'];
      $priority = $i['priority'];
      // Execute statement
      $stmt->execute();
    }

    // Select all data from file db messages table 
    $result = $file_db->query('SELECT * FROM todolist');

     $rows = $result->fetchAll();
    
 if ($rows) {
    $response["items"]   = array();
    
    foreach ($rows as $row) {
        $item             = array();
        $item["item"] = $row["item"];
        $item["creationdate"] = $row["creationdate"];
        $item["priority"] = $row["priority"];
        
        //update our repsonse JSON data
        array_push($response["items"], $item);
    }
    
    // echoing JSON response
    echo json_encode($response);
}

//print(json_encode($result->fetchAll()));


// Need this next line  since doing multiple PDO operations in a single functions
// without this line, the next request on file_db results in error "SQLSTATE[HY000]: General error: 6 database table is locked"
unset($result); 
 
    /**************************************
    * Drop tables                         *
    **************************************/
 
    // Drop table messages from file db
    //$file_db->exec("DROP TABLE shoppinglist");
    // Drop table messages from memory db
   // $memory_db->exec("DROP TABLE messages");
 
 
    /**************************************
    * Close db connections                *
    **************************************/
 
    // Close file db connection
    $file_db = null;
    // Close memory db connection
    //$memory_db = null;
  }
  catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
  }
?>
