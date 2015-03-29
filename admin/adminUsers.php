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
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsUsers = 10;
$pageNum_rsUsers = 0;
if (isset($_GET['pageNum_rsUsers'])) {
  $pageNum_rsUsers = $_GET['pageNum_rsUsers'];
}
$startRow_rsUsers = $pageNum_rsUsers * $maxRows_rsUsers;

mysql_select_db($database_conn_iatricdata, $conn_iatricdata);
$query_rsUsers = "SELECT memberID, memberName, accessLevel, firstName, lastName FROM tblIDusers ORDER BY lastName ASC";
$query_limit_rsUsers = sprintf("%s LIMIT %d, %d", $query_rsUsers, $startRow_rsUsers, $maxRows_rsUsers);
$rsUsers = mysql_query($query_limit_rsUsers, $conn_iatricdata) or die(mysql_error());
$row_rsUsers = mysql_fetch_assoc($rsUsers);

if (isset($_GET['totalRows_rsUsers'])) {
  $totalRows_rsUsers = $_GET['totalRows_rsUsers'];
} else {
  $all_rsUsers = mysql_query($query_rsUsers);
  $totalRows_rsUsers = mysql_num_rows($all_rsUsers);
}
$totalPages_rsUsers = ceil($totalRows_rsUsers/$maxRows_rsUsers)-1;

$queryString_rsUsers = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsUsers") == false && 
        stristr($param, "totalRows_rsUsers") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsUsers = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsUsers = sprintf("&totalRows_rsUsers=%d%s", $totalRows_rsUsers, $queryString_rsUsers);
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
<div id="content" style="width:760px; position:absolute; top:20px; z-index:102; left:50%; margin-left:-380px;"><div id="header"><img src="../images/spacer.gif" alt="Spacer" width="760" height="84" style="margin-bottom:4px;" /></div><table width="760" border="0" cellspacing="0" cellpadding="0">
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
      <td style="padding-top:20px; padding-left:20px; padding-right:20px;"><div id="logOff"><a href="<?php echo $logoutAction ?>" class="link">Log Off</a></div><h1><a href="adminMaster.php" class="link">Admin</a> &gt; Users</h1>
      </td>
    </tr>
    <tr valign="top">
      <td style="padding-left:20px; padding-right:20px;"><form name="frmUsers" id="frmUsers" method="post" action="">
        <table border="0" cellspacing="0" cellpadding="5">
          <tr align="center">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" align="right"><input name="btnNewUser" type="button" id="btnNewUser" value="New User" onClick="document.location='adminUsersNew.php';" /></td>
            </tr>
          <tr align="center">
            <td class="tableHeader">Last Name </td>
            <td class="tableHeader">First Name </td>
            <td class="tableHeader">E-mail (Username) </td>
            <td class="tableHeader">Group</td>
            <td class="tableHeader">Details/Update</td>
            <td class="tableHeader">Delete</td>
          </tr>
          <?php do { ?>
          <tr>
              <td><?php echo $row_rsUsers['lastName']; ?></td>
              <td><?php echo $row_rsUsers['firstName']; ?></td>
              <td><?php echo $row_rsUsers['memberName']; ?></td>
              <td><?php echo $row_rsUsers['accessLevel']; ?></td>
              <td>
              <input name="btnUpdate" type="button" id="btnUpdate" value="Details/Update" onClick='document.location="adminUsersUpdate.php?memberID=<?php echo $row_rsUsers['memberID']; ?>";' /></td>
              <td><input name="btnDelete" type="button" id="btnDelete" value="Delete" onClick='document.location="adminUsersDelete.php?memberID=<?php echo $row_rsUsers['memberID']; ?>";' /></td>
          </tr>
          <?php } while ($row_rsUsers = mysql_fetch_assoc($rsUsers)); ?>
        </table>
		
		
		
      <?php if ($totalPages_rsUsers != 0) { ?>
        <table border="0" width="50%" align="center">
          <tr>
            <td width="23%" align="center"><?php if ($pageNum_rsUsers > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_rsUsers=%d%s", $currentPage, 0, $queryString_rsUsers); ?>"><img src="First.gif" alt="First" border=0></a>
                <?php } // Show if not first page ?>&nbsp;
            </td>
            <td width="31%" align="center"><?php if ($pageNum_rsUsers > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_rsUsers=%d%s", $currentPage, max(0, $pageNum_rsUsers - 1), $queryString_rsUsers); ?>"><img src="Previous.gif" alt="Previous" border=0></a>
                <?php } // Show if not first page ?>&nbsp;
            </td>
            <td width="23%" align="center"><?php if ($pageNum_rsUsers < $totalPages_rsUsers) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_rsUsers=%d%s", $currentPage, min($totalPages_rsUsers, $pageNum_rsUsers + 1), $queryString_rsUsers); ?>"><img src="Next.gif" alt="Next" border=0></a>
                <?php } // Show if not last page ?>&nbsp;
            </td>
            <td width="23%" align="center"><?php if ($pageNum_rsUsers < $totalPages_rsUsers) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_rsUsers=%d%s", $currentPage, $totalPages_rsUsers, $queryString_rsUsers); ?>"><img src="Last.gif" alt="Last" border=0></a>
                <?php } // Show if not last page ?>&nbsp;
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
mysql_free_result($rsUsers);
?>
