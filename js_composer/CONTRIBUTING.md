# Contributing to WPBakery Page Builder

We are open to, and grateful for, any contributions made by the community.

You can contribute and help make WPBakery better. Please read the following guidelines before making a contribution.

- [Submission Guidelines](#submission-guidelines)
- [Installation instruction](#installation-instruction)
- [Coding Rules](#coding-rules)
- [Changelog](#changelog)

If you have any questions about this guide, please [open an issue](/issues/new).

## Submission Guidelines

### Pull Requests

1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Make your changes.
4. Commit your changes.
5. Push your branch to your fork.
6. Create a pull request (against the master branch).
7. Wait for your pull request to be reviewed.
8. Make changes if the reviewer requests them.
9. Get your pull request merged (into the WPBakery repository) and wait for it to be released.
10. PR in this repository will be closed after the PR in the main repository is merged.

**NOTE: Keep the master branch tests passing at all times.**

### Creating Issues

When creating issues please follow the according template structure:
- [Bug report](./.github/ISSUE_TEMPLATE/bug_report.md)
- [Feature request](./.github/ISSUE_TEMPLATE/feature_request.md)
- [Custom](./.github/ISSUE_TEMPLATE/custom.md)

**NOTE: When you are creating an issue, please include as many details as possible. Fill out the according template and provide a clear and descriptive title.**

#### Tips for writing good issues

1. Search the issue tracker before opening an issue.
2. Ensure you're using the latest version of WPBakery Page Builder.
3. Use a clear and descriptive title for the issue to identify the problem.
4. Describe the exact steps which reproduce the problem in as many details as possible.
5. Provide specific examples to demonstrate the steps.
6. Describe the behavior you observed after following the steps and explain what exactly is the problem with that behavior.
7. Explain which behavior you expected to see instead and why.
8. Include screenshots and animated GIFs which show you following the described steps and clearly demonstrate the problem.
9. If the problem is related to performance or memory, include a profile of the page load.
11. If the problem is related to the editor, include a screenshot of the browser.

## Installation instruction

### Clone

```sh
$ git clone git@github.com:[YOUR_USERNAME]/js_composer-3rd-party-devs.git
$ cd js_composer-3rd-party-devs
```

### Install

```sh
$ php composer.phar install
$ npm run install-project
```

You can also install npm packages separately.

Install npm packages for main project (inside the main project folder):

```sh
$ npm install
```

Install npm packages for assets vendor libraries:

```sh
$ cd assets/lib/vendor
$ npm install
```

### Build

To build the project, we are using [Gulp](https://gulpjs.com/). The following commands are available:

The `build` and `watch` command compile all JS, LESS and CSS files. The files will not be minified or uglified, and contain a sourcemap (for debugging).

The `build-prod` command will compile all JS, LESS and CSS files. The files will be minified and uglified, and will not contain a sourcemap (for production).

Build:
```sh
npm run build
```

Watch:
```sh
npm run watch
```

Build production:
```sh
npm run build-prod
```

## Coding Rules

To ensure consistency throughout the source code, keep these rules in mind as you are working:

- Your code should pass all tests.
- Your code should pass all linters.
- Your code should be well-documented.
- Your code should be well-formatted.
- Your code should be consistent with the existing codebase.
- Your code should be consistent with the coding style (see below).
- Your code should be consistent with the [accessibility guidelines](https://wordpress.org/about/accessibility/).
- Your code should be consistent with the [security guidelines](https://wordpress.org/about/security/).
- Your code should be consistent with the [performance guidelines](https://make.wordpress.org/performance/handbook/measuring-performance/best-practices-for-performance-measurement/).
- Your code should be consistent with the [localization guidelines](https://developer.wordpress.org/apis/internationalization/localization/).
- Your code should be consistent with the [internationalization guidelines](https://developer.wordpress.org/apis/internationalization/internationalization-guidelines/).

### PHP coding style check

For PHP, we are using [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer/) to ensure a consistent coding style.

To check your PHP code against our code standards rules:

```sh
php vendor/bin/phpcs --standard=project.ruleset.xml include js_composer.php config tests modules
```
To automatically fix errors and warnings, run:

```sh
php vendor/bin/phpcbf --standard=project.ruleset.xml include js_composer.php config tests modules
```

### JavaScript coding style check

For JavaScript, we are using [ESLint](https://eslint.org/) to ensure a consistent coding style.

To check your JS code against our code standards rules:

```sh
$ npm run eslint
```

To automatically fix errors and warnings, run:

```sh
$ npm run eslint --fix
```

### Run JavaScript tests

To run JavaScript tests, we are using [Jest](https://jestjs.io/). The following command is available:

```sh
npm run test
```

### Run PHPUnit tests

```sh
sudo docker run -ti -v=local-path-to-plugin-folder:/var/www/html/wp-content/plugins/js_composer wpbakery/ci-github:actions-node-18-13112024
cd wp-content/plugins/js_composer
php composer.phar install --dev
chmod +x _.tools/phpunit-9.0.0.phar

# run all test
php  _.tools/phpunit-9.0.0.phar --coverage-html tests/coverage-report --configuration=phpunit.xml

```

## Changelog

All notable changes to this project will be documented in the [changelog](/changelog.txt) file.
