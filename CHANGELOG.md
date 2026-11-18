# Changelog

All notable changes of this library are documented in this file.
The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

Entries before 0.7.0 are reconstructed from git tags and commit messages.

## [0.7.0] - 2026-11-18

Requires PHP ^8.3 and CeusMedia/Common 1.1.

**Components render markup for Bootstrap 2, 3, 4 and 5.** Select the version once by
`Abstraction::setDefaultBsVersion()`; class names, data attributes and structure are translated.
Changes marked with **BC** may need changes in code using this library.

### Added
- Enum `Base\BsVersion` (V2 to V5) for supported Bootstrap versions.
- `Abstraction::setDefaultBsVersion()` and `getDefaultBsVersion()`, validating the version.
- `getBsVersion()` on all components; `setBsVersion()` accepts enum or string and validates.
- Dialects `Base\Dialect\V2` to `V5`, translating class names and data attributes of Bootstrap 5 to each version
  (like `data-bs-toggle` / `data-toggle`, `dropdown-menu-end` / `pull-right`).
- `Base\ButtonClassResolver`, translating button states, outline, sizes and block of any version.
- `Base\AlertClassResolver`, translating alert states and dismissible classes of any version.
- `Base\StateClassParser`, reading colors of alerts, badges, labels and cards from class names of any version.
- `RowSpan`: responsive breakpoints `xs` to `xxl` (`BREAKPOINT_*`), `setSize( $size, $breakpoint )`,
  `setSizes()`, `getSizes()` and static `$defaultBreakpoint` (`md`).
- Component `Card`: box with header, title, body and footer, rendered as well (Bootstrap 2), panel (3) or card (4+), with colors.
- Component `Heading` (h1 to h6) with muted prefix and small subtitle.
- `Shiftbox::setLabel()`.
- Content of components may be a list of strings and components; contained components render for the same version.
- ID set by `setId()` is rendered by `Alert`, `Badge`, `Code`, `Label`, `Link`, `Row` and `RowSpan`.
- Data attributes, events and aria attributes set by `setData()`, `setEvent()` and `setAria()` are rendered
  by all components offering these methods, like `Dropdown\Menu` (before, several components ignored them).
- Demos: overview page `index.php`, navigation between demos, version selection kept for all demos,
  `modal.php` (all modal options), `components.php` (all components with source code and generated HTML),
  `compare.php` (each example in all Bootstrap versions side by side, dropdown menus and modals open on click, frames grow to show them),
  helper `DemoExample`.
- Style sheet for custom checkboxes in `assets/checkbox.css` (moved from demo folder).
- Examples for migrating templates of HydrogenModules in `examples/migration`.
- Changelog.

### Changed
Markup per Bootstrap version:
- `Alert`: dismiss button per version (`btn-close` in Bootstrap 5); **BC** alert without state is a warning
  in all versions (class `alert-warning` since Bootstrap 3), states missing in a version are shown as info.
- `Badge`: colors per version (`badge-*` in Bootstrap 4, `bg-*` in Bootstrap 5, colors of labels by style in Bootstrap 3);
  **BC** rendered as pill since Bootstrap 4, default color secondary.
- `Label`: **BC** rendered as badge since Bootstrap 4; `label-default` in Bootstrap 3.
- `Button`, `Button\Link`, `Modal\Trigger`: **BC** buttons without state get the default variant
  (`btn-default` in Bootstrap 3, `btn-secondary` since Bootstrap 4); sizes and block translated
  (`btn-mini` / `btn-xs` / `btn-sm`, `btn-block` / `w-100`).
- `Dropdown\Menu`: list items (Bootstrap 2, 3, 5) or links (Bootstrap 4), dividers and alignment per version.
- `Dropdown\Trigger`: caret element in Bootstrap 2 and 3 only; without caret, class `dropdown-toggle`
  is omitted since Bootstrap 4, since it draws the caret.
- `Modal\Dialog`: dialog and content containers since Bootstrap 3, header layout and close button per version,
  no class `hide` since Bootstrap 3, `aria-labelledby` refers to the heading.
- `Modal\Trigger`: **BC** extends `Structure`.
- `Nav\Breadcrumbs`: `ul` with dividers (Bootstrap 2), `ol` (since 3), `breadcrumb-item` and aria (since 4),
  custom divider by CSS variable (Bootstrap 5).
- `Nav\NavList`: stacked pills since Bootstrap 3.
- `Nav\PageControl`: **BC** rendered as pagination of each version instead of a button group
  (`div.pagination` in Bootstrap 2, `ul.pagination` since 3, `page-item` / `page-link` since 4),
  current page as active item, sizes `pagination-mini`, `-small`, `-large` (Bootstrap 2) or `-sm`, `-lg` (since 3).
- `Nav\Pills`: list instead of div, active link since Bootstrap 4, stacked per version.
- `Nav\TabbableNavbar`: navbar per version (collapse, brand, fixed position); **BC** `setActive()` takes the
  position of the tab, starting with 0.
