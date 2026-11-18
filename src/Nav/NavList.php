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
namespace CeusMedia\Bootstrap\Nav;

use CeusMedia\Bootstrap\Base\DataObject\NavListItem;
use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Icon;
use CeusMedia\Bootstrap\Link;
use CeusMedia\Common\ADT\URL;
use CeusMedia\Common\Exception\Data\Missing as DataMissingException;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class NavList extends Structure
{
	protected ?string $current		= NULL;

	/** @var NavListItem[] $items */
	protected array $items			= [];

	/**
	 *	@access		public
	 *	@param		URL|string			$url
	 *	@param		string				$label
	 *	@param		Icon|string|NULL	$icon
	 *	@param		string|NULL			$class
	 *	@return		static				Own instance for method chaining
	 */
	public function add( URL|string $url, string $label, Icon|string|null $icon = NULL, ?string $class = NULL/*, array $attr = [], $data = [], $events = []*/ ): static
	{
		$item	= new NavListItem();
		$item->type		= 'link';
		$item->url		= $url;
		$item->label	= $label;
		$item->icon		= is_string( $icon ) ? new Icon( $icon ) : $icon;
		$item->class	= $class;

		$this->items[]	= $item;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		static		Own instance for method chaining
	 */
	public function addDivider(): static
	{
		$item	= new NavListItem();
		$item->type		= 'divider';
		$this->items[]	= $item;
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		string				$label
	 *	@param		Icon|string|NULL	$icon
	 *	@param		string|NULL			$class
	 *	@return		static				Own instance for method chaining
	 */
	public function addHeader( string $label, Icon|string|null $icon = NULL, ?string $class = NULL ): static
	{
		$item	= new NavListItem();
		$item->type		= 'header';
		$item->label	= $label;
		$item->icon		= is_string( $icon ) ? new Icon( $icon ) : $icon;
		$item->class	= trim( 'nav-header autocut '.$class );

		$this->items[]	= $item;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		static		Own instance for method chaining
	 */
	public function addNavList( NavList $list ): static
	{
		$item	= new NavListItem();
		$item->type		= 'navlist';
		$item->list		= $list;
		$this->items[]	= $item;
		return $this;
	}

	/**
	 *	Renders navigation list for set Bootstrap version.
	 *	Bootstrap 2 has a list navigation (nav-list), later versions use stacked pills,
	 *	with headers and dividers borrowed from dropdowns (Bootstrap 3 and 4) or utilities (Bootstrap 5).
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
				'header'	=> $this->renderHeader( $item, $version ),
				'navlist'	=> NULL !== $item->list ? ( clone $item->list )->setBsVersion( $this->bsVersion )->render() : '',
				'link'		=> $this->renderLink( $item, $version ),
				default		=> '',
			};
		}
		$class	= match( $version ){
			BsVersion::V2	=> 'nav nav-list',
			BsVersion::V3	=> 'nav nav-pills nav-stacked',
			BsVersion::V4, BsVersion::V5	=> 'nav nav-pills flex-column',
		};
		return HtmlTag::create( 'ul', $list, ['class' => $class] );
	}

	/**
	 *	@access		public
	 *	@param		string		$url
	 *	@return		static		Own instance for method chaining
	 */
	public function setCurrent( string $url ): static
	{
		$this->current	= $url;
		return $this;
	}

	/*  --  PROTECTED  --  */

	protected function renderDivider( BsVersion $version ): string
	{
		return match( $version ){
			BsVersion::V2	=> HtmlTag::create( 'li', '', ['class' => 'divider'] ),
			BsVersion::V3	=> HtmlTag::create( 'li', '', ['class' => 'nav-divider', 'role' => 'separator'] ),
			BsVersion::V4	=> HtmlTag::create( 'li', '', ['class' => 'dropdown-divider', 'role' => 'separator'] ),
			BsVersion::V5	=> HtmlTag::create( 'li', '', ['class' => 'border-top my-2', 'role' => 'separator'] ),	//  like dropdown-divider of Bootstrap 4
		};
	}

	protected function renderHeader( NavListItem $item, BsVersion $version ): string
	{
		$label	= $item->label;
		if( NULL !== $item->icon )
			$label	= $item->icon->render().' '.$label;
		$custom	= preg_split( '/\s+/', trim( $item->class ?? '' ), -1, PREG_SPLIT_NO_EMPTY );
		$custom	= array_diff( FALSE !== $custom ? $custom : [], ['nav-header', 'autocut'] );	//  keep classes given by user
		$class	= match( $version ){
			BsVersion::V2	=> 'nav-header',
			BsVersion::V3, BsVersion::V4	=> 'dropdown-header',
			BsVersion::V5	=> 'px-4 py-2 small text-muted',		//  like dropdown-header of Bootstrap 4, which depends on dropdown CSS variables in Bootstrap 5
		};
		return HtmlTag::create( 'li', $label, ['class' => join( ' ', array_merge( [$class, 'autocut'], $custom ) )] );
	}

	/**
	 *	@throws		DataMissingException	if no URL is set
	 */
	protected function renderLink( NavListItem $item, BsVersion $version ): string
	{
		if( NULL === $item->url )
			throw new DataMissingException( 'No URL provided for link' );
		$classes	= preg_split( '/\s+/', trim( $item->class ?? '' ), -1, PREG_SPLIT_NO_EMPTY );
		$classes	= FALSE !== $classes ? $classes : [];
		$isActive	= in_array( 'active', $classes, TRUE ) || $item->url === $this->current;
		$classes	= array_values( array_diff( $classes, ['active'] ) );

		$link	= new Link( $item->url, $item->label, 'autocut' );
		$link->setBsVersion( $this->bsVersion );
		$link->setIcon( $item->icon, $isActive ? 'white' : '' );
		switch( $version ){
			case BsVersion::V2:
			case BsVersion::V3:
				if( $isActive )
					$classes[]	= 'active';
				break;
			case BsVersion::V4:
			case BsVersion::V5:
				$link->addClass( 'nav-link' );
				if( $isActive )
					$link->addClass( 'active' )->setAria( 'current', 'page' );
				array_unshift( $classes, 'nav-item' );
				break;
		}
		return HtmlTag::create( 'li', $link, [
			'class'	=> [] !== $classes ? join( ' ', $classes ) : NULL,
			'title'	=> $item->label,
		] );
	}
}
