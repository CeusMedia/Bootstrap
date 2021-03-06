<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\Component;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Label extends Component
{
	const CLASS_IMPORTANT	= "label-important";
	const CLASS_INVERSE		= "label-inverse";
	const CLASS_INFO		= "label-info";
	const CLASS_SUCCESS		= "label-success";
	const CLASS_WARNING		= "label-warning";

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$class	= 'label';
		if( count( $this->$classes ) )
			$class	.= ' '.join( " ", $this->classes );
		return \UI_HTML_Tag::create( 'span', $this->content, array( 'class' => $class ) );			//
	}
}
