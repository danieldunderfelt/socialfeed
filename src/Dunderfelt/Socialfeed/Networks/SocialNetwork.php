<?php namespace Dunderfelt\Socialfeed\Networks;


use Dunderfelt\Socialfeed\Interfaces\ContentRepository;

abstract class SocialNetwork {

    /**
     * @var ContentRepository
     */
    protected $content;

    public function __construct(ContentRepository $content)
    {
        $this->content = $content;
    }

    public function update()
    {
        return $this->saveItems(
            $this->getNewItems(
                $this->getLastItemTimestamp()
            )
        );
    }

    protected function saveItems($items = null)
    {
        if(!$items) {
            return false;
        }

        $count = 0;

        foreach($items as $item) {
            $this->content->save($item);
            $count++;
        }

        return $count;
    }

    protected function processTags($tags)
    {
        $hashString = "";

        foreach($tags as $tag) {
            $hashString .= $tag['text'] . "+";
        }

        return $hashString;
    }
}