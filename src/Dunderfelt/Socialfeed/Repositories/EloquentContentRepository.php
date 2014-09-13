<?php namespace Dunderfelt\Socialfeed\Repositories;


class EloquentContentRepository implements ContentRepository {

    /**
     * @var Content
     */
    private $db;

    public function __construct(Content $db)
    {
        $this->db = $db;
    }

    public function test()
    {
        return "test";
    }

    /**
     * @return array
     */
    public function getNew()
    {
        return $this->db->first();
    }

    /**
     * Content ID of newest item of type.
     * @param string $type
     * @return int
     */
    public function getLastItemTimestamp($type = "twitter")
    {
        return 0;
    }

    public function getRandom()
    {

    }

    public function markAsShown($contentId)
    {

    }

    public function saveItem($data)
    {
        $this->db->firstOrCreate( (array) $data );
    }

} 