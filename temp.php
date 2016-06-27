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
  $num = '9827269719,9861511095,9861308927';
  $sql = "SELECT `Service Area Code`, `Phone Numbers` ,`Preferences`, `Opstype`, `Phone Type` FROM dnd_data WHERE `Phone Numbers` IN (".$num.")";
  //$sql = "SELECT * FROM dnd_data ORDER BY `#` LIMIT 0,10";
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
    
//       $index = array_search($phone,$numArray);
//       $resArray[$index] = $outp;
       echo $outp;
       }
    }
  

  $conn->close();
  //echo var_dump($rs);