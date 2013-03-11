<?php
class CMM_Bootstrap_ButtonToolbar{
	protected $buttons		= array();
	public function __construct( $buttons = array() ){
		$this->add( $buttons );
	}

	public function add( $button ){
		if( is_array( $button ) )
			foreach( $button as $item )
				$this->add( $item );
		else if( $button )
			$this->buttons[]	= $button;
	}

	public function render(){
		$attributes		= array( 'class' => 'btn-toolbar' );
		return UI_HTML_Tag::create( 'div', $this->buttons, $attributes );
	}

	public function __toString(){
		return $this->render();
	}
}
?>
