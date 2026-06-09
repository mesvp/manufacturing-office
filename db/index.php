<?php 
$servername = "localhost";
$username = "u452440467_CRM";
$password = "Crmproject@123";
$dbname = "u452440467_CRM";

// Create connection
$conn = mysqli_connect($servername, $username, $password,$dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";

//$sql="ALTER TABLE `admins` ADD `deleted_at` TIMESTAMP NOT NULL AFTER `updated_at`;";
$sqr=mysqli_query($conn,"SHOW TABLES");
while($fetch=mysqli_fetch_assoc($sqr))
{
    // echo "<pre>";
    // print_r($fetch);
    // echo "</pre>";
    $sql="ALTER TABLE `".$fetch['Tables_in_crm']."` ADD `deleted_at` TIMESTAMP NOT NULL ";
    mysqli_query($conn,$sql);
}
?>