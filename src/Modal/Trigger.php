<?php
/**
 *	Modal trigger generator.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Modal;

use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Base\Aware\ClassAware;
use CeusMedia\Bootstrap\Base\Aware\IconAware;
use CeusMedia\Bootstrap\Base\Aware\IdAware;

/**
 *	Modal trigger generator.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
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
	public function __construct( $modalId = NULL, $label = NULL, $class = NULL, $icon = NULL ){
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
	 *	@return		object		Modal trigger instance for chainability
	 */
	static public function create(){
		return \Alg_Object_Factory::createObject( static::class, func_get_args() );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component or exception message
	 */
	public function __toString(){
		try{
			return $this->render();
		}
		catch( \Exception $e ){
			print $e->getMessage();
			exit;
		}
	}

	public function asButton( $asButton = TRUE ){
		$this->type		= (bool) $asButton ? "button" : "link";
		return $this;
	}

	public function asLink( $asLink = TRUE ){
		$this->type		= (bool) $asLink ? "link" : "button";
		return $this;
	}

	/**
	 *	Returns rendered component.
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(){
		if( !$this->label )
			throw new \RuntimeException( 'No label set' );
		if( !$this->modalId )
			throw new \RuntimeException( 'No modal ID set' );
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
				$icon	= \CeusMedia\Bootstrap\Icon::create(
					$icon,
					$this->iconStyle,
					$this->iconSize
				);
			$label	= $icon.'&nbsp;'.$label;
		}

		if( $this->type === 'link' )
			return \UI_HTML_Tag::create( 'a', $label, $attributes );
		if( $this->type === 'button' ){
			$attributes	= array_merge( $attributes, array( 'type' => 'button' ) );
			return \UI_HTML_Tag::create( 'button', $label, $attributes );
		}
		throw new \RangeException( sprinf( 'Unsupported type: %s', $this->type ) );
	}

	/**
	 *	Sets additional button attributes.
	 *	Set values for id, href, role, data-toggle will be ignored.
	 *	All others will set or override existing values.
	 *	@access		public
	 *	@param		array		$attributes		Map of button attributes
	 *	@return		self
	 */
	public function setAttributes( $attributes ){
		$this->attributes	= $attributes;
		return $this;
	}

	public function setIcon( $icon, $style = NULL, $size = NULL ){
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
	public function setLabel( $label ){
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
	public function setModalId( $modalId ){
		$this->modalId	= $modalId;
		return $this;
	}
}
