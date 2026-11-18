<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

use CeusMedia\Bootstrap\Base\Abstraction;
use CeusMedia\Bootstrap\Code;
use CeusMedia\Common\Renderable;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	Renders a demo example: title, description, live result and the source code producing it.
 *
 *	The example is written once, as closure returning the HTML to show.
 *	The source code block is read from the closure itself (by reflection),
 *	so result and shown code can never differ.
 *
 *	Rules for closures, to be read correctly:
 *	- the line containing "function" opens the closure and is not shown
 *	- the line containing the closing brace is not shown
 *	- a line "return ...;" at the end is shown as is
 */
class DemoExample
{
	/** @var array<string,string> Map of anchor ID to section title, for table of contents */
	protected static array $sections	= [];

	/** @var list<array{id: string, title: string, description: string, examples: list<array{title: string, closure: Closure, description: string}>}> Registered sections and examples */
	protected static array $registry	= [];

	/**
	 *	Registers a section, to be rendered by renderAll() or used by compare demo.
	 *	@param		string		$title
	 *	@param		string		$description	Optional description, may contain HTML
	 *	@return		void
	 */
	public static function addSection( string $title, string $description = '' ): void
	{
		self::$registry[]	= [
			'id'			=> self::registerSection( $title ),
			'title'			=> $title,
			'description'	=> $description,
			'examples'		=> [],
		];
	}

	/**
	 *	Registers an example in last registered section.
	 *	@param		string		$title			Title of example
	 *	@param		Closure		$example		Closure returning HTML (string, Stringable or Renderable)
	 *	@param		string		$description	Optional description, may contain HTML
	 *	@return		void
	 */
	public static function addExample( string $title, Closure $example, string $description = '' ): void
	{
		if( [] === self::$registry )
			self::addSection( 'Examples' );
		self::$registry[count( self::$registry ) - 1]['examples'][]	= [
			'title'			=> $title,
			'closure'		=> $example,
			'description'	=> $description,
		];
	}

	/**
	 *	Returns registered sections with their examples.
	 *	@return		list<array{id: string, title: string, description: string, examples: list<array{title: string, closure: Closure, description: string}>}>
	 */
	public static function getSections(): array
	{
		return self::$registry;
	}

	/**
	 *	Renders all registered sections and examples.
	 *	@return		string
	 */
	public static function renderAll(): string
	{
		$list	= [];
		foreach( self::$registry as $section ){
			$list[]	= self::renderSectionHeading( $section['id'], $section['title'], $section['description'] );
			foreach( $section['examples'] as $example )
				$list[]	= self::render( $example['title'], $example['closure'], $example['description'] );
		}
		return join( "\n", $list );
	}

	/**
	 *	Returns HTML produced by example.
	 *	@param		Closure		$example		Closure returning HTML (string, Stringable or Renderable)
	 *	@return		string
	 */
	public static function execute( Closure $example ): string
	{
		$result	= $example();
		if( $result instanceof Renderable )
			return $result->render();
		return is_string( $result ) || $result instanceof Stringable ? (string) $result : '';
	}

	/**
	 *	Renders an example: title, description, live result, source code and produced HTML.
	 *	@param		string		$title			Title of example
	 *	@param		Closure		$example		Closure returning HTML (string, Stringable or Renderable)
	 *	@param		string		$description	Optional description, may contain HTML
	 *	@return		string
	 */
	public static function render( string $title, Closure $example, string $description = '' ): string
	{
		$result	= self::execute( $example );
		$html	= [
			HtmlTag::create( 'h4', $title ),
			'' !== $description ? HtmlTag::create( 'p', $description ) : '',
			HtmlTag::create( 'div', $result, ['class' => 'demo-preview'] ),
			( new Code( self::getSource( $example ) ) )->render(),
			HtmlTag::create( 'details', [
				HtmlTag::create( 'summary', 'Generated HTML for Bootstrap '.Abstraction::getDefaultBsVersion()->value ),
				( new Code( self::formatHtml( $result ) ) )->render(),
			], ['class' => 'demo-html'] ),
		];
		return HtmlTag::create( 'div', $html, ['class' => 'demo-example'] );
	}

