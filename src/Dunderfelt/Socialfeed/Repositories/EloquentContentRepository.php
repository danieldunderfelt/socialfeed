<?php namespace Dunderfelt\Socialfeed\Repositories;

use Dunderfelt\Socialfeed\Interfaces\ContentRepository;

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
        return "new";
    }

    public function getOld()
    {

    }

    public function markAsShown($contentId)
    {

    }

} 