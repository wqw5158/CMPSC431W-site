function browseByCategory(type, data){
    /*
      url points to the php filename with ? at end
         example: url="php/home.php?";
      parameter will be in the format: name1=value1&name2=value2....
         example: parameter="type=year&data=Used";
         see browseByCategory(type, data) for how to pass paramter to php file

      getServerHttpResponse(url, parameter, callBackFunction) will send url+parameter to server.
      after server executed the php code, the output of the php code will be used to
      call the callBackFunction just like updateHtmlDynamicPart(output_of_php);
      we cal always use updateHtmlDynamicPart as the 3rd aurgument since we always
      update the "DynamicPart" div's html
    */

    //type=year, make, category
    //data for year: New, Used, Antique
    //     for make: BMW, HONDA, GMC, ....
    //     for category: SUV, Mini Van, ....
	var url="php/SelectByCategory.php?";
    var parameter="type="+type+"&"+"data="+data;

	getServerHttpResponse(url, parameter, updateHtmlDynamicPart);
}

function updateHtmlDynamicPart(httpText){
    var dynamicPart=document.getElementById("DynamicPart");
    //php function outputs are saved in httpText. Use httpText
    //to put into <div id="DynamicPart"> </div> in the index.html file
    dynamicPart.innerHTML=httpText;//composedHtml;
}
var initialized=0;
function initHome(){
	if (initialized==0){
		initialized=1;
		home();
	}
}
function home(){
    var url="php/home.php?";
    var parameter=""; //no parameter is needed
    getServerHttpResponse(url, parameter, updateHtmlDynamicPart);
}
//-------------------------------- sign in or register new user ------------------------------
var loggedInUserId=0;
function signIn(inOrOff){
  if (inOrOff==="Sign out") {
     var htmlElement=document.getElementById("signInId");
     htmlElement.innerHTML="Sign in";
     htmlElement=document.getElementById("userNameId");
     htmlElement.innerHTML="";
     loggedInUserId=0;
     home();
  } else {
    var url="php/signIn.php?"
    var parameter=""; //no parameter is needed for sign in, just display sign in page
    getServerHttpResponse(url, parameter, updateHtmlDynamicPart);
  }
}
var loggedInUsername="";
function validateUsernamePassword(username, password){
  var url="php/validateUsernamePassword.php?";
  var parameter="username="+username+"&"+"password="+password;
  getServerHttpResponse(url, parameter, updateSignInState);
}
function updateSignInState(httpText){
  //if username and password are valid, httpText is userid:name, otherwise, 0:none
  var idAndName=httpText.split(":");
  loggedInUserId=idAndName[0];
  loggedInUsername=idAndName[1];
  if (loggedInUserId=="0"){
     var htmlElement=document.getElementById("signInErrorId");
     htmlElement.innerHTML="Failed to sign in: invalid email or password.";
  } else {
     var htmlElement=document.getElementById("userNameId");
     htmlElement.innerHTML="Hi, "+loggedInUsername+" | ";
     htmlElement=document.getElementById("signInId");
     htmlElement.innerHTML="Sign out";
     home();
  }
}
function registerNewUserPage(){
   var url="php/registerNewUserPage.php?";
   var parameter="";
   getServerHttpResponse(url, parameter, updateHtmlDynamicPart);
}
function registerUser(){
  var e=document.getElementById("registerErrorId");
  var email=getValueById("emailId");
  if (email.length<1) {
    e.innerHTML="email field can not be blank."; return;
  }
  if (!isValidEmail(email)){
    e.innerHTML="Invalid email format."; return;
  }
  var password=getValueById("passwordId");
  if (password.length<1){
    e.innerHTML="Password can not be blank."; return;
  }
  var name=getValueById("nameId");
  if (name.length<1){
    e.innerHTML="Name field can not be blank."; return;
  }
  var phonenumber=getValueById("phoneId");
  if (phonenumber.length<1){
    e.innerHTML="Phone number is required."; return;
  }
  var income=getValueById("incomeId");
  if (income.length<1){
    e.innerHTML="Annual Income field can not be blank."; return;
  }
  var gender="";
  if (document.getElementById("gender_F").checked){
    gender="F";
  }
  if (document.getElementById("gender_M").checked){
    gender="M";
  }
  if (gender.length<1){
    e.innerHTML="Please select gender."; return;
  }
  var age = getValueById("ageId");
  if (age.length<1){
    e.innerHTML="Age field can not be blank."; return;
  }
  var routingnumber=getValueById("routingNumberId");
  if (routingnumber.length<1){
    e.innerHTML="Bank routing number can not be blank."; return;
  }
  var accountnumber=getValueById("accountNumberId");
  if (accountnumber.length<1){
    e.innerHTML="Bank account number can not be blank."; return;
  }
  e.innerHTML="";
  
  var street=getValueById("streetId");
  var city=getValueById("cityId");
  var state=getValueById("stateId");
  var zip=getValueById("zipId");

  var url="php/registerUser.php?"
  var parameters="email="+email+"&password="+password+"&name="+name+
      "&routingnumber="+routingnumber+"&accountnumber="+accountnumber+
      "&income="+income+"&gender="+gender+"&age="+age+
      "&phonenumber="+phonenumber+"&address="+street+","+city+","+state+","+zip;
  getServerHttpResponse(url, parameters, updateRegisterUserState);
}
function updateRegisterUserState(httpText){
    var ret=httpText.split(":");
    if (ret[0]=='0'){
      document.getElementById("registerButtonId").disabled="disabled";
      var c=document.getElementById("confirmRegisterUser");
      c.innerHTML="Registration is successful. Now you can sign in with your new account."
    } else {
      var e=document.getElementById("registerErrorId");
      e.innerHTML=ret[1];
    }
}

