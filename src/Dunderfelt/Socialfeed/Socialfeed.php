<?php namespace Dunderfelt\Socialfeed;

use Dunderfelt\Socialfeed\Repositories\ContentRepository;

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
} 