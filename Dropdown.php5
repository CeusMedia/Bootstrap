<?php
/**
 *	@todo	kriss: finish refactoring
 */
class CMM_Bootstrap_Dropdown{

	protected $items		= array();
	protected $alignLeft	= TRUE;

	public function add( $item, $disabled = FALSE ){
		$this->items[]	= (object) array(
			'content'	=> (string) $item,
			'submenu'	=> NULL,
			'disabled'	=> $disabled,
		);
	}

	public function addDropdown( $label, CMM_Bootstrap_Dropdown $dropdown, $disabled = FALSE ){
		$this->items[]	= (object) array(
			'content'	=> $label,
			'submenu'	=> $dropdown,
			'disabled'	=> $disabled,
		);
	}

	public function setAlign( $left = TRUE ){
		$this->alignLeft	= $left;
	}

	public function setAriaLabel( $label ){
		$this->ariaLabel	= $label;
	}

	public function render(){
		$list	= array();
		foreach( $this->items as $item ){
			$attributes	= array( 'class' => NULL );# 'class' => 'active' ); 
			if( $item->content === "divider" || preg_match( "/^-+$/", $item->content ) ){
				$item->content = NULL;
				$attributes['class']	= 'divider'; 
			}
			else if( $item->submenu ){
				$attributes['class']	= 'dropdown-submenu';
				$item->content	= $item->content.$item->submenu->render();
			}
			if( $item->disabled )
				$attributes['class']	.= ' disabled'; 
			$list[]	= UI_HTML_Tag::create( 'li', $item->content, $attributes );
		}
		$attributes	= array(
			'class'		=> "dropdown-menu",
		);
		if( !$this->alignLeft )
			$attributes['class']	= $attributes['class'].' pull-right';
		return UI_HTML_Tag::create( 'ul', $list, $attributes );
	}

	public function __toString(){
		return $this->render();
	}
}
?>
