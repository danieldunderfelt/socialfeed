<?php namespace Dunderfelt\Socialfeed;


use Instantiator\Exception\InvalidArgumentException;

class NetworksManager {

    /**
     * List of networks from config
     * @var array
     */
    public $networks;

    /**
     * List of loaded networks
     * @var array
     */
    public $loadedNetworks;

    public function __construct()
    {
        $this->networks = \Config::get("socialfeed::networks");
    }

    public function loadNetworks()
    {
        foreach($this->networks as $network)
        {
            $this->loadedNetworks[$network] = \App::make($network);
        }
    }

    /**
     *
     * @param bool|string $specific
     * @return array
     */
    public function update($specific = false)
    {
        $updateStatus = [];
        if($specific !== false && in_array($specific, $this->networks))
        {
            $this->loadedNetworks[$specific]->update();
        }
        else if($specific !== false)
        {
            throw new InvalidArgumentException('Specified network is not loaded. Specified: ' . $specific);
        }
        else
        {
            foreach($this->loadedNetworks as $networkName => $network)
            {
                $updateStatus[$networkName] = $network->update();
            }
        }

        return $updateStatus;
    }
} 