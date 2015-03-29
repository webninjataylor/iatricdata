<?php
//*****START SESSION*****
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
<?php require_once('../../../Connections/conn_iatricdata.php'); ?>
<?php
mysql_select_db($database_conn_iatricdata, $conn_iatricdata);
//Get all requests from database to search for multiple for same contact
$query_rsRequests = "SELECT requestID, contactID FROM tblRequests";
$rsRequests = mysql_query($query_rsRequests, $conn_iatricdata) or die(mysql_error());
$row_rsRequests = mysql_fetch_assoc($rsRequests);
$totalRows_rsRequests = mysql_num_rows($rsRequests);
//Check for multiple requests for the contactID passed in the URL
$contactHasOtherRequests = -1;   //Set contact test variable
do {
	if($_GET['contactID'] == $row_rsRequests['contactID']){   //if contact exists
		$contactHasOtherRequests = $contactHasOtherRequests + 1;   //increment contactHasOtherRequests variable (if only one request, it will = 0)
	}
} while ($row_rsRequests = mysql_fetch_assoc($rsRequests));
//If contact has only one request
if($contactHasOtherRequests == 0){
	$query_deleteContact = "DELETE FROM tblContacts WHERE contactID=".$_GET['contactID'];   //delete the contact too
	$deleteContact = mysql_query($query_deleteContact);
}
//Delete the request samples
$query_deleteSamples = "DELETE FROM tblRequestSamples WHERE requestID=".$_GET['requestID'];
$deleteSamples = mysql_query($query_deleteSamples);
//Delete the request
$query_deleteRequest = "DELETE FROM tblRequests WHERE requestID=".$_GET['requestID'];
$deleteRequest = mysql_query($query_deleteRequest);
?>
<?php
mysql_free_result($rsRequests);
header("Location: adminContacts.php");
?>