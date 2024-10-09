<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Dropdown_Trigger
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2024 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Dropdown\Trigger;

use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Base\Aware\ClassAware;
use CeusMedia\Bootstrap\Base\Aware\ContentAware;
use CeusMedia\Bootstrap\Base\Aware\IconAware;
use CeusMedia\Bootstrap\Button as BaseButton;

use CeusMedia\Bootstrap\Icon;
use CeusMedia\Common\Renderable;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

use Exception;
use Stringable;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Dropdown_Trigger
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2024 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Button extends Structure
{
	use ClassAware, ContentAware, IconAware;

	protected string $label;
	protected bool $caret;

	/**
	 *	@param		Stringable|Renderable|string|array|NULL	$label
	 *	@param		array|string|NULL				$class
	 *	@param		Icon|string|NULL				$icon
	 *	@param		bool							$caret
	 */
	public function __construct(
		Stringable|Renderable|string|array|null $label,
		array|string|null $class = NULL,
		Icon|string|null $icon = NULL,
		bool $caret = TRUE
	)
	{
		parent::__construct();
		$this->setContent( $label );
		if( NULL !== $class )
			$this->setClass( $class );
		if( NULL !== $icon )
			$this->setIcon( $icon );
		$this->useCaret( $caret );
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
		$caret	= $this->caret ? ' '.HtmlTag::create( 'span', '', ['class' => 'caret'] ) : '';
		$button	= new BaseButton( $this->getContentAsString().$caret, $this->classes, $this->icon );
		$button->addClass( 'dropdown-toggle' );
		$button->setData( 'toggle', 'dropdown' );
		return $button->render();
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for method chaining
	 */
	public function useCaret( bool $useCaret = TRUE ): self
	{
		$this->caret	= $useCaret;
		return $this;
	}
}
