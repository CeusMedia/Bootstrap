<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

/**
 *	Replacement for checkbox inputs.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@link			https://github.com/nostalgiaz/bootstrap-switch	requires Bootstrap Switch URL description
 *	@see			http://www.larentis.eu/switch/					original examples
 *	@see			http://bdmdesign.github.io/bootstrap-switch-BdMdesigN/examples.html		latest examples
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Base\Element;
use CeusMedia\Bootstrap\Base\Aware\NameAware;

use CeusMedia\Common\Renderable;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use Stringable;

/**
 *	Replacement for checkbox inputs.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 */
class Shiftbox extends Element
{
	use NameAware;

	public const string SIZE_DEFAULT	= '';
	public const string SIZE_LARGE		= 'large';
	public const string SIZE_MINI		= 'mini';
	public const string SIZE_SMALL		= 'small';

	protected string $value;
	protected bool $checked;

	public function __construct( string $name, string $value, bool $checked = FALSE, array $data = [] )
	{
		parent::__construct( '' );
		$this->setName( $name );
		$this->setValue( $value );
		$this->setChecked( $checked );
		foreach( $data as $key => $v )
			$this->setData( $key, $v );
	}

	/**
	 *	Renders switch for set Bootstrap version.
	 *	Bootstrap 2 and 3: checkbox with class "shiftbox", to be turned into a switch by jQuery plugin bootstrap-switch.
	 *	Options for this plugin are given as data attributes (like size, on-text, off-text, on-color).
	 *	Bootstrap 4: custom switch of Bootstrap ("custom-control custom-switch", since 4.2).
	 *	Bootstrap 5: form switch of Bootstrap ("form-check form-switch").
	 *	Since Bootstrap 4, the plugin is not needed and not supported: its options are not used, but the label is shown.
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$version	= $this->getBsVersion();
		$attributes	= [
			'type'		=> 'checkbox',
			'id'		=> $this->id ?? 'input_'.$this->name,
			'name'		=> $this->name,
			'value'		=> $this->value,
			'class'		=> NULL,
			'checked'	=> $this->checked ? 'checked' : NULL,
		];
		$this->extendAttributesByEvents( $attributes );
		$label		= $this->getContentAsString();
		switch( $version ){
			case BsVersion::V2:
			case BsVersion::V3:
				$attributes['class']	= 'shiftbox';
				$this->extendAttributesByData( $attributes );						//  options of plugin bootstrap-switch
				$input	= HtmlTag::create( 'input', NULL, $attributes );
				if( '' === $label )
					return $input;
				if( BsVersion::V2 === $version )
					return HtmlTag::create( 'label', $input.'&nbsp;'.$label, ['class' => 'checkbox'] );
				return HtmlTag::create( 'div', HtmlTag::create( 'label', $input.'&nbsp;'.$label ), ['class' => 'checkbox'] );
			case BsVersion::V4:
				$attributes['class']	= 'custom-control-input';
				$input	= HtmlTag::create( 'input', NULL, $attributes );
				$label	= HtmlTag::create( 'label', $label, ['class' => 'custom-control-label', 'for' => $attributes['id']] );	//  label draws switch, even if empty
				return HtmlTag::create( 'div', $input.$label, ['class' => 'custom-control custom-switch'] );
			case BsVersion::V5:
				$attributes['class']	= 'form-check-input';
				$attributes['role']		= 'switch';
				$input	= HtmlTag::create( 'input', NULL, $attributes );
				if( '' !== $label )
					$input	.= HtmlTag::create( 'label', $label, ['class' => 'form-check-label', 'for' => $attributes['id']] );
				return HtmlTag::create( 'div', $input, ['class' => 'form-check form-switch'] );
		}
	}

	/**
	 *	@access		public
	 *	@param		bool		$checked
	 *	@return		static		Own instance for method chaining
	 */
	public function setChecked( bool $checked ): static
	{
		$this->checked	= $checked;
		return $this;
	}

	/**
	 *	Sets label, shown beside switch.
	 *	@access		public
	 *	@param		Stringable|Renderable|string|NULL	$label
	 *	@return		static
	 */
	public function setLabel( Stringable|Renderable|string|null $label ): static
	{
		return $this->setContent( $label ?? '' );
	}

	/**
	 *	@access		public
	 *	@param		string		$value
	 *	@return		static		Own instance for method chaining
	 */
	public function setValue( string $value ): static
	{
		$this->value	= htmlentities( $value, ENT_QUOTES, 'UTF-8' );
		return $this;
	}
}
