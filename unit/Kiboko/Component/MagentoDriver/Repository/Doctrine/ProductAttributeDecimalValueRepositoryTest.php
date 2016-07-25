<?php

namespace unit\Kiboko\Component\MagentoDriver\Repository\Doctrine;

use Doctrine\DBAL\Schema\Schema;
use Kiboko\Component\MagentoDriver\Entity\Product\ProductInterface;
use Kiboko\Component\MagentoDriver\Factory\AttributeValueFactoryInterface;
use Kiboko\Component\MagentoDriver\Model\AttributeInterface;
use Kiboko\Component\MagentoDriver\Model\DecimalAttributeValueInterface;
use Kiboko\Component\MagentoDriver\Model\Immutable\ImmutableDecimalAttributeValue;
use Kiboko\Component\MagentoDriver\QueryBuilder\Doctrine\ProductAttributeValueQueryBuilder;
use Kiboko\Component\MagentoDriver\Repository\AttributeRepositoryInterface;
use Kiboko\Component\MagentoDriver\Repository\Doctrine\ProductAttributeValueRepository;
use Kiboko\Component\MagentoDriver\Repository\ProductAttributeValueRepositoryInterface;
use PHPUnit_Extensions_Database_DataSet_IDataSet;
use unit\Kiboko\Component\MagentoDriver\SchemaBuilder\DoctrineSchemaBuilder;
use unit\Kiboko\Component\MagentoDriver\DoctrineTools\DatabaseConnectionAwareTrait;
use unit\Kiboko\Component\MagentoDriver\SchemaBuilder\Table\Store as TableStore;

class ProductAttributeDecimalValueRepositoryTest extends \PHPUnit_Framework_TestCase
{
    use DatabaseConnectionAwareTrait;

    /**
     * @var Schema
     */
    private $schema;

    /**
     * @var ProductAttributeValueRepositoryInterface
     */
    private $repository;

