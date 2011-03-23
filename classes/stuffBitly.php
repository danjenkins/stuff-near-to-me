<?php

require_once('class.bitly.php');

class stuffBitly extends BitLy{

	
	
	function stuffBitly(){
		$this->login = 'stuffneartome';
		$this->apiKey = 'R_630116fe2106ac4dfc4cebd1638ec4e1';
		$this->__construct($this->login, $this->apiKey);
	}
	
}



?>