<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class MesageController extends Controller
{
        public function getAnswerFromPythonAPI(Request $request)
            {
                $question = $request->input('question');

                $client = new Client();
                $response = $client->post('http:///127.0.0.1:5000/generate_answer', [
                    'json' => ['question' => $question]
                ]);

                $responseData = json_decode($response->getBody(), true);
                $answer = $responseData['answer'];

                return response()->json(['question' => $question, 'answer' => $answer]);
            }


}
