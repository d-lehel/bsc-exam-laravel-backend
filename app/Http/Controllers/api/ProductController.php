<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        //$product['is_active'] = $request['is_active'];
        $product['user_id'] = auth()->user()->id;
        $product['user_name'] = auth()->user()->name;

        // kep tarolasa
        if ($request->hasFile('image_1')) {
            $destination_path = 'public/images/products';
            $image_name = $request['image_name_1'];
            $request->file('image_1')->storeAs($destination_path, $image_name);
        }

        // szelessegi es hosszusagi fok beallitasa a gyorsabb lekerdezes erdekeben
        $product['longitude'] = auth()->user()->longitude;
        $product['latitude'] = auth()->user()->latitude;

        $product['product_name'] = $request['product_name'];
        $product['description'] = $request['description'];
        $product['amount'] = $request['amount'];
        $product['expiration'] = $request['expiration'];
        $product['pickup_adress'] = $request['pickup_adress'];

        // localhost
        $product['image_1'] = "http://127.0.0.1:8000/storage/images/products/" . $request['image_name_1'];

        //server
        //$product['image_1'] = "http://89.44.120.99/storage/images/products/" . $request['image_name_1'];

        Product::create($product);

        return response()->json(
            ['message' => 'Sikeres feltöltés!',], 200
        );
    }

    // GET ALL PRODUCTS
    public function index(Request $request)
    {
        // lekerem a bejelenkezett user-t
        $user = auth()->user();

        // amennyiben van kereso szo beallitva
        if ($request['search_keyword'] != '') {
            $products = Product::where('product_name', 'like', "%" . $request['search_keyword'] . "%")->get();
        } else {

            // lekerem a termekeket aszerint hogy ki hozta letre -> sajatokat / masoket / mindenkiet
            switch ($request['filter_1']) {
                case "own":
                {
                    $products = Product::where('user_id', $user->id)
                        ->orderBy('updated_at', $request['filter_3'])
                        ->get();
                    break;
                }
                case "others":
                {
                    $products = Product::where('user_id', '<>', $user->id)
                        ->orderBy('updated_at', $request['filter_3'])
                        ->get();
                    break;
                }
                case "all":
                {
                    $products = Product::orderBy('updated_at', $request['filter_3'])->get();
                    break;
                }
            }
        }

        // lekerem a user koordinatait
        $userLat = $user->latitude;
        $userLng = $user->longitude;

        // hozza adom a kalkulalt distance mezot minden termekhez
        $products = collect($products)->map(function ($item) use ($userLng, $userLat, $products) {
            $item['distance'] = $this->calculateDistance($item['latitude'], $item['longitude'], $userLat, $userLng);
            return $item;
        });

        // amennyiben van beallitva maximum tavolsag szurom tavolsag szerint
        if ($request['max_distance'] != 0) {
            // kiszedem a kulcsokat ->values()!!!
            $products = $products->where('distance', '<=', $request['max_distance'],null)->values();


        }

        // szurom a termekeket feltoltesi_ido vagy tavolsag szerint
        if ($request['filter_2'] == 'distance') {

            // rendezes novekvobe vagy csokkenobe
            switch ($request['filter_3']) {
                case "ASC":
                {
                    $products->sortBy('distance',SORT_NATURAL);
                    break;
                }
                case "DESC":
                {
                    $products->sortByDesc('distance',SORT_NATURAL);
                    break;
                }
            }
        }

        // return all products
        return response()->json(
            $products, 200
        );
    }

    public function show(Request $request)
    {
        // lekerem a termeket id szerint
        $product = Product::where('id', '=', $request['id'])->first();

        return response()->json(
            $product, 200
        );
    }

    // Haversine formula
    public function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        // deg2rad() konvertelom az erteket a radian megfelelore
        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        // kiszamitom a deltat
        $delta_lat = $lat2 - $lat1;
        $delta_lng = $lng2 - $lng1;

        $hav_lat = (sin($delta_lat / 2)) ** 2;
        $hav_lng = (sin($delta_lng / 2)) ** 2;
        $distance = 2 * asin(sqrt($hav_lat + cos($lat1) * cos($lat2) * $hav_lng));

        // ha merfoldben szeretnem megkapni:  6371-at cserelem 3959-re
        $distance = 6371 * $distance;

        return $distance;
    }

    public function update(Request $request)
    {
        $user_id = auth()->user()->id;
        $product = Product::where('id', $request['id'])->first();

        // megivizsgalom hogy a bejelenkezett user-e a product
        if (!$product) {
            return response()->json(['messages' => 'Product does\'t exist!']);
        }
        if ($user_id != $product->user_id) {
            return response()->json(['messages' => 'Can\'t update other users product!']);
        }

        if ($request['name']) {
            $product->name = $request['name'];
        }
        if ($request['description']) {
            $product->description = $request['description'];
        }
        if ($request['amount']) {
            $product->amount = $request['amount'];
        }
        if ($request['expiration']) {
            $product->expiration = $request['expiration'];
        }
        if ($request['pickup_adress']) {
            $product->pickup_adress = $request['pickup_adress'];
        }
        if ($request['price']) {
            $product->price = $request['price'];
        }
        $product->save();

        return response()->json(
            ['messages' => 'Sikeres frissítés!']
        );
    }

    public function destroy(Request $request)
    {
        $user_id = auth()->user()->id;
        $product = Product::where('id', $request['id'])->first();

        // megivizsgalom hogy a bejelenkezett user-e a product
        if ($user_id == $product->user_id) {
            $product->delete();

        } else {
            return response()->json(
                ['messages' => 'Can\'t delete other users product!']
            );
        }

        // delete all messages first
        $product->messages()->delete();

        return response()->json(
            ['messages' => 'Sikeres törlés!']
        );
    }

    // thumbnail keszitese majd ...
    public function resizeImagePost(Request $request)
    {

        $this->validate($request, [
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('image');
        $input['imagename'] = time() . '.' . $image->extension();

        $destinationPath = public_path('/thumbnail');
        $img = Image::make($image->path());
        $img->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();

        })->save($destinationPath . '/' . $input['imagename']);

        $destinationPath = public_path('/images');
        $image->move($destinationPath, $input['imagename']);

        return back()
            ->with('success', 'Image Upload successful')
            ->with('imageName', $input['imagename']);
    }
}
