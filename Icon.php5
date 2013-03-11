<?php
class CMM_Bootstrap_Icon{

	public function __construct( $icon, $white = FALSE ){
		$this->icon		= $icon;
		$this->white	= $white;
	}

	public function render(){
		$class	= 'icon-'.$this->icon;
		if( $this->white )
			$class	.= ' icon-white';
		return UI_HTML_Tag::create( 'i', "", array( 'class' => $class ) );
	}

	public function __toString(){
		return $this->render();
	}
}
?>