function getLoggedInUserId(){
    /* getLoggedInUserId() will return 0 if no user logged in yet
                           otherwise, return currently logged in user id
     */
    return loggedInUserId;
}

function search(text){
  var url="php/search.php?";
  var parameter="text="+text;
  getServerHttpResponse(url, parameter, updateHtmlDynamicPart);
}

function placeBid(auctionId){
  var userId=getLoggedInUserId();
  if (userId<1){
    var htmlString=
        '<div align="center" style="margin-top: 10%">'+
        '    <div id="signInErrorId" style="color: #FF0000">'+
        '       Only registered users can view auctions details and place bids.<br />'+
        '       Please Sign in!'+
        '    </div>'+
        '</div>';
    updateHtmlDynamicPart(htmlString);
    return;
  }

  //we pass 2 pararameters to placeBid.php: auctionId and UserId
  var url="php/placeBid.php?"
  var parameter="auctionid="+auctionId+"&"+"userid="+userId+"&"+"bidamount=0&buynowprice=0";
  getServerHttpResponse(url, parameter, updateHtmlDynamicPart);
}
//----------------------- placeBid functions --------------------------------
function submitBid(auctionid, userid, sellerid, reserveprice, currentbid, bidamount){
   var e=document.getElementById("bidErrorId");
   if (userid==sellerid){
     e.innerHTML="You are the seller. You are not allowed to bid on your own auctions.";
     return;
   }
   e.innerHTML="";
   var c=document.getElementById("bidConfirmId");
   c.innerHTML="";
   var minamount=currentbid*1.05;
   if (bidamount<minamount){
    e.innerHTML="Bid amount must be at least 5% higher than current bid price, at least:$"+minamount;
    c.innerHTML="";
    return;
   }
   if (bidamount<reserveprice){
    e.innerHTML="Bid amount can not be less than seller's reserved price: $"+reserveprice;
    c.innerHTML="";
    return;
 }
e.innerHTML="";
   var url="php/placeBid.php?";
   var parameter="auctionid="+auctionid+"&"+"userid="+userid+"&"+"bidamount="+bidamount+"&buynowprice=0";
   getServerHttpResponse(url, parameter, updateHtmlDynamicPart);
}
function buynow(auctionid, userid, sellerid, buynowprice){
  var e=document.getElementById("bidErrorId");
  if (userid==sellerid){
    e.innerHTML="You are the seller. You are not allowed to buy your own auction items.";
    return;
  }
  var url="php/placeBid.php?";
   var parameter="auctionid="+auctionid+"&"+"userid="+userid+"&"+"bidamount=0&buynowprice="+buynowprice;
   getServerHttpResponse(url, parameter, updateHtmlDynamicPart);
}
function displayFloor(youtubeid){
  var e=document.getElementById("footerId");
  e.style.visibility="visible";
  e=document.getElementById("placeBidDynamicPart");
  e.setAttribute("align","center");
  e.innerHTML='<embed src="https://www.youtube.com/embed/'+youtubeid+'" allowfullscreen="true" width="400" height="300">';
}

