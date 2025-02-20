<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

use CeusMedia\Bootstrap\Icon;
use CeusMedia\Common\Net\HTTP\Request\Receiver as Request;
use CeusMedia\Common\UI\HTML\Elements as HtmlElements;
use CeusMedia\Common\UI\HTML\PageFrame as HtmlPage;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

class DemoAppTemplate
{
	protected int $faVersion		= 4;
	protected int $bsVersion		= 2;
	protected string $title;
	protected string $optionsControls	= '';
	protected string $content			= '';
	protected string $url				= './';

	protected array $scripts		= [];
	protected array $styles			= [];

	protected static array $versionsBootstrap		= [2, 3, 4, 5];
	protected static array $versionsFontAwesome	= [4, 5, 6];


	public function __construct( string $title, string $url )
	{
		$this->title = $title;
		$this->setUrl( $url );
	}

	public function addScript( string $script ): static
	{
		$this->scripts[] = $script;
		return $this;
	}

	public function addStyle( string $style ): static
	{
		$this->styles[] = $style;
		return $this;
	}

	public function getBsVersion(): int
	{
		return $this->bsVersion;
	}

	public function run(): void
	{
		$body	= HtmlTag::create( 'div', [
			HtmlTag::create( 'div', [
				HtmlTag::create( 'h1', '<span class="muted text-muted">CeusMedia Bootstrap</span> '.$this->title, ['class' => 'display-5'] ),
			], ['class' => 'not-bs2-hero-unit not-bs4-jumbotron'] ),
			$this->renderVersionForm(),
			'<hr/>',
			HtmlTag::create( 'form', $this->content, ['id' => 'test-form'] ),
		], ['class' => 'container'] );

		$pathCDN	= "https://cdn.ceusmedia.de/";
		$scripts	= [
			$pathCDN."js/jquery/1.10.2.js",
		];
		$styles		= [
		];
		foreach( $this->scripts as $script )
			$scripts[]	= $script;
		foreach( $this->styles as $style )
			$styles[]	= $style;
		switch( $this->bsVersion ){
			case 2:
				$scripts[]	= 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/js/bootstrap.min.js';
				$styles[]	= 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/css/bootstrap.min.css';
				$styles[]	= 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/css/bootstrap-responsive.min.css';
				break;
			case 3:
				$scripts[]	= 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js';
				$styles[]	= 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css';
				$styles[]	= 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap-theme.min.css';
				break;
			case 4:
				$styles[]	= 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css';
				$scripts[]	= 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js';
				break;
			case 5:
				$styles[]	= 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css';
				$scripts[]	= 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js';
				break;
		}

		switch( $this->faVersion ){
			case 6:
				$styles[]	= 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css';
				$scripts[]	= 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js';
				break;
			case 5:
				$styles[]	= 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.7.2/css/all.min.css';
				$scripts[]	= 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.7.2/js/all.min.js';
				break;
			case 4:
			default:
				$styles[]	= 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css';
				break;
		}

		/*  --  OUTPUT  --  */
		$page	= new HtmlPage();
		$page->addBody( VersionProcessor::process( trim( $body ), (string) $this->bsVersion ) );
		$page->setTitle( 'RadioBar Demo – Bootstrap – Ceus Media' );
		foreach( $scripts as $url ) $page->addJavaScript( $url );
		foreach( $styles as $url ) $page->addStylesheet( $url );
		print( $page->build( ['class' => 'bs-'.$this->bsVersion] ) );
	}

	public function setFontAwesomeVersion( int $version ): static
	{
		$this->faVersion = $version;
		$this->applyRequestParameters();
		return $this;
	}

	public function setUrl( string $url ): static
	{
		$this->url = $url;
		return $this;
	}

	public function setBootstrapVersion( int $version ): static
	{
		$this->bsVersion = $version;
		$this->applyRequestParameters();
		return $this;
	}

	public function setOptionsControls( string $optionsControls ): static
	{
		$this->optionsControls = $optionsControls;
		return $this;
	}
	public function setContent( string $content ): static
	{
		$this->content = $content;
		return $this;
	}

	protected function renderVersionForm(): string
	{
		return HtmlTag::create( 'form', [
			HtmlTag::create( 'div', [
				HtmlTag::create( 'div', [
					HtmlTag::create( 'label', 'Bootstrap', ['for' => 'input_version'] ),
					HtmlTag::create( 'select', HtmlElements::Options( array_combine( static::$versionsBootstrap, static::$versionsBootstrap ), (string) $this->bsVersion ), [
						'name'		=> 'bootstrap',
						'id'		=> 'input_bootstrap',
						'class' 	=> 'bs2-span12 bs3-form-control bs4-form-control bs5-form-select',
						'onchange'	=> 'this.form.submit()',
					] ),
				], ['class' => 'bs2-span3 bs3-col-sm-2 bs3-col-md-2 bs3-col-lg-2 bs4-form-group bs4-col-md-2 bs5-col-md-2'] ),
				HtmlTag::create( 'div', [
					HtmlTag::create( 'label', 'Fontawesome', ['for' => 'input_fontawesome'] ),
					HtmlTag::create( 'select', HtmlElements::Options( array_combine( static::$versionsFontAwesome, static::$versionsFontAwesome ), (string) $this->faVersion ), [
						'name'		=> 'fontawesome',
						'id'		=> 'input_fontawesome',
						'class' 	=> 'bs2-span12 bs3-form-control bs4-form-control bs5-form-select',
						'onchange'	=> 'this.form.submit()',
					] ),
				], ['class' => 'bs2-span3 bs3-col-sm-2 bs3-col-md-2 bs3-col-lg-2 bs4-form-group bs4-col-md-2 bs5-col-md-2'] ),
				HtmlTag::create( 'div', [
					$this->optionsControls,
				], ['class' => 'bs2-span3 bs2-offset1 bs3-col-sm-3 bs3-col-md-3 bs3-col-lg-3 bs3-form-group bs4-form-group bs4-col-md-3 bs5-col-md-3 bs5-offset-1 '] ),
			], ['class' => 'bs2-row-fluid bs3-row bs4-form-row bs5-row'] ),
		], ['action' => $this->url, 'method' => 'GET'] );

	}

	protected function applyRequestParameters(): void
	{
		$request	= new Request();
		if( $request->has( 'bootstrap' ) && in_array( (int) $request->get( 'bootstrap' ), static::$versionsBootstrap, TRUE ) )
			$this->bsVersion	= (int) $request->get( 'bootstrap' );
		if( $request->has( 'fontawesome' ) && in_array( (int) $request->get( 'fontawesome' ), static::$versionsFontAwesome, TRUE ) )
			$this->faVersion	= (int) $request->get( 'fontawesome' );
		CeusMedia\Bootstrap\Base\Element::$defaultBsVersion		= (string) $this->bsVersion;
		CeusMedia\Bootstrap\Base\Structure::$defaultBsVersion	= (string) $this->bsVersion;

		switch( $this->faVersion ){
			case 6:
				Icon::$defaultSet	= 'fontawesome6';
				Icon::$defaultStyle	= 'solid';
				Icon::$defaultSize	= ['fixed'];
				break;
			case 5:
				Icon::$defaultSet	= 'fontawesome5';
				Icon::$defaultStyle	= 'solid';
				Icon::$defaultSize	= ['fixed'];
				break;
			case 4:
			default:
				Icon::$defaultSet	= 'fontawesome4';
				break;
		}
	}
}
