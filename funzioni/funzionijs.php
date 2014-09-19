<script language="javascript" type="text/javascript">
<!--


//FUNZOINI PER IL CONTROLLO DEI MODULI

// validate an email address
function isEmail(strEmail, lblAlert){
	validRegExp = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	if (strEmail.search(validRegExp) == -1) {
	  alert(lblAlert);
	  return false;
	}
	return true;
}


//funzione di validazione email
function valida_email(email)
{
	if((email.indexOf('@')==-1) || (email.indexOf('.')==-1))
	{
		return false;
	}
	else
	{
		pos_at = email.indexOf('@');
		pos_punto = email.indexOf('.', pos_at);
		len_mail = email.length;
		
		pos_dominio = pos_punto-pos_at;
		pos_dominiodue = len_mail - pos_punto;
		
		if(pos_at < 2 || (pos_dominio <= 2) || (pos_dominiodue <= 2))
		{
			return false;
		}
	}
	
	return true;
}



//funzione che viene richiamata per gestire i controlli al submit dei moduli
function controllaeinviamodulo(nomemodulo,arcampicontrollo)
{
	controllo="yes";
	var i = 0;
	while(i<arcampicontrollo.length && controllo=="yes")
	{
		valuecampo=arcampicontrollo[i];
		arvalori=valuecampo.split(":");
		if(arvalori[0]!="-" && arvalori[2]!="dataobbligatoria" && arvalori[2]!="controlladata" && arvalori[2]!="controlloradiobutton")
		{
			if(document.forms[nomemodulo].elements[arvalori[0]].disabled==true)
			{
				document.forms[nomemodulo].elements[arvalori[0]].disabled=false;
				document.forms[nomemodulo].elements[arvalori[0]].style.border="1px solid #7F9DB9";
				document.forms[nomemodulo].elements[arvalori[0]].disabled=true;
			}
			else
			{
				document.forms[nomemodulo].elements[arvalori[0]].style.border="1px solid #7F9DB9";
			}
		}
		
		switch (arvalori[2])
		{
			case "obbligatorio":
				controllo = obbligatorio(nomemodulo,arvalori[0],arvalori[1]);
				break;
			case "numerico":
				controllo = numerico(nomemodulo,arvalori[0],arvalori[1]);
				break;
			case "numerico_int":
				controllo = numerico_int(nomemodulo,arvalori[0],arvalori[1]);
				break;
			case "file_obbligatorio":
				controllo = file_obbligatorio(nomemodulo,arvalori[0],arvalori[1]);
				break;
			case "controllaprivacy":
				controllo = controllaprivacy(nomemodulo,arvalori[0],arvalori[1]);
				break;
			case "dataobbligatoria":
				controllo = dataobbligatoria(nomemodulo,arvalori[0],arvalori[1]);
				break;
			case "controlladata":
				controllo = controlladata(nomemodulo,arvalori[0],arvalori[1]);
				break;
			case "controlloradiobutton":
				controllo = controlloradiobutton(nomemodulo,arvalori[0],arvalori[1]);
				break;
			case "verificaemail":
				controllo = verificaemail(nomemodulo,arvalori[0],arvalori[1]);
				break;
			case "verificaemail_nonobbligatoria":
				if(document.forms[nomemodulo].elements[arvalori[0]].value!="" && document.forms[nomemodulo].elements[arvalori[0]].value!=null)
				{
					controllo = verificaemail(nomemodulo,arvalori[0],arvalori[1]);
				}
				break;
			case "caratteriminimi":
				controllo = caratteriminimi(nomemodulo,arvalori[0],arvalori[1],arvalori[3])
				break;
			case "verificapassword":
				controllo = verificapassword(nomemodulo,arvalori[0],arvalori[1]);
				break;
			case "controllapwd":
				if(document.forms[nomemodulo].acc[0].checked==true)
				{
					controllo = obbligatorio(nomemodulo,'password','password');
				}
				else
				{
					controllo="yes";
				}
				break;
			default:
				alert("errore");
				return false;
				break;
		}
		i++;
	}
	
	if(controllo=="yes")
	{
		return true;
	}
	else
	{
		if(arvalori[0]!="-")
		{
			if(arvalori[2]=="dataobbligatoria" || arvalori[2]=="controlladata")
			{
				document.forms[nomemodulo].elements[arvalori[0] + "_giorno"].focus();
			}
			else if(arvalori[2]!="controlloradiobutton")
			{
				document.forms[nomemodulo].elements[arvalori[0]].style.border="1px solid red";
				document.forms[nomemodulo].elements[arvalori[0]].focus();
			}
		}
		return false;
	}
}

//campo obbligatorio
function obbligatorio(nomeform,nomecampo,nomeetichetta)
{
	tipocampo=document.forms[nomeform].elements[nomecampo].type;

	switch(tipocampo)
	{
		case "select-one":
			return obbligatoriomenuatendina(nomeform,nomecampo,nomeetichetta);
			break;
		default:
			return obbligatoriotext(nomeform,nomecampo,nomeetichetta);
			break;
	}
	if(document.forms[nomeform].elements[nomecampo].value=="" || document.forms[nomeform].elements[nomecampo].value==null)
	{
		alert("Il campo '" + nomeetichetta + "' e' obbligatorio!");
		return "no";
	}
	else
	{
		return "yes";
	}
}

