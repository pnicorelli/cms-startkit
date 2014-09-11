<?php
require_once("initback.php");
$suffixpage = "localita";
$page = $ww->getInt("page");

$object = new Localita();

//controllo se si sta effettuando una cancellazione
if(isset($_GET["del"]) && $_GET["del"] == "si")
{
	if(isset($_POST["id"]))		
	{
		while (list($indexValue, $cancello) = each ($_POST["id"]))  //indexValue è l'indice dell'array id; cancello è la chiave della voce selezionata
		{ $object->delete($cancello); }
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
		<span class="titoli">Localita - Elenco</span>
		<br />
		<br />
      	<form name="form" method="post">
			
		<?php				 
						
			$array = $object->getPage(" ", " ORDER BY nome", $page);
			$pagine_totali = $object->pages;

			if(empty($array))
			{
				print "<br />Non &egrave; presente nessun record!";
			}
			else
			{
			?>
				  <table width="100%" border="0" cellspacing="2" cellpadding="2">
					<tr class="colore_testata">
                      <td width="7%">&nbsp;</td>	
					  <td class="testo" align="center"><strong>Nome</strong></td>
					  <td class="testo" align="center"><strong>3bmeteo ID</strong></td>
					</tr>
					<?php
					  $sfondo = "colore_uno";
					  
						foreach($array as $item)				  
						{
							$tmp = date("YdmHis") . rand();
							$sfondo = ($sfondo=="colore_uno")?"colore_due":"colore_uno";
							
							print "<tr class='".$sfondo."'><td><input type='checkbox' name='id[]' value='" . $item[$object->tableid] . "'>&nbsp;";
							print "<a href='".$suffixpage."_item.php?id=".$item[$object->tableid]."'><img src='img/modifica.gif' border=0 title='dettaglio'></a></td>";
							print "<td class='testo'>". html_entity_decode($item["nome"], ENT_QUOTES, CHARSET) . "</td>";
							print "<td class='testo'>". html_entity_decode($item["3bmeteo_id"], ENT_QUOTES, CHARSET) . "</td>";
							
							print "</tr>";
						}
					  ?>
				  </table>
				  <br />
				  <div align="center"> <font size="1">
					<?php
					echo "Pagina ". ($page+1) ." di ". $pagine_totali;
					?>
					</font> <font size="1">
					<?php
					if($pagine_totali > 1)
					{	    
						$strCond = "";
					
						if($page==0)
						{
							echo "<<< Inizio&nbsp;&nbsp;&nbsp;&nbsp;<< Indietro&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						}
						else
						{
							echo "<a href=\"".$_SERVER['PHP_SELF']."?page=0".$strCond."\"><u><<< Inizio</u></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"".$_SERVER['PHP_SELF']."?page=".($page-1).$strCond."\"><u><< Indietro</u></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						}
						
						if($page==($pagine_totali - 1))
						{
							echo "Avanti >>&nbsp;&nbsp;&nbsp;&nbsp;Fine>>>";
						}
						else
						{
							echo "<a href=\"".$_SERVER['PHP_SELF']."?page=".($page+1).$strCond."\"><u>Avanti >></u></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"".$_SERVER['PHP_SELF']."?page=".($pagine_totali - 1).$strCond."\"><u>Fine >>></u></a>";
						}
					}
					?>
				  </font></div>
				  <?php
			}
			?>
				  <br />
				  <br />
				  <br />
				  <div align="center">
						
						<?php
						if(!empty($array))
						{
						?>
							<a href="#" onClick="res=confirm('Sei sicuro di voler cancellare le righe selezionate?'); if (res) { document.form.action='<?php echo $_SERVER['PHP_SELF'];?>?del=si'; document.form.submit(); } else return false;"><img src="img/cancel.gif" border="0" alt="cancella" title="cancella"></a>
						<?php
						}
						?>
					&nbsp;&nbsp;&nbsp;<a href="<?php echo $suffixpage;?>_item.php"><img src="img/new.gif" border="0" alt="nuova elemento" title="nuovo elemento"></a>
				 </div>
		
      </form>
      	    <br />
	</div>
	
	<div style="clear:both; height:5px;">
	&nbsp;
	</div>

</div>
</div>
</body>
</html>
