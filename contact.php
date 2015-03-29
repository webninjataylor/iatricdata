<?php require_once('../../Connections/conn_iatricdata.php'); ?>
<?php
mysql_select_db($database_conn_iatricdata, $conn_iatricdata);
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
<link rel="stylesheet" type="text/css" href="assets/iatricdata.css">
<script language="JavaScript" type="text/javascript" src="assets/iatricdata.js"></script>
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
</head><body onLoad="insertHeader(); insertFooter(); styleButtons(); MM_preloadImages('images/menuHomeOn.gif','images/menuCompanyOn.gif','images/menuPanelsOn.gif','images/menuServicesOn.gif','images/menuContactOn.gif');">
<!--start content-->
<div id="content" style="width:760px; position:absolute; top:20px; z-index:102; left:50%; margin-left:-380px;"><div id="header"><img src="images/spacer.gif" alt="Spacer" width="760" height="84" style="margin-bottom:4px;" /></div>
<table width="760" border="0" cellspacing="0" cellpadding="0">
    <tr valign="top">
      <td style="border-right:2px solid #ffffff;"><a href="index.php" onMouseOver="MM_swapImage('menuHome','','images/menuHomeOn.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="images/menuHome.gif" alt="Home" name="menuHome" width="151" height="28" border="0" id="menuHome" /></a></td>
      <td style="border-right:2px solid #ffffff;"><a href="company.php" onMouseOver="MM_swapImage('menuCompany','','images/menuCompanyOn.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="images/menuCompany.gif" alt="Company" name="menuCompany" width="150" height="28" border="0" id="menuCompany" /></a></td>
      <td style="border-right:2px solid #ffffff;"><a href="panels.php" onMouseOver="MM_swapImage('menuPanels','','images/menuPanelsOn.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="images/menuPanels.gif" alt="Panels" name="menuPanels" width="150" height="28" border="0" id="menuPanels" /></a></td>
      <td style="border-right:2px solid #ffffff;"><a href="services.php" onMouseOver="MM_swapImage('menuServices','','images/menuServicesOn.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="images/menuServices.gif" alt="Services" name="menuServices" width="150" height="28" border="0" id="menuServices" /></a></td>
      <td style="border-right:none;"><a href="contact.php"><img src="images/menuContactOn.gif" alt="Contact Us" name="menuContact" width="151" height="28" border="0" id="menuContact" /></a></td>
    </tr>
  </table>
  <form name="frm_contact" id="frm_contact" method="post" action="contactSent.php">
  <table width="760"  border="0" cellspacing="0" cellpadding="0">
    <tr valign="top">
      <td width="303" style="border-top:2px solid #ffffff;"><img src="images/areaContact.jpg" alt="Contact Us image of people" width="303" height="394" />
      </td>
      <td style="padding-top:20px; padding-left:20px; padding-right:0px;"><p>Please complete the following contact information and one of our account managers will contact you within one business day.</p>
        <p class="required">* Required Fields</p>
		<table width="439" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td id="step1Tab" class="stepTabOn"><a href="JavaScript:toggleSteps('step1');">Step 1: Contact Information</a><span class="required">*</span></td>
    <td id="step2Tab" class="stepTabOff"><a href="JavaScript:toggleSteps('step2');">Step 2: Project Information</a></td>
  </tr>
