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

use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Base\Aware\ClassAware;

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2024 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Toolbar extends Structure
{
	use ClassAware;

	protected array $groups		= [];

	public function __construct( array $groups = [] )
	{
		parent::__construct();
		$this->add( $groups );
	}

	/**
	 *	@access		public
	 *	@param		Group|array|string		$group
	 *	@return		self		Own instance for method chaining
	 */
	public function add( Group|array|string $group ): self
	{
		if( is_array( $group ) ) {
			foreach( $group as $item )
				$this->add( $item );
		}
		else if( 0 !== strlen( $group ) )
			$this->groups[]	= $group;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$classes	= array_merge( ['btn-toolbar'], $this->classes );
		$attributes	= ['class' => join( ' ', $classes )];
		return HtmlTag::create( 'div', $this->groups, $attributes );
	}
}
