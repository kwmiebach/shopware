<?php declare(strict_types=1);

namespace Shopware\Api\Order\Collection;

use Shopware\Api\Mail\Collection\MailBasicCollection;
use Shopware\Api\Order\Struct\OrderStateDetailStruct;

class OrderStateDetailCollection extends OrderStateBasicCollection
{
    /**
     * @var OrderStateDetailStruct[]
     */
    protected $elements = [];

    public function getTranslationIds(): array
    {
        $ids = [];
        foreach ($this->elements as $element) {
            foreach ($element->getTranslations()->getIds() as $id) {
                $ids[] = $id;
            }
        }

        return $ids;
    }

    public function getTranslations(): OrderStateTranslationBasicCollection
    {
        $collection = new OrderStateTranslationBasicCollection();
        foreach ($this->elements as $element) {
            $collection->fill($element->getTranslations()->getElements());
        }

        return $collection;
    }

    protected function getExpectedClass(): string
    {
        return OrderStateDetailStruct::class;
    }
}
