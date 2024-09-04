<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class ProductService
{
    public function getAllProducts(): Collection|array
    {
        return Product::with('product_images')->get();
    }

    /**
     * @throws \Exception
     */
    public function update($requestData, $productId)
    {
        try {
            $product = Product::findOrFail($productId);

            $product->name = $requestData['name'];
            $product->description = $requestData['description'];
            $product->prepare_days = $requestData['prepare_days'];
            $product->price = $requestData['price'];
            $product->availability = $requestData['availability'];

            $product->save();

            return $product;

        } catch (ModelNotFoundException $e) {
            // handle when product is not found
            Log::error('Product not found: ' . $e->getMessage());
            throw new \Exception(config('messages.prod_not_found'), 404);

        } catch (QueryException $e) {
            // handle database-related errors
            Log::error('Database error: ' . $e->getMessage());
            throw new \Exception(config('messages.db_error'), 500);

        } catch (\Exception $e) {
            // handle any other errors
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            throw new \Exception(config('messages.unexpected_error'), 500);
        }
    }

    /**
     * @throws \Exception
     */
    public function destroy($productId): void
    {
        try {
             $product = Product::findOrFail($productId);
             $product->delete();

        } catch (ModelNotFoundException $e) {
             Log::error('Product not found: ' . $e->getMessage());
            throw new \Exception(config('messages.prod_not_found'), 404);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            throw new \Exception(config('messages.db_error'), 500);
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            throw new \Exception(config('messages.unexpected_error'), 500);
        }
    }

    /**
     * @throws \Exception
     */
    public function store($requestData)
    {
        try {
            return Product::create($requestData);

        } catch (ModelNotFoundException $e) {
            Log::error('Product not found: ' . $e->getMessage());
            throw new \Exception(config('messages.prod_not_found'), 404);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            throw new \Exception(config('messages.db_error'), 500);
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            throw new \Exception(config('messages.unexpected_error'), 500);
        }
    }
}
