<?php namespace Dunderfelt\Socialfeed\Repositories;

interface ContentRepository {
    public function getNew();

    public function getOld();
}