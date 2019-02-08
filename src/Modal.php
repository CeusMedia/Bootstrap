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
/**
 *	Modal layer generator.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Modal{

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

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$id				ID of modal dialog container
	 */
	public function __construct( $id = NULL ){
		if( !is_null( $id ) )
			$this->setId( $id );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component or exception message
	 */
	public function __toString(){
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
	public function render(){
		$body		= \UI_HTML_Tag::create( 'div', $this->body, array(
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
		$modal		= \UI_HTML_Tag::create( 'div', array( $header, $body, $footer ), $attributes );
		if( $this->formAction ){
			$attributes	= array_merge( $this->formAttributes, array(
				'action'	=> $this->formAction,
				'method'	=> 'POST',
				'enctype'	=> $this->formIsUpload ? 'multipart/form-data' : NULL,
				'onsubmit'	=> $this->formSubmit ? $this->formSubmit.'; return false;' : NULL,
			) );
			$modal	= \UI_HTML_Tag::create( 'form', $modal, $attributes );
		}
		return $modal;
	}

	protected function renderFooter(){
		if( !$this->useFooter )
			return;
		$iconClose		= '';
		$iconSubmit		= '';
		if( $this->buttonCloseIconClass )
		 	$iconClose	= \UI_HTML_Tag::create( 'i', '', array( 'class' => $this->buttonCloseIconClass ) );
		if( $this->buttonSubmitIconClass )
		 	$iconSubmit	= \UI_HTML_Tag::create( 'i', '', array( 'class' => $this->buttonSubmitIconClass ) );
		$labelClose		= $iconClose.$this->buttonCloseLabel;
		$labelSubmit	= $iconSubmit.$this->buttonSubmitLabel;
		if( $iconClose && $this->buttonCloseLabel )
			$labelClose = $iconClose.'&nbsp;'.$this->buttonCloseLabel;
		if( $iconSubmit && $this->buttonSubmitLabel )
			$labelSubmit = $iconSubmit.'&nbsp;'.$this->buttonSubmitLabel;

		$buttonClose	= \UI_HTML_Tag::create( 'button', $labelClose, array(
			'class'		=> $this->buttonCloseClass,
			'data-dismiss'	=> 'modal',
			'aria-hidden'	=> 'true',
		) );
		$buttonSubmit	= \UI_HTML_Tag::create( 'button', $labelSubmit, array(
			'class'		=> $this->buttonSubmitClass,
			'type'		=> 'submit',
		) );
		$buttonSubmit	= $this->formAction ? $buttonSubmit : '';
		$footer		= \UI_HTML_Tag::create( 'div', array( $buttonClose, $buttonSubmit ), array(
			'class'	=> 'modal-footer',
		) );
		return $footer;
	}

	protected function renderHeader(){
		if( !$this->useHeader )
			return;
		$buttonClose	= \UI_HTML_Tag::create( 'button', $this->headerCloseButtonIcon, array(
			'type'			=> "button",
			'class'			=> "close",
			'data-dismiss'	=> "modal",
			'aria-hidden'	=> "true",
		) );
		$heading	= \UI_HTML_Tag::create( 'h3', $this->heading, array( 'id' => $this->id."-label" ) );
		$header		= \UI_HTML_Tag::create( 'div', array( $buttonClose, $heading ), array(
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
	public function setAttributes( $attributes ){
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
	public function setBody( $body ){
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
	public function setHeaderCloseButtonIcon( $icon ){
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
	public function setCloseButtonClass( $class ){
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
	public function setCloseButtonIconClass( $class ){
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
	public function setCloseButtonLabel( $label ){
		$this->buttonCloseLabel	= $label;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$fade			...
	 *	@return		self
	 *	@todo		code doc
	 */
	public function setFade( $fade ){
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
	public function setFormAction( $action, $attributes = array() ){
		$this->formAction		= $action;
		$this->formAttributes	= $attributes;
		return $this;
	}

	public function setFormIsUpload( $isUpload = TRUE ){
		$this->formIsUpload		= (bool) $isUpload;
		return $this;
	}

	public function setFormSubmit( $onSubmit ){
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
	public function setHeading( $heading ){
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
	public function setId( $id ){
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
	public function setSubmitButtonClass( $class ){
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
	public function setSubmitButtonIconClass( $class ){
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
	public function setSubmitButtonLabel( $label ){
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
	public function useFooter( $use ){
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
	public function useHeader( $use ){
		$this->useHeader	= $use;
		return $this;
	}
}
