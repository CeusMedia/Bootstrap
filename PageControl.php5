<?php
class CMM_Bootstrap_PageControl{

	public $baseUrl;
	public $page;
	public $pages;
	public $limit;
	public $patternUrl;
	public $patternIndicator;
	public $size;
	
	const SIZE_MINI		= "mini";
	const SIZE_SMALL		= "small";
	const SIZE_DEFAULT	= "";
	const SIZE_LARGE		= "large";
	
	public function __construct( $baseUrl, $page, $pages ){
		$this->baseUrl			= $baseUrl;
		$this->page				= abs( (int) $page );
		$this->pages			= abs( (int) $pages );
		$this->patternUrl		= "/%s";
		$this->patternIndicator	= "<b>%s</b> / %s";
		$this->fragment			= "";
	}

	public function __toString(){
		return $this->render();
	}

	protected function getUrl( $page = 0 ){
		$fragment	= $this->fragment ? "#".$this->fragment : "";
		$part		= (int)$page > 0 ? sprintf( $this->patternUrl, $page ) : "";
		return $this->baseUrl.$part.$fragment;
	}
	
	public function render(){
		if( $this->pages <= 1 )
			return "";
		$size	= $this->size ? 'btn-'.$this->size : NULL;
		$buttons	= array(
			(object) array(
				'url'		=> $this->getUrl( 0 ),
				'label'		=> NULL,
				'class'		=> $size,
				'icon'		=> new CMM_Bootstrap_Icon( 'fast-backward' ),
				'disabled'	=> $this->page === 0,
			),
			(object) array(
				'url'		=> $this->getUrl( $this->page - 1 ),
				'label'		=> NULL,
				'class'		=> $size,
				'icon'		=> new CMM_Bootstrap_Icon( 'backward' ),
				'disabled'	=> $this->page === 0,
			),
			(object) array(
				'label'		=> sprintf( $this->patternIndicator, $this->page + 1, $this->pages ),
				'class'		=> $size.' page-indicator',
				'icon'		=> NULL,
				'disabled'	=> TRUE,
			),
			(object) array(
				'url'		=> $this->getUrl( $this->page + 1 ),
				'label'		=> NULL,
				'class'		=> $size,
				'icon'		=> new CMM_Bootstrap_Icon( 'forward' ),
				'disabled'	=> $this->page === $this->pages - 1,
			),
			(object) array(
				'url'		=> $this->getUrl( $this->pages - 1 ),
				'label'		=> NULL,
				'class'		=> $size,
				'icon'		=> new CMM_Bootstrap_Icon( 'fast-forward' ),
				'disabled'	=> $this->page === $this->pages - 1,
			),
		);
		$group		= new CMM_Bootstrap_ButtonGroup();
		foreach( $buttons as $button ){
			if( isset( $button->url ) )
				$button	= new CMM_Bootstrap_LinkButton( $button->url, $button->label, $button->class, $button->icon, $button->disabled );
			else
				$button	= new CMM_Bootstrap_Button( $button->label, $button->class, $button->icon, $button->disabled );
			$group->add( $button );
		}
		$toolbar	= new CMM_Bootstrap_ButtonToolbar( $group );
		return UI_HTML_Tag::create( 'div', $toolbar, array( 'class' => 'page-control' ) );
	}
}
?>