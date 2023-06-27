<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Lista as categorias
     *
     * @return CategoryCollection
     */
    public function index()
    {
        return new CategoryCollection(Category::paginate(10));
    }

    public function store(Request $request)
    {
        if(!auth('api')->user()->isAdmin()) {
            return $this->customResponse("Não autorizado.", null, 403);
        }

        $data = $this->validate($request, [
            'name'        => 'required|min:2|max:100',
            'description' => 'required'
        ]);

        try {

            DB::beginTransaction();

            $model = Category::create($data);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->customResponse($exception->getMessage(), null, 500);
        }

        return $this->customResponse("Categoria criada.", $model, 201);
    }

    public function update(Request $request, $uuid)
    {
        if(!auth('api')->user()->isAdmin()) {
            return $this->customResponse("Não autorizado.", null, 403);
        }

        $category = $this->getCategoryByUuid($uuid);

        if (!$category) {
            return $this->customResponse("O ID não foi encontrado.", null, 404);
        }

        $data = $this->validate($request, [
            'name'        => 'nullable|min:2|max:100',
            'description' => 'nullable',
        ]);

        try {

            DB::beginTransaction();

            Category::where('id', $category->id)->update($data);
            $model = Category::where('id', $category->id)->first();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->customResponse($exception->getMessage(), null, 500);
        }

        return $this->customResponse("Categoria atualizada.", $model);
    }

    public function destroy($uuid)
    {
        if(!auth('api')->user()->isAdmin()) {
            return $this->customResponse("Não autorizado.", null, 403);
        }

        $category = $this->getCategoryByUuid($uuid);

        if (!$category) {
            return $this->customResponse("O ID não foi encontrado.", null, 404);
        }

        try {

            DB::beginTransaction();

            Category::where('id', $category->id)->delete();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->customResponse($exception->getMessage(), null, 500);
        }

        return $this->customResponse("Categoria deletada.");
    }

    protected function getCategoryByUuid($uuid)
    {
        return Category::where('uuid', $uuid)->first();
    }
}
