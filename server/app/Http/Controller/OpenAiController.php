<?php

namespace App\Http\Controller;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Dotenv\Dotenv;

class OpenAiController
{
    private $apiKey;
    private $url = 'https://api.openai.com/v1/chat/completions';
    public function __construct() {
        $this->apiKey = $_ENV['OPENAI_KEY'];
    }
    public function question(Request $request, Response $response)
    {
        $data = json_decode($request->getBody(), true);
        if (isset($data['body'])) {
            $question_date = $data['body']['date'];
            $question_text = $data['body']['text'];
        
            $questions = [
                'date' => $question_date,
                'text' => $question_text
            ];
        
            $apiResponses = [];
        
            foreach ($questions as $key => $question) {
                $body = [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $question
                        ]
                    ],
                    'max_tokens' => 100,
                    "temperature" => 0.8
                ];
        
                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_URL => $this->url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => json_encode($body),
                    CURLOPT_HTTPHEADER => [
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $this->apiKey,
                    ],
                ]);
        
                $apiResponse = curl_exec($ch);
        
                if (curl_errno($ch)) {
                    return json_encode(['error' => curl_error($ch)]);
                }
        
                $apiResponseData = json_decode($apiResponse, true);
                $apiResponses[$key] = $apiResponseData['choices'][0]["message"]["content"];
        
                curl_close($ch);
            }
        
            $response->getBody()->write(json_encode([
                "text" => $apiResponses['text'],
                "date" => $apiResponses['date']
            ]));
        
            return $response;
        }
        else
        {
            $str = $data['question'];
            $body = [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $str
                    ]
                ],
                'max_tokens' => 100,
                "temperature" => 0.8
            ];
        
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $this->url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($body),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->apiKey,
                ],
            ]);
        
            $apiResponse = curl_exec($ch);
        
            if (curl_errno($ch)) {
                return json_encode(['error' => curl_error($ch)]);
            }
        
            curl_close($ch);
            $apiResponseData = json_decode($apiResponse, true);
            $message = $apiResponseData['choices'][0]["message"]["content"];
            $response->getBody()->write(json_encode($message));
            return $response;            
        }

    }
    public function index($data)
    {

        $character = $data['character'] ?? 'undefined';
    
        if (isset($data['message'])) {
            $message = $data['message'];
            $str = "
            Faça o seguinte: abaixo eu vou te mandar um texto, você deverá reescrever o texto. A única mensagem que você irá me encaminhar será o texto reescrito (sem nenhuma aspas ou negrito). Você deverá reescrevê-lo como se fosse o $character dizendo-a. Seja o mais real possível, eu quero sentir como se fosse o personagem de fato escrevendo a mensagem. Não tem problema em mudar a forma da mensagem, contanto que mantenha o sentido da mensagem. Você deverá usar emojis na mensagem que possuem relação com o personagem.
            Segue o texto:
            '$message'
            ";
        } else {
            $str = "Chat gpt e o seguinte, eu vou te mandar um nome de um personagem, se ele for famoso, seja em algum anime ou serie ou na vida real, voce ira me responder com SOMENTE: True
    
            Caso vc nao consiga responder ou nao puder, responsa SOMENTE com:False
    
            Personagem: $character";
        }
    
        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $str
                ]
            ],
            'max_tokens' => 100,
            "temperature" => 0.8
        ];
    
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey,
            ],
        ]);
    
        $apiResponse = curl_exec($ch);
    
        if (curl_errno($ch)) {
            return json_encode(['error' => curl_error($ch)]);
        }
    
        curl_close($ch);
        $apiResponseData = json_decode($apiResponse, true);
    
        $message = $apiResponseData['choices'][0]["message"]["content"] ?? '';
    
        return $message;
    }
    
}