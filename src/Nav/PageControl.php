<?php /** @noinspection PhpUnused */
declare(strict_types=1);

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2024 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Nav;

use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Button;
use CeusMedia\Bootstrap\Button\Group as ButtonGroup;
use CeusMedia\Bootstrap\Button\Link as ButtonLink;
use CeusMedia\Bootstrap\Icon;
use Exception;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2024 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class PageControl extends Structure
{
	public string $baseUrl;
	public int $page;
	public int $pages;
	public string $patternUrl;
	public string $patternIndicator;
	public ?string $size				= NULL;
	public string $fragment;

	public const SIZE_MINI			= 'mini';
	public const SIZE_SMALL			= 'small';
	public const SIZE_DEFAULT		= '';
	public const SIZE_LARGE			= 'large';

	public function __construct( string $baseUrl, int $page, int $pages )
	{
		parent::__construct();
		$this->baseUrl			= $baseUrl;
		$this->page				= abs( $page );
		$this->pages			= abs( $pages );
		$this->patternUrl		= '/%s';
		$this->patternIndicator	= '<b>%s</b> / %s';
		$this->fragment			= '';
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

	protected function getUrl( int $page = 0 ): string
	{
		$fragment	= '' !== $this->fragment ? '#'.$this->fragment : '';
		$part		= sprintf( $this->patternUrl, $page );
		if( 0 === $page && "/%s" === $this->patternUrl )
			$part	= '';
		return $this->baseUrl.$part.$fragment;
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		if( $this->pages <= 1 )
			return '';
		$size	= NULL !== $this->size ? 'btn-'.$this->size : NULL;
		$group	= ButtonGroup::create()->setClass( "page-control" );
		$group->add( new ButtonLink(
			$this->getUrl(),
			'',
			$size,
			new Icon( 'fast-backward' ),
			$this->page === 0,
		) );
		$group->add( new ButtonLink(
			$this->getUrl( $this->page - 1 ),
			'',
			$size,
			new Icon( 'backward' ),
			$this->page === 0,
		) );
		$group->add( new Button(
			sprintf( $this->patternIndicator, $this->page + 1, $this->pages ),
			$size.' page-indicator',
			NULL,
			TRUE,
		) );
		$group->add( new ButtonLink(
			$this->getUrl( $this->page + 1 ),
			'',
			$size,
			new Icon( 'forward' ),
			$this->page === $this->pages - 1,
		) );
		$group->add( new ButtonLink(
			$this->getUrl( $this->pages - 1 ),
			'',
			$size,
			new Icon( 'fast-forward' ),
			$this->page === $this->pages - 1,
		) );
		return $group->render();
	}
}
