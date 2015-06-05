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
namespace CeusMedia\Bootstrap;
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
class Badge extends Component{

	const CLASS_IMPORTANT	= "badge-important";
	const CLASS_INVERSE		= "badge-inverse";
	const CLASS_INFO			= "badge-info";
	const CLASS_SUCCESS		= "badge-success";
	const CLASS_WARNING		= "badge-warning";

	public function render(){
		$class	= 'badge';
		if( count( $this->class ) )
			$class	.= ' '.join( " ", $this->class );
		return \UI_HTML_Tag::create( 'span', $this->content, array( 'class' => $class ) );			//
	}
}
?>
