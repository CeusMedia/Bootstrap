<?php
class CMM_Bootstrap_TabbableNavbar{
	
	protected $active		= NULL;
	protected $tabs			= array();
	protected $contents		= array();
	protected $classNavBar	= "navbar";

	public function add( $id, $label, $content ){
		$this->index[]	= $id;
		$this->tabs[$id]		= $label;
		$this->contents[$id]	= $content;
	}

	public function setFixed( $position = NULL ){
		switch( $position ){
			case 'top':
				$this->classNavBar	= "navbar navbar-fixed-top";
				break;
			case 'bottom':
				$this->classNavBar	= "navbar navbar-fixed-bottom";
				break;
			default:
				$this->classNavBar	= "navbar";
				break;
		}
	}

	public function render( $active = NULL, $label = NULL ){
		if( is_null( $active ) )
			if( is_null( $active = $this->active ) )
				$active	= array_shift( array_values( $this->index ) );

		$listTabs	= array();
		foreach( $this->index as $id ){
			$attributes	= array(
				'href'			=> '#'.$id,
				'data-toggle'	=> "tab",
			);
			$link	= UI_HTML_Tag::create( 'a', $this->tabs[$id], $attributes );
			$attributes	= array( 'class' => $active == $id ? "active" : NULL );
			$listTabs[]	= UI_HTML_Tag::create( 'li', $link, $attributes );
		}
		$attributes	= array( 'class' => "nav" );
		$listTabs	= UI_HTML_Tag::create( 'ul', $listTabs, $attributes );

		$listDivs	= array();
		foreach( $this->index as $id ){
			$attributes	= array(
				'id'	=> $id,
				'class'	=> $active == $id ? "tab-pane active" : "tab-pane",
			);
			$listDivs[]	= UI_HTML_Tag::create( 'div', $this->contents[$id], $attributes );
		}
		$title		= strlen( trim( $label ) ) ? '<span class="brand" href="#">'.$label.'</span>' : "";
		$attributes	= array( 'class' => "tab-content" );
		$listDivs	= UI_HTML_Tag::create( 'div', $listDivs, $attributes );
		$tabs		= UI_HTML_Tag::create( 'div', $title.$listTabs, array( 'class' => "navbar-inner" ) );	//
		$navbar		= UI_HTML_Tag::create( 'div', $tabs, array( 'class' => $this->classNavBar) );			//
		return UI_HTML_Tag::create( 'div', $navbar.$listDivs, array( 'class' => "tabbable" ) );		//
		
	}
}
?>
