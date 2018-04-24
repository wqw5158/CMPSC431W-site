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
$signInPage="";
$signInPage=<<<FIXED_HTML
<div align="center" style="margin-top: 1%">
  <div id="signInErrorId" style="color: #FF0000"> </div>
  <br />
  <div style="color: #000000">&#160;&#160;&#160;&#160;&#160;&#160;&#160;email: <input id="usernameId" type="text" style="color: #116EC4" onFocus="javascript:cleanElement('signInErrorId')" value="wlee@psu.edu" /></div>
  <br />
  <div style="color: #000000">Password: <input id="passwordId" type="text" style="color: #116EC4" onFocus="javascript:cleanElement('signInErrorId')" value="wlee" /></div>
  <br />&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;
  <button type="submit" style="background-color: #EB8607; border-width: thin"  onClick="javascript:validateUsernamePassword(getValueById('usernameId'), getValueById('passwordId'))">Sign in</button>&#160;&#160;&#160;&#160;
  <button type="submit" style="background-color: #B9B9AE; border-width: thin"  onClick="javascript:home()">Cancel</button>
  <br/>&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;New to eAuction? <a href="#" onClick="javascript:registerNewUserPage()">Register a new user</a>
</div>
FIXED_HTML;

print($signInPage);

//close the connection to MySQL database
close_connection_to_mysql();

?>
