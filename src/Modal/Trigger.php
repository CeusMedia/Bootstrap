<?php
namespace CeusMedia\Bootstrap\Modal;
class Trigger{

	protected $attributes	= array();
	protected $id;
	protected $label;
	protected $modalId;
	protected $type		= "button";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$id				ID of modal dialog container
	 */
	public function __construct( $modalId = NULL, $label = NULL ){
		if( !is_null( $modalId ) )
			$this->setModalId( $modalId );
		if( !is_null( $label ) )
			$this->setLabel( $label );
	}

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
	}

	public function asLink( $asLink = TRUE ){
		$this->type		= (bool) $asLink ? "link" : "button";
	}

	/**
	 *	Returns rendered component.
	 *	@access		public
	 *	@return		string
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
			'class'			=> "btn",
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
		if( $this->type === 'link' )
			return \UI_HTML_Tag::create( 'a', $this->label, $attributes );
		if( $this->type === 'button' ){
			$attributes	= array_merge( $attributes, array( 'type' => 'button' ) );
			return \UI_HTML_Tag::create( 'button', $this->label, $attributes );
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

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$id				...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setId( $id ){
		$this->id		= $id;
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
?>
