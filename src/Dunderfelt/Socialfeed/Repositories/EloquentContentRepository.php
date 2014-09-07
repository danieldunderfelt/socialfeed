<?php namespace Dunderfelt\Socialfeed\Repositories;

class EloquentContentRepository implements ContentRepository {

    /**
     * @var Content
     */
    private $content;

    public function __construct(Content $content)
    {
        $this->content = $content;
    }

    public function getNew()
    {

    }

    public function getOld()
    {

    }
} 