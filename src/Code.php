<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\Element;

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Code extends Element
{
	protected $convertTabsToWhitespace	= TRUE;
	protected $scrollable				= FALSE;

	static public $tabSize				= 4;

	public function __construct( $content, $scrollable = FALSE, $class = NULL, $convertTabsToWhitespace = TRUE )
	{
		parent::__construct( $content, $class );
		$this->setScrollable( $scrollable );
		$this->convertTabsToWhitespace	= $convertTabsToWhitespace;
	}

	protected function convertTabsToWhitespace( $content ): string
	{
		$lines	= array();
		foreach( explode( "\n", $content ) as $line ){
			$line	= trim( $line, "\r" );
			while( substr_count( $line, "\t" ) ){
				$pos	= strpos( $line, "\t" );
				$indent	= static::$tabSize - ( $pos % static::$tabSize );
				$subst	= str_repeat( " ", $indent );
//				$line	= substr( $line, 0, $pos ).$subst.substr( $line, $pos + 1 );
				$line	= preg_replace( "/\t/", $subst, $line, 1 );
			}
			$lines[]	= $line;
		}
		return join( "\n", $lines );
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
		$content	= $this->content;
		if( $this->convertTabsToWhitespace )
			$content	= $this->convertTabsToWhitespace( $this->content );
		return HtmlTag::create( 'pre', htmlentities( $content, ENT_QUOTES, 'UTF-8' ), $attributes );
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setScrollable( $scrollable ): self
	{
		$this->scrollable	= (bool) $scrollable;
		return $this;
	}
}
