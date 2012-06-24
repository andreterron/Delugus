/* javascripts.js */
var get_start = 0;
var get_size = 25;
var get_male = '';
var get_female = '';

function loadXMLDoc()
{
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
	var text = "<?xml version=\"1.0\" ?>" + he(xmlhttp.responseText.replace("<!DOCTYPE html>", ""));
	if (window.DOMParser)
	  {
	  parser=new DOMParser();
	  xmlDoc=parser.parseFromString(text,"text/xml");
	  }
	else // Internet Explorer
	  {
	  xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
	  xmlDoc.async=false;
	  xmlDoc.loadXML(text); 
	  } 
	
	var a = xmlDoc.getElementsByTagName("div");
	var txt = "";
	for(i= 0; i < a.length; i++)
	{
		if (a[i].getAttribute("id") == 'people-list') {
			txt = a.item(i).textContent;
		}
	}
	alert("shit ended! text = " + a[0].textContent + "\n\nTHE XML = \n" + text);
	
    document.getElementById("people-list").innerHTML=document.getElementById("people-list").innerHTML+txt;
    }
  }
xmlhttp.open("GET","filter.php?start=" + (get_start + get_size) + "&size=" + get_size ,true);
xmlhttp.send();
}