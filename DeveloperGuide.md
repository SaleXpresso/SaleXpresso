# Developer Guide
Guide for WordPress plugin and theme development by SaleXpresso and innovizz DevTeam 

## Getting Started

- Setting up development environment
  - Installing necessary software
  - Install & configure dependencies
  - Minify Assets (Scss, JS, Images)
- Git Flow
- Code Standard
- Localisation guidelines

### Setting up development environment
This section will guide you through the process of setting up WooCommerce development environment on your local machine.
It should work on Linux, macOS, or Windows 10 with the Linux Subsystem.

#### Pre-requisites

Before starting, make sure you have the following software installed and working on your machine:

1. Git: To clone and commit things. You will need to use git flow too. So make sure you have that (windows user gets this by default).
2. NPM (node.js): To install Node packages used to build assets and other tasks.
3. GULP CLI: We're using gulp js to compile things (running various tasks).
4. Composer: To install different packages.
5. Knowledge of vim (vi) editor.

PS: NPM will manage other necessary dependency.

##### Extra step for windows user 😭 (Must follow)
Install a terminal (console) emulator. We recommend to have [Cmder](https://cmder.net/). This is optional but strongly
recommended as it offers some extra feature over cmd and git bash.

PS: You need to be comfortable with command line staff 😉.

#### Configure a local server instance
You can use any of available solution available (Eg. XAMPP, WAMP, MAMP) via VM (Vagrant) or direct install. Make sure
that you are comfortable with it. You might need to change settings of the server installation. Eg Enable apache/php
module, changing apache/nginx config, etc.

**It's strongly recommended to have `X-Debug` installed and configured on the server.**

For this guid we're assuming that the server root directory is

`/your/server/public_html/`

And the WordPress installation location is

`/your/server/public_html/plugindev/`

#### Clone this repository

As this section title says go and clone this repository. Just clone it...

```bash
$ cd /your/server/public_html/plugindev/wp-content/plugins/
$ git clone git@github.com:salexpresso/salexpresso.git
```

#### Install phpcs and necessary standards
We're keeping it simple. Just install phpcs globally along with necessary plugins (standards) via composer.

First we will install the PHP Code Sniffer
```bash
$ composer global require squizlabs/php_codesniffer
```
We will install a optional package which will reduce repeating stapes for configuring phpcs plugin
```bash
$ composer global require dealerdirect/phpcodesniffer-composer-installer
```
Now we'll install phpcs plugins (standards) required for this project.

```bash
$ composer global require \
    phpcompatibility/php-compatibility \
    phpcompatibility/phpcompatibility-wp \
    wp-coding-standards/wpcs \
    automattic/vipwpcs
```

#### Update Environment Variable

If your haven't already added `.composer/vendor/bin` to your environment path, add now.
To do this open `~/.bash_profile` in a text editor (create if doesn't exists).

```bash
PATH="$PATH:$HOME/.composer/vendor/bin"
``` 

Put the above line before `export PATH`. Put `export PATH` at the end or after the above line if doesn't exists on the file.

Now run the following command to reload bash profile
```bash
source ~/.bash_profile
```

#### Install dependencies and generate assets

To install the dependency just run the following command.

```bash
$ cd /your/server/public_html/plugindev/wp-content/plugins/salexpresso/
$ npm install
```

This will install both node and composer dependencies.

The source code found on GitHub might not contain compiled CSS or Javascript.
To generate those assets run the following command from the plugins root directory:

```bash
$ npm run build
```

To automatically rebuild the assets whenever a JS or SCSS file is modified run:

```bash
$ npm start
```

To run common linting:

```bash
$ npm test
```

To make a installable zip archive

```bash
$ npm run make-zip
```

#### IDE integrations
This repository contains IDE configuration directory (.idea) for PhpStorm. If you are using any IDE other than PhpStorm
please install necessary plugins for following integration.

Development database name needs to be `salexpresso`, for PhpStorm to recognize and connect the database for uses and insights. 

**Install plugin for following integration:**
1. phpcs
2. eslint
3. eslintignore
4. stylelint

### Git Flow

We're using git flow for development branch modeling
run following command to initialize git flow.

```bash
$ git flow init
```
Press enter (return) key to continue, we will use the default settings of git flow. Alternatively you can use the following
command to initialize git flow with default settings.

```bash
$ git flow init -d
```

### Code Standard

Here we're following the WordPress coding standard for both php and js as well as for css. `npm install` will install all
necessary linting tools including phpcs for validating the source codes.

We're using WordPress Code standards (WPCS) with some other phpcs plugins to lint all php files. and this repo is configured
with git pre-commit hook to prevent developers from do any invalid commit and push.

phpcs (and wpcs) validate php files using phpcs.xml as standard (rule) or you can say config file.
Developers aren't allowed to modify this file. They can exclude certain rule temporary (or for absolute necessary)
with the phpcs ignore comment like below.

```php
// phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
``` 

When writing any function method or any code if you think that other developer might want to change the output, you must
use introduce necessary hooks (action or filter in-case-of php) and trigger event (in-case-of js).

```php
/**
 * Filter doc
 * 
 * @param type $name    Description.
 * @param type $param_1 Description.
 * @param type $param_2 Description.
 */
apply_filters( 'salexpresso_filter_tag', 'value_1', $param_1, $param_2 );
``` 

```js
$(el).trigger('event') // for jquery|html element
//  we'll introduce event emitter later.
```

We would like to take autoload feature of composer. And the composer.json is already configured to autoload class files
located in `includes/classes`. Class files names must follow WordPress naming standard as well as PSR4 namespace standard
with relevancy.

Helper function files are also configured with the autoloader.

For view (template) files, we will dynamically locate and include when necessary. 

Necessary config files for linting tools are already configured and included with this repo.

Please check (WordPress coding standard handbook)[https://make.wordpress.org/core/handbook/best-practices/coding-standards/] for more details on this.
Please Check (WordPress VIP Go Code Review documentation) [https://wpvip.com/documentation/vip-go/code-review-blockers-warnings-notices/].

All necessary directory must have the `Silence is golden.` index.php file to prevent directory listing.

All file must have necessary file comments.

#### Examples

Here's some minimal example 

##### PHP
```php
<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso
 * @version 1.0.0
 * @since   1.0.0
 */
```
##### JS
```javascript
/**!
 * SaleXpresso Public Scripts
 *
 * @author  SaleXpresso <support@salexpresso.com>
 * @package SaleXpresso
 * @version 1.0.0
 */
```
##### SCSS
```scss
/**
 * SaleXpresso Public Styles
 *
 * @author  SaleXpresso <support@salexpresso.com>
 * @package SaleXpresso
 * @version 1.0.0
 */
```

All php file must contain the the following snippet to stop direct access (script kiddies) after the file comment.

```php
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}
```

All php file needs to have file end comment as following.
```php
// End of file class-salexpresso.php.
```

PS: trailing period (.) necessary so phpcs can validate it.

#### Naming conventions
##### PHP
Here we are following (WordPress PHP naming conventions)[https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/#naming-conventions].
On top of that, function, class, and hook names should be prefixed. For functions the prefix is `sxp_`, for classes and constant is `SXP_`
and for hooks is `salexpresso_`.
##### JS
WooCommerce core follows (WordPress JS naming conventions)[https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/#naming-conventions].
As with PHP, function, class, and hook names should be prefixed, but the convention for JS is slightly different,
and camelCase is used instead of snake_case. For functions, the prefix is sxp, for classes is SXP and for hooks is salexpresso.

Function name example:
```javascript
function sxpSettings() {}
```

Class name example:
```javascript
class SXPCustomer {
    constructor() {}
}
```

Hook name example (actions or filters):
- salexpressoEvent

##### CSS and SCSS
Don't reinvent the wheel. Also don't just copy and paste things. If you need to use any library or part of library,
import it and extend it within the project.

###### Prefixing

As a WordPress plugin SaleXpresso has to play nicely with WordPress core and other plugins / themes. To minimise conflict
potential all classes should be prefixed with .sxp-.

###### Example

```scss
.sxp-wrap {}
.sxp-customer {
    $-type {}
    $-name {}
}
```

###### TL;DR

- Follow the (WP Coding standards for CSS)[https://make.wordpress.org/core/handbook/best-practices/coding-standards/css/] unless it contradicts anything here.
- Prefix all the things.

### Localisation guidelines

1. Use `salexpresso` textdomain in all strings.
2. When using dynamic strings in printf/sprintf, if you are replacing > 1 string use numbered args.
e.g. `Test %s string %s.` would be `Test %1$s string %2$s.`
3. Use sentence case. e.g. `Some Thing` should be `Some thing`.
Avoid HTML. If needed, insert the HTML using sprintf.

For more information, see WP core document (i18n for WordPress Developers)[https://codex.wordpress.org/I18n_for_WordPress_Developers].
