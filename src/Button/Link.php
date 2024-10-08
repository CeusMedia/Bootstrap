<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2023 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Button;

use CeusMedia\Bootstrap\Base\Element;
use CeusMedia\Bootstrap\Base\Aware\DisabledAware;
use CeusMedia\Bootstrap\Base\Aware\IconAware;

use CeusMedia\Bootstrap\Icon;
use CeusMedia\Common\Renderable;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use Stringable;

use function addslashes;
use function join;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2023 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Link extends Element
{
	use DisabledAware, IconAware;

	protected ?string $confirm		= NULL;
	protected ?string $title		= NULL;
	protected string $url;

	/**
	 *	@param		string					$url
	 *	@param		Stringable|Renderable|string		$content
	 *	@param		array|string|NULL		$class
	 *	@param		Icon|string|NULL		$icon
	 *	@param		bool					$disabled
	 */
	public function __construct(
		string $url,
		Stringable|Renderable|string $content,
		array|string|null $class = NULL,
		Icon|string|null $icon = NULL,
		bool $disabled = FALSE
	)
	{
		parent::__construct( $content, $class );
		$this->setUrl( $url );
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
		$attributes	= [
			'id'		=> $this->id,
			'class'		=> 'btn '.join( ' ', $this->classes ),
			'href'		=> $this->url,
			'title'		=> $this->title ? addslashes( $this->title ) : NULL,
			'role'		=> 'button',
			'onclick'	=> NULL,
		];
		if( $this->confirm ){
			$attributes['onclick']	= 'if(!confirm(\''.addslashes( $this->confirm ).'\'))return false;';
		}
		if( $this->disabled ){
			$attributes['class']	.= ' disabled';
			$attributes['data-attr-href']		= $attributes['href'];
			$attributes['data-attr-onclick']	= $attributes['onclick'];
			$attributes['href']		= NULL;
			$attributes['onclick']	= NULL;
		}
		$this->extendAttributesByEvents( $attributes );
		$icon		= (string) $this->icon;
		$content	= $this->getContentAsString();
		if( 0 !== strlen( $icon ) && 0 !== strlen( $content ) )
			$icon	.= ' ';
		return HtmlTag::create( 'a', $icon.$content, $attributes );
	}

	/**
	 *	@access		public
	 *	@param		string|NULL		$message
	 *	@return		self		Own instance for method chaining
	 */
	public function setConfirm( ?string $message = NULL ): self
	{
		$this->confirm	= $message;
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		string		$title
	 *	@return		self		Own instance for method chaining
	 */
	public function setTitle( string $title ): self
	{
		$this->title	= $title;
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		string		$url
	 *	@return		self		Own instance for method chaining
	 */
	public function setUrl( string $url ): self
	{
		$this->url		= $url;
		return $this;
	}
}
