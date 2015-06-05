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
class ButtonToolbar{

	protected $groups		= array();

	public function __construct( $groups = array() ){
		$this->add( $groups );
	}

	public function add( $group ){
		if( is_array( $group ) )
			foreach( $group as $item )
				$this->add( $item );
		else if( $group )
			$this->groups[]	= $group;
	}

	public function render(){
		$attributes		= array( 'class' => 'btn-toolbar' );
		return \UI_HTML_Tag::create( 'div', $this->groups, $attributes );
	}

	public function __toString(){
		return $this->render();
	}
}
?>
