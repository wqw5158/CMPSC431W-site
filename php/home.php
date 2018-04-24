<?php
 // The following code is the same for every php file
 //---- always include the following code in every php file --------
header('Content-type: text/xml');
header('Pragma: public');
header('Cache-control: private');
header('Expires: -1');
require_once('./php_utilities.php'); //open mysql connection
open_connection_to_mysql();
//------------------- end of fix part -------------------------------

$homePage=buildHomePage();
print($homePage);

//close the connection to MySQL database
close_connection_to_mysql();

function buildHomePage(){
  $htmlString=<<<FIXED_HTML
  <div class="container">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div id="carousel1" class="carousel slide">
       <div class="carousel-inner">
        <div class="item"> <img class="img-responsive" src="images/Market.jpg" alt="thumb" /></div>
        <div class="item active"> <img class="img-responsive" src="images/Tesla_Model_S.jpg" alt="thumb" /></div>
        <div class="item"> <img class="img-responsive" src="images/bmw-2.jpg" alt="thumb" /></div>
      </div>
      <a class="left carousel-control" href="#carousel1" data-slide="prev"><span class="icon-prev"></span></a>
      <a id="nextId" class="right carousel-control" href="#carousel1" data-slide="next"><span id="nextIconId" class="icon-next"></span></a>
     </div>
   </div>
 </div>
</div>
FIXED_HTML;
$sqlCmd=<<<SQL_CMD
SELECT v.vin, v.image, v.make, v.model, v.year, v.mileage, v.description,
       a.auctionid, a.buynowprice, a.enddate, v.category
FROM vehicles AS v, auctions AS a
WHERE v.vin=a.vin AND a.closedate IS NULL;
SQL_CMD;
$qry=uty_mysql_query($sqlCmd);
if (!$qry){
print("Failed to query vehicles and Auctions table.");
return htmlString;
}

$row=uty_mysql_fetch_array($qry);
if (!$row){
  return htmlString;
}
$vin=$row['vin'];$auctionid=$row['auctionid'];$enddate=$row['enddate'];
$image=$row['image'];$year=$row['year'];$make=$row['make'];$model=$row['model'];$mileage=$row['mileage'];
$buy=$row['buynowprice'];
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

$subString=<<<FIXED_HTML
<h2 class="line-title" style="color: #060606"><span><img width="180" height="51" src="images/hot.gif">Auctions</span><hr align="left" /></h2>
<div class="container"><!-- 0 -->

<div class="row text-center"> <!-- 1 -->
 
  <div class="col-sm-4 col-md-4 col-lg-4 col-xs-6">
    <div class="thumbnail"> <img height="129px" src="images/$image" alt="Thumbnail Image 1" class="img-responsive" />
      <div class="caption" style="color: #000000">
        <h3>$year $make $model</h3>
    <p>Transmission: Manual<br/>Mileage: $mileage miles<br/>Buy Now Price:<font color="#FF0000">$$buy</font>
             <br/>Current Bid: <font color="#0000FF">$$maxbid</font><br/>End date: $enddate</p>
        <p><a href="#" onClick="javascript:placeBid($auctionid)" class="btn btn-primary" role="button">View Details</a></p>
      </div>
    </div>
  </div>
FIXED_HTML;
$htmlString=$htmlString.$subString;

$row=uty_mysql_fetch_array($qry);
if ($row){ //1
$vin=$row['vin'];$auctionid=$row['auctionid'];$enddate=$row['enddate'];
$image=$row['image'];$year=$row['year'];$make=$row['make'];$model=$row['model'];$mileage=$row['mileage'];
$buy=$row['buynowprice'];
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
$subString=<<<FIXED_HTML
  <div class="col-sm-4 col-md-4 col-lg-4 col-xs-6">
    <div class="thumbnail"> <img height="129px" src="images/$image" alt="Thumbnail Image 1" class="img-responsive" />
      <div class="caption" style="color: #000000">
        <h3>$year $make $model</h3>
        <p>Transmission: Automatic<br/>Mileage: $mileage miles<br/>Buy Now Price: <font color="#FF0000">$$buy</font>
        <br/>Current Bid: <font color="#0000FF">$$maxbid</font><br/>End date: $enddate</p>
        <p><a href="#" onClick="javascript:placeBid($auctionid)" class="btn btn-primary" role="button">View Details</a> </p>
      </div>
    </div>
  </div>
FIXED_HTML;
  $htmlString=$htmlString.$subString;

$row=uty_mysql_fetch_array($qry);
if ($row){ //2
$vin=$row['vin'];$auctionid=$row['auctionid'];$enddate=$row['enddate'];
$image=$row['image'];$year=$row['year'];$make=$row['make'];$model=$row['model'];$mileage=$row['mileage'];
$buy=$row['buynowprice'];
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
$subString=<<<FIXED_HTML
  <div class="col-sm-4 col-md-4 col-lg-4 col-xs-6">
    <div class="thumbnail"> <img height="129px" src="images/$image" alt="Thumbnail Image 1" class="img-responsive" />
      <div class="caption" style="color: #000000">
        <h3>$year $make $model</h3>
        <p>Transmission: Automatic<br/>Mileage: $mileage<br/>Buy Now Price: <font color="#FF0000">$$buy</font>
        <br/>Current Bid: <font color="#0000FF">$$maxbid</font><br/>End date: $enddate</p>
        <p><a href="#" onClick="javascript:placeBid($auctionid)" class="btn btn-primary" role="button">View Details</a> </p>
      </div>
    </div>
  </div>
FIXED_HTML;
$htmlString=$htmlString.$subString;
}//2
}//1

$subString=<<<FIXED_HTML
</div><!-- 1 -->
FIXED_HTML;
$htmlString=$htmlString.$subString;

$row=uty_mysql_fetch_array($qry);
if ($row){ //3
$vin=$row['vin'];$auctionid=$row['auctionid'];$enddate=$row['enddate'];
$image=$row['image'];$year=$row['year'];$make=$row['make'];$model=$row['model'];$mileage=$row['mileage'];
$buy=$row['buynowprice'];
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
$subString=<<<FIXED_HTML
<div class="row text-center hidden-xs"><!-- 2 -->
 
  <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
    <div class="thumbnail"> <img height="129px" src="images/$image" alt="Thumbnail Image 1" class="img-responsive" />
      <div class="caption" style="color: #000000">
        <h3>$year $make $model</h3>
        <p>Transmission: Automatic<br/>Mileage: $mileage<br/>Buy Now Price: <font color="#FF0000">$$buy</font>
        <br/>Current Bid: <font color="#0000FF">$$maxbid</font><br/>End date: $enddate</p>
        <p><a href="#" onClick="javascript:placeBid($auctionid)" class="btn btn-primary" role="button">View Details</a> </p>
      </div>
    </div>
  </div>
FIXED_HTML;
$htmlString=$htmlString.$subString;

$row=uty_mysql_fetch_array($qry);
if ($row){ //4
$vin=$row['vin'];$auctionid=$row['auctionid'];$enddate=$row['enddate'];
$image=$row['image'];$year=$row['year'];$make=$row['make'];$model=$row['model'];$mileage=$row['mileage'];
$buy=$row['buynowprice'];
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
$subString=<<<FIXED_HTML
  <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
    <div class="thumbnail"> <img height="129px" src="images/$image" alt="Thumbnail Image 1" class="img-responsive" />
      <div class="caption" style="color: #000000">
        <h3>$year $make $model</h3>
        <p>Transmission: Automatic<br/>Mileage: $mileage miles<br/>Buy Now Price: <font color="#FF0000">$$buy</font>
        <br/>Current Bid: <font color="#0000FF">$$maxbid</font><br/>End date: $enddate</p>
        <p><a href="#" onClick="javascript:placeBid($auctionid)" class="btn btn-primary" role="button">View Details</a> </p>
      </div>
    </div>
  </div>
FIXED_HTML;
$htmlString=$htmlString.$subString;

$row=uty_mysql_fetch_array($qry);
if ($row){ //5
$vin=$row['vin'];$auctionid=$row['auctionid'];$enddate=$row['enddate'];
$image=$row['image'];$year=$row['year'];$make=$row['make'];$model=$row['model'];$mileage=$row['mileage'];
$buy=$row['buynowprice'];
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
$subString=<<<FIXED_HTML
  <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
    <div class="thumbnail"> <img height="129px" src="images/$image" alt="Thumbnail Image 1" class="img-responsive" />
      <div class="caption" style="color: #000000">
        <h3>$year $make $model</h3>
        <p>Transmission: Manual<br/>Mileage: $mileage miles<br/>Buy Now Price: <font color="#FF0000">$$buy</font>
        <br/>Current Bid: <font color="#0000FF">$$maxbid</font><br/>End date: $enddate</p>
        <p><a href="#" onClick="javascript:placeBid($auctionid)" class="btn btn-primary" role="button">View Details</a> </p>
      </div>
    </div>
  </div>
FIXED_HTML;
$htmlString=$htmlString.$subString;
}//5
}//4
$subString=<<<FIXED_HTML
</div><!-- 2 -->
FIXED_HTML;
$htmlString=$htmlString.$subString;
}//3

$subString=<<<FIXED_HTML
</div><!-- 0 -->
<hr style="border-color: #9E9E9E">
<footer class="text-center">
<div class="container">
  <div class="row">
    <div class="col-xs-12" style="color: #AEAEAE">
      <p>Copyright © Generic Team Name( William Wang, YuChen Zeng, Robert Beck, Michael Berezanich, JingRui Duan). All rights reserved.</p>
    </div>
  </div>
</div>
</footer>
FIXED_HTML;
$htmlString=$htmlString.$subString;

return $htmlString;

}

?>
