<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Base class for every component working on one HTML Tag.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2024 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Base;

use CeusMedia\Bootstrap\Base\Aware\ClassAware;
use CeusMedia\Bootstrap\Base\Aware\DataAware;
use CeusMedia\Bootstrap\Base\Aware\EventAware;
use CeusMedia\Bootstrap\Base\Aware\ContentAware;
use CeusMedia\Bootstrap\Base\Aware\IdAware;
use CeusMedia\Common\Alg\Obj\Factory as ObjectFactory;
use CeusMedia\Common\Renderable;
use ReflectionException;
use Stringable;

/**
 *	Base class for every component working on one HTML Tag.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2024 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
abstract class Element extends Abstraction implements Renderable, Stringable
{
	use ClassAware, ContentAware, DataAware, EventAware, IdAware;


	/**
	 *	@param		Stringable|Renderable|string|array|NULL		$content
	 *	@param		array|string|NULL			$class
	 */
	public function __construct( Stringable|Renderable|string|array|null $content, array|string|null $class = NULL )
	{
		parent::__construct();
		$this->setContent( $content );
		if( NULL !== $class )
			$this->setClass( $class );
	}

	/**
	 *	Create object of inheriting element class by static call.
	 *	For arguments see code doc of constructor.
	 *	@static
	 *	@access		public
	 *	@return		self		Component instance for method chaining
	 *	@throws		ReflectionException
	 */
	public static function create(): self
	{
		/** @noinspection PhpUnhandledExceptionInspection */
		/** @var self $element */

		$element	= ObjectFactory::createObject( static::class, func_get_args() );
		return $element;
	}
}
