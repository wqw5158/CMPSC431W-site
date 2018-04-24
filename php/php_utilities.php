<?php
global $databaseName;
$databaseName="genericTeamDB";
global $username;
$username="william";
global $password;
$password="william";
global $connection;
date_default_timezone_set('America/New_York');

function open_connection_to_mysql(){
	global $databaseName;
	global $username;
	global $password;
	global $connection;
	global $parms;
	$connection=uty_mysql_connect('localhost', $username, $password);
	if (!$connection){
		die("Could not connect to mysql:".uty_mysql_error() );
	}
	uty_mysql_select_db($connection,$databaseName);
}

function close_connection_to_mysql(){
	global $connection;
	uty_mysql_close($connection);
}

function phpMajorVersion(){
  $versions=explode(".", phpversion());
  return $versions[0];
}
#*************************PHP 5 and PHP 7 have different MySQL APIs ********************************
function uty_mysql_connect($DATABASE_SERVER, $DATABASE_USER_NAME, $DATABASE_USER_PASSWORD){
  if ( phpMajorVersion()==="5" ){
    return mysql_connect($DATABASE_SERVER, $DATABASE_USER_NAME, $DATABASE_USER_PASSWORD);	  #PHP5
  } else {
    return mysqli_connect($DATABASE_SERVER, $DATABASE_USER_NAME, $DATABASE_USER_PASSWORD);	#PHP7
  }
}

function uty_mysql_connect_error(){
  if ( phpMajorVersion()==="5" ){
    return mysql_error();	          #PHP5
  } else {
    return mysqli_connect_error();	#PHP7
  }
}

function uty_mysql_select_db($connection, $DATABASE_NAME){
  if ( phpMajorVersion()==="5" ){
    return mysql_select_db($DATABASE_NAME, $connection);	#PHP5
  } else {
    return mysqli_select_db($connection, $DATABASE_NAME);	#PHP7
  }
}

function uty_mysql_close($connection){
  if ( phpMajorVersion()==="5" ){
    return mysql_close($connection);	#PHP5
  } else {
    return mysqli_close($connection);	#PHP7
  }
}

function uty_mysql_query($cmd){
	global $connection;
  if ( phpMajorVersion()==="5" ){
    return mysql_query($cmd);	                #PHP5
  } else {
    return mysqli_query($connection,$cmd);	  #PHP7
  }
}

function uty_mysql_fetch_array($results){
  if ( phpMajorVersion()==="5" ){
    return mysql_fetch_array($results);	              #PHP5
  } else {
    return mysqli_fetch_array($results,MYSQLI_BOTH);	#PHP7
  }
}

function uty_mysql_error(){
	global $connection;
  if ( phpMajorVersion()==="5" ){
    return mysql_error();		          #PHP5
  } else {
    return mysqli_error($connection);	#PHP7
  }
}

function debug($msg){
  print("<!-- $msg -->\r\n");
}
function debug_array($a){
  print('<!--');
  print_r($a);
  print("-->");
}

function getUsernameById($userid){
  $username='';
  $sqlCmd=<<<SQL_CMD
     SELECT * FROM RegisteredUsers
     WHERE userid=$userid;
SQL_CMD;
  $qry=uty_mysql_query($sqlCmd);
  if ($qry){
    $row=uty_mysql_fetch_array($qry);
    if ($row){
       $username=$row['name'];
    }
  }
  if ($username==''){
    //not found in registered users
    //try Dealers
    $sqlCmd=<<<SQL_CMD
       SELECT * FROM Dearlers 
       WHERE userid=$userid;
SQL_CMD;
    $qry=uty_mysql_query($sqlCmd);
    if ($qry){
       $row=uty_mysql_fetch_array($qry);
       if ($row){
          $username=$row['dealershipname'];
       }
    }
  }
  if ($username==''){
     $username='unknown';
  }
  return $username;
}
function getUserEmailById($userid){
  $email='';
  $sqlCmd=<<<SQL_CMD
     SELECT * FROM Users
     WHERE userid=$userid;
SQL_CMD;
  $qry=uty_mysql_query($sqlCmd);
  if ($qry){
    $row=uty_mysql_fetch_array($qry);
    if ($row){
       $email=$row['email'];
    }
  }
  return $email;
}
function isPastDate($d){
  $todayDate=date("Y-m-d", time());
  $todayTime=strtotime($todayDate);
  $dTime=strtotime($d);
  if ($dTime<$todayTime){
      return true;
  }
  return false;
}
function isEarlier($d, $t){
  $tTime=strtotime($t);
  $dTime=strtotime($d);
  if ($dTime<=$tTime){
      return true;
  }
  return false;
}
?>
