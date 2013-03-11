<?php
/**
 *	@todo	kriss: finish refactoring
 */
class CMM_Bootstrap_DropdownButton{

	protected $items		= array();
	protected $alignLeft	= TRUE;
	protected $trigger		= NULL;

	public function __construct( $label, CMM_Bootstrap_Dropdown $dropdown, $class = NULL, $caret = TRUE ){
		$this->label	= $label;
		$this->class	= $class;
		$this->dropdown	= $dropdown;
		$this->caret	= $caret;
	}

	public function setAlign( $left = TRUE ){
		$this->alignLeft	= $left;
	}

	public function render(){
		$trigger	= new CMM_Bootstrap_DropdownTrigger( $this->label, $this->class, $this->caret );
		$list		= $this->dropdown->render();
		return UI_HTML_Tag::create( 'div', $trigger.$list, array( 'class' => 'btn-group' ) );
	}

	public function __toString(){
		return $this->render();
	}
}
?>
