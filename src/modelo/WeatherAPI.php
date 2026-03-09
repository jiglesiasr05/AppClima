<?php

class WeatherAPI {
    private $claveApi;

    public function __construct() {
        $this->claveApi = getenv('openweather_API') ?: ($_ENV['openweather_API'] ?? null);

        if (empty($this->claveApi)) {
            die("Error: La API Key no está configurada en el contenedor.");
        }
    }

    public function obtenerCoordenadas($ciudad) {
        $url = "http://api.openweathermap.org/geo/1.0/direct?q=" . urlencode($ciudad) . "&limit=1&appid=" . $this->claveApi;
        $respuesta = @file_get_contents($url);
        if (!$respuesta) return null;
        $datos = json_decode($respuesta, true);
        return (isset($datos[0])) ? $datos[0] : null;
    }

    public function obtenerClimaActual($lat, $lon) {
        $url = "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&units=metric&lang=es&appid=" . $this->claveApi;
        $respuesta = @file_get_contents($url);
        return $respuesta ? json_decode($respuesta, true) : null;
    }

    public function obtenerPronostico($lat, $lon) {
        $url = "https://api.openweathermap.org/data/2.5/forecast?lat=$lat&lon=$lon&units=metric&lang=es&appid=" . $this->claveApi;
        $respuesta = @file_get_contents($url);
        return $respuesta ? json_decode($respuesta, true) : null;
    }

    public function obtenerUrlIcono($icono, $tamano = '@2x') {
        return "https://openweathermap.org/img/wn/{$icono}{$tamano}.png";
    }

    public function traducirDia($fecha_txt) {
        $dias = [
            'Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado', 'Sunday' => 'Domingo'
        ];
        $ingles = date('l', strtotime($fecha_txt));
        return $dias[$ingles] ?? $ingles;
    }
}