<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2013 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@since			0.3.0
 *	@version		$Id$
 */
namespace CeusMedia\Bootstrap;
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2013 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@since			0.3.0
 *	@version		$Id$
 */
class TabbableNavbar{

	protected $active		= NULL;
	protected $tabs			= array();
	protected $contents		= array();
	protected $classNavBar	= "navbar";
	protected $brand		= NULL;

	public function __toString(){
		return $this->render();
	}

	public function add( $id, $label, $content ){
		$this->index[]			= $id;
		$this->tabs[$id]		= $label;
		$this->contents[$id]	= $content;
	}

	public function render( $active = NULL ){
		$index = $this->index;
		if( is_null( $active ) )
			if( is_null( $active = $this->active ) )
				$active	= array_shift( $index );

		$listTabs	= array();
		foreach( $this->index as $id ){
			$attributes	= array(
				'href'			=> '#'.$id,
				'data-toggle'	=> "tab",
			);
			$label	= $this->tabs[$id];
#			$label	= htmlentities( $label, ENT_QUOTES, 'UTF-8' );
			$link	= \UI_HTML_Tag::create( 'a', $label, $attributes );
			$attributes	= array( 'class' => $active == $id ? "active" : NULL );
			$listTabs[]	= \UI_HTML_Tag::create( 'li', $link, $attributes );
		}
		$attributes	= array( 'class' => "nav" );
		$listTabs	= \UI_HTML_Tag::create( 'ul', $listTabs, $attributes );

		$listDivs	= array();
		foreach( $this->index as $id ){
			$attributes	= array(
				'id'	=> $id,
				'class'	=> $active == $id ? "tab-pane active" : "tab-pane",
			);
			$listDivs[]	= \UI_HTML_Tag::create( 'div', $this->contents[$id], $attributes );
		}
		$attributes	= array( 'class' => "tab-content" );
		$listDivs	= \UI_HTML_Tag::create( 'div', $listDivs, $attributes );

		$toggleSpan	= \UI_HTML_Tag::create( 'span', "", array( 'class' => 'icon-bar' ) );
		$attributes	= array(
			'data-toggle'	=> 'collapse',
			'data-target'	=> '.nav-collapse',
			'class'			=> 'btn btn-navbar',
		);
		$toggler	= \UI_HTML_Tag::create( 'a', str_repeat( $toggleSpan, 3 ), $attributes );
		$collapse	= \UI_HTML_Tag::create( 'div', $listTabs, array( 'class' => "nav-collapse collapse" ) );
		$container	= \UI_HTML_Tag::create( 'div', $toggler.$this->brand.$collapse, array( 'class' => "container" ) );

		$tabs		= \UI_HTML_Tag::create( 'div', $container, array( 'class' => "navbar-inner" ) );	//
		$navbar		= \UI_HTML_Tag::create( 'div', $tabs, array( 'class' => $this->classNavBar) );			//
		return \UI_HTML_Tag::create( 'div', $navbar.$listDivs, array( 'class' => "tabbable" ) );		//
	}

	/**
	 *	Sets active tab by its number.
	 *	@access		public
	 *	@param		integer		$nr			Number of tab to mark as active.
	 *	@return
	 */
	public function setActive( $nr ){
		$this->active	= $nr;
	}

	public function setBrand( $label, $url = NULL ){
		$this->brand	= \UI_HTML_Tag::create( 'span', $label, array( 'class' => 'brand' ) );
		if( $url )
			$this->brand	= new Link( $url, $label, "brand" );
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
}
?>
