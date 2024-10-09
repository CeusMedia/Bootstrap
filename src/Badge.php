<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2024 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\Element;

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2024 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Badge extends Element
{
	const CLASS_IMPORTANT	= 'badge-important';
	const CLASS_INVERSE		= 'badge-inverse';
	const CLASS_INFO		= 'badge-info';
	const CLASS_SUCCESS		= 'badge-success';
	const CLASS_WARNING		= 'badge-warning';

	const CLASSES			= [
		self::CLASS_IMPORTANT,
		self::CLASS_INVERSE,
		self::CLASS_INFO,
		self::CLASS_SUCCESS,
		self::CLASS_WARNING,
	];

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$class	= 'badge';
		if( count( $this->classes ) )
			$class	.= ' '.join( ' ', $this->classes );
		return HtmlTag::create( 'span', $this->getContentAsString(), ['class' => $class] );
	}
}
