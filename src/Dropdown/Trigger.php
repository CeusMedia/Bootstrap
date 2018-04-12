<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Dropdown
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Dropdown;
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Dropdown
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Trigger{

	protected $label;
	protected $class;
	protected $caret;
	protected $type		= "button";

	public function __construct( $label, $class = NULL, $icon = NULL, $caret = TRUE ){
		$this->label	= $label;
		$this->class	= $class;
		$this->caret	= $caret;
		$this->icon		= $icon;
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
	public function asButton( $asButton = TRUE ){
		$this->type		= (bool) $asButton ? "button" : "link";
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		object		Own instance for chainability
	 */
	public function asLink( $asLink = TRUE ){
		$this->type		= (bool) $asLink ? "link" : "button";
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(){
		switch( $this->type ){
			case "button":
				$trigger	= new \CeusMedia\Bootstrap\Dropdown\Trigger\Button( $this->label, $this->class, $this->icon, $this->caret );
				break;
			case "link":
			default:
				$trigger	= new \CeusMedia\Bootstrap\Dropdown\Trigger\Link( $this->label, $this->class, $this->icon, $this->caret );
		}
		return $trigger;
	}
}
?>
