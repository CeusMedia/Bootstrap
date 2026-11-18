<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

/**
 *  ...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Nav;

use CeusMedia\Bootstrap\Base\DataObject\NavTabsItem;
use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Base\Aware\IdAware;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use RangeException;
use RuntimeException;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Tabs extends Structure
{
	use IdAware;

	protected ?string $activeId		= NULL;
	protected array $tabs			= [];

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$id			ID of tabs container
	 *	@return		void
	 */
	public function __construct( string $id/*, $active = 0*/ )
	{
		parent::__construct();
		$this->setId( $id );
//		$this->setActive( $active );
	}

	/**
	 *	Registers a tab (as link or fragment link with content).
	 *	ATTENTION: If you want to use dynamic tabs with content and your site is using base tag, you need to provide URLs relative to base.
	 *	@access		public
	 *	@param		string			$id			ID of tab pane container
	 *	@param		string			$url		URL of tab link
	 *	@param		string			$label		Label of tab pane
	 *	@param		string|NULL		$content	Content of tab pane, if tab is a fragment link
	 *	@param		boolean			$disabled	Flag: Do not enable this tab by default
	 *	@return		static			Own instance for method chaining
	 */
	public function add( string $id, string $url, string $label, ?string $content = NULL, bool $disabled = FALSE ): static
	{
		$this->tabs[]	= NavTabsItem::create( $id, $url, $label, $content ?? '', $disabled );
		return $this;
	}

	/**
	 *	Notes tab to be disabled.
	 *	@access		public
	 *	@param		integer|string	$idOrIndex		Number or ID of tab to disable
	 *	@return		static			Own instance for method chaining
	 */
	public function disableTab( int|string $idOrIndex ): static
	{
		$id	= is_int( $idOrIndex ) ? $this->getIdByIndex( $idOrIndex ) : $idOrIndex;
		foreach( $this->tabs as $nr => $item )
			if( $item->id === $id )
				$this->tabs[$nr]->disabled	= TRUE;
		return $this;
	}

	/**
	 *	Notes tab to be enabled.
	 *	@access		public
	 *	@param		integer|string	$idOrIndex		Number or ID of tab to enable
	 *	@return		static			Own instance for method chaining
	 */
	public function enableTab( int|string $idOrIndex ): static
	{
		$id	= is_int( $idOrIndex ) ? $this->getIdByIndex( $idOrIndex ) : $idOrIndex;
		foreach( $this->tabs as $nr => $item )
			if( $item->id === $id )
				$this->tabs[$nr]->disabled	= FALSE;
		return $this;
	}

	/**
	 *	Renders tabs and tab panes for set Bootstrap version.
	 *	Bootstrap 2 and 3 mark list items as active or disabled,
	 *	Bootstrap 4 and 5 mark links (class nav-link).
	 *	If no tab is set active, the first enabled tab is active.
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
		$activeId	= $this->activeId ?? $this->getFirstEnabledId();
		$listTabs	= [];
		$listPanes	= [];
		foreach( $this->tabs as $tab ){
			$isActive	= $tab->id === $activeId;
			$listTabs[]	= $this->renderTab( $tab, $isActive, $isLegacy, $version );
			$classesPane	= ['tab-pane'];
			if( $isActive )
				$classesPane[]	= $isLegacy ? 'active' : 'show active';
			$listPanes[]	= HtmlTag::create( 'div', $tab->content, [
				'class'	=> join( ' ', $classesPane ),
				'id'	=> $tab->id,
				'role'	=> 'tabpanel',
			] );
		}
		$listTabs	= HtmlTag::create( 'ul', $listTabs, [
			'class'	=> 'nav nav-tabs',
			'id'	=> $this->id,
			'role'	=> 'tablist',
		] );
		$listTabs	= HtmlTag::create( 'nav', $listTabs );
		$listPanes	= HtmlTag::create( 'div', $listPanes, ['class' => 'tab-content'] );
		return $listTabs.$listPanes;
	}

	/**
	 *	Sets active tab by its number.
	 *	@access		public
	 *	@param		integer|string	$idOrIndex		Number or ID of tab to mark as active.
	 *	@return		static			Own instance for method chaining
	 */
	public function setActive( int|string $idOrIndex ): static
	{
		$id		= is_int( $idOrIndex ) ? $this->getIdByIndex( $idOrIndex ) : $idOrIndex;
		$tab	= $this->getTabById( $id );
		if( $tab->disabled )
			throw new RuntimeException( sprintf( 'Tab with ID %s is disabled and cannot be active', $id ) );
		$this->activeId	= $id;
		return $this;
	}

	protected function getFirstEnabledId(): ?string
	{
		foreach( $this->tabs as $tab )
			if( !$tab->disabled )
				return $tab->id;
		return NULL;
	}

	protected function renderTab( NavTabsItem $tab, bool $isActive, bool $isLegacy, BsVersion $version ): string
	{
		$isToggle	= '#' === $tab->url || '#'.$tab->id === $tab->url;					//  pane on this page, otherwise link to other page
		$classesLink	= $isLegacy ? [] : ['nav-link'];
		$classesItem	= $isLegacy ? [] : ['nav-item'];
		$attributes	= [
			'href'			=> $isToggle ? '#'.$tab->id : $tab->url,
			'role'			=> BsVersion::V2 !== $version && $isToggle ? 'tab' : NULL,
			'aria-controls'	=> BsVersion::V2 !== $version && $isToggle ? $tab->id : NULL,
			'aria-selected'	=> $isLegacy || !$isToggle ? NULL : ( $isActive ? 'true' : 'false' ),
		];
		if( $isToggle )
			$attributes[$this->dialect()->data( 'toggle' )]	= 'tab';
		if( $tab->disabled ){
			$attributes['href']		= NULL;
			$attributes[$this->dialect()->data( 'toggle' )]	= NULL;
			if( $isLegacy )
				$classesItem[]	= 'disabled';
			else{
				$classesLink[]	= 'disabled';
				$attributes['aria-disabled']	= 'true';
			}
		}
		else if( $isActive ){
			if( $isLegacy )
				$classesItem[]	= 'active';
			else
				$classesLink[]	= 'active';
		}
		$attributes	= array_merge( ['class' => [] !== $classesLink ? join( ' ', $classesLink ) : NULL], $attributes );
		$link		= HtmlTag::create( 'a', $tab->label, $attributes );
		return HtmlTag::create( 'li', $link, [
			'class'	=> [] !== $classesItem ? join( ' ', $classesItem ) : NULL,
			'role'	=> BsVersion::V3 === $version ? 'presentation' : NULL,
		] );
	}

	protected function getIndexById( string $id ): int
	{
		foreach( $this->tabs as $nr => $item ){
			if( $item->id === $id )
				return $nr;
		}
		throw new RangeException( sprintf( 'No tab available for ID %s', $id ) );
	}

	protected function getIdByIndex( int $index ): string
	{
		foreach( $this->tabs as $nr => $item ){
			if( (int) $nr === $index )
				return $item->id;
		}
		throw new RangeException( sprintf( 'No tab available for index %d', $index ) );
	}

	protected function getTabById( string $id ): NavTabsItem
	{
		foreach( $this->tabs as $nr => $tab )
			if( $tab->id === $id )
				return $this->tabs[$nr];
		throw new RangeException( sprintf( 'No tab available for ID %s', $id ) );
	}
}
