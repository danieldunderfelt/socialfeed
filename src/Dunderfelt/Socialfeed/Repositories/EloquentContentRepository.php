<?php namespace Dunderfelt\Socialfeed\Repositories;

use Dunderfelt\Socialfeed\Interfaces\ContentRepository;

class EloquentContentRepository implements ContentRepository {

    /**
     * @var Content
     */
    public $content;

    public function __construct(Content $content)
    {
        $this->content = $content;
    }

    /**
     * @return array
     */
    public function getNew()
    {
        return [
            "new" => "new",
        ];
    }

    public function getRandom()
    {

    }

    public function markAsShown($contentId)
    {

    }

    public function save($data)
    {
        if(!empty($this->content->find($data))) {
            continue;
        }

        $object = new $this->content;

        $object->content_id = (int) $data->id;
        $object->content_created = (int) $data->created_time;
        $object->content_text = $data->caption->text;
        $object->content_creator = $data->user->username;
        $object->content_type = "instagram";
        $object->instagram_url = $data->images->standard_resolution->url;
        $object->shown = 0;
        $object->hashtags = $this->combineHashtags($data->tags);

        $saved[] = $object->save();
    }

} 