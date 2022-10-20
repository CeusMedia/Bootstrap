<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Nav
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2022 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Nav;

use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Link;
use CeusMedia\Bootstrap\Dropdown\Menu as DropdownMenu;
use CeusMedia\Bootstrap\Dropdown\Trigger\Link as TriggerLink;
use CeusMedia\Common\UI\HTML\Tag as Tag;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Nav
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2022 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Pills extends Structure
{
	protected $active	= -1;
	protected $items	= array();
	protected $stacked	= FALSE;

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component or exception message
	 */
	public function __toString(): string
	{
		try{
			return $this->render();
		}
		catch( \Exception $e ){
			print $e->getMessage();
			exit;
		}
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for method chaining
	 */
	public function add( $url, $label, $class = NULL, $icon = NULL ): self
	{
		$class	= 'nav-link'.( $class ? ' '.$class : '' );
		$link	= new Link( $url, $label, $class, $icon );
		$this->addLink( $link );
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for method chaining
	 */
	public function addLink( Link $link ): self
	{
		$link->addClass( 'nav-link' );
		$this->items[]	= (object) array(
			'type'		=> 'link',
			'link'		=> $link,
			'class'		=> 'nav-item',
		);
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for method chaining
	 *	@todo		rename to addMenu or addDropdownMenu
	 */
	public function addDropdown( DropdownMenu $dropdown, $label, $class = NULL, $icon = NULL, $iconActive = NULL ): self
	{
/*		if( version_compare( $this->bsVersion, 4, '>=' ) )
			$label		= HtmlTag::create( 'a', $label, array(
				'href'			=> '#',
				'class'			=> 'nav-link dropdown-toggle',
				'data-toggle'	=> 'dropdown',
			) );*/
		$this->items[]	= (object) array(
			'type'			=> 'dropdown',
			'label'			=> $label,
			'content'		=> $dropdown,
			'class'			=> 'nav-link'.( $class ? ' '.$class : '' ),
			'icon'			=> $icon,
			'iconActive'	=> $iconActive,
		);
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$items	= array();
		foreach( $this->items as $nr => $item ){
			$class		= $this->active === $nr ? "active" : NULL;
			if( $item->type === "dropdown" ){
				$icon		= $this->active === $nr && $item->iconActive ? $item->iconActive : $item->icon;
				$trigger	= new TriggerLink( $item->label, $item->class, $icon );
				$item		= Tag::create( 'li', $trigger.$item->content, array( 'class' => 'dropdown '.$class ) );
			}
			else{
				$item	= Tag::create( 'li', (string) $item->link, array( 'class' => $class ) );
			}
			$items[]	= $item;
		}
		return Tag::create( 'div', $items, array( 'class' => 'nav nav-pills' ) );
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for method chaining
	 */
	public function setActive( $nr ): self
	{
		$this->active	= $nr;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for method chaining
	 */
	public function setStacked( bool $stacked = TRUE ): self
	{
		$this->stacked	= $stacked;
		return $this;
	}
}
