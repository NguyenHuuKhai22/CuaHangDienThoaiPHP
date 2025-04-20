<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $message = $request->input('message');
        
        $response = Http::post('http://localhost:5000/chat', [
            'message' => $message
        ]);
        
        return response()->json($response->json());
    }
} 