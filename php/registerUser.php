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
$email=$parms['email'];
$password=$parms['password'];
$name=$parms['name'];
$phonenumber=$parms['phonenumber'];
$income=$parms['income'];
$gender=$parms['gender'];
$age=$parms['age'];
$routingnumber=$parms['routingnumber'];
$accountnumber=$parms['accountnumber'];
$address=$parms['address'];
  
$ret=validateUsernamePassword($email, $password,$name, $phonenumber, $income, $gender, $age, $routingnumber, $accountnumber, $address);
print($ret);

//close the connection to MySQL database
close_connection_to_mysql();

function validateUsernamePassword($email, $password,$name, $phonenumber, $income, $gender, $age, $routingnumber, $accountnumber, $address){
  //check if the email is already registered
  $sqlCmd=<<<SQL_CMD
    SELECT * FROM Users WHERE email='$email';
SQL_CMD;
  $qry=uty_mysql_query($sqlCmd);
  if ($qry){
    $row=uty_mysql_fetch_array($qry);
    if ($row) {
      return "1:Email is already registered:";
    }
  }
  //now same into database
  $sqlCmd=<<<SQL_CMD
    INSERT INTO Users(email, password, address, phonenumber)
    VALUES ('$email', '$password', '$address', '$phonenumber');
SQL_CMD;
  $qry=uty_mysql_query($sqlCmd);
  if (!$qry){
    return "2:Failed to insert into users table.";
  }
  //get new user id
  $sqlCmd=<<<SQL_CMD
     SELECT * FROM Users WHERE email='$email';
SQL_CMD;
  $userid=0;
  $qry=uty_mysql_query($sqlCmd);
  if ($qry){
    $row=uty_mysql_fetch_array($qry);
    if ($row) {
      $userid=$row['userid'];
    }
  }
  if ($userid<1){
    //should not have this case
    return "2:Failed to query regestered user id.";
  }
  //update RegisteredUsers
  $sqlCmd=<<<SQL_CMD
    INSERT INTO RegisteredUsers(userid, name,age,gender,income)
    VALUES ($userid, '$name', $age, '$gender', $income);
SQL_CMD;
  $qry=uty_mysql_query($sqlCmd);
  if (!$qry){
    return "2:Failed to insert into RegisteredUsers table.";
  }
  //update bank account
  $sqlCmd=<<<SQL_CMD
     INSERT INTO BankAccount(userid,accountno,routingno)
     VALUES ($userid, '$accountnumber', '$routingnumber');
SQL_CMD;
  //debug("2:".$sqlCmd.":");
  $qry=uty_mysql_query($sqlCmd);
  if (!$qry){
    return "2:Failed to insert into BankAccount table.";
  }

  return "0:Successfully registered:";
}
?>
