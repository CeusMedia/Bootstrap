<?php
class CMM_Bootstrap_LinkButton extends CMM_Bootstrap_Abstract{

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

	public function setDisabled( $disabled = TRUE ){
		$this->disabled	= $disabled;
	}

	public function setIcon( $icon, $white = FALSE ){
		if( !( $icon instanceof CMM_Bootstrap_Icon ) ){
			$class	= join( " ", $this->class );
			$white	= preg_match( "/btn-(danger|warning|info|inverse|success)/", $class );			//
			$icon	= new CMM_Bootstrap_Icon( $icon, $white );
		}
		$this->icon	= $icon;
	}

	public function setUrl( $url ){
		$this->url		= $url;
	}

	public function render(){
		$attributes	= array(
			'id'		=> $this->id,
			'class'		=> "btn ".join( " ", $this->class ),
			'href'		=> $this->url,
		);
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
