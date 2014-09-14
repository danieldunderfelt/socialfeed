<?php namespace Dunderfelt\Socialfeed;


use Dunderfelt\Socialfeed\Interfaces\SocialNetworkInterface;
use Dunderfelt\Socialfeed\Repositories\ContentRepository;
use Dunderfelt\Socialfeed\Repositories\SocialContentItem;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

class Twitter implements SocialNetworkInterface {

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
                $this->content->getLastItemTimestamp("twitter", "content_id")
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
        if(empty($data['statuses'])) {
            return null;
        }

        $newObjects = [];

        foreach($data['statuses'] as $tweet) {
            $newObjects[] = $this->formatData($tweet);
        }

        return $newObjects;
    }

    public function apiRequest($latestTimestamp)
    {
        $client = new Client(['base_url' => 'https://api.twitter.com', 'defaults' => ['auth' => 'oauth']]);
        $oauth = new Oauth1(\Config::get('socialfeed::api.twitter'));
        $client->getEmitter()->attach($oauth);

        $hashtags = implode(' OR ', array_map(function($hashtag) {
            return "#" . $hashtag;
        }, \Config::get('socialfeed::hashtags')));

        $res = $client->get('1.1/search/tweets.json', [
            'query' => [
                'q' => $hashtags,
                'since_id' => $latestTimestamp,
                'result_type' => 'recent',
                'count' => '100'
            ]
        ])->json();

        return $res;
    }

    private function formatData($tweet)
    {
        $item = new $this->socialContentItem;
        $item->content_id = $tweet['id_str'];
        $item->content_created = strtotime($tweet['created_at']);
        $item->content_text = $tweet["text"];
        $item->content_creator = $tweet["user"]["screen_name"];
        $item->content_creator_name = $tweet["user"]["name"];
        $item->shown = 0;
        $item->hashtags = $this->processTags($tweet["entities"]["hashtags"]);
        $item->approved = 0;
        $item->media_url = $this->findMedia($tweet["entities"]);
        $item->content_type = "twitter";

        return $item;
    }

    private function processTags($tags)
    {
        $hashString = "";

        foreach($tags as $tag) {
            $hashString .= $tag['text'] . "+";
        }

        return $hashString;
    }

    private function findMedia($entities)
    {
        return isset($entities['media']) ? $entities['media'][0]["media_url"] : null;
    }
} 