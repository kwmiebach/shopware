<?php declare(strict_types=1);

namespace Shopware\Api\Media\Event\Media;

use Shopware\Api\Media\Collection\MediaBasicCollection;
use Shopware\Api\Media\Event\MediaAlbum\MediaAlbumBasicLoadedEvent;
use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Event\NestedEvent;
use Shopware\Framework\Event\NestedEventCollection;

class MediaBasicLoadedEvent extends NestedEvent
{
    const NAME = 'media.basic.loaded';

    /**
     * @var TranslationContext
     */
    protected $context;

    /**
     * @var MediaBasicCollection
     */
    protected $media;

    public function __construct(MediaBasicCollection $media, TranslationContext $context)
    {
        $this->context = $context;
        $this->media = $media;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): TranslationContext
    {
        return $this->context;
    }

    public function getMedia(): MediaBasicCollection
    {
        return $this->media;
    }

    public function getEvents(): ?NestedEventCollection
    {
        $events = [];
        if ($this->media->getAlbums()->count() > 0) {
            $events[] = new MediaAlbumBasicLoadedEvent($this->media->getAlbums(), $this->context);
        }

        return new NestedEventCollection($events);
    }
}