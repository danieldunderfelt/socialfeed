<?php namespace Dunderfelt\Socialfeed\Repositories;

interface ContentRepository {
    public function getNew();
    public function getRandom();
    public function markAsShown($contentId);
    public function saveItem($data);
}