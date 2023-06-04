<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Modal trigger generator.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
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
use RuntimeException;

use function sprintf;

/**
 *	Modal trigger generator.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Trigger
{
	use IdAware, ClassAware, IconAware;

	protected $attributes	= array();
	protected $icon;
	protected $iconSize;
	protected $iconStyle;
	protected $label;
	protected $modalId;
	protected $type			= "button";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$modalId		ID of modal dialog container
	 *	@param		string		$label			Label of trigger
	 *	@return		void
	 */
	public function __construct( $modalId = NULL, $label = NULL, $class = NULL, $icon = NULL )
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
	 *	For arguments see code doc of contructor.
	 *	@static
	 *	@access		public
	 *	@return		self		Modal trigger instance for chainability
	 */
	public static function create(): self
	{
		return ObjectFactory::createObject( static::class, func_get_args() );
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

	public function asButton( $asButton = TRUE ): self
	{
		$this->type		= (bool) $asButton ? "button" : "link";
		return $this;
	}

	public function asLink( $asLink = TRUE ): self
	{
		$this->type		= (bool) $asLink ? "link" : "button";
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
		if( $this->icon ){
			$icon	= $this->icon;
			if( !is_object( $icon ) )
				$icon	= Icon::create(
					$icon,
					$this->iconStyle,
					$this->iconSize
				);
			$label	= $icon.'&nbsp;'.$label;
		}

		if( $this->type === 'link' )
			return HtmlTag::create( 'rector.php', $label, $attributes );
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
	public function setAttributes( $attributes ): self
	{
		$this->attributes	= $attributes;
		return $this;
	}

	public function setIcon( $icon, $style = NULL, $size = NULL ): self
	{
		$this->icon			= $icon;
		$this->iconStyle	= $style;
		$this->iconSize		= $size;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$label			...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setLabel( $label ): self
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
	public function setModalId( $modalId ): self
	{
		$this->modalId	= $modalId;
		return $this;
	}
}
