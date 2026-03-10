<?php 
$vista = 'semana';
include 'vista/header.php'; 

$dias_filtrados = [];
foreach($pronostico['list'] as $p) {
    // Filtramos para obtener solo el pronóstico del mediodía
    if(strpos($p['dt_txt'], '12:00:00') !== false) {
        $dias_filtrados[] = [
            'label' => $apiClima->traducirDia($p['dt_txt']),
            'temp'  => round($p['main']['temp']),
            'icon'  => $apiClima->obtenerUrlIcono($p['weather'][0]['icon']),
            'desc'  => $p['weather'][0]['description']
        ];
    }
}


$temps = array_column($dias_filtrados, 'temp');
$max_temp = (!empty($temps) ? max($temps) : 0) + 5;
?>

<div class="card" style="margin-bottom: 20px;">
    <h3 style="margin-top: 0; color: #555;">Temperatura Semanal (°C)</h3>
    <div style="background: #f8f9fa; border-radius: 10px; padding: 20px; margin-top: 15px;">
        <?php if(!empty($dias_filtrados)): ?>
            <?php foreach($dias_filtrados as $dia): 
                $percentage = max(($dia['temp'] / $max_temp) * 100, 15);
            ?>
            <div style="display: flex; align-items: center; margin-bottom: 12px;">
                <div style="width: 100px; font-weight: bold; color: #555; text-transform: capitalize;">
                    <?php echo $dia['label']; ?>
                </div>
                <div style="flex: 1; background: #e9ecef; border-radius: 15px; overflow: hidden; height: 30px;">
                    <div style="width: <?php echo $percentage; ?>%; background: linear-gradient(90deg, #4facfe, #00f2fe); height: 100%; display: flex; align-items: center; justify-content: flex-end; padding-right: 15px; box-sizing: border-box; color: white; font-weight: bold; border-radius: 15px;">
                        <?php echo $dia['temp']; ?>°C
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay datos disponibles para mostrar el gráfico.</p>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <h3 style="margin-top: 0; color: #555;">Pronóstico de la semana</h3>
    <?php foreach($dias_filtrados as $dia): ?>
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #f0f0f0;">
            <span style="font-weight: bold; width: 100px; text-transform: capitalize;"><?php echo $dia['label']; ?></span>
            <img src="<?php echo $dia['icon']; ?>" style="width: 45px;">
            <span style="flex: 1; margin-left: 15px; font-size: 0.9rem; color: #777; text-transform: capitalize;">
                <?php echo $dia['desc']; ?>
            </span>
            <span style="font-weight: bold; color: #333; font-size: 1.1rem;"><?php echo $dia['temp']; ?>°C</span>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'vista/footer.php'; ?>