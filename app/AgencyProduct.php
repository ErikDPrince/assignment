<?php

namespace App;
namespace App\Product;
use Illuminate\Database\Eloquent\Model;

class AgencyProduct extends Model
{
    //
    protected $table = 'agency_product';
    protected $fillable = ['id','agency_id','agency_product','quantity','discount_rate'];

    public function product() {
        return $this->belongsToMany('App\Product');
    }

    public function addData($agency_id,$product_id,$quantity = 0, $discount_rate = 0) 
    {
        $this->agency_id = $agency_id;
        $this->product_id = $product_id;
        $this->quantity =$quantity;
        $this->discount_rate = $discount_rate;
        $this->save(); 
    }

    public function getDataByProductId($id,$flag =true) 
    {
        if ($flag == true) {
            return $this->select('*')->where('product_id',$id)->orderby('discount_rate','DESC')->get()->toArray();
        return $this->select('*')->select('*')->where('product_id',$id)->orderby('discount_rate','DESC')->get();
        }
    }

    public function getDataByIdAndProductId($id, $product_id) 
    {
        return $this->select('*')->where('agency_id',$id)
                                 ->where('product_id',$product_id)
                                 ->first();                   
    }
        //
    public function getDataByProductIdAndAgencyId($product_id,$agency_id)
    {
        return $this->select('*')->where([['product_id',$product_id],['agency_id',$agency_id]])
                                 ->first();
    }

    public function deleteDataById($id)
    {
        return $this->find($id)->delete();
    }

    public function deleteDataByArtrId($id,$attr ='product_id') 
    {
        return $this->where($attr, $id)->delete();
    }

}
