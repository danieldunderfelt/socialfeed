<?php namespace Dunderfelt\Socialfeed\Networks;


use Dunderfelt\Socialfeed\Interfaces\ContentRepository;
use Dunderfelt\Socialfeed\Interfaces\SocialNetworkInterface;

class Instagram extends SocialNetwork implements SocialNetworkInterface {

    /**
     * Type of social network. Ie. Twitter, Instagram aso.
     * (Is "aso" even used as an abbreviation?)
     * @var string
     */
    protected $type = "instagram";

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
        return [
            "test" => "test",
            "test" => "test",
            "test" => "test"
        ];
    }

    /**
     * Processes the data for doubles and other operations.
     * @param $items
     * @return
     */
    private function processData($items)
    {
        return $items;
    }

    public function apiRequest()
    {

    }
} 