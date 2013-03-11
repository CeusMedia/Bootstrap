<?php
class CMM_Bootstrap_Link extends CMM_Bootstrap_Abstract{

	protected $icon;
	protected $url;

	public function __construct( $url, $content, $class = NULL, $icon = NULL ){
		$this->setUrl( $url );
		$this->setContent( $content );
		$this->setClass( $class );
		$this->setIcon( $icon );
	}

	public function setIcon( $icon, $white = FALSE ){
		if( !( $icon instanceof CMM_Bootstrap_Icon ) )
			$icon	= new CMM_Bootstrap_Icon( $icon, $white );
		$this->icon	= $icon;
	}

	public function setUrl( $url ){
		$this->url	= $url;
	}

	public function render(){
		$attributes		= array(
			'href'		=> $this->url,
			'class'		=> $this->class,
		);
		$icon	= $this->icon ? $this->icon->render().' ' : "";
		return UI_HTML_Tag::create( 'a', $icon.$this->content, $attributes );
	}

	public function __toString(){
		return $this->render();
	}
}
?>
