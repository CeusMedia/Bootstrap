<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Dropdown;

use CeusMedia\Bootstrap\Base\Aware\AriaAware;
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
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Menu extends Structure
{
	use AriaAware;

	protected $items		= array();
	protected $alignLeft	= TRUE;

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component or exception message
	 */
	public function __toString(): string
	{
		try{
			$string	= $this->render();
			return $string;
		}
		catch( \Exception $e ){
			print $e->getMessage();
			exit;
		}
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function add( $url, $label, $class = NULL, $icon = NULL, $disabled = FALSE ): self
	{
		$this->items[]	= (object) array(
			'type'		=> 'link',
			'content'	=> new Link( $url, $label, $class, $icon ),
/*			'class'		=> $class,
			'icon'		=> $icon,*/
			'disabled'	=> $disabled,
		);
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function addDivider(): self
	{
		$this->items[]	= (object) array(
			'type'		=> 'divider',
			'content'	=> NULL,
		);
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 *	@deprecated				not supported in Bootstrap 4.4, so disabled for all others, too
	 */
	public function addDropdown( $label, Menu $dropdown, $class = NULL, $icon = NULL, $disabled = FALSE ): self
	{
		\trigger_error( 'Not supported in Bootstrap 4.4, so disabled for all others, too', E_USER_DEPRECATED );
/*		$link		= new \CeusMedia\Bootstrap\Link( '#', $label, 'dropdown-item', $icon, $disabled );
		$link->setRole( 'button' );
		if( $class )
			$link->addClass( $class );
		$this->items[]	= (object) array(
			'type'		=> 'dropdown',
			'content'	=> $link,
			'submenu'	=> $dropdown,
			'class'		=> $class,
			'icon'		=> $icon,
			'disabled'	=> $disabled,
		);*/
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function addLink( $link, $disabled = FALSE ): self
	{
		$this->items[]	= (object) array(
			'type'		=> 'link',
			'content'	=> $link,
			'disabled'	=> $disabled,
		);
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$list	= array();
		foreach( $this->items as $item ){
			$attributes	= array( 'class' => NULL );# 'class' => 'active' );
			switch( $item->type ){
				case "dropdown":
					$attributes['class']	= 'dropdown-submenu';
					$item->content	= $item->content.$item->submenu->render();
					break;
				case "divider":
					$attributes['class']	= 'divider';
					break;
				case "link":
					$attributes['class']	= 'dropdown-item';
					break;
				default:
					throw new \OutOfBoundsException( 'Invalid dropdown item time: '.$item->type );
			}
			if( !empty( $item->disabled ) )
				$attributes['class']	.= ' disabled';
			$list[]	= HtmlTag::create( 'li', $item->content, $attributes );
		}
		$attributes	= array(
			'class'		=> "dropdown-menu",
		);
		if( !$this->alignLeft ){
			$additionalClass		= version_compare( $this->bsVersion, '4', '>=' ) ? 'dropdown-menu-right' : 'pull-right';
			$attributes['class']	= $attributes['class'].' '.$additionalClass;
		}
		return HtmlTag::create( 'ul', $list, $attributes );
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setAlign( $left = TRUE ): self
	{
		$this->alignLeft	= $left;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setAriaLabel( $label ): self
	{
		$this->setAria( 'label', $label );
		return $this;
	}
}
