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
            return null;
        }

        $saved = [];

        foreach($items as $item) {
            $this->content->save($item);
        }

        return $saved;
    }

    protected function processTags($tags)
    {
        return true;
    }
}