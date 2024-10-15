<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

/**
 *	Modal layer generator.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2024 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Modal;

use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Base\Aware\AriaAware;
use CeusMedia\Bootstrap\Base\Aware\ClassAware;
use CeusMedia\Bootstrap\Base\Aware\DataAware;
use CeusMedia\Bootstrap\Base\Aware\IdAware;
use CeusMedia\Bootstrap\Base\Aware\SizeAware;
use CeusMedia\Bootstrap\Button;
use CeusMedia\Bootstrap\Icon;

use CeusMedia\Common\Alg\Obj\Factory as ObjectFactory;
use CeusMedia\Common\Renderable;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

use Exception;
use Stringable;

/**
 *	Modal layer generator.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2024 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Dialog extends Structure implements Renderable, Stringable
{
	use AriaAware, ClassAware, DataAware, IdAware, SizeAware;

	public const SIZE_DEFAULT				= '';
	public const SIZE_SMALL					= 'modal-sm';
	public const SIZE_MEDIUM				= 'modal-md';
	public const SIZE_LARGE					= 'modal-lg';
	public const SIZE_EXTRA_LARGE			= 'modal-xl';

	public const SIZES						= [
		self::SIZE_DEFAULT,
		self::SIZE_SMALL,
		self::SIZE_MEDIUM,
		self::SIZE_LARGE,
		self::SIZE_EXTRA_LARGE,
	];

	public static bool $defaultFade			= FALSE;
	public static string $defaultSize		= self::SIZE_MEDIUM;

	/** @var Stringable|Renderable|string $heading */
	protected Stringable|Renderable|string $heading		= '';

	/** @var Stringable|Renderable|string $body */
	protected Stringable|Renderable|string $body		= '';

	protected array $attributes				= [];
	protected string $buttonCloseClass		= 'btn';
	protected string $buttonCloseIconClass	= '';
	protected string $buttonCloseLabel		= 'close';
	protected string $buttonSubmitClass		= 'btn';
	protected string $buttonSubmitIconClass	= '';
	protected string $buttonSubmitLabel		= 'submit';
	protected string $headerCloseButtonIcon	= '×';
	protected string $dialogClass			= '';
	protected bool $fade					= FALSE;
	protected ?string $formAction			= NULL;
	protected array $formAttributes			= [];
	protected bool $formIsUpload			= FALSE;
	protected ?string $formOnSubmit			= NULL;
	protected bool $useFooter				= TRUE;
	protected bool $useHeader				= TRUE;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string|NULL		$id		ID of modal dialog container
	 */
	public function __construct( ?string $id = NULL )
	{
		parent::__construct();
		if( !is_null( $id ) )
			$this->setId( $id );
		$this->setFade( static::$defaultFade );
		$this->setSize( static::$defaultSize );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component or exception message
	 *	@todo		allow to throw exception since PHP 7.4
	 */
	public function __toString(): string
	{
		try{
			return $this->render();
		}
		catch( Exception $e ){
			print $e->getMessage();
			exit;
		}
	}

	/**
	 *	Create modal trigger object by static call.
	 *	For arguments see code doc of constructor.
	 *	@static
	 *	@access		public
	 *	@return		static		Modal trigger instance for method chaining
	 * @noinspection PhpDocMissingThrowsInspection
	 */
	public static function create(): static
	{
		/** @var static $dialog */
		/** @noinspection PhpUnhandledExceptionInspection */
		$dialog	= ObjectFactory::createObject( static::class, func_get_args() );
		return $dialog;
	}

	/**
	 *	Returns rendered component.
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$body		= HtmlTag::create( 'div', $this->body, [
			'class'	=> 'modal-body',
		] );
		$footer		= $this->renderFooter();
		$header		= $this->renderHeader();
		$attributes	= [
			'id'				=> $this->id,
			'class'				=> 'modal hide'.( $this->fade ? ' fade' : '' ),
			'tabindex'			=> '-1',
			'role'				=> 'dialog',
			'aria-hidden'		=> 'true',
			'aria-labelledby'	=> 'myModalLabel',
		];
		foreach( $this->attributes as $key => $value ){
			switch( strtolower( $key ) ){
				case 'id':
				case 'role':
				case 'tabindex':
				case 'aria-hidden':
					break;
				case 'class':
					$attributes['class']	.= strlen( trim( (string) $value ) ) ? ' '.$value : '';
					break;
				default:
					$attributes[$key]	= $value;
			}
		}
		$content	= [$header, $body, $footer];
		if( TRUE === version_compare( $this->bsVersion, '4', '>=' ) ){
			$content	= HtmlTag::create( 'div', $content, ['class' => 'modal-content'] );
			$content	= HtmlTag::create( 'div', $content, ['class' => 'modal-dialog '.join( ' ', $this->classes ), 'role' => 'document'] );
		}
		$modal	= HtmlTag::create( 'div', [$content], $attributes );
		if( NULL !== $this->formAction ){
			$attributes	= array_merge( $this->formAttributes, [
				'action'	=> $this->formAction,
				'method'	=> 'POST',
				'enctype'	=> $this->formIsUpload ? 'multipart/form-data' : NULL,
				'onsubmit'	=> NULL !== $this->formOnSubmit ? $this->formOnSubmit.'; return false;' : NULL,
			] );
			$modal	= HtmlTag::create( 'form', $modal, $attributes );
		}
		return $modal;
	}

	/**
	 *	Sets additional modal attributes.
	 *	Set values for id, role, tabindex, aria-hidden will be ignored.
	 *	Set value for class will be added.
	 *	@access		public
	 *	@param		array		$attributes		Map of button attributes
	 *	@return		static
	 */
	public function setAttributes( array $attributes ): static
	{
		$this->attributes	= $attributes;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		Stringable|Renderable|string		$body			...
	 *	@return		static
	 *	@todo		code doc
	 */
	public function setBody( Stringable|Renderable|string $body ): static
	{
		$this->body		= $body;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		boolean		$centered			...
	 *	@return		static
	 *	@todo		code doc
	 */
	public function setCentered( bool $centered ): static
	{
		$class	= 'modal-dialog-centered';
		$centered ? $this->addClass( $class ) : $this->removeClass( $class );
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$class			...
	 *	@return		static
	 *	@todo		code doc
	 */
	public function setCloseButtonClass( string $class ): static
	{
		$this->buttonCloseClass	= $class;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$class			...
	 *	@return		static
	 *	@todo		code doc
	 */
	public function setCloseButtonIconClass( string $class ): static
	{
		$this->buttonCloseIconClass	= $class;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$label			...
	 *	@return		static
	 *	@todo		code doc
	 */
	public function setCloseButtonLabel( string $label ): static
	{
		$this->buttonCloseLabel	= $label;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$class			...
	 *	@return		static
	 *	@todo		code doc
	 */
	public function setDialogClass( string $class ): static
	{
		return $this->addClass( $class );
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		boolean		$fade			...
	 *	@return		static
	 *	@todo		code doc
	 */
	public function setFade( bool $fade ): static
	{
		$this->fade	= $fade;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$action			...
	 *	@param		array		$attributes		...
	 *	@return		static
	 *	@todo		code doc
	 */
	public function setFormAction( string $action, array $attributes = [] ): static
	{
		$this->formAction		= $action;
		$this->formAttributes	= $attributes;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		bool		$isUpload		...
	 *	@return		static
	 *	@todo		code doc
	 */
	public function setFormIsUpload( bool $isUpload = TRUE ): static
	{
		$this->formIsUpload		= $isUpload;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string|NULL		$onSubmit		...
	 *	@return		static
	 *	@todo		code doc
	 */
	public function setFormSubmit( ?string $onSubmit ): static
	{
		$this->formOnSubmit	= $onSubmit;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		Stringable|Renderable|string		$heading		...
	 *	@return		static
	 *	@todo		code doc
	 */
	public function setHeading( Stringable|Renderable|string $heading ): static
	{
		$this->heading		= $heading;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$icon			...
	 *	@return		static
	 *	@todo		code doc
	 */
	public function setHeaderCloseButtonIcon( string $icon ): static
	{
		$this->headerCloseButtonIcon	= $icon;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$class			...
	 *	@return		static
	 *	@todo		code doc
	 */
	public function setSubmitButtonClass( string $class ): static
	{
		$this->buttonSubmitClass	= $class;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$class			...
	 *	@return		static
	 *	@todo		code doc
	 */
	public function setSubmitButtonIconClass( string $class ): static
	{
		$this->buttonSubmitIconClass	= $class;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$label			...
	 *	@return		static
	 *	@todo		code doc
	 */
	public function setSubmitButtonLabel( string $label ): static
	{
		$this->buttonSubmitLabel	= $label;
		return $this;
	}

	/**
	 *	Enable or disable footer.
	 *	@access		public
	 *	@param		boolean		$use		Flag: use footer (default: yes)
	 *	@return		static
	 *	@todo		code doc
	 */
	public function useFooter( bool $use = TRUE ): static
	{
		$this->useFooter	= $use;
		return $this;
	}

	/**
	 *	Enable or disable header.
	 *	@access		public
	 *	@param		boolean		$use		Flag: use header (default: yes)
	 *	@return		static
	 */
	public function useHeader( bool $use = TRUE ): static
	{
		$this->useHeader	= $use;
		return $this;
	}

	/*  --  PROTECTED  --  */

	protected function renderFooter(): string
	{
		if( !$this->useFooter )
			return '';
		$iconClose		= '';
		$iconSubmit		= '';
		if( $this->buttonCloseIconClass )
		 	$iconClose	= new Icon( $this->buttonCloseIconClass );
		if( $this->buttonSubmitIconClass )
		 	$iconSubmit	= new Icon( $this->buttonSubmitIconClass );
		$labelClose		= $iconClose.$this->buttonCloseLabel;
		$labelSubmit	= $iconSubmit.$this->buttonSubmitLabel;
		if( $iconClose && $this->buttonCloseLabel )
			$labelClose = $iconClose.'&nbsp;'.$this->buttonCloseLabel;
		if( $iconSubmit && $this->buttonSubmitLabel )
			$labelSubmit = $iconSubmit.'&nbsp;'.$this->buttonSubmitLabel;

		$buttonClose	= new Button( $labelClose, $this->buttonCloseClass );
		$buttonClose->setAria( 'hidden', 'true' )->setData( 'dismiss', 'modal' );
		$buttonSubmit	= new Button( $labelSubmit, $this->buttonSubmitClass );
		$buttonSubmit->setType( Button::TYPE_SUBMIT );
		$buttonSubmit	= $this->formAction ? $buttonSubmit : '';
		return HtmlTag::create( 'div', [$buttonClose, $buttonSubmit], ['class' => 'modal-footer'] );
	}

	protected function renderHeader(): string
	{
		if( !$this->useHeader )
			return '';
		$buttonClose	= HtmlTag::create( 'button', $this->headerCloseButtonIcon, [
			'type'			=> 'button',
			'class'			=> 'close',
			'data-dismiss'	=> 'modal',
			'aria-hidden'	=> 'true',
		] );
		$heading	= HtmlTag::create( 'h3', $this->heading, ['id' => $this->id.'-label'] );
		return HtmlTag::create( 'div', [$buttonClose, $heading], ['class' => 'modal-header'] );
	}
}
