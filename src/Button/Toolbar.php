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
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Toolbar{

	protected $groups		= array();

	public function __construct( $groups = array() ){
		$this->add( $groups );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component or exception message
	 */
	public function __toString(){
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
	 *	@return		object		Own instance for chainability
	 */
	public function add( $group ){
		if( is_array( $group ) )
			foreach( $group as $item )
				$this->add( $item );
		else if( $group )
			$this->groups[]	= $group;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(){
		$attributes		= array( 'class' => 'btn-toolbar' );
		return \UI_HTML_Tag::create( 'div', $this->groups, $attributes );
	}
}
?>
