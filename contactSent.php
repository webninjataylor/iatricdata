<?php require_once('../../Connections/conn_iatricdata.php'); ?>
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
							SET firstName = '".$_POST['firstName']."',
								lastName = '".$_POST['lastName']."',
								company = '".$_POST['company']."',
								phone1 = '".$_POST['phone1']."',
								phone2 = '".$_POST['phone2']."',
								phone3 = '".$_POST['phone3']."'
							WHERE contactID = ".$contact;
	mysql_query($query_updateContact);
	insertRequest($contact);   //insert request using contact id
}
function insertContact(){
	//insert contact record
	$query_insertContact = "INSERT INTO tblContacts (firstName, lastName, company, email, phone1, phone2, phone3, modifiedBy)
							VALUES ('".$_POST['firstName']."', '".$_POST['lastName']."', '".$_POST['company']."', '".$_POST['email']."', '".$_POST['phone1']."', '".$_POST['phone2']."', '".$_POST['phone3']."', '".$_POST['email']."');";
	mysql_query($query_insertContact);
	$contact = mysql_insert_id();   //get new contact id
	insertRequest($contact);   //insert request using contact id
}
function insertRequest($contact){
	//insert request using contact id as the foreign key
	$query_insertRequest = "INSERT INTO tblRequests (contactID, additionalInfo, subject, gender, ageFrom, ageTo, sampleSize, closedEnded, openEnded, coding, modifiedBy)
							VALUES (".$contact.", '".$_POST['additionalInfo']."', '".$_POST['subject']."', '".$_POST['gender']."', '".$_POST['ageFrom']."', '".$_POST['ageTo']."', '".$_POST['sampleSize']."', '".$_POST['closedEnded']."', '".$_POST['openEnded']."', '".$_POST['coding']."', '".$_POST['email']."');";
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
//Send email to company and user
$to      = 'Cindy.Steinkamp@IatricData.com; ';
$subject = "Iatric Data: " . $_POST['subject'];
//loop through database samples choices
do {
	foreach ($_POST['sample'] as $choice => $sampleNumber) {   //loop through user's choices
		if($sampleNumber == $row_rsSamples['sampleID']){   //if there is a match
			$samples = $samples.", ".$row_rsSamples['sampleType'];   //get the sample type and add to samples string for email
		}
	}
} while ($row_rsSamples = mysql_fetch_assoc($rsSamples));
$message = $_POST['firstName'] . " " . $_POST['lastName'] . "\n" .
	$_POST['company'] . "\n" .
	$_POST['email'] . "\n" .
	"(" . $_POST['phone1'] . ") " . $_POST['phone2'] . "-" . $_POST['phone3'] . "\n\n" .
	"Regarding: " . $_POST['subject'] . "\n" .
	"Additional Information: " . $_POST['additionalInfo'] . "\n\n" .
	"Sample Type: " . $samples . " " . "\n" .
	"Gender: " . $_POST['gender'] . "\n" .
	"Age: " . $_POST['ageFrom'] . " to " . $_POST['ageTo'] . "\n" .
	"Sample Size: " . $_POST['sampleSize'] . "\n" .
	"Estimated number of closed ended questions: " . $_POST['closedEnded'] . "\n" .
	"Estimated number of open ended questions: " . $_POST['openEnded'] . "\n" .
	"Will you require coding of open ends? " . $_POST['coding'];
$headers  = 'From: ' . $_POST['email'] . "\r\n" . 
			'Cc: ' . $_POST['email'];
mail($to, $subject, $message, $headers);
?>
<!doctype html public "-//w3c//dtd xhtml 1.0 transitional//en" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<title>Iatric Data</title>
<!--smooth page transitions-->
<meta http-equiv="page-enter" content="revealtrans(duration=0,transition=1)" />
<!--start wrappers-->
<link rel="stylesheet" type="text/css" href="assets/iatricdata.css">
<script language="JavaScript" type="text/javascript" src="assets/iatricdata.js"></script>
<!--end wrappers-->
</head><body onLoad="insertHeader(); insertFooter(); MM_preloadImages('images/menuHomeOn.gif','images/menuCompanyOn.gif','images/menuPanelsOn.gif','images/menuServicesOn.gif','images/menuContactOn.gif');">
<!--start content-->
<div id="content" style="width:760px; position:absolute; top:20px; z-index:102; left:50%; margin-left:-380px;"><div id="header"><img src="images/spacer.gif" alt="Spacer" width="760" height="84" style="margin-bottom:4px;" /></div><table width="760" border="0" cellspacing="0" cellpadding="0">
    <tr valign="top">
      <td style="border-right:2px solid #ffffff;"><a href="index.php" onMouseOver="MM_swapImage('menuHome','','images/menuHomeOn.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="images/menuHome.gif" alt="Home" name="menuHome" width="151" height="28" border="0" id="menuHome" /></a></td>
      <td style="border-right:2px solid #ffffff;"><a href="company.php" onMouseOver="MM_swapImage('menuCompany','','images/menuCompanyOn.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="images/menuCompany.gif" alt="Company" name="menuCompany" width="150" height="28" border="0" id="menuCompany" /></a></td>
      <td style="border-right:2px solid #ffffff;"><a href="panels.php" onMouseOver="MM_swapImage('menuPanels','','images/menuPanelsOn.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="images/menuPanels.gif" alt="Panels" name="menuPanels" width="150" height="28" border="0" id="menuPanels" /></a></td>
      <td style="border-right:2px solid #ffffff;"><a href="services.php" onMouseOver="MM_swapImage('menuServices','','images/menuServicesOn.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="images/menuServices.gif" alt="Services" name="menuServices" width="150" height="28" border="0" id="menuServices" /></a></td>
      <td style="border-right:none;"><a href="contact.php"><img src="images/menuContactOn.gif" alt="Contact Us" name="menuContact" width="151" height="28" border="0" id="menuContact" /></a></td>
    </tr>
  </table>
  <table width="760"  border="0" cellspacing="0" cellpadding="0">
    <tr valign="top">
      <td width="303" style="border-top:2px solid #ffffff;"><img src="images/areaContact.jpg" alt="Contact Us image of people" width="303" height="394" /></td>
      <td style="padding-top:20px; padding-left:20px; padding-right:0px;"><p>Thank you for contacting us. A copy has been sent to you as a courtesy for your records. We will respond  in a timely manner.</p>
      </td>
    </tr>
  </table>
  <div id="footer"></div>
</div>
<!--end content-->
</body>
</html>
<?php
mysql_free_result($rsContacts);
mysql_free_result($rsSamples);
?>