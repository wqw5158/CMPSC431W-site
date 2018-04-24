<?php
 // The following code is the same for every php file
 //---- always include the following code in every php file --------
 //header('Content-type: text/xml');
 //header('Pragma: public');
 //header('Cache-control: private');
 //header('Expires: -1');
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
 //print('<!--');
 //print_r($_GET);
 //print_r($_POST);
 //print_r($_FILES);
 //print("  -->");
$htmlString=<<<HTML_TEXT
<!DOCTYPE html>
<html lang="en">
<head>
HTML_TEXT;
print($htmlString);
debug_array($_GET);
debug_array($_POST);
debug_array($_FILES);
global $errmsg;
$result=createAuction();

$htmlString=<<<HTML_TEXT
</head>
<script language="javascript" type="text/javascript">parent.updateCreateAuctionState('$result');</script>   
<body onload="javascript:parent.updateCreateAuctionState('$result');">
<div align="center">
HTML_TEXT;
print($htmlString);

if ($result=="0"){
    $htmlString=<<<HTML_TEXT
      Your auction is processed successfully.
HTML_TEXT;
} else {
   $htmlString=<<<HTML_TEXT
      <font color="#FF0000">$errmsg</font>
HTML_TEXT;
}
print($htmlString);
$htmlString=<<<HTML_TEXT
</div>
</body>
</html>
HTML_TEXT;
print($htmlString);
 
//close the connection to MySQL database
close_connection_to_mysql();
function validateDate($date) {
    $format = 'Y-m-d';
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
function createAuction(){
    global $parms;
    global $errmsg;
    $vin=$parms['vinId'];
    $year=$parms['yearId'];
    $make=$parms['makeId'];
    $model=$parms['modelId'];
    $transmission=$parms['transmission'];
    $category=$parms['category'];
    $mileage=$parms['mileageId'];
    $reserveprice=$parms['reservepriceId'];
    $buynowprice=$parms['buynowpriceId'];
    $enddate=$parms['enddateId'];
    $description=$parms['descriptionId'];
    $video=$parms['videoId'];
    $userid=$parms['userId'];
    if (strlen($vin)<1) { $errmsg='VIN field can not be blank.'; return '1';}
    if (strlen($year)<1) { $errmsg= 'Year field can not be blank.'; return '2';}
    if (!is_numeric($year)){$errmsg="Year field must be a numeric value."; return '2';}
    if (strlen($make)<1) { $errmsg= 'Make field can not be blank.'; return '3';}
    if (strlen($model)<1) { $errmsg= 'Model field can not be blank.'; return '4';}
    if (strlen($transmission)<1) { $errmsg= 'Please select transmission type.'; return '5';}
    if (strlen($category)<1) { $errmsg= 'Please select category.'; return '6';}
    if (strlen($mileage)<1) { $errmsg= 'Mileage field can not be blank.'; return '7';}
    if (!is_numeric($mileage)){ $errmsg="Mileage field must be a numeric value."; return '7';}
    if (strlen($reserveprice)<1) { $errmsg= 'Please specify reserved price.'; return '8';}
    if (!is_numeric($reserveprice)){ $errmsg="Reserved Price field must be a numeric value."; return '8';}
    if (strlen($buynowprice)<1) { $errmsg= 'Please specify Buy Now Price.'; return '9';}
    if (!is_numeric($buynowprice)){ $errmsg="Buy Now Price field must be a numeric value."; return '8';}
    if (strlen($enddate)<1) { $errmsg= 'Please specify auction end date.'; return '10';}
    if (!validateDate($enddate)) {$errmsg="End date must be in format yyyy-mm-dd."; return '10';}
    $todayDate=date("Y-m-d", time());
    $todayTime=strtotime($todayDate);
    $endTime=strtotime($enddate);
    if ($endTime<$todayTime){
        $errmsg="End Date is already passed."; return '10';
    }
    if (strlen($description)<1) { $errmsg= 'Please give your vehicle some description.'; return '11';}
    if (strlen($video)<1) { $errmsg= 'Please provide the YouTube video ID of your vehicle.';return '12';}
    $sqlCmd=<<<SQL_CMD
      SELECT * FROM Vehicles WHERE vin="$vin";
SQL_CMD;
    debug($sqlCmd);
    $qry=uty_mysql_query($sqlCmd);
    if ($qry){
        $row=uty_mysql_fetch_array($qry);
        if ($row){
            { $errmsg= "This vehicle is already on Auction."; return '13'; }
        }
    }    
    $target_dir = "../images/";
    if (strlen($_FILES["fileToUpload"]["name"])<1){
        $errmsg="Please select an image file for your vehicle."; return '19';
    }
    $image=$vin.basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $image;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            //echo "File is not an image.";
            //$uploadOk = 0;
            $errmsg= "The uploaded file is not a image file."; return '14';
        }
    }
    
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        //echo "Sorry, your file is too large.";
        $errmsg= "The vehicle file size can not be larger than 500KB"; return '15';
    }
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
            //echo "Sorry, there was an error uploading your file.";
            $errmsg= 'Failed to upload your vehicle image file.'; return '16';
       }
    //begin to insert into database
    $sqlCmd=<<<SQL_CMD
         INSERT INTO Vehicles(vin,ownerid,category,make,model,year,mileage,description,image)
         VALUES ('$vin',$userid,'$category','$make','$model', $year,$mileage,'$description','$image');
SQL_CMD;
    debug($sqlCmd);
    $qry=uty_mysql_query($sqlCmd);
    if (!$qry){
        $errmsg= "Failed to insert into vehicle table:".uty_mysql_error(); return '17';
    }
    $startdate=date("Y-m-d", time());
    $sqlCmd=<<<SQL_CMD
         INSERT INTO Auctions(vin,sellerid,startdate,enddate,reserveprice,buynowprice)
         VALUES ('$vin',$userid,'$startdate','$enddate',$reserveprice,$buynowprice);
SQL_CMD;
    debug($sqlCmd);
    $qry=uty_mysql_query($sqlCmd);
    if (!$qry){
        $errmsg= "Failed to insert into Auction table.".uty_mysql_error(); return '18';
    }
    $sqlCmd=<<<SQL_CMD
         INSERT INTO DisplayFloor(vin, youtubeid, certifiedby)
         VALUES ('$vin','$video','!1m18!1m12!1m3!1d96654.23020064064!2d-77.92658512459158!3d40.79622078739148!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x6ffe2e9d730aac4b!2sKarch+Auto!5e0!3m2!1sen!2sus!4v1524305160690');
SQL_CMD;
    debug($sqlCmd);
    $qry=uty_mysql_query($sqlCmd);
    if (!$qry){
        $errmsg= "Failed to insert into Displayfloor table.".uty_mysql_error(); return '19';
    }

    $errmsg= ""; return "0";
}
?>
