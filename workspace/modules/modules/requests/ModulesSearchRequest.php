<?php


namespace workspace\modules\modules\requests;


use core\RequestSearch;

/**
 * Class ModulesSearchRequest
 * @package workspace\modules\modules\requests
 *
 * @property int id
 * @property varchar(255) name
 * @property varchar(255) version
 * @property varchar(255) type
 * @property text description
 * @property int user_id
 * @property timestamp created_at
 * @property timestamp updated_at
 */

class ModulesSearchRequest extends RequestSearch
{
    public $id;
    public $name;
    public $version;
    public $type;
    public $description;
    public $user_id;
    public $created_at;
    public $updated_at;


    public function rules()
    {
        return [];
    }
}