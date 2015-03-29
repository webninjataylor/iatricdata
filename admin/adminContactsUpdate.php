<?php require_once('../../../Connections/conn_iatricdata.php'); ?>
<?php
//*****START SESSION*****
//initialize the session
 session_save_path("/home/users/web/b1923/d5.tt-graph/phpsessions");
session_start();

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  session_unregister('MM_Username');
  session_unregister('MM_UserGroup');
	
  $logoutGoTo = "adminLoggedOff.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
 session_save_path("/home/users/web/b1923/d5.tt-graph/phpsessions");
session_start();
$MM_authorizedUsers = "Admin";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "adminLoginFailed.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
//*****END SESSION*****
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_rsContact = "-1";
if (isset($_GET['contactID'])) {
  $colname_rsContact = (get_magic_quotes_gpc()) ? $_GET['contactID'] : addslashes($_GET['contactID']);
}
mysql_select_db($database_conn_iatricdata, $conn_iatricdata);
$query_rsContact = sprintf("SELECT * FROM tblContacts WHERE contactID = %s", GetSQLValueString($colname_rsContact, "int"));
$rsContact = mysql_query($query_rsContact, $conn_iatricdata) or die(mysql_error());
$row_rsContact = mysql_fetch_assoc($rsContact);
$totalRows_rsContact = mysql_num_rows($rsContact);

$colname_rsRequestSamples = "-1";
if (isset($_GET['requestID'])) {
  $colname_rsRequestSamples = (get_magic_quotes_gpc()) ? $_GET['requestID'] : addslashes($_GET['requestID']);
}
$query_rsRequestSamples = sprintf("SELECT * FROM tblRequestSamples WHERE requestID = %s", GetSQLValueString($colname_rsRequestSamples, "int"));
$rsRequestSamples = mysql_query($query_rsRequestSamples, $conn_iatricdata) or die(mysql_error());
$row_rsRequestSamples = mysql_fetch_assoc($rsRequestSamples);
$totalRows_rsRequestSamples = mysql_num_rows($rsRequestSamples);

$colname_rsRequest = "-1";
if (isset($_GET['requestID'])) {
  $colname_rsRequest = (get_magic_quotes_gpc()) ? $_GET['requestID'] : addslashes($_GET['requestID']);
}
$query_rsRequest = sprintf("SELECT * FROM tblRequests WHERE requestID = %s", GetSQLValueString($colname_rsRequest, "int"));
$rsRequest = mysql_query($query_rsRequest, $conn_iatricdata) or die(mysql_error());
$row_rsRequest = mysql_fetch_assoc($rsRequest);
$totalRows_rsRequest = mysql_num_rows($rsRequest);

//Get sample types for selection box
$query_rsSampleTypes = "SELECT * FROM tblSamples ORDER BY sampleType ASC";
$rsSampleTypes = mysql_query($query_rsSampleTypes, $conn_iatricdata) or die(mysql_error());
$row_rsSampleTypes = mysql_fetch_assoc($rsSampleTypes);
$totalRows_rsSampleTypes = mysql_num_rows($rsSampleTypes);

