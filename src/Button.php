<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\Component;
use CeusMedia\Bootstrap\Base\Aware\IconAware;
use CeusMedia\Bootstrap\Base\Aware\DisabledAware;
use CeusMedia\Bootstrap\Base\Aware\AriaAware;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Button extends Component
{
	use IconAware, DisabledAware, AriaAware;

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
	const SIZE_BLOCK		= 'btn-block';

	const TYPE_BUTTON		= 'button';
	const TYPE_SUBMIT		= 'submit';
	const TYPE_RESET		= 'reset';

	protected $name;
	protected $type			= 'button';

	public function __construct( $content, $class = NULL, $icon = NULL, $disabled = FALSE )
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
		$attributes	= array(
			'name'		=> $this->name,
			'id'		=> $this->id,
			'type'		=> $this->type,
			'class'		=> 'btn '.join( ' ', $this->classes ),
			'disabled'	=> $this->disabled ? 'disabled' : NULL,
		);
		$this->extendAttributesByEvents( $attributes );
		$this->extendAttributesByData( $attributes );
		$icon	= $this->icon ? $this->icon->render().' ' : '';
		return \UI_HTML_Tag::create( 'button', $icon.$this->content, $attributes );
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setName( $name ): self
	{
		$this->name	= $name;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setType( $type ): self
	{
		$this->type	= $type;
		return $this;
	}
}
