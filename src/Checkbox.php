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
	protected $name;
	protected $value;
	protected $options;
	protected $label;

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
			'id'		=> 'input_'.$this->name,
			'value'		=> $this->value,
			'checked'	=> $this->checked ? "checked" : NULL,
		);
		$this->extendAttributesByData( $attributes );
		$input			= \UI_HTML_Tag::create( 'input', NULL, $attributes );
		$icon			= \UI_HTML_Tag::create( 'i', '', array( 'class' => "cr-icon ".$this->icon ) );
		$overlay		= \UI_HTML_Tag::create( 'span', $icon, array( 'class' => "cr" ) );
		$label			= \UI_HTML_Tag::create( 'label', $input.$overlay.$this->label );
		return \UI_HTML_Tag::create( 'div', $label, array( 'class' => 'checkbox' ) );
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
	 *	@return		object		Own instance for chainability
	 */
	public function setName( $name ):self
	{
		$this->name		= $name;
		$this->setId( $name ? 'input_'.$name : "" );
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		object		Own instance for chainability
	 */
	public function setValue( $value ): self
	{
		$this->value	= htmlentities( $value, ENT_QUOTES, 'UTF-8' );
		return $this;
	}
}
