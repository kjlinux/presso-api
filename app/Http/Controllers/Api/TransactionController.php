<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            /** @var \Illuminate\Contracts\Auth\Guard $auth */
            $auth = auth();
            $user = $auth->user();

            $query = Transaction::forUser($user->id)->latest();

            if ($request->has('status') && $request->status !== 'all') {
                $query->byStatus($request->status);
            }

            if ($request->has('category') && $request->category !== 'all') {
                $query->where('category', $request->category);
            }

            if ($request->has('payment_method') && $request->payment_method !== 'all') {
                $query->where('payment_method', $request->payment_method);
            }

            if ($request->has('date_filter')) {
                switch ($request->date_filter) {
                    case 'today':
                        $query->whereDate('created_at', today());
                        break;
                    case 'week':
                        $query->where('created_at', '>=', now()->subWeek());
                        break;
                    case 'month':
                        $query->where('created_at', '>=', now()->subMonth());
                        break;
                }
            }

            if ($request->has('search') && !empty($request->search)) {
                $query->where('customer_name', 'like', '%' . $request->search . '%');
            }

            $transactions = $query->paginate($request->get('per_page', 15));

            $totalRevenue = $query->sum('amount');
            $totalQuantity = $query->sum('quantity');

            return response()->json([
                'success' => true,
                'data' => [
                    'transactions' => $transactions->items(),
                    'pagination' => [
                        'current_page' => $transactions->currentPage(),
                        'last_page' => $transactions->lastPage(),
                        'per_page' => $transactions->perPage(),
                        'total' => $transactions->total(),
                    ],
                    'summary' => [
                        'total_revenue' => $totalRevenue,
                        'total_quantity' => $totalQuantity,
                        'count' => $transactions->total(),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des transactions'
            ], 500);
        }
    }

    public function store(StoreTransactionRequest $request): JsonResponse
    {
        try {
            /** @var \Illuminate\Contracts\Auth\Guard $auth */
            $auth = auth();
            $user = $auth->user();

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'customer_name' => $request['customer_name'],
                'amount' => $request['amount'],
                'quantity' => $request['quantity'],
                'category' => $request['category'],
                'payment_method' => $request['payment_method'],
                'inventory' => $request['inventory'],
                'notes' => $request['notes'],
                'status' => $request['status'] ?? 'en-attente',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaction enregistrée avec succès',
                'data' => [
                    'transaction' => $transaction
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la transaction'
            ], 500);
        }
    }
}
