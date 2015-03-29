<?php require_once('../../../Connections/conn_iatricdata.php'); ?>
<?php
// *** Validate request to login to this site.
 session_save_path("/home/users/web/b1923/d5.tt-graph/phpsessions");
session_start();

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($accesscheck)) {
  $GLOBALS['PrevUrl'] = $accesscheck;
  session_register('PrevUrl');
}

if (isset($_POST['txtEmail'])) {
  $loginUsername=$_POST['txtEmail'];
  $password=$_POST['txtPassword'];
  $MM_fldUserAuthorization = "accessLevel";
  $MM_redirectLoginSuccess = "adminMaster.php";
  $MM_redirectLoginFailed = "adminLoginFailed.php";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_conn_iatricdata, $conn_iatricdata);
  	
  $LoginRS__query=sprintf("SELECT memberName, accessCode, accessLevel FROM tblIDusers WHERE memberName='%s' AND accessCode='%s'",
  get_magic_quotes_gpc() ? $loginUsername : addslashes($loginUsername), get_magic_quotes_gpc() ? $password : addslashes($password)); 
   
  $LoginRS = mysql_query($LoginRS__query, $conn_iatricdata) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'accessLevel');
    
    //declare two session variables and assign them
    $GLOBALS['MM_Username'] = $loginUsername;
    $GLOBALS['MM_UserGroup'] = $loginStrGroup;	      

    //register the session variables
    session_register("MM_Username");
    session_register("MM_UserGroup");

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
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
      <td style="padding-top:20px; padding-left:20px; padding-right:20px;"><h1>Log On</h1>
      </td>
    </tr>
    <tr valign="top">
      <td style="padding-top:20px; padding-left:20px; padding-right:20px;"><form name="frmLogin" id="frmLogin" method="POST" action="<?php echo $loginFormAction; ?>">
  <table border="0" cellspacing="0" cellpadding="0">
    <tr valign="top">
      <td align="right" style="padding:5px;">E-mail:</td>
      <td style="padding:5px;"><input name="txtEmail" type="text" id="txtEmail" /></td>
    </tr>
    <tr valign="top">
      <td align="right" style="padding:5px;">Password:</td>
      <td style="padding:5px;"><input name="txtPassword" type="password" id="txtPassword" /></td>
    </tr>
    <tr valign="top">
      <td align="right" style="padding:5px;">&nbsp;</td>
      <td style="padding:5px;">
        <input name="Submit" type="submit" onclick="MM_validateForm('txtEmail','','RisEmail','txtPassword','','R');return document.MM_returnValue" value="Submit" />
      </td>
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
