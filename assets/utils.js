// *******************************
// Validate email
function daBadEmail(email){
	alert('Please enter a valid email address.');
	email.select();
}
function daEmail(email){
	if(email.value == ''){
		return false
	}
	var invalidChars = ' /:,;';
	for(j=0; j<invalidChars.length; j++){  //Check for invalid characters
		badChar = invalidChars.charAt(j);
		if(email.value.indexOf(badChar,0) > -1){
			daBadEmail(email);
			return false
		}
	}
	atPos = email.value.indexOf('@',1);  //Check for an @ sign
	if(atPos == -1){
		daBadEmail(email);
		return false
	}
	if(email.value.indexOf('@',atPos+1) > -1){  //Check for multiple @ signs
		daBadEmail(email);
		return false
	}
	periodPos = email.value.indexOf('.',atPos);  //Check for a . after the @ sign
	if(periodPos == -1){
		daBadEmail(email);
		return false
	}
	if(periodPos+3 > email.value.length){  //Check for at least 2 characters after the .
		daBadEmail(email);
		return false
	}
}
// *******************************
// Only show flyout if menu is collapsed
function isitcollapsed(menuID,divID) {
	if (isAll || isID) {
		domStyle = findDOM(menuID,1);
		if ((domStyle.display == 'none')||(domStyle.display == '')) {
			MM_showHideLayers(divID,'','show');
		}
	}
	return;
}
// *******************************
// Expand or collapse menus
function toggleClamShellMenu(objectID) {
	if (isAll || isID) {
		domStyle = findDOM(objectID,1);
		i=1
		while (i<=2) { //change depending on number of submenus
			objectReset = "menu"+i;
			domStyleReset = findDOM(objectReset,1);
			domStyleReset.display = 'none';
			i+=1;
		}
		if (domStyle.display =='block')  domStyle.display='none';
		else domStyle.display='block';
	}
	else {
		destination = objectID + '.html';
		self.location = destination;
	}
	return;
}
// *******************************
// Find DOM
var isDHTML = 0;
var isID = 0;
var isAll = 0;
var isLayers = 0;

if (document.getElementById) {isID = 1; isDHTML = 1;}
else {
	if (document.all) {isAll = 1; isDHTML = 1;}
	else {
		browserVersion = parseInt(navigator.appVersion);
		if ((navigator.appName.indexOf('Netscape') != -1) && (browserVersion == 4)) {isLayers = 1; isDHTML = 1;}
}}

function findDOM(objectID,withStyle) {
	if (withStyle == 1) {
		if (isID) { return (document.getElementById(objectID).style) ; }
		else { 
			if (isAll) { return (document.all[objectID].style); }
		else {
			if (isLayers) { return (document.layers[objectID]); }
		};}
	}
	else {
		if (isID) { return (document.getElementById(objectID)) ; }
		else { 
			if (isAll) { return (document.all[objectID]); }
		else {
			if (isLayers) { return (document.layers[objectID]); }
		};}
	}
}
// *******************************
// No context menu on right click 
//function nocontextmenu() 
//{ 
//   event.cancelBubble = true; 
//   event.returnValue = false;
//   alert("Copyright(c) Merck & Co., Inc. 2004");
//   return false; 
//} 
//document.oncontextmenu = nocontextmenu;
//window.status='';
// *******************************
// Called by page onLoad to start processing the menu
function kickoff(){
	page = pageName();
	
	if (page == "default.aspx"){  // Select MCD Home in Menu
		main1TD.className = "leftNavItemHoverOn";
		main1P.className = "leftNavItem1SelectedwSubs";
		toggleClamShellMenu('menu1');
	}
	else if (page == "defaultOwner.aspx"){  // Select MCD Owner in Menu
		main2TD.className = "leftNavItemHoverOn";
		main2P.className = "leftNavItem1SelectedwSubs";
		toggleClamShellMenu('menu2');
	}
}
// *******************************
// Returns the page name
function pageName(){                                  // Pass in nothing
        page = document.location.pathname.split("/"); // Initialize var with URL pathname array
        page = page[(page.length)-1];                 // Set var to last value in array
	return (page);                                // Return the page name
}
// *******************************
// Returns the element top pixel coordinate
function getElementTop(eElement)             // Pass in element
{
    nTopPos = eElement.offsetTop;            // Initialize var to store calculations
    eParElement = eElement.offsetParent;     // Identify first offset parent element  
    while (eParElement != null)
    {                                            // Move up through element hierarchy
        nTopPos += eParElement.offsetTop;        // Appending top offset of each parent
        eParElement = eParElement.offsetParent;  // Until no more offset parents exist
    }
	return (nTopPos);                              // Return the number calculated
}
// *******************************
// MACROMEDIA
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}


