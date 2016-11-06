<?php
/**
 * Copyright (c) 2016 Kiboko SAS
 *
 * @author Grégory Planchat <gregory@kiboko.fr>
 */

namespace spec\Kiboko\Component\MagentoORM\Repository\Doctrine\Magento19;

use Doctrine\DBAL\Connection;
use Kiboko\Component\MagentoORM\Factory\ProductAttributeValueFactoryInterface;
use Kiboko\Component\MagentoORM\QueryBuilder\Doctrine\ProductAttributeQueryBuilderInterface;
use Kiboko\Component\MagentoORM\Repository\Doctrine\Magento19\ProductAttributeRepository;
use PhpSpec\ObjectBehavior;

class ProductAttributeRepositorySpec extends ObjectBehavior
{
    function it_is_initializable(
        Connection $connection,
        ProductAttributeQueryBuilderInterface $queryBuilder,
        ProductAttributeValueFactoryInterface $productAttributeValueFactory
    ) {
        $this->beConstructedWith($connection, $queryBuilder, $productAttributeValueFactory);
        $this->shouldHaveType(ProductAttributeRepository::class);
    }
}
