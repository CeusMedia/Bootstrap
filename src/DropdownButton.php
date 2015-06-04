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
 *	@todo			kriss: finish refactoring
 */
class CMM_Bootstrap_DropdownButton{

	protected $items		= array();
	protected $alignLeft	= TRUE;
	protected $trigger		= NULL;

	public function __construct( $label, CMM_Bootstrap_Dropdown $dropdown, $class = NULL, $caret = TRUE ){
		$this->label	= $label;
		$this->class	= $class;
		$this->dropdown	= $dropdown;
		$this->caret	= $caret;
	}

	public function setAlign( $left = TRUE ){
		$this->alignLeft	= $left;
	}

	public function render(){
		$trigger	= new CMM_Bootstrap_DropdownTrigger( $this->label, $this->class, $this->caret );
		$list		= $this->dropdown->render();
		return UI_HTML_Tag::create( 'div', $trigger.$list, array( 'class' => 'btn-group' ) );
	}

	public function __toString(){
		return $this->render();
	}
}
?>
