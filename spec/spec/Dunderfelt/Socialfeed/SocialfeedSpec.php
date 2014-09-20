<?php namespace spec\Dunderfelt\Socialfeed;

use Dunderfelt\Socialfeed\Interfaces\ContentRepository;
use Dunderfelt\Socialfeed\NetworksManager;
use Illuminate\Support\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SocialfeedSpec extends ObjectBehavior
{
    function let(ContentRepository $content, NetworksManager $networks)
    {
        $this->beConstructedWith($content, $networks);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Dunderfelt\Socialfeed\Socialfeed');
    }

    function it_gets_the_next_item()
    {
        $items = $this->next();

        $this->content->getNew()->shouldReturn(['id' => 0]);
        $items->shouldBeArray();
    }
}
