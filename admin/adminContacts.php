<?php require_once('../../../Connections/conn_iatricdata.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
 session_save_path("/home/users/web/b1923/d5.tt-graph/phpsessions");
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "adminLoggedOff.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
 session_save_path("/home/users/web/b1923/d5.tt-graph/phpsessions");
  session_start();
}
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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsContacts = 10;
$pageNum_rsContacts = 0;
if (isset($_GET['pageNum_rsContacts'])) {
  $pageNum_rsContacts = $_GET['pageNum_rsContacts'];
}
$startRow_rsContacts = $pageNum_rsContacts * $maxRows_rsContacts;

mysql_select_db($database_conn_iatricdata, $conn_iatricdata);
$query_rsContacts = "SELECT * FROM tblContacts INNER JOIN tblRequests ON tblRequests.contactID=tblContacts.contactID ORDER BY company ASC";
$query_limit_rsContacts = sprintf("%s LIMIT %d, %d", $query_rsContacts, $startRow_rsContacts, $maxRows_rsContacts);
$rsContacts = mysql_query($query_limit_rsContacts, $conn_iatricdata) or die(mysql_error());
$row_rsContacts = mysql_fetch_assoc($rsContacts);

if (isset($_GET['totalRows_rsContacts'])) {
  $totalRows_rsContacts = $_GET['totalRows_rsContacts'];
} else {
  $all_rsContacts = mysql_query($query_rsContacts);
  $totalRows_rsContacts = mysql_num_rows($all_rsContacts);
}
$totalPages_rsContacts = ceil($totalRows_rsContacts/$maxRows_rsContacts)-1;

$queryString_rsContacts = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsContacts") == false && 
        stristr($param, "totalRows_rsContacts") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsContacts = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsContacts = sprintf("&totalRows_rsContacts=%d%s", $totalRows_rsContacts, $queryString_rsContacts);
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
        <h1><a href="adminMaster.php" class="link">Admin</a> &gt; Contacts </h1></td>
    </tr>
    <tr valign="top">
      <td style="padding-left:20px; padding-right:20px;"><form name="frmContacts" id="frmContacts" method="post" action="">
          &nbsp;
          <table border="0" cellspacing="0" cellpadding="5">
            <tr>
              <td colspan="2">Total Contact Requests: <?php echo $totalRows_rsContacts ?> </td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td colspan="2" align="right"><input name="btnNewContact" type="button" id="btnNewContact" value="New Contact or Request" onClick="document.location='adminContactsNew.php';" /></td>
            </tr>
            <tr align="center">
              <td class="tableHeader">Company</td>
              <td class="tableHeader">Last Name</td>
              <td class="tableHeader">First Name</td>
              <td class="tableHeader">Subject</td>
              <td class="tableHeader">Details/Update</td>
              <td class="tableHeader">Delete</td>
            </tr>
            <?php do { ?>
              <tr>
                <td><?php echo $row_rsContacts['company']; ?></td>
                <td><?php echo $row_rsContacts['lastName']; ?></td>
                <td><?php echo $row_rsContacts['firstName']; ?></td>
                <td><?php echo $row_rsContacts['subject']; ?></td>
                <td><input name="btnUpdate" type="button" id="btnUpdate" value="Details/Update" onClick='document.location="adminContactsUpdate.php?requestID=<?php echo $row_rsContacts['requestID']; ?>&contactID=<?php echo $row_rsContacts['contactID']; ?>";' /></td>
                <td><input name="btnDelete" type="button" id="btnDelete" value="Delete" onClick='document.location="adminContactsDelete.php?requestID=<?php echo $row_rsContacts['requestID']; ?>&contactID=<?php echo $row_rsContacts['contactID']; ?>";' /></td>
              </tr>
              <?php } while ($row_rsContacts = mysql_fetch_assoc($rsContacts)); ?>
          </table>
          <?php if ($totalPages_rsContacts != 0) { ?>
          <table border="0" width="50%" align="center">
            <tr>
              <td width="23%" align="center"><?php if ($pageNum_rsContacts > 0) { // Show if not first page ?>
                  <a href="<?php printf("%s?pageNum_rsContacts=%d%s", $currentPage, 0, $queryString_rsContacts); ?>"><img src="First.gif" border=0></a>
                  <?php } // Show if not first page ?>
              </td>
              <td width="31%" align="center"><?php if ($pageNum_rsContacts > 0) { // Show if not first page ?>
                  <a href="<?php printf("%s?pageNum_rsContacts=%d%s", $currentPage, max(0, $pageNum_rsContacts - 1), $queryString_rsContacts); ?>"><img src="Previous.gif" border=0></a>
                  <?php } // Show if not first page ?>
              </td>
              <td width="23%" align="center"><?php if ($pageNum_rsContacts < $totalPages_rsContacts) { // Show if not last page ?>
                  <a href="<?php printf("%s?pageNum_rsContacts=%d%s", $currentPage, min($totalPages_rsContacts, $pageNum_rsContacts + 1), $queryString_rsContacts); ?>"><img src="Next.gif" border=0></a>
                  <?php } // Show if not last page ?>
              </td>
              <td width="23%" align="center"><?php if ($pageNum_rsContacts < $totalPages_rsContacts) { // Show if not last page ?>
                  <a href="<?php printf("%s?pageNum_rsContacts=%d%s", $currentPage, $totalPages_rsContacts, $queryString_rsContacts); ?>"><img src="Last.gif" border=0></a>
                  <?php } // Show if not last page ?>
              </td>
            </tr>
          </table>
          <?php } ?>
        </form></td>
    </tr>
  </table>
  <div id="footer"></div>
</div>
<!--end content-->
</body>
</html>
<?php
mysql_free_result($rsContacts);
?>
