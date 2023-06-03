<?php
/**
 *	Replacement for checkbox inputs.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013-2020 {@link https://ceusmedia.de/ Ceus Media}
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

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	Replacement for checkbox inputs.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Checkbox extends Structure
{
	use DataAware, IdAware, NameAware;

	protected $value;
	protected $options;
	protected $label;
	protected $icon;
	protected $checked;

	public function __construct( $name = NULL, $value = NULL, $checked = NULL, $label = NULL, $icon = 'fa fa-check', $data = array() )
	{
		parent::__construct();
		$this->setName( $name );
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
		$attributes	= array(
			'type'		=> 'checkbox',
			'name'		=> $this->name,
			'id'		=> $this->id,
			'value'		=> $this->value,
			'checked'	=> $this->checked ? "checked" : NULL,
		);
		$this->extendAttributesByData( $attributes );
		$input			= HtmlTag::create( 'input', NULL, $attributes );
		$icon			= HtmlTag::create( 'i', '', array( 'class' => "cr-icon ".$this->icon ) );
		$overlay		= HtmlTag::create( 'span', $icon, array( 'class' => "cr" ) );
		$label			= HtmlTag::create( 'label', $input.$overlay.$this->label );
		return HtmlTag::create( 'div', $label, array( 'class' => 'checkbox' ) );
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setChecked( $checked ): self
	{
		$this->checked	= $checked;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setValue( $value ): self
	{
		$this->value	= htmlentities( $value, ENT_QUOTES, 'UTF-8' );
		return $this;
	}
}
