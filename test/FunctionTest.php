<?php
namespace Test;

use App\Hello;
use phpmock\prophecy\PHPProphet;
use PHPUnit\Framework\TestCase;

class FunctionTest extends TestCase
{
    public function testOriginal()
    {
        $this->assertSame('I am origin a', b());
    }

    /**
     * @runInSeparateProcess
     */
    public function testCannotBeMockedLikeThat()
    {
        $phpProphet = new PHPProphet();

        $prophecy = $phpProphet->prophesize(__NAMESPACE__);
        $prophecy->a()->willReturn('HELLO')->shouldNotBeCalled();
        $prophecy->reveal();

        $this->assertSame('I am origin a', b());
    }

    /**
     * @runInSeparateProcess
     */
    public function testMockA()
    {
        $phpProphet = new PHPProphet();

        // Mock calls of function a() in namespace App
        $prophecy = $phpProphet->prophesize('App');
        $prophecy->a()->willReturn('Hello');
        $prophecy->reveal();

        $hello = new Hello();
        $this->assertSame('Hello', $hello->a());
        $this->assertSame('I am origin a', $hello->b());

        $this->assertSame('I am origin a', a());
        $this->assertSame('I am origin a', b());
    }

    /**
     * @runInSeparateProcess
     */
    public function testMockB()
    {
        $phpProphet = new PHPProphet();

        // Mocks calls of function b() in namespace App
        $prophecy = $phpProphet->prophesize('App');
        $prophecy->b()->willReturn('Bravo!');
        $prophecy->reveal();

        $hello = new Hello();
        $this->assertSame('I am origin a', $hello->a());
        $this->assertSame('Bravo!', $hello->b());

        $this->assertSame('I am origin a', a());
        $this->assertSame('I am origin a', b());
    }
}
