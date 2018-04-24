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
$sellerid=$parms['sellerid'];
$userid=$parms['userid'];
$auctionid=$parms['auctionid'];
$rating=$parms['rating'];
$comments=$parms['comments'];

insertReviewInDatabase($sellerid, $userid, $auctionid, $rating, $comments);

//close the connection to MySQL database
close_connection_to_mysql();


function insertReviewInDatabase($sellerid, $userid, $auctionid, $rating, $comments){
  $todayDate=date("Y-m-d", time());
  $htmlString="";
  $msg="";
  $sqlCmd=<<<SQL_CMD
    INSERT INTO  Reviews(reviewerid, sellerid,auctionid, reviewdate, review, rating) 
    VALUES ($userid, $sellerid, $auctionid, '$todayDate', '$comments', $rating); 
SQL_CMD;
  $qry=uty_mysql_query($sqlCmd);
  if ($qry){
    $msg="Your rating and comments are submitted. Thank you for your feedback.";
  } else {
    $msg="Failed to submit your rating and comments. Please contact eAuction team at <font color=\"#0000FF\">www.eAuction.com</font>.";
  }
  $sqlCmd=<<<SQL_CMD
  SELECT avg(rating) as avgRating, count(sellerid) as totalReviews FROM Reviews 
  WHERE sellerid=$sellerid;
SQL_CMD;
$avgRating=0;
$totalReviews=0;
$qry=uty_mysql_query($sqlCmd);
if ($qry){
  $row=uty_mysql_fetch_array($qry);
  if ($row){
    $avgRating=$row['avgRating'];
    $totalReviews=$row['totalReviews'];
  }
}
$ratingStars=' ';
for ($i=0; $i<$avgRating; $i++){
  $ratingStars = $ratingStars.'&#9733;';
}
print($ratingStars.':'.$msg);
}
?>
