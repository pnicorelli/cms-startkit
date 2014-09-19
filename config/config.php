<?php
define("NOME_SITO","LOVeatWELL");
define("NOME_SESSIONE","ww-dev");
define("CHARSET","UTF-8");
define("DB_PREFIX","");
define("DEFAULT_PAGE_SIZE","10");

//costanti per gli indirizzi email da utilizzare
define("EMAIL_MITTENTE_GENERALE", "info@webworking.it");
define("NOME_MITTENTE_GENERALE", "WWDev");
define("EMAIL_SITO", EMAIL_MITTENTE_GENERALE);
define("EMAIL_REGISTRAZIONE", EMAIL_MITTENTE_GENERALE);

//costanti per i link del sito
define("LINK_ROOT", "http://127.0.0.1/loveatwell/");
define("LINK_ADMIN", LINK_ROOT."admin/");
define("LINK_PATH","/var/www/loveatwell/");


//costante per la definizione della cartella in cui vengono salvati i file
define("UPLOAD_FILE", "upload");
define("CARTELLA_IMG", "immagini");
define("CARTELLA_FILE", "documenti");
define("CARTELLA_APPOGGIO_UPLOAD","../upload/tmp/");
define("CARTELLA_IMMAGINI", "/img/");


//costanti per la gestione delle img
define("large_image_prefix","resize_");
define("thumb_image_prefix","thumb_");
define("prefisso_img_originale","uploaded_");
define("MAX_SIZE_UPLOAD_MByte",ini_get("upload_max_filesize"));
define("MAX_SIZE_UPLOAD_Byte",ini_get("upload_max_filesize")*1024*1024);
define("MAX_WIDTH_IMAGES",1100);



?>
