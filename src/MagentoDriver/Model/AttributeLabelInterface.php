<?php

namespace Kiboko\Component\MagentoDriver\Model;

interface AttributeLabelInterface extends MappableInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getAttributeId();

    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @return string
     */
    public function getValue();
}
