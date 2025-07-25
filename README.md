# graycore/magento2-graph-ql-cache

The Graycore GraphQL Cache module provides a structured mechanism for managing and applying cache lifetimes (TTLs) for Magento 2 GraphQL queries. It allows resolvers to contribute dynamic TTL values based on business logic, such as future dates, promotions, or entity validity. These TTLs are then automatically applied to the response as cache-control headers, enabling full-page caching and CDN integration for GraphQL endpoints.

<div align="center">

[![Packagist Downloads](https://img.shields.io/packagist/dm/graycore/magento2-graph-ql-cache?color=blue)](https://packagist.org/packages/graycore/magento2-graph-ql-cache/stats)
[![Packagist Version](https://img.shields.io/packagist/v/graycore/magento2-graph-ql-cache?color=blue)](https://packagist.org/packages/graycore/magento2-graph-ql-cache)
[![Packagist License](https://img.shields.io/packagist/l/graycore/magento2-graph-ql-cache)](https://github.com/graycoreio/magento2-graph-ql-cache/blob/main/LICENSE)

</div>

## Use Case

This module is ideal for Magento stores that want to:

- Dynamically cache GraphQL queries based on data freshness
- Improve GraphQL performance with edge caching/CDNs
- Respect TTLs for time-sensitive data (e.g. flash sales, scheduled promotions, expiring inventory)

## Key Features

- **Resolver-Driven TTL Aggregation** - Resolver code can dynamically register TTLs using ResolverTtlStoreInterface, either with explicit seconds or based on future dates (with support for start-of-day and end-of-day cutoffs).

- **Automatic Cache Header Population** - This plugin will intercept GraphQL response rendering and applies the minimum TTL collected during the query lifecycle, enabling fine-grained cache-control behavior for GraphQL results.

- **Supports Flexible Input** - TTL input can be an integer, a DateTimeImmutable object, or a string (auto-parsed using the current store’s timezone).

- **Integration with Magento’s GraphQlCache system** - Honors and respects Magento's existing CacheableQuery mechanisms, enhancing them with automatic TTL computation.

## Install

```bash
composer require graycore/magento2-graph-ql-cache
bin/magento module:enable Graycore_GraphQlCache
bin/magento setup:upgrade
```

## Usage

The primary mechanism that you interact with after installation is the `Graycore\GraphQlCache\Api\ResolverTtlStoreInterface`.

```php
declare(strict_types=1);

namespace Vendor\Module\Model\Resolver;

use Graycore\GraphQlCache\Api\ResolverTtlStoreInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class VendorResolver implements ResolverInterface
{

    public function __construct(
        private ResolverTtlStoreInterface $ttlStore
    ) {}

    /**
     * @inheritDoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $this->ttl->add(300); // raw seconds
        $this->ttl->addForDate('2025-08-01T12:00:00'); // string or DateTimeImmutable
        $this->ttl->addForStartDate($date); // uses midnight of the provided date
        $this->ttl->addForEndDate($date);   // uses 23:59:59 of the provided date

        return $value;
    }
}
```


## Upgrading

* [Semver Policy](https://semver.org/)