<?php namespace Dunderfelt\Socialfeed;

use Dunderfelt\Socialfeed\Repositories\ContentRepository;

class Socialfeed {

    /**
     * @var ContentRepository
     */
    private $content;

    /**
     * @var NetworksManager
     */
    private $networks;

    public function __construct(ContentRepository $content, NetworksManager $networks)
    {
        $this->networks = $networks;
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function next()
    {
        $nextItems = $this->decideNext();
        $this->markBatchAsShown($nextItems);
        return $nextItems;
    }

    /**
     * @return array
     */
    private function decideNext()
    {
        $items = $this->content->getNew();
        if($items->isEmpty()) $items[] = $this->content->getRandom();
        return $items;
    }

    private function markBatchAsShown($items)
    {
        if(count($items) > 0) {
            foreach($items as $item) {
                $this->content->markAsShown($item->content_id);
            }
        }
    }

    /**
     * Updates the networks with newest items from the API.
     * @return bool
     */
    public function update()
    {
        return $this->networks->update();
    }
} 