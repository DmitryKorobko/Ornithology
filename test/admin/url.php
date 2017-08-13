<?php

class Url{

	private $address;
	private $desc;
	private $link;
	private $code;

	public function getLink(){
		return $this->code;
	}

	private function formatLink(){
		$this->code = '<a href = "' . $this->address . '" >';
		if($this->link){
			$this->code .= $this->link;
		}else{
			$this->code .= $this->address;
		}
		$this->code .= '</a>';
		if($this->desc){
			$this->code .= '</p>' . $this->desc . '</p>';
		}
	}
}

?>