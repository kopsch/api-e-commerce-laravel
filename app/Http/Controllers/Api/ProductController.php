<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Listando os produtos de acordo com o ID da categoria
     *
     * @param Request $request
     * @param $uuid
     * @return ProductCollection|JsonResponse
     */
    public function index(Request $request, $uuid)
    {
        $category = Category::where('uuid', $uuid)->first();

        if (!$category) {
            return $this->customResponse("O ID não foi encontrado.", null, 404);
        }

        return new ProductCollection(Product::where('category_id', $category->id)->paginate(10));
    }

    public function store(Request $request)
    {
        if(!auth('api')->user()->isAdmin()) {
            return $this->customResponse("Não autorizado.", null, 403);
        }

        $data = $this->validate($request, [
            'name'        => 'required|min:2|max:100',
            'description' => 'required',
            'stock'       => 'required|numeric',
            'price'       => 'required|numeric',
            'image'       => 'required',
            'category_id' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $model = Product::create($data);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->customResponse($exception->getMessage(), null, 500);
        }

        return $this->customResponse("Produto criado.", $model, 201);
    }

    public function destroy($uuid)
    {
        if(!auth('api')->user()->isAdmin()) {
            return $this->customResponse("Não autorizado.", null, 403);
        }

        $product = $this->getProductByUuid($uuid);

        if (!$product) {
            return $this->customResponse("O ID não foi encontrado.", null, 404);
        }

        try {

            DB::beginTransaction();

            Product::where('id', $product->id)->delete();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->customResponse($exception->getMessage(), null, 500);
        }

        return $this->customResponse("Produto deletado.");
    }


    public function update(Request $request, $uuid)
    {
        if(!auth('api')->user()->isAdmin()) {
            return $this->customResponse("Não autorizado.", null, 403);
        }

        $product = $this->getProductByUuid($uuid);

        if (!$product) {
            return $this->customResponse("O ID não foi encontrado.", null, 404);
        }

        $data = $this->validate($request, [
            'name'        => 'nullable|min:2|max:100',
            'description' => 'nullable',
            'stock'       => 'nullable|numeric',
            'price'       => 'nullable|numeric',
            'image'       => 'nullable',
            'category_id' => 'nullable',
        ]);

        if (!empty($data['category_id'])) {
            $data['category_id'] = $this->getCategoryByUuid($data['category_id'])?->id;
        }

        try {

            DB::beginTransaction();

            Product::where('id', $product->id)->update($data);
            $model = Product::where('id', $product->id)->first();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->customResponse($exception->getMessage(), null,  500);
        }

        return $this->customResponse("Produto atualizado.", $model);
    }
}
