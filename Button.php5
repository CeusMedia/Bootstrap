<?php
class CMM_Bootstrap_Button extends CMM_Bootstrap_Abstract{

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

	protected $icon;
	protected $iconWhite	= FALSE;
	protected $disabled;
	protected $type		= "button";
		
	public function __construct( $content, $class = NULL, $icon = NULL, $disabled = FALSE ){
		$this->setContent( $content );
		$this->setClass( $class );
		$this->setIcon( $icon );
		$this->setDisabled( $disabled );
	}

	public function setIcon( $icon, $white = FALSE ){
		if( !( $icon instanceof CMM_Bootstrap_Icon ) ){
			$class	= join( " ", $this->class );
			$white	= preg_match( "/btn-(danger|warning|info|inverse|success)/", $class );			//
			$icon	= new CMM_Bootstrap_Icon( $icon, $white );
		}
		$this->icon	= $icon;
	}

	public function setDisabled( $disabled = TRUE ){
		$this->disabled	= $disabled;
	}

	public function render(){
		$attributes	= array(
			'id'		=> $this->id,
			'type'		=> $this->type,
			'class'		=> "btn ".join( " ", $this->class ),
			'disabled'	=> $this->disabled ? "disabled" : NULL,
		);
		$this->extendAttributesByEvents( $attributes );
		$this->extendAttributesByData( $attributes );
		$icon	= $this->icon ? $this->icon->render().' ' : "";
		return UI_HTML_Tag::create( 'button', $icon.$this->content, $attributes );
	}

	public function __toString(){
		return $this->render();
	}
}
?>
