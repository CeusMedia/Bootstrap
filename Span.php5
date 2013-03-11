<?php
/**
 * @deprecated		use CMM_Bootstrap_Abstract instead
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
