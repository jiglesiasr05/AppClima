<?php 
$vista = 'actual';
include 'vista/header.php'; 
?>
<div class="card" style="text-align: center;">
    <h2>Clima en <?php echo htmlspecialchars($nombre); ?></h2>
    <?php if (isset($clima['weather'][0]['icon'])): ?>
    <img src="<?php echo $apiClima->obtenerUrlIcono($clima['weather'][0]['icon'], '@4x'); ?>" alt="Icono del clima">
    <?php endif; ?>
    <h1 style="font-size: 4rem; margin: 10px 0; color: var(--primary);"><?php echo round($clima['main']['temp']); ?>°C</h1>
    <p style="font-size: 1.2rem; color: #555; text-transform: capitalize;"><?php echo htmlspecialchars($clima['weather'][0]['description']); ?></p>
    
    <div style="display: flex; justify-content: center; gap: 20px; margin-top: 20px; color: #666;">
        <div><strong>Humedad:</strong> <?php echo $clima['main']['humidity']; ?>%</div>
        <div><strong>Viento:</strong> <?php echo $clima['wind']['speed']; ?> m/s</div>
    </div>
</div>
<?php include 'vista/footer.php'; ?>
