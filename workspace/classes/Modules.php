<?php

namespace workspace\classes;


use Illuminate\Support\Collection;

class Modules
{
    public $name;
    public $version;
    public $description;
    public $type;
    public $status;
    public $localStatus;
    public $relations;
    public $created_at;
    public $updated_at;

    /**
     * @param string $name
     * @param string $version
     * @param string $description
     * @param string $status
     * @param string $localStatus
     * @param string $type
     * @param $relations
     * @param string $created_at
     * @param string $updated_at
     */
    public function __construct(string $name, string $version, string $description, string $status, string $localStatus,
                                string $type, $relations, string $created_at, string $updated_at)
    {
        $this->name = $name;
        $this->version = $version;
        $this->description = $description;
        $this->status = $status;
        $this->localStatus = $localStatus;
        $this->type = $type;
        $this->relations = $relations;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    /**
     * @param ModulesSearchRequest $request
     * @param array $modules
     * @return Collection
     */
    public static function search(ModulesSearchRequest $request, array $modules)
    {
        $all = array();
        $hasRequest = false;

        if ($request->name) {
            $hasRequest = true;
            foreach ($modules as $value)
                if (stristr($value->name, $request->name))
                    array_push($all, $value);
        }
        if ($request->version) {
            $hasRequest = true;
            foreach ($modules as $value)
                if (stristr($value->version, $request->version))
                    array_push($all, $value);
        }
        if ($request->description) {
            $hasRequest = true;
            foreach ($modules as $value)
                if (stristr($value->description, $request->description))
                    array_push($all, $value);
        }
        if ($request->status) {
            $hasRequest = true;
            foreach ($modules as $value)
                if (stristr($value->status, $request->status))
                    array_push($all, $value);
        }

        if(empty($all) && !$hasRequest)
            $all = $modules;

        return new Collection($all);
    }
}