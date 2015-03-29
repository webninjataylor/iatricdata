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
//Get existing contacts to check against
$query_rsContacts = "SELECT contactID, email FROM tblContacts";
$rsContacts = mysql_query($query_rsContacts, $conn_iatricdata) or die(mysql_error());
$row_rsContacts = mysql_fetch_assoc($rsContacts);
$totalRows_rsContacts = mysql_num_rows($rsContacts);
//Get sample types for processing
$query_rsSamples = "SELECT sampleID, sampleType FROM tblSamples";
$rsSamples = mysql_query($query_rsSamples, $conn_iatricdata) or die(mysql_error());
$row_rsSamples = mysql_fetch_assoc($rsSamples);
$totalRows_rsSamples = mysql_num_rows($rsSamples);
//Run tests and insert data
$doesContactExist = -1;   //Set contact test variable
do {
	if($_POST['email'] == $row_rsContacts['email']){   //if contact exists
		$doesContactExist = $row_rsContacts['contactID'];   //set variable to contactID
		updateContact($doesContactExist);   //update existing record
	}
} while ($row_rsContacts = mysql_fetch_assoc($rsContacts));
if($doesContactExist == -1){   //if contact does not exist
	insertContact();   //insert new contact
}
function updateContact($contact){
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
								phone1 = '".$_POST['phone1']."',
								phone2 = '".$_POST['phone2']."',
								phone3 = '".$_POST['phone3']."'
							WHERE contactID = ".$contact;
	mysql_query($query_updateContact);
	insertRequest($contact);   //insert request using contact id
}
function insertContact(){
	//insert contact record
	$query_insertContact = "INSERT INTO tblContacts (title, firstName, lastName, jobTitle, company, address1, address2, city, stateProv, zip, email, phone1, phone2, phone3, modifiedBy)
							VALUES ('".$_POST['title']."', '".$_POST['firstName']."', '".$_POST['lastName']."', '".$_POST['jobTitle']."', '".$_POST['company']."', '".$_POST['address1']."', '".$_POST['address2']."', '".$_POST['city']."', '".$_POST['stateProv']."', '".$_POST['zip']."', '".$_POST['email']."', '".$_POST['phone1']."', '".$_POST['phone2']."', '".$_POST['phone3']."', '".$_POST['modifiedBy']."');";
	mysql_query($query_insertContact);
	$contact = mysql_insert_id();   //get new contact id
	insertRequest($contact);   //insert request using contact id
}
function insertRequest($contact){
	//insert request using contact id as the foreign key
	$query_insertRequest = "INSERT INTO tblRequests (contactID, additionalInfo, subject, gender, ageFrom, ageTo, sampleSize, closedEnded, openEnded, coding, modifiedBy)
							VALUES (".$contact.", '".$_POST['additionalInfo']."', '".$_POST['subject']."', '".$_POST['gender']."', '".$_POST['ageFrom']."', '".$_POST['ageTo']."', '".$_POST['sampleSize']."', '".$_POST['closedEnded']."', '".$_POST['openEnded']."', '".$_POST['coding']."', '".$_POST['modifiedBy']."');";
	mysql_query($query_insertRequest);
	$request = mysql_insert_id();
	//loop samples and insert with request id as the foreign key
	foreach ($_POST['sample'] as $choice => $sampleType) {
		$query_insertRequestSamples = "INSERT INTO tblRequestSamples (requestID, sampleID) VALUES (".$request.", '".$sampleType."');";
		mysql_query($query_insertRequestSamples);
	}
}
?>
<?php
mysql_free_result($rsContacts);
mysql_free_result($rsSamples);
header("Location: adminContacts.php");
?>
