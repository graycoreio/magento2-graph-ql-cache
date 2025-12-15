<?php

/**
 * Copyright © Graycore, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Graycore\GraphQlCache\Model;

use Magento\PageCache\Model\Config;
use Graycore\GraphQlCache\Model\ResolverTtlStore;

class QueryTtl
{
    /**
     * @param ResolverTtlStore $store
     * @param Config $config
     */
    public function __construct(
        private ResolverTtlStore $store,
        private Config $config,
    ) {
    }

    /**
     * Get the TTL of the query
     *
     * @return integer
     */
    public function getTtl(): int
    {
        if (!$this->store->getAll()) {
            return (int)$this->config->getTtl();
        }
        return min((int)$this->config->getTtl(), min($this->store->getAll()));
    }
}
