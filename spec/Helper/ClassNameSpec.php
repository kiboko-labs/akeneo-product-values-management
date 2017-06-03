<?php

declare(strict_types=1);

namespace spec\Kiboko\Component\AkeneoProductValues\Helper;

use Kiboko\Component\AkeneoProductValues\Helper\ClassName;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClassNameSpec extends ObjectBehavior
{
    function it_returns_class_name_when_having_namespace()
    {
        $this::extractClass('Foo\Bar')->shouldReturn('Bar');
    }

    function it_returns_namespace_when_having_namespace()
    {
        $this::extractNamespace('Foo\Bar')->shouldReturn('Foo');
    }

    function it_returns_class_name_when_missing_namespace()
    {
        $this::extractClass('Bar')->shouldReturn('Bar');
    }

    function it_returns_null_when_missing_namespace()
    {
        $this::extractNamespace('Bar')->shouldReturn(null);
    }
}
