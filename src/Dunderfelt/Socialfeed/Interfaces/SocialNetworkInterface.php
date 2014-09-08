<?php
namespace Dunderfelt\Socialfeed\Interfaces;

interface SocialNetworkInterface {
    public function getLastItemTimestamp();
    public function getNewItems($latestTimestamp);
    public function apiRequest();
}