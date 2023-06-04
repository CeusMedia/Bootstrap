<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Dropdown_Trigger
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2023 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */

namespace CeusMedia\Bootstrap\Dropdown\Trigger;

use CeusMedia\Bootstrap\Icon;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use Exception;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Dropdown_Trigger
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2023 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Link
{
	protected string $label;

	protected ?string $class;

	protected bool $caret;

	/** @var	Icon|string|NULL	$icon */
	protected Icon|string|NULL $icon;

	/**
	 *	@param		string				$label
	 *	@param		array|string|NULL	$class
	 *	@param		Icon|string|NULL	$icon
	 *	@param		boolean				$caret
	 */
	public function __construct(
		string $label,
		array|string|null $class = NULL,
		Icon|string|null $icon = NULL,
		bool $caret = TRUE
	)
	{
		$this->label	= $label;
		$this->class	= is_array( $class ) ? join( ' ', $class ) : $class;
		$this->icon		= $icon;
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
		catch( Exception $e ){
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
		$caret	= ' '.HtmlTag::create( 'span', "", array( 'class' => 'caret' ) );
		if( !$this->caret )
			$caret	= '';
		$link	= new \CeusMedia\Bootstrap\Link( "#", $this->label.$caret );
		$link->setClass( 'dropdown-toggle '.$this->class );
		$link->setData( 'toggle', "dropdown" );
		if( NULL !== $this->icon )
			$link->setIcon( $this->icon );
		return $link->render();
	}

	/**
	 *	@access		public
	 *	@param		boolean		$useCaret
	 *	@return		self		Own instance for method chaining
	 */
	public function toggleCaret( bool $useCaret = TRUE ): self
	{
		$this->caret	= $useCaret;
		return $this;
	}
}
