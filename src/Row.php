<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\Aware\AriaAware;
use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Base\Element;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 *	@todo			support Bootstrap 3+
 */
class Row extends Element
{
	use AriaAware;

	protected bool $fluid	= FALSE;

	/**
	 *	Renders row for set Bootstrap version.
	 *	Bootstrap 2 has fixed and fluid rows, since Bootstrap 3 all rows are fluid (class "row").
	 *	Content may be a list of spans or other components, which are rendered for the same version.
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$class	= match( $this->getBsVersion() ){
			BsVersion::V2	=> $this->fluid ? 'row-fluid' : 'row',
			BsVersion::V3, BsVersion::V4, BsVersion::V5	=> 'row',
		};
		$attributes		= [
			'id'		=> $this->id,
			'class'		=> join( ' ', array_merge( [$class], $this->classes ) ),
		];
		$this->extendAttributesByData( $attributes );
		$this->extendAttributesByEvents( $attributes );
		$this->extendAttributesByAria( $attributes );
		return HtmlTag::create( 'div', $this->getContentAsString(), $attributes );
	}

	/**
	 *	@access		public
	 *	@param		boolean		$fluid		Flag: set row to be fluid or not
	 *	@return		static		Own instance for method chaining
	 */
	public function setFluid( bool $fluid ): static
	{
		$this->fluid	= $fluid;
		return $this;
	}
}
