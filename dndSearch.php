<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$conn = new mysqli("localhost", "root", "", "dnd_db");
  
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  } 
  
  //$sql = "SELECT `Service Area Code`, `Phone Numbers` ,`Preferences`, `Opstype`, `Phone Type` FROM dnd_data WHERE `Phone Numbers` IN (9827269719,9861511095,9861308927)";
  $sql = "SELECT Service Area Code, Phone Numbers , Preferences, Opstype, Phone Type FROM dnd_data WHERE Phone Numbers IN (9827269719,9861511095,9861308927)";
  $result = $conn->query($sql);

  
    if ($result->num_rows > 0) {
       while($rs = $result->fetch_assoc()){
       $outp = ''; 
       $phone = $rs["Phone Numbers"];
    
       $outp .= "{'PhoneNumbers':'"  . $phone . "',";
       $outp .= "'ServiceAreaCode':'"   . $rs["Service Area Code"]        . "',";
       $outp .= "'Preferences':'"   . $rs["Preferences"]        . "',";
       $outp .= "'Opstype':'"   . $rs["Opstype"]        . "',";
       $outp .= "'PhoneType':'". $rs["Phone Type"]     . "'}"; 
    
       $index = array_search($phone,$numArray);
       $resArray[$index] = $outp;
       }
    }
  

  $conn->close();
  //echo var_dump($rs);
  
  
  // Check if file already exists
if (file_exists($csv_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0; http_response_code(422);
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0; http_response_code(422);
}
// Allow certain file formats
if($FileType != "csv") {
    echo "Sorry, only CSV files are allowed.";
    $uploadOk = 0; http_response_code(422);
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded."; http_response_code(422);
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $csv_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file."; http_response_code(422);
    }
}