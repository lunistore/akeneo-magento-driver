<?php

namespace unit\Luni\Component\MagentoDriver\Deleter\Doctrine\EntityStore;

use Doctrine\DBAL\Schema\Schema;
use Luni\Component\MagentoDriver\Factory\StandardEntityStoreFactory;
use Luni\Component\MagentoDriver\Repository\Doctrine\EntityStoreRepository;
use Luni\Component\MagentoDriver\Persister\EntityStorePersisterInterface;
use Luni\Component\MagentoDriver\Deleter\EntityStoreDeleterInterface;
use Luni\Component\MagentoDriver\Persister\Direct\Entity\StandardEntityStorePersister;
use Luni\Component\MagentoDriver\Deleter\Doctrine\EntityStoreDeleter;
use Luni\Component\MagentoDriver\QueryBuilder\Doctrine\EntityStoreQueryBuilder;
use PHPUnit_Extensions_Database_DataSet_IDataSet;
use unit\Luni\Component\MagentoDriver\SchemaBuilder\DoctrineSchemaBuilder;
use unit\Luni\Component\MagentoDriver\DoctrineTools\DatabaseConnectionAwareTrait;

class EntityStoreDeleterTest extends \PHPUnit_Framework_TestCase
{

    use DatabaseConnectionAwareTrait;

    /**
     * @var Schema
     */
    private $schema;

    /**
     * @var EntityStoreDeleterInterface
     */
    private $deleter;
    
    /**
     * @var EntityStorePersisterInterface
     */
    private $persister;

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        $dataset = new \PHPUnit_Extensions_Database_DataSet_YamlDataSet(
                $this->getFixturesPathname('eav_entity_store', '1.9', 'ce'));

        return $dataset;
    }

    private function truncateTables()
    {
        $platform = $this->getDoctrineConnection()->getDatabasePlatform();

        $this->getDoctrineConnection()->exec('SET FOREIGN_KEY_CHECKS=0');
        $this->getDoctrineConnection()->exec(
                $platform->getTruncateTableSQL('eav_entity_type')
        );

        $this->getDoctrineConnection()->exec(
                $platform->getTruncateTableSQL('eav_entity_store')
        );

        $this->getDoctrineConnection()->exec('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function setUp()
    {
        $currentSchema = $this->getDoctrineConnection()->getSchemaManager()->createSchema();

        $this->schema = new Schema();

        $schemaBuilder = new DoctrineSchemaBuilder($this->getDoctrineConnection(), $this->schema);
        $schemaBuilder->ensureEntityTypeTable();
        $schemaBuilder->ensureEntityStoreTable();

        $comparator = new \Doctrine\DBAL\Schema\Comparator();
        $schemaDiff = $comparator->compare($currentSchema, $this->schema);

        foreach ($schemaDiff->toSql($this->getDoctrineConnection()->getDatabasePlatform()) as $sql) {
            $this->getDoctrineConnection()->exec($sql);
        }

        $this->truncateTables();

        parent::setUp();

        $schemaBuilder->hydrateEntityTypeTable('1.9', 'ce');
        $schemaBuilder->hydrateEntityStoreTable('1.9', 'ce');

        $this->persister = new StandardEntityStorePersister(
            $this->getDoctrineConnection(), 
            EntityStoreQueryBuilder::getDefaultTable()
        );
        
        $this->deleter = new EntityStoreDeleter(
            $this->getDoctrineConnection(),
            new EntityStoreQueryBuilder(
                $this->getDoctrineConnection(), 
                EntityStoreQueryBuilder::getDefaultTable(), 
                EntityStoreQueryBuilder::getDefaultFields()
            )
        );
        
        $this->repository = new EntityStoreRepository(
            $this->getDoctrineConnection(), 
            new EntityStoreQueryBuilder(
                $this->getDoctrineConnection(), 
                EntityStoreQueryBuilder::getDefaultTable(), 
                EntityStoreQueryBuilder::getDefaultFields()
            ),
            new StandardEntityStoreFactory()
        );
    }

    protected function tearDown()
    {
        $this->truncateTables();
        parent::tearDown();

        $this->persister = $this->deleter = $this->repository = null;
    }

    public function testRemoveNone()
    {
        $this->persister->initialize();

        $this->assertTableRowCount('eav_entity_store', 9);
    }

    public function testRemoveOne()
    {

        $this->persister->initialize();
        $this->deleter->deleteOneById(2);

        $this->assertTableRowCount('eav_entity_store', 8);
        $this->assertNull($this->repository->findOneById(2));
    }

}
