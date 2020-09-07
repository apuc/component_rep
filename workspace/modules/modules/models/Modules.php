<?php


namespace workspace\modules\modules\models;


use Illuminate\Database\Eloquent\Model;
use workspace\modules\modules\requests\ModulesSearchRequest;

class Modules extends Model
{
    protected $table = "modules";

    public $fillable = ['name', 'version', 'type', 'description', 'user_id', 'created_at', 'updated_at'];

    public function _save()
    {
        $this->name = $_POST["name"];
        $this->version = $_POST["version"];
        $this->type = $_POST["type"];
        $this->description = $_POST["description"];
        $this->user_id = $_POST["user_id"];

        $this->save();
    }

    public function __save($data)
    {
        $this->name = $data['manifest']->name;
        $this->version = $data['manifest']->version;
        $this->type = $data['manifest']->type;
        $this->description = $data['manifest']->description;
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

        if($request->id)
            $query->where('id', 'LIKE', "%$request->id%");

        if($request->name)
            $query->where('name', 'LIKE', "%$request->name%");

        if($request->version)
            $query->where('version', 'LIKE', "%$request->version%");

        if($request->type)
            $query->where('type', 'LIKE', "%$request->type%");

        if($request->description)
            $query->where('description', 'LIKE', "%$request->description%");

        if($request->user_id)
            $query->where('user_id', 'LIKE', "%$request->user_id%");

        if($request->created_at)
            $query->where('created_at', 'LIKE', "%$request->created_at%");

        if($request->updated_at)
            $query->where('updated_at', 'LIKE', "%$request->updated_at%");


        return $query->get();
    }
}