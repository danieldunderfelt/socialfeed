<?php namespace Dunderfelt\Socialfeed\Interfaces;

interface ContentRepository {
    public function getNew();
    public function getOld();
    public function markAsShown($contentId);
}