?>
<!doctype html public "-//w3c//dtd xhtml 1.0 transitional//en" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<title>Iatric Data</title>
<!--smooth page transitions-->
<meta http-equiv="page-enter" content="revealtrans(duration=0,transition=1)" />
<!--start wrappers-->
<link rel="stylesheet" type="text/css" href="../assets/iatricdata.css">
<script language="JavaScript" type="text/javascript" src="../assets/iatricdata.js"></script>
<!--end wrappers-->
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>
</head><body onLoad="insertHeaderAdmin(); insertFooter(); styleButtons(); MM_preloadImages('../images/menuHomeOn.gif','../images/menuCompanyOn.gif','../images/menuPanelsOn.gif','../images/menuServicesOn.gif','../images/menuContactOn.gif')">
<!--start content-->
<div id="content" style="width:760px; position:absolute; top:20px; z-index:102; left:50%; margin-left:-380px;">
  <div id="header"><img src="../images/spacer.gif" alt="Spacer" width="760" height="84" style="margin-bottom:4px;" /></div>
  <table width="760" border="0" cellspacing="0" cellpadding="0">
    <tr valign="top">
      <td style="border-right:2px solid #ffffff;"><a href="../index.php" onMouseOver="MM_swapImage('menuHome','','../images/menuHomeOn.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="../images/menuHome.gif" alt="Home" name="menuHome" width="151" height="28" border="0" id="menuHome" /></a></td>
      <td style="border-right:2px solid #ffffff;"><a href="../company.php" onMouseOver="MM_swapImage('menuCompany','','../images/menuCompanyOn.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="../images/menuCompany.gif" alt="Company" name="menuCompany" width="150" height="28" border="0" id="menuCompany" /></a></td>
      <td style="border-right:2px solid #ffffff;"><a href="../panels.php" onMouseOver="MM_swapImage('menuPanels','','../images/menuPanelsOn.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="../images/menuPanels.gif" alt="Panels" name="menuPanels" width="150" height="28" border="0" id="menuPanels" /></a></td>
      <td style="border-right:2px solid #ffffff;"><a href="../services.php" onMouseOver="MM_swapImage('menuServices','','../images/menuServicesOn.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="../images/menuServices.gif" alt="Services" name="menuServices" width="150" height="28" border="0" id="menuServices" /></a></td>
      <td style="border-right:none;"><a href="../contact.php" onMouseOver="MM_swapImage('menuContact','','../images/menuContactOn.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="../images/menuContact.gif" alt="Contact Us" name="menuContact" width="151" height="28" border="0" id="menuContact" /></a></td>
    </tr>
  </table>
  <table width="760"  border="0" cellspacing="0" cellpadding="0">
    <tr valign="top">
      <td style="padding-top:20px; padding-left:20px; padding-right:20px;"><div id="logOff"><a href="<?php echo $logoutAction ?>" class="link">Log Off</a></div>
        <h1><a href="adminMaster.php" class="link">Admin</a> &gt; <a href="adminContacts.php" class="link">Contacts</a> &gt; Details/Update</h1></td>
    </tr>
    <tr valign="top">
      <td style="padding-left:20px; padding-right:20px;"><form action="adminContactsUpdateProcessor.php" method="POST" name="frmNewContact" id="frmNewContact">
          <table border="0" cellspacing="0" cellpadding="3">
            <tr>
              <td>&nbsp;</td>
              <td class="required">* Required </td>
            </tr>
            <tr>
              <td align="right">Title:</td>
              <td><select name="title" size="1" id="title" style="width:150px;">
                  <option value="" <?php if($row_rsContact['title'] == ''){echo "SELECTED";}?>>Select One</option>
                  <option value="Mr." <?php if($row_rsContact['title'] == 'Mr.'){echo "SELECTED";}?>>Mr.</option>
                  <option value="Mrs." <?php if($row_rsContact['title'] == 'Mrs.'){echo "SELECTED";}?>>Mrs.</option>
                  <option value="Ms." <?php if($row_rsContact['title'] == 'Ms.'){echo "SELECTED";}?>>Ms.</option>
                  <option value="Miss" <?php if($row_rsContact['title'] == 'Miss'){echo "SELECTED";}?>>Miss</option>
                  <option value="Dr." <?php if($row_rsContact['title'] == 'Dr.'){echo "SELECTED";}?>>Dr.</option>
                </select></td>
            </tr>
            <tr>
              <td align="right">First Name:<span class="required">*</span> </td>
              <td><input name="firstName" type="text" id="firstName" value="<?php echo $row_rsContact['firstName']; ?>" /></td>
            </tr>
            <tr>
              <td align="right">Last Name:<span class="required">*</span> </td>
              <td><input name="lastName" type="text" id="lastName" value="<?php echo $row_rsContact['lastName']; ?>" /></td>
            </tr>
            <tr>
              <td align="right">Job Title: </td>
              <td><input name="jobTitle" type="text" id="jobTitle" value="<?php echo $row_rsContact['jobTitle']; ?>" /></td>
            </tr>
            <tr>
              <td align="right">Company:<span class="required">*</span></td>
              <td><input name="company" type="text" id="company" value="<?php echo $row_rsContact['company']; ?>" /></td>
            </tr>
            <tr>
              <td align="right">Address 1:</td>
              <td><input name="address1" type="text" id="address1" value="<?php echo $row_rsContact['address1']; ?>" /></td>
            </tr>
            <tr>
              <td align="right">Address 2:</td>
              <td><input name="address2" type="text" id="address2" value="<?php echo $row_rsContact['address2']; ?>" /></td>
            </tr>
            <tr>
              <td align="right">City:</td>
              <td><input name="city" type="text" id="city" value="<?php echo $row_rsContact['city']; ?>" /></td>
            </tr>
            <tr>
              <td align="right">State/Province:</td>
              <td><select name="stateProv" id="stateProv">
                  <option value="" <?php if($row_rsContact['stateProv'] == ''){echo "SELECTED";}?>>SELECT ONE</option>
                  <option value="--U.S.A.--" <?php if($row_rsContact['stateProv'] == ''){echo "SELECTED";}?>>--U.S.A.--</option>
                  <option value="AL" <?php if($row_rsContact['stateProv'] == 'AL'){echo "SELECTED";}?>>Alabama</option>
                  <option value="AK" <?php if($row_rsContact['stateProv'] == 'AK'){echo "SELECTED";}?>>Alaska</option>
                  <option value="AZ" <?php if($row_rsContact['stateProv'] == 'AZ'){echo "SELECTED";}?>>Arizona</option>
                  <option value="AR" <?php if($row_rsContact['stateProv'] == 'AR'){echo "SELECTED";}?>>Arkansas</option>
                  <option value="CA" <?php if($row_rsContact['stateProv'] == 'CA'){echo "SELECTED";}?>>California</option>
                  <option value="CO" <?php if($row_rsContact['stateProv'] == 'CO'){echo "SELECTED";}?>>Colorado</option>
                  <option value="CT" <?php if($row_rsContact['stateProv'] == 'CT'){echo "SELECTED";}?>>Connecticut</option>
                  <option value="DE" <?php if($row_rsContact['stateProv'] == 'DE'){echo "SELECTED";}?>>Delaware</option>
                  <option value="DC" <?php if($row_rsContact['stateProv'] == 'DC'){echo "SELECTED";}?>>District of Columbia</option>
                  <option value="FL" <?php if($row_rsContact['stateProv'] == 'FL'){echo "SELECTED";}?>>Florida</option>
                  <option value="GA" <?php if($row_rsContact['stateProv'] == 'GA'){echo "SELECTED";}?>>Georgia</option>
                  <option value="HI" <?php if($row_rsContact['stateProv'] == 'HI'){echo "SELECTED";}?>>Hawaii</option>
                  <option value="ID" <?php if($row_rsContact['stateProv'] == 'ID'){echo "SELECTED";}?>>Idaho</option>
                  <option value="IL" <?php if($row_rsContact['stateProv'] == 'IL'){echo "SELECTED";}?>>Illinois</option>
                  <option value="IN" <?php if($row_rsContact['stateProv'] == 'IN'){echo "SELECTED";}?>>Indiana</option>
                  <option value="IA" <?php if($row_rsContact['stateProv'] == 'IA'){echo "SELECTED";}?>>Iowa</option>
                  <option value="KS" <?php if($row_rsContact['stateProv'] == 'KS'){echo "SELECTED";}?>>Kansas</option>
                  <option value="KY" <?php if($row_rsContact['stateProv'] == 'KY'){echo "SELECTED";}?>>Kentucky</option>
                  <option value="LA" <?php if($row_rsContact['stateProv'] == 'LA'){echo "SELECTED";}?>>Louisiana</option>
                  <option value="ME" <?php if($row_rsContact['stateProv'] == 'ME'){echo "SELECTED";}?>>Maine</option>
                  <option value="MD" <?php if($row_rsContact['stateProv'] == 'MD'){echo "SELECTED";}?>>Maryland</option>
                  <option value="MA" <?php if($row_rsContact['stateProv'] == 'MA'){echo "SELECTED";}?>>Massachusetts</option>
                  <option value="MI" <?php if($row_rsContact['stateProv'] == 'MI'){echo "SELECTED";}?>>Michigan</option>
                  <option value="MN" <?php if($row_rsContact['stateProv'] == 'MN'){echo "SELECTED";}?>>Minnesota</option>
                  <option value="MS" <?php if($row_rsContact['stateProv'] == 'MS'){echo "SELECTED";}?>>Mississippi</option>
                  <option value="MO" <?php if($row_rsContact['stateProv'] == 'MO'){echo "SELECTED";}?>>Missouri</option>
                  <option value="MT" <?php if($row_rsContact['stateProv'] == 'MT'){echo "SELECTED";}?>>Montana</option>
                  <option value="NE" <?php if($row_rsContact['stateProv'] == 'NE'){echo "SELECTED";}?>>Nebraska</option>
                  <option value="NV" <?php if($row_rsContact['stateProv'] == 'NV'){echo "SELECTED";}?>>Nevada</option>
                  <option value="NH" <?php if($row_rsContact['stateProv'] == 'NH'){echo "SELECTED";}?>>New Hampshire</option>
                  <option value="NJ" <?php if($row_rsContact['stateProv'] == 'NJ'){echo "SELECTED";}?>>New Jersey</option>
                  <option value="NM" <?php if($row_rsContact['stateProv'] == 'NM'){echo "SELECTED";}?>>New Mexico</option>
                  <option value="NY" <?php if($row_rsContact['stateProv'] == 'NY'){echo "SELECTED";}?>>New York</option>
                  <option value="NC" <?php if($row_rsContact['stateProv'] == 'NC'){echo "SELECTED";}?>>North Carolina</option>
                  <option value="ND" <?php if($row_rsContact['stateProv'] == 'ND'){echo "SELECTED";}?>>North Dakota</option>
                  <option value="OH" <?php if($row_rsContact['stateProv'] == 'OH'){echo "SELECTED";}?>>Ohio</option>
                  <option value="OK" <?php if($row_rsContact['stateProv'] == 'OK'){echo "SELECTED";}?>>Oklahoma</option>
                  <option value="OR" <?php if($row_rsContact['stateProv'] == 'OR'){echo "SELECTED";}?>>Oregon</option>
                  <option value="PA" <?php if($row_rsContact['stateProv'] == 'PA'){echo "SELECTED";}?>>Pennsylvania</option>
                  <option value="PR" <?php if($row_rsContact['stateProv'] == 'PR'){echo "SELECTED";}?>>Puerto Rico</option>
                  <option value="RI" <?php if($row_rsContact['stateProv'] == 'RI'){echo "SELECTED";}?>>Rhode Island</option>
                  <option value="SC" <?php if($row_rsContact['stateProv'] == 'SC'){echo "SELECTED";}?>>South Carolina</option>
                  <option value="SD" <?php if($row_rsContact['stateProv'] == 'SD'){echo "SELECTED";}?>>South Dakota</option>
                  <option value="TN" <?php if($row_rsContact['stateProv'] == 'TN'){echo "SELECTED";}?>>Tennessee</option>
                  <option value="TX" <?php if($row_rsContact['stateProv'] == 'TX'){echo "SELECTED";}?>>Texas</option>
                  <option value="UT" <?php if($row_rsContact['stateProv'] == 'UT'){echo "SELECTED";}?>>Utah</option>
                  <option value="VT" <?php if($row_rsContact['stateProv'] == 'VT'){echo "SELECTED";}?>>Vermont</option>
                  <option value="VI" <?php if($row_rsContact['stateProv'] == 'VI'){echo "SELECTED";}?>>Virgin Islands</option>
                  <option value="VA" <?php if($row_rsContact['stateProv'] == 'VA'){echo "SELECTED";}?>>Virginia</option>
                  <option value="WA" <?php if($row_rsContact['stateProv'] == 'WA'){echo "SELECTED";}?>>Washington</option>
                  <option value="WV" <?php if($row_rsContact['stateProv'] == 'WV'){echo "SELECTED";}?>>West Virginia</option>
                  <option value="WI" <?php if($row_rsContact['stateProv'] == 'WI'){echo "SELECTED";}?>>Wisconsin</option>
                  <option value="WY" <?php if($row_rsContact['stateProv'] == 'WY'){echo "SELECTED";}?>>Wyoming</option>
                  <option value="" <?php if($row_rsContact['stateProv'] == '--U.S.A. Armed Forces--'){echo "SELECTED";}?>>--U.S.A. Armed Forces--</option>
                  <option value="AA" <?php if($row_rsContact['stateProv'] == 'AA'){echo "SELECTED";}?>>AA, Americas</option>
                  <option value="AE" <?php if($row_rsContact['stateProv'] == 'AE'){echo "SELECTED";}?>>AE, Europe</option>
                  <option value="AP" <?php if($row_rsContact['stateProv'] == 'AP'){echo "SELECTED";}?>>AP, Pacific</option>
                  <option value="" <?php if($row_rsContact['stateProv'] == '--Canada--'){echo "SELECTED";}?>>--Canada--</option>
                  <option value="NF" <?php if($row_rsContact['stateProv'] == 'NF'){echo "SELECTED";}?>>NF, Canada</option>
                  <option value="NS" <?php if($row_rsContact['stateProv'] == 'NS'){echo "SELECTED";}?>>NS, Canada</option>
                  <option value="PE" <?php if($row_rsContact['stateProv'] == 'PE'){echo "SELECTED";}?>>PE, Canada</option>
                  <option value="NB" <?php if($row_rsContact['stateProv'] == 'NB'){echo "SELECTED";}?>>NB, Canada</option>
                  <option value="QC" <?php if($row_rsContact['stateProv'] == 'QC'){echo "SELECTED";}?>>QC, Canada</option>
                  <option value="ON" <?php if($row_rsContact['stateProv'] == 'ON'){echo "SELECTED";}?>>ON, Canada</option>
                  <option value="MB" <?php if($row_rsContact['stateProv'] == 'MB'){echo "SELECTED";}?>>MB, Canada</option>
                  <option value="SK" <?php if($row_rsContact['stateProv'] == 'SK'){echo "SELECTED";}?>>SK, Canada</option>
                  <option value="AB" <?php if($row_rsContact['stateProv'] == 'AB'){echo "SELECTED";}?>>AB, Canada</option>
                  <option value="BC" <?php if($row_rsContact['stateProv'] == 'BC'){echo "SELECTED";}?>>BC, Canada</option>
                  <option value="NT" <?php if($row_rsContact['stateProv'] == 'NT'){echo "SELECTED";}?>>NT, Canada</option>
                  <option value="YT" <?php if($row_rsContact['stateProv'] == 'YT'){echo "SELECTED";}?>>YT, Canada</option>
                </select></td>
            </tr>
            <tr>
              <td align="right">Zip/Postal&#160;Code:</td>
              <td><input name="zip" type="text" class="" id="zip" value="<?php echo $row_rsContact['zip']; ?>" size="10" maxlength="10" /></td>
            </tr>
            <tr>
              <td align="right">E-mail Address:<span class="required">*</span></td>
              <td><input name="email" type="text" id="email" value="<?php echo $row_rsContact['email']; ?>" size="30" /></td>
            </tr>
            <tr>
              <td align="right">Phone:</td>
              <td><script language="JavaScript" type="text/javascript">
				function phoneTab(phoneValue, phonePart){
					if((phonePart == 'phone1')&&(phoneValue.length == document.frmNewContact(phonePart).maxLength)){
						document.frmNewContact.phone2.focus();
					}
					else if((phonePart == 'phone2')&&(phoneValue.length == document.frmNewContact(phonePart).maxLength)){
						document.frmNewContact.phone3.focus();
					}
				}
				</script>
                (
                <input name="phone1" type="text" id="phone1" onKeyUp="phoneTab(this.value, this.id);" value="<?php echo $row_rsContact['phone1']; ?>" size="3" maxlength="3" />
                )
                <input name="phone2" type="text" id="phone2" onKeyUp="phoneTab(this.value, this.id);" value="<?php echo $row_rsContact['phone2']; ?>" size="3" maxlength="3" />
                -
                <input name="phone3" type="text" id="phone3" value="<?php echo $row_rsContact['phone3']; ?>" size="4" maxlength="4" /></td>
            </tr>
            <tr valign="top">
              <td align="right">Subject:<span class="required">*</span></td>
              <td><input name="subject" type="radio" id="subject" value="Marketing Research" <?php if($row_rsRequest['subject'] == 'Marketing Research'){echo "CHECKED";}?> />
                Marketing Research&#160;&#160;&#160;
                <input name="subject" id="subject" type="radio" value="Disease Management" <?php if($row_rsRequest['subject'] == 'Disease Management'){echo "CHECKED";}?> />
                Disease Management<br />
                <input name="subject" id="subject" type="radio" value="Clinical Trial" <?php if($row_rsRequest['subject'] == 'Clinical Trial'){echo "CHECKED";}?> />
                Clinical Trial&#160;&#160;&#160;
                <input name="subject" id="subject" type="radio" value="iPatient Profiles" <?php if($row_rsRequest['subject'] == 'iPatient Profiles'){echo "CHECKED";}?> />
                iPatient Profiles</td>
            </tr>
            <tr valign="top">
              <td align="right">Additional Info: </td>
              <td><textarea name="additionalInfo" rows="3" id="additionalInfo" style="width:300px;"><?php echo $row_rsRequest['additionalInfo']; ?>
