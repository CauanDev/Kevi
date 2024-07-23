<?php

namespace App\Http\Controller;
date_default_timezone_set('America/Campo_Grande');
use App\Http\Models\Schedule;
use Carbon\Carbon;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class ScheduleController 
{
    public function filter(Request $request, Response $response)
    {
        $data = json_decode($request->getBody(), true);
        $query = Schedule::query();
        if(isset($data['startDate']))$query->where('scheduled_at', '>=', $data['startDate']);

        if(isset($data['endDate']))$query->where('scheduled_at', '<=' ,$data['endDate'] );
        if(isset($data['status']) && $data['status']!=='all')$query->where('status' ,'=', $data['status'] );
        if(isset($data['character'])&& $data['character']!=='all')$query->where('character' ,'=', $data['character'] );

        $query->orderBy('status', 'asc')->get();
        $result = $query->get();

        $response->getBody()->write(json_encode($result));
        return $response;

    }
    public function destroy(Request $request, Response $response)
    {
        $data = json_decode($request->getBody(), true);
        $schedule = Schedule::find($data['id']);

        if ($schedule) {
            // Deletar o Schedule
            $schedule->delete();
            
            $response->getBody()->write(json_encode(['status' => 'success', 'message' => 'Schedule deleted successfully']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'Schedule not found']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
    public function index(Request $request, Response $response)
    {
        $schedules = Schedule::orderBy('status', 'asc')->get();
        $response->getBody()->write(json_encode($schedules));
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function store(Request $request, Response $response)
    {
        $data = json_decode($request->getBody(), true);
        $date = Carbon::createFromFormat('d/m/Y H:i', $data['date'])->format('Y-m-d H:i:s');
        try
        {
            $schedule = Schedule::create([
                'message' => $data['message'],
                "phone"=> $data["phone"],
                'newMessage' => $data["newMessage"],
                'status' => "Ainda Não Postado",
                'character' => $data['character'],
                'scheduled_at' => $date
            ]);     

            $response->getBody()->write(json_encode([
                "store"=>true,
                "message"=>$schedule
                ])); 
        }
        catch(\Exception $e)
        {
            $response->getBody()->write(json_encode([
            "store"=>false,
            "message"=>$e
            ])); 
        }
        return $response->withHeader('Content-Type', 'application/json');

    }
}