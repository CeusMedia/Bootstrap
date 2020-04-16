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

	protected $set		= NULL;
	protected $size		= array();
	protected $style	= FALSE;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$icon 		Icon class name plus modifying class names
	 *	@param		string			$style 		Icon set style (see code doc of setStyle)
	 *	@param		string|array	$size 		One or many size or modifier class name (see code doc of setSize)
	 *	@return		void
	 */
	public function __construct( $icon, $style = NULL, $size = NULL )
	{
		$style	= $style === TRUE ? 'white' : $style;												//  legacy: glyphicons white @todo remove
		$this->setSet( self::$defaultSet );
		$this->setIcon( $icon );
		$this->setStyle( $style ? $style : static::$defaultStyle );
		if( $size )
			$this->setSize( $size );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component or exception message
	 */
	public function __toString(): string
	{
		try{
			$string	= $this->render();
			return $string;
		}
		catch( \Exception $e ){
			$message	= '... failed: '.$e->getMessage();
			\trigger_error( $message, E_USER_ERROR | E_RECOVERABLE_ERROR );							//  trigger recoverable user error
//			print $e->getMessage();																	//  if app is still alive: print exception message
//			exit;																					//  if app is still alive: exit application
		}
	}

	/**
	 *	Create icon object by static call.
	 *	For arguments see code doc of contructor.
	 *	@static
	 *	@access		public
	 *	@return		object		Icon instance for chainability
	 */
	static public function create(): self
	{
		return \Alg_Object_Factory::createObject( static::class, func_get_args() );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$class		= $this->resolve( $this->icon );
		return \UI_HTML_Tag::create( 'i', "", array( 'class' => $class ) );
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
	public function setSet( $set ): self
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
	 *	@param		string		$size 		One or many size or modifier class name
	 *	@return		self		Own instance for chainability
	 */
	public function setSize( $sizes ): self
	{
		$this->size		= array();
		if( !is_array( $sizes ) ){
			if( !is_string( $sizes ) )
				throw new \InvalidArgumentException( 'Size must be of array or string' );
			$sizes	= preg_split( "/\s+/", trim( $sizes ) );
		}
		foreach( $sizes as $size )
			if( trim( $size ) )
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
	public function setStyle( $style ): self
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
