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
class Badge extends Component
{
	const CLASS_IMPORTANT	= "badge-important";
	const CLASS_INVERSE		= "badge-inverse";
	const CLASS_INFO		= "badge-info";
	const CLASS_SUCCESS		= "badge-success";
	const CLASS_WARNING		= "badge-warning";

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$class	= 'badge';
		if( count( $this->classes ) )
			$class	.= ' '.join( " ", $this->classes );
		return \UI_HTML_Tag::create( 'span', $this->content, array( 'class' => $class ) );			//
	}
}
