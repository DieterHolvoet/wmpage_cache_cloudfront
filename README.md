wmpage_cache_cloudfront
======================

[![Latest Stable Version](https://poser.pugx.org/wieni/wmpage_cache_cloudfront/v/stable)](https://packagist.org/packages/wieni/wmpage_cache_cloudfront)
[![Total Downloads](https://poser.pugx.org/wieni/wmpage_cache_cloudfront/downloads)](https://packagist.org/packages/wieni/wmpage_cache_cloudfront)
[![License](https://poser.pugx.org/wieni/wmpage_cache_cloudfront/license)](https://packagist.org/packages/wieni/wmpage_cache_cloudfront)

> An [Amazon CloudFront](https://aws.amazon.com/cloudfront) cache invalidator for [wieni/wmpage_cache](https://github.com/wieni/wmpage_cache)

## Installation

This package requires PHP 7.1 and Drupal 8 or higher. It can be
installed using Composer:

```bash
 composer require wieni/wmpage_cache_cloudfront
```

To enable this cache invalidator, change the `wmpage_cache.purger` container parameter:
```yaml
parameters:
    wmpage_cache.cloudfront:
        distributionId: ''
        accessKey: ''
        secret: ''
    
    wmpage_cache.storage: wmpage_cache.storage.cloudfront
    
    # This storage only invalidates at CloudFront. It does not store anything
    # and requires another storage to function. By default it uses the database storage.
    wmpage_cache.cloudfront.backend.storage: wmpage_cache.storage.mysql
```

## Changelog
All notable changes to this project will be documented in the
[CHANGELOG](CHANGELOG.md) file.

## Security
If you discover any security-related issues, please email
[security@wieni.be](mailto:security@wieni.be) instead of using the issue
tracker.

## License
Distributed under the MIT License. See the [LICENSE](LICENSE) file
for more information.
