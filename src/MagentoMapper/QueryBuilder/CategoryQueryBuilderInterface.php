<?php

namespace Luni\Component\MagentoMapper\QueryBuilder;

use Doctrine\DBAL\Query\QueryBuilder;

interface CategoryQueryBuilderInterface
{
    /**
     * @param string $alias
     * @return QueryBuilder
     */
    public function createFindOneByCodeQueryBuilder($alias);

    /**
     * @param string $alias
     * @return QueryBuilder
     */
    public function createFindAllQueryBuilder($alias);
}