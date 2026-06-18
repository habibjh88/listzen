# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

Listzen is a commercial WordPress directory/listing theme by RadiusTheme, distributed via ThemeForest and updated through radiustheme.com's EDD Software Licensing. It is the **theme only** — it ships with (and depends heavily on) four bundled premium plugins kept as zips in `plugin-bundle/`:

- `listzen-core` — first-party companion plugin
- `classified-listing-pro` / `classified-listing-store` — Classified Listing (RTCL) plugins
- `rtcl-booking` — booking add-on

Most listing pages, single layouts, and form pages assume `rtcl()` (Classified Listing) is active. Template files under `classified-listing/` and many functions guarded by `Fns::is_cl_active()` belong to that integration. Do not assume these are part of the theme's own UI.

## Common commands

```bash
# install JS deps (postinstall runs scripts/patch-yargs.js + scripts/patch-webpackbar.js — they MUST run)
npm install

# dev build (sourcemaps, unminified)
npm run dev               # alias for `mix`
npm run watch             # incremental rebuild

# production build (minified, regenerates languages/listzen.pot, builds bundled listzen-bundle.min.js)
npm run production

# full release workflow: dev -> cleanup -> production
npm run package
npm run zip               # package then archive into build/listzen.<version>.zip

# linting / formatting (WordPress coding standards via @wordpress/scripts)
npm run lint:js
npm run lint:css
npm run format

# PHP linting — phpcs.xml extends ruleset.xml (PSR2 with tabs) and adds WordPress + WordPress-Extra + WordPress-Docs.
# Composer brings in dealerdirect/phpcodesniffer-composer-installer; run phpcs against the repo root.
vendor/bin/phpcs
vendor/bin/phpcs path/to/file.php
```

There is **no test suite**. Verification is manual in the WP admin/front-end.

## Architecture

