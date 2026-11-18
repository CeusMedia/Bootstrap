<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Dropdown;

use CeusMedia\Bootstrap\Base\Aware\AriaAware;
use CeusMedia\Bootstrap\Base\BsVersion;
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
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
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
	/**
	 *	Constructor.
	 *	@param		string				$url
	 *	@param		string				$label
	 *	@param		string|array|NULL	$class
	 *	@param		Icon|string|NULL	$icon
	 *	@param		bool				$disabled
	 *	@return		static				Own instance for method chaining
	 */
	public function add( string $url, string $label, array|string|null $class = NULL, Icon|string|null $icon = NULL, bool $disabled = FALSE ): static
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
	public function addDropdown( string $label, Menu $dropdown, string|array|null $class = NULL, Icon|string|null $icon = NULL, bool $disabled = FALSE ): static
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
	 *	Renders dropdown menu for set Bootstrap version.
	 *	Bootstrap 2 and 3 use list items containing links, Bootstrap 4 uses links directly
	 *	within a div, Bootstrap 5 uses list items containing links with item class.
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$version	= $this->getBsVersion();
		$list		= [];
		foreach( $this->items as $item ){
			$list[]	= match( $item->type ){
				'divider'	=> $this->renderDivider( $version ),
				'link'		=> $this->renderLinkItem( $item, $version ),
				default		=> throw new OutOfBoundsException( 'Invalid dropdown item type: '.$item->type ),
			};
		}
		$attributes	= [
			'class'		=> $this->dialect()->classes( 'dropdown-menu', $this->alignLeft ? '' : 'dropdown-menu-end' ),
		];
		$this->extendAttributesByAria( $attributes );
		$tagName	= match( $version ){
			BsVersion::V2, BsVersion::V3, BsVersion::V5	=> 'ul',
			BsVersion::V4								=> 'div',
		};
		return HtmlTag::create( $tagName, $list, $attributes );
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

	/*  --  PROTECTED  --  */

	protected function renderDivider( BsVersion $version ): string
	{
		return match( $version ){
			BsVersion::V2	=> HtmlTag::create( 'li', '', ['class' => 'divider'] ),
			BsVersion::V3	=> HtmlTag::create( 'li', '', ['role' => 'separator', 'class' => 'divider'] ),
			BsVersion::V4	=> HtmlTag::create( 'div', '', ['class' => 'dropdown-divider'] ),
			BsVersion::V5	=> HtmlTag::create( 'li', HtmlTag::create( 'hr', NULL, ['class' => 'dropdown-divider'] ) ),
		};
	}

	protected function renderLinkItem( MenuItem $item, BsVersion $version ): string
	{
		$content	= $item->content;
		if( $content instanceof Link ){
			$content	= clone $content;												//  do not change given link object
			$content->setBsVersion( $this->bsVersion );
		}
		switch( $version ){
			case BsVersion::V2:
			case BsVersion::V3:
				$attributes	= ['class' => $item->disabled ? 'disabled' : NULL];
				return HtmlTag::create( 'li', $content, $attributes );
			case BsVersion::V4:
			case BsVersion::V5:
				if( $content instanceof Link ){
					$content->addClass( 'dropdown-item' );
					if( $item->disabled )
						$content->addClass( 'disabled' );
				}
				$html	= $content instanceof Renderable ? $content->render() : (string) $content;
				return BsVersion::V5 === $version ? HtmlTag::create( 'li', $html ) : $html;
		}
	}
}
