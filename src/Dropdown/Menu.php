<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2024 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Dropdown;

use CeusMedia\Bootstrap\Base\Aware\AriaAware;
use CeusMedia\Bootstrap\Base\DataObject\MenuItem;
use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Icon;
use CeusMedia\Bootstrap\Link;
use CeusMedia\Common\Renderable;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use OutOfBoundsException;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2024 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Menu extends Structure
{
	use AriaAware;

	/** @var array<MenuItem> $items */
	protected array $items		= [];

	protected bool $alignLeft	= TRUE;

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
	/**
	 *	Constructor.
	 *	@param		string				$url
	 *	@param		string				$label
	 *	@param		string|array|NULL	$class
	 *	@param		Icon|string|NULL	$icon
	 *	@param		bool				$disabled
	 *	@return		static				Own instance for method chaining
	 */
	public function add( string $url, string $label, array|string $class = NULL, Icon|string $icon = NULL, bool $disabled = FALSE ): static
	{
		$item	= new MenuItem();
		$item->type		= 'link';
		$item->content	= new Link( $url, $label, $class, $icon );
		$item->disabled	= $disabled;
		$this->items[]	= $item;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		static		Own instance for method chaining
	 */
	public function addDivider(): static
	{
		$item			= new MenuItem();
		$item->type		= 'divider';
		$this->items[]	= $item;
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		string				$label
	 *	@param		Menu				$dropdown
	 *	@param		string|array|NULL	$class
	 *	@param		Icon|string|NULL	$icon
	 *	@param		bool				$disabled
	 *	@return		static				Own instance for method chaining
	 *	@deprecated						not supported in Bootstrap 4.4, so disabled for all others, too
	 */
	public function addDropdown( string $label, Menu $dropdown, string|array $class = NULL, Icon|string $icon = NULL, bool $disabled = FALSE ): static
	{
		\trigger_error( 'Not supported in Bootstrap 4.4, so disabled for all others, too', E_USER_DEPRECATED );
/*		$link		= new \CeusMedia\Bootstrap\Link( '#', $label, 'dropdown-item', $icon, $disabled );
		$link->setRole( 'button' );
		if( $class )
			$link->addClass( $class );

		$item	= new MenuItem();
		$item->type		= 'dropdown';
		$item->content	= $link;
		$item->submenu	= $dropdown;
		$item->class	= $class;
		$item->icon		= $icon;
		$item->disabled	= $disabled;
		$this->items[]	= $item;*/
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		Link			$link		Link to add
	 *	@param		bool			$disabled	Flag: disable menu item, default: no
	 *	@return		static			Own instance for method chaining
	 */
	public function addLink( Link $link, bool $disabled = FALSE ): static
	{
		$item	= new MenuItem();
		$item->type		= 'link';
		$item->content	= $link;
		$item->disabled	= $disabled;
		$this->items[]	= $item;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$list	= [];
		foreach( $this->items as $item ){
			$attributes	= ['class' => NULL];# 'class' => 'active' );
			switch( $item->type ){
				case "dropdown":
					$attributes['class']	= 'dropdown-submenu';
					$content	= is_object( $item->content ) ? $item->content->render() : $item->content;
					$submenu	= is_object( $item->submenu ) ? $item->submenu->render() : $item->submenu;
					$item->content	= $content.$submenu;
					break;
				case "divider":
					$attributes['class']	= 'divider';
					break;
				case "link":
					$attributes['class']	= 'dropdown-item';
					break;
				default:
					throw new OutOfBoundsException( 'Invalid dropdown item time: '.$item->type );
			}
			if( $item->disabled )
				$attributes['class']	.= ' disabled';
			$list[]	= HtmlTag::create( 'li', $item->content, $attributes );
		}
		$attributes	= [
			'class'		=> "dropdown-menu",
		];
		if( !$this->alignLeft ){
			$additionalClass		= version_compare( $this->bsVersion, '4', '>=' ) ? 'dropdown-menu-right' : 'pull-right';
			$attributes['class']	= $attributes['class'].' '.$additionalClass;
		}
		return HtmlTag::create( 'ul', $list, $attributes );
	}

	/**
	 *	@access		public
	 *	@param		bool		$left
	 *	@return		static		Own instance for method chaining
	 */
	public function setAlign( bool $left = TRUE ): static
	{
		$this->alignLeft	= $left;
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		string		$label
	 *	@return		static		Own instance for method chaining
	 */
	public function setAriaLabel( string $label ): static
	{
		$this->setAria( 'label', $label );
		return $this;
	}
}
