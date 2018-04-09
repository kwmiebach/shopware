<?php declare(strict_types=1);

namespace Shopware\Api\Product\Event\ProductConfigurator;

use Shopware\Api\Product\Struct\ProductConfiguratorSearchResult;
use Shopware\Context\Struct\ShopContext;
use Shopware\Framework\Event\NestedEvent;

class ProductConfiguratorSearchResultLoadedEvent extends NestedEvent
{
    public const NAME = 'product_configurator.search.result.loaded';

    /**
     * @var ProductConfiguratorSearchResult
     */
    protected $result;

    public function __construct(ProductConfiguratorSearchResult $result)
    {
        $this->result = $result;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): ShopContext
    {
        return $this->result->getContext();
    }
}