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

    /**
     * @return array
     */
    public function getNew()
    {
        $query = $this->db->where('shown', 1);

        $query = $this->filterApproved($query);
        $query = $this->preferTags($query);

        return $query->orderBy("content_created", "asc")
               ->take(\Config::get("socialfeed::show"))->get();
    }

    public function filterApproved($query)
    {
        if(\Config::get("socialfeed::require_approval"))
            $query = $query->where('approved', 1);

        return $query;
    }

    /**
     * Epic query ahead...
     * @param $query
     * @return mixed
     */
    public function preferTags($query)
    {
        if( ! empty(\Config::get("socialfeed::preferred_hashtags")))
        {
            $origQuery = clone $query;

            $query = $query->where(function($query) {
                $i = 0;
                foreach(\Config::get("socialfeed::hashtags") as $hashtag)
                {
                    $keyword = $i === 0 ? "where" : "orWhere";
                    if(in_array($hashtag, \Config::get("socialfeed::preferred_hashtags"))) {
                        $query->$keyword('hashtags', 'LIKE', '%' . $hashtag . '%');
                        $i++;
                    }
                }
            });

            if($query->get()->isEmpty()) {
                $query = $origQuery;
            }
        }

        return $query;
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
        $query = $this->filterApproved($this->db);
        $collection = $query->get();
        return $collection->random(\Config::get('socialfeed::show'));
    }

    public function markAsShown($contentId)
    {

    }

    public function saveItem($data)
    {
        $this->db->firstOrCreate( (array) $data );
    }

} 