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

	/**
	 *	Returns URL of a demo, keeping selected Bootstrap and FontAwesome versions.
	 *	@param		string					$file			File name of demo, like "modal.php"
	 *	@param		array<string,mixed>		$parameters		Further query parameters, overriding versions as well
	 *	@return		string
	 */
	public function getDemoUrl( string $file, array $parameters = [] ): string
	{
		return './'.$file.'?'.http_build_query( array_merge( ['bootstrap' => $this->bsVersion, 'fontawesome' => $this->faVersion], $parameters ) );
	}

	/**
	 *	Returns URLs of scripts and styles: jQuery, Bootstrap and FontAwesome of selected versions, then own ones.
	 *	@return		array{0: list<string>, 1: list<string>}		Scripts and styles
	 */
	public function getAssets(): array
	{
		$pathCDN	= "https://cdn.ceusmedia.de/";
		$scripts	= [
			$pathCDN."js/jquery/1.10.2.js",
		];
		$styles		= [
		];
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

		//  own scripts and styles after frameworks, so own rules win over framework rules of same specificity
		foreach( $this->scripts as $script )
			$scripts[]	= $script;
		foreach( $this->styles as $style )
			$styles[]	= $style;
		return [$scripts, $styles];
	}

	/**
	 *	Prints a minimal page for a single example, without header, navigation and version form.
	 *	Used for frames of compare demo.
	 *	@param		string		$content		HTML of example
	 *	@return		void
	 */
	public function runFrame( string $content ): void
	{
		[$scripts, $styles]	= $this->getAssets();
		$page	= new HtmlPage();
		$page->addBody( HtmlTag::create( 'div', $content, ['class' => 'demo-frame'] ) );
		$page->setTitle( $this->title );
		foreach( $scripts as $url ) $page->addJavaScript( $url );
		foreach( $styles as $url ) $page->addStylesheet( $url );
		print( $page->build( ['class' => 'bs-'.$this->bsVersion] ) );
	}

	public function run(): void
	{
		$body	= HtmlTag::create( 'div', [
			HtmlTag::create( 'div', [
				HtmlTag::create( 'h1', '<span class="muted text-muted">CeusMedia Bootstrap</span> '.$this->title, ['class' => 'display-5'] ),
			], ['class' => 'not-bs2-hero-unit not-bs4-jumbotron'] ),
			$this->renderDemoNavigation(),
			$this->renderVersionForm(),
			'<hr/>',
			HtmlTag::create( 'form', $this->content, ['id' => 'test-form'] ),
		], ['class' => 'container'] );

		[$scripts, $styles]	= $this->getAssets();

		/*  --  OUTPUT  --  */
		$page	= new HtmlPage();
		$page->addBody( VersionProcessor::process( trim( $body ), (string) $this->bsVersion ) );
		$page->setTitle( $this->title.' – Bootstrap – Ceus Media' );
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

	/**
	 *	Renders links to all demos, keeping selected versions.
	 *	@return		string
	 */
	protected function renderDemoNavigation(): string
	{
		$demos	= [
			'index.php'			=> 'Overview',
			'components.php'	=> 'Components',
			'compare.php'		=> 'Compare versions',
			'modal.php'			=> 'Modal',
			'checkbox.php'		=> 'Checkbox',
			'shiftbox.php'		=> 'Shiftbox',
			'radiobar.php'		=> 'RadioBar',
		];
		$list	= [];
		foreach( $demos as $file => $label ){
			$isCurrent	= basename( $this->url ) === $file;
			$link		= HtmlTag::create( 'a', $label, [
				'href'	=> $this->getDemoUrl( $file ),
				'class'	=> 'bs4-nav-link bs5-nav-link'.( $isCurrent ? ' bs4-active bs5-active' : '' ),
			] );
			$list[]		= HtmlTag::create( 'li', $link, ['class' => 'bs4-nav-item bs5-nav-item'.( $isCurrent ? ' bs2-active bs3-active' : '' )] );
		}
		return HtmlTag::create( 'ul', $list, ['class' => 'nav nav-pills'] );
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
		$bsVersion	= $request->get( 'bootstrap' );
		if( is_numeric( $bsVersion ) && in_array( (int) $bsVersion, static::$versionsBootstrap, TRUE ) )
			$this->bsVersion	= (int) $bsVersion;
		$faVersion	= $request->get( 'fontawesome' );
		if( is_numeric( $faVersion ) && in_array( (int) $faVersion, static::$versionsFontAwesome, TRUE ) )
			$this->faVersion	= (int) $faVersion;
		CeusMedia\Bootstrap\Base\Abstraction::setDefaultBsVersion( (string) $this->bsVersion );	//  used by all components

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
