<?php
// Incluye la conexión a la base de datos
include 'db\db.php';

// Verifica si la conexión se ha establecido correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Realiza una consulta a la base de datos
$sql = "SELECT * FROM servicios";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="sb-nav-fixed">
    <div id="navbar-container">
        <?php include 'navbar.html'; ?>
    </div>
    <div class="wrapper">
        <div id="sidebar-container">
            <?php include 'sidebar.html'; ?>
        </div>
        <div class="main">
            <div id="main-content">
                <h1 class="ms-4 mt-4">Estadísticas</h1>
                <div class="container">
                <div class="row">
                <!-- Gráfico de clientes -->
                <div class="col-md-6">
                    <h3>Clientes</h3>
                    <select id="filterClients" class="form-select mb-3">
                        <option value="dia">Diario</option>
                        <option value="semana">Semanal</option>
                        <option value="mes">Mensual</option>
                    </select>
                    <canvas id="clientsChart" width="400" height="250"></canvas>
                </div>
                <!-- Gráfico de ingresos -->
                <div class="col-md-6">
                    <h3>Ingresos</h3>
                    <select id="filterRevenue" class="form-select mb-3">
                        <option value="dia">Diario</option>
                        <option value="semana">Semanal</option>
                        <option value="mes">Mensual</option>
                    </select>
                    <canvas id="revenueChart" width="400" height="250"></canvas>
                </div>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="js/load-content.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Datos de ejemplo
        const dailyDataClients = [5, 10, 15, 7, 9, 14, 10];
        const weeklyDataClients = [30, 35, 40, 25, 45, 50, 60];
        const monthlyDataClients = [150, 160, 170, 180, 190, 200, 210, 200, 195, 215,  220, 230];


        const dailyDataRevenue = [100, 200, 150, 180, 220, 300, 250];
        const weeklyDataRevenue = [700, 800, 900, 1000, 1100, 1200, 1300];
        const monthlyDataRevenue = [3000, 3500, 4000, 4500, 5000, 5500, 4000, 4300, 4600, 3500, 3900, 4200];

        // Función para generar etiquetas de semanas
        function getWeeklyLabels(year, month) {
            const labels = [];
            const date = new Date(year, month, 1);
            
            // Obtiene el número de días en el mes
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            
            // Crea etiquetas para cada semana
            for (let startDay = 1; startDay <= daysInMonth; startDay += 7) {
                const endDay = Math.min(startDay + 6, daysInMonth); // Asegúrate de no sobrepasar el número de días
                const weekLabel = `${startDay}-${endDay} de ${date.toLocaleString('es-ES', { month: 'long' })}`;
                labels.push(weekLabel);
            }
            
            return labels;
        }

        // Función para actualizar el gráfico
        function updateChart(chart, newData, newLabels) {
            chart.data.labels = newLabels;
            chart.data.datasets[0].data = newData;
            chart.update();
        }

        // Configuración del gráfico de clientes
        const clientsCtx = document.getElementById('clientsChart').getContext('2d');
        const clientsChart = new Chart(clientsCtx, {
            type: 'bar',
            data: {
                labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Clientes',
                    data: dailyDataClients,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Configuración del gráfico de ingresos
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Ingresos ($)',
                    data: dailyDataRevenue,
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Manejo de cambios en el filtro de clientes
        document.getElementById('filterClients').addEventListener('change', function() {
            const filter = this.value;
            const currentYear = new Date().getFullYear();
            const currentMonth = new Date().getMonth(); // 0 = Enero, 1 = Febrero, etc.
            
            if (filter === 'dia') {
                updateChart(clientsChart, dailyDataClients, ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom']);
            } else if (filter === 'semana') {
                const weeklyLabels = getWeeklyLabels(currentYear, currentMonth);
                updateChart(clientsChart, weeklyDataClients, weeklyLabels);
            } else if (filter === 'mes') {
                updateChart(clientsChart, monthlyDataClients, ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']);
            }
        });

        // Manejo de cambios en el filtro de ingresos
        document.getElementById('filterRevenue').addEventListener('change', function() {
            const filter = this.value;
            const currentYear = new Date().getFullYear();
            const currentMonth = new Date().getMonth(); // 0 = Enero, 1 = Febrero, etc.
            
            if (filter === 'dia') {
                updateChart(revenueChart, dailyDataRevenue, ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom']);
            } else if (filter === 'semana') {
                const weeklyLabels = getWeeklyLabels(currentYear, currentMonth);
                updateChart(revenueChart, weeklyDataRevenue, weeklyLabels);
            } else if (filter === 'mes') {
                updateChart(revenueChart, monthlyDataRevenue, ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']);
            }
        });
    </script>
</body>
</html>

