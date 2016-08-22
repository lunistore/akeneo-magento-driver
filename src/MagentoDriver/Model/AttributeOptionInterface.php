<?php

namespace Kiboko\Component\MagentoDriver\Model;

interface AttributeOptionInterface
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
    public function getSortOrder();

    /**
     * @param AttributeOptionValueInterface $optionValue
     */
    public function addValue(AttributeOptionValueInterface $optionValue);

    /**
     * @param AttributeOptionValueInterface[] $optionValues
     */
    public function setValues(array $optionValues);

    /**
     * @return AttributeOptionValueInterface[]
     */
    public function getValues();
}
