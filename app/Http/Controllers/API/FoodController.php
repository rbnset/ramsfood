<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $name = $request->input('name');
        $types = $request->input('types');

        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        $reate_from = $request->input('reate_from');
        $reate_to = $request->input('reate_to');

        if ($id) {
            $food = Food::find($id);

            if ($food) {
                return ResponseFormatter::success($food, 'Produk berhasil di ambil');
            } else {
                return ResponseFormatter::error(null, 'Data produk tidak ada', 404);
            }
        }

        $food = Food::query();

        if ($name) {
            $food->where('name', 'like', '%' . $name . '%');
        }
        if ($types) {
            $food->where('types', 'like', '%' . $types . '%');
        }
        if ($price_from) {
            $food->where('price', '>=', $price_from);
        }
        if ($price_to) {
            $food->where('price', '<=', $price_to);
        }
        if ($reate_from) {
            $food->where('rate', '>=', $reate_from);
        }
        if ($reate_to) {
            $food->where('rate', '<=', $reate_to);
        }

        return ResponseFormatter::success($food->paginate($limit), 'Data list produk berhasil di ambil');
    }
}
