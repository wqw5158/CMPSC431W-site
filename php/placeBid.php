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
$auctionid=$parms['auctionid'];
$userid=$parms['userid'];
$bidamount=$parms['bidamount'];
$buynowprice=$parms['buynowprice'];

buildSubmitBidPage($auctionid, $userid, $bidamount, $buynowprice);

//close the connection to MySQL database
close_connection_to_mysql();


function buildSubmitBidPage($auctionid, $userid, $bidamount, $buynowprice){\
  /* this function is used for 3 cases:
     (1) generate the intial place bid page
     (2) insert a valied bid and then generate the place bid page again
     (3) insert a buy-now and then close the auction
  */
  debug($auctionid.','.$userid.','.$bidamount.','.$buynowprice);
  $biddername=getUsernameById($userid);
  $timestamp=date("Y-m-d H:i:s", time());
  if ($bidamount>0){
    //(2) save a valid bid into database
    $sqlCmd=<<<SQL_CMD
      INSERT INTO bids(auctionid, bidderid, amount, timestamp) 
      VALUES ($auctionid, $userid, $bidamount, "$timestamp");
SQL_CMD;
     debug($sqlCmd);
     $qry=uty_mysql_query($sqlCmd);
     if (!$qry){
       print("Failed to insert new bid into bids table.");
       return;
     }
   } else if ($buynowprice>0){
     //(3) user clicked buy now button
    $sqlCmd=<<<SQL_CMD
      INSERT INTO bids(auctionid, bidderid, amount, timestamp) 
      VALUES ($auctionid, $userid, $buynowprice, "$timestamp");
SQL_CMD;
     debug($sqlCmd);
     $qry=uty_mysql_query($sqlCmd);
     if (!$qry){
       print("Failed to insert buy now price into bids table.");
       return;
     }
     //close the auction
     $closedate=date("Y-m-d", time());
     $sqlCmd=<<<SQL_CMD
        UPDATE auctions SET closedate="$closedate" 
        WHERE auctionid=$auctionid;
SQL_CMD;
     debug($sqlCmd);
     $qry=uty_mysql_query($sqlCmd);
     if (!$qry){
       print("Failed to set closedate:".$closedate);
       return;
     } 
   }
  //(1) re-generate the place bid page
  //retrieve the auction info
  $sqlCmd=<<<SQL_CMD
     SELECT * FROM Auctions 
     WHERE auctionid=$auctionid;
SQL_CMD;
  debug($sqlCmd);
  $qry=uty_mysql_query($sqlCmd);
  if (!$qry){
    //should not have this case
    print("Invalid auction id:".$auctionid);
    return;
  }
  $row=uty_mysql_fetch_array($qry);
  if (!$row){
    //should not have this case
    print("Failed to retrieve Auction info:".$auctionid);
    return;
  }
  $vin=$row['vin'];
  $sellerid=$row['sellerid'];
  $reserveprice=$row['reserveprice'];
  debug("sellerid:".$sellerid);
  $enddate=$row['enddate'];
  $db_buynowprice=$row['buynowprice'];
  //retrieve vehicle info
  $sqlCmd=<<<SQL_CMD
    SELECT * FROM vehicles 
    WHERE vin="$vin";
SQL_CMD;
  debug($sqlCmd);
  $qry=uty_mysql_query($sqlCmd);
  if (!$qry){
    //should not have this case
    print("Invalid vin:".$vin);
    return;
  }
  $row=uty_mysql_fetch_array($qry);
  if (!$row){
    //should not have this case
    print("Failed to retrieve vehicle info:".$vin);
    return;
  }
  $make=$row['make'];
  $model=$row['model'];
  $year=$row['year'];
  $mileage=$row['mileage'];
  $description=$row['description'];
  $image=$row['image'];
  //retrieve displayfloor info
  $sqlCmd=<<<SQL_CMD
    SELECT * FROM DisplayFloor  
    WHERE vin="$vin";
SQL_CMD;
  debug($sqlCmd);
  $qry=uty_mysql_query($sqlCmd);
  if (!$qry){
    //should not have this case
    print("Invalid vin:".$vin);
    return;
  }
  $row=uty_mysql_fetch_array($qry);
  if (!$row){
    //should not have this case
    print("Failed to retrieve vehicle displayfloor info:".$vin);
    return;
  }
  $youtubeid=$row['youtubeid'];
  $certifiedby=$row['certifiedby'];

  //retrieve bids info
  $sqlCmd=<<<SQL_CMD
    SELECT max(amount) as currentBid, count(bidid) as totalBids
    FROM bids
    WHERE auctionid=$auctionid;
SQL_CMD;
  debug($sqlCmd);
  $qry=uty_mysql_query($sqlCmd);
  $totalBids=0;
  $currentBid=0;
  if ($qry){  
    $row=uty_mysql_fetch_array($qry);
    if ($row){
      $totalBids=$row['totalBids'];
      if (isset($row['currentBid'])){
        $currentBid=$row['currentBid'];
      }
    }
  }
  //retrieve seller info
  //try registered users first
  $sellerName=getUsernameById($sellerid);
  //retrieve sellerid review info
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
  $ratingStars='';
  for ($i=0; $i<$avgRating; $i++){
    $ratingStars = $ratingStars.'&#9733;';
  }

  //now begin to construct html
  $htmlString=<<<HTML_TEXT
  <table width="80%" border="1" align="center">
  <tbody>
    <tr>
      <!-- vehicle image -->
      <td width="28%" align="center"><img src="../images/$image" width="200px"></img><br/> <br/>
          <button onClick="javascript:displayFloor('$youtubeid')">Displayfloor</button> 
          <button onClick="javascript:certifiedMechanic('$certifiedby')">Certified By</button>
      </td>
      <td>
        <!-- vehicle info -->
        <font size="5"><b>$year $make $model</b></font><br/>
        Transmission: Automatic<br/>
        Mileage: $mileage miles<br/>
        Buy Now Price: <font color="#AF171A">$$db_buynowprice </font> 
HTML_TEXT;

       $subHtml="";
       if ($buynowprice>0){
         //user clicked buy now button case
         $subHtml=<<<HTML_TEXT
          <button onClick="javascript:buynow($auctionid, $userid, $sellerid, $db_buynowprice)" disabled>Buy Now</button><br/>
          Current Bid: <font color="#360BE5">$$currentBid</font> [ $totalBids bids ]</br>
          End date: $enddate<br/>
          <input id="bidAmountId" type="text" style="color: #116EC4" disabled /> 
          <button onClick="javascript:submitBid($auctionid, $userid, $sellerid, $reserveprice, $currentBid, getValueById('bidAmountId'))" disabled>Submit</button><br/>
          <font color="#FF0000"><span id="bidErrorId"></span></font>
          <span id="bidConfirmId">
HTML_TEXT;
          $htmlString=$htmlString.$subHtml;
        } else {
          $subHtml=<<<HTML_TEXT
          <button onClick="javascript:buynow($auctionid, $userid, $sellerid, $db_buynowprice)">Buy Now</button><br/>
          Current Bid: <font color="#360BE5">$$currentBid</font> [ $totalBids bids ]</br>
          End date: $enddate<br/>
          <input id="bidAmountId" type="text" style="color: #116EC4" onFocus="javascript:clearById('bidErrorId')"/> 
          <button onClick="javascript:submitBid($auctionid, $userid, $sellerid, $reserveprice, $currentBid, getValueById('bidAmountId'))">Submit</button><br/>
          <font color="#FF0000"><span id="bidErrorId"></span></font>
          <span id="bidConfirmId">
HTML_TEXT;
          $htmlString=$htmlString.$subHtml;
        }
    
        if ($bidamount>0){
          $t=time() % 10000;
          $confirmation=''.$t;
          $email=getUserEmailById($userid);
          $subHtml=<<<HTML_TEXT
            <b>Dear $biddername:</b><br/>
            You have succeffuly submit your bid amount $$bidamount<br/>
            at $timestamp.<br/>
            <b>Your Confirmation number:</b> <font color="#E105EF">$confirmation-$auctionid-$userid</font><br/>
            A confirmation email is sent to <font color="#0000FF">$email</font>.
HTML_TEXT;
          $htmlString=$htmlString.$subHtml;
        } 
        if ($buynowprice>0){
          $confirmation=rand(10000, 100000);
          $email=getUserEmailById($userid);
          $subHtml=<<<HTML_TEXT
            <b>Dear $biddername:</b><br/>
            You have succeffuly offerred Buy Now Price $$buynowprice.<br/>
            This auction is closed at $timestamp.<br/>
            <b>Your Confirmation number:</b> <font color="#E105EF">$confirmation-$auctionid</font><br/>
            A confirmation email is sent to <font color="#0000FF">$email</font>.
HTML_TEXT;
          $htmlString=$htmlString.$subHtml;        
        }
    $seller_email=getUserEmailById($sellerid);
    $user_email=getUserEmailById($userid);
    $seller_name=getUserNameById($sellerid);
    $user_name=getUserNameById($userid);
    $subHtml=<<<HTML_TEXT
        </span>
      </td>
      <!-- seller info -->
      <td width="30%" bgcolor="#B2B2B2">
        <font size="5"><b>Seller Information</b></font><br/>
        <font color="#691BCB">$sellerName</font> (reviews: <span id="totalReviews" style="color: #0000FF">$totalReviews</span> rating: <span id="ratingStarsId" style="color: #EB1147">$ratingStars</span>)<br/>
        <br/>
        <a onClick="javascript:viewAllReviews($sellerid)" href="#">View all reviews</a><br/>
        <a onClick="javascript:contactSeller('$seller_email','$user_email')" href="#">Contact this seller</a><br/>
        <a onClick="javascript:reviewSeller($sellerid,'$seller_name',$userid,'$user_name',$auctionid)" href="#">Review this seller</a><br/>
      </td>
    </tr>
  </tbody>
  </table>
  
  <div align="center">
    <br/>
    <div id="placeBidDynamicPart" align="left" style="width: 80%">
HTML_TEXT;
   $htmlString=$htmlString.$subHtml;
   
//now display first 4 reviews if possible
  $sqlCmd=<<<SQL_CMD
    SELECT * FROM Reviews 
    WHERE sellerid=$sellerid;
SQL_CMD;
  $qry=uty_mysql_query($sqlCmd);
  $reviews=0;
  if ($qry){
    while ($reviews<3){
       $reviews=$reviews+1;
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
  if ($reviews<1){
    $subHtml=<<<HTML_TEXT
      No review for this seller yet.
HTML_TEXT;
  }
       
  $subHtml =<<<HTML_TEXT
    </div>
  </div>
HTML_TEXT;
  $htmlString=$htmlString.$subHtml;
  print($htmlString);
    print("<div id=\"footerId\" style=\"position: absolute; bottom: 0; width: 100%\">");
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