function obbligatorio_condizioni(nomeform,nomecampo,nomeetichetta,campo_condizione,valore_campo_condizione)
{
	/*tipocampo=document.forms[nomeform].elements[nomecampo].type;
	switch(tipocampo)
	{
		case "select-one":
			return obbligatoriomenuatendina(nomeform,nomecampo,nomeetichetta);
			break;
		default:
			return obbligatoriotext(nomeform,nomecampo,nomeetichetta);
			break;
	}*/
	if((document.forms[nomeform].elements[nomecampo].value=="" || document.forms[nomeform].elements[nomecampo].value==null) && document.forms[nomeform].elements[campo_condizione].value==valore_campo_condizione )
	{
		alert("Il campo '" + nomeetichetta + "' e' obbligatorio!");
		return "no";
	}
	else
	{
		return "yes";
	}
}


//valore numerico
function numerico(nomeform,nomecampo,nomeetichetta)
{
	var valoreCampo = document.forms[nomeform].elements[nomecampo].value;

	if(isNaN(valoreCampo))
	{
		alert("Il campo '" + nomeetichetta + "' deve essere di tipo numerico!");
		return "no";
	}
	else
	{
		return "yes";
	}
}


//valore numerico intero
function numerico_int(nomeform,nomecampo,nomeetichetta)
{
	var valoreCampo = document.forms[nomeform].elements[nomecampo].value;

	if(isNaN(valoreCampo) || String(valoreCampo).indexOf(".") != (-1))
	{
		alert("Il campo '" + nomeetichetta + "' deve essere un numero intero!");
		return "no";
	}
	else
	{
		return "yes";
	}
}


//campo obbligatorio
function file_obbligatorio(nomeform,nomecampo,nomeetichetta)
{
	nomecampo_old=nomecampo+"_old"
	tipocampo=document.forms[nomeform].elements[nomecampo].type;

	if((document.forms[nomeform].elements[nomecampo].value=="" || document.forms[nomeform].elements[nomecampo].value==null) && (document.forms[nomeform].elements[nomecampo_old].value==null || document.forms[nomeform].elements[nomecampo_old].value==""))
	{
		alert("Il campo '" + nomeetichetta + "' e' obbligatorio!");
		return "no";
	}
	else
	{
		return "yes";
	}
}

function file_obbligatorio_condizione(nomeform,nomecampo,nomeetichetta,campo_condizione,valore_campo_condizione)
{
	nomecampo_old=nomecampo+"_old"

	if((document.forms[nomeform].elements[nomecampo].value=="" || document.forms[nomeform].elements[nomecampo].value==null) && document.forms[nomeform].elements[campo_condizione].value==valore_campo_condizione)
	{
		alert("Il campo '" + nomeetichetta + "' e' obbligatorio!");
		return "no";
	}
	else
	{
		return "yes";
	}
}

function controllaprivacy(nomeform,nomecampo,testoalert)
{
	if(document.forms[nomeform].elements[nomecampo].checked==false)
	{
		alert(testoalert);
		return "no";
	}
	else
	{
		return "yes";
	}
}

function obbligatoriotext(nomeform,nomecampo,nomeetichetta)
{
	if(document.forms[nomeform].elements[nomecampo].value=="" || document.forms[nomeform].elements[nomecampo].value==null)
	{
		alert("Il campo '" + nomeetichetta + "' e' obbligatorio!");
		return "no";
	}
	else
	{
		return "yes";
	}
}

function obbligatoriomenuatendina(nomeform,nomecampo,nomeetichetta)
{
	if(document.forms[nomeform].elements[nomecampo].selectedIndex==0)
	{
		alert("Il campo '" + nomeetichetta + "' e' obbligatorio!");
		return "no";
	}
	else
	{
		return "yes";
	}
}

//data obbligatoria
function dataobbligatoria(nomeform,nomecampo,nomeetichetta)
{
	if(document.forms[nomeform].elements[nomecampo + "_giorno"].selectedIndex==0 && document.forms[nomeform].elements[nomecampo + "_mese"].selectedIndex==0 && document.forms[nomeform].elements[nomecampo + "_anno"].selectedIndex==0)
	{
		alert("Il campo '" + nomeetichetta + "' e' obbligatorio!");
		return "no";
	}
	else
	{
		return controlladata(nomeform,nomecampo,nomeetichetta);
	}
}

