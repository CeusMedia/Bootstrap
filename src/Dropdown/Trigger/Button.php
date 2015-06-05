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
namespace CeusMedia\Bootstrap\Dropdown\Trigger;
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
class Button{

	protected $label;
	protected $class;
	protected $caret;

	public function __construct( $label, $class = NULL, $icon = NULL, $caret = TRUE ){
		$this->label	= $label;
		$this->class	= $class;
		$this->icon		= $icon;
		$this->toggleCaret( $caret );
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

	public function render(){
		$caret	= ' '.\UI_HTML_Tag::create( 'span', "", array( 'class' => 'caret' ) );
		if( !$this->caret )
			$caret	= '';
		$button	= new \CeusMedia\Bootstrap\Button( $this->label.$caret, $this->class, $this->icon );
		$button->addClass( 'dropdown-toggle '.$this->class );
		$button->setData( 'toggle', "dropdown" );
		return $button->render();
	}

	public function toggleCaret( $useCaret = TRUE ){
		$this->caret	= (bool) $useCaret;
	}
}
?>
