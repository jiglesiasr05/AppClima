<?php 
$vista = 'semana';
include 'vista/header.php'; 

$datos_labels = [];
$datos_temp = [];
foreach($pronostico['list'] as $pronosticoDia) {
    if(strpos($pronosticoDia['dt_txt'], '12:00:00') !== false) {
        $dia_semana_raw = $apiClima->traducirDia($pronosticoDia['dt_txt']);
        $datos_labels[] = $dia_semana_raw;
        $datos_temp[] = round($pronosticoDia['main']['temp']);
    }
}
?>

<div class="card" style="margin-bottom: 20px;">
    <h3 style="margin-top: 0; color: #555;">Temperatura Semanal (°C)</h3>
    <div style="background: #f8f9fa; border-radius: 10px; padding: 20px; margin-top: 15px;">
        <?php
        if(!empty($datos_temp)) {
            $max_temp = max($datos_temp) + 5; // Margen superior
            
            foreach($datos_labels as $index => $label): 
                $temp = $datos_temp[$index];
                $percentage = ($temp / $max_temp) * 100;
                if($percentage < 15) $percentage = 15; // Mínimo visual
        ?>
        <div style="display: flex; align-items: center; margin-bottom: 12px;">
            <div style="width: 100px; font-weight: bold; color: #555; text-transform: capitalize;"><?php echo $label; ?></div>
            <div style="flex: 1; background: #e9ecef; border-radius: 15px; overflow: hidden; height: 30px; box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);">
                <div style="width: <?php echo $percentage; ?>%; background: linear-gradient(90deg, #4facfe, #00f2fe); height: 100%; display: flex; align-items: center; justify-content: flex-end; padding-right: 15px; box-sizing: border-box; color: white; font-weight: bold; text-shadow: 1px 1px 1px rgba(0,0,0,0.2); border-radius: 15px;">
                    <?php echo $temp; ?>°C
                </div>
            </div>
        </div>
        <?php 
            endforeach; 
        } else {
            echo "<p>No hay datos disponibles para mostrar el gráfico.</p>";
        }
        ?>
    </div>
</div>


<div class="card">
    <h3 style="margin-top: 0; color: #555;">Pronóstico de la semana</h3>
    <?php foreach($pronostico['list'] as $pronosticoDia): 
        if(strpos($pronosticoDia['dt_txt'], '12:00:00') !== false): ?>
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #f0f0f0;">
            <span style="font-weight: bold; width: 100px; text-transform: capitalize;"><?php echo $apiClima->traducirDia($pronosticoDia['dt_txt']); ?></span>
            <img src="<?php echo $apiClima->obtenerUrlIcono($pronosticoDia['weather'][0]['icon']); ?>" style="width: 45px;">
            <span style="flex: 1; margin-left: 15px; font-size: 0.9rem; color: #777; text-transform: capitalize;">
                <?php echo $pronosticoDia['weather'][0]['description']; ?>
            </span>
            <span style="font-weight: bold; color: #333; font-size: 1.1rem;"><?php echo round($pronosticoDia['main']['temp']); ?>°C</span>
        </div>
    <?php endif; endforeach; ?>
</div>

<?php include 'vista/footer.php'; ?>