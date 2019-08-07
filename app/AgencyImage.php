<?php

namespace App;
namespace App\Agency;
use Illuminate\Database\Eloquent\Model;

class AgencyImage extends Model
{
    //
    protected $table = 'agency_image';
    protected $fillable = ['id','agency_image','image'];

    public function agency() {
        return $this->belongsTo('App\Agency');
    }

    public function getDataByAgencyId($id ,$flag = true) {
        if ($flag == true)
            return $this->select('*')->where('agency_id',$id)->get()->toArray();
        return $this->select('*')->where('agency_id',$id)->get();
    }

    public function addData($agency_id,$name) {
        $this->$agency_id = $agency_id;
        $this->$name      = $image;
        $this->save();
    }

    public function deleteDataById($id,$hasID =true)
    {
        if ($hasId == true)
        {
            $img =$this->find($id);
            $result = $img->image;
            $image->delete();
            return $result;            
        }
        return $this->delete();
    }
}
