<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function customResponse(string $message = null, $data = null, int $status = 200)
    {
        $response = ['message' => $message];

        if ($data !== null) {
            $response['data'] = $data;
        }


        return response()->json($response, $status);
    }


    protected function getProductByUuid($uuid)
    {
        return Product::where('uuid', $uuid)->first();
    }

    protected function getCategoryByUuid($uuid)
    {
        return Category::where('uuid', $uuid)->first();
    }

    protected function getReviewByUuid($uuid)
    {
        return Review::where('uuid', $uuid)->first();
    }

    protected function getOrderByUuid($uuid)
    {
        return Order::where('uuid', $uuid)->first();
    }
}
