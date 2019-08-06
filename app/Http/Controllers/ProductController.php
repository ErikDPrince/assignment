<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Category;
use App\Product;
use App\ProductCategory;
use App\ProductImage;
use App\Agency;
use App\AgencyProduct;
use DB;
use File;
use Auth;
class ProductController extends Controller
{
    //
    private function getUserID()
    {
        return Auth::user()->id;
    }

    public function index()
    {
        $id = $this->getUserID();
        $product = new Product;
        $product = $product->getAllDataByUserId($id);

        $size = count($product);

        $this->groupImg($product,$size);
        $this->groupAgency($product,$size);

        return view('admin.product.list')->with('product'.$product);
    }

    public function getAddProduct()
    {
        $id = $this->getUserID();
        $agency = new Agency;
        $agency = $agency->getAllDataById($id);

        $category = new Category;
        $category = $category ->getAllData();

        return view('admin.product.add')->with([
            'category' =>$category,
            'agency'   =>$agency
        ]);
    }

    public function postAddProduct(ProductRequest $request)
    {
        DB::beginTransaction();
        try {

            $id = $this->getUserId();
        	$product = new Product;
            $product->addData($id, $request);

        	$product_id = $product->id;
            $file = $request->file('fImage');

            $this->uploadFile($file, $product_id);

            $cate = $request->cate;  //array
            foreach ($cate as $key => $value) {
    	        $product_cate = new ProductCategory;
                $product_cate = $product_cate->addData($product_id, $value);
            }

            $agency = $request->agency; // array
            foreach ($agency as $key => $value) {
                $agency_product = new AgencyProduct;
                $agency_product = $agency_product->addData($value, $product_id);
            }
        DB::commit();
        return redirect()->route('seller.product');
        }
        catch (Exception $e) {
        DB::rollback();    
        }
    }

    public function groupImg($product, $size)
    {
        for ($i =0; $i< $size;$i++) {
            $id = $product[$i]['product_id'];
            $product_img = new ProductImage;
            $product_img =$product_img->getAllDataById($id);
            $product[$i]['image'] =$product_img;
        }
    }

    public function groupAgency($product, $size)
    {
        for($i = 0 ;i<$size;$i++) {
            $id = $product[$i]['product_id'];
            $agency_product = new AgencyProduct;
            $agency_product = $agency_product->getDataByProductId($id);
            $product[$i]['agency_pro'] = $agency_product;

        }
    }

    public function getEditProduct($id)
    {
        $uid = $this->getUserId();
        $product = new Product;
        $product = $product->getDataById($id);

        $product_img = new ProductImage;
        $product_img = $product_img->getDataByProductId($id);

        $product_cate = new ProductCategory;
        $product_cate =$product_cate->getDataByProductId($id);

        $category = new Category;
        $category = $category->getAllData();

        return view('admin.product.edit')->with([
            'product'           =>  $product,
            'product_img'       =>  $product_img,
            'agency'            =>  $agency,
            'category'          =>  $category,
            'product_cate'      =>  $product_cate,
            'agency_product'    =>  $agency_product
        ]);
    }

    public function uploadFile($file, $product_id)
    {
        foreach ($file as $key => $value) {
            $file_name = $value->getClientOriginalName();
            $product_img =new ProductImage;
            $value->move(public_path('/uploads/products'), $file_name);
            $product_img = $product_img->addData($product_id, $file_name);
        }
    }

    public function postEditProduct($id ,ProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $uid = $this->getUserID();
            $product = new Product;
            $product = $product->getDataById($id, false);
            $product = $product->addData($uid,$request);

            //store product_img
            $file = $request->file('fImage');
            if (isset($file)){
                $this->uploadFile($file,$id);
            }

            //store agency
            $agency =$agency->agency;
            print_r($agency);

            if(!isset($agency)){

            }
            else {
                $quantity = $request->quantity;
                $discount_rate = $request->discount_rate;
                $size = count($agency);

                for ($i=0; $i < $size; $i++) { 
                    $agency_product = new AgencyProduct;
                    $agency_product = $agency_product->getDataByIdAndProductId($agency[$i], $id);

                    $agency_product = $agency_product->updateQuantityAndDiscoundtRate($quantity[$i], $discount_rate[$i]);
                }
            }

            //store product category
            $category = $request->cate;
            $test = 0;
            $size = count($category);
            for ($i=0; $i < $size; $i++) { 
                $product_cate = new ProductCategory;
                if (($product_cate = $product_cate->getDataByProIdAndCateId($id, $category[$i]))) {
                    // isset product_category;
                }
                else {
                    $product_cate = new ProductCategory;
                    // add product_category
                     $product_cate = $product_cate->addData($id, $category[$i]);
                }
            }

            $product_cate = new ProductCategory;
            $product_cate = $product_cate->deleteNotIn($id, $category);

            // DB::table('product_categories')->where('product_id', $id)->whereNotIn('category_id', $category)->delete();

            //add agency for product
            if ($request->has('new_agency')){
                $new_agency         = $request->new_agency;
                $new_quantity       = $request->new_quantity;
                $new_discount_rate  = $request->new_discount_rate;

                $cate_size = count($new_agency);
                for ($i=0; $i < $cate_size; $i++) { 
                    $agency_product = new AgencyProduct;
                    if (($agency_product = $agency_product->getDataByProIdAndAgenId($id, $new_agency[$i]))) {
                        // isset agency_product;
                    }
                    else {
                        // add agency_product
                        $agency_product = new AgencyProduct;
                        $agency_product = $agency_product->addData($new_agency[$i], $id, $new_quantity[$i], $new_discount_rate[$i]);
                    }
                }
            }

            DB::commit();
            return redirect()->route();
        }
        catch (Exception $e) {
            DB::rollback();
        }
    }


    public function delImgProduct(Request $request)
    {
        if($request->ajax())
        {
            $id = $request->id;
            $product_image = new ProductImage;
            $product_image = $product_image->getDataById($id);
            $image_path = public_path('/uploads/products/') . $product_image->image;
            $product_image = $product_image->deleteData();
            
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
            return 1;
        }
        else {
            return "not found";
        }
    }

    public function setDefaultImg(Request $request)
    {
        if($request->ajax())
        {
            $id         = $request->id;
            $product_id = $request->product_id;
            $product_img = new ProductImage;
            $product_img = $product_img->setDefaultImg($id, $product_id);
            return 1;
        }
        else {
            return "not found";
        }
    }

    public function delAgencyProduct(Request $request)
    {
        if($request->ajax())
        {
            $id = $request->id;
            $agency_product = new AgencyProduct;
            $agency_product = $agency_product->deleteDataById($id);
            return 1;
        }
        else{
            return "not found";
        } 
    }

    public function delProduct($id)
    {
        DB::beginTransaction();
        try {
            //delete product image
            $product_image = new ProductImage;
            $product_image = $product_image->getDataByProductId($id, false);
            foreach ($product_image as $key => $value) {
                $image_path = public_path('/uploads/products/') . $value->image;
                $value->deleteData();
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }

            //delete product cate
           
            $product_cate = new ProductCategory;
            $product_cate = $product_cate->deleteDataByProductId($id);

            //delete agency product;
            $agency_product = new AgencyProduct;
            $agency_product = $agency_product->deleteDataByAttrId($id);

            // delete product
            $product = new Product;
            $product = $product->deleteDataById($id);

            DB::commit();
            return redirect()->back();
        }
        catch (Exception $e) {
            DB::rollBack();
        }
    }




    

}
