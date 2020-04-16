<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Dropdown_Trigger
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Dropdown\Trigger;

use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Base\Aware\ClassAware;
use CeusMedia\Bootstrap\Base\Aware\ContentAware;
use CeusMedia\Bootstrap\Base\Aware\IconAware;
use CeusMedia\Bootstrap\Button as BaseButton;
use UI_HTML_Tag as Tag;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Dropdown_Trigger
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Button extends Structure
{
	use ClassAware, ContentAware, IconAware;

	protected $label;
	protected $class;
	protected $caret;

	public function __construct( $label, $class = NULL, $icon = NULL, $caret = TRUE )
	{
		$this->setContent( $label );
		$this->setClass( $class );
		$this->setIcon( $icon );
		$this->toggleCaret( $caret );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component or exception message
	 */
	public function __toString(): string
	{
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
	public function render(): string
	{
		$caret	= $this->caret ? ' '.Tag::create( 'span', '', ['class' => 'caret'] ) : '';
		$button	= new BaseButton( $this->content.$caret, $this->classes, $this->icon );
		$button->addClass( 'dropdown-toggle' );
		$button->setData( 'toggle', 'dropdown' );
		return $button->render();
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function toggleCaret( $useCaret = TRUE ): self
	{
		$this->caret	= (bool) $useCaret;
		return $this;
	}
}
?>
