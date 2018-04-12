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
class Button{

	protected $items		= array();
	protected $alignLeft	= TRUE;
	protected $trigger		= NULL;

	public function __construct( $label, \CeusMedia\Bootstrap\Dropdown $dropdown, $class = NULL, $icon = NULL, $caret = TRUE ){
		$this->label	= $label;
		$this->dropdown	= $dropdown;
		$this->class	= $class;
		$this->icon		= $icon;
		$this->caret	= $caret;
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
	 *	@return		string		Rendered HTML of component
	 */
	public function render(){
		$trigger	= new Trigger\Button( $this->label, $this->class, $this->icon, $this->caret );
		return \UI_HTML_Tag::create( 'div', $trigger.$this->dropdown, array( 'class' => 'btn-group' ) );
	}

	/**
	 *	@access		public
	 *	@return		object		Own instance for chainability
	 */
	public function setAlign( $left = TRUE ){
		$this->alignLeft	= $left;
		return $this;
	}
}
?>
