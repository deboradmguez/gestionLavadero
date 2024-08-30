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
    <title>Servicios</title>
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
                <h1 class="ms-4 mt-4">Servicios</h1>
                
                <div class="d-flex flex-wrap align-items-center ms-4 me-4 mt-3">
                                <div class="col-lg-6 col-md-6 flex-grow-1">
                                    <input type="text" class="form-control" name="busqueda" id="busqueda" placeholder="Buscar servicio">
                                </div>
                                <div class="ms-1">
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                                        <i class="fa-solid fa-circle-plus"></i>
                                        Nuevo
                                    </button>
                                </div>
                    </div>

                <!-- Modal para agregar servicios -->
                <div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title ms-4" id="addServiceModalLabel">Añadir Servicio</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="addServiceForm" method="post" action="db\services\crearservicio.php">
                                    <div class="mb-3">
                                        <label for="servicio" class="form-label">Servicio</label>
                                        <input type="text" class="form-control" id="servicio" name="servicio" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="precio" class="form-label">Precio</label>
                                        <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tiempo" class="form-label">Tiempo (minutos)</label>
                                        <input type="number" class="form-control" id="tiempo" name="tiempo" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Agregar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para editar servicios -->
                <div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="editServiceModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editServiceModalLabel">Editar Servicio</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editServiceForm" method="post" action="\db\services\editarservicio.php">
                                    <input type="hidden" name="idservicio" id="edit-idservicio">
                                    <div class="mb-3">
                                        <label for="edit-servicio" class="form-label">Servicio</label>
                                        <input type="text" class="form-control" id="edit-servicio" name="servicio" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-precio" class="form-label">Precio</label>
                                        <input type="number" step="0.01" class="form-control" id="edit-precio" name="precio" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-tiempo" class="form-label">Tiempo (minutos)</label>
                                        <input type="number" class="form-control" id="edit-tiempo" name="tiempo" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cards de servicios -->
                <div class="row row-cols-1 row-cols-md-3 g-4 mt-3 ms-4 me-4" id="servicios-container">
                    <?php
                    // Verifica si la variable $conn está definida y es un objeto
                    if (isset($conn) && $conn instanceof mysqli) {
                        $sql = "SELECT * FROM servicios";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="col servicio-item">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">' . $row["servicio"] . '</h5>
                                                <p class="card-text">Precio: $' . $row["precio"] . '</p>
                                                <p class="card-text">Tiempo: ' . $row["tiempo"] . ' minutos</p>
                                                <div class="d-flex justify-content-start gap-2">
                                                    <button class="btn btn-warning btn-sm" onclick="editService(' . $row["idservicio"] . ', \'' . $row["servicio"] . '\', ' . $row["precio"] . ', ' . $row["tiempo"] . ')">Editar</button>
                                                    <a href="db\services\eliminarservicio.php?id=' . $row["idservicio"] . '" class="btn btn-danger btn-sm">Eliminar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                            }
                        } else {
                            echo '<div class="col">
                                    <div class="alert alert-warning" role="alert">
                                        No hay servicios disponibles.
                                    </div>
                                </div>';
                        }
                    } else {
                        echo '<div class="col">
                                <div class="alert alert-danger" role="alert">
                                    Error de conexión a la base de datos.
                                </div>
                            </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="js/load-content.js"></script>
    <script>
        function editService(id, servicio, precio, tiempo) {
            document.getElementById('edit-idservicio').value = id;
            document.getElementById('edit-servicio').value = servicio;
            document.getElementById('edit-precio').value = precio;
            document.getElementById('edit-tiempo').value = tiempo;
            var editModal = new bootstrap.Modal(document.getElementById('editServiceModal'), {});
            editModal.show();
        }

        // Función de búsqueda
        document.getElementById('busqueda').addEventListener('input', function() {
            let busqueda = this.value.toLowerCase();
            let servicios = document.getElementsByClassName('servicio-item');
            
            for (let servicio of servicios) {
                let titulo = servicio.querySelector('.card-title').textContent.toLowerCase();
                if (titulo.includes(busqueda)) {
                    servicio.style.display = '';
                } else {
                    servicio.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>
