<?php
  try {

    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:todolist.sqlite3');
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, 
                            PDO::ERRMODE_EXCEPTION);

     if($_REQUEST["whereClause"] == "*") {
        $delete = "DELETE FROM todolist";
     }
     else {

	$tmp = SQLite3::escapeString($_REQUEST["whereClause"]);
	$delete = "DELETE FROM todolist WHERE item='".$tmp."'";
     }

    $stmt = $file_db->prepare($delete);

    // Execute statement
    $stmt->execute();

    // Select all data from file db messages table 
    $result = $file_db->query('SELECT * FROM todolist');

     $rows = $result->fetchAll();

 if ($rows) {
    $response["items"]   = array();

    foreach ($rows as $row) {
        $item             = array();
        $item["item"] = $row["item"];
        $item["creationdate"] = $row["creationdate"];

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
 
    // Close file db connection
    $file_db = null;
  }
  catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
  }
?>
