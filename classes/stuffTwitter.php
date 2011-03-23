<?php

	require_once('class.twitter.php');
	
	class stuffTwitter extends twitter{
		
		//this way it keeps these settings outside of the twitter class!
		function stuffTwitter(){
			$this->username = 'stuffneartome';
			$this->password = 'Stuffn3ar2me';
			$this->user_agent = 'Stuff Near To Me - admin@stuffnearto.me';
		}
		
	}

?>