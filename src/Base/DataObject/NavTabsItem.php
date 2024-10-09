<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

namespace CeusMedia\Bootstrap\Base\DataObject;

use CeusMedia\Common\ADT\URL;
use CeusMedia\Common\Renderable;
use Stringable;

class NavTabsItem
{
	public string $id;
	public URL|string $url;
	public Stringable|Renderable|string $label;
	public Stringable|Renderable|string $content;
	public bool $disabled		= FALSE;

	public function __construct(
		string $id,
		URL|string $url,
		Stringable|Renderable|string $label,
		Stringable|Renderable|string $content,
		bool $disabled = FALSE
	)
	{
		$this->id		= $id;
		$this->url		= $url;
		$this->label	= $label;
		$this->content	= $content;
		$this->disabled	= $disabled;
	}

	public static function create(
		string $id,
		URL|string $url,
		Stringable|Renderable|string $label,
		Stringable|Renderable|string $content,
		bool $disabled = FALSE
	): self
	{
		return new self( $id, $url, $label, $content, $disabled );
	}
}
