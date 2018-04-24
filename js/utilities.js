function getXmlHttpObject() {
  if (window.XMLHttpRequest) {
    var xmlhttp = new XMLHttpRequest(); //non-IE browser
    return xmlhttp;
  } else if(window.ActiveXObject)
    return new ActiveXObject("Microsoft.XMLHTTP"); //IE browser
  else
    alert("Your browser doesn't support the XmlHttpRequest object.");
}

//send http://url+parameter request to server. once responce comes back
// call callBack(response)
function getServerHttpResponse(url, parameter, callBack)
{ var geXmlHttp = getXmlHttpObject();
  if (geXmlHttp.overrideMimeType)
     geXmlHttp.overrideMimeType('text/xml');
 
  //geXmlHttp.open("POST", url, true); //false);
  geXmlHttp.open("GET", url+parameter, true);
  geXmlHttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
  geXmlHttp.setRequestHeader('Content-length',parameter.length);
  geXmlHttp.onreadystatechange = function() { 
	 if (geXmlHttp.readyState == 4 && geXmlHttp.status == 200) {
		 //http return back with response
		 callBack(geXmlHttp.responseText);
	 }
  }
  //geXmlHttp.send(parameter);
  geXmlHttp.send(null);
}

function cleanElement(id){
   var htmlElement=document.getElementById(id);
   htmlElement.innerHTML="&nbsp;";
}
/* retrieves the 'value' or innerHTML value of the indicated HTML element ID 
   This function is used to get input field text,
                                select iterm
 */
function getValueById(elemId) {
    return getValueFromDOMById(document, elemId);
}
function getValueFromDOMById(dom, elemId) {
    if (dom) {
        var elem = dom.getElementById(elemId);
        return getValueFromDOM(dom, elem)
    }
    return "";
}
function getValueFromDOM(dom, elem) {
    if (dom) {
        if (elem) {
            if ((elem.nodeName == "input") || (elem.nodeName == "INPUT") ||
                    (elem.nodeName == "select") || (elem.nodeName == "SELECT") ||
                    (elem.nodeName == "textarea") || (elem.nodeName == "TEXTAREA")) {
                return elem.value;
            }
            else {
                return elem.innerHTML;
            }
            return "";
        }
    }
    return "";
}
function isValidEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }
//--------------------- for debug ---------------------
function openBrowserWindow(url, windowTitle, h, w){
    var winId=window.open(url,winTitle,
        "directories=0,height ="+h+",width="+w+",left=200,top=400,location=0,menubar=0,scrollbars=1,status=0,titlebar=0,toolbar=0,resizable=1"
        );
    return winId;
}
var debugWindow=null;
var debugOutput='<head><title>DegugWindow</title></head><body>';
function debug(msg){
    if (debugWindow==null){
        debugWindow=openBrowserWindow("", 'DebugWindow', 600, 400);
    }
    debugOutput+=new Date()+'->'+msg+"<br />";
    if (debugWindow){
        debugWindow.document.open();
        debugWindow.document.write(debugOutput);
        debugWindow.document.write('</body>');
        debugWindow.document.close();
    }
}
function clearById(eid){
    var e=document.getElementById(eid);
    if (e){
        e.innerHTML="";
    }
}
