<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

namespace radiobar;

use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Icon;
use CeusMedia\Common\Renderable;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	Horizontal bar of radio buttons, with optional contents shown for selected option (by FormOptionals.js).
 *	Bootstrap 2 has no list groups, so the bar is styled by radiobar.css.
 *	Bootstrap 3 has no horizontal list groups, so radiobar.css lays out the list group horizontally.
 *	Bootstrap 4 (since 4.4) and 5 have horizontal list groups and form checks.
 */
class RadioBar extends Structure
{
	public const string ANIMATION_FADE = 'fade';
	public const string ANIMATION_SLIDE = 'slide';

	protected string $name;
	protected string|null $animation = NULL;
	protected string|int|float|null $value = NULL;
	/** @var array<object{value: string|int|float, label: string, icon: Icon|NULL, optional: Renderable|string|NULL}> $options */
	protected array $options = [];

	protected bool $useOptionals = FALSE;

	/**
	 * @param string $name
	 * @param string|int|float|NULL $value
	 * @param bool $useOptionals
	 */
	public function __construct( string $name, string|int|float|null $value = NULL, bool $useOptionals = FALSE )
	{
		parent::__construct();
		$this->name = $name;
		$this->setValue($value);
		$this->useOptionals( $useOptionals );
	}

	public function addOption(string|int|float $value, string $label, Icon|string|null $icon = NULL, Renderable|string|null $optional = NULL): static
	{
		$icon = is_string($icon) ? Icon::create($icon) : $icon;
		$this->options[] = (object)[
			'value' => $value,
			'label' => $label,
			'icon' => $icon,
			'optional' => $optional,
		];
		return $this;
	}

	public function render(): string
	{
		$version = $this->getBsVersion();
		$items = [];
		$optionals = [];
		/** @var object{value: string|int|float, label: string, icon: Icon|NULL, optional: Renderable|string|NULL} $option */
		foreach ($this->options as $option) {
			$id = $this->name . '_' . $option->value;
			$input = HtmlTag::create('input', NULL, [
				'type' => 'radio',
				'name' => $this->name,
				'id' => $id,
				'value' => $option->value,
				'class' => trim(match ($version) {
					BsVersion::V2, BsVersion::V3 => '',
					BsVersion::V4, BsVersion::V5 => 'form-check-input',
				} . ($this->useOptionals ? ' has-optionals' : '')),
				'checked' => (string) $this->value === (string) $option->value ? 'checked' : NULL,
			], [
				'animation' => $this->animation,
			]);
			$text = (NULL !== $option->icon ? $option->icon . ' ' : '') . $option->label;
			$control = match ($version) {
				BsVersion::V2 => HtmlTag::create('label', [$input, $text], ['class' => 'radio']),
				BsVersion::V3 => HtmlTag::create('div', HtmlTag::create('label', [$input, $text]), ['class' => 'radio']),
				BsVersion::V4, BsVersion::V5 => HtmlTag::create('div', [
					$input,
					HtmlTag::create('label', $text, ['class' => 'form-check-label', 'for' => $id]),
				], ['class' => 'form-check']),
			};
			$items[] = HtmlTag::create('li', $control, [
				'class' => BsVersion::V2 === $version ? 'radiobar-item' : 'list-group-item',
			]);
			if ($this->useOptionals && '' !== ($option->optional ?? ''))
				$optionals[] = HtmlTag::create('div', $option->optional, [
					'class' => 'optional ' . $this->name . ' ' . $this->name . '-' . $option->value,
				]);
		}
		$class = match ($version) {
			BsVersion::V2 => 'radiobar',
			BsVersion::V3 => 'radiobar list-group',
			BsVersion::V4, BsVersion::V5 => 'radiobar list-group list-group-horizontal',
		};
		return HtmlTag::create('ul', $items, ['class' => $class]) . join($optionals);
	}

	public function setValue(string|int|float|null $value): static
	{
		$this->value = $value;
		return $this;
	}

	public function useOptionals(bool $bool = TRUE): static
	{
		$this->useOptionals = $bool;
		return $this;
	}

	public function useAnimation(string|null $animation): static
	{
		$this->animation = $animation;
		return $this;
	}
}
