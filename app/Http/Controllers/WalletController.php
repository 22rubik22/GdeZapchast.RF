<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TransactionHistory;
use App\Models\User;


class WalletController extends Controller
{
public function getHistory(Request $request)
{
    // Получаем текущего пользователя
    $user = Auth::user();

    // Получаем все операции пользователя
    $transactions = TransactionHistory::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();

    // Форматируем данные для ответа
    $formattedTransactions = $transactions->map(function ($transaction) {
        return [
            'type' => $transaction->operation_type === 'пополнение' ? 'replenishment' : 'withdrawal',
            'text' => $transaction->operation_type === 'пополнение' ? 'Пополнение кошелька' : 'Списание средств',
            'details' => $transaction->details, // Используем новый столбец details
            'date' => $transaction->created_at->format('d.m.Y'),
            'amount' => $transaction->operation_type === 'пополнение' 
                ? '+ ' . number_format($transaction->amount, 2, '.', ' ') . ' ₽' 
                : '- ' . number_format($transaction->amount, 2, '.', ' ') . ' ₽',
            'color' => $transaction->operation_type === 'пополнение' ? 'text-green-500' : 'text-red-500',
        ];
    });

    return response()->json($formattedTransactions);
}

public function showPayForm()
    {
        // Проверяем, авторизован ли пользователь
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Получаем текущего пользователя
        $user = Auth::user();

        // Передаем баланс в представление
        return view('pay_form', [
            'balance' => $user->balance, // Баланс из столбца balance
        ]);
    }
}