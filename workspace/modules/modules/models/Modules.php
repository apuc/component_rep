<?php


namespace workspace\modules\modules\models;


use Illuminate\Database\Eloquent\Model;
use workspace\modules\modules\requests\ModulesSearchRequest;

class Modules extends Model
{
    protected $table = "modules";

    public $fillable = ['name', 'version', 'type', 'description', 'user_id', 'created_at', 'updated_at'];
    /**
     * @var mixed
     */

    public function _save($data = '')
    {
        $this->name = ($data) ? $data->name : $_POST["name"];
        $this->version = ($data) ? $data->version : $_POST["version"];
        $this->type = ($data) ? $data->type : $_POST["type"];
        $this->description = ($data) ? $data->description : $_POST["description"];
        $this->user_id = null;

        $this->save();
    }

    public function __save($data)
    {
        $this->name = $data->name;
        $this->version = $data->version;
        $this->type = $data->type;
        $this->description = $data->description;
        $this->user_id = null;

        $this->save();
    }

    /**
     * @param ModulesSearchRequest $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function search(ModulesSearchRequest $request)
    {
        $query = self::query();

        if ($request->id)
            $query->where('id', 'LIKE', "%$request->id%");

        if ($request->name)
            $query->where('name', 'LIKE', "%$request->name%");

        if ($request->version)
            $query->where('version', 'LIKE', "%$request->version%");

        if ($request->type)
            $query->where('type', 'LIKE', "%$request->type%");

        if ($request->description)
            $query->where('description', 'LIKE', "%$request->description%");

        if ($request->user_id)
            $query->where('user_id', 'LIKE', "%$request->user_id%");

        if ($request->created_at)
            $query->where('created_at', 'LIKE', "%$request->created_at%");

        if ($request->updated_at)
            $query->where('updated_at', 'LIKE', "%$request->updated_at%");

        return $query->get();
    }
}