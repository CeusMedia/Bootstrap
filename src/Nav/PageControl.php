<?php /** @noinspection PhpUnused */
declare(strict_types=1);

/**
 *	Pagination with links to first, previous, next and last page and indicator of current page.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Nav;

use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Icon;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	Pagination with links to first, previous, next and last page and indicator of current page.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class PageControl extends Structure
{
	public string $baseUrl;
	public int $page;
	public int $pages;
	public string $patternUrl;
	public string $patternIndicator;
	public ?string $size				= NULL;
	public string $fragment;

	public const string SIZE_MINI			= 'mini';
	public const string SIZE_SMALL			= 'small';
	public const string SIZE_DEFAULT		= '';
	public const string SIZE_LARGE			= 'large';

	public function __construct( string $baseUrl, int $page, int $pages )
	{
		parent::__construct();
		$this->baseUrl			= $baseUrl;
		$this->page				= abs( $page );
		$this->pages			= abs( $pages );
		$this->patternUrl		= '/%s';
		$this->patternIndicator	= '<b>%s</b> / %s';
		$this->fragment			= '';
	}

	/**
	 *	Renders page control as pagination of set Bootstrap version:
	 *	links to first, previous, next and last page and an indicator of the current page.
	 *	Bootstrap 2: "div.pagination > ul > li", Bootstrap 3: "ul.pagination > li",
	 *	since Bootstrap 4: "ul.pagination > li.page-item > .page-link".
	 *	Sizes: pagination-mini, -small, -large (Bootstrap 2), pagination-sm, -lg (since Bootstrap 3).
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		if( $this->pages <= 1 )
			return '';
		$version	= $this->getBsVersion();
		$isFirst	= 0 === $this->page;
		$isLast		= $this->page === $this->pages - 1;
		$items		= [
			$this->renderItem( $version, new Icon( 'fast-backward' ), $isFirst ? NULL : $this->getUrl() ),
			$this->renderItem( $version, new Icon( 'backward' ), $isFirst ? NULL : $this->getUrl( max( 0, $this->page - 1 ) ) ),
			$this->renderItem( $version, sprintf( $this->patternIndicator, $this->page + 1, $this->pages ), NULL, TRUE ),
			$this->renderItem( $version, new Icon( 'forward' ), $isLast ? NULL : $this->getUrl( min( $this->pages - 1, $this->page + 1 ) ) ),
			$this->renderItem( $version, new Icon( 'fast-forward' ), $isLast ? NULL : $this->getUrl( $this->pages - 1 ) ),
		];
		$classes	= join( ' ', array_filter( [
			'pagination',
			$this->realizeSizeClass( $version ),
			'page-control',
		], static fn( ?string $class ): bool => NULL !== $class ) );
		return match( $version ){
			BsVersion::V2	=> HtmlTag::create( 'div', HtmlTag::create( 'ul', $items ), ['class' => $classes] ),
			BsVersion::V3, BsVersion::V4, BsVersion::V5	=> HtmlTag::create( 'nav',
				HtmlTag::create( 'ul', $items, ['class' => $classes] ),
				['aria-label' => 'Pagination']
			),
		};
	}

	/*  --  PROTECTED  --  */

	protected function getUrl( int $page = 0 ): string
	{
		$fragment	= '' !== $this->fragment ? '#'.$this->fragment : '';
		$part		= sprintf( $this->patternUrl, $page );
		if( 0 === $page && "/%s" === $this->patternUrl )
			$part	= '';
		return $this->baseUrl.$part.$fragment;
	}

	/**
	 *	Renders list item of pagination: a link, a disabled item (without URL) or the active indicator.
	 *	@param		BsVersion		$version
	 *	@param		Icon|string		$content
	 *	@param		string|NULL		$url			URL of link, NULL for disabled item or indicator
	 *	@param		bool			$isActive		Flag: item is indicator of current page
	 *	@return		string
	 */
	protected function renderItem( BsVersion $version, Icon|string $content, ?string $url, bool $isActive = FALSE ): string
	{
		$hasPageClasses	= match( $version ){
			BsVersion::V2, BsVersion::V3	=> FALSE,
			BsVersion::V4, BsVersion::V5	=> TRUE,
		};
		$content	= $content instanceof Icon ? $content->render() : $content;
		$classLink	= $hasPageClasses ? 'page-link' : NULL;
		$inner		= NULL !== $url
			? HtmlTag::create( 'a', $content, ['href' => $url, 'class' => $classLink] )
			: HtmlTag::create( 'span', $content, ['class' => $classLink] );
		$classes	= array_filter( [
			$hasPageClasses ? 'page-item' : NULL,
			$isActive ? 'active' : ( NULL === $url ? 'disabled' : NULL ),
		], static fn( ?string $class ): bool => NULL !== $class );
		return HtmlTag::create( 'li', $inner, [
			'class'			=> [] !== $classes ? join( ' ', $classes ) : NULL,
			'aria-current'	=> $isActive && BsVersion::V2 !== $version ? 'page' : NULL,
		] );
	}

	protected function realizeSizeClass( BsVersion $version ): ?string
	{
		if( NULL === $this->size || self::SIZE_DEFAULT === $this->size )
			return NULL;
		return match( $version ){
			BsVersion::V2	=> 'pagination-'.$this->size,
			BsVersion::V3, BsVersion::V4, BsVersion::V5	=> self::SIZE_LARGE === $this->size ? 'pagination-lg' : 'pagination-sm',
		};
	}
}
