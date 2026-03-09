<?php
require_once 'modelo/WeatherAPI.php';
require_once 'modelo/climaDAO.php';

class ClimaControlador {
    private $apiClima;
    private $climaDAO;

    public function __construct() {
        $this->apiClima = new WeatherAPI();
        $this->climaDAO = new ClimaDAO();
    }

    public function manejarPeticion() {
        $accion = isset($_POST['accion']) ? htmlspecialchars($_POST['accion']) : 'inicio';

        switch ($accion) {
            case 'buscar':
                $this->procesarBusqueda();
                break;
            case 'actual':
                $this->mostrarActual();
                break;
            case 'horas':
                $this->mostrarHoras();
                break;
            case 'semana':
                $this->mostrarSemana();
                break;
            case 'historial':
                $this->mostrarHistorial();
                break;
            default:
                $this->mostrarBuscador();
                break;
        }
    }

    /**
     * Procesa la búsqueda inicial de una ciudad introducida por el usuario.
     */
    private function procesarBusqueda() {
    
        $ciudad = isset($_POST['ciudad']) ? trim(strip_tags($_POST['ciudad'])) : null;

        if (empty($ciudad)) {
            $this->mostrarBuscador("Por favor, introduce el nombre de una ciudad válida.");
            return;
        }

        $coordenadas = $this->apiClima->obtenerCoordenadas($ciudad);

        if ($coordenadas) {
            $lat = $coordenadas['lat'];
            $lon = $coordenadas['lon'];
            $nombre = $coordenadas['name'];

            $climaActual = $this->apiClima->obtenerClimaActual($lat, $lon);
            
            if ($climaActual) {
                $this->climaDAO->insertarConsulta(
                    $nombre, 
                    $climaActual['main']['temp'], 
                    $climaActual['weather'][0]['description'], 
                    $lat, 
                    $lon
                );
                
                $this->renderizar('actual', [
                    'clima' => $climaActual,
                    'lat' => $lat,
                    'lon' => $lon,
                    'nombre' => $nombre
                ]);
            }
        } else {
            $this->mostrarBuscador("No se pudo encontrar la ciudad: " . htmlspecialchars($ciudad));
        }
    }

    /**
     * Procesa y muestra la vista del clima actual.
     */
    private function mostrarActual() {
        $datos = $this->obtenerDatosNavegacion();
        $clima = $this->apiClima->obtenerClimaActual($datos['lat'], $datos['lon']);
        $this->renderizar('actual', array_merge($datos, ['clima' => $clima]));
    }

    /**
     * Procesa y muestra la vista del pronóstico por horas.
     */
    private function mostrarHoras() {
        $datos = $this->obtenerDatosNavegacion();
        $pronostico = $this->apiClima->obtenerPronostico($datos['lat'], $datos['lon']);
        $this->renderizar('horas', array_merge($datos, ['pronostico' => $pronostico]));
    }

    /**
     * Procesa y muestra la vista del pronóstico semanal.
     */
    private function mostrarSemana() {
        $datos = $this->obtenerDatosNavegacion();
        $pronostico = $this->apiClima->obtenerPronostico($datos['lat'], $datos['lon']);
        $this->renderizar('semana', array_merge($datos, ['pronostico' => $pronostico]));
    }

    /**
     * Procesa y muestra la vista del historial completo.
     */
    private function mostrarHistorial() {
        $datos = $this->obtenerDatosNavegacion();
        $historial = $this->climaDAO->obtenerTodasConsultas();
        $this->renderizar('historial', array_merge($datos, ['historial' => $historial]));
    }

    /**
     * Carga la interfaz del buscador inicial con el historial de consultas.
     */
    private function mostrarBuscador($mensajeError = null) {
        $historial = $this->climaDAO->obtenerTodasConsultas();
        $error = $mensajeError;
        require_once 'vista/buscador.php';
    }

    /**
     * Recupera y valida los datos de navegación enviados por POST (lat, lon, nombre).
     */
    private function obtenerDatosNavegacion() {
        $lat = isset($_POST['lat']) ? floatval($_POST['lat']) : null;
        $lon = isset($_POST['lon']) ? floatval($_POST['lon']) : null;
        $nombre = isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : 'Ciudad';
        
        if ($lat === null || $lon === null) {
            header("Location: index.php");
            exit();
        }
        
        return ['lat' => $lat, 'lon' => $lon, 'nombre' => $nombre];
    }

    /*
     * Método auxiliar para extraer datos y requerir el archivo de la vista.
     */
    private function renderizar($nombreVista, $variables = []) {
        extract($variables);
        $vista = $nombreVista; 
        require_once "vista/$nombreVista.php";
    }
}