function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}
// *******************************
// window pop up function
function popWindow(url, myWidth, myHeight) {
	var winWidth, winHeight;
	if (myWidth) {
		winWidth = myWidth;
	} else {
		winWidth = 462;
	}
	
	if (myHeight) {
		winHeight = myHeight;
	} else {
		winHeight = 480;
	}
	
	var optionListing = "HEIGHT=" + winHeight + ",WIDTH=" + winWidth + ",innerHeight=480,innerWidth=462,channelmode=0,dependent=0,directories=0,fullscreen=0,location=0,menubar=0,resizable=1,scrollbars=1,status=0,toolbar=0,screenX=10,screenY=20"
	remote = window.open(url,"sb_popup",optionListing);
	if (remote != null)  {
		remote.focus();
	} // end if
}

function popWindowScroll(url) {
	var optionListing = "HEIGHT=500,WIDTH=477,innerHeight=500,innerWidth=477,channelmode=0,dependent=0,directories=0,fullscreen=0,location=0,menubar=0,resizable=1,scrollbars=1,status=0,toolbar=0,screenX=10,screenY=20"
	remote = window.open(url,"sb_popup",optionListing);
	if (remote != null)  {
		remote.focus();
	} // end if
}

function popWindowLarge(url) {
	var optionListing = "HEIGHT=500,WIDTH=660,innerHeight=500,innerWidth=660,channelmode=0,dependent=0,directories=0,fullscreen=0,location=0,menubar=0,resizable=1,scrollbars=1,status=0,toolbar=0,screenX=10,screenY=20"
	remote = window.open(url,"sb_popup",optionListing);
	if (remote != null)  {
		remote.focus();
	} // end if
}

function sb_validateForm() { //v4.0
  	var i,p,q,nm,test,num,min,max,errors='',args=sb_validateForm.arguments;
  	for (i=0; i<(args.length-2); i+=3) { 
	
		test=args[i+2]; 
		val=MM_findObj(args[i]);
		if (val) { 
	
			var temp_nm=val.name;
			temp_nm=args[i];
			nm = eval(temp_nm+'_label'); 
		
			if ((val=val.value)!="") {
    	  		if (test.indexOf('isEmail')!=-1) { 
					p=val.indexOf('@');
					if (p<1 || p==(val.length-1)) {
						errors+='- '+nm+' '+emailError+'\n';
     				}
				} else if (test!='R') { 
					num = parseFloat(val);
       				if (isNaN(val)) {
       					errors+='- '+nm+' '+numericError+'\n';
	   				}
					if (test.indexOf('inRange') != -1) { 
						p=test.indexOf(':');
        	  			min=test.substring(8,p); 
          				max=test.substring(p+1);
         				if (num<min || max<num) {
         					errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    					}
					} // end if in range
    			} // end if 
    		} else if (test.charAt(0) == 'R') {
    			errors += '- '+nm+' '+nullError+'.\n'; 
			} // end if value != ""
		} // end if val
  	} // end for loop
	
  	if (errors) {
  		alert(validationMessage+'\n'+errors);
	}
	
  document.MM_returnValue = (errors == '');
}



// Returns the entire query string passed to the current page.
function getQueryString(){ 
        zquery = parent.location.search; 
        zquery = zquery.substring(1,zquery.length); 
        return (zquery);
} 

// Parses a query string 'queryString' for the variable 'queryName'
// and returns the value associated with that name.
function parseQuery (queryString, queryName) {
	startIndex = queryString.indexOf(queryName);
	if (startIndex == -1) {
		return "";
	}
	
	startIndex = queryString.indexOf("=", startIndex) + 1;
	endIndex = queryString.indexOf("&", startIndex);
	if (endIndex == -1) {
		endIndex = queryString.length;
	}
	return queryString.substring(startIndex, endIndex);
}

function jumpPage(url) {
	if ((url != "none") && (url != "")) {
		// Escape the value of the parameter
		paramName = url.substring(0, url.indexOf("=")+1);
		varName = escape(url.substring(url.indexOf("=") + 1, url.length));
		url =  paramName + varName;
		location.href=url;
	}
}

