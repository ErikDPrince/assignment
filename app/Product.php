<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;
use App\Product;
use App\ProductCategory;
use App\ProductImage;
use App\Agency;
use App\AgencyProduct;
use DB;

class Product extends Model
{
    //
    protected $table = 'product';
    protected $fillable = ['id','name','user_id','base_price'];

    public function productImage() 
    {
        return $this->hasMany('App\ProductImage');

    }

    public function agency()
    {
        return $this->belongsToMany('App\Agency');
    }

    public function category()
    {
        return $this->belongsToMany('App\Category');
    }

    public function getAllDataByUserId($id, $stt = 0)
    {
        $calculate_rate =
        // table product join agency_product va product khi product.id = agency_product.product_id
        $this->join('agency_product','product.id','=','agency_product.product_id')
        ->select('product_id',DB::raw('MAX(user_id) as user_id'),DB::raw('SUM(quantity) as sum_quantity'),DB::raw('MAX(agency_product.discount_rate) as discount_rate'))
        ->groupBy('product_id');
        
        if ($stt = 0) 
        {
            return $this->joinSub($calculate_rate, 'calculate_rate',function($join) {
                $join->on('calculate_rate.product_id', '=', 'products.id');
                })
                ->join('users', 'users.id', '=', 'products.user_id')
                ->where('users.id', $id)
                ->select('*','products.name as pname')
                ->paginate(12);
        }
        else {
            return $this->joinSub($calculate_rate, 'calculate_rate', function ($join){
                $join->on('calculate_rate.product_id', '=', 'products.id');
                })
                ->join('users', 'users.id', '=', 'products.user_id')
                ->where([['users.id', $id], ['products.id', '<>', $stt]])
                // in the case: stt - product-id
                ->select('*','products.name as pname')
                ->skip(0)->take(20)->get();
            }
        
    }

    public function getDataById($id, $flag = true)
    {
        if ($flag == true)
            return $this->find($id)->toArray();
        return $this->find($id);
    }

    public function deleteDataById($id)
    {
        return $this->find($id)->delete();
    }

    public function getAllData()
    {
        $calculate_rate = 
            $this->join('agency_products', 'products.id', '=', 'agency_products.product_id')
                 ->select('product_id', DB::raw('SUM(quantity) as sum_quantity'), DB::raw('MAX(agency_products.discount_rate) as discount_rate'))
                 ->groupBy('product_id');

        return $this->joinSub($calculate_rate, 'calculate_rate', function ($join){
                $join->on('calculate_rate.product_id', '=', 'products.id');
        })->select('*', 'products.name as pname')->paginate(12);
        
    }

    public function getAllDataByName($name)
    {
        return $this->join('agency_products', 'products.id', '=', 'agency_products.product_id')
            ->join('agencies', 'agencies.id', '=', 'agency_products.agency_id')
            ->join('product_categories', 'product_categories.product_id', '=', 'products.id')
            ->join('categories', 'categories.id', '=', 'product_categories.category_id')
            ->join('users', 'users.id', '=', 'agencies.user_id')
            ->where('products.name', 'like', $name.'%')
            ->orWhere('agencies.name', 'like', $name.'%')
            ->orWhere('categories.name', 'like', $name.'%')
            ->orWhere('users.name', 'like', $name.'%')
            ->select('*', 'products.name as pname', 'categories.name as cname', 'agencies.name as aname', 'users.name as uname')
            ->paginate(12);
    }

    public function getDataByPrice($from, $to)
    {
        $calculate_rate = 
            $this->join('agency_products', 'products.id', '=', 'agency_products.product_id')
                 ->select('product_id', DB::raw('SUM(quantity) as sum_quantity'), DB::raw('MAX(agency_products.discount_rate) as discount_rate'))
                 ->groupBy('product_id');

        return 
        $this->joinSub($calculate_rate, 'calculate_rate', function ($join){
            $join->on('calculate_rate.product_id', '=', 'products.id');
        })->groupBy('id', 'product_id', 'name', 'base_price', 'sum_quantity', 'discount_rate')
        ->havingRaw('base_price - (base_price * discount_rate) / 100 < ?', [$to])
        ->havingRaw('base_price - (base_price * discount_rate)/100 > ?', [$from])
        ->paginate(12);
    }

    public function get12BestSale()
    {
        $calculate_rate = 
            $this->join('agency_products', 'products.id', '=', 'agency_products.product_id')
                 ->select('product_id', DB::raw('SUM(quantity) as sum_quantity'), DB::raw('MAX(agency_products.discount_rate) as discount_rate'))
                 ->groupBy('product_id');

        return $this->joinSub($calculate_rate, 'calculate_rate', function ($join){
                $join->on('calculate_rate.product_id', '=', 'products.id');
        })->orderBy('discount_rate', 'DESC')->skip(0)->take(12)->get();
    }

    public function getAllDataById($id)
    {
        $calculate_rate = 
            $this->join('agency_products', 'products.id', '=', 'agency_products.product_id')
                 ->select('product_id', DB::raw('MAX(user_id) as user_id'), DB::raw('SUM(quantity) as sum_quantity'), DB::raw('MAX(agency_products.discount_rate) as discount_rate'))
                 ->groupBy('product_id');

        return $this->joinSub($calculate_rate, 'calculate_rate', function ($join){
                $join->on('calculate_rate.product_id', '=', 'products.id');
        })
        ->join('users', 'users.id', '=', 'products.user_id')
        ->where('products.id', $id)
        ->select('*','products.name as pname')
        ->get();
    }

    public function getCateById($id)
    {

        //return an array
        return $this->join('product_categories', 'product_categories.product_id', '=', 'products.id')->where('product_categories.product_id', $id)->select('category_id')->get()->toArray();
    }

    public function getDataSameCate($cate_id_arr)
    {
        return $this->join('product_categories', 'product_categories.product_id', '=', 'products.id')->whereIn('product_categories.category_id', $cate_id_arr)->select('product_id')->get()->toArray();
    }

    public function getAllDataInArrayProductId($product_id_arr)
    {
        $calculate_rate = 
            $this->join('agency_products', 'products.id', '=', 'agency_products.product_id')
                ->whereIn('products.id', $product_id_arr)
                 ->select('product_id', DB::raw('SUM(quantity) as sum_quantity'), DB::raw('MAX(agency_products.discount_rate) as discount_rate'))
                 ->groupBy('product_id');

        return $this->joinSub($calculate_rate, 'calculate_rate', function ($join){
                $join->on('calculate_rate.product_id', '=', 'products.id');
        })->select('*', 'products.name as pname')->skip(0)->take(20)->get();
    }

    public function getUserId($product_id)
    {
        return $this->select('user_id')->where('id', $product_id)->get();
    }

    

}
