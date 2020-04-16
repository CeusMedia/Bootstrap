<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Dropdown
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Dropdown;

use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Base\Aware\ClassAware;
use CeusMedia\Bootstrap\Base\Aware\ContentAware;
use CeusMedia\Bootstrap\Base\Aware\IconAware;
use CeusMedia\Bootstrap\Dropdown\Trigger\Button as TriggerButton;
use CeusMedia\Bootstrap\Dropdown\Trigger\Link as TriggerLink;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Dropdown
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Trigger extends Structure
{
	use ContentAware, ClassAware, IconAware;

	protected $caret;
	protected $type		= "button";

	public function __construct( $label, $class = NULL, $icon = NULL, $caret = TRUE ){
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
	 *	@return		self		Own instance for chainability
	 */
	public function asButton( $asButton = TRUE ): self
	{
		$this->type		= (bool) $asButton ? "button" : "link";
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function asLink( $asLink = TRUE ): self
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
				$trigger	= new TriggerLink( $this->content, $this->classes, $this->icon, $this->caret );
		}
		return $trigger;
	}
}
