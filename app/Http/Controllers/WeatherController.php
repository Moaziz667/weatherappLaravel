<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;

class WeatherController extends Controller
{
    protected $weatherService;
    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService ; 
    }
    public function index (Request $request){
        $city = $request->input('city','London');
    $weather = $this->weatherService->getWeather($city);
    return view('weather.index',[
        'weather' => $weather
    ]);
    }
}
