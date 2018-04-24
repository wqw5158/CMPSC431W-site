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

buildReviewHtml($sellerid);

//close the connection to MySQL database
close_connection_to_mysql();


function buildReviewHtml($sellerid){
  $htmlString="";
  $sqlCmd=<<<SQL_CMD
    SELECT * FROM Reviews 
    WHERE sellerid=$sellerid;
SQL_CMD;
  $qry=uty_mysql_query($sqlCmd);
  if ($qry){
    while (true){
       $row=uty_mysql_fetch_array($qry);
       if (!$row){ 
         break;
       }
       $reviewerid=$row['reviewerid'];
       $reviewdate=$row['reviewdate'];
       $review=$row['review'];
       $rating=$row['rating'];
       $ratingStars='';
       for ($i=0; $i<$rating; $i++){
         $ratingStars = $ratingStars."&#9733;";
       }
       $reviewername=getUsernameById($reviewerid);
       $subHtml=<<<HTML_TEXT
       <b>$reviewername</b><br/>
       <font color="#EB1147">$ratingStars</font><br/>
       <font color="#DDA809">$reviewdate</font><br />
       <p>$review</p>
HTML_TEXT;
       $htmlString=$htmlString.$subHtml;
   }
    
  }
  print($htmlString);
}
?>
