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
$query = "SELECT * 
          FROM stormforged.eqdkp23_raid_attendees
          INNER JOIN stormforged.eqdkp23_members USING (member_id)
          INNER JOIN stormforged.eqdkp23_raids USING (raid_id)
          INNER JOIN stormforged.eqdkp23_events USING (event_id)
          ORDER BY raid_date ASC
          ;";

$result = mysqli_query($dbconnect, $query)
   or die (mysqli_error($dbconnect));
  
//open export file
$myfile = fopen("..\data\attendance.txt", "w") or die("Unable to open file!");
//fwrite($myfile, $TOKEN);

$header = "Date, Raid, Value, Attendee\n";
echo $header; 
fwrite($myfile, $header);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

      //get data
      $raidDate = new DateTime();
      $raidDate->setTimestamp($row["raid_date"]);
      $attendee = $row["member_name"];
      $raidValue = $row["raid_value"];
      $raid = trim($row["event_name"]);
      $output = date_format($raidDate,'m/d/y'). ", " .$raid. ", " .$raidValue. ", " .$attendee."\n";
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