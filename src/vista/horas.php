<?php 
$vista = 'horas';
include 'vista/header.php'; 
$pronosticos_limpios = [];
$contador = 0;
foreach($pronostico['list'] as $p) {
    if($contador >= 8) break;
    
    $pronosticos_limpios[] = [
        'hora' => date('H:i', strtotime($p['dt_txt'])),
        'temp' => round($p['main']['temp']),
        'icon' => $apiClima->obtenerUrlIcono($p['weather'][0]['icon']),
        'desc' => $p['weather'][0]['description']
    ];
    $contador++;
}

// Cálculo para la gráfica de barras
$temps = array_column($pronosticos_limpios, 'temp');
$max_temp = (!empty($temps) ? max($temps) : 0) + 5;
?>

<div class="card" style="margin-bottom: 20px;">
    <h3 style="margin-top: 0; color: #555;">Evolución de la Temperatura (°C)</h3>
    <div style="background: #f8f9fa; border-radius: 10px; padding: 20px; margin-top: 15px;">
        <?php foreach($pronosticos_limpios as $item): 
            $percentage = max(($item['temp'] / $max_temp) * 100, 15);
        ?>
        <div style="display: flex; align-items: center; margin-bottom: 12px;">
            <div style="width: 100px; font-weight: bold; color: #555;"><?php echo $item['hora']; ?> horas</div>
            <div style="flex: 1; background: #e9ecef; border-radius: 15px; overflow: hidden; height: 30px;">
                <div style="width: <?php echo $percentage; ?>%; background: linear-gradient(90deg, #4facfe, #00f2fe); height: 100%; display: flex; align-items: center; justify-content: flex-end; padding-right: 15px; box-sizing: border-box; color: white; font-weight: bold; border-radius: 15px;">
                    <?php echo $item['temp']; ?>°C
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="card">
    <h3 style="margin-top: 0; color: #555;">Pronóstico por horas</h3>
    <?php foreach($pronosticos_limpios as $item): ?>
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #f0f0f0;">
            <span style="font-weight: bold; width: 100px;"><?php echo $item['hora']; ?></span>
            <img src="<?php echo $item['icon']; ?>" style="width: 45px;">
            <span style="flex: 1; margin-left: 15px; font-size: 0.9rem; color: #777; text-transform: capitalize;">
                <?php echo $item['desc']; ?>
            </span>
            <span style="font-weight: bold; color: #333; font-size: 1.1rem;"><?php echo $item['temp']; ?>°C</span>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'vista/footer.php'; ?>