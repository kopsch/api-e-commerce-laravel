<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewCollection;

class ReviewController extends Controller
{
    /**
     * Lista as avaliações de acordo com o ID do usuário.
     *
     * @return ReviewCollection
     */
    public function index()
    {
        $uuid = auth()->id();

        return new ReviewCollection(Review::where('user_id', '=', $uuid)->get());
    }

    /**
     * Lista as avaliações de acordo com o ID do usuário.
     *
     * @return ReviewCollection
     */
    public function allReviewsByProduct($uuid)
    {
        $id = $this->getProductByUuid($uuid)->id;

        return new ReviewCollection(Review::where('product_id', '=', $id)->get());
    }

    /**
     * Cria uma nova avaliação.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'subject' => 'required|min:2|max:255',
            'product_id' => 'required',
            'rating' => 'required|numeric|max:10',
            'comment' => 'required',
        ]);

        $data['user_id'] = auth('api')->id();
        $data['product_id'] = $this->getProductByUuid($data['product_id'])?->id;

        try {

            DB::beginTransaction();

            $model = Review::create($data);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->customResponse($exception->getMessage(), null, 500);
        }

        return $this->customResponse("Avaliação criada.", $model, 201);
    }

    /**
     * Mostra a avaliação selecionada
     *
     * @param $uuid
     * @return JsonResponse
     */
    public function show($uuid)
    {
        $review = $this->getReviewByUuid($uuid);

        if (!$review) {
            return $this->customResponse("O ID não foi encontrado.", null, 404);
        }

        return response()->json([
            "data" => Review::with(['product' => fn($query) => $query->with('category')])->first()
        ]);
    }

    /**
     * Remove a avaliação de acordo com o ID da avaliação
     *
     * @param $uuid
     * @return JsonResponse
     */
    public function destroy($uuid)
    {
        $review = $this->getReviewByUuid($uuid);

        if (!$review) {
            return $this->customResponse("O ID não foi encontrado.", null, 404);
        }

        try {

            DB::beginTransaction();

            Review::where('id', $review->id)->delete();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->customResponse($exception->getMessage(), null, 500);
        }

        return $this->customResponse("Avaliação deletada.");
    }
}
