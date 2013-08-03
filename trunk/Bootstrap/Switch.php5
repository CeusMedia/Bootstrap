<?php
/**
 *	Replacement for checkbox inputs.
 *	@category		cmModules
 *	@package		Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@since			0.3.0
 *	@version		$Id$
 *	@link			https://github.com/nostalgiaz/bootstrap-switch	requires Bootstrap Switch URL description
 *	@see			http://www.larentis.eu/switch/					original examples
 *	@see			http://bdmdesign.github.io/bootstrap-switch-BdMdesigN/examples.html		latest examples
 */
/**
 *	Replacement for checkbox inputs.
 *	@category		cmModules
 *	@package		Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@since			0.3.0
 *	@version		$Id$
 */
class CMM_Bootstrap_Switch extends CMM_Bootstrap_Abstract{

	const SIZE_DEFAULT	= "";
	const SIZE_LARGE		= "large";
	const SIZE_MINI		= "mini";
	const SIZE_SMALL		= "small";

	protected $name;
	protected $value;
	protected $options;
	protected $size;

	public function __construct( $name = NULL, $value = NULL, $checked = NULL, $size = self::SIZE_DEFAULT, $data = array() ){
		$this->setName( $name );
		$this->setValue( $value );
		$this->setChecked( $checked );
		$this->size		= $size;
		foreach( $data as $key => $value )
			$this->setData( $key, $value );
	}

	public function render(){
		$attributes	= array(
			'type'		=> 'checkbox',
			'name'		=> $this->name,
			'value'		=> $this->value,
			'checked'	=> $this->checked ? "checked" : NULL,
		);
		$input			= UI_HTML_Tag::create( 'input', NULL, $attributes );
		$attributes	= array();
		$attributes['class']	= "make-switch".( $this->size ? " switch-".$this->size : "" );
		$this->extendAttributesByData( $attributes );
		return UI_HTML_Tag::create( 'div', $input, $attributes );
	}

	public function setChecked( $checked ){
		$this->checked	= $checked;
	}

	public function setName( $name ){
		$this->name		= $name;
		$this->setId( $name ? 'input_'.$name : "" );
	}

	public function setValue( $value ){
		$this->value	= htmlentities( $value, ENT_QUOTES, 'UTF-8' );
	}
}
?>
