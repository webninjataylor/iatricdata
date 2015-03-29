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
mysql_select_db($database_conn_iatricdata, $conn_iatricdata);
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
        <h1><a href="adminMaster.php" class="link">Admin</a> &gt; <a href="adminContacts.php" class="link">Contacts</a> &gt; New Contact or Request</h1></td>
    </tr>
    <tr valign="top">
      <td style="padding-left:20px; padding-right:20px;"><form action="adminContactsNewProcessor.php" method="POST" name="frmNewContact" id="frmNewContact">
          <table border="0" cellspacing="0" cellpadding="3">
            <tr>
              <td>&nbsp;</td>
              <td class="required">* Required </td>
            </tr>
            <tr>
              <td align="right">Title:</td>
              <td><select name="title" size="1" id="title" style="width:150px;">
                  <option value="" selected="selected">Select One</option>
                  <option value="Mr.">Mr.</option>
                  <option value="Mrs.">Mrs.</option>
                  <option value="Ms.">Ms.</option>
                  <option value="Miss">Miss</option>
                  <option value="Dr.">Dr.</option>
                </select></td>
            </tr>
            <tr>
              <td align="right">First Name:<span class="required">*</span> </td>
              <td><input name="firstName" type="text" id="firstName" /></td>
            </tr>
            <tr>
              <td align="right">Last Name:<span class="required">*</span> </td>
              <td><input name="lastName" type="text" id="lastName" /></td>
            </tr>
            <tr>
              <td align="right">Job Title: </td>
              <td><input name="jobTitle" type="text" id="jobTitle" /></td>
            </tr>
            <tr>
              <td align="right">Company:<span class="required">*</span></td>
              <td><input name="company" type="text" id="company" /></td>
            </tr>
            <tr>
              <td align="right">Address 1:</td>
              <td><input name="address1" type="text" id="address1" /></td>
            </tr>
            <tr>
              <td align="right">Address 2:</td>
              <td><input name="address2" type="text" id="address2" /></td>
            </tr>
            <tr>
              <td align="right">City:</td>
              <td><input name="city" type="text" id="city" /></td>
            </tr>
            <tr>
              <td align="right">State/Province:</td>
              <td><select name="stateProv" id="stateProv">
                  <option value="" selected="selected">SELECT ONE</option>
                  <option value="">--U.S.A.--</option>
                  <option value="AL">Alabama</option>
                  <option value="AK">Alaska</option>
                  <option value="AZ">Arizona</option>
                  <option value="AR">Arkansas</option>
                  <option value="CA">California</option>
                  <option value="CO">Colorado</option>
                  <option value="CT">Connecticut</option>
                  <option value="DE">Delaware</option>
                  <option value="DC">District of Columbia</option>
                  <option value="FL">Florida</option>
                  <option value="GA">Georgia</option>
                  <option value="HI">Hawaii</option>
                  <option value="ID">Idaho</option>
                  <option value="IL">Illinois</option>
                  <option value="IN">Indiana</option>
                  <option value="IA">Iowa</option>
                  <option value="KS">Kansas</option>
                  <option value="KY">Kentucky</option>
                  <option value="LA">Louisiana</option>
                  <option value="ME">Maine</option>
                  <option value="MD">Maryland</option>
                  <option value="MA">Massachusetts</option>
                  <option value="MI">Michigan</option>
                  <option value="MN">Minnesota</option>
                  <option value="MS">Mississippi</option>
                  <option value="MO">Missouri</option>
                  <option value="MT">Montana</option>
                  <option value="NE">Nebraska</option>
                  <option value="NV">Nevada</option>
                  <option value="NH">New Hampshire</option>
                  <option value="NJ">New Jersey</option>
                  <option value="NM">New Mexico</option>
                  <option value="NY">New York</option>
                  <option value="NC">North Carolina</option>
                  <option value="ND">North Dakota</option>
                  <option value="OH">Ohio</option>
                  <option value="OK">Oklahoma</option>
                  <option value="OR">Oregon</option>
                  <option value="PA">Pennsylvania</option>
                  <option value="PR">Puerto Rico</option>
                  <option value="RI">Rhode Island</option>
                  <option value="SC">South Carolina</option>
                  <option value="SD">South Dakota</option>
                  <option value="TN">Tennessee</option>
                  <option value="TX">Texas</option>
                  <option value="UT">Utah</option>
                  <option value="VT">Vermont</option>
                  <option value="VI">Virgin Islands</option>
                  <option value="VA">Virginia</option>
                  <option value="WA">Washington</option>
                  <option value="WV">West Virginia</option>
                  <option value="WI">Wisconsin</option>
                  <option value="WY">Wyoming</option>
                  <option value="">--U.S.A. Armed Forces--</option>
                  <option value="AA">AA, Americas</option>
                  <option value="AE">AE, Europe</option>
                  <option value="AP">AP, Pacific</option>
                  <option value="">--Canada--</option>
                  <option value="NF">NF, Canada</option>
                  <option value="NS">NS, Canada</option>
                  <option value="PE">PE, Canada</option>
                  <option value="NB">NB, Canada</option>
                  <option value="QC">QC, Canada</option>
                  <option value="ON">ON, Canada</option>
                  <option value="MB">MB, Canada</option>
                  <option value="SK">SK, Canada</option>
                  <option value="AB">AB, Canada</option>
                  <option value="BC">BC, Canada</option>
                  <option value="NT">NT, Canada</option>
                  <option value="YT">YT, Canada</option>
                </select></td>
            </tr>
            <tr>
              <td align="right">Zip/Postal&#160;Code:</td>
              <td><input name="zip" type="text" class="" id="zip" value="" size="10" maxlength="10" /></td>
            </tr>
            <tr>
              <td align="right">E-mail Address:<span class="required">*</span></td>
              <td><input name="email" type="text" id="email" size="30" /></td>
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
                <input name="phone1" type="text" id="phone1" onKeyUp="phoneTab(this.value, this.id);" size="3" maxlength="3" />
                )
                <input name="phone2" type="text" id="phone2" onKeyUp="phoneTab(this.value, this.id);" size="3" maxlength="3" />
                -
                <input name="phone3" type="text" id="phone3" size="4" maxlength="4" /></td>
            </tr>
            <tr valign="top">
              <td align="right">Subject:<span class="required">*</span></td>
              <td><input name="subject" type="radio" id="subject" value="Marketing Research" checked="checked" />
                Marketing Research&#160;&#160;&#160;
                <input name="subject" id="subject" type="radio" value="Disease Management" />
                Disease Management<br />
                <input name="subject" id="subject" type="radio" value="Clinical Trial" />
                Clinical Trial&#160;&#160;&#160;
                <input name="subject" id="subject" type="radio" value="iPatient Profiles" />
                iPatient Profiles</td>
            </tr>
            <tr valign="top">
              <td align="right">Additional Info: </td>
              <td><textarea name="additionalInfo" rows="3" id="additionalInfo" style="width:300px;"></textarea></td>
            </tr>
            <tr valign="top">
              <td align="right">Sample Type(s): </td>
              <td><select name="sample[]" size="2" multiple="multiple" id="sample[]" style="width:200px;">
                  <?php
