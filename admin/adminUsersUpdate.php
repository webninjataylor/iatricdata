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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frmUser")) {
  $updateSQL = sprintf("UPDATE tblIDusers SET memberName=%s, accessCode=%s, accessLevel=%s, firstName=%s, lastName=%s, phone=%s, fax=%s, address1=%s, address2=%s, city=%s, `state`=%s, zip=%s, modifiedBy=%s WHERE memberID=%s",
                       GetSQLValueString($_POST['userName'], "text"),
                       GetSQLValueString($_POST['pwd'], "text"),
                       GetSQLValueString($_POST['accessLevel'], "text"),
                       GetSQLValueString($_POST['firstName'], "text"),
                       GetSQLValueString($_POST['lastName'], "text"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['fax'], "text"),
                       GetSQLValueString($_POST['address1'], "text"),
                       GetSQLValueString($_POST['address2'], "text"),
                       GetSQLValueString($_POST['city'], "text"),
                       GetSQLValueString($_POST['state'], "text"),
                       GetSQLValueString($_POST['zip'], "int"),
                       GetSQLValueString($_POST['modifiedBy'], "text"),
                       GetSQLValueString($_POST['userID'], "int"));

  mysql_select_db($database_conn_iatricdata, $conn_iatricdata);
  $Result1 = mysql_query($updateSQL, $conn_iatricdata) or die(mysql_error());

  $updateGoTo = "adminUsers.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsUser = "1";
if (isset($_GET['memberID'])) {
  $colname_rsUser = (get_magic_quotes_gpc()) ? $_GET['memberID'] : addslashes($_GET['memberID']);
}
mysql_select_db($database_conn_iatricdata, $conn_iatricdata);
$query_rsUser = sprintf("SELECT * FROM tblIDusers WHERE memberID = %s", $colname_rsUser);
$rsUser = mysql_query($query_rsUser, $conn_iatricdata) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);
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
      <td style="padding-top:20px; padding-left:20px; padding-right:20px;"><div id="logOff"><a href="<?php echo $logoutAction ?>" class="link">Log Off</a></div><h1><a href="adminMaster.php" class="link">Admin</a> &gt; <a href="adminUsers.php" class="link">Users</a> &gt; Details/Update </h1>
      </td>
    </tr>
    <tr valign="top">
      <td style="padding-left:20px; padding-right:20px;"><form name="frmUser" id="frmUser" method="POST" action="<?php echo $editFormAction; ?>">
        <table border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td>&nbsp;</td>
            <td class="required">* Required </td>
          </tr>
          <tr>
            <td align="right">First Name:<span class="required">*</span> </td>
            <td><input name="firstName" type="text" id="firstName" value="<?php echo $row_rsUser['firstName']; ?>" /></td>
          </tr>
          <tr>
            <td align="right">Last Name:<span class="required">*</span> </td>
            <td><input name="lastName" type="text" id="lastName" value="<?php echo $row_rsUser['lastName']; ?>" /></td>
          </tr>
          <tr>
            <td align="right">E-mail (Username):<span class="required">*</span></td>
            <td><?php echo $row_rsUser['memberName']; ?></td>
          </tr>
          <tr>
            <td align="right">Password:<span class="required">*</span></td>
            <td><input name="pwd" type="password" id="pwd" value="<?php echo $row_rsUser['accessCode']; ?>" /></td>
          </tr>
          <tr>
            <td align="right">Group:<span class="required">*</span></td>
            <td><select name="accessLevel" id="accessLevel">
                <option value="Visitor" <?php if ($row_rsUser['accessLevel'] == 'Visitor') {echo " SELECTED";} ?>>Visitor</option>
                <option value="Admin" <?php if ($row_rsUser['accessLevel'] == 'Admin') {echo " SELECTED";} ?>>Admin</option>
              </select>
		  </td>
          </tr>
          <tr>
            <td align="right">Phone:</td>
            <td><input name="phone" type="text" id="phone" value="<?php echo $row_rsUser['phone']; ?>" /></td>
          </tr>
          <tr>
            <td align="right">Fax:</td>
            <td><input name="fax" type="text" id="fax" value="<?php echo $row_rsUser['fax']; ?>" /></td>
          </tr>
          <tr>
            <td align="right">Address 1:<span class="required">*</span> </td>
            <td><input name="address1" type="text" id="address1" value="<?php echo $row_rsUser['address1']; ?>" /></td>
          </tr>
          <tr>
            <td align="right">Address 2: </td>
            <td><input name="address2" type="text" id="address2" value="<?php echo $row_rsUser['address2']; ?>" /></td>
          </tr>
          <tr>
            <td align="right">City:<span class="required">*</span></td>
            <td><input name="city" type="text" id="city" value="<?php echo $row_rsUser['city']; ?>" /></td>
          </tr>
          <tr>
            <td align="right">State:<span class="required">*</span></td>
            <td><input name="state" type="text" id="state" value="<?php echo $row_rsUser['state']; ?>" /></td>
          </tr>
          <tr>
            <td align="right">Zip:<span class="required">*</span></td>
            <td><input name="zip" type="text" id="zip" value="<?php echo $row_rsUser['zip']; ?>" /></td>
          </tr>
          <tr>
            <td align="right">Last Modified By: </td>
            <td><?php echo $row_rsUser['modifiedBy']; ?></td>
          </tr>
          <tr>
            <td><input name="userID" type="hidden" id="userID" value="<?php echo $row_rsUser['memberID']; ?>" />
			<input name="userName" type="hidden" id="userName" value="<?php echo $row_rsUser['memberName']; ?>" />
			<input name="modifiedBy" type="hidden" id="modifiedBy" value="<?php echo $_SESSION['MM_Username']; ?>" /></td>
            <td><input type="submit" name="Submit" value="Submit" /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="frmUser">
      </form></td>
    </tr>
  </table>
  <div id="footer"></div>
</div>
<!--end content-->
</body>
</html>
<?php
mysql_free_result($rsUser);
?>
