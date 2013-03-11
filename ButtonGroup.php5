<?php
class CMM_Bootstrap_ButtonGroup{
	protected $buttons		= array();
	protected $stacked		= FALSE;

	public function __construct( $buttons = array(), $stacked = FALSE ){
		$this->add( $buttons );
		$this->setStacked( $stacked );
	}

	public function add( $button ){
		if( is_array( $button ) )
			foreach( $button as $item )
				$this->add( $item );
		else if( $button )
			$this->buttons[]	= $button;
	}

	public function render(){
		$attributes		= array( 'class' => 'btn-group' );
		if( $this->stacked )
			$attributes['class']	.= ' btn-group-vertical';
		return UI_HTML_Tag::create( 'div', $this->buttons, $attributes );
	}

	public function setStacked( $stacked = TRUE ){
		$this->stacked		= $stacked;
	}

	public function __toString(){
		return $this->render();
	}
}
?>
