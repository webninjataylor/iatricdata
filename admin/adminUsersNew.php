<?php require_once('../../../Connections/conn_iatricdata.php'); ?>
<?php
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
?>
<?php
// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  //Set cookie with values for repopulation if username exists
  setcookie('txtFirstName', $_POST['txtFirstName'], time() + (60*60*24));
  setcookie('txtLastName', $_POST['txtLastName'], time() + (60*60*24));
  setcookie('txtPhone', $_POST['txtPhone'], time() + (60*60*24));
  setcookie('txtFax', $_POST['txtFax'], time() + (60*60*24));
  setcookie('txtAddress1', $_POST['txtAddress1'], time() + (60*60*24));
  setcookie('txtAddress2', $_POST['txtAddress2'], time() + (60*60*24));
  setcookie('txtCity', $_POST['txtCity'], time() + (60*60*24));
  setcookie('txtState', $_POST['txtState'], time() + (60*60*24));
  setcookie('txtZip', $_POST['txtZip'], time() + (60*60*24));
  $MM_dupKeyRedirect="adminUsersNew.php?error=userExists";
  $loginUsername = $_POST['txtEmail'];
  $LoginRS__query = "SELECT memberName FROM tblIDusers WHERE memberName='" . $loginUsername . "'";
  mysql_select_db($database_conn_iatricdata, $conn_iatricdata);
  $LoginRS=mysql_query($LoginRS__query, $conn_iatricdata) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmNewUser")) {
  $insertSQL = sprintf("INSERT INTO tblIDusers (memberName, accessCode, accessLevel, firstName, lastName, phone, fax, address1, address2, city, `state`, zip, modifiedBy) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['txtEmail'], "text"),
                       GetSQLValueString($_POST['txtPwd'], "text"),
                       GetSQLValueString($_POST['selGroup'], "text"),
                       GetSQLValueString($_POST['txtFirstName'], "text"),
                       GetSQLValueString($_POST['txtLastName'], "text"),
                       GetSQLValueString($_POST['txtPhone'], "text"),
                       GetSQLValueString($_POST['txtFax'], "text"),
                       GetSQLValueString($_POST['txtAddress1'], "text"),
                       GetSQLValueString($_POST['txtAddress2'], "text"),
                       GetSQLValueString($_POST['txtCity'], "text"),
                       GetSQLValueString($_POST['txtState'], "text"),
                       GetSQLValueString($_POST['txtZip'], "int"),
                       GetSQLValueString($_POST['modifiedBy'], "text"));

  mysql_select_db($database_conn_iatricdata, $conn_iatricdata);
  $Result1 = mysql_query($insertSQL, $conn_iatricdata) or die(mysql_error());

  $insertGoTo = "adminUsers.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
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
</head><body onload="insertHeaderAdmin(); insertFooter(); styleButtons(); MM_preloadImages('../images/menuHomeOn.gif','../images/menuCompanyOn.gif','../images/menuPanelsOn.gif','../images/menuServicesOn.gif','../images/menuContactOn.gif')">
<!--start content-->
<div id="content" style="width:760px; position:absolute; top:20px; z-index:102; left:50%; margin-left:-380px;"><div id="header"><img src="../images/spacer.gif" alt="Spacer" width="760" height="84" style="margin-bottom:4px;" /></div><table width="760" border="0" cellspacing="0" cellpadding="0">
    <tr valign="top">
      <td style="border-right:2px solid #ffffff;"><a href="../index.php" onmouseover="MM_swapImage('menuHome','','../images/menuHomeOn.gif',1)" onmouseout="MM_swapImgRestore()"><img src="../images/menuHome.gif" alt="Home" name="menuHome" width="151" height="28" border="0" id="menuHome" /></a></td>
      <td style="border-right:2px solid #ffffff;"><a href="../company.php" onmouseover="MM_swapImage('menuCompany','','../images/menuCompanyOn.gif',1)" onmouseout="MM_swapImgRestore()"><img src="../images/menuCompany.gif" alt="Company" name="menuCompany" width="150" height="28" border="0" id="menuCompany" /></a></td>
      <td style="border-right:2px solid #ffffff;"><a href="../panels.php" onmouseover="MM_swapImage('menuPanels','','../images/menuPanelsOn.gif',1)" onmouseout="MM_swapImgRestore()"><img src="../images/menuPanels.gif" alt="Panels" name="menuPanels" width="150" height="28" border="0" id="menuPanels" /></a></td>
      <td style="border-right:2px solid #ffffff;"><a href="../services.php" onmouseover="MM_swapImage('menuServices','','../images/menuServicesOn.gif',1)" onmouseout="MM_swapImgRestore()"><img src="../images/menuServices.gif" alt="Services" name="menuServices" width="150" height="28" border="0" id="menuServices" /></a></td>
      <td style="border-right:none;"><a href="../contact.php" onmouseover="MM_swapImage('menuContact','','../images/menuContactOn.gif',1)" onmouseout="MM_swapImgRestore()"><img src="../images/menuContact.gif" alt="Contact Us" name="menuContact" width="151" height="28" border="0" id="menuContact" /></a></td>
    </tr>
  </table>
  <table width="760"  border="0" cellspacing="0" cellpadding="0">
    <tr valign="top">
      <td style="padding-top:20px; padding-left:20px; padding-right:20px;"><div id="logOff"><a href="<?php echo $logoutAction ?>" class="link">Log Off</a></div><h1><a href="adminMaster.php" class="link">Admin</a> &gt; <a href="adminUsers.php" class="link">Users</a> &gt; New User </h1>
      </td>
    </tr>
    <tr valign="top">
      <td style="padding-left:20px; padding-right:20px;">
	  <form name="frmNewUser" id="frmNewUser" method="POST" action="<?php echo $editFormAction; ?>">
        <table border="0" cellspacing="0" cellpadding="3">
          <tr>
            <td align="right">&nbsp;</td>
            <td class="required">* Required </td>
          </tr>
          <tr>
            <td align="right">First Name:<span class="required">*</span> </td>
            <td><input name="txtFirstName" type="text" id="txtFirstName" value="<?php echo $_COOKIE['txtFirstName']; ?>" /></td>
          </tr>
          <tr>
            <td align="right">Last Name:<span class="required">*</span> </td>
            <td><input name="txtLastName" type="text" id="txtLastName" value="<?php echo $_COOKIE['txtLastName']; ?>" /></td>
          </tr>
          <tr>
            <td align="right">E-mail (Username):<span class="required">*</span></td>
            <td><input name="txtEmail" type="text" id="txtEmail" />
			<?php
	  	if ($_GET['error'] == "userExists")
		{
			echo "<span class='error'>User already exists.</span>";
		}
	  ?>
	  </td>
          </tr>
          <tr>
            <td align="right">Password:<span class="required">*</span></td>
            <td><input name="txtPwd" type="password" id="txtPwd" /></td>
          </tr>
          <tr>
            <td align="right">Group:<span class="required">*</span></td>
            <td><select name="selGroup" id="selGroup">
                <option value="Visitor" selected="selected">Visitor</option>
                <option value="Admin">Admin</option>
              </select></td>
          </tr>
          <tr>
            <td align="right">Phone:</td>
            <td><input name="txtPhone" type="text" id="txtPhone" value="<?php echo $_COOKIE['txtPhone']; ?>" /></td>
          </tr>
          <tr>
            <td align="right">Fax:</td>
            <td><input name="txtFax" type="text" id="txtFax" value="<?php echo $_COOKIE['txtFax']; ?>" /></td>
          </tr>
          <tr>
            <td align="right">Address 1:<span class="required">*</span> </td>
            <td><input name="txtAddress1" type="text" id="txtAddress1" value="<?php echo $_COOKIE['txtAddress1']; ?>" /></td>
          </tr>
          <tr>
            <td align="right">Address 2: </td>
            <td><input name="txtAddress2" type="text" id="txtAddress2" value="<?php echo $_COOKIE['txtAddress2']; ?>" /></td>
          </tr>
          <tr>
            <td align="right">City:<span class="required">*</span></td>
            <td><input name="txtCity" type="text" id="txtCity" value="<?php echo $_COOKIE['txtCity']; ?>" /></td>
          </tr>
          <tr>
            <td align="right">State:<span class="required">*</span></td>
            <td><input name="txtState" type="text" id="txtState" value="<?php echo $_COOKIE['txtState']; ?>" /></td>
          </tr>
          <tr>
            <td align="right">Zip:<span class="required">*</span></td>
            <td><input name="txtZip" type="text" id="txtZip" value="<?php echo $_COOKIE['txtZip']; ?>" /></td>
          </tr>
          <tr>
            <td align="right"><input name="modifiedBy" type="hidden" id="modifiedBy" value="<?php echo $_SESSION['MM_Username']; ?>" /></td>
            <td><input name="Submit" type="submit" onclick="MM_validateForm('txtFirstName','','R','txtLastName','','R','txtEmail','','RisEmail','txtPwd','','R','txtAddress1','','R','txtCity','','R','txtState','','R','txtZip','','R');return document.MM_returnValue" value="Submit" /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="frmNewUser">
      </form></td>
    </tr>
  </table>
  <div id="footer"></div>
</div>
<!--end content-->
</body>
</html>
