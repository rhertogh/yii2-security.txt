Yii2 security.txt extension
===========================

[![Latest Stable Version](https://img.shields.io/packagist/v/rhertogh/yii2-security.txt.svg)](https://packagist.org/packages/rhertogh/yii2-security.txt)
[![build Status](https://github.com/rhertogh/yii2-security.txt/actions/workflows/build.yml/badge.svg)](https://github.com/rhertogh/yii2-security.txt/actions/workflows/build.yml)
[![Code Coverage](https://scrutinizer-ci.com/g/rhertogh/yii2-security.txt/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/rhertogh/yii2-security.txt/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rhertogh/yii2-security.txt/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rhertogh/yii2-security.txt/?branch=master)
[![GitHub](https://img.shields.io/github/license/rhertogh/yii2-security.txt?color=brightgreen)](https://github.com/rhertogh/yii2-security.txt/blob/master/LICENSE.md)

A Yii2 extension to serve .well-known/security.txt  
[RFC 9116: A File Format to Aid in Security Vulnerability Disclosure.](https://www.rfc-editor.org/rfc/rfc9116)

### 📑 Contents

- [Getting started](#-getting-started)
- [Test Drive](#%EF%B8%8F-test-drive)
- [Documentation](#-documentation)
- [FAQ](#-faq)
- [Implemented Standards](#-implemented-standards)
- [Importing/Migrating](#-importingmigrating)
- [Versioning & Change Log](#-versioning--change-log)
- [Reporting Security issues](#-reporting-security-issues)
- [Directory Structure](#-directory-structure)
- [Contributing](#-contributing)
- [Credits](#-credits)
- [License](#-license)

---

🐣 Getting started
------------------
#### Requirements:
* The minimum required PHP version is 8.1 (compatibility tested up till and including PHP 8.3).
* The minimum required Yii version is 2.0.49.

#### Installation:
The preferred way to install this extension is through [composer](https://getcomposer.org/download/).
```bash
composer require rhertogh/yii2-security.txt
```

Full installation and configuration details can be found in the docs under
[Installing Yii2 security.txt](docs/guide/start-installation.md)


🏎️ Test Drive
----------
You can run a local preview instance using [Docker](https://docker.com/):
```bash
docker run --rm -p 82:80 --name Yii2SecurityTxt ghcr.io/rhertogh/yii2-securitytxt:master
```
After the container is started you can access the sample server on [localhost:82](http://localhost:82).
> Hint: The port number on the host machine is specified by the first part of the `-p` argument.
> This can be changed if desired (e.g. `-p 88:80`).

To access the CLI of the Docker container you can run:
```bash
docker container exec -it Yii2SecurityTxt bash
```


📖 Documentation
----------------
There are two main sections in the documentation:
* [Usage Guide](docs/guide/README.md) for using the Yii2SecurityTxt in your own project.
* [Development Guide](docs/internals/README.md) for contributing to the Yii2SecurityTxt.


🔮 FAQ
------
The FAQ can be found [here](docs/guide/faq.md).

📜 Versioning & Change Log
--------------------------
The Yii2 security.txt project follows [Semantic Versioning 2.0](https://semver.org/spec/v2.0.0.html)  
Please see the [Change Log](CHANGELOG.md) for more information on version history
and the [Upgrading Instructions](UPGRADE.md) when upgrading to a newer version.


🔎 Reporting Security issues
----------------------------
In case you found a security issue please [contact us directly](
https://forms.gle/8aEGxmN51Hvb7oLJ7)
DO NOT use the issue tracker or discuss it in public as it will cause more damage than help.

Please note that as a non-commercial OpenSource project we are not able to pay bounties.


📂 Directory Structure
----------------------
```
docker/     Docker container definition
docs/       Documentation (for both usage and development)
sample/     Sample app for the server
src/        Yii2SecurityTxt source
tests/      Codeception unit and functional tests
```


🚀 Contributing
---------------
The Yii2SecurityTxt extension is [Open Source](LICENSE.md). You can help by:

- [Report an issue](docs/internals/report-an-issue.md)
- [Contribute with new features or bug fixes](docs/internals/pull-request-qa.md)

Thanks in advance for your contribution!


🎉 Credits
----------
- [Rutger Hertogh](https://github.com/rhertogh)
- [All Contributors](https://github.com/rhertogh/yii2-security.txt/graphs/contributors)


✒️ License
----------
The Yii2SecurityTxt extension is free software. It is released under the terms of the Apache License.
Please see [`LICENSE.md`](LICENSE.md) for more information.
