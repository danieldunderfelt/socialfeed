<?php namespace Dunderfelt\Socialfeed\Networks;


use Carbon\Carbon;
use Dunderfelt\Socialfeed\Interfaces\ContentRepository;
use Dunderfelt\Socialfeed\Interfaces\SocialNetworkInterface;
use Dunderfelt\Socialfeed\Repositories\SocialContentItem;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Illuminate\Support\Facades\Config;

class Twitter extends SocialNetwork implements SocialNetworkInterface {

    /**
     * Type of social network. Ie. Twitter, Instagram aso.
     * (Is "aso" even used as an abbreviation?)
     * @var string
     */
    protected $type = "twitter";
    /**
     * @var
     */
    private $socialContentItem;

    public function __construct(SocialContentItem $socialContentItem)
    {
        $this->socialContentItem = $socialContentItem;
    }

    /**
     * Content ID of newest item of type.
     * @return int
     */
    public function getLastItemTimestamp()
    {
        return 0;
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
        $oauth = new Oauth1(Config::get('socialfeed::api.twitter'));
        $client->getEmitter()->attach($oauth);

        $hashtags = implode(' ', array_map(function($hashtag) {
            return "#" . $hashtag;
        }, Config::get('socialfeed::hashtags')));

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
        $item->content_created = Carbon::createFromTimestamp($tweet['created_at']);
        $item->content_text = $tweet["text"];
        $item->content_creator = $tweet["user"]["screen_name"];
        $item->content_creator_name = $tweet["user"]["name"];
        $item->shown = 0;
        $item->hashtags = $tweet["entities"]["hashtags"];
        $item->approved = 0;
        $item->media_url = $this->findMedia($tweet["entities"]["media"]);

        return $item;
    }

    private function findMedia($media)
    {
        return !empty($media[0]["media_url"]) ? $media[0]["media_url"] : false;
    }
} 