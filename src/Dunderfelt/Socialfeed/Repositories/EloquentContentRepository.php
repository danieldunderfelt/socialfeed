<?php namespace Dunderfelt\Socialfeed\Repositories;

use Dunderfelt\Socialfeed\Interfaces\ContentRepository;

class EloquentContentRepository implements ContentRepository {

    /**
     * @var Content
     */
    public $content;
    /**
     * @var SocialContentItem
     */
    private $socialContentItem;

    public function __construct(Content $content, SocialContentItem $socialContentItem)
    {
        $this->content = $content;
        $this->socialContentItem = $socialContentItem;
    }

    /**
     * @return array
     */
    public function getNew()
    {
        return $this->content->first();
    }

    public function getRandom()
    {

    }

    public function markAsShown($contentId)
    {

    }

    public function save($data)
    {
        $object = $this->content->firstOrCreate( (array) $data );
    }

} 