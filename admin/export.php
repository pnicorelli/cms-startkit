<?php
require_once("initback.php");
function get($item, $field){
		return $item[$field];
}

/*
$query = "select * from utenti where username <>''";

$res=mysql_query($query);
$fields = mysql_fetch_field ($res);
*/
$object = new Utenti();
$list = $object->getAll(" and username<>'' ");

$xls = "";

$xls = '<table  style="width:100%">';
$xls .= '<tr>';
$xls .= '<td>username</td>';
$xls .= '<td>data_primoaccesso</td>';
$xls .= '<td>data_ultimoaccesso</td>';
$xls .= '</tr>';

	foreach( $list as $row ){
		$xls .= '<tr>';
		$xls .= '<td>'.get( $row, "username").'</td>';
		$xls .= '<td>'.get( $row, "data_primoaccesso").'</td>';
		$xls .= '<td>'.get( $row, "data_ultimoaccesso").'</td>';
			
		$xls .= '</tr>';
	}
	
$xls .= '</table>';

$filename ="report.xls";
header('Content-type: application/ms-excel');
header('Content-Disposition: attachment; filename='.$filename);
echo $xls;
