<?php
require_once 'config.php';
requireLogin();

// Get contact messages from database
$db = getDB();
$messages = [];
$result = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f3f4f6;
        }
        
        .header {
            background: #1e40af;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 24px;
        }

        .nav-links {
            display: flex;
            gap: 15px;
        }

        .nav-links a {
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .nav-links a:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .logout-btn {
            background: #f97316;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
            border: none;
            cursor: pointer;
        }
        
        .logout-btn:hover {
            background: #ea580c;
        }
        
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stat-card h3 {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .stat-card .number {
            color: #1e40af;
            font-size: 32px;
            font-weight: bold;
        }
        
        .messages-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .messages-section h2 {
            color: #1e40af;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: #f3f4f6;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        tr:hover {
            background: #f9fafb;
        }
        
        .no-messages {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📧 Mensajes</h1>
        <div class="nav-links">
            <a href="citas.php">Ver Citas</a>
            <form method="POST" action="logout.php" style="display: inline;">
                <button type="submit" class="logout-btn">Cerrar Sesión</button>
            </form>
        </div>
    </div>
    
    <div class="container">
        <div class="stats">
            <div class="stat-card">
                <h3>MENSAJES TOTALES</h3>
                <div class="number"><?php echo count($messages); ?></div>
            </div>
            <div class="stat-card">
                <h3>MENSAJES HOY</h3>
                <div class="number">
                    <?php 
                    $today = date('Y-m-d');
                    $todayCount = 0;
                    foreach ($messages as $msg) {
                        if (date('Y-m-d', strtotime($msg['created_at'])) === $today) {
                            $todayCount++;
                        }
                    }
                    echo $todayCount;
                    ?>
                </div>
            </div>
            <div class="stat-card">
                <h3>ÚLTIMA SEMANA</h3>
                <div class="number">
                    <?php 
                    $weekAgo = date('Y-m-d', strtotime('-7 days'));
                    $weekCount = 0;
                    foreach ($messages as $msg) {
                        if ($msg['created_at'] >= $weekAgo) {
                            $weekCount++;
                        }
                    }
                    echo $weekCount;
                    ?>
                </div>
            </div>
        </div>
        
        <div class="messages-section">
            <h2>Mensajes de Contacto</h2>
            <?php if (empty($messages)): ?>
                <div class="no-messages">
                    No hay mensajes aún
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Mensaje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $msg): ?>
                        <tr>
                            <td><?php echo date('d/m/Y H:i', strtotime($msg['created_at'])); ?></td>
                            <td><?php echo htmlspecialchars($msg['name']); ?></td>
                            <td><?php echo htmlspecialchars($msg['email']); ?></td>
                            <td><?php echo htmlspecialchars($msg['phone']); ?></td>
                            <td><?php echo htmlspecialchars($msg['message']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