</table>
<div id="step1" style="display:block;">
        <table width="437" border="0" cellpadding="3" cellspacing="0" bgcolor="#EEEEEE">
            <tr>
              <td align="right" valign="top"><img src="images/spacer.gif" width="1" height="1" /></td>
              <td valign="top"><img src="images/spacer.gif" width="1" height="1" /></td>
            </tr>
            <tr>
              <td width="150" align="right" valign="top">First Name:<span class="required">*</span></td>
              <td width="275" valign="top"><input name="firstName" type="text" id="firstName" style="width:200px;" maxlength="30" /></td>
            </tr>
            <tr>
              <td align="right" valign="top">Last Name:<span class="required">*</span></td>
              <td><input name="lastName" type="text" id="lastName" style="width:200px;" maxlength="30" /></td>
            </tr>
            <tr>
              <td align="right" valign="top">Company:<span class="required">*</span></td>
              <td><input name="company" type="text" id="company" style="width:200px;" maxlength="30" /></td>
            </tr>
            <tr>
              <td align="right" valign="top">E-mail Address:<span class="required">*</span></td>
              <td><input name="email" type="text" id="email" style="width:200px;" /></td>
            </tr>
            <tr>
              <td align="right" style="padding:3px;">Phone:</td>
              <td style="padding:3px;"><script language="JavaScript" type="text/javascript">
				function phoneTab(phoneValue, phonePart){
					if((phonePart == 'phone1')&&(phoneValue.length == document.frm_contact(phonePart).maxLength)){
						document.frm_contact.phone2.focus();
					}
					else if((phonePart == 'phone2')&&(phoneValue.length == document.frm_contact(phonePart).maxLength)){
						document.frm_contact.phone3.focus();
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
            <tr>
              <td align="right" valign="top" style="padding:3px;">Regarding:<span class="required">*</span></td>
              <td width="275" valign="top" style="padding:3px;">
  <select name="subject" id="subject" style="width:200px;">
    <option value="Marketing Research" selected="selected">Marketing Research</option>
	<option value="Disease Management">Disease Management</option>
	<option value="Clinical Trial">Clinical Trial</option>
	<option value="iPatient Profiles">iPatient Profiles</option>
  </select></td>
            </tr>
            <tr>
              <td align="right" valign="top" style="padding:3px;">&nbsp;</td>
              <td valign="top" style="padding:3px;"><input type="button" name="Continue" value="Continue" onClick="toggleSteps('step2');" /></td>
            </tr>
			<tr>
              <td align="right" valign="top"><img src="images/spacer.gif" width="1" height="1" /></td>
              <td valign="top"><img src="images/spacer.gif" width="1" height="1" /></td>
            </tr>
          </table>
		</div><div id="step2" style="display:none;"><table width="437" border="0" cellpadding="3" cellspacing="0" bgcolor="#EEEEEE">
            <tr>
              <td align="right" valign="top"><img src="images/spacer.gif" width="1" height="1" /></td>
              <td valign="top"><img src="images/spacer.gif" width="1" height="1" /></td>
            </tr>
			<tr>
              <td width="150" align="right" valign="top" style="padding:3px;">Sample Type:</td>
              <td width="275" style="padding:3px;"><select name="sample[]" size="2" multiple="multiple" id="sample[]" style="width:200px;">
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
              </select><br />
              (Ctrl+Click to select multiple samples)</td>
            </tr>
            <tr>
              <td align="right" style="padding:3px;">Sample Gender:</td>
              <td style="padding:3px;"><input name="gender" type="radio" value="Male" />
Male
  <input name="gender" type="radio" value="Female" />
Female
<input name="gender" type="radio" value="Both" />
Both</td>
            </tr>
            <tr>
              <td align="right" style="padding:3px;">Sample Age:</td>
              <td style="padding:3px;"><select name="ageFrom">
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
              <td align="right" style="padding:3px;">Sample Size:</td>
              <td style="padding:3px;"><input name="sampleSize" type="text" size="4" maxlength="4" />
(up to 9999)</td>
            </tr>
            <tr valign="top">
              <td align="right" style="padding:3px;">Estimated number of closed ended questions:</td>
              <td style="padding:3px;"><input name="closedEnded" type="text" size="3" maxlength="3" />
(up to 999)</td>
            </tr>
            <tr valign="top">
              <td align="right" style="padding:3px;">Estimated number of open ended questions:</td>
              <td style="padding:3px;"><input name="openEnded" type="text" size="2" maxlength="2" />
(up to 99)</td>
            </tr>
            <tr valign="top">
              <td align="right" style="padding:3px;">Will you require coding of open ends?</td>
              <td style="padding:3px;"><input name="coding" type="radio" value="Yes" />
Yes
  <input name="coding" type="radio" value="No" />
No</td>
            </tr>
            <tr>
              <td align="right" valign="top" style="padding:3px;">Additional Information:</td>
              <td style="padding:3px;"><textarea name="additionalInfo" rows="3" id="additionalInfo" style="width:250px;"></textarea></td>
            </tr>
            <tr>
              <td align="right" valign="top" style="padding:3px;">&nbsp;</td>
              <td style="padding:3px;"><input name="Submit" type="submit" onClick="MM_validateForm('firstName','','R','lastName','','R','company','','R','email','','RisEmail');return document.MM_returnValue" value="Submit" /></td>
            </tr>
			<tr>
              <td align="right" valign="top"><img src="images/spacer.gif" width="1" height="1" /></td>
              <td valign="top"><img src="images/spacer.gif" width="1" height="1" /></td>
            </tr>
          </table>
		</div><br /><br />
      </td>
    </tr>
  </table>
  </form>
  <div id="footer"></div>
</div>
<!--end content-->
</body>
</html>
<?php
mysql_free_result($rsSampleTypes);
?>
