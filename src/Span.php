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
 *	@deprecated		use CMM_Bootstrap_Abstract instead
 */
abstract class CMM_Bootstrap_Span{

	protected $class;
	protected $content;

	public function __construct( $content, $class = NULL ){
		$this->setClass( $class );
		$this->setContent( $content );
	}

	public function setClass( $class ){
		$this->class	= $class;
	}

	public function setContent( $content ){
		$this->content	= $content;
	}

	abstract public function render();

	public function __toString(){
		return $this->render();
	}
}
?>
