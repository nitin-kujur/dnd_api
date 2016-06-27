<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$list=$outp='';
$outNull = "{'PhoneNumbers':'--','ServiceAreaCode':'--','Preferences':'--','Opstype':'--','PhoneType':'--'}";
$resArray = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $numlist = ($_POST["numlist"]);
  $numArray = explode(",",$numlist);
  
  foreach ($numArray as $var){
      array_push($resArray, $outNull);
  }
  
  $conn = new mysqli("localhost", "root", "", "dnd_db");
  
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  } 
  
  $result = $conn->query("SELECT `Service Area Code`, `Phone Numbers` ,`Preferences`, `Opstype`, `Phone Type` FROM dnd_data WHERE `Phone Numbers` IN (".$numlist.")");

  
    if ($result->num_rows > 0) {
       while($rs = $result->fetch_array(MYSQLI_ASSOC)){
       $outp = 0; 
       $phone = $rs["PhoneNumbers"];
    
       $outp .= "{'PhoneNumbers':'"  . $phone . "',";
       $outp .= "'ServiceAreaCode':'"   . $rs['ServiceAreaCode']        . "',";
       $outp .= "'Preferences':'"   . $rs['Preferences']        . "',";
       $outp .= "'Opstype':'"   . $rs['Opstype']        . "',";
       $outp .= "'PhoneType':'". $rs['PhoneType']     . "'}"; 
    
       $index = array_search($phone,$numArray);
       $resArray[$index] = $outp;
       }
    }
  

  $conn->close();

  echo("[".implode(",",$resArray)."]");
 

}