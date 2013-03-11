<?php
abstract class CMM_Bootstrap_Abstract{

	protected $class	= array();
	protected $content	= NULL;
	protected $data		= array();
	protected $events	= array();
	protected $id		= NULL;

	public function __construct( $content, $class = NULL ){
		$this->setClass( $class );
		$this->setContent( $content );
	}

	public function setClass( $class ){
		if( !is_array( $class ) )
			$class	= explode( " ", $class );
		$this->class	= $class;
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
			$attributes['data-'.strtolower( $key )]	= addslashes( $value );
	}

	protected function extendAttributesByEvents( &$attributes ){
		foreach( $this->events as $event => $actions ){
			$event		= 'on'.strtolower( $event );
			$action		= addslashes( join( '; ', $actions ) );
			$attributes[$event]	= $action;
		}
	}

	abstract public function render();

	public function __toString(){
		return $this->render();
	}
}
?>
