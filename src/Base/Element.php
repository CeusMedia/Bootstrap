<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

/**
 *	Base class for components having content and class, given to constructor.
 *	Provides class, content, data, event and ID handling.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Base;

use CeusMedia\Bootstrap\Base\Aware\ClassAware;
use CeusMedia\Bootstrap\Base\Aware\DataAware;
use CeusMedia\Bootstrap\Base\Aware\EventAware;
use CeusMedia\Bootstrap\Base\Aware\ContentAware;
use CeusMedia\Bootstrap\Base\Aware\IdAware;
use CeusMedia\Common\Renderable;
use Stringable;

/**
 *	Base class for components having content and class, given to constructor.
 *	Provides class, content, data, event and ID handling.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
abstract class Element extends Abstraction
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

}
