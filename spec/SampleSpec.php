<?php

namespace spec\App;

use App\Sample;
use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;
use stdClass;

/*
 * Object itself:
 *   $this implements the "App\Sample" behavior
 */
class SampleSpec extends ObjectBehavior
{
    /**
     * Run before EACH spec
     */
    public function let(SampleSpec $sample)
    {
        // $this is object itself
        $app_sample = new self();   

        $this->beConstructedWith($sample, []); // no args

        // test for object creation
        // $this->shouldThrow('\InvalidArgumentException')->duringInstantiation();
    }

    /**
     * Run after EACH spec
     */
    public function letGo()
    {
        // cleanup
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Sample::class);
    }

    /**
     * Use typehint or beADoubleOf()
     */
    public function it_stubs_and_mocks(SampleSpec $notImplementedStub, $notImplementedMock)
    {
        // Stub "SampleSpec"
        $notImplementedStub->my_method()->willReturn('hello');
        $this->sampleObject($notImplementedStub);

        // Mock "SampleSpec"
        $notImplementedMock->beADoubleOf('spec\App\SampleSpec');
        $notImplementedMock->my_method('here')->shouldBeCalled();
        $this->sampleObject($notImplementedMock, 'here');

        // Spie after
        $notImplementedMock->my_method('here')->shouldHaveBeenCalled();
    }

    /**
     * Used for it_stubs_and_mocks()
     */
    public function my_method(string $text): string
    {
        return 'modified ' . $text;
    }

    public function it_describes_the_methods_behaviors()
    {
        $this->sampleText("there")->shouldReturn("it is there");
    }

    public function it_skips_behaviors()
    {
        if (!function_exists('strrev')) {
            throw new SkippingException(
                'The strrev function is unavailable'
            );
        }
    }

    // http://www.phpspec.net/en/stable/cookbook/matchers.html
    public function it_have_matchers()
    {
        // identity ===
        $this->sampleText('t')->shouldBe('it is t');
        $this->sampleText('t')->shouldBeEqualTo('it is t');
        $this->sampleText('t')->shouldReturn('it is t');
        $this->sampleText('t')->shouldEqual('it is t');

        // comparison ==
        $this->sampleText('test')->shouldBeLike('it is test');

        // approximate
        // $this->sample(1)->shouldBeApproximately(1.00001, 1.0e-9);

        // throw
        // $this->shouldThrow('\InvalidArgumentException')->duringSampleText('arg');
        // $this->shouldThrow('\InvalidArgumentException')->during('sampleText', ['arg']);
        // $this->shouldTrigger(\E_USER_DEPRECATED, 'The method is deprecated')->duringSampleText('4');

        // type
        $this->shouldHaveType('App\\Sample');
        $this->shouldReturnAnInstanceOf('App\\Sample');
        $this->shouldBeAnInstanceOf('App\\Sample');
        $this->shouldImplement('App\\Sample');

        // Object state matcher is* ou has*
        //      will calls isSample()
        //$this->shouldBeSample();
        //      will calls hasSample()
        //$this->shouldHaveSample();

        // Count matcher (should return \Traversable)
        //$this->sample()->shouldHaveCount(1);

        //scalar
        //$this->sample()->shouldBeString();
        //$this->sample()->shouldBeArray();

        // iterable (\Traversable) that contains with (===)
        // $this->sample()->shouldContain('Jane');
        // $this->sample()->shouldHaveKeyWithValue('leadRole', 'John');
        // $this->sample()->shouldHaveKey('France');

        // same
        // $this->sample()->shouldIterateAs(new \ArrayIterator(['Jane', 'John'])); 
        // $this->sample()->shouldYield(new \ArrayIterator(['Jane', 'John']));

        // with ==
        // $this->sample()->shouldIterateLike(new \ArrayIterator(['Jane Smith', 'John Smith']));
        // $this->sample()->shouldYieldLike(new \ArrayIterator(['Jane Smith', 'John Smith']));

        // with ===
        // $this->sample()->shouldStartIteratingAs(new \ArrayIterator(['Jane Smith']));
        // $this->sample()->shouldStartYielding(new \ArrayIterator(['Jane Smith']));

        // String contains
        // $this->getTitle()->shouldContain('Wizard');
        // $this->getTitle()->shouldStartWith('The Wizard');
        // $this->getTitle()->shouldEndWith('of Oz');
        // $this->getTitle()->shouldMatch('/wizard/i');

        // With custom matchers
        $this->sampleText('y')->shouldSampleCustom('it is y customs');
        // $this->sample()->shouldHaveKey('username');
        // $this->sample()->shouldHaveValue('diegoholiveira');

        /* 
         * If should...method is implemented in object
         */
        //$this->callOnWrappedObject('shouldHandle', array($somethingToHandle));
    }

    // Custom matchers //
    public function getMatchers(): array
    {
        return [
            'sampleCustom' => function($returned, $testedValue) {
                return $testedValue === "$returned customs" ? true : false;
            },
            'haveKey' => function ($subject, $key) {
                if (!array_key_exists($key, $subject)) {
                    throw new FailureException(sprintf(
                        'Message with subject "%s" and key "%s".',
                        $subject, $key
                    ));
                }
                return true;
            },
            'haveValue' => function ($subject, $value) {
                return in_array($value, $subject);
            },
        ];
    }

    // https://github.com/phpspec/prophecy
    public function prophecy_usage()
    {
        $this->prophet = new \Prophecy\Prophet;
        // Mock object
        $mock = $this->prophet->prophesize('App\Sample'); //class ObjectProphecy
        $mock->willExtend('App\Sample');
        $mock->willImplement('SessionHandlerInterface');
        // Mock methods
        $mock->sampleText('qwerty')->willReturn('customized return');

        // Reveal
        $dummy = $mock->reveal(); // Become a "real" object
        // Check
        $this->prophet->checkPredictions();
    }
}
