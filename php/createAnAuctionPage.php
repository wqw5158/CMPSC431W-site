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
$userid=$parms['userid'];
//get parameters passed to this php file
//no parameter is needed for sign in. just display sign in page
$pageHtml="";
$pageHtml=<<<FIXED_HTML
<form action="php/createAnAuction.php" method="post" enctype="multipart/form-data" target="upload_target" onSubmit="javascript:startUpload()">
<table width="80%" border="0" align="center">
<tbody>
  <tr> <td align="right">VIN:</td> 
       <td><input id="vinId" name="vinId" type="text" /> 
           Year: <input id="yearId" name="yearId" type="text" size="4" />
           Make: <input id="makeId" name="makeId" type="text" size="10" />
           Model: <input id="modelId" name="modelId" type="text" size="10" />
      </td> 
  </tr>
  <tr> <td align="right">Transmission:</td> 
       <td><label><input type="radio" name="transmission" value="M" id="transmission_A" checked/>Automatic</label>&#160;&#160;
           <label><input type="radio" name="transmission" value="A" id="transmission_M" />Manual</label>
       </td> 
  </tr>
  <tr> <td align="right">Category:</td> 
       <td><label><input type="radio" name="category" value="SUV" id="transmission_SUV" checked/>SUV</label>&#160;&#160;
           <label><input type="radio" name="category" value="Sport" id="transmission_Sport" />Sport</label>&#160;&#160;
           <label><input type="radio" name="category" value="Mini Van" id="transmission_MiniVan" />Mini Van</label>&#160;&#160;
           <label><input type="radio" name="category" value="Truck" id="transmission_Truck" />Truck</label>&#160;&#160;
           <label><input type="radio" name="category" value="Coupes" id="transmission_Coupes" />Coupes</label>&#160;&#160;
           <label><input type="radio" name="category" value="Sedan" id="transmission_Sedan" />Sedan</label>
       </td> 
  </tr>
  <tr> <td align="right">Mileage:</td> <td><input id="mileageId" name="mileageId" type="number" /></td> </tr>
  <tr> <td align="right">Reserved Price:</td> <td><input id="reservepriceId" name="reservepriceId" type="number" /></td> </tr>
  <tr> <td align="right">Buy Now Price:</td><td><input id="buynowpriceId" name="buynowpriceId" type="number" /></td></tr>
  <tr> <td align="right">Auction End Date:</td><td><input id="enddateId" name="enddateId" type="date" /><font color="#B6B6F6">yyyy-mm-dd</font></td></tr>
  <tr> <td align="right">Description:</td><td><textarea style="width: 70%" id="descriptionId" name="descriptionId" ></textarea></td></tr>
  <tr> <td align="right">Vechicle Image File:</td>
      <td>
        <input type="file" name="fileToUpload" id="fileToUpload" />
      </td>
  </tr>
  <tr> <td align="right">Vehicle YouTube Video ID:</td><td><input id="videoId" name="videoId" type="text" /></td></tr>

  <tr>
      <td></td>
      <td>
      <input id="submitId" type="submit" value="Create Auction" name="submit" style="background-color: #EB8607; width: 20%; border-width: thin" />
      <span id="loadProgressId"> </span>
      </td>
  </tr>
</tbody>
</table>
<input id="userId" name="userId" type="hidden" value="$userid"/>
</form>
<div align="center">
    <div style="width: 55%">
       <iframe id="upload_target" name="upload_target" src="#" style="width:100%;height:50;border:0px solid #fff;"></iframe>
    </div>
</div>
FIXED_HTML;

print($pageHtml);

//close the connection to MySQL database
close_connection_to_mysql();

?>
