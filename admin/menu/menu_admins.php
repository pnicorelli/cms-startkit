<script language='JavaScript'>
myMenu=[ 

	[null,'Studio','studio_item.php',null,null],
	[null,'Staff','','','',
		[null,'Medici','staff_elenco.php?sezione=medici'],
		[null,'Collaboratori','staff_elenco.php?sezione=collaboratori'],


	],
	[null,'Terapie','terapie_elenco.php',null,null],	
	[null,'Igiene e Sicurezza','igiene_item.php',null,null],
	[null,'Garanzia','garanzia_item.php',null,null],
	[null,'Promozioni','promozioni_elenco.php',null,null],	
	
	
	[null,'Logout','logout.php',null,null],
	
]; 

</script>
	

<SCRIPT LANGUAGE="JavaScript">
	cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
</SCRIPT>
