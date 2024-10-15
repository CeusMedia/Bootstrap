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
class Dialog extends Structure
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
	 *	@return		self		Modal trigger instance for method chaining
	 * @noinspection PhpDocMissingThrowsInspection
	 */
	public static function create(): self
	{
		/** @var self $dialog */
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
	 *	@return		self
	 */
	public function setAttributes( array $attributes ): self
	{
		$this->attributes	= $attributes;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		Stringable|Renderable|string		$body			...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setBody( Stringable|Renderable|string $body ): self
	{
		$this->body		= $body;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		boolean		$centered			...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setCentered( bool $centered ): self
	{
		$class	= 'modal-dialog-centered';
		$centered ? $this->addClass( $class ) : $this->removeClass( $class );
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$class			...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setCloseButtonClass( string $class ): self
	{
		$this->buttonCloseClass	= $class;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$class			...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setCloseButtonIconClass( string $class ): self
	{
		$this->buttonCloseIconClass	= $class;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$label			...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setCloseButtonLabel( string $label ): self
	{
		$this->buttonCloseLabel	= $label;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$class			...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setDialogClass( string $class ): self
	{
		$this->dialogClass	= $class;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		boolean		$fade			...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setFade( bool $fade ): self
	{
		$this->fade	= $fade;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$action			...
	 *	@param		array		$attributes		...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setFormAction( string $action, array $attributes = [] ): self
	{
		$this->formAction		= $action;
		$this->formAttributes	= $attributes;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		bool		$isUpload		...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setFormIsUpload( bool $isUpload = TRUE ): self
	{
		$this->formIsUpload		= $isUpload;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string|NULL		$onSubmit		...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setFormSubmit( ?string $onSubmit ): self
	{
		$this->formOnSubmit	= $onSubmit;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		Stringable|Renderable|string		$heading		...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setHeading( Stringable|Renderable|string $heading ): self
	{
		$this->heading		= $heading;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$icon			...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setHeaderCloseButtonIcon( string $icon ): self
	{
		$this->headerCloseButtonIcon	= $icon;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$class			...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setSubmitButtonClass( string $class ): self
	{
		$this->buttonSubmitClass	= $class;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$class			...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setSubmitButtonIconClass( string $class ): self
	{
		$this->buttonSubmitIconClass	= $class;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$label			...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setSubmitButtonLabel( string $label ): self
	{
		$this->buttonSubmitLabel	= $label;
		return $this;
	}

	/**
	 *	Enable or disable footer.
	 *	@access		public
	 *	@param		boolean		$use		Flag: use footer (default: yes)
	 *	@return		self
	 *	@todo		code doc
	 */
	public function useFooter( bool $use = TRUE ): self
	{
		$this->useFooter	= $use;
		return $this;
	}

	/**
	 *	Enable or disable header.
	 *	@access		public
	 *	@param		boolean		$use		Flag: use header (default: yes)
	 *	@return		self
	 */
	public function useHeader( bool $use = TRUE ): self
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
		return HtmlTag::create( 'div', [$heading, $buttonClose], ['class' => 'modal-header'] );
	}
}
