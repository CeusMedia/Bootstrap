<?php
/**
 *	Base class for every component working on one HTML Tag.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2022 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Base;

use CeusMedia\Bootstrap\Base\Aware\ClassAware;
use CeusMedia\Bootstrap\Base\Aware\DataAware;
use CeusMedia\Bootstrap\Base\Aware\EventAware;
use CeusMedia\Bootstrap\Base\Aware\ContentAware;
use CeusMedia\Bootstrap\Base\Aware\IdAware;

use CeusMedia\Common\Alg\Obj\Factory as ObjectFactory;
use Exception;

/**
 *	Base class for every component working on one HTML Tag.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2022 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
abstract class Element
{
	use ClassAware, ContentAware, DataAware, EventAware, IdAware;

	public static $version			= "0.5.2";
	public static $defaultBsVersion	= "2.3.2";

	protected $bsVersion;

	public function __construct( $content, $class = NULL )
	{
		$this->bsVersion		= static::$defaultBsVersion;
		$this->setContent( $content );
		$this->setClass( $class );
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
		catch( Exception $e ){
			$message	= '... failed: '.$e->getMessage();
			trigger_error( $message, E_USER_ERROR | E_RECOVERABLE_ERROR );						//  trigger recoverable user error
//			print $e->getMessage();																//  if app is still alive: print exception message
//			exit;																				//  if app is still alive: exit application
			return '';
		}
	}

	/**
	 *	Create icon object by static call.
	 *	For arguments see code doc of contructor.
	 *	@static
	 *	@access		public
	 *	@return		self		Component instance for method chaining
	 */
	public static function create(): self
	{
		return ObjectFactory::createObject( static::class, func_get_args() );
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
	 *	@abstract				To be implemented by derived components
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	abstract public function render();
}
