<?php namespace Dunderfelt\Socialfeed;

use Dunderfelt\Socialfeed\Interfaces\ContentRepository;
use Dunderfelt\Socialfeed\Networks\Twitter;
use Illuminate\Support\Facades\Config;

class Socialfeed {

    /**
     * @var ContentRepository
     */
    private $content;
    /**
     * @var Twitter
     */
    private $twitter;

    public function __construct(ContentRepository $content, Twitter $twitter)
    {
        $this->content = $content;
        $this->twitter = $twitter;
    }

    /**
     * @return mixed
     */
    public function next()
    {
        $this->twitter->update();
        return $this->decideNext();
    }

    /**
     * @return array
     */
    private function decideNext()
    {
        $items = $this->content->getNew();
        if($items === null) $this->content->getRandom();
        return $items->content_text;
    }

    /**
     * Updates the networks with newest items from the API.
     * @return bool
     */
    public function update()
    {
        return $this->twitter->update();
    }
} 