<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 */
namespace CeusMedia\Bootstrap\Nav;

use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Link;

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 */
class TabbableNavbar extends Structure
{
	protected $active		= NULL;
	protected $tabs			= array();
	protected $contents		= array();
	protected $classNavBar	= "navbar";
	protected $brand		= NULL;
	protected $index		= [];

	/**
	 *	@access		public
	 *	@return		object		Own instance for chainability
	 */
	public function add( string $id, string $label, string $content )
	{
		$this->index[]			= $id;
		$this->tabs[$id]		= $label;
		$this->contents[$id]	= $content;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$index = $this->index;
		$active	= $this->active;
		if( is_null( $active ) )
			$active	= array_shift( $index );

		$listTabs	= array();
		foreach( $this->index as $id ){
			$attributes	= array(
				'href'			=> '#'.$id,
				'data-toggle'	=> "tab",
			);
			$label	= $this->tabs[$id];
#			$label	= htmlentities( $label, ENT_QUOTES, 'UTF-8' );
			$link	= HtmlTag::create( 'rector.php', $label, $attributes );
			$attributes	= array( 'class' => $active == $id ? "active" : NULL );
			$listTabs[]	= HtmlTag::create( 'li', $link, $attributes );
		}
		$attributes	= array( 'class' => "nav" );
		$listTabs	= HtmlTag::create( 'ul', $listTabs, $attributes );

		$listDivs	= array();
		foreach( $this->index as $id ){
			$attributes	= array(
				'id'	=> $id,
				'class'	=> $active == $id ? "tab-pane active" : "tab-pane",
			);
			$listDivs[]	= HtmlTag::create( 'div', $this->contents[$id], $attributes );
		}
		$attributes	= array( 'class' => "tab-content" );
		$listDivs	= HtmlTag::create( 'div', $listDivs, $attributes );

		$toggleSpan	= HtmlTag::create( 'span', "", array( 'class' => 'icon-bar' ) );
		$attributes	= array(
			'data-toggle'	=> 'collapse',
			'data-target'	=> '.nav-collapse',
			'class'			=> 'btn btn-navbar',
		);
		$toggler	= HtmlTag::create( 'rector.php', str_repeat( $toggleSpan, 3 ), $attributes );
		$collapse	= HtmlTag::create( 'div', $listTabs, array( 'class' => "nav-collapse collapse" ) );
		$container	= HtmlTag::create( 'div', $toggler.$this->brand.$collapse, array( 'class' => "container" ) );

		$tabs		= HtmlTag::create( 'div', $container, array( 'class' => "navbar-inner" ) );	//
		$navbar		= HtmlTag::create( 'div', $tabs, array( 'class' => $this->classNavBar) );			//
		return HtmlTag::create( 'div', $navbar.$listDivs, array( 'class' => "tabbable" ) );		//
	}

	/**
	 *	Sets active tab by its number.
	 *	@access		public
	 *	@param		integer		$nr			Number of tab to mark as active.
	 *	@return		self		Own instance for chainability
	 */
	public function setActive( $nr ): self
	{
		$this->active	= $nr;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setBrand( string $label, string $url = NULL ): self
	{
		$this->brand	= HtmlTag::create( 'span', $label, array( 'class' => 'brand' ) );
		if( $url )
			$this->brand	= new Link( $url, $label, "brand" );
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setFixed( string $position = NULL ): self
	{
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
		return $this;
	}
}