function certifiedMechanic(certifiedby){
  var e=document.getElementById("footerId");
  e.style.visibility="visible";
  e=document.getElementById("placeBidDynamicPart");
  e.setAttribute("align","center");
  e.innerHTML='<iframe src="https://www.google.com/maps/embed?pb='+certifiedby+'" width="600" height="450" frameborder="0" style="border:0"></iframe>';
}
function viewAllReviews(sellerid){
  var url="php/viewAllReviews.php?"
  var parameter="sellerid="+sellerid;
  getServerHttpResponse(url, parameter, displayAllReviews);
}
function displayAllReviews(httpText){
  var e=document.getElementById("footerId");
  e.style.visibility="hidden";
  e=document.getElementById("placeBidDynamicPart");
  e.setAttribute("align","left");
  e.innerHTML=httpText;
}
function contactSeller(seller_email,user_email){
  var e=document.getElementById("footerId");
  e.style.visibility="visible";
  e=document.getElementById("placeBidDynamicPart");
  e.setAttribute("align","left");
  e.innerHTML=
  '&nbsp;&nbsp;&nbsp;&nbsp;From: <input id="contactFromId" type="text" value="'+user_email+'" style="color: #116EC4" disabled><br/> <br/>'+
  '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To: <input id="contactToId" type="text" value="'+seller_email+'" style="color: #116EC4" disabled><br/> <br/>'+
  'Subject: <input id="contactSubjectId" type="text" style="width: 85%"><br/> <br/>'+
  '<textarea id="emailTextId" style="margin-left: 55px; width: 85%; height: 100px"></textarea><br/>'+
  '<button id="sendButtonId" style="margin-left: 55px" onClick="javascript:sendEmail()">Send</button> <span id="emailErrorId" style="color: #FF0000">&nbsp;</span><br/>'+
  '<span id="emailConfirmationId"  style="margin-left: 55px;color: #0000FF">&nbsp;</span>';
}
function sendEmail(){
  subject=getValueById("contactSubjectId");
  if (subject.length<1){
    document.getElementById("emailErrorId").innerHTML="Subject field can not be blank.";
    return;
  }
  emailText=getValueById("emailTextId");
  if (emailText.length<1){
    document.getElementById("emailErrorId").innerHTML="Email content can not be blank";
    return;    
  }
  document.getElementById("contactSubjectId").disabled="disabled";
  document.getElementById("emailTextId").disabled="disabled";
  document.getElementById("sendButtonId").disabled="disabled";
  document.getElementById("emailErrorId").innerHTML="";
  document.getElementById("emailConfirmationId").innerHTML="Your message is sent to the seller.";
}
function reviewSeller(sellerid, seller_name, userid,user_name,auctionid){
  var e=document.getElementById("footerId");
  e.style.visibility="visible";
  e=document.getElementById("placeBidDynamicPart");
  e.setAttribute("align","left");
  e.innerHTML=
  'Rate Seller:&nbsp;&nbsp;'+
  '<label><input type="radio" name="rating" value="1" id="rating_1"><font color="#FF0000">&#9733;</font></label>&nbsp;&nbsp;'+
  '<label><input type="radio" name="rating" value="2" id="rating_2"><font color="#FF0000">&#9733;&#9733;</font></label>&nbsp;&nbsp;'+
  '<label><input type="radio" name="rating" value="3" id="rating_3"><font color="#FF0000">&#9733;&#9733;&#9733;</font></label>&nbsp;&nbsp;'+
  '<label><input type="radio" name="rating" value="4" id="rating_4"><font color="#FF0000">&#9733;&#9733;&#9733;&#9733;</font></label>&nbsp;&nbsp;'+
  '<label><input type="radio" name="rating" value="5" id="rating_5"><font color="#FF0000">&#9733;&#9733;&#9733;&#9733;&#9733;</font></label> <br/>'+
  'Comment:<br/>'+
       '<textarea id="reviewTextId" style="margin-left: 55px; width: 85%; height: 100px"></textarea><br/>'+
       '<button id="reviewSubmitButtonId" style="margin-left: 55px" onClick="javascript:submitReview('+sellerid+','+userid+','+auctionid+')">Submit</button>'+
       '<span id="reviewErrorId" style="color: #FF0000"></span><br/>'+
       '<span id="reviewConfirmationId" style="margin-left: 55px;color: #196F04"></span>';
}
function submitReview(sellerid, userid, auctionid){
  var rating=0;
  if ( document.getElementById("rating_1").checked ) {
     rating=1;
  } else if ( document.getElementById("rating_2").checked ) {
     rating=2;
  } else if ( document.getElementById("rating_3").checked ) {
     rating=3;
  } else if ( document.getElementById("rating_4").checked ) {
      rating=4;
  } else if ( document.getElementById("rating_5").checked ) {
      rating=5;
  }
  if (rating==0){
    document.getElementById("reviewErrorId").innerHTML=" Please select a rate.";
    return;
  }
  var comment=getValueById("reviewTextId");
  if (comment.length<1){
    document.getElementById("reviewErrorId").innerHTML=" Please write some comments for this seller.";
    return;    
  }
  document.getElementById("reviewErrorId").innerHTML="";
  var url="php/submitReview.php?";
  var parameter="sellerid="+sellerid+"&userid="+userid+"&auctionid="+auctionid+"&rating="+rating+"&comments="+comment;
  getServerHttpResponse(url, parameter, updateSubmitReviewResult);
 }
 function updateSubmitReviewResult(httpText){
   var results=httpText.split(":");
   var stars=results[0];
   var msg=results[1];
   document.getElementById("reviewConfirmationId").innerHTML=msg;//"Your rating and comments are submitted. Thank you for your feedback."
   if (httpText.indexOf("Failed")!=-1){
     return;
   }
   totalReviews=parseInt(getValueById("totalReviews"));
   totalReviews=totalReviews+1;
   document.getElementById("totalReviews").innerHTML=totalReviews;
   document.getElementById("ratingStarsId").innerHTML=stars;
   document.getElementById("reviewSubmitButtonId").disabled="disabled";
   document.getElementById("reviewTextId").disabled="disabled";
   document.getElementById("rating_1").disabled="disabled";
   document.getElementById("rating_2").disabled="disabled";
   document.getElementById("rating_3").disabled="disabled";
   document.getElementById("rating_4").disabled="disabled";
   document.getElementById("rating_5").disabled="disabled";
 }
 function about(){
   var e=document.getElementById("DynamicPart");
   e.innerHTML=
   '<div align="center">'+
     '<div id="placeBidDynamicPart" align="center" style="width: 80%">'+
         '<img src="images/about.png" width="556" height="600" alt=""/>'+
     '</div>'+
   '</div>'+
   '<div id=\"footerId\" style=\"position: absolute; bottom: 0; width: 100%\">'+
   '<hr style=\"border-color: #9E9E9E\">'+
   '<footer class=\"text-center\">'+
   '<div class=\"container\">'+
     '<div class=\"row\">'+
       '<div class=\"col-xs-12\" style=\"color: #AEAEAE\">'+
         '<p>Copyright © Generic Team Name( William Wang, YuChen Zeng, Robert Beck, Michael Berezanich, JingRui Duan). All rights reserved.</p>"'+
       '</div>'+
     '</div>'+
   '</div>'+
   '</footer>'+
   '</div>';
 }
