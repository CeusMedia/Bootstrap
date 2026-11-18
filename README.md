# Bootstrap

![Branch](https://img.shields.io/badge/Branch-0.7.x-blue?style=flat-square)
![Release](https://img.shields.io/badge/Release-0.7.0-blue?style=flat-square)
![PHP version](https://img.shields.io/badge/PHP-%5E8.3-blue?style=flat-square&color=777BB4)
![PHPStan level](https://img.shields.io/badge/PHPStan_level-9+strict-darkgreen?style=flat-square)
[![Total downloads](https://img.shields.io/packagist/dt/ceus-media/bootstrap.svg?style=flat-square)](https://packagist.org/packages/ceus-media/bootstrap)
[![Package version](https://img.shields.io/packagist/v/ceus-media/bootstrap.svg?style=flat-square)](https://packagist.org/packages/ceus-media/bootstrap)
[![License](https://img.shields.io/packagist/l/ceus-media/bootstrap.svg?style=flat-square)](https://packagist.org/packages/ceus-media/bootstrap)

PHP components to generate HTML for **Bootstrap 2, 3, 4 and 5**.

The same code renders the markup of the selected Bootstrap version:
class names, data attributes and structure are translated for each version.

```php
use CeusMedia\Bootstrap\Base\Abstraction;
use CeusMedia\Bootstrap\Button;

Abstraction::setDefaultBsVersion( '5.3.3' );

echo new Button( 'Save', Button::STATE_PRIMARY, 'check' );
//  Bootstrap 5: <button type="button" class="btn btn-primary">...</button>
```

The library renders HTML only. Load the CSS and JavaScript of Bootstrap (and jQuery for Bootstrap 2 to 4)
and of the icon set in your page, as usual.

See [CHANGELOG.md](CHANGELOG.md) for changes. When upgrading from 0.6, check the changes marked with **BC**.

## Installation
The branch 0.7.x runs on PHP ^8.3 and uses CeusMedia::Common 1.1 (using namespaces).
For PHP <8.3, use branch 0.6.x.

```
composer require ceus-media/bootstrap:^0.7.x
```
or checkout the repository and switch to the branch
```
git clone git@github.com:CeusMedia/Bootstrap.git
git checkout 0.7.x
```

## Bootstrap versions

### Selecting a version
The default version is **2.3.2**, for backwards compatibility. Set the version for all components once:

```php
use CeusMedia\Bootstrap\Base\Abstraction;

Abstraction::setDefaultBsVersion( '5.3.3' );  //  only the major version is relevant
Abstraction::getDefaultBsVersion();           //  BsVersion::V5
```

Single components can render for another version:

```php
use CeusMedia\Bootstrap\Base\BsVersion;

$button->setBsVersion( '4.6.2' );
$button->setBsVersion( BsVersion::V4 );
$button->getBsVersion();                      //  BsVersion::V4
```

Components rendering other components (like dropdowns, modals or rows) pass their version on.
Unsupported versions throw a `DomainException`, when set or (if the static property was set directly) when creating a component.

### Content and escaping
Contents and labels of components are taken as HTML, so components and markup can be nested.
**Escape user input yourself**, for example by `htmlspecialchars()`.
Attribute values (like URLs, titles, data attributes) are escaped by the library.
Exception: `Code` shows its content as source code, so it is escaped by the component.

```php
echo new Badge( htmlspecialchars( $userName ) );
```

### Errors on rendering
Echoing a component (string conversion) never ends the application:
if rendering fails, a warning (`E_USER_WARNING`) is triggered and an empty string is returned.
Call `render()` directly to get the exception instead.

### Class names of any version
Constants and class names of any version can be used and are translated,
like `btn-mini` (2), `btn-xs` (3) and `btn-sm` (4+), `alert-error` (2) and `alert-danger` (3+)
or `btn-inverse` (2) and `btn-dark` (4+).
Where a version lacks a feature, the closest one is used, for example:

| Feature | Bootstrap 2 | Bootstrap 3 | Bootstrap 4 | Bootstrap 5 |
|---|---|---|---|---|
| Button without state | `btn` | `btn-default` | `btn-secondary` | `btn-secondary` |
| Dark button | `btn-inverse` | `btn-default` | `btn-dark` | `btn-dark` |
| Outline button | filled | filled | `btn-outline-*` | `btn-outline-*` |
| Alert without state | `alert` (warning) | `alert-warning` | `alert-warning` | `alert-warning` |
| Badge colors | yes | colors of labels by style | `badge-*` as pill | `bg-*` as pill |
| Label | `label` | `label` | rendered as badge | rendered as badge |
| Column `RowSpan::setSize( 4 )` | `span4` | `col-md-4` | `col-md-4` | `col-md-4` |
| Breakpoints `xl`, `xxl` | none | fall back to `lg` | `xxl` falls back to `xl` | all |
| Modal sizes / centering | ignored | sizes | sizes, centering | sizes, centering |
| Custom breadcrumb divider | yes | CSS needed | CSS needed | yes |
| Data attributes | `data-toggle` | `data-toggle` | `data-toggle` | `data-bs-toggle` |

### Components

| Component | Notes |
|---|---|
| `Alert` | dismissible |
| `Badge`, `Label` | |
| `Button`, `Button\Link`, `Button\Submit` | states, outline, sizes, block, icon, disabled |
| `Button\Group`, `Button\Toolbar` | same markup in all versions |
| `Checkbox` | custom styled with icon, same markup in all versions, needs `assets/checkbox.css` |
| `Card` | box with header, title, body and footer: `well` (2), `panel` (3), `card` (4+); colors |
| `Code` | scrollable; since Bootstrap 4 with utility classes for background and border, like older versions |
| `Heading` | h1 to h6 with muted prefix (`muted` / `text-muted`) and small subtitle |
| `Dropdown\Menu`, `Dropdown\Trigger` | button or link trigger, dividers, disabled items, right aligned |
| `Icon` | Glyphicons of Bootstrap 2, FontAwesome 4, 5 and 6 (see below) |
| `Link` | |
| `Modal\Dialog`, `Modal\Trigger` | sizes, centering, forms, upload |
| `Nav\Breadcrumbs`, `Nav\NavList`, `Nav\Pills`, `Nav\TabbableNavbar`, `Nav\Tabs` | |
| `Nav\PageControl` | pagination with first, previous, next and last page and current page indicator, sizes |
| `Progress` | colors, stacked, striped, animated |
| `Row`, `RowSpan` | responsive breakpoints `xs` to `xxl` |
| `Shiftbox` | Bootstrap 2 and 3: needs jQuery plugin [bootstrap-switch](https://github.com/nostalgiaz/bootstrap-switch), since Bootstrap 4: built-in switch |

### Writing templates for all versions
Templates should not distinguish Bootstrap versions. Use components for Bootstrap elements
and `HtmlTag` of CeusMedia Common for remaining HTML:

```php
use CeusMedia\Bootstrap\Row;
use CeusMedia\Bootstrap\RowSpan;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

echo Row::create( [
	RowSpan::create( [
		HtmlTag::create( 'label', 'Amount (&euro;)', ['for' => 'input_amount'] ),
		HtmlTag::create( 'input', NULL, ['type' => 'number', 'name' => 'amount', 'id' => 'input_amount'] ),
	] )->setSize( 6 ),
] );
//  Bootstrap 2: row > span6, since Bootstrap 3: row > col-md-6
```

Content of components may be a list of strings and components; contained components render for the same version.
See `examples/migration` for migrated templates of HydrogenModules.

### Responsive columns

```php
use CeusMedia\Bootstrap\Row;
use CeusMedia\Bootstrap\RowSpan;

$span = ( new RowSpan( 'Content' ) )
	->setSize( 12, RowSpan::BREAKPOINT_XS )   //  full width on phones
	->setSize( 6, RowSpan::BREAKPOINT_MD )    //  half width on tablets
	->setSize( 4, RowSpan::BREAKPOINT_XL );   //  third on large screens
echo new Row( [$span, $span, $span] );
//  Bootstrap 5: <div class="row"><div class="col-12 col-md-6 col-xl-4">...
```

Or set all sizes at once (replacing sizes set before):

```php
$span->setSizes( ['xs' => 12, 'md' => 6, 'xl' => 4] );
```

`setSize()` without breakpoint uses `RowSpan::$defaultBreakpoint` (`md`).

### Icons
Select the icon set once:

```php
use CeusMedia\Bootstrap\Icon;

Icon::$defaultSet   = 'fontawesome6';    //  glyphicons, fontawesome4, fontawesome5, fontawesome6
Icon::$defaultStyle = 'solid';           //  FontAwesome 5: solid, regular, light, brand
                                         //  FontAwesome 6: solid, regular, light, thin, brands
```

Short names (like `star`) are resolved for the icon set, complete classes (like `fa-solid fa-star`) are kept.

Icon sets are independent of the Bootstrap version.
Glyphicons are rendered in the naming of Bootstrap 2 (`icon-star`).
Glyphicons of Bootstrap 3 (`glyphicon glyphicon-star`) are not supported; Bootstrap 4 and 5 bring no icons at all,
so use FontAwesome with these versions.

### Bootstrap 6
Bootstrap 6 is not supported yet. The library is prepared for it:
version dependent code matches all versions explicitly,
so adding `BsVersion::V6` makes PHPStan report every place needing a decision.

## Demos
The `demo` folder contains demos, which can be switched between Bootstrap and FontAwesome versions:

| Demo | Content |
|---|---|
| `index.php` | overview and quick start |
| `components.php` | all components, each with its source code and generated HTML |
| `compare.php` | each example in Bootstrap 2, 3, 4 and 5 side by side |
| `modal.php` | modals with all options |
| `checkbox.php` | checkbox with icons |
| `shiftbox.php` | switches |
| `radiobar.php` | radio bar with optional contents (demo component) |

Serve the project folder by a web server (the demos load `../vendor` and `../assets`) and open `demo/index.php`.
Helpers of the demos are in `demo/lib`, styles and scripts in `demo/assets`.

## Development

```
composer dev:all                #  syntax, unit tests, PHPStan, Rector
composer dev:test-syntax        #  syntax check of all PHP files
composer dev:phpunit            #  unit tests, without code coverage
composer dev:phpunit-coverage   #  unit tests with code coverage, reports in doc/Test/
composer dev:phpstan            #  PHPStan level 9 with strict rules
composer dev:rector             #  Rector suggestions (dry run)
composer dev:rector-fix         #  apply Rector suggestions
```

Code coverage needs Xdebug or PCOV in the PHP running Composer.
To use another PHP version than the default one, run Composer with it: `php8.5 $(command -v composer) dev:phpunit-coverage`.

## Compatibility
To support code written for version 0.4.x of this library, `compat.php` is loaded on autoload.
It provides the old class names `PageControl`, `Breadcrumbs`, `NavList` and `TabbableNavbar`
in namespace `CeusMedia\Bootstrap`.

## License
GPL-3.0-or-later, see [GNU General Public License](https://www.gnu.org/licenses/gpl-3.0.txt).