### Bootstrap
`functions.php` is intentionally tiny — it loads `vendor/autoload.php` (Composer PSR-4 maps `Listzen\` → `./inc`, plus `inc/template-tags.php` as a global helper file) and calls `Listzen\Init::instance()`. **Adding a new feature class means registering it in `inc/Init.php::register()`** — there is no auto-discovery.

Every class uses `Listzen\Traits\SingletonTraits` (single instance, hooks wired in the constructor). Do not instantiate classes with `new`; always use `ClassName::instance()`.

### Namespace map (`inc/`)
- `Init.php` — root bootstrapper, instantiates everything
- `Setup/` — theme supports, menus, asset enqueue (`Enqueue.php` is the central place for front-end + admin scripts/styles)
- `Custom/` — runtime hooks, AJAX endpoints, dynamic inline styles from Customizer, menu meta
- `Core/` — sidebars, custom nav walker
- `Framework/Customize/` — in-house Customizer framework (controls + `FieldManager`); used by `Options/Opt.php` and `Options/Layouts.php` to register settings
- `Options/Opt.php` — loads stored Customizer values into `Opt::$options` (used by the global `listzen_option()` helper)
- `Api/` — Gutenberg block registration, Customizer JSON config
- `Plugins/` — third-party integrations (`ClassifiedListing`, `CLToolKits`, `ThemeJetpack`)
- `Modules/` — reusable rendering pieces (breadcrumb, pagination, post meta, thumbnail, SVG icons, TGM config)
- `Lib/` — vendored libraries (TGM Plugin Activation)
- `Shortcode/` — theme shortcodes
- `Helpers/` — `Constants` (version + EDD config), `Fns` (general utilities), `CLFns` (Classified Listing utilities)
- `Admin/` — admin-only code, only instantiated when `is_admin()`:
  - `LicenseController` — EDD theme license activate/deactivate + dashboard notice; updater is booted on every admin request via `EDDThemeUpdater`
  - `BundlePluginGuard` — blocks install/activation of bundled premium plugins (`listzen-core`, `classified-listing-pro`, `classified-listing-store`, `rtcl-booking`) until the license is active; intercepts both TGMPA and `plugins.php`
- `Traits/SingletonTraits.php` — used by every class above

### Global helpers (procedural, defined in `inc/template-tags.php`)
These are the conventional way to access assets and options from templates — prefer them over hard-coding paths:
- `listzen_get_css($name, $check_rtl=false)` — resolves to `assets/css/$name.min.css` (or `.css` when `WP_DEBUG`), optionally swapping to `assets/css-rtl/` on RTL
- `listzen_get_js($name, $folder='js', $check_minify=false)`
- `listzen_get_assets($filename, $return_path=false)`
- `listzen_option($key, $default='', $return_array=false)` — reads from `Opt::$options`
- `listzen_html($html, $context='', $echo=true)` — context-aware `wp_kses` wrapper (`plain`, `social`, `allow_link`, `allow_title`, `default`)

### Asset pipeline (Laravel Mix / webpack)
`webpack.mix.js` is the single source of truth. Source lives in `src/` and compiles into `assets/`:

- `src/sass/style.scss` → `assets/css/style[.min].css` (front-end)
- `src/sass/admin.scss` → `assets/css/admin[.min].css`
- `src/sass/gutenberg.scss` → `assets/css/gutenberg.css`
- `src/sass/rtl.scss` → `assets/css-rtl/rtl.css`
- `assets/css/style.css` is also passed through `rtlcss` to produce `assets/css-rtl/style[.min].css`
- `src/js/{admin,form,scripts}.js` → `assets/js/{admin,form,scripts}[.min].js`
- In production only, all `assets/library/*.js` plus `src/js/scripts.js` are combined into `assets/js/listzen-bundle.min.js`. The `listzen_script_optimize` Customizer option switches the front-end between the bundle and individual library scripts (see `Setup/Enqueue.php::register_scripts`).
- Production also regenerates `languages/listzen.pot` via `wp-pot` from all PHP files.

`assets/css/`, `assets/css-rtl/`, `build/`, and `mix-manifest.json` are gitignored — they are build artifacts. Never edit compiled CSS/JS in `assets/`; edit the source under `src/` and run the build.

### License + bundle workflow
The theme cannot self-update or install its bundled plugins without an active license:

1. User pastes their key on **Appearance → License** (`LicenseController::render_page` renders `inc/Admin/views/license-panel.php`; JS in `assets/js/license.js`).
2. `LicenseController::manage_license` is the `wp_ajax_listzen_manage_license` endpoint that POSTs `activate_license` / `deactivate_license` to `Constants::EDD_STORE_URL` (radiustheme.com) with `Constants::edd_item_id()` (filterable via `listzen_edd_item_id`, override constant `LISTZEN_EDD_ITEM_ID`).
3. License payload is stored in the `listzen_license_data` option (`Constants::LICENSE_OPTION`).
4. `EDDThemeUpdater` is booted on `admin_init` regardless of license state.
5. `BundlePluginGuard` short-circuits TGMPA install/update + `plugins.php` activate (single, bulk, and TGMPA links), disables the row checkboxes via inline JS, rewrites the Activate row action to "License required", and renders an admin notice on `plugins.php` and the TGMPA install screen.
6. On the WP dashboard, `LicenseController::dashboard_license_notice` renders a branded notice if the license is inactive.

When touching license code, mirror the existing pattern (this implementation is modelled on The Post Grid Pro). Only `manage_options` users may interact with the AJAX endpoint, and nonces use `LicenseController::NONCE_ACTION`.

### Template layout
- Root template files (`index.php`, `single.php`, `archive.php`, etc.) and `template-parts/` follow the standard WP hierarchy.
- `classified-listing/` overrides templates from the Classified Listing plugin (e.g. `content-single-rtcl_listing.php`, `single-layout/`, `listing/`, `global/`).
- `classified-listing/elementor/` holds custom Elementor widget templates.
- `page-templates/template-fullwidth.php` is the only standalone page template.

### Coding standards
- PHP follows `phpcs.xml` (WordPress + WordPress-Extra + WordPress-Docs) layered on top of `ruleset.xml` (PSR2 with tabs). Cyclomatic complexity is capped at 35, nesting at 10. Short arrays are allowed.
- Text domain is **`listzen`** (see `webpack.mix.js` and translation files). Always wrap user-facing strings.
- File-naming and variable-naming WP rules are intentionally excluded — class files use `PascalCase.php` to match PSR-4, methods use `camelCase` or `snake_case` (both appear; match the surrounding file), procedural helpers use `snake_case` with the `listzen_` prefix.

## Companion: `listzen-core` plugin

The `listzen-core` plugin (`wp-content/plugins/listzen-core/`) is first-party and tightly coupled to this theme. The theme expects it to be installed and active for full functionality, and `BundlePluginGuard` ships its zip in `plugin-bundle/listzen-core.zip`. When working on listing UI, Elementor widgets, demo import, or block/Gutenberg features, the relevant code likely lives in the plugin — not the theme.

### Plugin entry + bootstrap
- Entry: `listzen-core.php` defines `LISTZEN_CORE` (version), `LISTZEN_CORE_PREFIX = 'listzen'`, `LISTZEN_CORE_BASE_URL`, `LISTZEN_CORE_BASE_DIR`, `LISTZEN_CORE_BASE_FILE_NAME`.
- Composer PSR-4: `ListzenCore\` → `./app` (note: the plugin uses `app/`, not `inc/`).
- `ListzenCore\Init::instance()` boots the plugin. Most classes are deferred to the **`listzen_theme_init` action** (fired from the theme's `functions.php` after `Listzen\Init::instance()`), so the plugin only fully initializes when the Listzen theme is the active theme. `DemoImportController` is the exception — it runs on `plugins_loaded` priority 17.
- Activation/deactivation hooks wire to `ListzenCore\Helper\Install::activate` / `::deactivate`.

### Plugin namespace map (`app/`)
- `Hooks/` — `CategoryHooks`, `FilterHooks`, `ActionHooks`, `ListingMeta` (runtime hook wiring)
- `Generator/CPTGenerator.php` — registers custom post types
- `Modules/` — `PostAttribute`, `PostQuery`, `WidgetOverwrite`
- `Api/` — REST endpoints (`Api/Rest`), settings APIs (`Api/Settings`), widget APIs (`Api/Widgets`)
- `Controllers/` — bootstrappers for `Script`, `Gutenberg`, `Framework`, `PostMeta`, `CustomCSSMeta`, `Elementor`, `ElmentorBuilder` (sic), `DemoImport`, `Widget`
- `Elementor/` — `Addons/`, `Controls/`, `Custom/`, `Renderers/` (only loaded when `did_action('elementor/loaded')`)
- `Builder/Builder.php` — theme builder integration
- `Blocks/` — Gutenberg blocks (`PostGrid`, `SectionTitle`, `Renderer/`); React source lives in `src/blocks/`, built to `assets/blocks/`
- `Widgets/` — classic WP widgets (`About_Widget`, `Contact_Widget`, `Post_Widget`)
- `ImportExport/` — `Exporter`, `OneClickDemoImport`, `UserImportExport` (drives the `RT_EXPORT_ENABLE` flow described in the theme's `readme.txt`)
- `Framework/` — in-house options framework used by `FrameworkController`
- `Helper/` — `Fns`, `FnsBuilder`, `Install`
- `Traits/SingletonTraits.php` — same singleton pattern as the theme

### Plugin commands
Run from `wp-content/plugins/listzen-core/`:

```bash
npm install                # postinstall patches yargs, webpackbar, AND laravel-mix-size

# Laravel Mix (CSS/JS via webpack.mix.js)
npm run dev / npm run watch / npm run prod

# Gutenberg blocks (separate @wordpress/scripts build, src/blocks/*)
npm run start              # block dev (HMR)
npm run build              # block production build → assets/blocks/

# Release
npm run package            # clean → build (blocks) → dev → prod → dev --package
npm run zip
```

There is a third patch script (`scripts/patch-laravel-mix-size.js`) that the theme doesn't have.

### Plugin sample data
`sample-data/` holds the one-click demo content consumed by `OneClickDemoImport`:
- `contents.xml` — WordPress WXR export
- `customizer.dat`, `widgets.wie` — Customizer + widget snapshots
- `rtcl-options.json`, `rtcl-form.json`, `rtcl-ajax-filter.json` — Classified Listing settings/forms/filter exports
- `users.json`, `usermeta.json`, `listing-info.json` — user + listing payloads

These files are **regenerated by the theme's export utilities** (set `define( 'RT_EXPORT_ENABLE', true )` in `wp-config.php`, then visit `/?listzen_export=yes`, `/?listzen_user=yes`, or `/?ajax_filter_export=yes`). After exporting, copy the files into this `sample-data/` directory and commit.

### Cross-cutting notes (theme ↔ plugin)
- **Different text domains**: theme is `listzen`, plugin is `listzen-core`. Don't mix them.
- **Different namespaces**: `Listzen\` (theme, `inc/`) vs `ListzenCore\` (plugin, `app/`). Both use the same `SingletonTraits` pattern but they are separate trait definitions in separate namespaces.
- **The plugin depends on the theme being active** via the `listzen_theme_init` hook — it will silently do nothing under a different theme (apart from demo import + activation hooks).
- **License gating**: the theme's `BundlePluginGuard` prevents installing/activating `listzen-core` (and the three RTCL bundled plugins) until the theme license is active. When debugging plugin install/activate issues, check `LicenseController::is_license_active()` first.
- **EDD updates** for `listzen-core` are not handled by this theme's `EDDThemeUpdater` (which is theme-only) — look inside the plugin if you need to touch its update flow.

## Things to know before editing

- **Don't edit `assets/css/*` or `assets/js/*.min.js` directly** — they are generated. Edit `src/` and rebuild.
- **Don't add classes without registering them in `inc/Init.php`** — nothing else autoloads them into the request lifecycle (Composer autoloads on access, but hooks won't fire until `::instance()` is called).
- **`Constants::get_version()` returns `time()` when `WP_DEBUG` is on** — that is intentional to bust caches during development; don't "fix" it.
- **Admin-only classes belong under `inc/Admin/` and inside the `is_admin()` branch in `Init.php`** — instantiating them on the front-end will load WP admin functions that aren't available.
- **Bundled plugin slugs are hard-coded in `BundlePluginGuard::BUNDLED_SLUGS`** — keep the zips in `plugin-bundle/`, the slug list, and TGMPA config (`inc/Modules/TgmConfig.php`) in sync when adding/removing a bundle.
- **EDD item id can be overridden** with `define( 'LISTZEN_EDD_ITEM_ID', … )` in `wp-config.php` or via the `listzen_edd_item_id` filter — useful for staging against a different EDD product.
- **Export utilities** (referenced in `readme.txt`) live in the `listzen-core` plugin, not in this theme. They require `define( 'RT_EXPORT_ENABLE', true )` in `wp-config.php` and write to `listzen-core/sample-data/`.
