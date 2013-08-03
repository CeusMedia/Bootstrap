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
class CMM_Bootstrap_LinkButton extends CMM_Bootstrap_Abstract{

	protected $confirm;
	protected $icon;
	protected $url;
	protected $events		= array();

	public function __construct( $url, $content, $class = NULL, $icon = NULL, $disabled = FALSE ){
		$this->setUrl( $url );
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
		if( !( $icon instanceof CMM_Bootstrap_Icon ) ){
			$class	= join( " ", $this->class );
			$white	= preg_match( "/btn-(primary|danger|warning|info|inverse|success)/", $class );			//
			$icon	= new CMM_Bootstrap_Icon( $icon, $white );
		}
		$this->icon	= $icon;
	}

	public function setTitle( $title ){
		$this->title	= $title;
	}

	public function setUrl( $url ){
		$this->url		= $url;
	}

	public function render(){
		$attributes	= array(
			'id'		=> $this->id,
			'class'		=> "btn ".join( " ", $this->class ),
			'href'		=> $this->url,
			'title'		=> addslashes( $this->title ),
		);
		if( $this->confirm ){
			$attributes['onclick']	= 'if(!confirm(\''.addslashes( $this->confirm ).'\'))return false;';
		}
		if( $this->disabled ){
			$attributes['class']	.= " disabled";
			$attributes['href']		= "#";
		}
		$this->extendAttributesByEvents( $attributes );
		$icon	= $this->icon ? $this->icon->render().' ' : "";
		return UI_HTML_Tag::create( 'a', $icon.$this->content, $attributes );
	}
}
?>
