<?php namespace Dunderfelt\Socialfeed\Repositories;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Content extends \Eloquent {
    use SoftDeletingTrait;

    protected $table = 'social_feed_content';
    protected $guarded = ['id'];

}