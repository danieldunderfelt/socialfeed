<?php

use Dunderfelt\Socialfeed\Repositories\EloquentContentRepository;

class EloquentContentRepositoryTest extends TestCase {

    public function testGetsData()
    {
        $repo = $this->app->make('Dunderfelt\Socialfeed\Repositories\EloquentContentRepository');
        $item = $repo->getNew();
        $this->assertEquals("new", $item);
    }

}
 