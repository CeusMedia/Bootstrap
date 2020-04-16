<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Button;

use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Base\Aware\ClassAware;
use CeusMedia\Bootstrap\Base\Aware\AriaAware;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Group extends Structure
{
	use ClassAware, AriaAware;

	protected $buttons		= array();
	protected $stacked		= FALSE;

	public function __construct( $buttons = array(), $stacked = FALSE )
	{
		parent::__construct();
		$this->setRole( 'group' );
//		$this->setClass( 'btn-group' );
		$this->add( $buttons );
		$this->setStacked( $stacked );
	}

	public function __toString(): string
	{
		try{
			return $this->render();
		}
		catch( \Exception $e ){
			print $e->getMessage();
			exit;
		}
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
		if( $this->stacked )
			$classes[]	= 'btn-group-vertical';
		if( count( $this->classes ) )
			$classes	= array_merge( $classes, $this->classes );
		$attributes	= ['class' => join( ' ', $classes )];
		$this->extendAttributesByAria( $attributes );
		return \UI_HTML_Tag::create( 'div', $this->buttons, $attributes );
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setStacked( $stacked = TRUE ): self
	{
		$class		= 'btn-group-vertical';
		$stacked	? $this->addClass( $class ) : $this->removeClass( $class );
		return $this;
	}
}
