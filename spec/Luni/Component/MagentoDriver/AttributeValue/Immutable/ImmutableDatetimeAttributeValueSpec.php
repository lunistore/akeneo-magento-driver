<?php

namespace spec\Luni\Component\MagentoDriver\AttributeValue\Immutable;

use Luni\Component\MagentoDriver\Attribute\AttributeInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ImmutableDatetimeAttributeValueSpec extends ObjectBehavior
{
    public function getMatchers()
    {
        return [
            'beTimestamp' => function(\DateTimeInterface $subject, $timestamp) {
                return $subject->getTimestamp() === $timestamp;
            },
        ];
    }

    function it_is_an_ImmutableAttributeValueInterface(AttributeInterface $attribute, \DateTimeImmutable $datetime)
    {
        $this->beConstructedWith($attribute, $datetime);
        $this->shouldImplement('Luni\Component\MagentoDriver\AttributeValue\Immutable\ImmutableAttributeValueInterface');
    }

    function it_should_contain_immutable_datetime_value(AttributeInterface $attribute, \DateTimeImmutable $datetime)
    {
        $this->beConstructedWith($attribute, $datetime);

        $datetime->getTimestamp()->willReturn(1234567890);

        $this->getValue()
            ->shouldReturnAnInstanceOf('DateTimeImmutable')
        ;

        $this->getValue()
            ->shouldBeTimestamp(1234567890)
        ;
    }
}
