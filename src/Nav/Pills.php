<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Nav
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Nav;

use CeusMedia\Bootstrap\Base\DataObject\NavPillItemDropdown;
use CeusMedia\Bootstrap\Base\DataObject\NavPillItemLink;
use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Icon;
use CeusMedia\Bootstrap\Link;
use CeusMedia\Bootstrap\Dropdown\Menu as DropdownMenu;
use CeusMedia\Bootstrap\Dropdown\Trigger\Link as TriggerLink;
use CeusMedia\Common\Renderable;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use Stringable;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Nav
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Pills extends Structure
{
	protected int $active	= -1;

	/** @var array<NavPillItemLink|NavPillItemDropdown> $items */
	protected array $items	= [];
	protected bool $stacked	= FALSE;

	/**
	 *	@access		public
	 *	@param		string					$url
	 *	@param		Stringable|Renderable|string|NULL	$label
	 *	@param		string|NULL				$class
	 *	@param		Icon|string|NULL		$icon
	 *	@return		static					Own instance for method chaining
	 */
	public function add( string $url, Stringable|Renderable|string|null $label, ?string $class = NULL, Icon|string|null $icon = NULL ): static
	{
		$class	= 'nav-link'.( ( '' !== ( $class ?? '' ) ) ? ' '.$class : '' );
		$link	= new Link( $url, $label, $class, $icon );
		$this->addLink( $link );
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		static		Own instance for method chaining
	 */
	public function addLink( Link $link ): static
	{
		$link->addClass( 'nav-link' );
		$this->items[]	= NavPillItemLink::create( $link );
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		DropdownMenu		$dropdown
	 *	@param		string				$label
	 *	@param		string|NULL			$class
	 *	@param		Icon|string|NULL	$icon
	 *	@param		Icon|string|NULL	$iconActive
	 *	@return		static				Own instance for method chaining
	 *	@todo		rename to addMenu or addDropdownMenu
	 */
	public function addDropdown( DropdownMenu $dropdown, string $label, ?string $class = NULL, Icon|string|null $icon = NULL, Icon|string|null $iconActive = NULL ): static
	{
/*		if( version_compare( $this->bsVersion, 4, '>=' ) )
			$label		= HtmlTag::create( 'a', $label, [
				'href'			=> '#',
				'class'			=> 'nav-link dropdown-toggle',
				'data-toggle'	=> 'dropdown',
			] );*/
		$this->items[]	= NavPillItemDropdown::create( $label, $dropdown, $class, $icon, $iconActive );
		return $this;
	}

	/**
	 *	Renders pills for set Bootstrap version.
	 *	Bootstrap 2 and 3 mark list items as active, Bootstrap 4 and 5 mark links (class nav-link).
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$version	= $this->getBsVersion();
		$isLegacy	= match( $version ){
			BsVersion::V2, BsVersion::V3	=> TRUE,
			BsVersion::V4, BsVersion::V5	=> FALSE,
		};
		$items	= [];
		foreach( $this->items as $nr => $item ){
			$isActive	= $this->active === $nr;
			$items[]	= $item instanceof NavPillItemDropdown
				? $this->renderDropdownItem( $item, $isActive, $isLegacy )
				: $this->renderLinkItem( $item, $isActive, $isLegacy );
		}
		$classStacked	= $isLegacy ? 'nav-stacked' : 'flex-column';
		return HtmlTag::create( 'ul', $items, [
			'class'	=> 'nav nav-pills'.( $this->stacked ? ' '.$classStacked : '' ),
		] );
	}

	/**
	 *	@access		public
	 *	@param		int			$nr
	 *	@return		static		Own instance for method chaining
	 */
	public function setActive( int $nr ): static
	{
		$this->active	= $nr;
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		bool		$stacked
	 *	@return		static		Own instance for method chaining
	 */
	public function setStacked( bool $stacked = TRUE ): static
	{
		$this->stacked	= $stacked;
		return $this;
	}

	/*  --  PROTECTED  --  */

	protected function renderDropdownItem( NavPillItemDropdown $item, bool $isActive, bool $isLegacy ): string
	{
		$icon		= $isActive && NULL !== $item->iconActive ? $item->iconActive : $item->icon;
		$classes	= preg_split( '/\s+/', $item->class, -1, PREG_SPLIT_NO_EMPTY );
		$classes	= FALSE !== $classes ? $classes : [];
		if( $isLegacy )																//  Bootstrap 2 and 3: no nav-link
			$classes	= array_diff( $classes, ['nav-link'] );
		else if( $isActive )														//  Bootstrap 4 and 5: active link
			$classes[]	= 'active';
		$trigger	= new TriggerLink( $item->label, join( ' ', $classes ), $icon );
		$trigger->setBsVersion( $this->bsVersion );
		$menu		= $item->content;
		if( $menu instanceof DropdownMenu ){
			$menu	= clone $menu;													//  do not change given menu object
			$menu->setBsVersion( $this->bsVersion );
		}
		$classItem	= $isLegacy
			? 'dropdown'.( $isActive ? ' active' : '' )
			: 'nav-item dropdown';
		return HtmlTag::create( 'li', $trigger->render().$menu, ['class' => $classItem] );
	}

	protected function renderLinkItem( NavPillItemLink $item, bool $isActive, bool $isLegacy ): string
	{
		$link	= $item->link;
		if( $link instanceof Link ){
			$link	= clone $link;													//  do not change given link object
			$link->setBsVersion( $this->bsVersion );
			if( $isLegacy )
				$link->removeClass( 'nav-link' );
			else if( $isActive )
				$link->addClass( 'active' )->setAria( 'current', 'page' );
		}
		$classItem	= $isLegacy
			? ( $isActive ? 'active' : NULL )
			: $item->class;
		return HtmlTag::create( 'li', $link, ['class' => $classItem] );
	}
}
