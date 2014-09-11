<?php require_once("../init.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php print NOME_SITO?> - Area Amministrativa</title>
<link href="css/stile.css" rel="stylesheet" type="text/css" />

<script language="javascript">

function controlla()
{
	if((document.form.username.value == "" || document.form.username.value == null) || (document.form.password.value == "" || document.form.password.value == null))
	{
		alert("inserire username e password!");
		return false;
	}
	else
	{
		document.form.submit();
	}
}

</script>
</head>

<body>

<div id="contenitore_esterno">
<div id="contenitore">

<table width="100%">
<tr><td align="left">

</td></tr>
<tr>
  <td align="center" height="300">
  <h2><img src="../img/logo.png" /></h2>
  
  <?php
  if(isset($_GET["errore"]) and $_GET["errore"] == "login") 
  {
  	  echo "<span id='titoletto_login'>Errore!<br>Login errato.</span><br><br>";
  }
 
  if(isset($_GET["logout"]) and $_GET["logout"] == "si") 
  {
  	  echo "<span id='titoletto_login'>Logout effettuato correttamente</span><br><br>";
  }
  ?>
  
 <form name="form" method="post" action="controlla_login.php">
  <table width="35%" border="0" cellspacing="2" cellpadding="2" >
    <tr>
      <td>username:</td>
      <td><input type="text" name="username" class="input" /></td>
    </tr>
    <tr>
      <td>password:</td>
      <td><input type="password" name="password" class="input" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center" height="50"><input type="button" name="Submit" value="Login" class="button" onclick="controlla();" /></td>
      </tr>
  </table>
  </form>
  </td>
</tr>
</table>
</div>
</div>
</body>
</html>
