<?php
declare(strict_types=1);

/**
 *	Card (panel, well): box with optional header, title, body and footer.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\AlertClassResolver;
use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Base\StateClassParser;
use CeusMedia\Bootstrap\Base\Element;
use CeusMedia\Common\Renderable;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use Stringable;

/**
 *	Card: box with optional header, title, body and footer, rendered for set Bootstrap version.
 *
 *	Bootstrap 2: well (colored: alert), Bootstrap 3: panel, since Bootstrap 4: card.
 *	Colors are given by class names of any version (like "panel-danger", "border-danger") or constants.
 *	Colored cards look like panels of Bootstrap 3: colored header and border, white body.
 *
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 */
class Card extends Element
{
	public const string CLASS_DEFAULT	= '';
	public const string CLASS_PRIMARY	= 'panel-primary';
	public const string CLASS_SUCCESS	= 'panel-success';
	public const string CLASS_INFO		= 'panel-info';
	public const string CLASS_WARNING	= 'panel-warning';
	public const string CLASS_DANGER	= 'panel-danger';

	public const array CLASSES			= [
		self::CLASS_DEFAULT,
		self::CLASS_PRIMARY,
		self::CLASS_SUCCESS,
		self::CLASS_INFO,
		self::CLASS_WARNING,
		self::CLASS_DANGER,
	];

	protected Stringable|Renderable|string|null $header		= NULL;

	protected Stringable|Renderable|string|null $title		= NULL;

	protected Stringable|Renderable|string|null $footer		= NULL;

	/**
	 *	@param		Stringable|Renderable|string|array|NULL		$content	Body
	 *	@param		Stringable|Renderable|string|NULL			$header		Header
	 *	@param		array|string|NULL							$class		Color (like CLASS_DANGER) and other class names
	 */
	public function __construct(
		Stringable|Renderable|string|array|null $content,
		Stringable|Renderable|string|null $header = NULL,
		array|string|null $class = NULL
	)
	{
		parent::__construct( $content, $class );
		$this->setHeader( $header );
	}

	/**
	 *	Renders card for set Bootstrap version.
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$version	= $this->getBsVersion();
		$context	= new StateClassParser( $this->classes, ['panel', 'card', 'border', 'bg', 'text-bg', 'alert'] );
		$header		= $this->renderContentItem( $this->header );
		$title		= $this->renderContentItem( $this->title );
		$footer		= $this->renderContentItem( $this->footer );
		$body		= $this->getContentAsString();

		[$classes, $parts]	= match( $version ){
			BsVersion::V2	=> $this->renderV2( $context->state, $header, $title, $body, $footer ),
			BsVersion::V3	=> $this->renderV3( $context->state, $header, $title, $body, $footer ),
			BsVersion::V4, BsVersion::V5	=> $this->renderV4( $version, $context->state, $header, $title, $body, $footer ),
		};
		$attributes	= [
			'id'	=> $this->id,
			'class'	=> join( ' ', array_unique( array_merge( $classes, $context->others ) ) ),
		];
		$this->extendAttributesByData( $attributes );
		$this->extendAttributesByEvents( $attributes );
		return HtmlTag::create( 'div', join( '', $parts ), $attributes );
	}

	/**
	 *	@access		public
	 *	@param		Stringable|Renderable|string|NULL		$footer
	 *	@return		static
	 */
	public function setFooter( Stringable|Renderable|string|null $footer ): static
	{
		$this->footer	= $footer;
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		Stringable|Renderable|string|NULL		$header
	 *	@return		static
	 */
	public function setHeader( Stringable|Renderable|string|null $header ): static
	{
		$this->header	= $header;
		return $this;
	}

	/**
	 *	Sets title, shown as heading at top of body.
	 *	@access		public
	 *	@param		Stringable|Renderable|string|NULL		$title
	 *	@return		static
	 */
	public function setTitle( Stringable|Renderable|string|null $title ): static
	{
		$this->title	= $title;
		return $this;
	}

	/*  --  PROTECTED  --  */

	/**
	 *	Bootstrap 2: well, or alert for colored card, since there are no colored wells.
	 *	@return		array{0: list<string>, 1: list<string>}		Classes of card and parts
	 */
	protected function renderV2( ?string $state, string $header, string $title, string $body, string $footer ): array
	{
		$classes	= match( $state ){
			NULL, 'secondary', 'light', 'dark'	=> ['well'],
			default								=> AlertClassResolver::resolve( BsVersion::V2, ['alert-'.$state] ),
		};
		$parts	= [
			'' !== $header ? HtmlTag::create( 'h4', $header ) : '',
			'' !== $title ? HtmlTag::create( 'h5', $title ) : '',
			$body,
			'' !== $footer ? HtmlTag::create( 'hr' ).HtmlTag::create( 'div', $footer, ['class' => 'muted'] ) : '',
		];
		return [$classes, $parts];
	}

	/**
	 *	Bootstrap 3: panel with heading, body and footer.
	 *	@return		array{0: list<string>, 1: list<string>}		Classes of card and parts
	 */
	protected function renderV3( ?string $state, string $header, string $title, string $body, string $footer ): array
	{
		$color	= match( $state ){
			NULL, 'secondary', 'light', 'dark'	=> 'panel-default',
			default								=> 'panel-'.$state,
		};
		$parts	= [
			'' !== $header ? HtmlTag::create( 'div', $header, ['class' => 'panel-heading'] ) : '',
			HtmlTag::create( 'div', ( '' !== $title ? HtmlTag::create( 'h4', $title ) : '' ).$body, ['class' => 'panel-body'] ),
			'' !== $footer ? HtmlTag::create( 'div', $footer, ['class' => 'panel-footer'] ) : '',
		];
		return [['panel', $color], $parts];
	}

	/**
	 *	Bootstrap 4 and 5: card with header, body and footer; colored by border and header background.
	 *	@return		array{0: list<string>, 1: list<string>}		Classes of card and parts
	 */
	protected function renderV4( BsVersion $version, ?string $state, string $header, string $title, string $body, string $footer ): array
	{
		$classes		= ['card', 'mb-3'];											//  margin like panel and well
		$classesHeader	= ['card-header'];
		if( NULL !== $state ){
			$classes[]		= 'border-'.$state;
			$isLight		= in_array( $state, ['light', 'warning'], TRUE ) || ( BsVersion::V5 === $version && 'info' === $state );
			$classesHeader	= array_merge( $classesHeader, ['bg-'.$state, $isLight ? 'text-dark' : 'text-white'] );
		}
		$parts	= [
			'' !== $header ? HtmlTag::create( 'div', $header, ['class' => join( ' ', $classesHeader )] ) : '',
			HtmlTag::create( 'div', ( '' !== $title ? HtmlTag::create( 'h5', $title, ['class' => 'card-title'] ) : '' ).$body, ['class' => 'card-body'] ),
			'' !== $footer ? HtmlTag::create( 'div', $footer, ['class' => 'card-footer'] ) : '',
		];
		return [$classes, $parts];
	}
}
