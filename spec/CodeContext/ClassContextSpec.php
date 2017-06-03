<?php

declare(strict_types=1);

namespace spec\Kiboko\Component\AkeneoProductValues\CodeContext;

use Kiboko\Component\AkeneoProductValues\CodeContext\ClassContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClassContextSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(
            \stdClass::class,
            null
        );

        $this->shouldHaveType(ClassContext::class);
    }

    function it_has_a_class_name()
    {
        $this->beConstructedWith(
            \stdClass::class,
            null
        );

        $this->getClassName()->shouldReturn(\stdClass::class);
    }

    function it_has_a_parent_class(
        ClassReferenceContext $parent
    ) {
        $this->beConstructedWith(
            \stdClass::class,
            $parent
        );

        $this->getParentClass()->shouldReturn($parent);
    }

    function it_has_interfaces(
        ClassReferenceContext $parent,
        ClassReferenceContext $firstInterface,
        ClassReferenceContext $secondInterface,
        ClassReferenceContext $firstTrait,
        ClassReferenceContext $secondTrait
    ) {
        $this->beConstructedWith(
            \stdClass::class,
            $parent,
            [
                $firstInterface,
                $secondInterface,
            ],
            [
                $firstTrait,
                $secondTrait,
            ]
        );

        $this->getImplementedInterfaces()->shouldBeEqualTo(
            [
                $firstInterface,
                $secondInterface,
            ]
        );
    }

    function it_has_traits(
        ClassReferenceContext $parent,
        ClassReferenceContext $firstInterface,
        ClassReferenceContext $secondInterface,
        ClassReferenceContext $firstTrait,
        ClassReferenceContext $secondTrait
    ) {
        $this->beConstructedWith(
            \stdClass::class,
            $parent,
            [
                $firstInterface,
                $secondInterface,
            ],
            [
                $firstTrait,
                $secondTrait,
            ]
        );

        $this->getUsedTraits()->shouldBeEqualTo(
            [
                $firstTrait,
                $secondTrait,
            ]
        );
    }
}
