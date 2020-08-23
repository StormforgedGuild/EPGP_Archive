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
          FROM stormforged.eqdkp23_items 
          INNER JOIN stormforged.eqdkp23_members USING (member_id)
          INNER JOIN stormforged.eqdkp23_raids USING (raid_id)
          INNER JOIN stormforged.eqdkp23_events USING (event_id)
          ORDER BY item_date ASC
          ;";

$result = mysqli_query($dbconnect, $query)
   or die (mysqli_error($dbconnect));
  
//open export file
$myfile = fopen("..\data\items.txt", "w") or die("Unable to open file!");
//fwrite($myfile, $TOKEN);

$header = "Date, Buyer, Name, Raid, Value\n";
echo $header; 
fwrite($myfile, $header);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

      //get data
      $itemDate = new DateTime();
      $itemDate->setTimestamp($row["item_date"]);
      $member = $row["member_name"];
      $itemName = html_entity_decode($row["item_name"], ENT_QUOTES);
      $raid = $row["event_name"];
      $points = $row["item_value"];
      $output = date_format($itemDate,'m/d/y'). ", " .$member. ", " .$itemName. ", " .$raid. ", " .$points."\n";
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