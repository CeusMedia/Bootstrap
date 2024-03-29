<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

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

use CeusMedia\Bootstrap\Base\Aware\AriaAware;
use CeusMedia\Bootstrap\Base\Element;

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 *	@todo			support Bootstrap 3+
 */
class Row extends Element
{
	use AriaAware;

	protected $fluid	= FALSE;

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$attributes		= array(
			'class'		=> 'row'.( $this->fluid ? '-fluid' : ''),
		);
		$this->extendAttributesByData( $attributes );
		$this->extendAttributesByAria( $attributes );
		return HtmlTag::create( 'div', $this->content, $attributes );
	}

	/**
	 *	@access		public
	 *	@param		boolean		$fluid		Flag: set row to be fluid or not
	 *	@return		self		Own instance for chainability
	 */
	public function setFluid( bool $fluid ): self
	{
		$this->fluid	= $fluid;
		return $this;
	}
}
