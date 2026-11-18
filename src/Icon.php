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

use CeusMedia\Bootstrap\Base\Structure;

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

use function explode;
use function in_array;
use function is_string;
use function join;
use function preg_match;
use function preg_split;
use function str_starts_with;
use function strtolower;
use function trim;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Icon extends Structure
{
	public static string $defaultSet		= 'glyphicons';
	public static array $defaultSize		= [];
	public static string $defaultStyle		= '';

	protected string $icon;
	protected ?string $set		= NULL;
	/** @var array<string> $size */
	protected array $size		= [];
	protected string $style;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string				$icon 		Icon class name plus modifying class names
	 *	@param		string|NULL			$style 		Icon set style (see code doc of setStyle)
	 *	@param		array|string|NULL	$size 		One or many size or modifier class name (see code doc of setSize)
	 *	@return		void
	 */
	public function __construct( string $icon, ?string $style = NULL, array|string|null $size = NULL )
	{
		parent::__construct();
		$this->setSet( self::$defaultSet );
		$this->setIcon( $icon );
		$this->setStyle( $style ?? static::$defaultStyle );
		if( NULL !== $size )
			$this->setSize( $size );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$class		= $this->resolve( $this->icon );
		return HtmlTag::create( 'i', '', ['class' => $class] );
	}

	/**
	 *	Set icon by its icon class name plus modifying class names.
	 *	@access		public
	 *	@param		string		$icon 		Icon class name plus modifying class names
	 *	@return		static		Own instance for method chaining
	 */
	public function setIcon( string $icon ): static
	{
		$this->icon		= $icon;
		return $this;
	}

	/**
	 *	Set icon set, like fontawesome[4|5] or glyphicons.
	 *	@access		public
	 *	@param		string		$set 		Icon set key, like fontawesome[4|5] or glyphicons
	 *	@return		static		Own instance for method chaining
	 */
	public function setSet( string $set ): static
	{
		$this->set	= trim( $set );
		return $this;
	}

	/**
	 *	Set size or other modifiers by one or many class names.
	 *
	 *	FontAwesome 4 & 5:
	 *	- fixed: fw (alias: fixed)
	 *	- scale: 1x - 9x
	 *	- modifiers: see FontAwesome doc
	 *
	 *	@access		public
	 *	@param		string|array<string>	$sizes 		One or many size or modifier class name
	 *	@return		static					Own instance for method chaining
	 */
	public function setSize( string|array $sizes ): static
	{
		$this->size		= [];
		if( is_string( $sizes ) )
			$sizes	= (array) preg_split( "/\s+/", trim( $sizes ) );
		foreach( $sizes as $size )
			if( strlen( trim( (string) $size ) ) > 0 )
				$this->size[]	= trim( (string) $size );
		return $this;
	}

	/**
	 *	Set icon set style to use for this icon.
	 *	Default: none (use static defaultStyle)
	 *
	 *	FontAwesome 4:
	 *	- white
	 *
	 *	FontAwesome 5 (fontawesome5:
	 *	- solid (default)
	 *	- regular
	 *	- light
	 *	- brand
	 *
	 *	@access		public
	 *	@param		string		$style 		Icon set style
	 *	@return		static					Own instance for method chaining
	 *	@todo		code doc
	 */
	public function setStyle( string $style ): static
	{
		$this->style	= trim( $style );
		return $this;
	}

	//  --  PROTECTED  --  //

	protected function realizeSizes(): array
	{
		$sizes	= [] !== $this->size ? $this->size : static::$defaultSize;
		$list	= [];
		foreach( $sizes as $size ){
			switch( strtolower( $this->set ?? '' ) ){
				case 'fontawesome':
				case 'fontawesome4':
				case 'fontawesome5':
				case 'fontawesome6':
					$size	= 'fixed' === $size ? 'fw' : $size;										//  translate generic 'fixed' to FontAwesome's 'fw'
					if( 1 === preg_match( $regExpFactor = '/^x([1-9])$/', (string) $size ) )		//  translate sizes like 'x2' (allowed: 1-9)
						$size	= preg_replace( $regExpFactor, '\\1x', (string) $size );	//  ... to 2x
					$list[]	= 'fa-'.$size;															//  ...
					break;
				default:																			//  icon set not known
					$list[]	= $size;																//  forward size without modification
			}
		}
		return $list;
	}

	/**
	 *	@return		array
	 */
	protected function realizeStyle(): array
	{
		$style	= '' !== $this->style ? $this->style : static::$defaultStyle;
		$list	= [];
		switch( strtolower( $this->set ?? '' ) ){
			case 'glyphicons':
				if( 'white' === $style )
					$list[]	= 'icon-white';
				break;
			case 'fontawesome6':
				$list[]	= '' !== $style ? 'fa-'.$style : 'fa';
				break;
			case 'fontawesome5':
				$list[]	= match( $style ){
					'regular'	=> 'fa far',
					'light'		=> 'fa fal',
					'brand'		=> 'fa fab',
					default		=> 'fa fas',
				};
				break;
			case 'fontawesome4':
			case 'fontawesome':
				$list[]	= 'fa';
				break;
		}
		return $list;
	}

	/**
	 *	Indicates whether given icon string already contains a FontAwesome style class
	 *	(like fa, fas, far, fal, fab, fad, fat or fa-solid, fa-regular, fa-brands, ...).
	 *	In this case, the icon string is considered to be complete and will not be prefixed.
	 *	@param		string		$icon
	 *	@return		bool
	 */
	protected static function hasFontAwesomeStyleClass( string $icon ): bool
	{
		$regExp	= '/(^| )(fa[srlbdt]?|fa-(solid|regular|light|thin|duotone|brands|sharp))( |$)/';
		return 1 === preg_match( $regExp, $icon );
	}

	protected function resolve( string $icon ): string
	{
		$icon		= trim( preg_replace( "/ +/", " ", $icon ) ?? '' );
		$parts		= explode( " ", $icon );
		if( static::hasFontAwesomeStyleClass( $icon ) )									//  icon classes are complete already
			$list	= $parts;															//  ... so take them as they are
		else{
			$list	= $this->realizeStyle();
			foreach( $parts as $part ){
				switch( strtolower( $this->set ?? '' ) ){
					case 'glyphicons':
						$part	= "icon-".$part;
						break;
					case 'fontawesome':
					case 'fontawesome4':
					case 'fontawesome5':
					case 'fontawesome6':
						if( !str_starts_with( $part, 'fa-' ) )									//  avoid double prefix
							$part	= 'fa-'.$part;
						break;
				}
				$list[]		= $part;
			}
		}
		foreach( $this->realizeSizes() as $class )
			if( !in_array( $class, $list, TRUE ) )										//  avoid size given twice
				$list[]	= $class;
		return join( " ", $list );
	}
}
