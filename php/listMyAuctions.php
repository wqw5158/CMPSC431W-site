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
$userid=$parms['userid'];

listMyAuctions($userid);

//close the connection to MySQL database
close_connection_to_mysql();

function listMyAuctions($userid){
    debug($userid);
    $sqlCmd=<<<SQL_CMD
       SELECT v.vin, v.image, v.make, v.model, v.year, v.mileage, v.description,
              a.auctionid, a.buynowprice, a.enddate, v.category, a.closedate
       FROM vehicles AS v, auctions AS a
       WHERE v.vin=a.vin AND a.sellerid=$userid;
SQL_CMD;
    $qry=uty_mysql_query($sqlCmd);
    if (!$qry){
       print("Failed to query Auction table.");
       return;
    }
    //now comstruct the html to display found auction in table view
  print("<table align=\"center\" border=\"0\" width=\"85%\" style=\"color: #000000\">");
  print('<tbody>');
  print("<tr style=\"background-color: #9898DB\">");
  print("  <th scope=\"col\" width=\"118px\"> Picture</th>");
  print("  <th scope=\"col\" width=\"20%\">Description</th>");
  print("  <th scope=\"col\">Buy Now Price</th>");
  print("  <th scope=\"col\">Current Bid</th>");
  print("  <th scope=\"col\">End Date</th>");
  print("  <th scope=\"col\">Status</th>");
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
    $closedate=$row['closedate'];
    $status="Active";
    if (strlen($closedate)>4){
       $status="Closed on: ".$closedate;
    }
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
     if ($row2){
       if (isset($row2['maxbid'])){
          $maxbid=$row2['maxbid'];
       }
     }
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
    print("  <td>$status</td>");
    print('</tr>');
  }
  print('</tbody>');
  print('</table>');

  if ($i<1){
    print("<div style=\"align-content: center; width: 100%\"><p><br /><font color=\"#AEAEAE\">");
    print("<center>You have not created any auction yet.</center></font></p></div>");
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
