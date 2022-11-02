<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2022 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Button;

use CeusMedia\Bootstrap\Base\Element;
use CeusMedia\Bootstrap\Base\Aware\DisabledAware;
use CeusMedia\Bootstrap\Base\Aware\IconAware;
use CeusMedia\Bootstrap\Button as BaseButton;
use CeusMedia\Bootstrap\Icon;
use CeusMedia\Common\Renderable;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2022 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 *	@deprecated		use Button with type TYPE_SUBMIT instead
 */
class Submit extends Element
{
	use IconAware, DisabledAware;

	protected BaseButton $button;
#	protected $confirm;
#	protected $title;

	/**
	 *	@param		string|NULL					$name
	 *	@param		Renderable|string|NULL		$content
	 *	@param		array|string|NULL			$class
	 *	@param		Icon|string|NULL			$icon
	 *	@param		bool						$disabled
	 */
	public function __construct( ?string $name, $content, $class = NULL, $icon = NULL, bool $disabled = FALSE )
	{
		$this->button	= new BaseButton( $content, $class, $icon, $disabled );
		$this->button->setType( 'submit' );
		$this->button->setName( $name );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		return $this->button->render();
	}
}
