<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\Component;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Button extends Component
{
	const CLASS_DANGER		= "btn-danger";
	const CLASS_INVERSE		= "btn-inverse";
	const CLASS_INFO		= "btn-info";
	const CLASS_SUCCESS		= "btn-success";
	const CLASS_WARNING		= "btn-warning";

	const CLASS_MINI		= "btn-mini";
	const CLASS_SMALL		= "btn-small";
	const CLASS_DEFAULT		= "";
	const CLASS_LARGE		= "btn-large";
	const CLASS_BLOCK		= "btn-block";

	protected $disabled;
	protected $icon;
	protected $name;
	protected $type			= "button";

	public function __construct( $content, $class = NULL, $icon = NULL, $disabled = FALSE )
	{
		parent::__construct( $content, $class );
//		$this->setContent( $content );
//		$this->setClass( $class );
		$this->setIcon( $icon );
		$this->setDisabled( $disabled );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$attributes	= array(
			'name'		=> $this->name,
			'id'		=> $this->id,
			'type'		=> $this->type,
			'class'		=> "btn ".join( " ", $this->class ),
			'disabled'	=> $this->disabled ? "disabled" : NULL,
		);
		$this->extendAttributesByEvents( $attributes );
		$this->extendAttributesByData( $attributes );
		$icon	= $this->icon ? $this->icon->render().' ' : "";
		return \UI_HTML_Tag::create( 'button', $icon.$this->content, $attributes );
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setDisabled( $disabled = TRUE ): self
	{
		$this->disabled	= $disabled;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setIcon( $icon, $white = FALSE ): self
	{
		if( $icon && !( $icon instanceof Icon ) ){
			$class	= join( " ", $this->class );
			$icon	= new Icon( $icon, $white );
		}
		$this->icon	= $icon;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setName( $name ): self
	{
		$this->name	= $name;
		return $this;
	}
}
