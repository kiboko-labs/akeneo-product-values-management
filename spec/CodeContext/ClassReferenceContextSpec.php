<?php

declare(strict_types=1);

namespace spec\Kiboko\Component\AkeneoProductValues\CodeContext;

use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use MyProject\Proxies\__CG__\stdClass;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClassReferenceContextSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(
            \stdClass::class,
            'MyAlias'
        );

        $this->shouldHaveType(ClassReferenceContext::class);
    }

    function it_has_a_class_name()
    {
        $this->beConstructedWith(
            \stdClass::class,
            'MyAlias'
        );

        $this->getClassName()->shouldReturn(\stdClass::class);
    }

    function it_must_have_a_string_class_name()
    {
        $this->beConstructedWith(
            null, null
        );

        $this->shouldThrow(\TypeError::class)->duringInstantiation();
    }

    function it_must_have_a_string_alias()
    {
        $this->beConstructedWith(
            'Foo', new \stdClass()
        );

        $this->shouldThrow(\Error::class)->duringInstantiation();
    }

    function it_has_an_alias()
    {
        $this->beConstructedWith(
            \stdClass::class,
            'MyAlias'
        );

        $this->getAlias()->shouldReturn('MyAlias');
    }

    function it_has_no_alias()
    {
        $this->beConstructedWith(
            \stdClass::class
        );

        $this->getAlias()->shouldReturn(null);
    }
}
