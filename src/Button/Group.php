<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Button;

use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Base\Aware\AriaAware;
use CeusMedia\Bootstrap\Base\Aware\ClassAware;

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

use Exception;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Group extends Structure
{
	use AriaAware, ClassAware;

	protected $buttons		= array();
	protected $stacked		= FALSE;

	public function __construct( array $buttons = array(), bool $stacked = FALSE )
	{
		parent::__construct();
		$this->setRole( 'group' );
//		$this->setClass( 'btn-group' );
		$this->add( $buttons );
		$this->setStacked( $stacked );
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function add( $button ): self
	{
		if( is_array( $button ) )
			foreach( $button as $item )
				$this->add( $item );
		else if( $button )
			$this->buttons[]	= $button;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$classes		= ['btn-group'];
//		if( $this->stacked )
//			$classes[]	= 'btn-group-vertical';
		if( count( $this->classes ) )
			$classes	= array_merge( $classes, $this->classes );
		$attributes	= ['class' => join( ' ', $classes )];
		$this->extendAttributesByAria( $attributes );
		return HtmlTag::create( 'div', $this->buttons, $attributes );
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setStacked( bool $stacked = TRUE ): self
	{
		$class		= 'btn-group-vertical';
		$stacked	? $this->addClass( $class ) : $this->removeClass( $class );
		return $this;
	}
}
