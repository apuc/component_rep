<?php


namespace core\component_manager\lib;


use core\Debug;

class RelationsHandler
{
    public $relations = [];
    public $data = [];

    /**
     * @param $slug
     * @param $version
     * @param int $status
     */
    public function init($slug, $version)
    {
        $data = json_decode(file_get_contents("cloud/modules/$slug/$version/manifest.json"))->relations;

        foreach ($data as $relation)
            if(!in_array($relation, $this->data)) {
                $rel = new Relations();
                $rel->name = $relation->name;
                $rel->version = $relation->version;
                $rel->status = 0;
                array_push($this->relations, $rel);
                array_push($this->data, $relation);
            }
    }

    /**
     * @param $slug
     * @param $version
     * @return array
     */
    public function arr($slug, $version) : array
    {
        $this->init($slug, $version);
        for($i = 0; $i < count($this->relations); $i++)
            foreach ($this->relations as $relation)
                if($relation->status == 0) {
                    $this->init($relation->name, $relation->version);
                    $relation->status = 1;
                }
        $relations = [];
        foreach ($this->relations as $relation)
            array_push($relations, $relation->name);

        return $this->relations;
    }
}