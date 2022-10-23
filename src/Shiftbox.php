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

use CeusMedia\Bootstrap\Base\Element;
use CeusMedia\Bootstrap\Base\Aware\NameAware;

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	Replacement for checkbox inputs.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 */
class Shiftbox extends Element
{
	use NameAware;

	public const SIZE_DEFAULT	= '';
	public const SIZE_LARGE		= 'large';
	public const SIZE_MINI		= 'mini';
	public const SIZE_SMALL		= 'small';

	protected string $value;
	protected bool $checked;

	public function __construct( string $name = NULL, string $value = NULL, bool $checked = NULL, array $data = [] )
	{
		parent::__construct( '' );
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
			'checked'	=> $this->checked ? 'checked' : NULL,
		);
		$this->extendAttributesByData( $attributes );
		return HtmlTag::create( 'input', NULL, $attributes );
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
	 *	@param		string		$value
	 *	@return		self		Own instance for method chaining
	 */
	public function setValue( string $value ): self
	{
		$this->value	= htmlentities( $value, ENT_QUOTES, 'UTF-8' );
		return $this;
	}
}
