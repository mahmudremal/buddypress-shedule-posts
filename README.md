# [WordPress Plugin - BuddyPress Shedule Posts](https://futurewordpress.com/wordpress/) 🎨
[![Project Status: Active.](https://www.repostatus.org/badges/latest/active.svg)](https://www.repostatus.org/#active) [![code style: prettier](https://img.shields.io/badge/code_style-prettier-ff69b4.svg?style=flat-square)](https://github.com/prettier/prettier)

* A WordPress Plugin Project for Shedule posts on BuddyPress platform.
This theme uses Bootstrap build package and JQuery package to build an BuddyPress Shedule Posts from scratch.


## Features

- ![](demo/Job%20Opening%20Job%20Lists.PNG)
- ![](demo/Job%20Opening%20Archive%20page.PNG)
- ![](demo/Job%20Opening%20Post%20New.PNG)

- Shedule Posts on Activity Input.
- Shedule Posts on Admin Posts.

## Maintainer

| Name                                                   | Github Username | Fiverr Username |
|--------------------------------------------------------|-----------------|-----------------|
| [Remal Mahmud](mailto:info@futurewordpress.com)        |  @mahmudremal   |  [@mahmud_remal](https://www.fiverr.com/mahmud_remal)|

## Usage

1. Clone the WordPress Plugin [AJO](https://github.com/mahmudremal/buddypress-shedule-posts) in your WordPress
Plugin directory and activate it.

## Dashboard Setup.

1. Setup plugin from Settings > Shedule posts and setup what you actually need with language:
- ![](demo/Job%20Opening%20Setup%20Page.PNG)
2. Flush WordPress permalink by Settings > Permalinks and then clicking SAVE Changes button:
- ![](demo/Job%20Opening%20Permalink%20Flush.PNG)
## Development ( To be added )

**Install**

Clone the repo and run

```bash
cd buddypress-shedule-posts/assets
npm install
```

**During development**

```bash
npm run dev
```

Run precommit from assets directory before pushing the code for development/contribution.

```
cd assets && npm run precommit
```

**Production**

```bash
npm run prod
```

**Linting & Formatting**

The following command will fix most errors and show and remaining ones which cannot be fixed automatically.

```bash
npm run lint:fix
```

We follow the stylelint configuration used in WordPress Gutenberg, run the following command to lint and fix styles.

```bash
npm run stylelint:fix
```

Format code with prettier ( TO BE ADDED )

```bash
npm run format-js
```

Directory Structure

```php
BSP:
│   .gitignore
│   buddypress-shedule-posts.php
│   README.md
│   
├───assets
│   │   .babelrc
│   │   .eslintignore
│   │   .eslintrc.json
│   │   .nvmrc
│   │   .stylelintrc.json
│   │   hash.js
│   │   package-lock.json
│   │   package.json
│   │   postcss.config.js
│   │   tailwind.config.js
│   │   webpack.config.js
│   │   
│   ├───build
│   └───src
│       ├───icons
│       ├───img
│       ├───js
│       │   │   main.js
│       │   │   
│       │   └───posts
│       │           loadmore-single.js
│       │           loadmore.js
│       │           main.js
│       │           
│       ├───library
│       │   ├───css
│       │   │   │   ace-responsive-menu.css
│       │   │   │   admin.css
│       │   │   │   animate.css
│       │   │   │   bootstrap-grid.css
│       │   │   │   bootstrap-grid.min.css
│       │   │   │   bootstrap-select.min.css
│       │   │   │   bootstrap.min.css
│       │   │   │   fancyBox.css
│       │   │   │   flaticon.css
│       │   │   │   frontend-base.css
│       │   │   │   invoice.css
│       │   │   │   jquery-ui.min.css
│       │   │   │   menu.css
│       │   │   │   owl.css
│       │   │   │   progressbar.css
│       │   │   │   simplebar.min.css
│       │   │   │   slick-theme.css
│       │   │   │   slick.css
│       │   │   │   slider.css
│       │   │   │   timecounter.css
│       │   │   │   
│       │   │   └───map-css
│       │   │           info-box.css
│       │   │           maps.css
│       │   │           searcher.css
│       │   │           
│       │   ├───fonts
│       │   └───js
│       │           bootstrap-select.min.js
│       │           bootstrap.min.js
│       │           jquery.counterup.js
│       │           progressbar.js
│       │           simplebar.js
│       │           timepicker.js
│       │           
│       └───sass
│           │   main.scss
│           │   
│           ├───0-settings
│           │       _background.scss
│           │       _colors.scss
│           │       _margin.scss
│           │       _settings.scss
│           │       _typography.scss
│           │       _variables.scss
│           │       _z-index.scss
│           │       
│           ├───1-tools
│           │       _functions.scss
│           │       _mixins.scss
│           │       _placeholders.scss
│           │       _tools.scss
│           │       
│           ├───2-generic
│           │       _buttons.scss
│           │       _common-classes.scss
│           │       _editor-color-classes.scss
│           │       _elements.scss
│           │       _generic.scss
│           │       _gutenberg.scss
│           │       _icons.scss
│           │       _normalize.scss
│           │       _search-results.scss
│           │       _slick-carousel.scss
│           │       _wp-css.scss
│           │       
│           └───3-utilities
│                   _animations.scss
│                   
├───demo
├───inc
│   ├───classes
│   │   │   class-archive-settings.php
│   │   │   class-assets.php
│   │   │   class-blocks.php
│   │   │   class-dashboard.php
│   │   │   class-database.php
│   │   │   class-hooks.php
│   │   │   class-invoices.php
│   │   │   class-loadmore-posts.php
│   │   │   class-loadmore-single.php
│   │   │   class-menus.php
│   │   │   class-meta-boxes.php
│   │   │   class-option.php
│   │   │   class-post-types.php
│   │   │   class-project.php
│   │   │   class-requests.php
│   │   │   class-shortcodes.php
│   │   │   class-sidebars.php
│   │   │   class-taxonomies.php
│   │   │   class-update.php
│   │   │   class-video.php
│   │   │   class-widgets.php
│   │   │   class-zip.php
│   │   │   
│   │   └───loader
│   │           class-metabox.php
│   │           class-option.php
│   │           
│   ├───helpers
│   │       autoloader.php
│   │       template-tags.php
│   │       
│   └───traits
│           trait-singleton.php
│           
├───languages
│       README.md
│       
└───template-parts
    │   apply.php
    │   
    ├───company
    │       archive.php
    │       single.php
    │       
    ├───dashboard
    │   │   dashboard.php
    │   │   
    │   ├───candidate
    │   │       agenda.php
    │   │       apply.php
    │   │       cvmanager.php
    │   │       favourite.php
    │   │       home.php
    │   │       invoice.php
    │   │       
    │   └───company
    │           application.php
    │           home.php
    │           managejobs.php
    │           post.php
    │           profile.php
    │           resumes.php
    │           
    └───jobs
            apply.php
            archive.php
            dashboard.php
            list.php
            single.php
```

### Fixing Errors

1. Error: Node Sass does not yet support your current environment
Solution : 
```shell
cd assets
npm rebuild node-sass
```
