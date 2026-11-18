<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
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

use CeusMedia\Bootstrap\Base\DataObject\Breadcrumb;
use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Base\Aware\ClassAware;
use CeusMedia\Bootstrap\Icon;
use CeusMedia\Bootstrap\Link;

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
class Breadcrumbs extends Structure
{
	use ClassAware;

	protected array $crumbs		= [];
	protected string $divider;

	/**
	 *	@param		string			$divider
	 *	@param		string|array	$class
	 */
	public function __construct( string $divider = '/', $class = NULL )
	{
		parent::__construct();
		$this->setDivider( $divider );
		if( NULL !== $class )
			$this->setClass( $class );
	}

	/**
	 *	@access		public
	 *	@param		Link|string			$label
	 *	@param		string|NULL			$url
	 *	@param		string|NULL			$class
	 *	@param		Icon|string|NULL	$icon
	 *	@param		boolean				$active
	 *	@return		static				Own instance for method chaining
	 */
	public function add( Link|string $label, ?string $url = NULL, ?string $class = NULL, Icon|string|null $icon = NULL, bool $active = FALSE ): static
	{
		$crumb	= new Breadcrumb();
		$crumb->label	= $label;
		$crumb->url		= (string) $url;
		$crumb->class	= (string) $class;
		$crumb->icon	= $icon;
		$crumb->active	= $active;

		$this->crumbs[]	= $crumb;
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		Link|string			$label
	 *	@param		Icon|string|NULL	$icon
	 *	@return		static		Own instance for method chaining
	 */
	public function addCurrent( Link|string $label, Icon|string|null $icon = NULL ): static
	{
		$this->add( $label, NULL, NULL, $icon, TRUE );
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		static		Own instance for method chaining
	 */
	public function addLink( Link $link ): static
	{
		$this->add( $link );
		return $this;
	}

	/**
	 *	Renders breadcrumbs for set Bootstrap version.
	 *	Bootstrap 2 renders dividers as elements, later versions draw them by CSS.
	 *	Custom dividers are supported by Bootstrap 2 and 5 (by CSS variable) only.
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
		$list		= [];
		$last		= count( $this->crumbs ) - 1;
		foreach( array_values( $this->crumbs ) as $nr => $crumb ){
			$content	= $this->renderCrumbContent( $crumb );
			if( BsVersion::V2 === $version && $nr < $last )
				$content	.= ' '.HtmlTag::create( 'span', htmlentities( $this->divider, ENT_QUOTES, 'UTF-8' ), ['class' => 'divider'] );
			$classesItem	= $isLegacy ? [] : ['breadcrumb-item'];
			if( '' !== $crumb->class )
				$classesItem[]	= $crumb->class;
			if( $crumb->active )
				$classesItem[]	= 'active';
			$list[]	= HtmlTag::create( 'li', $content, [
				'class'			=> [] !== $classesItem ? join( ' ', $classesItem ) : NULL,
				'aria-current'	=> !$isLegacy && $crumb->active ? 'page' : NULL,
			] );
		}
		$classes	= join( ' ', array_merge( ['breadcrumb'], $this->classes ) );
		$list		= HtmlTag::create( BsVersion::V2 === $version ? 'ul' : 'ol', $list, ['class' => $classes] );
		$style		= NULL;
		if( BsVersion::V5 === $version && '/' !== $this->divider )							//  Bootstrap 5: divider by CSS variable
			$style	= "--bs-breadcrumb-divider: '".addcslashes( $this->divider, "'\\" )."';";
		return HtmlTag::create( 'nav', $list, [
			'aria-label'	=> $isLegacy ? NULL : 'breadcrumb',
			'style'			=> $style,
		] );
	}

	/**
	 *	@access		public
	 *	@param		string		$divider
	 *	@return		static		Own instance for method chaining
	 */
	public function setDivider( string $divider ): static
	{
		$this->divider	= $divider;
		return $this;
	}

	/*  --  PROTECTED  --  */

	protected function renderCrumbContent( Breadcrumb $crumb ): string
	{
		if( $crumb->label instanceof Link ){
			$link	= clone $crumb->label;												//  do not change given link object
			$link->setBsVersion( $this->bsVersion );
			return $link->render();
		}
		$icon	= '' !== $crumb->icon ? $crumb->icon : NULL;
		if( '' !== trim( $crumb->url ) ){
			$link	= new Link( $crumb->url, $crumb->label, NULL, $icon );			//  icon within link, like for given links
			$link->setBsVersion( $this->bsVersion );
			return $link->render();
		}
		if( is_string( $icon ) )
			$icon	= new Icon( $icon );
		return ( NULL !== $icon ? $icon->render().' ' : '' ).$crumb->label;
	}
}
