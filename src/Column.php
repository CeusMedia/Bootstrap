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

use CeusMedia\Bootstrap\Base\Aware\AriaAware;
use CeusMedia\Bootstrap\Base\Component;

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
class Column extends Component
{
	use AriaAware;

	protected $size	= 12;

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$classes	= array_merge( $this->classes, array( 'span'.$this->size ) );
		$attributes		= array(
			'class'		=> join( ' ', $classes ),
		);
		$this->extendAttributesByData( $attributes );
		$this->extendAttributesByAria( $attributes );
		return \UI_HTML_Tag::create( 'div', $this->content, $attributes );
	}

	/**
	 *	@access		public
	 *	@param		integer		$size		Size of column (1-12)
	 *	@return		self		Own instance for chainability
	 */
	public function setSize( int $size ): self
	{
		$this->size		= $size;
		return $this;
	}
}
