<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2022 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\Element;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use RuntimeException;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2022 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
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
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$attributes		= array( 'class' => join( " ", $this->classes ) );
		if( $this->scrollable )
			$attributes['class']	.= " pre-scrollable";
		$content	= $this->getContentAsString();
		if( $this->convertTabsToWhitespace )
			$content	= $this->convertTabsToWhitespace( $content );
		return HtmlTag::create( 'pre', htmlentities( $content, ENT_QUOTES, 'UTF-8' ), $attributes );
	}

	/**
	 *	@access		public
	 *	@param		bool		$scrollable
	 *	@return		self		Own instance for method chaining
	 */
	public function setScrollable( bool $scrollable ): self
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
			while( substr_count( $line, "\t" ) ){
				$pos	= strpos( $line, "\t" );
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
