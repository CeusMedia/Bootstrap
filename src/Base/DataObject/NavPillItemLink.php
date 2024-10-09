<?php
declare(strict_types=1);

namespace CeusMedia\Bootstrap\Base\DataObject;

use CeusMedia\Common\Renderable;
use Stringable;

class NavPillItemLink
{
	/** @var string $type */
	public string $type		= 'link';

	/** @var Renderable|Stringable|string $link */
	public Renderable|Stringable|string $link;

	/** @var ?string $class */
	public ?string $class	= NULL;

	public function __construct( Renderable|Stringable|string $link, string|null $class = NULL )
	{
		$this->link		= $link;
		$this->class	= $class ?? 'nav-item';
	}

	public static function create( Renderable|Stringable|string $link, string|null $class = NULL ): self
	{
		return new self( $link, $class );
	}
}