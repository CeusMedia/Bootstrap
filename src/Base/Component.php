<?php
/**
 *	Base class for every component working on one HTML Tag.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Base;

/**
 *	Base class for every component working on one HTML Tag.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
abstract class Component
{
	static public $defaultBsVersion	= "2.3.2";
	static public $version			= "0.4.8";

	protected $bsVersion;
	protected $class	= array();
	protected $content	= NULL;
	protected $data		= array();
	protected $events	= array();
	protected $id		= NULL;

	public function __construct( $content, $class = NULL )
	{
		$this->bsVersion		= static::$defaultBsVersion;
		$this->setClass( $class );
		$this->setContent( $content );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component or exception message
	 */
	public function __toString(): string
	{
		try{
			return $this->render();
		}
		catch( \Exception $e ){
			$message	= '... failed: '.$e->getMessage();
			trigger_error( $message, E_USER_ERROR | E_RECOVERABLE_ERROR );						//  trigger recoverable user error
//			print $e->getMessage();																//  if app is still alive: print exception message
//			exit;																				//  if app is still alive: exit application
			return '';
		}
	}

	/**
	 *	Sets one or many HTML/CSS class names, given by string or array.
	 *	Appends new class names to prior added or set class names.
	 *	Accepts string with whitespace separated class names or list of class names.
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function addClass( $class ): self
	{
		if( !is_array( $class ) )
			$class	= explode( " ", $class );
		foreach( $class as $item )
			if( !in_array( $item, $this->class ) )
				$this->class[]	= $item;
		return $this;
	}

	/**
	 *	Create icon object by static call.
	 *	For arguments see code doc of contructor.
	 *	@static
	 *	@access		public
	 *	@return		object		Component instance for chainability
	 */
	static public function create(): self
	{
		return \Alg_Object_Factory::createObject( static::class, func_get_args() );
	}

	protected function extendAttributesByData( &$attributes ): self
	{
		foreach( $this->data as $key => $value )
			$attributes['data-'.strtolower( $key )]	= htmlentities( $value, ENT_QUOTES, 'UTF-8' );
		return $this;
	}

	protected function extendAttributesByEvents( &$attributes ): self
	{
		foreach( $this->events as $event => $actions ){
			$event		= 'on'.strtolower( $event );
			$action		= addslashes( join( '; ', $actions ) );
			$attributes[$event]	= $action;
		}
		return $this;
	}

	/**
	 *	Returns version of installed library.
	 *	@access		public
	 *	@static
	 *	@return		string		Version of installed library.
	 */
	public static function getVersion(): string
	{
		return static::$version;
	}

	/**
	 *	Indicates whether a version is supported by installed library.
	 *	@access		public
	 *	@static
	 *	@param		string		$version		Version to check against
	 *	@return		bool
	 */
	public static function supportsVersion( $version, $installVersion = NULL ): bool
	{
		$installVersion	= !is_null( $installVersion ) ? $installVersion : static::$version;
		return version_compare( $version, $installVersion, '>=' );
	}

	/**
	 *	@access		public
	 *	@param		string		$class		Class to be removed
	 *	@return		self		Own instance for chainability
	 */
	public function removeClass( $class ): self
	{
		$index	= array_search( trim( $class ), $this->class );
		if( $index !== FALSE )
			unset( $this->class[$index] );
		return $this;
	}

	/**
	 *	@abstract				To be implemented by derived components
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	abstract public function render();

	/**
	 *	Sets one or many HTML/CSS class names, given by string or array.
	 *	Clears prior added or set class names.
	 *	Accepts string with whitespace separated class names or list of class names.
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setClass( $class ): self
	{
		$this->class	= array();
		return $this->addClass( $class );
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setContent( $content ): self
	{
		$this->content	= $content;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setData( $key, $value ): self
	{
		$this->data[$key]	= $value;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setEvent( $event, $action ): self
	{
		if( !isset( $this->events[$event] ) )
			$this->events[$event]	= array();
		$this->events[$event][]	= $action;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setId( $id ): self
	{
		$this->id		= $id;
		return $this;
	}
}
