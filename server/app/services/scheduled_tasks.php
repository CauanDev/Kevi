<?php

use App\Http\Models\Schedule;

date_default_timezone_set('America/Campo_Grande');
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'pgsql',
    'host' => $_ENV['DB_HOST'],
    'database' => $_ENV['DB_DATABASE'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$now = date('Y-m-d H:i:s');

$tasks = Schedule::where('scheduled_at', '<=', $now)
                ->where('status', 'Ainda Nao Postado')
                ->get();

foreach ($tasks as $task) {
    echo "===Mensagem Para ser Enviada ID[" . $task->id . "]===\n";
    $instance = $_ENV['INSTANCE_ID'];
    $token = $_ENV['TOKEN_ZAPI'];
    $security = $_ENV['SECURITY_TOKEN'];
    $url = "https://api.z-api.io/instances/$instance/token/$token/send-text";

    $data = [
        "phone" => $task->phone,
        "message" => $task->newMessage
    ];
    echo "Informacoes a serem enviadas:\nNumero: " . $data['phone'] . "\nMensagem: " . $data['message'] . "\n";

    $data_string = json_encode($data);
    $headers = [
        'Content-Type: application/json',
        'Client-Token: ' . $security
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data_string,
        CURLOPT_HTTPHEADER => $headers
    ]);

    $apiResponse = curl_exec($ch);
    if (curl_errno($ch)) {
        echo json_encode(['error' => curl_error($ch)]);
    } else {
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode != 200) {
            echo json_encode(['error' => 'API response error', 'response' => $apiResponse]);
        } else {
            echo "Mensagem Enviada com Sucesso e Atualizado no Banco de Dados!";
            $task->status = 'Lembrado';
            $task->save();
        }
    }
    curl_close($ch);
}
