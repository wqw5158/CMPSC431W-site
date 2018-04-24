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
$text=$parms['text'];

doSearch($text);

//close the connection to MySQL database
close_connection_to_mysql();

function doSearch($text){
    debug($text);
    $words=explode(" ", strtolower($text)); //make search case insensitive
    $sqlCmd=<<<SQL_CMD
       SELECT v.vin, v.image, v.make, v.model, v.year, v.mileage, v.description,
              a.auctionid, a.buynowprice, a.enddate, v.category
       FROM vehicles AS v, auctions AS a
       WHERE v.vin=a.vin AND a.closedate IS NULL;
SQL_CMD;
    $qry=uty_mysql_query($sqlCmd);
    if (!$qry){
       print("Failed to query vehicles table.");
       return;
    }
    //now comstruct the html to display found auction in table view
  print("<table align=\"center\" border=\"0\" width=\"85%\" style=\"color: #000000\">");
  print('<tbody>');
  print("<tr style=\"background-color: #9898DB\">");
  print("  <th scope=\"col\" width=\"118px\"> Picture</th>");
  print("  <th scope=\"col\" width=\"25%\">Description</th>");
  print("  <th scope=\"col\">Buy Now Price</th>");
  print("  <th scope=\"col\">Current Bid</th>");
  print("  <th scope=\"col\">End Date</th>");
  print('  <th></th>');
  print('</tr>');
  $i=0;
  while(true){
    $row=uty_mysql_fetch_array($qry);
    if (!$row){
       break;
    }
    $vin=$row['vin'];
    $image=$row['image'];
    $make=$row['make']; $model=$row['model']; $year=$row['year']; $mileage=$row['mileage'];
    $description=$row['description'];
    $auctionid=$row['auctionid'];
    $buynowprice=$row['buynowprice'];
    $enddate=$row['enddate'];
    $category=$row['category'];
    //now to get the current max bid
    $sqlCmd="SELECT max(amount) AS maxbid ".
            "  FROM bids as b ".
            " WHERE b.auctionid=$auctionid;";
    debug($sqlCmd);
   $qry2 = uty_mysql_query($sqlCmd);
   $maxbid=0;
   if ($qry2){
     debug("found bids:".$maxbid);
     $row2=uty_mysql_fetch_array($qry2);
     $maxbid=$row2['maxbid'];
   }
   $infoString=$vin.$year.$make.$model.$mileage.'$'.$buynowprice.$enddate.'$'.$maxbid.$category;
   $searchString=strtolower($infoString);
   $matches=0;
   foreach ($words as $word){
      if (strpos($searchString, $word)!==false){
          $matches=$matches+1;
      }
   }
   debug($matches.'='.$searchString);
   if ($matches<1){
       continue;
   }
   $i=$i+1;

   if ( ($i%2) == 1){
    print("<tr style=\"background-color: #D0D0D0\">");
   } else {
    print("<tr>");
   }
    print("  <td><img src=\"images/".$image."\" width=\"100px\" style=\"padding-top: 10px; padding-bottom: 10px\" alt=\"\"/></td>");
    print('  <td>VIN: '.$vin.'<br/>'.$make.' '.$year.' '.$model.'<br/>Mileage: '.$mileage.' miles</td>');
    print("  <td>Buy Now Price: <font color=\"#FF0000\">".$buynowprice.'</font></td>');
    print("  <td>Current Bid: <font color=\"#0000FF\">".$maxbid.'</font></td>');
    print("  <td>End Date: <font color=\"#FF00FF\">".$enddate.'</font></td>');
    print("  <td><a href=\"#\" onClick=\"javascript:placeBid(".$auctionid.")\" class=\"btn btn-primary\" role=\"button\">View Details</a></td>");
    print('</tr>');
  }
  print('</tbody>');
  print('</table>');

  if ($i<1){
    print("<div style=\"align-content: center; width: 100%\"><p><br /><font color=\"#AEAEAE\">");
    print("<center>To search for [$text], no auction found. Please try different search criteria.</center></font></p></div>");
  }

  if ($i<8){
   print("<div style=\"position: absolute; bottom: 0; width: 100%\">");
  } else {
   print("<div style=\"width: 100%\">");
  }
  print("<hr style=\"border-color: #9E9E9E\">");
  print("<footer class=\"text-center\">");
  print("<div class=\"container\">");
    print("<div class=\"row\">");
      print("<div class=\"col-xs-12\" style=\"color: #AEAEAE\">");
        print("<p>Copyright © Generic Team Name( William Wang, YuChen Zeng, Robert Beck, Michael Berezanich, JingRui Duan). All rights reserved.</p>");
      print("</div>");
    print("</div>");
  print("</div>");
  print("</footer>");
  print("</div>");

}

?>
