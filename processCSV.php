<?php
$target_dir = "uploads/";
$csv_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$FileType = pathinfo($csv_file,PATHINFO_EXTENSION);

  // Check if file already exists
if (file_exists($csv_file)) {
    //echo "Sorry, file already exists.";
    $uploadOk = 0; http_response_code(422);
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    //echo "Sorry, your file is too large.";
    $uploadOk = 0; http_response_code(422);
}
// Allow certain file formats
if($FileType != "csv") {
    //echo "Sorry, only CSV files are allowed.";
    $uploadOk = 0; http_response_code(422);
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    //echo "Sorry, your file was not uploaded."; http_response_code(422);
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $csv_file)) {
        //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        //echo "Sorry, there was an error uploading your file."; 
        http_response_code(422);
    }
}

$outNull = "','ServiceAreaCode':'--','Preferences':'--','Opstype':'--','PhoneType':'--'}";
$numArray = [];
$resArray = array();

$file = fopen($csv_file,"r");

while(! feof($file))
  {
  array_push( $numArray,implode( fgetcsv($file) ) );
  }
fclose($file); 

unlink($csv_file);

if(sizeof($numArray)>6)
array_pop($numArray);   //removes last elemnt of array
array_shift($numArray); //removes 1st elemnt of array

$numlist = implode(",",$numArray);

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
  header_remove(); 
 echo (str_replace("'",'"',("[".implode(",",$resArray)."]")));
