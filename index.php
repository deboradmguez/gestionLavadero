<?php
session_start();
if (!isset($_SESSION['idusuario'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Lavadero de Autos</title>
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
            <div id="main-content" class="container-fluid px-4">
                <h2 class="mt-4">Dashboard</h2>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Gestión de Turnos</li>
                </ol>
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-primary text-white mb-4">
                            <div class="card-body">Turnos de Hoy</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="#">Ver Detalles</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-danger text-white mb-4">
                            <div class="card-body">Servicios Pendientes</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="#">Ver Detalles</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-success text-white mb-4">
                            <div class="card-body">Ingresos del Día</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="#">Ver Detalles</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-secondary text-white mb-4">
                            <div class="card-body">Clientes Frecuentes</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="#">Ver Detalles</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-8">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Turnos del Día
                                </div>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#agregarTurnoModal">
                                    <i class="fas fa-plus"></i> Agregar Turno
                                </button>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Hora</th>
                                            <th>Cliente</th>
                                            <th>Vehículo</th>
                                            <th>Servicio</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Incluir el archivo de conexión a la base de datos
                                        require_once 'C:\Users\debod\OneDrive\Desktop\gestionLavadero\db\db.php';

                                        $sql = "SELECT t.hora, 
                                                    CONCAT(c.nombre, ' ', c.apellido) AS cliente, 
                                                    CONCAT(v.tipo, ' ', v.modelo, ' (', v.patente, ')') AS vehiculo, 
                                                    s.servicio AS servicio,
                                                    t.estado 
                                                FROM turnos t 
                                                JOIN clientes c ON t.idcliente = c.idcliennte 
                                                JOIN vehiculos v ON t.idvehiculo = v.patente 
                                                JOIN servicios s ON t.idservicio = s.idservicio
                                                WHERE t.fecha = CURDATE() 
                                                ORDER BY t.hora";

                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $estadoClass = '';
                                                switch ($row["estado"]) {
                                                    case 'Pendiente':
                                                        $estadoClass = 'warning';
                                                        break;
                                                    case 'En Proceso':
                                                        $estadoClass = 'info';
                                                        break;
                                                    case 'Completado':
                                                        $estadoClass = 'success';
                                                        break;
                                                    case 'Cancelado':
                                                        $estadoClass = 'danger';
                                                        break;
                                                }
                                                echo "<tr>
                                                        <td>" . $row["hora"] . "</td>
                                                        <td>" . $row["cliente"] . "</td>
                                                        <td>" . $row["vehiculo"] . "</td>
                                                        <td>" . $row["servicio"] . "</td>
                                                        <td><span class='badge bg-{$estadoClass}'>" . $row["estado"] . "</span></td>
                                                    </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='5'>No hay turnos para hoy</td></tr>";
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-pie me-1"></i>
                                Servicios Más Solicitados
                            </div>
                            <div class="card-body"><canvas id="serviciosChart" width="100%" height="50"></canvas></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Agregar Turno -->
    <div class="modal fade" id="agregarTurnoModal" tabindex="-1" aria-labelledby="agregarTurnoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarTurnoModalLabel">Agregar Nuevo Turno</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                            <input type="hidden" id="clienteSeleccionado" name="clienteSeleccionado">
                        </div>
                        <div id="resultadosBusqueda" class="list-group mt-2" style="display:none;"></div>
                        <div class="mb-3"><form id="formAgregarTurno">
                        <div class="mb-3">
                            <label for="clienteBusqueda" class="form-label">Buscar Cliente (DNI o Nombre)</label>
                            <input type="text" class="form-control" id="clienteBusqueda" name="clienteBusqueda" placeholder="Ingrese DNI o nombre del cliente" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="vehiculo" class="form-label">Vehículo</label>
                            <select class="form-select" id="vehiculo" name="vehiculo" required>
                                <option value="">Seleccione un vehículo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="servicio" class="form-label">Servicios</label>
                            <select class="form-select" id="servicio" name="servicio[]" multiple required>
                                <?php
                                // Cargar servicios desde la base de datos
                                $sqlServicios = "SELECT idservicio, servicio FROM servicios";
                                $resultServicios = $conn->query($sqlServicios);

                                if ($resultServicios->num_rows > 0) {
                                    while ($rowServicio = $resultServicios->fetch_assoc()) {
                                        echo "<option value='" . $rowServicio['idservicio'] . "'>" . $rowServicio['servicio'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No hay servicios disponibles</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="horaTurno" class="form-label">Hora del Turno</label>
                            <input type="time" class="form-control" id="horaTurno" name="horaTurno" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarTurno">Guardar Turno</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="js/load-content.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="js/turnos.js"></script>
</body>

</html>