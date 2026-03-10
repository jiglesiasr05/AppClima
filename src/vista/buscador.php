<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador de Clima</title>
    <style>
        :root { 
            --primary: #4facfe; 
        --secondary: #00f2fe; 
        }
        body { 
        font-family: 'Segoe UI', sans-serif; 
        margin: 0; background: #f8f9fa; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        min-height: 100vh; 
        flex-direction: column; 
        }
        .card { background: white; 
        padding: 30px; 
        border-radius: 15px; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
        width: 100%; 
        max-width: 400px; 
        text-align: center; 
        }
        input[type="text"] { 
            width: 100%; 
            padding: 12px; 
            margin: 15px 0; 
            border: 1px solid #ddd; 
            border-radius: 25px; 
            box-sizing: border-box; 
            outline: none; 
        }
        button { 
        background: linear-gradient(135deg, var(--primary), var(--secondary)); 
        color: white; 
        border: none; 
        padding: 12px 25px; 
        border-radius: 25px; 
        cursor: pointer; 
        width: 100%; 
        font-weight: bold; 
        font-size: 1.1rem; 
        }
        .error { 
        color: #e74c3c; 
        margin-bottom: 15px; 
        }
        .history { 
        margin-top: 30px; 
        width: 100%; 
        max-width: 600px; 
        padding: 0 15px; 
    }
        .history-item { 
        background: white; 
        padding: 15px; 
        border-radius: 10px; 
        margin-bottom: 10px; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <div class="card">
        <h2>El Clima</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="index.php" method="POST">
            <input type="hidden" name="accion" value="buscar">
            <input type="text" name="ciudad" placeholder="Introduce una ciudad..." required>
            <button type="submit">Buscar</button>
        </form>
    </div>

    <?php if (!empty($historial)): ?>
    <div class="history">
        <h3 style="text-align: center; color: #555;">Historial de búsquedas</h3>
        <?php foreach ($historial as $registro): ?>
            <div class="history-item">
                <span style="font-weight: bold;"><?php echo htmlspecialchars($registro['ciudad']); ?></span>
                <span><?php echo $registro['temperatura']; ?>°C</span>
                <span style="color: #777; font-size: 0.9rem; text-transform: capitalize;"><?php echo htmlspecialchars($registro['descripcion']); ?></span>
                <span style="color: #aaa; font-size: 0.8rem;"><?php echo date('d/m H:i', strtotime($registro['fecha'])); ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</body>
</html>
