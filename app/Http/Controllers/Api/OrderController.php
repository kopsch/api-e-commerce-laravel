<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderCollection;

class OrderController extends Controller
{
    /**
     * Lista os pedidos de acordo com o ID do usuário.
     *
     * @return OrderCollection
     */
    public function index()
    {
        $uid = auth()->id();

        return new OrderCollection(Order::where('user_id', '=', $uid)->get());
    }

    /**
     * Cria um novo pedido.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required|min:2|max:100',
            'product_id' => 'required',
            'status' => 'required|numeric',
            'order_date' => 'required',
        ]);

        $data['user_id'] = auth('api')->id();
        $data['product_id'] = $this->getProductByUuid($data['product_id'])?->id;

        try {

            DB::beginTransaction();

            $model = Order::create($data);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->customResponse($exception->getMessage(), null, 500);
        }

        return $this->customResponse("Pedido criado.", $model, 201);
    }

    protected function getProductByUuid($uuid)
    {
        return Product::where('uuid', $uuid)->first();
    }

    /**
     * Mostra o pedido selecionado
     *
     * @param $uuid
     * @return JsonResponse
     */
    public function show($uuid)
    {
        $order = $this->getOrderByUuid($uuid);

        if (!$order) {
            return $this->customResponse("O ID não foi encontrado.", null, 404);
        }

        return response()->json([
            "data" => Order::with(['product' => fn($query) => $query->with('category')])->find($order->id)
        ]);
    }

    /**
     * Atualiza o pedido
     *
     * @param Request $request
     * @param $uuid
     * @return JsonResponse
     */
    public function update(Request $request, $uuid)
    {
        $order = $this->getOrderByUuid($uuid);

        if (!$order) {
            return $this->customResponse("O ID não foi encontrado.", null, 404);
        }

        $data = $this->validate($request, [
            'name' => 'nullable|min:2|max:100',
            'status'    => 'nullable|numeric',
            'product_id' => 'nullable|exists:products,uuid,deleted_at,NULL',
        ]);

        if (!empty($data['product_id'])) {
            $data['product_id'] = $this->getProductByUuid($data['product_id'])?->id;
        }

        try {

            DB::beginTransaction();

            Order::where('id', $order->id)->update($data);
            $model = Order::where('id', $order->id)->first();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->customResponse($exception->getMessage(), null, 500);
        }

        return $this->customResponse("Pedido atualizado.", $model);
    }

    /**
     * Remove o pedido de acordo com o ID do pedido
     *
     * @param $uuid
     * @return JsonResponse
     */
    public function destroy($uuid)
    {
        $order = $this->getOrderByUuid($uuid);

        if (!$order) {
            return $this->customResponse("O ID não foi encontrado.", null, 404);
        }

        try {

            DB::beginTransaction();

            Order::where('id', $order->id)->delete();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->customResponse($exception->getMessage(), null, 500);
        }

        return $this->customResponse("Pedido deletado.");
    }
}
