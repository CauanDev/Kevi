<?php

namespace App\Http\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Http\Models\Schedule;
use Dotenv\Dotenv;

class ZApiController
{
    private $token;
    private $security;
    private $instance;
    public function __construct()
    {
        $this->instance = $_ENV['INSTANCE_ID'];
        $this->token = $_ENV['TOKEN_ZAPI'];
        $this->security=$_ENV['SECURITY_TOKEN'];
    }
    public function index(Request $request, Response $response)
    {
        $data = json_decode($request->getBody(), true);
        $phone = $data["phone"];
        $url = "https://api.z-api.io/instances/$this->instance/token/$this->token/phone-exists/$phone";
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Client-Token: ' . $this->security,
            ],
        ]);
    
        $apiResponse = curl_exec($ch);
    
        if (curl_errno($ch)) {
            $response->getBody()->write(json_encode(['error' => curl_error($ch)]));
        }
        else
        {
            $apiResponse = json_decode($apiResponse,true);
            if($apiResponse["exists"])
            {
                $url = "https://api.z-api.io/instances/$this->instance/token/$this->token/profile-picture?phone=$phone";
                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTPGET => true,
                    CURLOPT_HTTPHEADER => [
                        'Content-Type: application/json',
                        'Client-Token: ' . $this->security,
                    ],
                ]);
            
                $apiResponse = curl_exec($ch);
                $apiResponse = json_decode($apiResponse,true);
                $response->getBody()->write(json_encode(['user'=> true,"link"=>$apiResponse["link"]]));

            }
            else
            {
                $response->getBody()->write(json_encode(['user'=> false]));
            }
           
        }


        return $response;
        
    }

    public function destroy(Request $request, Response $response)
    {

        $url= "https://api.z-api.io/instances/$this->instance/token/$this->token/disconnect";
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Client-Token: ' . $this->security,
            ],
        ]);
    
        $apiResponse = curl_exec($ch);
        if (curl_errno($ch)) {
            $response->getBody()->write(json_encode(['error' => curl_error($ch)]));
        }
        else $response->getBody()->write(json_encode($apiResponse ));
        
        return $response;
    }
    public function store(Request $request, Response $response)
    {
        $data = json_decode($request->getBody(), true);
        $phone = $data["phone"];


        $url = ($data["option"] === "qrcode") ? 
        "https://api.z-api.io/instances/$this->instance/token/$this->token/qr-code/image" : 
        "https://api.z-api.io/instances/$this->instance/token/$this->token/phone-code/{$phone}";
 
        
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Client-Token: ' . $this->security,
            ],
        ]);
    
        $apiResponse = curl_exec($ch);
        if (curl_errno($ch)) {
            $response->getBody()->write(json_encode(['error' => curl_error($ch)]));
        }
        else $response->getBody()->write(json_encode($apiResponse));

        return $response;

    }

    public function contact(Request $request, Response $response)
    {
        $url= "https://api.z-api.io/instances/$this->instance/token/$this->token/contacts?page=1&pageSize=10000";
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Client-Token: ' . $this->security,
            ],
        ]);
    
        $apiResponse = curl_exec($ch);
        if (curl_errno($ch)) {
            $response->getBody()->write(json_encode(['error' => curl_error($ch)]));
        }
        else $response->getBody()->write(json_encode($apiResponse));

        return $response; 
    }
    public function instance(Request $request, Response $response)
    {
        $url = "https://api.z-api.io/instances/$this->instance/token/$this->token/status";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Client-Token: ' . $this->security,
            ],
        ]);
    
        $apiResponse = curl_exec($ch);
        $response->getBody()->write($apiResponse);    
        return $response;
    }
    public function send(Request $request, Response $response)
    {
        $data = json_decode($request->getBody(), true);
        $message= $data["newMessage"];
        $phone = $data["phone"];
        $id = $data['id'];
        $url = "https://api.z-api.io/instances/$this->instance/token/$this->token/send-text";
        
        $data = array("phone" => $phone , "message" => $message);
        $data_string = json_encode($data);
        $headers = array(   
            "Client-Token : $this->security"
        );
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data_string,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Client-Token: ' . $this->security,
            ],
        ]);
    
        $apiResponse = curl_exec($ch);
    
        if (curl_errno($ch)) {
            return json_encode(['error' => curl_error($ch)]);
        }
        else
        {
            $schedule = Schedule::find($id);

            if ($schedule) 
            {
       
                $schedule->status = "Lembrado";
                $schedule->scheduled_at = date('Y-m-d H:i:s');
                $schedule->save();
                $response->getBody()->write(json_encode(['message' => 'Schedule updated successfully']));
            }
            else
            {
                print_r($data);
            }
        }
        curl_close($ch);

    
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write($apiResponse);
    
        return $response;
    }
}