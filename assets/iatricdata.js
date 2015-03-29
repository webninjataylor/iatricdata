//******************************************
//***** Written by Taylor Johnson 2006 *****
//******************************************

//Global Variables
var currentURL = document.location;
var frozenMenu;
var randomImage = Math.floor(Math.random()*3)+1;
var copyrightDate = new Date;

//Headers and footer
function insertHeader(){
	document.getElementById('header').innerHTML = '<img src="images/bannerRandom' + randomImage + '.gif" alt="Iatric Data logo and banner" width="760" height="84" style="margin-bottom:4px;" />';
}
function insertHeaderAdmin(){
	document.getElementById('header').innerHTML = '<img src="../images/bannerRandom' + randomImage + '.gif" alt="Iatric Data logo and banner" width="760" height="84" style="margin-bottom:4px;" />';
}
function insertFooter(){
	document.getElementById('footer').innerHTML = 'Copyright &copy; ' + copyrightDate.getFullYear() + ' Iatric Data, LLC. All rights reserved.&#160;&#160;&#160;&bull;&#160;&#160;&#160;<a href="http://www.tt-graphics.com" class="link">Design by T&amp;T Graphics</a>';
}
//Style buttons
function styleButtons(){
	var formItems = document.forms[0].length;   //START STYLING BUTTONS
	for(i=0; i<formItems; i++){
		if((document.forms[0].elements[i].type=="submit")||(document.forms[0].elements[i].type=="button")){
			document.forms[0].elements[i].className = "buttons";
		}
	}
}
//***Toggle Steps***
function toggleSteps(objectID) {
	if(objectID == "step1"){
		document.getElementById("step1").style.display="block";
		document.getElementById("step2").style.display="none";
		document.getElementById("step1Tab").className="stepTabOn";
		document.getElementById("step2Tab").className="stepTabOff";
	} else {
		document.getElementById("step1").style.display="none";
		document.getElementById("step2").style.display="block";
		document.getElementById("step1Tab").className="stepTabOff";
		document.getElementById("step2Tab").className="stepTabOn";
	}
	return;
}
//Dreamweaver Functions
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}