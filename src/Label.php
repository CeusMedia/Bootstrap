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

use CeusMedia\Bootstrap\Base\Element;

use UI_HTML_Tag as HtmlTag;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Label extends Element
{
	const CLASS_IMPORTANT	= 'label-important';
	const CLASS_INVERSE		= 'label-inverse';
	const CLASS_INFO		= 'label-info';
	const CLASS_SUCCESS		= 'label-success';
	const CLASS_WARNING		= 'label-warning';

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
		$class	= 'label';
		if( count( $this->classes ) )
			$class	.= ' '.join( ' ', $this->classes );
		return HtmlTag::create( 'span', $this->content, ['class' => $class] );
	}
}
