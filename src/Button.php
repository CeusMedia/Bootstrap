<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2023 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\Element;
use CeusMedia\Bootstrap\Base\Aware\AriaAware;
use CeusMedia\Bootstrap\Base\Aware\DisabledAware;
use CeusMedia\Bootstrap\Base\Aware\IconAware;
use CeusMedia\Bootstrap\Base\Aware\NameAware;
use CeusMedia\Bootstrap\Base\Aware\SizeAware;

use CeusMedia\Common\Renderable;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

use RangeException;
use Stringable;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2023 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Button extends Element
{
	use AriaAware, DisabledAware, IconAware, NameAware, SizeAware;

	public const STATE_DEFAULT		= '';
	public const STATE_PRIMARY		= 'btn-primary';
	public const STATE_SECONDARY	= 'btn-secondary';
	public const STATE_SUCCESS		= 'btn-success';
	public const STATE_DANGER		= 'btn-danger';
	public const STATE_WARNING		= 'btn-warning';
	public const STATE_INFO			= 'btn-info';
	public const STATE_INVERSE		= 'btn-inverse';
	public const STATE_LIGHT		= 'btn-light';
	public const STATE_DARK			= 'btn-dark';
	public const STATE_LINK			= 'btn-link';

	public const SIZE_DEFAULT		= '';
	public const SIZE_MINI			= 'btn-mini';
	public const SIZE_SMALL			= 'btn-small btn-sm';
	public const SIZE_LARGE			= 'btn-large btn-lg';

	public const SIZES				= [
		self::SIZE_DEFAULT,
		self::SIZE_MINI,
		self::SIZE_SMALL,
		self::SIZE_LARGE,
	];

	public const TYPE_BUTTON		= 'button';
	public const TYPE_SUBMIT		= 'submit';
	public const TYPE_RESET			= 'reset';

	public const TYPES				= [
		self::TYPE_BUTTON,
		self::TYPE_SUBMIT,
		self::TYPE_RESET,
	];

	protected string $type			= self::TYPE_BUTTON;

	/**
	 *	@param		Stringable|Renderable|string|NULL		$content
	 *	@param		array|string|NULL			$class
	 *	@param		Icon|string|NULL			$icon
	 *	@param		boolean						$disabled
	 */
	public function __construct(
		Stringable|Renderable|string|null $content,
		array|string|null $class = NULL,
		Icon|string|null $icon = NULL,
		bool $disabled = FALSE
	)
	{
		parent::__construct( $content, $class );
		if( NULL !== $icon )
			$this->setIcon( $icon );
		$this->setDisabled( $disabled );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$classes	= array_merge( ['btn'], $this->classes );
		$attributes	= [
			'name'		=> $this->name,
			'id'		=> $this->id,
			'type'		=> $this->type,
			'class'		=> join( ' ', $classes ),
			'disabled'	=> $this->disabled ? 'disabled' : NULL,
		];
		$this->extendAttributesByEvents( $attributes );
		$this->extendAttributesByData( $attributes );
		$icon	= (string) $this->icon;
		$icon	= 0 !== strlen( $icon ) ? $icon.' ' : '';
		$content	= $this->getContentAsString();
		return HtmlTag::create( 'button', $icon.$content, $attributes );
	}

	public function setBlock( bool $block = TRUE ): self
	{
		$block ? $this->addClass( 'btn-block' ): $this->removeClass( 'btn-block' );
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for method chaining
	 */
	public function setType( string $type ): self
	{
		if( !in_array( $type, self::TYPES, TRUE ) )
			throw new RangeException( 'Invalid type' );
		$this->type	= $type;
		return $this;
	}
}
