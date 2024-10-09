<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2023 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Nav;

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
 *	@copyright		2012-2023 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class TabbableNavbar extends Structure
{
	protected ?int $active			= NULL;
	protected array $tabs			= [];
	protected array $contents		= [];
	protected string $classNavBar	= "navbar";

	/** @var Stringable|Renderable|string|NULL $brand */
	protected Stringable|Renderable|string|null $brand	= NULL;

	protected array $index			= [];

	/**
	 *	@access		public
	 *	@return		self		Own instance for method chaining
	 */
	public function add( string $id, string $label, string $content ): self
	{
		$this->index[]			= $id;
		$this->tabs[$id]		= $label;
		$this->contents[$id]	= $content;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$index = $this->index;
		$active	= $this->active;
		if( is_null( $active ) )
			$active	= array_shift( $index );

		$listTabs	= [];
		foreach( $this->index as $id ){
			$attributes	= [
				'href'			=> '#'.$id,
				'data-toggle'	=> "tab",
			];
			$label	= $this->tabs[$id];
#			$label	= htmlentities( $label, ENT_QUOTES, 'UTF-8' );
			$link	= HtmlTag::create( 'a', $label, $attributes );
			$attributes	= ['class' => $active == $id ? "active" : NULL];
			$listTabs[]	= HtmlTag::create( 'li', $link, $attributes );
		}
		$attributes	= ['class' => "nav"];
		$listTabs	= HtmlTag::create( 'ul', $listTabs, $attributes );

		$listDivs	= [];
		foreach( $this->index as $id ){
			$attributes	= [
				'id'	=> $id,
				'class'	=> $active == $id ? "tab-pane active" : "tab-pane",
			];
			$listDivs[]	= HtmlTag::create( 'div', $this->contents[$id], $attributes );
		}
		$attributes	= ['class' => "tab-content"];
		$listDivs	= HtmlTag::create( 'div', $listDivs, $attributes );

		$toggleSpan	= HtmlTag::create( 'span', "", ['class' => 'icon-bar'] );
		$attributes	= [
			'data-toggle'	=> 'collapse',
			'data-target'	=> '.nav-collapse',
			'class'			=> 'btn btn-navbar',
		];
		$brand		= $this->realizeRenderableOrStringableProperty( 'brand' );
		$toggle		= HtmlTag::create( 'a', str_repeat( $toggleSpan, 3 ), $attributes );
		$collapse	= HtmlTag::create( 'div', $listTabs, ['class' => "nav-collapse collapse"] );
		$container	= HtmlTag::create( 'div', $toggle.$brand.$collapse, ['class' => "container"] );

		$tabs		= HtmlTag::create( 'div', $container, ['class' => "navbar-inner"] );	//
		$navbar		= HtmlTag::create( 'div', $tabs, ['class' => $this->classNavBar] );			//
		return HtmlTag::create( 'div', $navbar.$listDivs, ['class' => "tabbable"] );		//
	}

	/**
	 *	Sets active tab by its number.
	 *	@access		public
	 *	@param		integer		$nr			Number of tab to mark as active.
	 *	@return		self		Own instance for method chaining
	 */
	public function setActive( int $nr ): self
	{
		$this->active	= $nr;
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		Stringable|Renderable|string	$label
	 *	@param		URL|string|NULL		$url
	 *	@return		self				Own instance for method chaining
	 */
	public function setBrand( Stringable|Renderable|string $label, URL|string|null $url = NULL ): self
	{
		$this->brand	= HtmlTag::create( 'span', $label, ['class' => 'brand'] );
		if( $url !== NULL )
			$this->brand	= new Link( $url, $label, "brand" );
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for method chaining
	 */
	public function setFixed( string $position = NULL ): self
	{
		$this->classNavBar = match ($position) {
			'top'		=> "navbar navbar-fixed-top",
			'bottom'	=> "navbar navbar-fixed-bottom",
			default		=> "navbar",
		};
		return $this;
	}
}
