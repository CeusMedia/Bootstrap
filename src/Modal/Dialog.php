<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Modal layer generator.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
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
use CeusMedia\Common\UI\HTML\Tag as Tag;

use RangeException;

/**
 *	Modal layer generator.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Dialog extends Structure
{
	use AriaAware, ClassAware, DataAware, IdAware, SizeAware;

	const SIZE_DEFAULT					= '';
	const SIZE_SMALL					= 'modal-sm';
	const SIZE_MEDIUM					= 'modal-md';
	const SIZE_LARGE					= 'modal-lg';
	const SIZE_EXTRA_LARGE				= 'modal-xl';

	const SIZES							= [
		self::SIZE_DEFAULT,
		self::SIZE_SMALL,
		self::SIZE_MEDIUM,
		self::SIZE_LARGE,
		self::SIZE_EXTRA_LARGE,
	];

	public static $defaultFade			= FALSE;
	public static $defaultSize			= self::SIZE_MEDIUM;

	protected $attributes				= array();
	protected $fade						= FALSE;
	protected $heading;
	protected $body;
	protected $formAction;
	protected $formAttributes			= array();
	protected $formIsUpload				= FALSE;
	protected $formSubmit;
	protected $buttonCloseClass			= 'btn';
	protected $buttonCloseIconClass		= '';
	protected $buttonCloseLabel			= 'close';
	protected $buttonSubmitClass		= 'btn';
	protected $buttonSubmitIconClass	= '';
	protected $buttonSubmitLabel		= 'submit';
	protected $headerCloseButtonIcon	= '×';
	protected $useFooter				= TRUE;
	protected $useHeader				= TRUE;
	protected $dialogClass				= '';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$id				ID of modal dialog container
	 */
	public function __construct( $id = NULL )
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
		catch( \Exception $e ){
			print $e->getMessage();
			exit;
		}
	}

	/**
	 *	Create modal trigger object by static call.
	 *	For arguments see code doc of contructor.
	 *	@static
	 *	@access		public
	 *	@return		self		Modal trigger instance for chainability
	 */
	public static function create(): self
	{
		return ObjectFactory::createObject( static::class, func_get_args() );
	}

	/**
	 *	Returns rendered component.
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$body		= Tag::create( 'div', $this->body, array(
			'class'	=> 'modal-body',
		) );
		$footer		= $this->renderFooter();
		$header		= $this->renderHeader();
		$attributes	= array(
			'id'				=> $this->id,
			'class'				=> 'modal hide'.( $this->fade ? ' fade' : '' ),
			'tabindex'			=> '-1',
			'role'				=> 'dialog',
			'aria-hidden'		=> 'true',
			'aria-labelledby'	=> 'myModalLabel',
		);
		foreach( $this->attributes as $key => $value ){
			switch( strtolower( $key ) ){
				case 'id':
				case 'role':
				case 'tabindex':
				case 'aria-hidden':
					break;
				case 'class':
					$attributes['class']	.= strlen( trim( $value ) ) ? ' '.$value : '';
					break;
				default:
					$attributes[$key]	= $value;
			}
		}
		$content	= array( $header, $body, $footer );
		if( version_compare( $this->bsVersion, "4", '>=' ) === TRUE ){
			$content	= Tag::create( 'div', $content, array( 'class' => 'modal-content' ) );
			$content	= Tag::create( 'div', $content, array( 'class' => 'modal-dialog '.join( ' ', $this->classes ), 'role' => 'document' ) );
		}
		$modal	= Tag::create( 'div', array( $content ), $attributes );
		if( $this->formAction ){
			$attributes	= array_merge( $this->formAttributes, array(
				'action'	=> $this->formAction,
				'method'	=> 'POST',
				'enctype'	=> $this->formIsUpload ? 'multipart/form-data' : NULL,
				'onsubmit'	=> $this->formSubmit ? $this->formSubmit.'; return false;' : NULL,
			) );
			$modal	= Tag::create( 'form', $modal, $attributes );
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
	public function setAttributes( $attributes ): self
	{
		$this->attributes	= $attributes;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$body			...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setBody( $body ): self
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
	public function setCentered( $centered ): self
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
	public function setCloseButtonClass( $class ): self
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
	public function setCloseButtonIconClass( $class ): self
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
	public function setCloseButtonLabel( $label ): self
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
	public function setDialogClass( $class ): self
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
	public function setFade( $fade ): self
	{
		$this->fade	= $fade;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$action			...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setFormAction( $action, $attributes = array() ): self
	{
		$this->formAction		= $action;
		$this->formAttributes	= $attributes;
		return $this;
	}

	public function setFormIsUpload( $isUpload = TRUE ): self
	{
		$this->formIsUpload		= (bool) $isUpload;
		return $this;
	}

	public function setFormSubmit( $onSubmit ): self
	{
		$this->formSubmit	= $onSubmit;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$heading		...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setHeading( $heading ): self
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
	public function setHeaderCloseButtonIcon( $icon ): self
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
	public function setSubmitButtonClass( $class ): self
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
	public function setSubmitButtonIconClass( $class ): self
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
	public function setSubmitButtonLabel( $label ): self
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
	public function useFooter( $use ): self
	{
		$this->useFooter	= $use;
		return $this;
	}

	/**
	 *	Enable or disable header.
	 *	@access		public
	 *	@param		boolean		$use		Flag: use header (default: yes)
	 *	@return		self
	 *	@todo		code doc
	 */
	public function useHeader( $use ): self
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
		$footer		= Tag::create( 'div', array( $buttonClose, $buttonSubmit ), array(
			'class'	=> 'modal-footer',
		) );
		return $footer;
	}

	protected function renderHeader(): string
	{
		if( !$this->useHeader )
			return '';
		$buttonClose	= Tag::create( 'button', $this->headerCloseButtonIcon, array(
			'type'			=> 'button',
			'class'			=> 'close',
			'data-dismiss'	=> 'modal',
			'aria-hidden'	=> 'true',
		) );
		$heading	= Tag::create( 'h3', $this->heading, array( 'id' => $this->id.'-label' ) );
		$header		= Tag::create( 'div', array( $heading, $buttonClose ), array(
			'class'	=> 'modal-header',
		) );
		return $header;
	}
}
