<?php namespace Dunderfelt\Socialfeed;

use Dunderfelt\Socialfeed\Repositories\ContentRepository;

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
        $this->twitter = $twitter;
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function next()
    {
        return $this->decideNext();
    }

    /**
     * @return array
     */
    private function decideNext()
    {
        $items = $this->content->getNew();
        if($items->isEmpty()) $items[] = $this->content->getRandom();
        return $items;
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