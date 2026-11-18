<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Dropdown_Trigger
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */

namespace CeusMedia\Bootstrap\Dropdown\Trigger;

use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Icon;
use CeusMedia\Bootstrap\Link as BaseLink;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Dropdown_Trigger
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Link extends Structure
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
		parent::__construct();
		$this->label	= $label;
		$this->class	= is_array( $class ) ? join( ' ', $class ) : $class;
		$this->icon		= $icon;
		$this->toggleCaret( $caret );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$caret	= match( $this->getBsVersion() ){
			BsVersion::V2, BsVersion::V3	=> ' '.HtmlTag::create( 'span', "", ['class' => 'caret'] ),
			BsVersion::V4, BsVersion::V5	=> '',											//  caret is drawn by CSS
		};
		if( !$this->caret )
			$caret	= '';
		$link	= new BaseLink( "#", $this->label.$caret );
		$link->setBsVersion( $this->bsVersion );
		$hasToggleClass	= $this->caret || match( $this->getBsVersion() ){
			BsVersion::V2, BsVersion::V3	=> TRUE,
			BsVersion::V4, BsVersion::V5	=> FALSE,										//  this class draws the caret
		};
		$link->setClass( ( $hasToggleClass ? 'dropdown-toggle ' : '' ).$this->class );
		$link->setData( $this->dialect()->dataKey( 'toggle' ), "dropdown" );
		if( NULL !== $this->icon )
			$link->setIcon( $this->icon );
		return $link->render();
	}

	/**
	 *	@access		public
	 *	@param		boolean		$useCaret
	 *	@return		static		Own instance for method chaining
	 */
	public function toggleCaret( bool $useCaret = TRUE ): static
	{
		$this->caret	= $useCaret;
		return $this;
	}
}
