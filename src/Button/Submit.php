<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2024 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Button;

use CeusMedia\Bootstrap\Button;
use CeusMedia\Bootstrap\Icon;
use CeusMedia\Common\Renderable;
use Stringable;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2024 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Submit extends Button
{
	/**
	 *	@param		string|NULL					$name
	 *	@param		Stringable|Renderable|string|NULL		$content
	 *	@param		array|string|NULL			$class
	 *	@param		Icon|string|NULL			$icon
	 *	@param		bool						$disabled
	 */
	public function __construct(
		?string $name,
		Stringable|Renderable|string|null $content,
		array|string|null $class = NULL,
		Icon|string|null $icon = NULL,
		bool $disabled = FALSE
	)
	{
		parent::__construct( $content, $class, $icon, $disabled );
		$this->setType( Button::TYPE_SUBMIT );
		$this->setName( $name );
	}
}