	/**
	 *	Formats HTML for reading: block elements on own lines, indented by nesting.
	 *	@param		string		$html
	 *	@return		string
	 */
	public static function formatHtml( string $html ): string
	{
		$blocks	= 'div|ul|ol|li|nav|form|table|thead|tbody|tr|th|td|h[1-6]|p|pre|section|label|select|details|summary';
		$tokens	= preg_split( '/(<[^>]+>)/', $html, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );
		$lines	= [];
		$line	= '';
		$depth	= 0;
		foreach( FALSE !== $tokens ? $tokens : [] as $token ){
			$isOpen		= 1 === preg_match( '/^<('.$blocks.')\b[^>]*[^\/]>$/i', $token ) || 1 === preg_match( '/^<('.$blocks.')>$/i', $token );
			$isClose	= 1 === preg_match( '/^<\/('.$blocks.')>$/i', $token );
			$isVoid		= 1 === preg_match( '/^<(hr|br|input)\b/i', $token );
			if( $isOpen || $isClose || $isVoid ){
				if( '' !== trim( $line ) )
					$lines[]	= str_repeat( '  ', $depth ).trim( $line );
				$line	= '';
				if( $isClose )
					$depth	= max( 0, $depth - 1 );
				$lines[]	= str_repeat( '  ', $depth ).$token;
				if( $isOpen )
					$depth++;
			}
			else
				$line	.= $token;
		}
		if( '' !== trim( $line ) )
			$lines[]	= str_repeat( '  ', $depth ).trim( $line );
		return self::joinShortElements( $lines );
	}

	/**
	 *	Joins opening tag, short content and closing tag into one line, like "<li>Item</li>".
	 *	@param		list<string>	$lines
	 *	@return		string
	 */
	protected static function joinShortElements( array $lines ): string
	{
		$result	= [];
		$count	= count( $lines );
		for( $i = 0; $i < $count; $i++ ){
			$open	= trim( $lines[$i] );
			$inner	= trim( $lines[$i + 1] ?? '' );
			$close	= trim( $lines[$i + 2] ?? '' );
			$isPair	= 1 === preg_match( '/^<([a-z0-9]+)(?:\s[^>]*[^\/])?>$/i', $open, $matches )		//  opening tag, not self closing
				&& '</'.$matches[1].'>' === $close;
			if( $isPair && strlen( $open.$inner.$close ) <= 140 ){
				$indent		= substr( $lines[$i], 0, strlen( $lines[$i] ) - strlen( ltrim( $lines[$i] ) ) );
				$result[]	= $indent.$open.$inner.$close;
				$i	+= 2;
				continue;
			}
			$result[]	= $lines[$i];
		}
		return join( "\n", $result );
	}

	/**
	 *	Renders section heading with anchor and registers it for table of contents (for demos rendering immediately).
	 *	@param		string		$title
	 *	@param		string		$description	Optional description, may contain HTML
	 *	@return		string
	 */
	public static function section( string $title, string $description = '' ): string
	{
		return self::renderSectionHeading( self::registerSection( $title ), $title, $description );
	}

	protected static function registerSection( string $title ): string
	{
		$id		= 'section-'.trim( (string) preg_replace( '/[^a-z0-9]+/', '-', strtolower( $title ) ), '-' );
		self::$sections[$id]	= $title;
		return $id;
	}

	protected static function renderSectionHeading( string $id, string $title, string $description ): string
	{
		return HtmlTag::create( 'h2', $title, ['id' => $id, 'class' => 'demo-section'] ).
			( '' !== $description ? HtmlTag::create( 'p', $description ) : '' );
	}

	/**
	 *	Renders table of contents of all sections registered so far.
	 *	@return		string
	 */
	public static function renderTableOfContents(): string
	{
		$list	= [];
		foreach( self::$sections as $id => $title )
			$list[]	= HtmlTag::create( 'li', HtmlTag::create( 'a', $title, ['href' => '#'.$id] ) );
		return HtmlTag::create( 'ul', $list, ['class' => 'demo-toc'] );
	}

	/**
	 *	Reads body of closure from its source file and removes common indentation.
	 *	@param		Closure		$closure
	 *	@return		string
	 */
	protected static function getSource( Closure $closure ): string
	{
		$reflection	= new ReflectionFunction( $closure );
		$fileName	= $reflection->getFileName();
		$start		= $reflection->getStartLine();
		$end		= $reflection->getEndLine();
		if( FALSE === $fileName || FALSE === $start || FALSE === $end )
			return '';
		$lines		= file( $fileName, FILE_IGNORE_NEW_LINES );
		if( FALSE === $lines )
			return '';
		$body		= array_slice( $lines, $start, $end - $start - 1 );				//  without opening and closing line

		$indent		= NULL;
		foreach( $body as $line ){
			if( '' === trim( $line ) )
				continue;
			$length	= strlen( $line ) - strlen( ltrim( $line, "\t" ) );
			$indent	= NULL === $indent ? $length : min( $indent, $length );
		}
		$indent	??= 0;
		$body	= array_map( static fn( string $line ): string => substr( $line, $indent ), $body );
		return trim( join( "\n", $body ), "\n" );
	}
}
