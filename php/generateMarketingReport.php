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

marketingReport();

//close the connection to MySQL database
close_connection_to_mysql();

function marketingReport(){
    $sqlCmd=<<<SQL_CMD
      SELECT u.userid, u.email, u.address, u.phonenumber, r.name, r.age, r.gender, r.income
      FROM Users as u, RegisteredUsers as r
      WHERE u.userid=r.userid;
SQL_CMD;
    debug($sqlCmd);
    $qry=uty_mysql_query($sqlCmd);
    if (!$qry){
       print("Failed to query Users table:"+uty_mysql_error());
       return;
    }
    //now comstruct the html to display found auction in table view
  print("<table align=\"center\" border=\"1\" width=\"99%\" style=\"color: #000000\">");
  print('<tbody>');
  print("<tr style=\"background-color: #9898DB\">");
  print("  <th scope=\"col\">Name</th>");
  print("  <th scope=\"col\">Address</th>");
  print("  <th scope=\"col\">email</th>");
  print("  <th scope=\"col\">Phone</th>");
  print("  <th scope=\"col\">Age</th>");
  print("  <th scope=\"col\">Gender</th>");
  print("  <th scope=\"col\">Annual Income</th>");
  print("  <th scope=\"col\">Total Bids</th>");
  print('</tr>');
  $i=0;
  while(true){
    $row=uty_mysql_fetch_array($qry);
    if (!$row){
       break;
    }
    $userid=$row['userid'];
    $email=$row['email'];
    $address=$row['address'];
    $phone=$row['phonenumber']; $name=$row['name']; $age=$row['age']; $gender=$row['gender'];
    $income=$row['income'];
    
    //now to total bids
    $sqlCmd="SELECT count(bidderid) AS bids ".
            "  FROM bids as b ".
            " WHERE b.bidderid=$userid;";
   debug($sqlCmd);
   $qry2 = uty_mysql_query($sqlCmd);
   $bids=0;
   if ($qry2){
     debug("found bids:".$bids);
     $row2=uty_mysql_fetch_array($qry2);
     if ($row2){
       if (isset($row2['bids'])){
          $bids=$row2['bids'];
       }
     }
   }
   
   $i=$i+1;
    print("<tr>");    
    print("  <td>$name</td>");
    print("  <td>$address</td>");
    print("  <td>$email</td>");
    print("  <td>$phone</td>");
    print("  <td>$age</td>");
    print("  <td>$gender</td>");
    print("  <td>$income</td>");
    print("  <td>$bids</td>");
    print('</tr>');
  }
  print('</tbody>');
  print('</table>');

  if ($i<1){
    print("<div style=\"align-content: center; width: 100%\"><p><br /><font color=\"#AEAEAE\">");
    print("<center>There is no registered users in the system.</center></font></p></div>");
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
