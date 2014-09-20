<?php namespace Dunderfelt\Socialfeed\Networks;


use Dunderfelt\Socialfeed\Interfaces\SocialNetworkInterface;
use Dunderfelt\Socialfeed\Interfaces\ContentRepository;
use Dunderfelt\Socialfeed\Repositories\SocialContentItem;
use Instaphp\Instaphp;

class Instagram implements SocialNetworkInterface {

    /**
     * key/value object for uniform item properties
     * @var
     */
    private $socialContentItem;

    /**
     * data repository
     * @var
     */
    private $content;

    public function __construct(SocialContentItem $socialContentItem, ContentRepository $content)
    {
        $this->socialContentItem = $socialContentItem;
        $this->content = $content;
    }

    /**
     * Updates storage with new items from the service.
     * @return int
     */
    public function update()
    {
        return $this->saveItems(
            $this->getNewItems(
                $this->content->getLastItemTimestamp("instagram", "content_id")
            )
        );
    }

    /**
     * Get (@call apiRequest()) new items from service API.
     * @param $latestTimestamp | Timestamp from db of newest item timestamp.
     * @return array
     */
    public function getNewItems($latestTimestamp)
    {
        return $this->processData( $this->apiRequest($latestTimestamp) );
    }

    public function saveItems($items = null)
    {
        if($items === null || empty($items)) {
            return 0;
        }

        $count = 0;

        foreach($items as $item) {
            $this->content->saveItem($item);
            $count++;
        }

        return $count;
    }

    private function processData($data)
    {
        if($data === null && empty($data))
        {
            return 0;
        }

        $newObjects = [];

        foreach($data as $insta) {
            $newObjects[] = $this->formatData($insta);
        }

        return $newObjects;
    }

    public function apiRequest($latestTimestamp)
    {
        $api = new Instaphp([
            'client_id' => \Config::get("socialfeed::api.instagram.client_id"),
            'client_secret' => \Config::get("socialfeed::api.instagram.client_secret"),
            'redirect_uri' => 'http://social.dev/oauth',
            'scope' => 'comments+likes'
        ]);

        $items = [];

        foreach(\Config::get("socialfeed::hashtags") as $tag)
        {
            $tagBatch = $api->Tags->Recent($tag, [
                'count' => 100,
                'min_tag_id' => $latestTimestamp
            ]);

            if(empty($tagBatch->error))
            {
                foreach($tagBatch->data as $item)
                {
                    $items[] = $item;
                }
            }
        }

        return empty($items) ? null : $items;
    }

    private function formatData($data)
    {
        $item = new $this->socialContentItem;
        $item->content_id = (int) $data["id"];
        $item->content_created = (int) $data["created_time"];
        $item->content_text = $data["caption"]["text"];
        $item->content_creator = $data["user"]["username"];
        $item->content_creator_name = $data["user"]["full_name"];
        $item->shown = 0;
        $item->hashtags = $this->processTags($data["tags"]);
        $item->approved = 0;
        $item->media_url = $this->findMedia($data);
        $item->content_type = "instagram";

        return $item;
    }

    private function processTags($tags)
    {
        $hashString = "";

        foreach($tags as $tag) {
            $hashString .= $tag . "+";
        }

        return $hashString;
    }

    private function findMedia($insta)
    {
        return $insta["type"] === "image" ? $insta["images"]["standard_resolution"]["url"] : $insta["videos"]["standard_resolution"]["url"];
    }
} 