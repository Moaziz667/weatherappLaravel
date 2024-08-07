<?php 

        namespace App\Services;
        use Illuminate\Support\Facades\Http;

        class WeatherService {
            protected $apiKey ; 
            protected $apiUrl ;
            public function __construct()
            {
                $this->apiKey = env('WEATHER_API_KEY');
                $this->apiUrl = env('WEATHER_API_URL');
            }
            public function getWeather($city){
                try{
                $response = Http::get($this->apiUrl, [
                    'q' => $city,
                    'appid' => $this->apiKey,
                    'units' => 'metric' or 'imperial'
                ]);
                if($response->successful()){
                    $data =  $response->json();
                    if(isset($data['main']['temp'])){
                        $kelvinTemp = $data['main']['temp'];
                        $data['main']['temp'] = $kelvinTemp - 273.15;
                        return $data;
                    }else{
                        return ["error" => "Tempearture not found"];
                    }
                }else{
                    return ['error' => 'Unable to fetch weather data. Please try again later.'];

                }

            }catch(\Exception $e){
                return ['error' => 'Unable to fetch weather data. Please try again later.'];
            }
            

        }}