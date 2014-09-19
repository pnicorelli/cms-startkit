// JavaScript Document

function apri_upload(idR, idTabella)
{
	pagina = "upload_image.php?idTabella="+idTabella+"&chiave_record="+idR
	window.open(pagina,'_blank','width=800, height=580,top=0,left=200,resizable=yes,scrollbars=yes', '');
}

//funzione che apre il calendarietto, passando il nome di: div e campo hidden
function openCalendar(divData, hiddenData) 
{ 
  var calendarwindow; 
  url = "calendario.php?divData="+divData+"&hiddenData="+hiddenData;
  calendarwindow = window.open(url, "thewindow", "toolbar=no,LEFT=300,TOP=400,WIDTH=250,HEIGHT=160,status=no,scrollbars=no,resize=no,menubar=no");
  calendarwindow.focus();
}