//controllo dalla correttezza della data
function controlladata(nomeform,nomecampo,nomeetichetta)
{
	if(document.forms[nomeform].elements[nomecampo + "_giorno"].selectedIndex==0 && document.forms[nomeform].elements[nomecampo + "_mese"].selectedIndex==0 && document.forms[nomeform].elements[nomecampo + "_anno"].selectedIndex==0)
	{
		return "yes";
	}
	else if(document.forms[nomeform].elements[nomecampo + "_giorno"].selectedIndex==0 || document.forms[nomeform].elements[nomecampo + "_mese"].selectedIndex==0 || document.forms[nomeform].elements[nomecampo + "_anno"].selectedIndex==0)
	{
		alert("Il campo '" + nomeetichetta + "' non e' completo!");
		return "no";
	}
	else
	{
		return controllavaliditadata(nomeform,nomecampo,nomeetichetta);
	}
}

//funzione che controlla la validità di una data
function controllavaliditadata(nomeform,nomecampo,nomeetichetta)
{
	gg=document.forms[nomeform].elements[nomecampo + "_giorno"].selectedIndex;
	if(gg<10)
	{
		gg="0" + gg;
	}
	
	mm=document.forms[nomeform].elements[nomecampo + "_mese"].selectedIndex;
	if(mm<10)
	{
		mm="0" + mm;
	}
	
	aa=document.forms[nomeform].elements[nomecampo + "_anno"].selectedIndex+1999;
	
	strdata=gg+"/"+mm+"/"+aa;
	
	data = new Date(aa,mm-1,gg);
	
	daa=data.getFullYear().toString();
	dmm=(data.getMonth()+1).toString();
	dmm=dmm.length==1?"0"+dmm:dmm
	dgg=data.getDate().toString();
	dgg=dgg.length==1?"0"+dgg:dgg
	dddata=dgg+"/"+dmm+"/"+daa
	if(dddata!=strdata)
	{
      alert("Verificare il campo '" + nomeetichetta + "'");
	  return "no";
	}
	else
	{
		return "yes";
	}
}

//controllo formattazione di un campo mail obbligatorio
function verificaemail(nomeform,nomecampo,nomeetichetta)
{
	if ((document.forms[nomeform].elements[nomecampo].value.indexOf('@')==-1)|| (document.forms[nomeform].elements[nomecampo].value.indexOf('.')==-1))
	{
	 alert("Il campo '" + nomeetichetta + "' non e' valido!");
	 return "no";
	}
	else
	{
		valorecampo = document.forms[nomeform].elements[nomecampo].value;
		pos_at = valorecampo.indexOf('@');
		pos_punto = valorecampo.indexOf('.', pos_at);
		len_mail = valorecampo.length;
		
		pos_dominio = pos_punto-pos_at;
		pos_dominiodue = len_mail - pos_punto;
		
		if(pos_at < 2 || (pos_dominio <= 2) || (pos_dominiodue <= 2))
		{
		 alert("Il campo '" + nomeetichetta + "' non e' valido!");
		 return "no";
		}
		else
			return "yes";
	}
}


//controllo che venga selezionato almeno un radiobutton
function controlloradiobutton(nomeform,nomecampo,nomeetichetta)
{
	oggetto=document.forms[nomeform].elements[nomecampo];
	nselezionati=0;
	if(isNaN(oggetto.length))
	{
		if(oggetto.checked)
		{
			nselezionati++;
		}
	}
	else
	{
		finoa=oggetto.length;
		for(i=0;i<finoa;i++)
		{
			if(oggetto[i].checked)
			{
				nselezionati++;
			}
		}
	}
	
	if(nselezionati==0)
	{
		alert("Non hai selezionato '" + nomeetichetta + "'!");
		return "no";
	}
	else
	{
		return "yes";
	}
}

//funzione per il controllo dell'inserimetno di una nuova password
function verificapassword(nomeform,nomecampo,nomeetichetta)
{
	if(document.forms[nomeform].elements[nomecampo].value!=document.forms[nomeform].elements[nomecampo + "2"].value)
	{
		alert("La Password di controllo e' diversa da quella inserita!");
		return "no";
	}
	else
	{
		return "yes";
	}
}


//funzione che controlla il numero minimo di caratteri per un campo
function caratteriminimi(nomeform,nomecampo,nomeetichetta,controllo)
{
	valore_campo = document.forms[nomeform].elements[nomecampo].value;

	if(valore_campo.length < controllo)
	{
		alert("Il campo "+nomeetichetta+" deve contenere almeno "+controllo+" caratteri");
		return "no";
	}
	else
	{
		return "yes";
	}
}

//FINE FUNZOINI PER IL CONTROLLO DEI MODULI
//*****************************************



//funzione per formattare un importo
function format_number(numero)
{
	numero = numero.toString();

	//controllo se ci sono decimali
	if(numero.indexOf(".")>=0)
	{
		arrNumero = numero.split(".",numero);
		
		if(arrNumero[1].length==2)
		{
			numFormattato = arrNumero[0]+","+arrNumero[1];
		}
		else if(arrNumero[1].length==1)
		{
			numFormattato = arrNumero[0]+","+arrNumero[1]+"0";
		}
		
	} else {  //non ci sono decimali
		numFormattato = numero+",00";
	}
	
	return numFormattato;
}


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

-->
</script>
