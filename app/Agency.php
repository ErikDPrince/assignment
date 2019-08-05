<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    //
    protected $table = 'agencies';
    protected $fillable = ['id','user_id','name','address'];

    public function user() {
        return $this->belongTo('App\User');
    }

     public function agencyImage() {
         return $this->hasMany('App\AgencyImage');
    }

    public function product() {
        return $this->belongsToMany('App\Product');
    }
    //get all data
    public function getAllDataById($id) {
        return $this->select('*')->where('user_id',$id)->get();
    }

    public function addData($user_id, $data) {
        $this->name     = $data->name;
        $this->address  = $data->address;
        $this->user_id  = $user_id;
        $this->save();
        return $this->id;
 
    }
    //get data by id
    public function getDataById($id, $flag = true) {
        if ($flag == true)
            return $this->find($id)->toArray();
        return $this->find($id);
    }
    public function updateById($id,$data) 
    {
        $agency = $this->find($id);
        $agency->name =$data->name;
        $agency->address =$data->address;
        $agency->save();
    }

    public function deleteById($id) 
    {
        return $this->find($id)->delete();
    }
}
