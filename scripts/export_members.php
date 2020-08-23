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
$query = "SELECT * FROM stormforged.eqdkp23_members;";

$result = mysqli_query($dbconnect, $query)
   or die (mysqli_error($dbconnect));
  
//open export file
$myfile = fopen("..\data\members.txt", "w") or die("Unable to open file!");
//fwrite($myfile, $TOKEN);

$header = "Name, Class, Priority Rating, Effort Points, Gear Points\n";
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

      //deserialize the profile
      $profileArray = json_decode($row["profiledata"], true);
      $classID = $profileArray["class"];
      $class = "Unknown";

      switch ($classID) {
        case 2:
            $class = "Druid";
            break;
        case 3:
            $class = "Hunter";
            break;
        case 4:
            $class = "Mage";
            break;
        case 5:
            $class = "Paladin";
            break;
        case 6:
            $class = "Priest";
            break;
        case 7:
            $class = "Rogue";
            break;            
        case 8:
           $class = "Shaman";
            break;
        case 9:
            $class = "Warlock";
            break;
        case 10:
             $class = "Warrior";
            break;          
       }

      $output = $row["member_name"]. ", ". $class. ", " .number_format($pr,2,'.',''). ", " . number_format($earned,2,'.',''). ", " . number_format($spent,2,'.','')."\n";
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