<?php
class CMM_Bootstrap_Label extends CMM_Bootstrap_Abstract{

	const CLASS_IMPORTANT	= "label-important";
	const CLASS_INVERSE		= "label-inverse";
	const CLASS_INFO		= "label-info";
	const CLASS_SUCCESS		= "label-success";
	const CLASS_WARNING		= "label-warning";

	public function render(){
		$class	= 'label';
		if( count( $this->class ) )
			$class	.= ' '.join( " ", $this->class );
		return UI_HTML_Tag::create( 'span', $this->content, array( 'class' => $class ) );			//
	}
}
?>
