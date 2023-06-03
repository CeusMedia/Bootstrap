<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Replacement for checkbox inputs.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013-2022 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 *	@link			https://github.com/nostalgiaz/bootstrap-switch	requires Bootstrap Switch URL description
 *	@see			http://www.larentis.eu/switch/					original examples
 *	@see			http://bdmdesign.github.io/bootstrap-switch-BdMdesigN/examples.html		latest examples
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Base\Aware\DataAware;
use CeusMedia\Bootstrap\Base\Aware\IdAware;
use CeusMedia\Bootstrap\Base\Aware\NameAware;

use CeusMedia\Common\Renderable;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use Stringable;

/**
 *	Replacement for checkbox inputs.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013-2022 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Checkbox extends Structure
{
	use DataAware, IdAware, NameAware;

	/** @var	string|int|float|NULL		$value */
	protected $value;

	/** @var	Stringable|Renderable|string|NULL		$label */
	protected $label;

	/** @var	string						$label */
	protected string $icon;

	/** @var	bool						$checked */
	protected bool $checked;

	/**
	 *	@param		string|NULL				$name
	 *	@param		string|int|float|NULL	$value
	 *	@param		bool					$checked
	 *	@param		Stringable|Renderable|string|NULL	$label
	 *	@param		string					$icon
	 *	@param		array					$data
	 */
	public function __construct(
		?string $name = NULL,
		string|int|float|null $value = NULL,
		bool $checked = FALSE,
		Stringable|Renderable|string|null $label = NULL,
		string $icon = 'fa fa-check',
		array $data = []
	)
	{
		parent::__construct();
		$this->setName( $name );
		if( NULL !== $value )
			$this->setValue( $value );
		$this->setChecked( $checked );
		$this->label	= $label;
		$this->icon		= $icon;
		foreach( $data as $key => $value )
			$this->setData( $key, $value );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$attributes	= [
			'type'		=> 'checkbox',
			'name'		=> $this->name,
			'id'		=> $this->id,
			'value'		=> $this->value,
			'checked'	=> $this->checked ? "checked" : NULL,
		];
		$this->extendAttributesByData( $attributes );
		$input			= HtmlTag::create( 'input', NULL, $attributes );
		$icon			= HtmlTag::create( 'i', '', array( 'class' => "cr-icon ".$this->icon ) );
		$overlay		= HtmlTag::create( 'span', $icon, array( 'class' => "cr" ) );
		$label			= HtmlTag::create( 'label', $input.$overlay.strval(  $this->label ) );
		return HtmlTag::create( 'div', $label, array( 'class' => 'checkbox' ) );
	}

	/**
	 *	@access		public
	 *	@param		bool		$checked
	 *	@return		self		Own instance for method chaining
	 */
	public function setChecked( bool $checked ): self
	{
		$this->checked	= $checked;
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		string|int|float		$value
	 *	@return		self		Own instance for method chaining
	 */
	public function setValue( $value ): self
	{
		if( is_string( $value ) )
			$value	= htmlentities( $value, ENT_QUOTES, 'UTF-8' );
		$this->value	= $value;
		return $this;
	}
}
