<br>
<div align="center">
<p>
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="https://github-production-user-asset-6210df.s3.amazonaws.com/51531217/279796754-be3e5050-8bcc-478c-aac8-d3522b9af351.svg">
    <source media="(prefers-color-scheme: light)" srcset="https://user-images.githubusercontent.com/51531217/279796586-11ab7ee1-aff5-4960-99eb-c21c0ccbdec5.svg">
    <img alt="bootScore logo light/dark mode" src="https://user-images.githubusercontent.com/51531217/279796586-11ab7ee1-aff5-4960-99eb-c21c0ccbdec5.svg" height="50">
  </picture>
</p>

  Intuitive Bootstrap WordPress starter-theme and time-saving plugins

  [Theme](https://bootscore.me/theme/) • [Plugins](https://bootscore.me/plugins/) • [Shop](https://bootscore.me/shop/) • [Docs](https://bootscore.me/documentation/) • [Blog](https://bootscore.me/blog/) • [Community](https://github.com/orgs/bootscore/discussions)

<h1></h1>
</div>

<br>
<div align="center">
  <a href="https://vshymanskyy.github.io/StandWithUkraine"><img src="https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/banner2-direct.svg" width="500"></a>
</div>
<br>

# bootScore
bootScore combines the [Underscores](https://underscores.me) theme boiler template with [Bootstrap](https://getbootstrap.com) to create a compact starter theme for excellent WordPress projects. It does not contain customizer settings, logo upload functionality, or drag-and-drop features. bootScore is entirely customizable through its .php, .scss, and .js files, necessitating basic coding skills for editing.

Moreover, bootScore seamlessly integrates with Bootstrap and offers complete [WooCommerce](https://woo.com/) support, featuring an integrated AJAX offcanvas cart.

bootScore is a time-saving tool designed to swiftly create clean, mobile-first projects. If you are familiar with Bootstrap and it's HTML classes, you'll feel right at home and be able to build whatever you want.

[![Packagist Prerelease](https://img.shields.io/packagist/vpre/bootscore/bootscore?logo=packagist&logoColor=fff)](https://packagist.org/packages/bootscore/bootscore)
[![Backers on Open Collective](https://img.shields.io/opencollective/backers/bootscore?logo=opencollective&logoColor=fff)](https://opencollective.com/bootscore)
[![GitHub Sponsors](https://img.shields.io/github/sponsors/bootscore?logo=github)](https://github.com/sponsors/bootscore)

## Table of contents
- [Installation](#installation)
  - [Composer](#composer)
- [Child-theme](#child-theme)
- [Plugins](#plugins)

## Installation
1. Download latest release [bootscore-main.zip](https://github.com/bootscore/bootscore/releases/latest/download/bootscore-main.zip)
2. Upload theme via the WordPress theme-uploader and activate it

### Composer
To install with composer you will need to have composer installed on your system, and ideally on your server also with command line access, but that’s not required.

Here are two methods of installing theme with composer:

#### Method 1 – composer/installers
Add the following to your projects composer.json file (remove comments)

```json
"require": {
  "composer/installers": "^2.1",
  // other existing dependencies if any here
},
"repositories":[ // add the wpackagist repo, packagist is included by default
        {
            "type":"composer",
            "url":"https://wpackagist.org",
            "only": [
                "wpackagist-plugin/*",
                "wpackagist-theme/*"
            ]
        }
    ],
"extra": { //configuration for composer/installers:
  "installer-paths": {
    "wordpress/wp-content/plugins/mu-plugins/{$name}/": ["type:wordpress-muplugin"],
    "wordpress/wp-content/plugins/{$name}/": ["type:wordpress-plugin"],
    "wordpress/wp-content/themes/{$name}/": ["type:wordpress-theme"]
  }
}
```

#### Method 2 – Bedrock
1. Create a new bedrock project with `$ composer create-project roots/bedrock`
2. Configure the .env file – see bedrock docs page @ https://docs.roots.io/bedrock/
3. You can now install themes and packages, in an alternate project structure

## Child-theme
Edit theme in an upgrade-safe way using the provided [bootScore Child](https://github.com/bootscore/bootscore-child).

## Plugins
Extend bootScore with tiny but usefull [plugins](https://bootscore.me/plugins/). Each plugin uses Bootstrap or adds it to 3rd party plugins.

## Support & Contribute

### Discussions
Check the [documentation](https://bootscore.me/category/documentation/). If you have a general question or need help with your project, feel free to open a new [discussion](https://github.com/orgs/bootscore/discussions). Maybe your question is in [help wanted](https://github.com/bootscore/bootscore/issues?q=is%3Aissue+label%3A%22help+wanted%22+) labeled issues already solved.

### Issues
If you find a bug or have a feature idea, you're welcome to open an [issue](https://github.com/bootscore/bootscore/issues). Please check [closed issues](https://github.com/bootscore/bootscore/issues?q=is%3Aissue+is%3Aclosed) first to avoid duplicates.

### Blog & Twitter
For important updates read the [blog](https://bootscore.me/category/blog/) and follow us on [Twitter](https://twitter.com/_bootscore). You will be informed if there is something new you should know.

### Pull requests
We’re happy if you want to contribute. Just fork the repository, do your changes and create a pull request. Every PR will be reviewed. If everything is fine we’ll merge your changes to the `main` branch.

## Sponsor this project


## License & Credits
- bootScore, [MIT License](https://github.com/bootscore/bootscore/blob/main/LICENSE)
- Bootstrap, [MIT License](https://github.com/twbs/bootstrap/blob/main/LICENSE)
- Bootstrap 5 WordPress Navbar Walker by AlexWebLab, [MIT License](https://github.com/AlexWebLab/bootstrap-5-wordpress-navbar-walker/blob/main/LICENSE)
- Font Awesome [Free License](https://fontawesome.com/license/free)
- scssphp by Leaf Corcoran, [MIT License](https://github.com/scssphp/scssphp/blob/master/LICENSE.md)
- Plugin Update Checker by YahnisElsts, [MIT License](https://github.com/YahnisElsts/plugin-update-checker/blob/master/license.txt)
