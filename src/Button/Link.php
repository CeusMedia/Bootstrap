<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Button;
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Link extends \CeusMedia\Bootstrap\Component{

	protected $confirm;
	protected $icon;
	protected $url;
	protected $title;
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
		if( $icon && !( $icon instanceof \CeusMedia\Bootstrap\Icon ) ){
			$class	= join( " ", $this->class );
			$white	= preg_match( "/btn-(primary|danger|warning|info|inverse|success)/", $class );			//
			$icon	= new \CeusMedia\Bootstrap\Icon( $icon, $white );
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
			'title'		=> $this->title ? addslashes( $this->title ) : NULL,
			'onclick'	=> NULL,
		);
		if( $this->confirm ){
			$attributes['onclick']	= 'if(!confirm(\''.addslashes( $this->confirm ).'\'))return false;';
		}
		if( $this->disabled ){
			$attributes['class']	.= " disabled";
			$attributes['data-attr-href']		= $attributes['href'];
			$attributes['data-attr-onclick']	= $attributes['onclick'];
			$attributes['href']		= NULL;
			$attributes['onclick']	= NULL;
		}
		$this->extendAttributesByEvents( $attributes );
		$icon	= $this->icon ? $this->icon->render().' ' : "";
		return \UI_HTML_Tag::create( 'a', $icon.$this->content, $attributes );
	}
}
?>