</textarea></td>
            </tr>
            <tr valign="top">
              <td align="right">Sample Type(s): </td>
              <td><select name="sample[]" size="2" multiple="multiple" id="sample[]" style="width:200px;">
                <?php
do {  
?>
                  <option value="<?php echo $row_rsSampleTypes['sampleID']?>"
				  <?php
				  do {
				  	if($row_rsRequestSamples['sampleID'] == $row_rsSampleTypes['sampleID']){
						echo "SELECTED";
					}
				  } while ($row_rsRequestSamples = mysql_fetch_assoc($rsRequestSamples));
				   		$rows = mysql_num_rows($rsRequestSamples);
				   		if($rows > 0) {
				   			mysql_data_seek($rsRequestSamples, 0);
							$row_rsRequestSamples = mysql_fetch_assoc($rsRequestSamples);
						}
				  ?>
				  ><?php echo $row_rsSampleTypes['sampleType']?></option>
                  <?php
} while ($row_rsSampleTypes = mysql_fetch_assoc($rsSampleTypes));
 $rows = mysql_num_rows($rsSampleTypes);
  if($rows > 0) {
      mysql_data_seek($rsSampleTypes, 0);
	  $row_rsSampleTypes = mysql_fetch_assoc($rsSampleTypes);
  }
?>
                </select></td>
            </tr>
            <tr>
              <td align="right">Gender:</td>
              <td><input name="gender" type="radio" value="Male" <?php if($row_rsRequest['gender'] == 'Male'){echo "CHECKED";}?> />
                Male
                <input name="gender" type="radio" value="Female" <?php if($row_rsRequest['gender'] == 'Female'){echo "CHECKED";}?> />
                Female
                <input name="gender" type="radio" value="Both" <?php if($row_rsRequest['gender'] == 'Both'){echo "CHECKED";}?> />
                Both</td>
            </tr>
            <tr>
              <td align="right">Age:</td>
              <td><select name="ageFrom">
                <option value="" <?php if($row_rsRequest['ageFrom'] == ''){echo "SELECTED";}?>></option>
                <option value="18-20" <?php if($row_rsRequest['ageFrom'] == '18-20'){echo "SELECTED";}?>>18-20</option>
                <option value="21-30" <?php if($row_rsRequest['ageFrom'] == '21-30'){echo "SELECTED";}?>>21-30</option>
                <option value="31-40" <?php if($row_rsRequest['ageFrom'] == '31-40'){echo "SELECTED";}?>>31-40</option>
                <option value="41-50" <?php if($row_rsRequest['ageFrom'] == '41-50'){echo "SELECTED";}?>>41-50</option>
                <option value="51-60" <?php if($row_rsRequest['ageFrom'] == '51-60'){echo "SELECTED";}?>>51-60</option>
                <option value="61-70" <?php if($row_rsRequest['ageFrom'] == '61-70'){echo "SELECTED";}?>>61-70</option>
                <option value="71+" <?php if($row_rsRequest['ageFrom'] == '71+'){echo "SELECTED";}?>>71+</option>
              </select>
                to
                <select name="ageTo">
                  <option value="" <?php if($row_rsRequest['ageTo'] == ''){echo "SELECTED";}?>></option>
                  <option value="18-20" <?php if($row_rsRequest['ageTo'] == '18-20'){echo "SELECTED";}?>>18-20</option>
                  <option value="21-30" <?php if($row_rsRequest['ageTo'] == '21-30'){echo "SELECTED";}?>>21-30</option>
                  <option value="31-40" <?php if($row_rsRequest['ageTo'] == '31-40'){echo "SELECTED";}?>>31-40</option>
                  <option value="41-50" <?php if($row_rsRequest['ageTo'] == '41-50'){echo "SELECTED";}?>>41-50</option>
                  <option value="51-60" <?php if($row_rsRequest['ageTo'] == '51-60'){echo "SELECTED";}?>>51-60</option>
                  <option value="61-70" <?php if($row_rsRequest['ageTo'] == '61-70'){echo "SELECTED";}?>>61-70</option>
                  <option value="71+" <?php if($row_rsRequest['ageTo'] == '71+'){echo "SELECTED";}?>>71+</option>
                </select></td>
            </tr>
            <tr>
              <td align="right">Sample Size: </td>
              <td><input name="sampleSize" type="text" value="<?php echo $row_rsRequest['sampleSize']; ?>" size="4" maxlength="4" />
                (up to 9999)</td>
            </tr>
            <tr>
              <td align="right">Closed Ended Questions: </td>
              <td><input name="closedEnded" type="text" value="<?php echo $row_rsRequest['closedEnded']; ?>" size="3" maxlength="3" />
                (up to 999)</td>
            </tr>
            <tr>
              <td align="right">Open Ended Questions: </td>
              <td><input name="openEnded" type="text" value="<?php echo $row_rsRequest['openEnded']; ?>" size="2" maxlength="2" />
                (up to 99)</td>
            </tr>
            <tr>
              <td align="right">Coding of Open Ends: </td>
              <td><input name="coding" type="radio" value="Yes" <?php if($row_rsRequest['coding'] == 'Yes'){echo "CHECKED";}?> />
                Yes
                <input name="coding" type="radio" value="No" <?php if($row_rsRequest['coding'] == 'No'){echo "CHECKED";}?> />
                No</td>
            </tr>
            <tr>
              <td align="right"><input name="modifiedBy" type="hidden" id="modifiedBy" value="<?php echo $_SESSION['MM_Username']; ?>" />
			  <input name="contactID" type="hidden" id="contactID" value="<?php echo $_GET['contactID']; ?>" />
			  <input name="requestID" type="hidden" id="requestID" value="<?php echo $_GET['requestID']; ?>" /></td>
              <td><input name="Submit" type="submit" onClick="MM_validateForm('firstName','','R','lastName','','R','company','','R','email','','RisEmail');return document.MM_returnValue" value="Update" /></td>
            </tr>
          </table>
        </form></td>
    </tr>
  </table>
  <div id="footer"></div>
</div>
<!--end content-->
</body>
</html>
<?php
mysql_free_result($rsContact);
mysql_free_result($rsRequestSamples);
mysql_free_result($rsRequest);
mysql_free_result($rsSampleTypes);
?>
