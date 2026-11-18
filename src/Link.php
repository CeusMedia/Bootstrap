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
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Base\Element;
use CeusMedia\Bootstrap\Base\Aware\AriaAware;
use CeusMedia\Bootstrap\Base\Aware\DisabledAware;
use CeusMedia\Bootstrap\Base\Aware\IconAware;

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
class Link extends Element
{
	use AriaAware, DisabledAware, IconAware;

	/** @var array<string> Classes of Bootstrap, which style disabled links themselves */
	protected const array CLASSES_WITH_DISABLED_STYLE	= ['btn', 'nav-link', 'dropdown-item', 'page-link', 'list-group-item'];

	protected URL|string $url;

	/**
	 *	@param		URL|string					$url
	 *	@param		Stringable|Renderable|string|NULL	$content
	 *	@param		array|string|NULL		$class
	 *	@param		Icon|string|NULL		$icon
	 *	@param		bool					$disabled
	 */
	public function __construct(
		URL|string $url,
		Stringable|Renderable|string|null $content,
		array|string|null $class = NULL,
		Icon|string|null $icon = NULL,
		bool $disabled = FALSE
	)
	{
		parent::__construct( $content, $class );
		$this->setUrl( $url );
		if( NULL !== $icon )
			$this->setIcon( $icon );
		$this->setDisabled( $disabled );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$attributes		= [
			'id'		=> $this->id,
			'href'		=> $this->url,
			'class'		=> $this->classes,
		];
		$this->extendAttributesByClass( $attributes );
		$this->extendAttributesByAria( $attributes );
		$this->extendAttributesByEvents( $attributes );
		$this->extendAttributesByData( $attributes );
		if( $this->disabled ){
			$classes	= $this->classes;
			if( !in_array( 'disabled', $classes, TRUE ) )
				$classes[]	= 'disabled';
			$hasOwnDisabledStyle	= [] !== array_intersect( self::CLASSES_WITH_DISABLED_STYLE, $classes );
			if( !$hasOwnDisabledStyle ){												//  plain link: looks like a link without href otherwise
				$classes	= array_merge( $classes, match( $this->getBsVersion() ){
					BsVersion::V2					=> ['muted'],
					BsVersion::V3, BsVersion::V4	=> ['text-muted'],
					BsVersion::V5					=> ['text-muted', 'text-decoration-none'],
				} );
			}
			$attributes['class']			= join( ' ', $classes );
			$attributes['data-attr-href']	= $attributes['href'];						//  keep URL for re-enabling, without changing state
			$attributes['href']				= NULL;
			$attributes['data-attr-onclick']	= $attributes['onclick'] ?? NULL;		//  click events, kept for re-enabling
			$attributes['onclick']			= NULL;
			$attributes['aria-disabled']	= 'true';
		}
		$icon	= (string) $this->icon;
		$icon	= 0 !== strlen( $icon ) ? $icon.' ' : '';
		return HtmlTag::create( 'a', $icon.$this->getContentAsString(), $attributes );
	}

	/**
	 *	@param		URL|string	$url
	 *	@return		static		Own instance for method chaining
	 */
	public function setUrl( URL|string $url ): static
	{
		$this->url	= $url;
		return $this;
	}
}
