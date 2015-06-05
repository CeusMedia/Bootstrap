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
abstract class Component{

	protected $class	= array();
	protected $content	= NULL;
	protected $data		= array();
	protected $events	= array();
	protected $id		= NULL;

	public function __construct( $content, $class = NULL ){
		$this->setClass( $class );
		$this->setContent( $content );
	}

	public function __toString(){
		try{
			return $this->render();
		}
		catch( Exception $e ){
			print $e->getMessage();
			exit;
		}
	}

	public function addClass( $class ){
		if( !is_array( $class ) )
			$class	= explode( " ", $class );
		foreach( $class as $item )
			$this->class[]	= $item;
	}

	public function setClass( $class ){
		$this->class	= array();
		$this->addClass( $class );
//		if( !is_array( $class ) )
//			$class	= explode( " ", $class );
//		$this->class	= $class;
	}

	public function setContent( $content ){
		$this->content	= $content;
	}

	public function setData( $key, $value ){
		$this->data[$key]	= $value;
	}

	public function setEvent( $event, $action ){
		if( !isset( $this->events[$event] ) )
			$this->events[$event]	= array();
		$this->events[$event][]	= $action;
	}

	public function setId( $id ){
		$this->id		= $id;
	}

	protected function extendAttributesByData( &$attributes ){
		foreach( $this->data as $key => $value )
			$attributes['data-'.strtolower( $key )]	= htmlentities( $value, ENT_QUOTES, 'UTF-8' );
	}

	protected function extendAttributesByEvents( &$attributes ){
		foreach( $this->events as $event => $actions ){
			$event		= 'on'.strtolower( $event );
			$action		= addslashes( join( '; ', $actions ) );
			$attributes[$event]	= $action;
		}
	}

	abstract public function render();
}
?>
