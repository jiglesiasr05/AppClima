<?php 
$vista = 'historial';
include 'vista/header.php'; 
?>
<div class="card">
    <h3 style="text-align: center; color: #555; margin-top: 0;">Historial Completo</h3>
    <?php if (!empty($historial)): ?>
        <?php foreach ($historial as $registro): ?>
            <div style="background: #fdfdfd; padding: 15px; border-radius: 10px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #eee;">
                <span style="font-weight: bold; min-width: 120px;"><?php echo htmlspecialchars($registro['ciudad']); ?></span>
                <span style="font-size: 1.1rem; color: #333; font-weight: bold;"><?php echo $registro['temperatura']; ?>°C</span>
                <span style="color: #777; font-size: 0.9rem; text-transform: capitalize; flex: 1; text-align: center;"><?php echo htmlspecialchars($registro['descripcion']); ?></span>
                <span style="color: #aaa; font-size: 0.8rem;"><?php echo date('d/m H:i', strtotime($registro['fecha'])); ?></span>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align: center; color: #777;">No hay consultas en el historial.</p>
    <?php endif; ?>
</div>
<?php include 'vista/footer.php'; ?>
