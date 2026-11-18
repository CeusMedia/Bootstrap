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

use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Link;

use CeusMedia\Common\ADT\URL;
use CeusMedia\Common\Renderable;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use Stringable;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class TabbableNavbar extends Structure
{
	protected ?int $active			= NULL;

	/** @var array<string,string> $tabs */
	protected array $tabs			= [];

	/** @var array<string,string> $contents */
	protected array $contents		= [];

	/** @var string|NULL $fixed		Fixed position: top, bottom or NULL */
	protected ?string $fixed		= NULL;

	/** @var Stringable|Renderable|string|NULL $brandLabel */
	protected Stringable|Renderable|string|null $brandLabel	= NULL;

	protected URL|string|null $brandUrl	= NULL;

	/** @var array<int,string> $index */
	protected array $index			= [];

	/**
	 *	@access		public
	 *	@return		static		Own instance for method chaining
	 */
	public function add( string $id, string $label, string $content ): static
	{
		$this->index[]			= $id;
		$this->tabs[$id]		= $label;
		$this->contents[$id]	= $content;
		return $this;
	}

	/**
	 *	Renders navbar with tabs and tab panes for set Bootstrap version.
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$activeId	= $this->index[$this->active ?? 0] ?? NULL;							//  resolve active tab number (0-based) to tab ID
		$navbar		= match( $this->getBsVersion() ){
			BsVersion::V2	=> $this->renderNavbarV2( $activeId ),
			BsVersion::V3	=> $this->renderNavbarV3( $activeId ),
			BsVersion::V4, BsVersion::V5	=> $this->renderNavbarV4( $activeId ),
		};

		$listDivs	= [];
		foreach( $this->index as $id ){
			$attributes	= [
				'id'	=> $id,
				'class'	=> $activeId === $id ? "tab-pane active" : "tab-pane",
			];
			$listDivs[]	= HtmlTag::create( 'div', $this->contents[$id], $attributes );
		}
		$listDivs	= HtmlTag::create( 'div', $listDivs, ['class' => "tab-content"] );
		return HtmlTag::create( 'div', $navbar.$listDivs, ['class' => "tabbable"] );
	}

	/**
	 *	Sets active tab by its number.
	 *	@access		public
	 *	@param		integer		$nr			Number of tab to mark as active, starting with 0.
	 *	@return		static		Own instance for method chaining
	 */
	public function setActive( int $nr ): static
	{
		$this->active	= $nr;
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		Stringable|Renderable|string	$label
	 *	@param		URL|string|NULL		$url
	 *	@return		static				Own instance for method chaining
	 */
	public function setBrand( Stringable|Renderable|string $label, URL|string|null $url = NULL ): static
	{
		$this->brandLabel	= $label;
		$this->brandUrl		= $url;
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		string|NULL		$position		top, bottom or NULL for not fixed
	 *	@return		static		Own instance for method chaining
	 */
	public function setFixed( ?string $position = NULL ): static
	{
		$this->fixed	= in_array( $position, ['top', 'bottom'], TRUE ) ? $position : NULL;
		return $this;
	}

	/*  --  PROTECTED  --  */

	/**
	 *	Returns ID for collapsible part, unique per set of tabs.
	 *	@return		string
	 */
	protected function getCollapseId(): string
	{
		return 'navbar-collapse-'.substr( md5( join( ',', $this->index ) ), 0, 8 );
	}

	protected function renderBrand( string $class ): string
	{
		if( NULL === $this->brandLabel )
			return '';
		if( NULL === $this->brandUrl )
			return HtmlTag::create( 'span', $this->brandLabel, ['class' => $class] );
		$link	= new Link( $this->brandUrl, $this->brandLabel, $class );
		$link->setBsVersion( $this->bsVersion );
		return $link->render();
	}

	/**
	 *	Renders list items with tab links.
	 *	@param		string|NULL		$activeId
	 *	@param		bool			$onLink			Flag: mark link as active (Bootstrap 4+), otherwise list item
	 *	@return		array<string>
	 */
	protected function renderTabItems( ?string $activeId, bool $onLink ): array
	{
		$dialect	= $this->dialect();
		$list		= [];
		foreach( $this->index as $id ){
			$isActive	= $activeId === $id;
			$attributes	= [
				'href'						=> '#'.$id,
				'class'						=> $onLink ? 'nav-link'.( $isActive ? ' active' : '' ) : NULL,
				$dialect->data( 'toggle' )	=> 'tab',
				'role'						=> $onLink ? 'tab' : NULL,
			];
			$link		= HtmlTag::create( 'a', $this->tabs[$id], $attributes );
			$classItem	= $onLink ? 'nav-item' : ( $isActive ? 'active' : NULL );
			$list[]		= HtmlTag::create( 'li', $link, ['class' => $classItem] );
		}
		return $list;
	}

	protected function renderNavbarV2( ?string $activeId ): string
	{
		$listTabs	= HtmlTag::create( 'ul', $this->renderTabItems( $activeId, FALSE ), ['class' => "nav"] );
		$toggleSpan	= HtmlTag::create( 'span', "", ['class' => 'icon-bar'] );
		$toggle		= HtmlTag::create( 'a', str_repeat( $toggleSpan, 3 ), [
			'data-toggle'	=> 'collapse',
			'data-target'	=> '.nav-collapse',
			'class'			=> 'btn btn-navbar',
		] );
		$collapse	= HtmlTag::create( 'div', $listTabs, ['class' => "nav-collapse collapse"] );
		$container	= HtmlTag::create( 'div', $toggle.$this->renderBrand( 'brand' ).$collapse, ['class' => "container"] );
		$inner		= HtmlTag::create( 'div', $container, ['class' => "navbar-inner"] );
		$classes	= 'navbar'.( NULL !== $this->fixed ? ' navbar-fixed-'.$this->fixed : '' );
		return HtmlTag::create( 'div', $inner, ['class' => $classes] );
	}

	protected function renderNavbarV3( ?string $activeId ): string
	{
		$collapseId	= $this->getCollapseId();
		$listTabs	= HtmlTag::create( 'ul', $this->renderTabItems( $activeId, FALSE ), ['class' => "nav navbar-nav", 'role' => 'tablist'] );
		$toggleSpan	= HtmlTag::create( 'span', "", ['class' => 'icon-bar'] );
		$toggle		= HtmlTag::create( 'button', HtmlTag::create( 'span', 'Toggle navigation', ['class' => 'sr-only'] ).str_repeat( $toggleSpan, 3 ), [
			'type'			=> 'button',
			'class'			=> 'navbar-toggle collapsed',
			'data-toggle'	=> 'collapse',
			'data-target'	=> '#'.$collapseId,
			'aria-expanded'	=> 'false',
		] );
		$header		= HtmlTag::create( 'div', $toggle.$this->renderBrand( 'navbar-brand' ), ['class' => 'navbar-header'] );
		$collapse	= HtmlTag::create( 'div', $listTabs, ['class' => "collapse navbar-collapse", 'id' => $collapseId] );
		$container	= HtmlTag::create( 'div', $header.$collapse, ['class' => "container-fluid"] );
		$classes	= 'navbar navbar-default'.( NULL !== $this->fixed ? ' navbar-fixed-'.$this->fixed : '' );
		return HtmlTag::create( 'nav', $container, ['class' => $classes] );
	}

	/**
	 *	Renders navbar for Bootstrap 4 and 5, which differ in data attributes and container only.
	 *	@param		string|NULL		$activeId
	 *	@return		string
	 */
	protected function renderNavbarV4( ?string $activeId ): string
	{
		$dialect	= $this->dialect();
		$collapseId	= $this->getCollapseId();
		$listTabs	= HtmlTag::create( 'ul', $this->renderTabItems( $activeId, TRUE ), ['class' => "navbar-nav nav", 'role' => 'tablist'] );
		$toggle		= HtmlTag::create( 'button', HtmlTag::create( 'span', '', ['class' => 'navbar-toggler-icon'] ), [
			'type'						=> 'button',
			'class'						=> 'navbar-toggler',
			$dialect->data( 'toggle' )	=> 'collapse',
			$dialect->data( 'target' )	=> '#'.$collapseId,
			'aria-controls'				=> $collapseId,
			'aria-expanded'				=> 'false',
			'aria-label'				=> 'Toggle navigation',
		] );
		$collapse	= HtmlTag::create( 'div', $listTabs, ['class' => "collapse navbar-collapse", 'id' => $collapseId] );
		$content	= $this->renderBrand( 'navbar-brand' ).$toggle.$collapse;
		if( BsVersion::V5 === $this->getBsVersion() )								//  Bootstrap 5: container is required
			$content	= HtmlTag::create( 'div', $content, ['class' => 'container-fluid'] );
		$classes	= 'navbar navbar-expand-lg navbar-light bg-light'.( NULL !== $this->fixed ? ' fixed-'.$this->fixed : '' );
		return HtmlTag::create( 'nav', $content, ['class' => $classes] );
	}
}
