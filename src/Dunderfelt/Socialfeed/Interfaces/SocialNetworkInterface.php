<?php
namespace Dunderfelt\Socialfeed\Interfaces;

interface SocialNetworkInterface {
    public function update();
    public function getNewItems($latestTimestamp);
    public function saveItems($items);
    public function apiRequest($latestTimestamp);
}