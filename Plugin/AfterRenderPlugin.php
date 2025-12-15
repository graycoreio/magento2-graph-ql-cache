<?php

/**
 * Copyright © Graycore, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Graycore\GraphQlCache\Plugin;

use Graycore\GraphQlCache\Model\QueryTtl;
use Magento\Framework\App\Response\Http as ResponseHttp;
use Magento\Framework\Controller\ResultInterface;
use Magento\GraphQlCache\Model\CacheableQuery;

class AfterRenderPlugin
{
    /**
     * @param CacheableQuery $cacheableQuery
     * @param ResponseHttp $response
     * @param QueryTtl $ttl
     */
    public function __construct(
        private CacheableQuery $cacheableQuery,
        private ResponseHttp $response,
        private QueryTtl $ttl,
    ) {
        $this->cacheableQuery = $cacheableQuery;
        $this->response = $response;
    }

    /**
     * Plugin for GraphQL after render from dispatch to set tag and cache headers
     *
     * @param ResultInterface $subject
     * @param ResultInterface $result
     * @param ResponseHttp $response
     * @return ResultInterface
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterRenderResult(ResultInterface $subject, ResultInterface $result, ResponseHttp $response)
    {
        if ($this->cacheableQuery->shouldPopulateCacheHeadersWithTags()) {
            $this->response->setPublicHeaders($this->ttl->getTtl());
        }

        return $result;
    }
}
