<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$list=$outp='';
$outNull = "','ServiceAreaCode':'--','Preferences':'--','Opstype':'--','PhoneType':'--'}";
$resArray = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $numlist = ($_POST["numlist"]);
  $numArray = explode(",",$numlist);
  
  foreach ($numArray as $var){
      $outTemp = "{'PhoneNumbers':'".$var.$outNull;
      array_push($resArray, $outTemp);
  }
  
  //-----------------------------------------------------------------------
  $conn = new mysqli("localhost", "root", "", "dnd_db");
  
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    http_response_code(422);
  } 
  
  $sql = "SELECT `Service Area Code`, `Phone Numbers` ,`Preferences`, `Opstype`, `Phone Type` FROM dnd_data WHERE `Phone Numbers` IN (".$numlist.")";
  
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
  
  //-----------------------------------------------------------------------

  echo("[".implode(",",$resArray)."]");
  
 

}