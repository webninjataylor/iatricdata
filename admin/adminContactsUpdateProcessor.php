<?php require_once('../../../Connections/conn_iatricdata.php'); ?>
<?php
//*****START SESSION*****
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
//*****END SESSION*****
?>
<?php
mysql_select_db($database_conn_iatricdata, $conn_iatricdata);
//update contact record
$query_updateContact = "UPDATE tblContacts
						SET title = '".$_POST['title']."',
							firstName = '".$_POST['firstName']."',
							lastName = '".$_POST['lastName']."',
							jobTitle = '".$_POST['jobTitle']."',
							company = '".$_POST['company']."',
							address1 = '".$_POST['address1']."',
							address2 = '".$_POST['address2']."',
							city = '".$_POST['city']."',
							stateProv = '".$_POST['stateProv']."',
							zip = '".$_POST['zip']."',
							email = '".$_POST['email']."',
							phone1 = '".$_POST['phone1']."',
							phone2 = '".$_POST['phone2']."',
							phone3 = '".$_POST['phone3']."'
						WHERE contactID = ".$_POST['contactID'];
mysql_query($query_updateContact);
$query_updateRequest = "UPDATE tblRequests
						SET additionalInfo = '".$_POST['additionalInfo']."',
							subject = '".$_POST['subject']."',
							gender = '".$_POST['gender']."',
							ageFrom = '".$_POST['ageFrom']."',
							ageTo = '".$_POST['ageTo']."',
							sampleSize = '".$_POST['sampleSize']."',
							closedEnded = '".$_POST['closedEnded']."',
							openEnded = '".$_POST['openEnded']."',
							coding = '".$_POST['coding']."',
							modifiedBy = '".$_POST['modifiedBy']."'
						WHERE requestID = ".$_POST['requestID'];
mysql_query($query_updateRequest);
//Delete old samples
$query_deleteSamples = "DELETE FROM tblRequestSamples WHERE requestID=".$_POST['requestID'];
$deleteSamples = mysql_query($query_deleteSamples);
//Insert New samples
//Get sample types for processing
$query_rsSamples = "SELECT sampleID, sampleType FROM tblSamples";
$rsSamples = mysql_query($query_rsSamples, $conn_iatricdata) or die(mysql_error());
$row_rsSamples = mysql_fetch_assoc($rsSamples);
$totalRows_rsSamples = mysql_num_rows($rsSamples);
//loop samples and insert with request id as the foreign key
foreach ($_POST['sample'] as $choice => $sampleType) {
	$query_insertRequestSamples = "INSERT INTO tblRequestSamples (requestID, sampleID) VALUES (".$_POST['requestID'].", '".$sampleType."');";
	mysql_query($query_insertRequestSamples);
}
?>
<?php
mysql_free_result($rsSamples);
header("Location: adminContacts.php");
?>