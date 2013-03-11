<?php
class CMM_Bootstrap_ProgressBar{
	
	const CLASS_ACTIVE		= "active";
	const CLASS_DANGER		= "progress-danger";
	const CLASS_INFO		= "progress-info";
	const CLASS_STRIPED		= "progress_striped";
	const CLASS_SUCCESS		= "progress-success";
	const CLASS_WARNING		= "progress-warning";
	
	const BAR_CLASS_DANGER	= "bar-danger";
	const BAR_CLASS_INFO	= "bar-info";
	const BAR_CLASS_SUCCESS	= "bar-success";
	const BAR_CLASS_WARNING	= "bar-warning";
	
	protected $bars		= array();
	
	public function __construct( $class = NULL ){
		$this->class	= $class;
	}
	public function addBar( $width, $class = NULL, $label = NULL ){
		$this->bars[]	= (object) array(
			'width'		=> $width,
			'class'		=> $class,
			'label'		=> (string) $label,
		);
	}
	public function render(){
		$list	= array();
		foreach( $this->bars as $bar ){
			$attributes	= array(
				'class'		=> "bar",
				'style'		=> 'width: '.$bar->width.'%',
			);
			if( $bar->class ){
				$class	= is_array( $bar->class ) ? join( ' ', $bar->class ) : $bar->class;
				$attributes['class']	.= ' '.$class;
			}
			$list[]	= UI_HTML_Tag::create( 'div', $bar->label, $attributes );
		}
		$class	= 'progress';
		if( $this->class ){
			$class	.= ' '.( is_array( $this->class ) ? join( ' ', $this->class ) : $this->class );	//
		}
		return UI_HTML_Tag::create( 'div', $list, array( 'class' => $class ) );
	}

	public function __toString(){
		return $this->render();
	}
}
?>
