<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Modal trigger generator.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2023 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Modal;

use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Base\Aware\ClassAware;
use CeusMedia\Bootstrap\Base\Aware\IconAware;
use CeusMedia\Bootstrap\Base\Aware\IdAware;
use CeusMedia\Bootstrap\Icon;

use CeusMedia\Common\Alg\Obj\Factory as ObjectFactory;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

use Exception;
use RangeException;
use ReflectionException;
use RuntimeException;

use function sprintf;

/**
 *	Modal trigger generator.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2023 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Trigger
{
	use IdAware, ClassAware, IconAware;

	protected array $attributes	= [];
	protected ?string $label;
	protected ?string $modalId;
	protected string $type			= "button";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string|NULL			$modalId		ID of modal dialog container
	 *	@param		string|NULL			$label			Label of trigger
	 *	@param		string|array|NULL	$class
	 *	@param		Icon|string|NULL	$icon
	 *	@return		void
	 */
	public function __construct( ?string $modalId = NULL, ?string $label = NULL, $class = NULL, $icon = NULL )
	{
		if( !is_null( $modalId ) )
			$this->setModalId( $modalId );
		if( !is_null( $label ) )
			$this->setLabel( $label );
		if( !is_null( $class ) )
			$this->setClass( $class );
		if( !is_null( $icon ) )
			$this->setIcon( $icon );
	}

	/**
	 *	Create modal trigger object by static call.
	 *	For arguments see code doc of constructor.
	 *	@static
	 *	@access		public
	 *	@return		self		Modal trigger instance for method chaining
	 *	@throws		ReflectionException
	 */
	public static function create(): self
	{
		/** @var Trigger $trigger */
		$trigger	= ObjectFactory::createObject( static::class, func_get_args() );
		return $trigger;
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
			print $e->getMessage();
			exit;
		}
	}

	public function asButton( bool $asButton = TRUE ): self
	{
		$this->type		= $asButton ? "button" : "link";
		return $this;
	}

	public function asLink( bool $asLink = TRUE ): self
	{
		$this->type		= $asLink ? "link" : "button";
		return $this;
	}

	/**
	 *	Returns rendered component.
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 *	@throws		RuntimeException		if no label is set
	 *	@throws		RuntimeException		if no modal ID is set
	 *	@throws		RangeException			if set type is not supported
	 */
	public function render(): string
	{
		if( !$this->label )
			throw new RuntimeException( 'No label set' );
		if( !$this->modalId )
			throw new RuntimeException( 'No modal ID set' );
		$attributes	= array(
			'id'			=> $this->id,
			'href'			=> "#".$this->modalId,
			'role'			=> "button",
			'class'			=> "btn ".join( ' ', $this->classes ),
			'data-toggle'	=> "modal",
		);
		foreach( $this->attributes as $key => $value ){
			switch( strtolower( $key ) ){
				case 'id':
				case 'href':
				case 'role':
				case 'data-toggle':
					break;
/*				case 'class':
					$attributes['class']	.= strlen( trim( $value ) ) ? ' '.$value : '';
					break;
*/				default:
					$attributes[$key]	= $value;
			}
		}
		$label	= $this->label;
		if( $this->icon )
			$label	= $this->icon.'&nbsp;'.$label;

		if( $this->type === 'link' )
			return HtmlTag::create( 'a', $label, $attributes );
		if( $this->type === 'button' ){
			$attributes	= array_merge( $attributes, array( 'type' => 'button' ) );
			return HtmlTag::create( 'button', $label, $attributes );
		}
		throw new RangeException( sprintf( 'Unsupported type: %s', $this->type ) );
	}

	/**
	 *	Sets additional button attributes.
	 *	Set values for id, href, role, data-toggle will be ignored.
	 *	All others will set or override existing values.
	 *	@access		public
	 *	@param		array		$attributes		Map of button attributes
	 *	@return		self
	 */
	public function setAttributes( array $attributes ): self
	{
		$this->attributes	= $attributes;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$label			...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setLabel( string $label ): self
	{
		$this->label	= $label;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$modalId		...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setModalId( string $modalId ): self
	{
		$this->modalId	= $modalId;
		return $this;
	}
}
