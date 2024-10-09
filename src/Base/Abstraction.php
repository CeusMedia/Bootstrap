<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

namespace CeusMedia\Bootstrap\Base;

use CeusMedia\Common\Renderable;
use Exception;
use Stringable;

abstract class Abstraction implements Stringable
{
	public static string $version			= "0.6.2";

	public static string $defaultBsVersion	= "2.3.2";

	protected string $bsVersion;

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
	 *	@param		string			$version			Version to check against
	 *	@param		string|NULL		$installVersion		Version to check against
	 *	@return		bool
	 */
	public static function supportsVersion( string $version, ?string $installVersion = NULL ): bool
	{
		$installVersion	= !is_null( $installVersion ) ? $installVersion : static::$version;
		return version_compare( $version, $installVersion, '>=' );
	}

	public function setBsVersion( string $bsVersion ): self
	{
		$this->bsVersion	= $bsVersion;
		return $this;
	}

	public function __construct()
	{
		$this->bsVersion		= static::$defaultBsVersion;
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
//			return '';
		}
	}

	/**
	 *	@abstract				To be implemented by derived components
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	abstract public function render(): string;


	protected function realizeRenderableOrStringableProperty( string $propertyName ): ?string
	{
		if( !property_exists( $this, $propertyName ) )
			throw new \DomainException( 'Property "'.$propertyName.'" does not exist.' );
		$value = $this->{$propertyName};
		if( $value instanceof Renderable )
			$value = $value->render();
		else if( $value instanceof Stringable )
			$value = $value->__toString();
		return strval( $value );
	}
}