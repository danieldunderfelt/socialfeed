<?php namespace Dunderfelt\Socialfeed;

use Dunderfelt\Socialfeed\Interfaces\ContentRepository;

class Socialfeed {

    /**
     * @var ContentRepository
     */
    private $content;

    public function __construct(ContentRepository $content)
    {
        $this->content = $content;
    }

    public function next()
    {
        return "next";
    }

    private function decideNext()
    {

    }

    public function update()
    {

    }
} 