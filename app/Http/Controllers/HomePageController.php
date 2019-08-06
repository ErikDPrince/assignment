<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductCategory;
use App\AgencyProduct;
use App\ProductImage;
use App\Category;
use App\Product;
use App\Agency;
class HomePageController extends Controller
{
    public function index()
    {
        $product = new Product;
        $product = $product->getAllData();

        $size = count($product);

        for ($i =0;$i < $size ; $i++) {
            $this->groupImg($product,$i);
            $this->groupAgency($product, $i);
        }

        // top sales
        $topsales = new Product;
        $topsales = $this->getTopSale();

        return view('home')->with([
            'product' => $product,
            'topsales'=> $topsales
        ]);
        
    }

    public function getRealPrice($base_price ,$rate) {
        return $base_price - ($base_price * rate)/100;
    }

    public function groupImg($product, $size)
    {
        for ($i =0; $i <$size; $i++) {
            $id =$product[$i]['product_id'];
            $product_img = new ProductImage;
            $product_img = $product_img->getAllDataById($id);
            $product[$i]['image'] = $product_img;

        }
    }

    public function groupAgency($product , $size)
    {
        for ($i=0; $i< $size; $i++) {
            $id = $product[$i]['product_id'];
            $agency_product = new AgencyProduct;
            $agency_product = $agency_product->getDataByProductId($id);
            $product[$i]['agen_pro'] = $agency_product;
        }
    }

    public function searchByPriceSlide($min ,$max)
    {
        $product = new Product;
        $product = $product->getDataByPrice($min,$max);

        $size = count($product);

        for ($i=0; $i <$size; $i++)
        {
            $this->groupImg($product,$i);
            $this->groupAgency($product,$i);
        }

        $topsales = new Product;
        $topsales = $this->getTopSale();

        return view('homepage.home')->with([
            'product'   =>  $product,
            'topsales'  =>  $topsales,
            'minPrice'  =>  $min,
            'maxPrice'  =>  $max
        ]);
    }

    public function getTopSale()
    {
        $topsales = new Product;
        $topsales = $topsales->get12BestSale();
        $size = count($topsales);

        for ($i=0; $i < $size; $i++) {
            $this->groupImg($topsales ,$i);
        }
        return $topsales;
    }

    public function searchByNameProduct(Request $request)
    {
        $product = new Product;
        $product = $product->getAllDataByName($request->name);
        
        $size = count($product);

        for ($i =0;$i < $size ;$i++) {
            $this->groupImg($product,$i);
            $this->groupAgency($product,$i);
        }

        $topsales = new Product;
        $topsales = $this ->getTopSale();


        return view('homepage.home')->with([
            'product'  =>  $product,
            'topsales' =>  $topsales
        ]);
    }





}
