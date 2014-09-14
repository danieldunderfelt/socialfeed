<?php namespace Dunderfelt\Socialfeed\Repositories;


use Instantiator\Exception\InvalidArgumentException;

class EloquentContentRepository implements ContentRepository {

    /**
     * Eloquent model
     * @var Content
     */
    private $db;

    public function __construct(Content $db)
    {
        $this->db = $db;
    }

    /**
     * Gets new item from database and attaches filters.
     * @return array
     */
    public function getNew()
    {
        $query = $this->db->where('shown', 0);

        $query = $this->filterApproved($query);
        $query = $this->preferTags($query);

        return $query->orderBy("content_created", "asc")
               ->take(\Config::get("socialfeed::show"))->get();
    }

    /**
     * Filters out unapproved items if set in the config.
     * @param $query
     * @return Fluent database query
     */
    public function filterApproved($query)
    {
        if(\Config::get("socialfeed::require_approval"))
            $query = $query->where('approved', 1);

        return $query;
    }

    /**
     * If you want some of the hashtags to jump skip ahead in the queue, set them in the config.
     * Epic query ahead...
     * @param $query
     * @return mixed
     */
    public function preferTags($query)
    {
        /* First of all, let's see if we want to prefer some hashtags */
        if( ! empty(\Config::get("socialfeed::preferred_hashtags")))
        {
            /* Save original query if this thing doesn't produce anything */
            $origQuery = clone $query;

            $query = $query->where(function($query) {
                $i = 0;
                /* Get hashtags from config and see if they are preferred */
                foreach(\Config::get("socialfeed::hashtags") as $hashtag)
                {
                    /* The first where can't be OR, because then it would OR between shown and this hashtag... The second SHOULD be OR of course. */
                    $keyword = $i === 0 ? "where" : "orWhere";
                    if(in_array($hashtag, \Config::get("socialfeed::preferred_hashtags"))) {
                        /* Hashtags are in a string, so we use LIKE to search through it */
                        $query->$keyword('hashtags', 'LIKE', '%' . $hashtag . '%');
                        /* Only increment if we did anything, for the sake of the where/orWhere thing above */
                        $i++;
                    }
                }
            });

            /* If there are no unshown items with the preferred hashtags, this was all in vain and we go back to the original query. */
            if($query->get()->isEmpty()) {
                $query = $origQuery;
            }
        }

        return $query;
    }

    /**
     * Content ID or content timestamp of newest item of type.
     * @param string $type
     * @param string $getCol
     * @return int
     */
    public function getLastItemTimestamp($type = "twitter", $getCol = "content_id")
    {
        /* Improvised ENUM of 'content_id' and 'content_created' */
        if( ! in_array($getCol, ["content_id", "content_created"])) {
            throw new InvalidArgumentException(
                'Invalid $getCol argument value, only content_id or content_created should be supplied to getLastItemTimestamp. Supplied argument was ' . $getCol
            );
        }
        /* No use looking for things that aren't there... */
        if( ! in_array($type, \Config::get("socialfeed::networks"))) {
            throw new InvalidArgumentException(
                'Invalid network type, please input only names of loaded networks. Supplied network type was ' . $type
            );
        }
        $item = $this->db->where("content_type", $type)->orderBy($getCol, "desc")->first();
        return $item === null ? 0 : (int) $item->$getCol;
    }

    /**
     * Returns random item from database, optionally (set in config) filtering out unapproved items.
     * @return Illuminate collection (or db row if show is 1)
     */
    public function getRandom()
    {
        $query = $this->filterApproved($this->db);
        $collection = $query->get();
        return $collection->random(\Config::get('socialfeed::show'));
    }

    /**
     * Marks the item with the supplied content_id (id from service API) as shown.
     * @param $contentId
     * @return bool|int
     */
    public function markAsShown($contentId)
    {
        $item = $this->db->where("content_id", $contentId)->update(["shown" => 1]);
        return $item;
    }

    /**
     * Saves item to database if it doesn't already exists.
     * @param $data stdClass
     */
    public function saveItem($data)
    {
        $this->db->firstOrCreate( (array) $data );
    }

} 