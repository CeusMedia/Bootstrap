<?php
/**
 *	...
 *	@category		cmModules
 *	@package		Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2013 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@since			0.3.0
 *	@version		$Id$
 */
namespace CeusMedia\Bootstrap;
/**
 *	...
 *	@category		cmModules
 *	@package		Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2013 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@since			0.3.0
 *	@version		$Id$
 */
class ButtonGroup{
	protected $buttons		= array();
	protected $stacked		= FALSE;
	protected $class		= "";

	public function __construct( $buttons = array(), $stacked = FALSE ){
		$this->add( $buttons );
		$this->setStacked( $stacked );
	}

	public function __toString(){
		return $this->render();
	}

	public function add( $button ){
		if( is_array( $button ) )
			foreach( $button as $item )
				$this->add( $item );
		else if( $button )
			$this->buttons[]	= $button;
	}

	public function render(){
		$classes		= array( 'btn-group' );
		if( $this->stacked )
			$classes[]	= 'btn-group-vertical';
		if( strlen( trim( $this->class ) ) )
			$classes[]	= trim( $this->class );
		$attributes		= array( 'class' => join( " ", $classes ) );
		return \UI_HTML_Tag::create( 'div', $this->buttons, $attributes );
	}

	public function setClass( $class ){
		$this->class	= $class;
	}

	public function setStacked( $stacked = TRUE ){
		$this->stacked		= $stacked;
	}
}
?>
