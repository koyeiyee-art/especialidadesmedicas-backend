<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

// Fetch all appointments
$stmt = $conn->prepare("SELECT * FROM citas ORDER BY created_at DESC");
$stmt->execute();
$citas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Count statistics
$total_citas = count($citas);
$today = date('Y-m-d');
$citas_today = array_filter($citas, function($cita) use ($today) {
    return date('Y-m-d', strtotime($cita['created_at'])) === $today;
});
$citas_today_count = count($citas_today);

$week_ago = date('Y-m-d', strtotime('-7 days'));
$citas_week = array_filter($citas, function($cita) use ($week_ago) {
    return date('Y-m-d', strtotime($cita['created_at'])) >= $week_ago;
});
$citas_week_count = count($citas_week);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citas - Panel de Administración</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            color: #333;
            font-size: 28px;
        }

        .nav-links {
            display: flex;
            gap: 15px;
        }

        .nav-links a, .logout-btn {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .nav-links a:hover, .logout-btn:hover {
            background: #764ba2;
            transform: translateY(-2px);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .stat-card .number {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
        }

        .appointments-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .appointments-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .appointment-card {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .appointment-card:hover {
            border-color: #667eea;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        }

        .appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .appointment-info {
            flex: 1;
        }

        .appointment-info h3 {
            color: #333;
            margin-bottom: 5px;
        }

        .appointment-info p {
            color: #666;
            margin: 5px 0;
        }

        .appointment-date {
            color: #999;
            font-size: 14px;
        }

        .insurance-badge {
            display: inline-block;
            padding: 5px 15px;
            background: #4CAF50;
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .no-insurance-badge {
            display: inline-block;
            padding: 5px 15px;
            background: #999;
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .documents-section {
            margin-top: 15px;
        }

        .documents-section h4 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .document-link {
            display: inline-block;
            margin: 5px 10px 5px 0;
            padding: 8px 15px;
            background: #f0f0f0;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .document-link:hover {
            background: #667eea;
            color: white;
        }

        .no-appointments {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📅 Citas</h1>
        <div class="nav-links">
            <a href="dashboard.php">Mensajes</a>
            <form method="POST" action="logout.php" style="display: inline;">
                <button type="submit" class="logout-btn">Cerrar Sesión</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="stats-container">
            <div class="stat-card">
                <h3>Total de Citas</h3>
                <div class="number"><?php echo $total_citas; ?></div>
            </div>
            <div class="stat-card">
                <h3>Citas de Hoy</h3>
                <div class="number"><?php echo $citas_today_count; ?></div>
            </div>
            <div class="stat-card">
                <h3>Últimos 7 Días</h3>
                <div class="number"><?php echo $citas_week_count; ?></div>
            </div>
        </div>

        <div class="appointments-container">
            <h2>Todas las Citas</h2>
            
            <?php if (empty($citas)): ?>
                <div class="no-appointments">
                    <p>No hay citas registradas</p>
                </div>
            <?php else: ?>
                <?php foreach ($citas as $cita): ?>
                    <div class="appointment-card">
                        <div class="appointment-header">
                            <div class="appointment-info">
                                <h3><?php echo htmlspecialchars($cita['name']); ?></h3>
                                <p>📧 <?php echo htmlspecialchars($cita['email']); ?></p>
                                <p>📱 <?php echo htmlspecialchars($cita['phone']); ?></p>
                                <p class="appointment-date">📅 <?php echo date('d/m/Y H:i', strtotime($cita['created_at'])); ?></p>
                            </div>
                            <div>
                                <?php if ($cita['use_insurance']): ?>
                                    <span class="insurance-badge">Con Seguro</span>
                                <?php else: ?>
                                    <span class="no-insurance-badge">Sin Seguro</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div style="margin-top: 10px;">
                            <strong>Mensaje:</strong>
                            <p><?php echo nl2br(htmlspecialchars($cita['message'])); ?></p>
                        </div>

                        <?php if ($cita['use_insurance']): ?>
                            <div class="documents-section">
                                <h4>📄 Documentos Adjuntos</h4>
                                
                                <?php if ($cita['is_underage'] && $cita['birth_certificate']): ?>
                                    <a href="<?php echo htmlspecialchars($cita['birth_certificate']); ?>" target="_blank" class="document-link">
                                        Partida de Nacimiento
                                    </a>
                                <?php endif; ?>

                                <?php if ($cita['cedula_titular']): ?>
                                    <a href="<?php echo htmlspecialchars($cita['cedula_titular']); ?>" target="_blank" class="document-link">
                                        Cédula del Titular
                                    </a>
                                <?php endif; ?>

                                <?php if ($cita['cedula_beneficiario']): ?>
                                    <a href="<?php echo htmlspecialchars($cita['cedula_beneficiario']); ?>" target="_blank" class="document-link">
                                        Cédula del Beneficiario
                                    </a>
                                <?php endif; ?>

                                <?php if ($cita['referencia_medica']): 
                                    $referencias = json_decode($cita['referencia_medica'], true);
                                    if ($referencias && is_array($referencias)):
                                        foreach ($referencias as $idx => $ref): ?>
                                            <a href="<?php echo htmlspecialchars($ref); ?>" target="_blank" class="document-link">
                                                Referencia Médica <?php echo $idx + 1; ?>
                                            </a>
                                        <?php endforeach;
                                    endif;
                                endif; ?>

                                <?php if ($cita['indicaciones_medicas']): 
                                    $indicaciones = json_decode($cita['indicaciones_medicas'], true);
                                    if ($indicaciones && is_array($indicaciones)):
                                        foreach ($indicaciones as $idx => $ind): ?>
                                            <a href="<?php echo htmlspecialchars($ind); ?>" target="_blank" class="document-link">
                                                Indicación Médica <?php echo $idx + 1; ?>
                                            </a>
                                        <?php endforeach;
                                    endif;
                                endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
