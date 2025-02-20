<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace radiobar;

use CeusMedia\Bootstrap\Icon;
use CeusMedia\Common\Renderable;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

class RadioBar
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
	public function __construct( string $name, string|int|float $value = NULL, bool $useOptionals = FALSE )
	{
		$this->name = $name;
		$this->setValue($value);
		$this->useOptionals( $useOptionals );
	}

	public function addOption(string|int|float $value, string $label, Icon|string $icon = NULL, Renderable|string $optional = NULL): static
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
		$items = [];
		$optionals = [];
		/** @var object{value: string|int|float, label: string, icon: Icon|NULL, optional: Renderable|string|NULL} $option */
		foreach ($this->options as $option) {
			$input = HtmlTag::create('input', NULL, [
				'type' => 'radio',
				'name' => $this->name,
				'id' => $this->name . '_' . $option->value,
				'value' => $option->value,
				'class' => 'form-check-input' . ($this->useOptionals ? ' has-optionals' : ''),
				'checked' => $this->value === $option->value ? 'checked' : NULL,
			], [
				'animation' => $this->animation,
			]);
			$icon = NULL !== $option->icon ? $option->icon . ' ' : '';
			$label = HtmlTag::create('label', $icon . $option->label, [
				'class' => 'form-check-label',
				'for' => $this->name . '_' . $option->value,
			]);
			$control = HtmlTag::create('div', [$input, $label], ['class' => 'form-check']);
			$items[] = HtmlTag::create('li', $control, ['class' => 'list-group-item']);
			if ($this->useOptionals && '' !== ($option->optional ?? ''))
				$optionals[] = HtmlTag::create('div', $option->optional, [
					'class' => 'optional ' . $this->name . ' ' . $this->name . '-' . $option->value,
				]);
		}
		return '<ul class="list-group list-group-horizontal">' . join($items) . '</ul><br/>' . join($optionals);
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
