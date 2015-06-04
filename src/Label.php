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
class CMM_Bootstrap_Label extends CMM_Bootstrap_Abstract{

	const CLASS_IMPORTANT	= "label-important";
	const CLASS_INVERSE		= "label-inverse";
	const CLASS_INFO			= "label-info";
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
