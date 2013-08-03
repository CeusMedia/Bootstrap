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
class CMM_Bootstrap_Code extends CMM_Bootstrap_Abstract{

	protected $scrollable		= FALSE;

	public function __construct( $content, $scrollable = FALSE, $class = NULL ){
		$this->setContent( $content );
		$this->setClass( $class );
		$this->setScrollable( $scrollable );
	}

	public function setScrollable( $scrollable ){
		$this->scrollable	= (bool) $scrollable;
	}

	public function render(){
		$attributes		= array( 'class' => join( " ", $this->class ) );
		if( $this->scrollable )
			$attributes['class']	.= " pre-scrollable";
		return UI_HTML_Tag::create( 'pre', htmlentities( $this->content, ENT_QUOTES, 'UTF-8' ), $attributes );
	}
}
?>
