<?php

//connect to the DB
$hostname = "localhost";
$username = "root";
$password = "comsat1!comsat1!";
$db = "stormforged";

$dbconnect=mysqli_connect($hostname,$username,$password,$db);
if ($dbconnect->connect_error) {
  echo 'DB connect failure';
  die("Database connection failed: " . $dbconnect->connect_error);
}

// run query
//$query = "SELECT * FROM stormforged.eqdkp23_member_points INNER JOIN stormforged.eqdkp23_members USING (member_id);";
$query = "SELECT * FROM stormforged.eqdkp23_members;";

$result = mysqli_query($dbconnect, $query)
   or die (mysqli_error($dbconnect));
  
//open export file
$myfile = fopen("..\data\members.txt", "w") or die("Unable to open file!");
//fwrite($myfile, $TOKEN);

$header = "Name, Priority Rating, Effort Points, Gear Points\n";
echo $header; 
fwrite($myfile, $header);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

      //deserialize the points
      $pointsArray = unserialize($row["points"]);
      $earned = $pointsArray[1][0];
      $spent = $pointsArray[1][1] + 2000;
      $pr = $earned/$spent;
      $output = $row["member_name"]. ", " .number_format($pr,2). ", " . number_format($earned,2). ", " . number_format($spent,2)."\n";
      echo $output;
      fwrite($myfile, $output);
    }
  } else {
    echo "0 results";
  }

//close file/db
fclose($myfile);
$dbconnect->close();

?>