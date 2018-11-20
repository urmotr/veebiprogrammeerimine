<?php
	class Test
	{
		private $secretNumber;
		public $publicNumber;
		function __construct($sentNumber){
			$this->secretNumber = 5;
			$this->publicNumber = $this->secretNumber * $sentNumber;
			$this->tellSecret();
		}
		
		function __destruct(){
			echo "Lõpetame!";
		}
		
		private function tellSecret(){
			echo $this->secretNumber;
		}
		public function tellInfo(){
			echo "\nSaladusi ei paljasta!";
		}
	}

?>