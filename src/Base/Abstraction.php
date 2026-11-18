<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

namespace CeusMedia\Bootstrap\Base;

use CeusMedia\Bootstrap\Base\Dialect\Dialect;
use CeusMedia\Common\Alg\Obj\Factory as ObjectFactory;
use CeusMedia\Common\Renderable;
use DomainException;
use Exception;
use ReflectionException;
use Stringable;

use function func_get_args;

abstract class Abstraction implements Renderable, Stringable
{
	public static string $version			= "0.7.0";

	/**
	 *	@var	string		Bootstrap version used by new components.
	 *						Please use setDefaultBsVersion() to set it, which validates the version.
	 */
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
		$installVersion ??= static::$version;
		return version_compare( $version, $installVersion, '>=' );
	}

	/**
	 *	Returns major Bootstrap version used by new components.
	 *	@access		public
	 *	@static
	 *	@return		BsVersion
	 *	@throws		DomainException		if default version is not supported
	 */
	public static function getDefaultBsVersion(): BsVersion
	{
		return BsVersion::fromString( self::$defaultBsVersion );
	}

	/**
	 *	Sets Bootstrap version used by new components, after validating it.
	 *	@access		public
	 *	@static
	 *	@param		BsVersion|string	$bsVersion		Version enum or string, like "2.3.2" or "5"
	 *	@return		void
	 *	@throws		DomainException		if given version is not supported
	 */
	public static function setDefaultBsVersion( BsVersion|string $bsVersion ): void
	{
		if( $bsVersion instanceof BsVersion )
			$bsVersion	= (string) $bsVersion->value;
		BsVersion::fromString( $bsVersion );										//  validate before setting
		self::$defaultBsVersion	= $bsVersion;
	}

	/**
	 *	Returns major Bootstrap version this component is rendered for.
	 *	@access		public
	 *	@return		BsVersion
	 *	@throws		DomainException		if set version is not supported
	 */
	public function getBsVersion(): BsVersion
	{
		return BsVersion::fromString( $this->bsVersion );
	}

	/**
	 *	Sets Bootstrap version this component is rendered for.
	 *	@access		public
	 *	@param		BsVersion|string	$bsVersion		Version enum or string, like "2.3.2" or "5"
	 *	@return		static
	 *	@throws		DomainException		if given version is not supported
	 */
	public function setBsVersion( BsVersion|string $bsVersion ): static
	{
		if( $bsVersion instanceof BsVersion )
			$bsVersion	= (string) $bsVersion->value;
		BsVersion::fromString( $bsVersion );										//  validate early
		$this->bsVersion	= $bsVersion;
		return $this;
	}

	/**
	 *	Creates component by static call, for method chaining.
	 *	For arguments see code doc of constructor of component.
	 *	@static
	 *	@access		public
	 *	@return		static		Component instance for method chaining
	 *	@throws		ReflectionException
	 */
	public static function create(): static
	{
		/** @var static $component */
		$component	= ObjectFactory::createObject( static::class, func_get_args() );
		return $component;
	}

	/**
	 *	@throws		DomainException		if default version is not supported (if set directly, not by setDefaultBsVersion)
	 */
	public function __construct()
	{
		$this->setBsVersion( static::$defaultBsVersion );							//  validates default version
	}

	/**
	 *	Renders component. On failure, a user warning is triggered and an empty string is returned,
	 *	since exceptions should not leave string conversion (and must not end the application).
	 *	@access		public
	 *	@return		string		Rendered HTML of component or empty string on failure
	 */
	public function __toString(): string
	{
		try{
			return $this->render();
		}
		catch( Exception $e ){
			$message	= 'Rendering of '.static::class.' failed: '.$e->getMessage();
			trigger_error( $message, E_USER_WARNING );
			return '';
		}
	}

	/**
	 *	@abstract				To be implemented by derived components
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	abstract public function render(): string;


	/**
	 *	Returns dialect of set Bootstrap version, translating neutral (Bootstrap 5) class names and data attributes.
	 *	@access		protected
	 *	@return		Dialect
	 */
	protected function dialect(): Dialect
	{
		return $this->getBsVersion()->dialect();
	}

	protected function realizeRenderableOrStringableProperty( string $propertyName ): ?string
	{
		if( !property_exists( $this, $propertyName ) )
			throw new DomainException( 'Property "'.$propertyName.'" does not exist.' );
		/** @phpstan-ignore-next-line */
		$value = $this->{$propertyName};
		if( $value instanceof Renderable )
			$value = $value->render();
		else if( $value instanceof Stringable )
			$value = $value->__toString();
		return strval( $value );
	}
}