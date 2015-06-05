<?php
/**
 *	...
 *	@category		cmModules
 *	@package		Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2013 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@since			0.3.0
 *	@version		$Id$
 */
namespace CeusMedia\Bootstrap;
/**
 *	...
 *	@category		cmModules
 *	@package		Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2013 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@since			0.3.0
 *	@version		$Id$
 */
class Button extends Component{

	const CLASS_DANGER		= "btn-danger";
	const CLASS_INVERSE		= "btn-inverse";
	const CLASS_INFO		= "btn-info";
	const CLASS_SUCCESS		= "btn-success";
	const CLASS_WARNING		= "btn-warning";

	const CLASS_MINI			= "btn-mini";
	const CLASS_SMALL		= "btn-small";
	const CLASS_DEFAULT		= "";
	const CLASS_LARGE		= "btn-large";
	const CLASS_BLOCK		= "btn-block";

	protected $disabled;
	protected $icon;
	protected $name;
	protected $type		= "button";

	public function __construct( $content, $class = NULL, $icon = NULL, $disabled = FALSE ){
		$this->setContent( $content );
		$this->setClass( $class );
		$this->setIcon( $icon );
		$this->setDisabled( $disabled );
	}

	public function setDisabled( $disabled = TRUE ){
		$this->disabled	= $disabled;
	}

	public function setIcon( $icon, $white = FALSE ){
		if( $icon && !( $icon instanceof Icon ) ){
			$class	= join( " ", $this->class );
			$white	= preg_match( "/btn-(primary|danger|warning|info|inverse|success)/", $class );			//
			$icon	= new Icon( $icon, $white );
		}
		$this->icon	= $icon;
	}

	public function setName( $name ){
		$this->name	= $name;
	}

	public function render(){
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
}
?>
