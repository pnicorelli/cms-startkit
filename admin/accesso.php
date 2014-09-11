<?php
require_once("initback.php");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
require_once("header_admin.php");
?>
</head>

<body>
<div id="contenitore_esterno">
<div id="contenitore">
	<!-- testata -->
	<div id="head"><?php include("testata.php"); ?></div>
	
	<!-- menu in alto -->
	<DIV ID=myMenuID>
	<?php include("menu/menu.php");?>
	</DIV>		

	<!-- contenuti della pagina -->
	<div id="content">
		<div align="center" style="padding-top:20px;"><span class="titoli">Benvenuto  </span><br />
      	    <br />
        	E' ora possibile accedere alle varie sezioni dell'area amministrativa.
		</div>
	</div>
	
	<div style="clear:both; height:5px;">
	&nbsp;
	</div>

</div>
</div>
</body>
</html>
