<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\Structure;

use Exception;
use InvalidArgumentException;
use UI_HTML_Tag as HtmlTag;

use function explode;
use function is_array;
use function is_string;
use function join;
use function preg_match;
use function preg_split;
use function strtolower;
use function trigger_error;
use function trim;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Icon extends Structure
{
	static $defaultSet		= 'glyphicons';
	static $defaultSize		= array();
	static $defaultStyle	= '';

	protected $icon;
	protected $set		= NULL;
	protected $size		= array();
	protected $style	= FALSE;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$icon 		Icon class name plus modifying class names
	 *	@param		string|NULL		$style 		Icon set style (see code doc of setStyle)
	 *	@param		string|array	$size 		One or many size or modifier class name (see code doc of setSize)
	 *	@return		void
	 */
	public function __construct( string $icon, ?string $style = NULL, $size = NULL )
	{
		$this->setSet( self::$defaultSet );
		$this->setIcon( $icon );
		$this->setStyle( $style ? $style : static::$defaultStyle );
		if( $size )
			$this->setSize( $size );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$class		= $this->resolve( $this->icon );
		return HtmlTag::create( 'i', '', array( 'class' => $class ) );
	}

	/**
	 *	Set icon by its icon class name plus modifying class names.
	 *	@access		public
	 *	@param		string		$icon 		Icon class name plus modifying class names
	 *	@return		self		Own instance for chainability
	 */
	public function setIcon( $icon ): self
	{
		$this->icon		= $icon;
		return $this;
	}

	/**
	 *	Set icon set, like fontawesome[4|5] or glyphicons.
	 *	@access		public
	 *	@param		string		$set 		Icon set key, like fontawesome[4|5] or glyphicons
	 *	@return		self		Own instance for chainability
	 */
	public function setSet( string $set ): self
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
	 *	@param		string		$sizes 		One or many size or modifier class name
	 *	@return		self		Own instance for chainability
	 */
	public function setSize( string $sizes ): self
	{
		$this->size		= array();
		$sizes	= preg_split( "/\s+/", trim( $sizes ) );
		foreach( $sizes as $size )
			if( strlen( trim( $size ) ) > 0 )
				$this->size[]	= trim( $size );
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
	 *	@return		self		Own instance for chainability
	 *	@todo		code doc
	 */
	public function setStyle( string $style ): self
	{
		$this->style	= trim( $style );
		return $this;
	}

	//  --  PROTECTED  --  //

	protected function realizeSizes(): array
	{
		$sizes	= $this->size ? $this->size : static::$defaultSize;
		$list	= array();
		foreach( $sizes as $size ){
			switch( strtolower( $this->set ) ){
				case 'fontawesome':
				case 'fontawesome4':
				case 'fontawesome5':
					$size	= $size === 'fixed' ? 'fw' : $size;										//  translate generic 'fixed' to FontAwesome's 'fw'
					if( preg_match( $regExpFactor = '/^x([1-9])$/', $size ) )						//  translate sizes like 'x2' (allowed: 1-9)
						$size	= preg_match( $regExpFactor, '\\1x', $size );						//  ... to 2x
					$list[]	= 'fa-'.$size;															//  ...
					break;
				default:																			//  icon set not known
					$list[]	= $size;																//  forward size without modification
			}
		}
		return $list;
	}

	protected function realizeStyle(): array
	{
		$style	= $this->style ? $this->style : static::$defaultStyle;
		$list	= array();
		switch( strtolower( $this->set ) ){
			case 'glyphicons':
				if( $this->style === 'white' )
					$list[]	= 'icon-white';
				break;
			case 'fontawesome5':
				$style	= 'fas';
				if( $this->style === 'regular' )
					$style	= 'far';
				else if( $this->style === 'light' )
					$style	= 'fal';
				else if( $this->style === 'brand' )
					$style	= 'fab';
				$list[]	= $style;
				break;
			case 'fontawesome4':
			case 'fontawesome':
				$list[]	= 'fa';
				break;
		}
		return $list;
	}

	protected function resolve( $icon ): string
	{
		$parts		= explode( " ", preg_replace( "/ +/", " ", $icon ) );
		$list		= array();
		if( preg_match( '/^fa(r|l|s|b)? fa-/', $icon ) )
			return $icon;
		foreach( $this->realizeStyle() as $style )
			$list[]	= $style;
		foreach( $parts as $part ){
			switch( strtolower( $this->set ) ){
				case 'glyphicons':
					$part	= "icon-".$part;
					break;
				case 'fontawesome5':
					$part	= 'fa-'.$part;
					break;
				case 'fontawesome':
				case 'fontawesome4':
					$part	= 'fa-'.$part;
					break;
			}
			$list[]		= $part;
		}
		foreach( $this->realizeSizes() as $class )
			$list[]	= $class;
		return join( " ", $list );
	}
}
