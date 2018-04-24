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

$signInPage=<<<FIXED_HTML
<table width="80%" border="0" align="center">
<tbody>
  <tr> <td></td><td>Note: Field with <font color="#FF0000">*</font> is required.</td></tr>
  <tr> <td align="right">email:<font color="#FF0000">*</font></td> <td><input id="emailId" type="text" /></td> </tr>
  <tr> <td align="right">Password:<font color="#FF0000">*</font></td> <td><input id="passwordId" type="text" /></td> </tr>
  <tr> <td align="right">Name:<font color="#FF0000">*</font></td> <td><input id="nameId" type="text" /></td> </tr>
  <tr> <td align="right">Phone:<font color="#FF0000">*</font></td> <td><input id="phoneId" type="text" /></td> </tr>
  <tr> <td align="right">Annual Income:<font color="#FF0000">*</font></td><td><input id="incomeId" type="number" /></td></tr>
  <tr> <td align="right">Gender:<font color="#FF0000">*</font></td>
       <td> <label><input type="radio" name="gender" value="F" id="gender_F" /> F</label>&#160;&#160;&#160;&#160;
            <label><input type="radio" name="gender" value="M" id="gender_M" /> M</label>&#160;&#160;&#160;&#160;
            Age:<font color="#FF0000">*</font> <input id="ageId" type="number" />
       </td>
  </tr>
  <tr> <td align="right">Bank account:<font color="#FF0000">*</font></td> 
       <td> <input id="routingNumberId" type="text" placeholder="Bank routing number" size="20" />
            <input id="accountNumberId" type="text" placeholder="account number" size="20" />
       </td> 
  </tr>
  <tr> <td align="right">Address:</td> 
       <td> <input id="streetId" type="text" placeholder="street" size="50" />
            <input id="cityId" type="text" placeholder="city" />
            <input id="stateId" type="text" placeholder="state" size="2" />
            <input id="zipId" type="text" placeholder="zip" size="5" />
       </td> 
  </tr>
  <tr>
      <td></td>
      <td><button id="registerButtonId" style="background-color: #EB8607; width: 20%; border-width: thin" onClick="javascript:registerUser()">Register</button></td>
  </tr>
  <tr>
      <td>&#160;</td><td><div id="registerErrorId" style="color: #FF0000">&#160;</div></td>
  </tr>
  <tr>
      <td>&#160;</td><td><div id="confirmRegisterUser">&#160;</div></td>
  </tr>
</tbody>
</table>
FIXED_HTML;

print($signInPage);

//close the connection to MySQL database
close_connection_to_mysql();

?>
