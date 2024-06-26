<?php
require_once "db\db.php";
$sql = "SELECT * FROM clientes";
$resultado = $conn->query($sql);
$clientes = [];

if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $clientes[] = $row;
    }
}

// Cerrar conexión
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/clientes.css">


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
                <div class="row mb-3">
                    <div class="col-lg-12 col-md-6 mt-3">
                        <form action="" method="get" class="d-flex">
                            <div class="col-10 ms-2">
                                <input type="text" class="form-control" name="busqueda" id="busqueda" placeholder="Buscar por artículo o código">
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistrarCliente">
                                    <i class="fa-solid fa-circle-plus"></i>
                                    Añadir nuevo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-6 ms-3 mb-2">
                        <h4>Clientes</h4>
                    </div>
                </div>
                <div class="col-lg-11 col-md-6 ms-3">
                    <ul class="list-group list-group-flush clientes-lista" id="listaClientes">
                        <?php foreach ($clientes as $cliente) : ?>
                            <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" data-dni="<?php echo $cliente['dni']; ?>">
                                <span class="cliente-nombre">
                                    <?php echo $cliente['nombre'] . ' ' . $cliente['apellido']; ?>
                                </span>
                                <div class="cliente-acciones">
                                    <button class="btn btn-primary ms-2 btn-detalles" data-bs-toggle="modal" data-bs-target="#modalDetalleCliente">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </button>
                                    <button class="btn btn-secondary ms-2 btn-modificar" data-bs-toggle="modal" data-bs-target="#modalModificarCliente">
                                        <i class="fa-solid fa-user-pen"></i>
                                    </button>
                                    <button class="btn btn-danger ms-2 btn-eliminar" data-bs-toggle="modal">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Modal para mostrar detalles del cliente -->
                <div class="modal fade" id="modalDetalleCliente" tabindex="-1" aria-labelledby="modalDetalleClienteLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalDetalleClienteLabel">Detalles del Cliente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>DNI:</strong> <span id="detalleDNI"></span></p>
                                <p><strong>Nombre:</strong> <span id="detalleNombre"></span></p>
                                <p><strong>Apellido:</strong> <span id="detalleApellido"></span></p>
                                <p><strong>Teléfono:</strong> <span id="detalleTelefono"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal para el registro de clientes -->
                <div class="modal fade" id="modalRegistrarCliente" tabindex="-1" aria-labelledby="modalRegistrarClienteLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalRegistrarClienteLabel">Registrar Cliente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="clienteForm" action="javascript:void(0);" method="POST">
                                    <div class="mb-3">
                                        <label for="dni" class="form-label">DNI</label>
                                        <input type="text" class="form-control" id="dni" name="dni" maxlength="8" pattern="\d{8}" required>
                                        <div id="dni-error" class="text-danger"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="apellido" class="form-label">Apellido</label>
                                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para mensaje de éxito -->
                <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                Cliente registrado correctamente.
                            </div>
                        </div>
                    </div>
                </div>




                <!-- Modal para modificar cliente -->
                <div class="modal fade" id="modalModificarCliente" tabindex="-1" aria-labelledby="modalModificarClienteLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalModificarClienteLabel">Modificar Cliente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="formModificarCliente" action="db\modificar_cliente.php" method="POST">
                                    <div class="mb-3">
                                        <label for="modificarDni" class="form-label">DNI</label>
                                        <input type="text" class="form-control" id="modificarDni" name="dni" maxlength="8" required>
                                        <div id="dni-error" class="text-danger"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="modificarNombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="modificarNombre" name="nombre" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="modificarApellido" class="form-label">Apellido</label>
                                        <input type="text" class="form-control" id="modificarApellido" name="apellido" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="modificarTelefono" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" id="modificarTelefono" name="telefono" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="js/load-content.js"></script>
    <script src="js/cliente.js"></script>

</body>

</html>