    /**
     * @var AttributeRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $attributeRepositoryMock;

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        $dataSet = new \PHPUnit_Extensions_Database_DataSet_ArrayDataSet([]);

        return $dataSet;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    private function truncateTables()
    {
        $platform = $this->getDoctrineConnection()->getDatabasePlatform();

        $this->getDoctrineConnection()->exec('SET FOREIGN_KEY_CHECKS=0');
        $this->getDoctrineConnection()->exec(
            $platform->getTruncateTableSQL(TableStore::getTableName($GLOBALS['MAGENTO_VERSION']))
        );

        $this->getDoctrineConnection()->exec(
            $platform->getTruncateTableSQL('eav_attribute')
        );

        $this->getDoctrineConnection()->exec(
            $platform->getTruncateTableSQL('catalog_product_entity')
        );

        $this->getDoctrineConnection()->exec(
            $platform->getTruncateTableSQL('catalog_product_entity_decimal')
        );
        $this->getDoctrineConnection()->exec('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function setUp()
    {
        parent::setUp();

        $currentSchema = $this->getDoctrineConnection()
            ->getSchemaManager()
            ->createSchema()
        ;

        $this->schema = new Schema();

        $schemaBuilder = new DoctrineSchemaBuilder($this->getDoctrineConnection(), $this->schema);
        $schemaBuilder->ensureStoreTable();
        $schemaBuilder->ensureAttributeTable();
        $schemaBuilder->ensureCatalogProductEntityTable();
        $schemaBuilder->ensureCatalogProductAttributeValueTable('decimal', 'decimal');
        $schemaBuilder->ensureCatalogProductAttributeValueToStoreLinks('decimal');
        $schemaBuilder->ensureCatalogProductAttributeValueToAttributeLinks('decimal');
        $schemaBuilder->ensureCatalogProductAttributeValueToCatalogProductEntityLinks('decimal');

        $comparator = new \Doctrine\DBAL\Schema\Comparator();
        $schemaDiff = $comparator->compare($currentSchema, $this->schema);

        foreach ($schemaDiff->toSql($this->getDoctrineConnection()->getDatabasePlatform()) as $sql) {
            $this->getDoctrineConnection()->exec($sql);
        }

        $this->truncateTables();

        $schemaBuilder->hydrateStoreTable(
            'catalog_product_entity_decimal',
            DoctrineSchemaBuilder::CONTEXT_REPOSITORY,
            $GLOBALS['MAGENTO_VERSION'],
            $GLOBALS['MAGENTO_EDITION']
        );

        $schemaBuilder->hydrateAttributeTable(
            'catalog_product_entity_decimal',
            DoctrineSchemaBuilder::CONTEXT_REPOSITORY,
            $GLOBALS['MAGENTO_VERSION'],
            $GLOBALS['MAGENTO_EDITION']
        );

        $schemaBuilder->hydrateCatalogProductEntityTable(
            'catalog_product_entity_decimal',
            DoctrineSchemaBuilder::CONTEXT_REPOSITORY,
            $GLOBALS['MAGENTO_VERSION'],
            $GLOBALS['MAGENTO_EDITION']
        );

        $schemaBuilder->hydrateCatalogProductAttributeValueTable(
            'decimal',
            'catalog_product_entity_decimal',
            DoctrineSchemaBuilder::CONTEXT_REPOSITORY,
            $GLOBALS['MAGENTO_VERSION'],
            $GLOBALS['MAGENTO_EDITION']
        );

        $this->repository = new ProductAttributeValueRepository(
            $this->getDoctrineConnection(),
            new ProductAttributeValueQueryBuilder(
                $this->getDoctrineConnection(),
                ProductAttributeValueQueryBuilder::getDefaultTable('decimal'),
                ProductAttributeValueQueryBuilder::getDefaultVariantAxisTable(),
                ProductAttributeValueQueryBuilder::getDefaultFields()
            ),
            $this->getAttributeRepositoryMock(),
            $this->getAttributeValueFactoryMock()
        );
    }

    protected function tearDown()
    {
        $this->truncateTables();

        parent::tearDown();

        $this->repository = null;
    }

    /**
     * @param int    $id
     * @param string $code
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|AttributeRepositoryInterface
     */
    private function getAttributeMock($id, $code)
    {
        $mock = $this->createMock(AttributeInterface::class);

        $mock->method('getId')
            ->willReturn($id)
        ;

        $mock->method('getCode')
            ->willReturn($code)
        ;

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|AttributeRepositoryInterface
     */
    private function getAttributeRepositoryMock()
    {
        $this->attributeRepositoryMock = $mock = $this->createMock(AttributeRepositoryInterface::class);

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|AttributeValueFactoryInterface
     */
    private function getAttributeValueFactoryMock()
    {
        $mock = $this->createMock(AttributeValueFactoryInterface::class);

        $mock->method('buildNew')
            ->with($this->isInstanceOf(AttributeInterface::class), $this->isType('array'))
            ->willReturnCallback(function ($attribute, $data) {
                return ImmutableDecimalAttributeValue::buildNewWith(
                    $attribute,
                    $data['value_id'],
                    $data['value'],
                    null,
                    $data['store_id']
                );
            })
        ;

        return $mock;
    }

    public function testFetchingOneByProductAndAttributeFromDefault()
    {
        /** @var ProductInterface $product */
        $product = $this->createMock(ProductInterface::class);
        $product
            ->method('getId')
            ->willReturn(3)
        ;

        /** @var AttributeInterface $attribute */
        $attribute = $this->getAttributeMock(79, 'cost');

        $this->attributeRepositoryMock
            ->method('findOneById')
            ->with(79)
            ->willReturn($attribute)
        ;

        /** @var DecimalAttributeValueInterface $attributeValue */
        $attributeValue = $this->repository->findOneByProductAndAttributeFromDefault($product, $attribute);
        $this->assertInstanceOf(DecimalAttributeValueInterface::class, $attributeValue);

        $this->assertInternalType('float', $attributeValue->getValue());
    }

    public function testFetchingOneByProductAndAttributeFromDefaultButNonExistent()
    {
        /** @var ProductInterface $product */
        $product = $this->createMock(ProductInterface::class);
        $product
            ->method('getId')
            ->willReturn(PHP_INT_MAX - 1)
        ;

        /** @var AttributeInterface $attribute */
        $attribute = $this->getAttributeMock(79, 'cost');

        $this->assertNull($this->repository->findOneByProductAndAttributeFromDefault($product, $attribute));
    }
}
