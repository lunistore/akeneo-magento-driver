<?php
/**
 * Copyright (c) 2016 Kiboko SAS.
 *
 * @author Grégory Planchat <gregory@kiboko.fr>
 */

namespace Kiboko\Component\MagentoORM\QueryBuilder\Doctrine\ProductUrlRewrite;

use Doctrine\DBAL\Query\QueryBuilder;

interface EnterpriseEditionProductUrlRewriteQueryBuilderInterface
{
    /**
     * @param string $alias
     * @param string $productLinkAlias
     *
     * @return QueryBuilder
     */
    public function createFindOneByProductIdQueryBuilder($alias, $productLinkAlias);
}
