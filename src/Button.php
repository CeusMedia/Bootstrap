<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\Element;
use CeusMedia\Bootstrap\Base\Aware\AriaAware;
use CeusMedia\Bootstrap\Base\Aware\DisabledAware;
use CeusMedia\Bootstrap\Base\Aware\IconAware;
use CeusMedia\Bootstrap\Base\Aware\NameAware;
use CeusMedia\Bootstrap\Base\Aware\SizeAware;

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

use RangeException;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Button extends Element
{
	use AriaAware, DisabledAware, IconAware, NameAware, SizeAware;

	const STATE_DEFAULT		= '';
	const STATE_PRIMARY		= 'btn-primary';
	const STATE_SECONDARY	= 'btn-secondary';
	const STATE_SUCCESS		= 'btn-success';
	const STATE_DANGER		= 'btn-danger';
	const STATE_WARNING		= 'btn-warning';
	const STATE_INFO		= 'btn-info';
	const STATE_INVERSE		= 'btn-inverse';
	const STATE_LIGHT		= 'btn-light';
	const STATE_DARK		= 'btn-dark';
	const STATE_LINK		= 'btn-link';

	const SIZE_DEFAULT		= '';
	const SIZE_MINI			= 'btn-mini';
	const SIZE_SMALL		= 'btn-small btn-sm';
	const SIZE_LARGE		= 'btn-large btn-lg';

	const SIZES				= [
		self::SIZE_DEFAULT,
		self::SIZE_MINI,
		self::SIZE_SMALL,
		self::SIZE_LARGE,
	];

	const TYPE_BUTTON		= 'button';
	const TYPE_SUBMIT		= 'submit';
	const TYPE_RESET		= 'reset';

	const TYPES				= [
		self::TYPE_BUTTON,
		self::TYPE_SUBMIT,
		self::TYPE_RESET,
	];

	protected $type			= 'button';

	public function __construct( string $content, $class = NULL, $icon = NULL, bool $disabled = FALSE )
	{
		parent::__construct( $content, $class );
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
		$attributes	= array(
			'name'		=> $this->name,
			'id'		=> $this->id,
			'type'		=> $this->type,
			'class'		=> join( ' ', $classes ),
			'disabled'	=> $this->disabled ? 'disabled' : NULL,
		);
		$this->extendAttributesByEvents( $attributes );
		$this->extendAttributesByData( $attributes );
		$icon	= $this->icon ? $this->icon->render().' ' : '';
		return HtmlTag::create( 'button', $icon.$this->content, $attributes );
	}

	public function setBlock( bool $block = TRUE ): self
	{
		$block ? $this->addClass( 'btn-block' ): $this->removeClass( 'btn-block' );
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setType( string $type ): self
	{
		if( !in_array( $type, self::TYPES, TRUE ) )
			throw new RangeException( 'Invalid type' );
		$this->type	= $type;
		return $this;
	}
}
