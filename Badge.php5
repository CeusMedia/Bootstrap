<?php
class CMM_Bootstrap_Badge extends CMM_Bootstrap_Abstract{

	const CLASS_IMPORTANT	= "badge-important";
	const CLASS_INVERSE		= "badge-inverse";
	const CLASS_INFO		= "badge-info";
	const CLASS_SUCCESS		= "badge-success";
	const CLASS_WARNING		= "badge-warning";

	public function render(){
		$class	= 'badge';
		if( count( $this->class ) )
			$class	.= ' '.join( " ", $this->class );
		return UI_HTML_Tag::create( 'span', $this->content, array( 'class' => $class ) );			//
	}
}
?>
