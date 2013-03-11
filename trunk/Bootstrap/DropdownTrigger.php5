<?php
class CMM_Bootstrap_DropdownTrigger{

	protected $label;
	protected $class;
	protected $caret;

	public function __construct( $label, $class = NULL, $caret = TRUE ){
		$this->label = $label;
		$this->class	= $class;
		$this->caret	= $caret;
	}

	public function render(){
		$caret	= ' '.UI_HTML_Tag::create( 'span', "", array( 'class' => 'caret' ) );
		if( !$this->caret )
			$caret	= '';
		$button	= new CMM_Bootstrap_Button( $this->label.$caret );
		$button->setClass( 'dropdown-toggle '.$this->class );
		$button->setData( 'toggle', "dropdown" );
		return $button->render();
	}

	public function __toString(){
		return $this->render();
	}
}
?>
