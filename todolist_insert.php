<?php
   try {
    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:todolist.sqlite3');

    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, 
                            PDO::ERRMODE_EXCEPTION);

    // Prepare INSERT statement to SQLite3 file db
    $insert = "INSERT INTO todolist (item, creationdate) 
                VALUES (:item, :creationdate)";
    $stmt = $file_db->prepare($insert);

    // Bind parameters to statement variables
    $stmt->bindParam(':item', $item);
    $stmt->bindParam(':creationdate', $creationdate);

    // Set values to bound variables
    $item = $_REQUEST['newitem'];
    $creationdate = $_REQUEST['creationdate'];

    // Execute statement
    $stmt->execute();

    // echoing JSON response
    echo json_encode("insert OK");

    // Close file db connection
    $file_db = null;
  }
  catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
  }
?>
