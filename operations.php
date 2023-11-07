<?php
$host = 'localhost';
$dbname = 'test';
$username = 'root';
$password = '';


$mysqli = new mysqli($host, $username, $password, $dbname);


if ($mysqli->connect_error) {
   die("Connection failed: " . $mysqli->connect_error);
}


if (isset($_POST['action'])) {
   switch ($_POST['action']) {
      case 'getAll':
         // Retrieve all data
         $sql = "SELECT * FROM data";
         $result = $mysqli->query($sql);
         $data = [];
         if ($result) {
            while ($row = $result->fetch_assoc()) {
               $data[] = $row;
            }
            $response = ["data" => $data, "status" => "success"];
         } else {
            $response = ["message" => "Error: " . $mysqli->error, "status" => "error"];
         }
         echo json_encode($response);
         break;
      case 'add':
         // Add a new entry
         $name = $_POST['name'];
         $sql = "INSERT INTO data (name, datetime) VALUES ('$name', NOW())";
         if ($mysqli->query($sql) === TRUE) {
            $response = [
               "id" => $mysqli->insert_id,
               "name" => $name,
               "datetime" => date('Y-m-d H:i:s'),
               "message" => "Entry added successfully",
               "status" => "success"
            ];
         } else {
            $response = ["message" => "Error: " . $mysqli->error, "status" => "error"];
         }
         echo json_encode($response);
         break;

      case 'edit':
         // Edit an entry
         $id = $_POST['id'];
         $name = $_POST['name'];
         $sql = "UPDATE data SET name = '$name' WHERE id = $id";
         if ($mysqli->query($sql) === TRUE) {
            $response = ["message" => "Entry updated successfully", "status" => "success"];
         } else {
            $response = ["message" => "Error: " . $mysqli->error, "status" => "error"];
         }
         echo json_encode($response);
         break;

      case 'delete':
         // Delete an entry
         $id = $_POST['id'];
         $sql = "DELETE FROM data WHERE id = $id";
         if ($mysqli->query($sql) === TRUE) {
            $response = ["message" => "Entry deleted successfully", "status" => "success"];
         } else {
            $response = ["message" => "Error: " . $mysqli->error, "status" => "error"];
         }
         echo json_encode($response);
         break;

      default:
         echo json_encode(["message" => "Invalid action", "status" => "error"]);
         break;
   }
}

// Close the database connection
$mysqli->close();
