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
class CMM_Bootstrap_SubmitButton extends CMM_Bootstrap_Abstract{

	protected $confirm;
	protected $icon;
	protected $name;
	protected $title;
	protected $events		= array();

	public function __construct( $name, $content, $class = NULL, $icon = NULL, $disabled = FALSE ){
		$this->setName( $name );
		$this->setContent( $content );
		$this->setClass( $class );
		$this->setIcon( $icon );
		$this->setDisabled( $disabled );
	}

	public function setConfirm( $message = NULL ){
		$this->confirm	= $message;
	}

	public function setDisabled( $disabled = TRUE ){
		$this->disabled	= $disabled;
	}

	public function setIcon( $icon, $white = FALSE ){
		if( $icon && !( $icon instanceof CMM_Bootstrap_Icon ) ){
			$class	= join( " ", $this->class );
			$white	= preg_match( "/btn-(primary|danger|warning|info|inverse|success)/", $class );			//
			$icon	= new CMM_Bootstrap_Icon( $icon, $white );
		}
		$this->icon	= $icon;
	}

	public function setTitle( $title ){
		$this->title	= $title;
	}

	public function setName( $name ){
		$this->name		= $name;
	}

	public function render(){
		$attributes	= array(
			'type'		=> 'submit',
			'id'		=> $this->id,
			'name'		=> $this->name,
			'class'		=> "btn ".join( " ", $this->class ),
			'title'		=> $this->title ? addslashes( $this->title ) : NULL,
		);
		if( $this->confirm && !$this->disabled ){
			$attributes['onclick']	= 'if(!confirm(\''.addslashes( $this->confirm ).'\'))return false;';
		}
		if( $this->disabled ){
			$attributes['class']	.= " disabled";
			$attributes['disabled']	= 'disabled';
		}
		$this->extendAttributesByEvents( $attributes );
		$icon	= $this->icon ? $this->icon->render().' ' : "";
		return UI_HTML_Tag::create( 'button', $icon.$this->content, $attributes );
	}
}
?>
