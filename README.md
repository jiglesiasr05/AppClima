# 🌦️ Documentación Técnica: Aplicación de Previsión Meteorológica (MVC)

Esta app está hecha en **PHP** siguiendo el patrón **Modelo-Vista-Controlador (MVC)**.  
Aquí se explica cómo funciona internamente, qué hace cada archivo y cómo se mueven los datos dentro del proyecto.

---

## 1. Flujo de Datos de la Aplicación

El flujo sigue un camino **lineal y cíclico**. Se usa el método **POST** para mantener la información entre pantallas sin guardar sesiones en el servidor.

### Paso a paso

1. **Entrada del Usuario**  
   El usuario empieza en `buscador.php`, donde introduce el nombre de la ciudad que quiere consultar.

2. **Enrutamiento Central**  
   Todas las peticiones pasan por `index.php`, que funciona como **Front Controller** y redirige al `ClimaControlador`.

3. **Procesamiento de la Búsqueda**  
   - El controlador recibe el nombre de la ciudad.  
   - El modelo `WeatherAPI` convierte el nombre en coordenadas (lat y lon).  
   - Con esas coordenadas, pide el clima actual.  
   - El modelo `ClimaDAO` guarda la búsqueda y el resultado en la base de datos.  
   - Luego se muestra la vista `actual.php`.

4. **Navegación Interna**  
   Si el usuario cambia de pestaña (Horas, Semana o Historial), la vista manda un formulario oculto por **POST** con `lat`, `lon` y `nombre`.  
   El controlador detecta la acción, pide nuevos datos al modelo y recarga la vista correcta.

---

## 2. Estructura del Proyecto y Funciones

### Raíz del Proyecto

**`index.php`**  
- Actúa como punto de entrada principal.  
- Carga el controlador y llama a su función `manejarPeticion()`.

---

### Controladores (`src/controlador/`)

**`controlador.php` (Clase `ClimaControlador`)**  
- Es el “cerebro” del sistema. Coordina modelos y vistas.  
- **Funciones importantes:**
  - `__construct()`: Crea instancias de `WeatherAPI` y `ClimaDAO`.  
  - `manejarPeticion()`: Revisa `$_POST['accion']` para decidir qué hacer (`buscar`, `actual`, `horas`, `semana`, `historial`).  
  - `procesarBusqueda()`: Limpia el nombre, obtiene coordenadas a través de la API, pide el clima actual, lo guarda en el DAO y prepara la vista.  
  - `mostrarActual()`, `mostrarHoras()`, `mostrarSemana()` y `mostrarHistorial()`: Piden la información necesaria a los modelos y renderizan las páginas.  
  - `mostrarBuscador()`: Recupera el historial a través del DAO y muestra la vista de inicio.  
  - `obtenerDatosNavegacion()`: Recupera las coordenadas guardadas en los inputs ocultos asegurándose de convertirlas a `float`.  
  - `renderizar()`: Extrae las variables y requiere la vista correspondiente.

---

### Modelos (`src/modelo/`)

**`WeatherAPI.php`**  
- Maneja la conexión con **OpenWeatherMap**.  
- **Funciones:**
  - `obtenerCoordenadas($ciudad)`: Convierte el texto de la ciudad en lat/lon.  
  - `obtenerClimaActual($lat, $lon)`: Solicita y devuelve el tiempo instantáneo.  
  - `obtenerPronostico($lat, $lon)`: Recupera las previsiones.  
  - `obtenerUrlIcono($icono)`: Genera el enlace directo a la imagen del clima según el id devuelto por la API.  
  - `traducirDia($fecha_txt)`: Transforma los días de inglés a español.

**`climaDAO.php`**  
- Controla las interacciones con la base de datos MySQL mediante PDO.  
- **Funciones:**
  - `__construct()`: Instancia y guarda la conexión utilizando `Database`.  
  - `insertarConsulta(...)`: Añade un registro nuevo a la BDD cada vez que se busca una ciudad.  
  - `obtenerTodasConsultas()`: Trae el historial completo, ordenado desde el más reciente.  
  - `eliminarConsulta($id)`: (Opcional) Borra búsquedas específicas de la tabla.