- `Nav\Tabs`: active and disabled on link since Bootstrap 4, aria attributes.
- `Progress`: bar colors, stripes and animation per version.
- `Row`, `RowSpan`: `row-fluid` / `span*` (Bootstrap 2), `row` / `col-*` (since 3).
- `Code`: utility classes for background and border since Bootstrap 4, scrollable by style since Bootstrap 4.
- `Shiftbox`: built-in switch since Bootstrap 4 (plugin bootstrap-switch is used up to Bootstrap 3);
  with label in structure of Bootstrap 3 (`div.checkbox > label`).
- `Link`: disabled plain links are muted (`muted` / `text-muted`) and get `aria-disabled`;
  links styled by Bootstrap (like `btn`, `nav-link`) keep their own disabled style.
- `Checkbox`: same markup in all versions, icon by `Icon` component (short names resolved),
  icon container for font and SVG icons, wrapper class `cr-checkbox`; updated style sheet, now `assets/checkbox.css`,
  **BC** its rules apply to classes `cr-checkbox` and `cr-radio` only (before: all `.checkbox` and `.radio`).

Other changes:
- **BC** String conversion of components never ends the application: on failure, a warning is triggered and an
  empty string is returned (`Abstraction::__toString()`). Before, several components printed the message and exited.
- **BC** Creating a component throws a `DomainException`, if the default version (set directly) is not supported.
- **BC** Typed class constants.
- **BC** Data object `Breadcrumb`: property `icon` is `Icon|string|null`.
- Explicit nullable parameter types (implicit nullable types are deprecated since PHP 8.4).
- `Icon`: FontAwesome 5 renders prefix `fa` before style prefix (`fa fas`, `fa far`, `fa fal`).
- `Modal`: setters return `static`; dialog and trigger implement `Renderable` and `Stringable`.
- Static `create()` and interface `Renderable` moved to `Abstraction`, so all components have them;
  `Structure` is an empty base class for components without fixed constructor, `Element` for components with content and class.
- README: version support, templates for all versions, responsive columns, icons, demos, development.

### Fixed
- Events set by `setEvent()` were escaped by slashes, breaking JavaScript with quotes (like `alert("hi")`).
- `Button\Link`: title showed slashes before quotes; click events replaced the confirmation.
- `Link`, `Button\Link`: disabled links still ran click events; now kept as `data-attr-onclick`, like URL.
- `Structure::__toString()` threw a `ValueError` (invalid error level) instead of reporting the failure.
- `Icon`: complete FontAwesome 5 and 6 classes were prefixed again (like `fa-fa-user`); sizes were dropped for complete classes.
- `Link`: disabled link lost its URL on first rendering, rendering changed its state, class attribute had a leading space.
- `Button\Link`, `Modal\Trigger`: class attribute had a trailing space.
- `Nav\TabbableNavbar`: `setActive()` never matched a tab.
- `Nav\Tabs`: rendering fixed the active tab and failed, if the first tab was disabled; error messages.
- `Nav\Breadcrumbs`: custom divider was ignored in Bootstrap 2; icon objects were rendered twice; classes were not rendered.
- `Nav\Pills`: `setStacked()` had no effect.
- `Nav\NavList`, `Nav\PageControl`: unstyled or invisible since Bootstrap 3.
- `Progress`: all bars were blue in Bootstrap 3.
- `Modal\Dialog`: fixed label ID `myModalLabel`; dialog classes were lost in Bootstrap 2; position of close button in header.
- `Row`: classes were not rendered; content given as list was lost (also in `RowSpan`).
- Empty `class` attributes are not rendered anymore.
- Composer script descriptions.

### Removed
- Demos `1_standard.php` (replaced by `components.php`) and outdated `old_index.php`; demos renamed without numbers,
  helpers moved to `demo/lib`, styles and scripts to `demo/assets`.
- `Dropdown\Menu`: rendering of nested dropdown items (`addDropdown()` was already disabled and is deprecated).
- `__toString()` overrides printing exception messages and exiting (see Changed).

### Development
- PHPStan 2 (level 9 with strict rules), Rector 2, PHPUnit 10; prefer stable packages.
- Composer scripts `dev:phpunit` (without coverage) and `dev:phpunit-coverage`, using the PHP running Composer.
- Unit tests for all components in all supported Bootstrap versions (19 to 314 tests, line coverage 99 %).

## [0.6.3] - 2025-02-15
### Changed
- Strict types in all classes and demos, PHPStan level max with strict rules.
- Element creation returns `static` instead of `self`.
- Anonymous objects replaced by data structures or component instances.
- Modal: setters return `static`, interfaces implemented, unit tests added.
### Fixed
- Modal dialog: position of close button in header.
### Added
- Demo for NavList, Bootstrap 5.3.3 in demo.

## [0.6.2] - 2024-10-09
### Changed
- Common properties and methods of base classes extracted to `Abstraction`.
- Short array syntax; updated PHPStan, Rector and PHPUnit configuration.

