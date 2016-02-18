Package lemonphp/cli
===
[![Build Status](https://travis-ci.org/lemonphp/cli.svg?branch=master)](https://travis-ci.org/lemonphp/cli)
[![Coverage Status](https://coveralls.io/repos/github/lemonphp/cli/badge.svg?branch=master)](https://coveralls.io/github/lemonphp/cli?branch=master)

A a simple command line application framework to develop simple tools based on Symfony2 components


Requirements
---

* php >=5.5.9
* pimple/pimple ^3.0
* symfony/console ^3.0
* symfony/event-dispatcher ^3.0

Installation
---

```shell
$ composer require lemonphp/cli
```

Usage
---

```php
$app = new \Lemon\Cli\App('Simple CLI app', '1.0.1');
$app->addCommand(new YourCommand());
$app->run();
```

Changelog
---
See [CHANGELOG.md](https://github.com/lemonphp/cli/blob/master/CHANGELOG.md)

Contributing
---
Please report any bugs or add pull requests on [Github Issues](https://github.com/lemonphp/cli/issues).

License
---
This project is released under the MIT License.