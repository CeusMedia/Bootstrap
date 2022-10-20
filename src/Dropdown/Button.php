<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Dropdown
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2022 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Dropdown;

use CeusMedia\Bootstrap\Base\Aware\ClassAware;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Dropdown
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2022 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 *	@deprecated		use Dropdown\Trigger with asButton instead
 *	@todo			te be removed in 0.5
 */
class Button{

	use ClassAware;

	protected $items		= array();
	protected $alignLeft	= TRUE;
	protected $trigger		= NULL;
	protected $label;
	protected $dropdown;
	protected $caret;
	protected $icon;

	public function __construct( $label, \CeusMedia\Bootstrap\Dropdown\Menu $dropdown, $class = NULL, $icon = NULL, $caret = TRUE ){
		\trigger_error( 'Use base component instead', E_USER_DEPRECATED );
		$this->label	= $label;
		$this->dropdown	= $dropdown;
		$this->setClass( $class );
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
		$trigger	= new Trigger\Button( $this->label, $this->classes, $this->icon, $this->caret );
		return HtmlTag::create( 'div', $trigger.$this->dropdown, array( 'class' => 'btn-group' ) );
	}

	/**
	 *	@access		public
	 *	@return		object		Own instance for method chaining
	 */
	public function setAlign( $left = TRUE ){
		$this->alignLeft	= $left;
		return $this;
	}
}
?>
