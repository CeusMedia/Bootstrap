<?php
/**
 *	Modal layer generator.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\Structure;
use \UI_HTML_Tag as Tag;

/**
 *	Modal layer generator.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Modal extends Structure
{
	protected $attributes				= array();
	protected $id;
	protected $fade						= TRUE;
	protected $heading;
	protected $body;
	protected $formAction;
	protected $formAttributes			= array();
	protected $formIsUpload				= FALSE;
	protected $formSubmit;
	protected $buttonCloseClass			= "btn";
	protected $buttonCloseIconClass		= "";
	protected $buttonCloseLabel			= "close";
	protected $buttonSubmitClass		= "btn";
	protected $buttonSubmitIconClass	= "";
	protected $buttonSubmitLabel		= "submit";
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
		if( version_compare( $this->bsVersion, 4, '>=' ) === TRUE ){
			$content	= Tag::create( 'div', $content, array( 'class' => 'modal-content' ) );
			$content	= Tag::create( 'div', $content, array( 'class' => 'modal-dialog '.$this->dialogClass, 'role' => 'document' ) );
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

	protected function renderFooter(): string
	{
		if( !$this->useFooter )
			return '';
		$iconClose		= '';
		$iconSubmit		= '';
		if( $this->buttonCloseIconClass )
		 	$iconClose	= Tag::create( 'i', '', array( 'class' => $this->buttonCloseIconClass ) );
		if( $this->buttonSubmitIconClass )
		 	$iconSubmit	= Tag::create( 'i', '', array( 'class' => $this->buttonSubmitIconClass ) );
		$labelClose		= $iconClose.$this->buttonCloseLabel;
		$labelSubmit	= $iconSubmit.$this->buttonSubmitLabel;
		if( $iconClose && $this->buttonCloseLabel )
			$labelClose = $iconClose.'&nbsp;'.$this->buttonCloseLabel;
		if( $iconSubmit && $this->buttonSubmitLabel )
			$labelSubmit = $iconSubmit.'&nbsp;'.$this->buttonSubmitLabel;

		$buttonClose	= Tag::create( 'button', $labelClose, array(
			'class'		=> $this->buttonCloseClass,
			'data-dismiss'	=> 'modal',
			'aria-hidden'	=> 'true',
		) );
		$buttonSubmit	= Tag::create( 'button', $labelSubmit, array(
			'class'		=> $this->buttonSubmitClass,
			'type'		=> 'submit',
		) );
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
			'type'			=> "button",
			'class'			=> "close",
			'data-dismiss'	=> "modal",
			'aria-hidden'	=> "true",
		) );
		$heading	= Tag::create( 'h3', $this->heading, array( 'id' => $this->id."-label" ) );
		$header		= Tag::create( 'div', array( $heading, $buttonClose ), array(
			'class'	=> 'modal-header',
		) );
		return $header;
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
	 *	@param		string		$body			...
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
	 *	@param		string		$fade			...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setFade( $fade ): self
	{
		$this->fade		= $fade;
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
	 *	@param		string		$id				...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setId( $id ): self
	{
		$this->id		= $id;
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
}
