<?php

namespace Luni\Component\MagentoDriver\Repository\Doctrine;

use Doctrine\DBAL\Connection;
use Luni\Component\MagentoDriver\Exception\DatabaseFetchingFailureException;
use Luni\Component\MagentoDriver\Factory\AttributeLabelFactoryInterface;
use Luni\Component\MagentoDriver\Model\AttributeLabelInterface;
use Luni\Component\MagentoDriver\QueryBuilder\Doctrine\AttributeLabelQueryBuilderInterface;
use Luni\Component\MagentoDriver\Repository\AttributeLabelRepositoryInterface;

class AttributeLabelRepository implements AttributeLabelRepositoryInterface
{
    /**
     * @var AttributeLabelQueryBuilderInterface
     */
    private $queryBuilder;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var AttributeLabelFactoryInterface
     */
    private $attributeLabelFactory;

    /**
     * @param Connection                          $connection
     * @param AttributeLabelQueryBuilderInterface $queryBuilder
     * @param AttributeLabelFactoryInterface      $attributeLabelFactory
     */
    public function __construct(
        Connection $connection,
        AttributeLabelQueryBuilderInterface $queryBuilder,
        AttributeLabelFactoryInterface $attributeLabelFactory
    ) {
        $this->connection = $connection;
        $this->queryBuilder = $queryBuilder;
        $this->attributeLabelFactory = $attributeLabelFactory;
    }

    /**
     * @param array $options
     *
     * @return AttributeLabelInterface
     */
    protected function createNewAttributeLabelInstanceFromDatabase(array $options)
    {
        return $this->attributeLabelFactory->buildNew($options);
    }

    /**
     * @param int $id
     *
     * @return AttributeLabelInterface
     */
    public function findOneById($id)
    {
        $query = $this->queryBuilder->createFindOneByIdQueryBuilder('eav_l');

        $statement = $this->connection->prepare($query);
        if (!$statement->execute([$id])) {
            throw new DatabaseFetchingFailureException();
        }

        if ($statement->rowCount() < 1) {
            return;
        }

        $options = $statement->fetch();

        return $this->createNewAttributeLabelInstanceFromDatabase($options);
    }

}
