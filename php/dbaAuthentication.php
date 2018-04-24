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
//no parameter is needed for sign in. just display sign in page
$todayDate=date("Y-m-d", time());
$signInPage="";
$signInPage=<<<FIXED_HTML
<div align="center" style="margin-top: 1%">
 <div align="left" style="width: 50%">
  <div id="signInErrorId" style="color: #FF0000"> </div>
  <br />
  <div style="color: #000000">Close all auctions with end-date on or earlier than this date:<br /><input id="closedateId" type="text" style="color: #116EC4" value="$todayDate" /><font color="#ADADAD">yyyy-mm-dd</font></div><br />
  <div style="color: #000000">This feature is developped for eAuction database administration department only. Special Authentication is required to use this feature.</div> <br />
  <div style="color: #000000">DBA Password:<br/><input id="passwordId" type="text" style="color: #116EC4"  /><font color="#ADADAD">Hint:william</font></div>
  <br /><center>
  <button type="submit" style="background-color: #EB8607; border-width: thin"  onClick="javascript:validateDbaPassword(getValueById('passwordId'))">Termindate Auctions</button>&#160;&#160;&#160;&#160;
  <button type="submit" style="background-color: #B9B9AE; border-width: thin"  onClick="javascript:home()">Cancel</button>
  <br /><span id="errorId" style="color: #FF0000"></span>
  </center>
</div>
</div>
FIXED_HTML;

print($signInPage);

//close the connection to MySQL database
close_connection_to_mysql();

?>
