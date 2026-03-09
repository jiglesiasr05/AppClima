<?php $apiClima = new WeatherAPI(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeatherApp - <?php echo $nombre; ?></title>
    <link rel="stylesheet" href="public/css/style.css">
    <style>
        :root { --primary: #4facfe; --secondary: #00f2fe; }
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f8f9fa; }
        .hero-header { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 30px 20px; text-align: center; }
        .nav-wrapper { position: relative; display: flex; justify-content: center; align-items: center; margin-top: 20px; max-width: 800px; margin-left: auto; margin-right: auto; }
        .nav-links { display: flex; gap: 10px; }
        .nav-btn { background: rgba(255,255,255,0.2); border: 1px solid white; color: white; padding: 8px 18px; border-radius: 20px; cursor: pointer; transition: 0.3s; }
        .nav-btn:hover, .nav-btn.active { background: white; color: var(--primary); }
        .back-btn { background: #ff4757; border: 1px solid #ff4757; color: white; padding: 8px; border-radius: 20px; cursor: pointer; transition: 0.3s; font-weight: bold; }
        .back-btn:hover { background: white; color: #ff4757; }
        .container { max-width: 800px; margin: 20px auto; padding: 0 15px; min-height: 60vh; }
        footer { text-align: center; padding: 20px; color: #888; border-top: 1px solid #ddd; margin-top: 40px; }
        .card { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    </style>
    
    <!-- Charts.js se cargará en las vistas donde se necesite -->
    
</head>
<body>
    <header class="hero-header">
        <h1><?php echo htmlspecialchars($nombre); ?></h1>
        <div class="nav-wrapper">
            <div class="nav-links">
                <form action="index.php" method="POST">
                    <input type="hidden" name="accion" value="actual">
                    <input type="hidden" name="lat" value="<?php echo $lat; ?>">
                    <input type="hidden" name="lon" value="<?php echo $lon; ?>">
                    <input type="hidden" name="nombre" value="<?php echo $nombre; ?>">
                    <button type="submit" class="nav-btn <?php echo ($vista == 'actual') ? 'active' : ''; ?>">Ahora</button>
                </form>
                <form action="index.php" method="POST">
                    <input type="hidden" name="accion" value="horas">
                    <input type="hidden" name="lat" value="<?php echo $lat; ?>">
                    <input type="hidden" name="lon" value="<?php echo $lon; ?>">
                    <input type="hidden" name="nombre" value="<?php echo $nombre; ?>">
                    <button type="submit" class="nav-btn <?php echo ($vista == 'horas') ? 'active' : ''; ?>">Por Horas</button>
                </form>
                <form action="index.php" method="POST">
                    <input type="hidden" name="accion" value="semana">
                    <input type="hidden" name="lat" value="<?php echo $lat; ?>">
                    <input type="hidden" name="lon" value="<?php echo $lon; ?>">
                    <input type="hidden" name="nombre" value="<?php echo $nombre; ?>">
                    <button type="submit" class="nav-btn <?php echo ($vista == 'semana') ? 'active' : ''; ?>">Semana</button>
                </form>
                <form action="index.php" method="POST">
                    <input type="hidden" name="accion" value="historial">
                    <input type="hidden" name="lat" value="<?php echo $lat; ?>">
                    <input type="hidden" name="lon" value="<?php echo $lon; ?>">
                    <input type="hidden" name="nombre" value="<?php echo $nombre; ?>">
                    <button type="submit" class="nav-btn <?php echo ($vista == 'historial') ? 'active' : ''; ?>">Historial</button>
                </form>
            </div>
            <form action="index.php" method="GET" style="position: absolute; right: 0;">
                <button type="submit" class="back-btn">Nueva Búsqueda</button>
            </form>
        </div>
    </header>
    <div class="container">