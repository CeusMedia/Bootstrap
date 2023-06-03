<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

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
use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Base\Aware\ContentAware;
use CeusMedia\Bootstrap\Base\Aware\IconAware;
use CeusMedia\Bootstrap\Dropdown\Trigger\Button as TriggerButton;
use CeusMedia\Bootstrap\Dropdown\Trigger\Link as TriggerLink;
use CeusMedia\Bootstrap\Icon;
use CeusMedia\Common\Renderable;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Dropdown
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2022 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Trigger extends Structure
{
	use ContentAware, ClassAware, IconAware;

	protected bool $caret;
	protected string $type		= "button";

	/**
	 *	Constructor.
	 *	@param		Renderable|string|array|NULL	$label
	 *	@param		string|array					$class
	 *	@param		Icon|string|NULL				$icon
	 *	@param		bool							$caret
	 *	@return		void
	 */
	public function __construct( $label, $class = NULL, $icon = NULL, bool $caret = TRUE )
	{
		parent::__construct();
		$this->setContent( $label );
		$this->setClass( $class );
		$this->setIcon( $icon );
		$this->caret	= $caret;
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
	 *	@param		bool		$asButton
	 *	@return		self		Own instance for method chaining
	 */
	public function asButton( bool $asButton = TRUE ): self
	{
		$this->type		= $asButton ? "button" : "link";
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		bool		$asLink
	 *	@return		self		Own instance for method chaining
	 */
	public function asLink( bool $asLink = TRUE ): self
	{
		$this->type		= (bool) $asLink ? "link" : "button";
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		switch( $this->type ){
			case "button":
				$trigger	= new TriggerButton( $this->content, $this->classes, $this->icon, $this->caret );
				break;
			case "link":
			default:
				$trigger	= new TriggerLink( strval( $this->content ), $this->classes, $this->icon, $this->caret );
		}
		return $trigger;
	}
}
