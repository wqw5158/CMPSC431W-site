<?php
 // The following code is the same for every php file
 //---- always include the following code in every php file --------
header('Content-type: text/xml');
header('Pragma: public');
header('Cache-control: private');
header('Expires: -1');
require_once('./php_utilities.php'); //open mysql connection
open_connection_to_mysql();
if (isset($argv)){
  foreach ($argv as $arg) {
      $e=explode("=",$arg);
      if(count($e)==2)
          $_GET[$e[0]]=$e[1];
      else
          $_GET[$e[0]]=0;
  }
}
global $parms;
$parms=array_merge($_GET, $_POST);
//------------------- end of fix part -------------------------------

//get parameters passed to this php file
//debug_array($parms);
$username=$parms['username'];
$password=$parms['password'];

validateUsernamePassword($username, $password);

//close the connection to MySQL database
close_connection_to_mysql();

function validateUsernamePassword($username, $password){
  $sqlCmd="SELECT * FROM Users ".
        "WHERE email='".$username."' AND password='".$password."';";
$qry=uty_mysql_query($sqlCmd);
if (!$qry){
  //no user found
  print("0:none:");
  return;
}
$row=uty_mysql_fetch_array($qry);
if (!$row){
  //no user found
  print("0:none:");
  return;
}

$userid=$row['userid'];
$name="";
//now get user name
$sqlCmd="SELECT name FROM RegisteredUsers ".
        "WHERE userid=".$userid.";";
$qry=uty_mysql_query($sqlCmd);
if ($qry){
   $row=uty_mysql_fetch_array($qry);
   if ($row){
     $name=$row['name'];
   }
}
$sqlCmd="SELECT dealershipname FROM Dealers ".
        "WHERE userid=".$userid.";";
$qry=uty_mysql_query($sqlCmd);
if ($qry){
   $row=uty_mysql_fetch_array($qry);
   if ($row){
     $name=$row['dealershipname'];
   }
}

print($userid.":".$name.":");
}
?>
