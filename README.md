# Google Analytics bundle for Symfony

[![Latest Version][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-github-actions]][link-github-actions]

Use Google Analytics in your Symfony application. Under the hood this bundle integrates the
[Google Analytics measurement protocol](https://github.com/Setono/google-analytics-measurement-protocol) library.

## Installation

To install this bundle, simply run:

```shell
composer require setono/google-analytics-bundle
```

This also installs the [Bot Detection Bundle](https://github.com/Setono/BotDetectionBundle) which is used to filter bot requests.

If you want to handle consent (i.e. cookie/GDPR consent), you can use the [consent bundle](https://github.com/Setono/ConsentBundle), by installing it:

```shell
composer require setono/consent-bundle
```

## Usage

TODO

[ico-version]: https://poser.pugx.org/setono/google-analytics-bundle/v/stable
[ico-license]: https://poser.pugx.org/setono/google-analytics-bundle/license
[ico-github-actions]: https://github.com/Setono/GoogleAnalyticsBundle/workflows/build/badge.svg

[link-packagist]: https://packagist.org/packages/setono/google-analytics-bundle
[link-github-actions]: https://github.com/Setono/GoogleAnalyticsBundle/actions