## [0.6.1] - 2024-07-16
### Fixed
- Recursion in content aware trait.
### Added
- Configuration for PHPUnit 10, badges in README.

## [0.6.0] - 2023-06-03
### Changed
- Requires PHP ^8.1; uses `Stringable` interface.
- Supports CeusMedia/Common 0.9 (namespaces); composer scripts and organized tool configuration.
- PHPStan level max.
### Added
- Data object classes for navigations.

## [0.5.5] - 2024-01-14
### Fixed
- Broken link button tags.

## [0.5.4] - 2023-06-03
### Changed
- Support for PHP 8 and CeusMedia/Common 0.9.

## [0.5.3] - 2022-05-22
### Changed
- Supports CeusMedia/Common 0.9 with namespaces; improved modals; PHPStan level 4.
### Added
- Abstract legacy base component class.

## [0.5.2] - 2021-01-30
### Added
- Components `Row` and `RowSpan` (column).
### Changed
- Requires PHP 7, supports PHP 8.

## [0.5.1] - 2020-05-26
### Fixed
- Bug fix.

## [0.5.0] - 2020-04-19
### Changed
- Updated button, dropdown, modal, tab and pills classes; demo split into parts; compatibility file for older code.

## [0.4.8] - 2019-02-08
### Added
- Modal: switches for header and footer.
- Versioning of components, as first step.

## [0.4.7] - 2018-07-14
### Added
- FontAwesome 5 support, static `create()`, `setIcon()`, `removeClass()`, chainable setters.
- Modal: upload forms and JavaScript submit handling.

## [0.4.6] - 2018-04-12
### Added
- Chainable setters.

## [0.4.5] - 2018-02-06
### Added
- Modal: JavaScript handling for forms.

## [0.4.4] - 2018-01-19
### Changed
- Improved modal and modal trigger.

## [0.4.3] - 2018-01-05
### Added
- Component `Shiftbox`, updated `Checkbox`; `Code` replaces tabs by whitespace.

## [0.4.2] - 2017-11-06
### Changed
- Tab behaviour.

## [0.4.1] - 2017-06-20
- Re-tag of 0.4.

## [0.4] - 2017-06-20
### Added
- Modal components, legacy classes, tab toggle.
### Changed
- Button components moved into folder `Button`.

## [0.3] - 2016-05-27
### Added
- Icon sets for icon renderer.
### Changed
- Tabs adjusted to Bootstrap 2.3.2; class name prefix removed; requires CeusMedia/Common 0.8.2.

## [0.2] - 2015-06-05
### Changed
- All classes use namespaces, PSR-4 autoloading; demo added.

## [0.1] - 2015-06-04
- First tagged version.

[0.7.0]: https://github.com/CeusMedia/Bootstrap/compare/0.6.3...0.7.0
[0.6.3]: https://github.com/CeusMedia/Bootstrap/compare/0.6.2...0.6.3
[0.6.2]: https://github.com/CeusMedia/Bootstrap/compare/0.6.1...0.6.2
[0.6.1]: https://github.com/CeusMedia/Bootstrap/compare/0.6.0...0.6.1
[0.6.0]: https://github.com/CeusMedia/Bootstrap/compare/0.5.3...0.6.0
[0.5.5]: https://github.com/CeusMedia/Bootstrap/compare/0.5.4...0.5.5
[0.5.4]: https://github.com/CeusMedia/Bootstrap/compare/0.5.3...0.5.4
[0.5.3]: https://github.com/CeusMedia/Bootstrap/compare/0.5.2...0.5.3
[0.5.2]: https://github.com/CeusMedia/Bootstrap/compare/0.5.1...0.5.2
[0.5.1]: https://github.com/CeusMedia/Bootstrap/compare/0.5.0...0.5.1
[0.5.0]: https://github.com/CeusMedia/Bootstrap/compare/0.4.8...0.5.0
[0.4.8]: https://github.com/CeusMedia/Bootstrap/compare/0.4.7...0.4.8
[0.4.7]: https://github.com/CeusMedia/Bootstrap/compare/0.4.6...0.4.7
[0.4.6]: https://github.com/CeusMedia/Bootstrap/compare/0.4.5...0.4.6
[0.4.5]: https://github.com/CeusMedia/Bootstrap/compare/0.4.4...0.4.5
[0.4.4]: https://github.com/CeusMedia/Bootstrap/compare/0.4.3...0.4.4
[0.4.3]: https://github.com/CeusMedia/Bootstrap/compare/0.4.2...0.4.3
[0.4.2]: https://github.com/CeusMedia/Bootstrap/compare/0.4.1...0.4.2
[0.4.1]: https://github.com/CeusMedia/Bootstrap/compare/0.4...0.4.1
[0.4]: https://github.com/CeusMedia/Bootstrap/compare/0.3...0.4
[0.3]: https://github.com/CeusMedia/Bootstrap/compare/0.2...0.3
[0.2]: https://github.com/CeusMedia/Bootstrap/compare/0.1...0.2
[0.1]: https://github.com/CeusMedia/Bootstrap/releases/tag/0.1
