<?php declare(strict_types=1);

namespace Shopware\Api\Country\Definition;

use Shopware\Api\Country\Collection\CountryBasicCollection;
use Shopware\Api\Country\Collection\CountryDetailCollection;
use Shopware\Api\Country\Event\Country\CountryWrittenEvent;
use Shopware\Api\Country\Repository\CountryRepository;
use Shopware\Api\Country\Struct\CountryBasicStruct;
use Shopware\Api\Country\Struct\CountryDetailStruct;
use Shopware\Api\Customer\Definition\CustomerAddressDefinition;
use Shopware\Api\Entity\EntityDefinition;
use Shopware\Api\Entity\EntityExtensionInterface;
use Shopware\Api\Entity\Field\BoolField;
use Shopware\Api\Entity\Field\DateField;
use Shopware\Api\Entity\Field\FkField;
use Shopware\Api\Entity\Field\IdField;
use Shopware\Api\Entity\Field\IntField;
use Shopware\Api\Entity\Field\ManyToOneAssociationField;
use Shopware\Api\Entity\Field\OneToManyAssociationField;
use Shopware\Api\Entity\Field\StringField;
use Shopware\Api\Entity\Field\TranslatedField;
use Shopware\Api\Entity\Field\TranslationsAssociationField;
use Shopware\Api\Entity\FieldCollection;
use Shopware\Api\Entity\Write\Flag\PrimaryKey;
use Shopware\Api\Entity\Write\Flag\Required;
use Shopware\Api\Order\Definition\OrderAddressDefinition;
use Shopware\Api\Shop\Definition\ShopDefinition;
use Shopware\Api\Tax\Definition\TaxAreaRuleDefinition;

class CountryDefinition extends EntityDefinition
{
    /**
     * @var FieldCollection
     */
    protected static $primaryKeys;

    /**
     * @var FieldCollection
     */
    protected static $fields;

    /**
     * @var EntityExtensionInterface[]
     */
    protected static $extensions = [];

    public static function getEntityName(): string
    {
        return 'country';
    }

    public static function getFields(): FieldCollection
    {
        if (self::$fields) {
            return self::$fields;
        }

        self::$fields = new FieldCollection([
            (new IdField('id', 'id'))->setFlags(new PrimaryKey(), new Required()),
            (new FkField('country_area_id', 'areaId', CountryAreaDefinition::class))->setFlags(new Required()),
            (new TranslatedField(new StringField('name', 'name')))->setFlags(new Required()),
            new StringField('iso', 'iso'),
            new IntField('position', 'position'),
            new BoolField('shipping_free', 'shippingFree'),
            new BoolField('tax_free', 'taxFree'),
            new BoolField('taxfree_for_vat_id', 'taxfreeForVatId'),
            new BoolField('taxfree_vatid_checked', 'taxfreeVatidChecked'),
            new BoolField('active', 'active'),
            new StringField('iso3', 'iso3'),
            new BoolField('display_state_in_registration', 'displayStateInRegistration'),
            new BoolField('force_state_in_registration', 'forceStateInRegistration'),
            new DateField('created_at', 'createdAt'),
            new DateField('updated_at', 'updatedAt'),
            new ManyToOneAssociationField('area', 'country_area_id', CountryAreaDefinition::class, false),
            new OneToManyAssociationField('states', CountryStateDefinition::class, 'country_id', false, 'id'),
            (new TranslationsAssociationField('translations', CountryTranslationDefinition::class, 'country_id', false, 'id'))->setFlags(new Required()),
            new OneToManyAssociationField('customerAddresses', CustomerAddressDefinition::class, 'country_id', false, 'id'),
            new OneToManyAssociationField('orderAddresses', OrderAddressDefinition::class, 'country_id', false, 'id'),
            new OneToManyAssociationField('shops', ShopDefinition::class, 'country_id', false, 'id'),
            new OneToManyAssociationField('taxAreaRules', TaxAreaRuleDefinition::class, 'country_id', false, 'id'),
        ]);

        foreach (self::$extensions as $extension) {
            $extension->extendFields(self::$fields);
        }

        return self::$fields;
    }

    public static function getRepositoryClass(): string
    {
        return CountryRepository::class;
    }

    public static function getBasicCollectionClass(): string
    {
        return CountryBasicCollection::class;
    }

    public static function getWrittenEventClass(): string
    {
        return CountryWrittenEvent::class;
    }

    public static function getBasicStructClass(): string
    {
        return CountryBasicStruct::class;
    }

    public static function getTranslationDefinitionClass(): ?string
    {
        return CountryTranslationDefinition::class;
    }

    public static function getDetailStructClass(): string
    {
        return CountryDetailStruct::class;
    }

    public static function getDetailCollectionClass(): string
    {
        return CountryDetailCollection::class;
    }
}
