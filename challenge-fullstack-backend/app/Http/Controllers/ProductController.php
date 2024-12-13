<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        try{
            $products = Product::all();

            return response()->json([
                "products" => $products,
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar os produtos',
                'errors' => $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        try{
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Produto não encontrado',
                ], 404);
            }

            return response()->json([
                "product" => $product,
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar o produto',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try{
            $request->validate([
                'name' => 'required|string|max:255|min:3',
                'description' => 'required|string|max:255|min:3',
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:0',
                'active' => 'required|boolean',
            ],
            [
                'required' => 'O campo :attribute é obrigatório.',
                'min' => 'O campo :attribute deve ter pelo menos :min caracteres.',
                'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
                'integer' => 'O campo :attribute deve ser um número inteiro.',
                'numeric' => 'O campo :attribute deve ser um número.',
            ]);

            $product = Product::create($request->all());

            return response()->json([
                "message" => "Produto criado com sucesso!",
                "product" => $product,
            ], 201);
        }
        catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 400);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar o produto',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try{
            $request->validate([
                'name' => 'string|max:255|min:3',
                'description' => 'string|max:255|min:3',
                'price' => 'numeric|min:0',
                'quantity' => 'integer|min:0',
                'active' => 'boolean',
            ],
            [
                'min' => 'O campo :attribute deve ter pelo menos :min caracteres.',
                'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
                'integer' => 'O campo :attribute deve ser um número inteiro.',
                'numeric' => 'O campo :attribute deve ser um número.',
            ]);

            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Produto não encontrado',
                ], 404);
            }

            $product->update($request->all());

            return response()->json([
                "message" => "Produto atualizado com sucesso!",
                "product" => $product,
            ], 200);
        }
        catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 400);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar o produto',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try{
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Produto não encontrado',
                ], 404);
            }

            $product->delete();

            return response()->json([
                "message" => "Produto excluído com sucesso!",
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir o produto',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
