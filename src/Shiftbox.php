<?php
/**
 *	Replacement for checkbox inputs.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@link			https://github.com/nostalgiaz/bootstrap-switch	requires Bootstrap Switch URL description
 *	@see			http://www.larentis.eu/switch/					original examples
 *	@see			http://bdmdesign.github.io/bootstrap-switch-BdMdesigN/examples.html		latest examples
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\Component;

/**
 *	Replacement for checkbox inputs.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 */
class Shiftbox extends Component
{
	const SIZE_DEFAULT		= "";
	const SIZE_LARGE		= "large";
	const SIZE_MINI			= "mini";
	const SIZE_SMALL		= "small";

	protected $name;
	protected $value;
	protected $options;
	protected $size;

	public function __construct( $name = NULL, $value = NULL, $checked = NULL, $data = array() )
	{
		parent::__construct();
		$this->setName( $name );
		$this->setValue( $value );
		$this->setChecked( $checked );
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
			'id'		=> 'input_'.$this->name,
			'name'		=> $this->name,
			'value'		=> $this->value,
			'class'		=> 'shiftbox',
			'checked'	=> $this->checked ? "checked" : NULL,
		);
		$this->extendAttributesByData( $attributes );
		$input			= \UI_HTML_Tag::create( 'input', NULL, $attributes );
		return $input;
		$attributes	= array();
		$attributes['class']	= "make-switch";
//		$this->extendAttributesByData( $attributes );
		return \UI_HTML_Tag::create( 'div', $input, $attributes );
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
	public function setName( $name ): self
	{
		$this->name		= $name;
		$this->setId( $name ? 'input_'.$name : "" );
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
