<?php namespace Dunderfelt\Socialfeed;

use Dunderfelt\Socialfeed\Interfaces\ContentRepository;

class Socialfeed {

    /**
     * @var ContentRepository
     */
    public $content;

    /**
     * @var NetworksManager
     */
    protected $networks;

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
        return $nextItems;
    }

    /**
     * Updates the networks with newest items from the API.
     * @return bool
     */
    public function update()
    {
        return $this->networks->update();
    }

    /**
     * @return array
     */
    private  function decideNext()
    {
        $items = $this->content->getNew();
        if($items === null || $items->isEmpty()) $items[] = $this->content->getRandom();
        return $this->markBatchAsShown($items);
    }

    private function markBatchAsShown($items)
    {
        if(count($items) > 0 && $items !== null) {
            foreach($items as $item) {
                $this->content->markAsShown($item->content_id);
            }
        }

        return $items;
    }
} 