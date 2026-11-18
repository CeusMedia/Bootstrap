<?php
declare(strict_types=1);

/**
 *	Heading with optional muted prefix and subtitle.
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
use CeusMedia\Common\Renderable;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use RangeException;
use Stringable;

/**
 *	Heading (h1 to h6) with optional muted prefix and subtitle, rendered for set Bootstrap version.
 *
 *	Example: <h2><span class="muted">Company</span> Example Inc. <small>since 2012</small></h2>
 *	Muted text: "muted" (Bootstrap 2), "text-muted" (since 3).
 *	Subtitle: small text, muted by Bootstrap 2 and 3, by class "text-muted" since Bootstrap 4.
 *
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 */
class Heading extends Element
{
	protected int $level		= 2;

	protected Stringable|Renderable|string|null $prefix		= NULL;

	protected Stringable|Renderable|string|null $subtitle	= NULL;

	/**
	 *	@param		Stringable|Renderable|string|array|NULL		$content	Title
	 *	@param		int											$level		Level of heading, 1 to 6
	 *	@param		Stringable|Renderable|string|NULL			$prefix		Muted text before title
	 *	@param		array|string|NULL							$class		Additional class names
	 *	@throws		RangeException								if level is invalid
	 */
	public function __construct(
		Stringable|Renderable|string|array|null $content,
		int $level = 2,
		Stringable|Renderable|string|null $prefix = NULL,
		array|string|null $class = NULL
	)
	{
		parent::__construct( $content, $class );
		$this->setLevel( $level );
		$this->setPrefix( $prefix );
	}

	/**
	 *	Renders heading for set Bootstrap version.
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$version	= $this->getBsVersion();
		$classMuted	= match( $version ){
			BsVersion::V2	=> 'muted',
			BsVersion::V3, BsVersion::V4, BsVersion::V5	=> 'text-muted',
		};
		$parts		= [];
		$prefix		= $this->renderContentItem( $this->prefix );
		if( '' !== $prefix )
			$parts[]	= HtmlTag::create( 'span', $prefix, ['class' => $classMuted] );
		$parts[]	= $this->getContentAsString();
		$subtitle	= $this->renderContentItem( $this->subtitle );
		if( '' !== $subtitle ){
			$classSubtitle	= match( $version ){
				BsVersion::V2, BsVersion::V3	=> NULL,							//  small in headings is muted already
				BsVersion::V4, BsVersion::V5	=> $classMuted,
			};
			$parts[]	= HtmlTag::create( 'small', $subtitle, ['class' => $classSubtitle] );
		}
		$attributes	= [
			'id'	=> $this->id,
			'class'	=> [] !== $this->classes ? join( ' ', $this->classes ) : NULL,
		];
		$this->extendAttributesByData( $attributes );
		$this->extendAttributesByEvents( $attributes );
		return HtmlTag::create( 'h'.$this->level, join( ' ', array_filter( $parts, static fn( string $part ): bool => '' !== $part ) ), $attributes );
	}

	/**
	 *	@access		public
	 *	@param		int			$level		Level of heading, 1 to 6
	 *	@return		static
	 *	@throws		RangeException			if level is invalid
	 */
	public function setLevel( int $level ): static
	{
		if( $level < 1 || $level > 6 )
			throw new RangeException( 'Invalid heading level: '.$level );
		$this->level	= $level;
		return $this;
	}

	/**
	 *	Sets muted text before title, like a category or type.
	 *	@access		public
	 *	@param		Stringable|Renderable|string|NULL		$prefix
	 *	@return		static
	 */
	public function setPrefix( Stringable|Renderable|string|null $prefix ): static
	{
		$this->prefix	= $prefix;
		return $this;
	}

	/**
	 *	Sets small text after title.
	 *	@access		public
	 *	@param		Stringable|Renderable|string|NULL		$subtitle
	 *	@return		static
	 */
	public function setSubtitle( Stringable|Renderable|string|null $subtitle ): static
	{
		$this->subtitle	= $subtitle;
		return $this;
	}
}
