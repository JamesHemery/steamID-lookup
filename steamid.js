function HandleSteamidResponse(response)
{
  document.getElementById('ResponseDiv').innerHTML = response;
  
  jQuery(document).ready(function() { 
  jQuery(".ResponseDiv").fadeIn(1000,"swing");
});
  
  jQuery(document).ready(function() {
  jQuery(".tcontent").hide();
  //toggle the componenet with class msg_body
  jQuery(".toggleContent").click(function()
  {
    jQuery(this).next(".tcontent").slideToggle(500);
  });
});
}
function getXMLHttpSteamid()
{
  var xmlHttp

  try
  {
    //Firefox, Opera 8.0+, Safari
    xmlHttp = new XMLHttpRequest();
  }
  catch(e)
  {
    //Internet Explorer
    try
    {
      xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch(e)
    {
      try
      {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch(e)
      {
        alert("Your browser does not support AJAX!")
        return false;
      }
    }
  }
  return xmlHttp;
}
function MakeSteamidRequest(str)
{
if (document.getElementById('ResponseDiv').innerHTML != "")
{
	jQuery(document).ready(function() {
	jQuery(".ResponseDiv").fadeOut(1000,"swing");
});}
  var xmlHttp = getXMLHttpSteamid();
  
  xmlHttp.onreadystatechange = function()
  {
    if(xmlHttp.readyState == 4)
    {
      HandleSteamidResponse(xmlHttp.responseText);
    }
  }
  xmlHttp.open("GET", "steamidProcess.php?steam="+str, true); 
  xmlHttp.send(null);
}