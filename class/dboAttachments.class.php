<?php

class dboAttachments  extends DbObject{
	
		public $table = "attachments";	
		public $tableid = "id";	
	
		public $fk_id = "attachments";	
		public $fk_table = "id";	
		
	function __construct($fk_id, $fk_table){
		$this->fk_id = $fk_id;
		$this->fk_table = $fk_table;
	}
	
	function uploadFile($fieldname, $where){
	
		if (!empty($_FILES[$fieldname])) {

			$upload = Upload::factory($where);
			$upload->file($_FILES[$fieldname]);
			
			$upload->set_filename($new_filename);
			
			$results = $upload->upload();
/*
 * $results = array
			  'status' => boolean false
			  'destination' => string 'important/files/' (length=16)
			  'size_in_bytes' => int 466028
			  'size_in_mb' => float 0.44
			  'mime' => string 'application/pdf' (length=15)
			  'original_filename' => string 'About Stacks.pdf' (length=16)
			  'tmp_name' => string '/private/var/tmp/phpXF2V7o' (length=26)
			  'post_data' => 
				array
				  'name' => string 'About Stacks.pdf' (length=16)
				  'type' => string 'application/pdf' (length=15)
				  'tmp_name' => string '/private/var/tmp/phpXF2V7o' (length=26)
				  'error' => int 0
				  'size' => int 466028
			  'errors' => 
				array
				  0 => string 'File name is too long.' (length=22)
*/
		

		}
	
	}
}
