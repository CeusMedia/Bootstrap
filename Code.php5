<?php
class CMM_Bootstrap_Code extends CMM_Bootstrap_Abstract{

	protected $scrollable		= FALSE;

	public function __construct( $content, $scrollable = FALSE, $class = NULL ){
		$this->setContent( $content );
		$this->setClass( $class );
		$this->setScrollable( $scrollable );
	}

	public function setScrollable( $scrollable ){
		$this->scrollable	= (bool) $scrollable;
	}

	public function render(){
		$attributes		= array( 'class' => join( " ", $this->class ) );
		if( $this->scrollable )
			$attributes['class']	.= " pre-scrollable";
		return UI_HTML_Tag::create( 'pre', $this->content, $attributes );
	}
}
?>
