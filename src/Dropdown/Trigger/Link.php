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
class Link{

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
		$link	= new \CeusMedia\Bootstrap\Link( "#", $this->label.$caret );
		$link->setClass( 'dropdown-toggle '.$this->class );
		$link->setData( 'toggle', "dropdown" );
		$link->setIcon( $this->icon );
		return $link->render();
	}

	public function toggleCaret( $useCaret = TRUE ){
		$this->caret	= (bool) $useCaret;
	}
}
?>