do {  
?>
                  <option value="<?php echo $row_rsSampleTypes['sampleID']?>"><?php echo $row_rsSampleTypes['sampleType']?></option>
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
              <td><input name="gender" type="radio" value="Male" />
                Male
                <input name="gender" type="radio" value="Female" />
                Female
                <input name="gender" type="radio" value="Both" />
                Both</td>
            </tr>
            <tr>
              <td align="right">Age:</td>
              <td><select name="ageFrom">
                  <option value="" selected="selected"></option>
                  <option value="18-20">18-20</option>
                  <option value="21-30">21-30</option>
                  <option value="31-40">31-40</option>
                  <option value="41-50">41-50</option>
                  <option value="51-60">51-60</option>
                  <option value="61-70">61-70</option>
                  <option value="71+">71+</option>
                </select>
                to
                <select name="ageTo">
                  <option value="" selected="selected"></option>
                  <option value="18-20">18-20</option>
                  <option value="21-30">21-30</option>
                  <option value="31-40">31-40</option>
                  <option value="41-50">41-50</option>
                  <option value="51-60">51-60</option>
                  <option value="61-70">61-70</option>
                  <option value="71+">71+</option>
                </select></td>
            </tr>
            <tr>
              <td align="right">Sample Size: </td>
              <td><input name="sampleSize" type="text" size="4" maxlength="4" />
                (up to 9999)</td>
            </tr>
            <tr>
              <td align="right">Closed Ended Questions: </td>
              <td><input name="closedEnded" type="text" size="3" maxlength="3" />
                (up to 999)</td>
            </tr>
            <tr>
              <td align="right">Open Ended Questions: </td>
              <td><input name="openEnded" type="text" size="2" maxlength="2" />
                (up to 99)</td>
            </tr>
            <tr>
              <td align="right">Coding of Open Ends: </td>
              <td><input name="coding" type="radio" value="Yes" />
                Yes
                <input name="coding" type="radio" value="No" />
                No</td>
            </tr>
            <tr>
              <td align="right"><input name="modifiedBy" type="hidden" id="modifiedBy" value="<?php echo $_SESSION['MM_Username']; ?>" /></td>
              <td><input name="Submit" type="submit" onClick="MM_validateForm('firstName','','R','lastName','','R','company','','R','email','','RisEmail');return document.MM_returnValue" value="Submit" /></td>
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
mysql_free_result($rsSampleTypes);
?>