//-------------------- add auction --------------------------
function createAnAuction(){
  var userid=getLoggedInUserId();
  if (userid<1){
    var htmlString=
        '<div align="center" style="margin-top: 10%">'+
        '    <div id="signInErrorId" style="color: #FF0000">'+
        '       Only registered users can create an auction.<br />'+
        '       Please Sign in!'+
        '    </div>'+
        '</div>';
    updateHtmlDynamicPart(htmlString);
    return;
  }
   var url="php/createAnAuctionPage.php?";
   var parameters="userid="+userid;
   getServerHttpResponse(url, parameters, updateHtmlDynamicPart);
}
function startUpload(){
  var e=document.getElementById("loadProgressId");
  e.innerHTML='Processing <img style="visibility: visible" src="images/loader.gif" />';
  e=document.getElementById("submitId");
  e.disabled="disabled";
}
function updateCreateAuctionState(result){
  var e=document.getElementById("loadProgressId");
  e.innerHTML=" ";
  if (result!="0"){
    e=document.getElementById("submitId");
    e.disabled="";
    return;
  }
}
//------------------ list active auctions
function listMyAuctions(){
  var userid=getLoggedInUserId();
  if (userid<1){
    var htmlString=
        '<div align="center" style="margin-top: 10%">'+
        '    <div id="signInErrorId" style="color: #FF0000">'+
        '       Only registered users can view their auctions.<br />'+
        '       Please Sign in!'+
        '    </div>'+
        '</div>';
    updateHtmlDynamicPart(htmlString);
    return;
  }
  var url="php/listMyAuctions.php?";
  var parameters="userid="+userid;
  getServerHttpResponse(url, parameters, updateHtmlDynamicPart);
}
function listMyBids(){
  var userid=getLoggedInUserId();
  if (userid<1){
    var htmlString=
        '<div align="center" style="margin-top: 10%">'+
        '    <div id="signInErrorId" style="color: #FF0000">'+
        '       Only registered users can view their bids.<br />'+
        '       Please Sign in!'+
        '    </div>'+
        '</div>';
    updateHtmlDynamicPart(htmlString);
    return;
  }
  var url="php/listMyBids.php?";
  var parameters="userid="+userid;
  getServerHttpResponse(url, parameters, updateHtmlDynamicPart);
}
//------------------------ teleMarketReports -------------------
function teleMarketReports(){
  var url="php/marketingAuthentication.php?";
  var parameters="";
  getServerHttpResponse(url, parameters, updateHtmlDynamicPart);
}
function validateMarketPassword(pwd){
   if (pwd!="william"){
     var e=document.getElementById("errorId").innerHTML="Invalid marketing password. Hint: william";
     return;
   }
   var url="php/generateMarketingReport.php?";
   var parameters="";
   getServerHttpResponse(url, parameters, updateHtmlDynamicPart);
}
//----------------------- terminateAuction ---------------------
function terminateAuction(){
  var url="php/dbaAuthentication.php?";
  var parameters="";
  getServerHttpResponse(url, parameters, updateHtmlDynamicPart);
}
function validateDbaPassword(pwd){
  var closedate=getValueById("closedateId");
  var e=document.getElementById("errorId");
  if (closedate.length<4){
     e.innerHTML="Close date is not specified.";
     return;
  }
  if (pwd!="william"){
    e.innerHTML="Invalid marketing password. Hint: william";
    return;
  }
  var url="php/terminateAuctions.php?";
  var parameters="closedate="+closedate;
  getServerHttpResponse(url, parameters, updateHtmlDynamicPart); 
}
