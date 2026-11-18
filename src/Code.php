<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Base\Element;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use RuntimeException;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Code extends Element
{
	protected bool $convertTabsToWhitespace	= TRUE;
	protected bool $scrollable				= FALSE;

	public static int $tabSize				= 4;

	public function __construct( $content, bool $scrollable = FALSE, $class = NULL, bool $convertTabsToWhitespace = TRUE )
	{
		parent::__construct( $content, $class );
		$this->setScrollable( $scrollable );
		$this->convertTabsToWhitespace	= $convertTabsToWhitespace;
	}

	/**
	 *	Renders preformatted code for set Bootstrap version.
	 *	Bootstrap 2 and 3 style preformatted text with background and border,
	 *	Bootstrap 4 and 5 do not, so utility classes are added to keep this look.
	 *	Scrollable code uses class "pre-scrollable" in Bootstrap 2 and 3,
	 *	which was removed in Bootstrap 4, so the same height limit is set by style there.
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$isLegacy	= match( $this->getBsVersion() ){
			BsVersion::V2, BsVersion::V3	=> TRUE,
			BsVersion::V4, BsVersion::V5	=> FALSE,
		};
		$classes	= $isLegacy ? $this->classes : array_merge( ['bg-light', 'border', 'rounded', 'p-3'], $this->classes );
		$style		= NULL;
		if( $this->scrollable ){
			if( $isLegacy )
				$classes[]	= 'pre-scrollable';
			else
				$style		= 'max-height: 340px; overflow-y: scroll';
		}
		$attributes	= [
			'id'	=> $this->id,
			'class'	=> [] !== $classes ? join( ' ', $classes ) : NULL,
			'style'	=> $style,
		];
		$this->extendAttributesByData( $attributes );
		$this->extendAttributesByEvents( $attributes );
		$content	= $this->getContentAsString();
		if( $this->convertTabsToWhitespace )
			$content	= $this->convertTabsToWhitespace( $content );
		return HtmlTag::create( 'pre', htmlentities( $content, ENT_QUOTES, 'UTF-8' ), $attributes );
	}

	/**
	 *	@access		public
	 *	@param		bool		$scrollable
	 *	@return		static		Own instance for method chaining
	 */
	public function setScrollable( bool $scrollable ): static
	{
		$this->scrollable	= $scrollable;
		return $this;
	}

	/**
	 *	@param		string		$content
	 *	@return		string
	 */
	protected function convertTabsToWhitespace( string $content ): string
	{
		$lines	= [];
		foreach( explode( "\n", $content ) as $line ){
			$line	= trim( $line, "\r" );
			while( FALSE !== ( $pos = strpos( $line, "\t" ) ) ){
				$indent	= static::$tabSize - ( $pos % static::$tabSize );
				$subst	= str_repeat( " ", $indent );
//				$line	= substr( $line, 0, $pos ).$subst.substr( $line, $pos + 1 );
				$new	= preg_replace( "/\t/", $subst, $line, 1 );
				if( NULL === $new )
					throw new RuntimeException( 'Replacement failed' );
				$line	= $new;
			}
			$lines[]	= $line;
		}
		return join( "\n", $lines );
	}
}