**`Database.php`**  
- Contiene la función estática `conectar()`, que lee las variables de entorno (`getenv`) y devuelve un objeto de conexión PDO al servidor MySQL.

---

### Vistas (`src/vista/`)

Las vistas solo muestran la información enviada mediante arreglos desde el controlador.

- **`buscador.php`** → La portada. Formulario inicial + listado del historial recuperado de la base de datos.  
- **`actual.php`** → Pantalla destacada mostrando el clima presente del lugar buscado.  
- **`horas.php`** → Listado en bloque con las predicciones para las próximas horas.  
- **`semana.php`** → Lista del pronóstico para los días venideros.  
- **`historial.php`** → Pantalla exclusiva que dibuja en tabla todo el registro de búsquedas.  
- **`header.php` y `footer.php`** → Trozos HTML que construyen la cabecera (con los botones de navegación ocultos mediante formularios POST a cada *accion*) y el pie.

---

## 3. Gráficas de Temperatura

Para mostrar la evolución de la temperatura en las vistas de **Horas** y **Semana**, se ha prescindido por completo de librerías externas de JavaScript (como Chart.js o jQuery) para garantizar la compatibilidad, el rendimiento y evitar problemas de caché o conflictos de navegador.

En su lugar, **los gráficos se generan al 100% en el servidor mediante PHP y CSS nativo**:
1. El controlador en PHP obtiene los datos de previsión y calcula la temperatura máxima del conjunto de datos.
2. Basándose en este máximo, PHP calcula el porcentaje proporcional que representa cada temperatura.
3. Este porcentaje se inyecta directamente como anchura (`width: X%`) en barras horizontales creadas con etiquetas `<div>` de HTML.
4. Las animaciones visuales, gradientes de color (`linear-gradient`) y bordes redondeados se aplican usando clases y estilos de **CSS3 nativo** incrustado, generando un resultado estético, un diseño adaptable (responsive) y extremadamente rápido en cargar.

---

## 4. Seguridad y Validación

- **Contra Inyección SQL:**  
  `climaDAO` usa **sentencias preparadas** (`prepare`, `bindParam`), asegurando que los parámetros insertados por el usuario no comprometan el gestor de bases de datos.

- **Contra XSS:**  
  El controlador utiliza `htmlspecialchars()` para las cadenas de salida y `strip_tags()` para limpiar texto entrante.

- **Validación de Tipos:**  
  Se fuerza el tipado con `floatval()` en `obtenerDatosNavegacion()` para confirmar la naturaleza numérica de las coordenadas antes de reenviarlas a la API.

---

## 5. Implementación a través de AWS (EC2)

Para el despliegue de la aplicación en la nube, se ha utilizado los servicios de Amazon Web Services.

### 5.1. Instanciación del servidor

Se ha configurado una instancia en el servicio EC2 (Elastic Compute Cloud) cumpliendo con los siguientes parámetros:

- **Tipo de instancia:** t3.micro, recursos de la Capa Gratuita (Free Tier) de AWS.

- **Sistema Operativo:** Ubuntu Server 22.04 LTS.

- **Configuración de Red:** Se han habilitado los puertos 80 (HTTP), 443 (HTTPS) y 22 (SSH) en el Grupo de Seguridad para permitir el acceso web y la administración remota.

### 5.2. Clonación del repositorio y preparación

Una vez establecida la conexión SSH con la instancia, se procede a la descarga del código fuente desde el repositorio oficial:

```bash
# Actualización del sistema e instalación de git
sudo apt update -y
sudo apt install git -y

# Clonación del proyecto
git clone https://github.com/jiglesiasr05/AppClima
cd AppClima
```
En el archivo debemos añadir la clave api conseguida en https://openweathermap.org/

### 5.3. Despliegue con Docker

Para garantizar que la aplicación funcione de forma idéntica al entorno de desarrollo, se utiliza Docker.
El despliegue se realiza mediante el contenedor definido en el proyecto:

```bash
# Instalación de Docker
sudo apt install docker -y
sudo service docker start

# Construcción y despliegue del contenedor
docker-compose up -d
```

### 5.4. Acceso y DNS

El servicio se encuentra totalmente operativo en la siguiente URL:  
URL de acceso: [http://jaimeiglesias.myddns.me/](http://jaimeiglesias.myddns.me/)

