<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

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

use CeusMedia\Bootstrap\Base\Aware\AriaAware;
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
 *	@todo			support Bootstrap 3+
 */
class RowSpan extends Element
{
	use AriaAware;

	protected int $size	= 12;

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$classes	= array_merge( $this->classes, ['span'.$this->size.' bs4-col-md-'.$this->size] );
		$attributes		= [
			'class'		=> join( ' ', $classes ),
		];
		$this->extendAttributesByData( $attributes );
		$this->extendAttributesByAria( $attributes );
		return HtmlTag::create( 'div', $this->getContentAsString(), $attributes );
	}

	/**
	 *	@access		public
	 *	@param		integer		$size		Size of column (1-12)
	 *	@return		self		Own instance for method chaining
	 */
	public function setSize( int $size ): self
	{
		$this->size		= $size;
		return $this;
	}
}