function changeIframe (baseUrl, defaultPage, iframeNameParam) {
	var iframeName = "externalContent";
	if ((typeof iframeNameParam != 'undefined') && (iframeNameParam != "")) {
		iframeName = iframeNameParam;
	}
	
	var subpage = parseQuery(getQueryString(), "subpage");
	//alert("subpage = " + subpage);
	if (subpage == "") {
		subpage = defaultPage;
	}
	
	iFrameObj = getObj(iframeName);
	iFrameObj.src= "http://" + baseUrl + subpage;
}


// Writes out the subsection navigation
function sb_writeSubsectionNav () {
	var sHTML = "";
	var currentLocation = location.href;
	currentLocation = currentLocation.slice(currentLocation.lastIndexOf("/")+1);
	currentSection = -1;
	currentIndex = -1;
	
	//alert("currentIndex = "+currentIndex);
	
	for (i=0; (i<subsection.length) && (currentSection < 0); i++) {
		for (j=0; (j<subsection[i].length) && (currentIndex < 0); j++) {
			if (subsection[i][j].indexOf(currentLocation) != -1) {
				//alert ("index found at "+j+" - "+i);
				currentIndex = j;
				currentSection = i;
			}
		}
	}
	
	sHTML += "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
	sHTML += "<tr>";
	sHTML += "<td><img src=\"/site_images/s.gif\" width=\"1\" height=\"10\" alt=\"\"/></td>"
	sHTML += "<td  align=\"center\" valign=\"middle\" class=\"prevNext\" height=\"10\" nowrap>";
	
	if ((currentIndex-1) < 0) {
		sHTML += "<img src=\"/site_images/s.gif\" width=\"2\" height=\"1\" alt=\"\" /><span class=\"prevNextArrow\">&lt;</span><img src=\"/site_images/s.gif\" width=\"5\" height=\"1\" alt=\"\" />" + prevText ;
	} else {
		sHTML += "<img src=\"/site_images/s.gif\" width=\"2\" height=\"1\" alt=\"\" /><span class=\"prevNextArrow\">&lt;</span><img src=\"/site_images/s.gif\" width=\"5\" height=\"1\" alt=\"\" /><a href=\""  + subsection[currentSection][currentIndex-1] + "\" class=\"prevNextLink\">" + prevText + "</a>";
	}
	
	if (subsectionIndex == "") {
		sHTML += "<img src=\"/site_images/s.gif\" width=\"4\" height=\"1\" alt=\"\" />|<img src=\"/site_images/s.gif\" width=\"5\" height=\"1\" alt=\"\" />";
		sHTML += "Index";
		sHTML += "<img src=\"/site_images/s.gif\" width=\"4\" height=\"1\" alt=\"\" />|<img src=\"/site_images/s.gif\" width=\"5\" height=\"1\" alt=\"\" />";
	} else {
		sHTML += "<img src=\"/site_images/s.gif\" width=\"4\" height=\"1\" alt=\"\" />|<img src=\"/site_images/s.gif\" width=\"5\" height=\"1\" alt=\"\" />";
		sHTML += "<a href=\"" + subsectionIndex + "\" class=\"prevNextLink\">" + indexText + "</a>";
		sHTML += "<img src=\"/site_images/s.gif\" width=\"4\" height=\"1\" alt=\"\" />|<img src=\"/site_images/s.gif\" width=\"5\" height=\"1\" alt=\"\" />";
	}

	if ( (currentIndex < 0) || (currentIndex > (subsection[currentSection].length - 2)) ) {	
		sHTML += nextText + "<img src=\"/site_images/s.gif\" width=\"4\" height=\"1\" alt=\"\" /><span class=\"prevNextArrow\">&gt;</span><img src=\"/site_images/s.gif\" width=\"3\" height=\"1\" alt=\"\" />";
	} else {
		sHTML += "<a href=\"" + subsection[currentSection][currentIndex+1] +  "\" class=\"prevNextLink\">" + nextText + "</a><img src=\"/site_images/s.gif\" width=\"4\" height=\"1\" alt=\"\" /><span class=\"prevNextArrow\">&gt;</span><img src=\"/site_images/s.gif\" width=\"3\" height=\"1\" alt=\"\" />";
	}	
	sHTML += "</td>";
	sHTML += "</tr>";
	sHTML += "</table>";

	//alert(sHTML);
	document.write(sHTML);
}