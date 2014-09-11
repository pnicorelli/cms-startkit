<?php 
include("../funzioni/formattajs.php"); 
$width = "400";
?>


<table width="<?php echo $width; ?>" cellspacing="0" cellpadding="3" align="left">

	<tr align="center" valign="middle">

	  <td width="130" align="center"><input type="button" class="bottone_formatta" name="addbbcode0" value="Grassetto"  onClick="bbstyle(0, 'document.<?php print $nome_form; ?>.<?php  print $nome_campo; ?>'); return false;" ></td>

	  <td width="130" align="center"><input type="button" class="bottone_formatta" name="addbbcode4" value="Corsivo" onClick="bbstyle(4, 'document.<?php print $nome_form; ?>.<?php  print $nome_campo; ?>'); return false;" ></td>


	  <td width="130" align="center"><input type="button" class="bottone_formatta" name="addbbcode6" value="Link" onClick="bbstyle(6, 'document.<?php print $nome_form; ?>.<?php  print $nome_campo; ?>'); return false;" ></td>


	  <td align="right">
	<a href="#" title="Note utilizzo Editor" onclick="javascript: if(document.getElementById('note_editor<?php  print $nome_campo; ?>').style.display=='none') { document.getElementById('note_editor<?php  print $nome_campo; ?>').style.display='block'; } else {document.getElementById('note_editor<?php  print $nome_campo; ?>').style.display='none';}; return false;"><img src="img/question_mark.jpg" style="border:0px;"/></a></td>
	</tr>

	<tr>

		<td colspan="6">
		<div id='note_editor<?php  print $nome_campo; ?>' style="width:80;margin:0px; display:none; background-color:#e7eaee; border:1px solid #cccccc; padding:6px 6px 5px 6px; font-size:11px;">
		
		<span style="font-size:10px; color:#333333; font-weight:bold;">
		Per formattare una o più parole come "<i>Grassetto</i>" o "<i>Sottolineato</i>" scrivere il testo, selezionarlo e cliccare sul pulsante corrispondente.
		<br /><br />
		Per inserire un collegamento ad un sito Web nel testo:<br>1) Scrivere il testo che farà da link<br>2) Selezionare il testo e cliccare sul pulsante "Link"<br>3) Al posto del testo "inserisci qui il link" inserire l'indirizzo (completo di http://) facendo attenzione a non cancellare o modificare le virgolette, l'uguale e la scritta "href".<br>Se si desidera che il link non venga aperto in un'altra finestra, eliminare la scritta <i>target="blank"</i> facendo attenzione a cancellare le virgolette prima e dopo <i>blank</i> e a lasciare il minore prima del testo del link		</span>		</div>		</td>
	</tr>
</table>

