<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DeliveryController extends Controller
{
    public function sendDeliveryData(Request $request)
    {
        // Получаем данные о посылке и получателе из запроса
        $parcelData = $request->only(['width', 'height', 'length', 'weight']);
        $recipientData = $request->only(['customer_name', 'phone_number', 'email', 'delivery_address']);

        // Формируем тело запроса для отправки на курьерскую службу
        $requestData = [
            'customer_name' => $recipientData['customer_name'],
            'phone_number' => $recipientData['phone_number'],
            'email' => $recipientData['email'],
            'sender_address' => config('app.sender_address'),
            'delivery_address' => $recipientData['delivery_address']
        ];

        // Отправляем запрос на курьерскую службу
        $response = Http::post('http://novaposhta.test/api/delivery', $requestData);

        // Проверяем ответ от курьерской службы
        if ($response->ok()) {
            // Обработка успешного ответа
            return response()->json(['message' => 'Данные о посылке успешно отправлены на курьерскую службу']);
        } else {
            // Обработка неудачного ответа
            return response()->json(['error' => 'Ошибка отправки данных на курьерскую службу'], $response->status());
        }
    }
}
