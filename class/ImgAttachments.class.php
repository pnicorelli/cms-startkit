<?php

class ImgAttachments extends dboAttachments{
	
	public function __construct($id, $table, $options){
		parent::__construct($id, $table);
	}
	
	public function getAdminBox(){
		if( $this->fk_id > 0 ){
			ob_start();
			?>
			<a href="upload_image.php" >Aggiungi Immagine</a>
			<?php
			$content = ob_get_contents();
			ob_end_clean();
		} else {
			$content = "Salvare il record per caricare immagini";
		}
		return $content;
	}
	
}
