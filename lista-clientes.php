<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
                        <form action="" method="get">
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="col-lg-6 col-md-6 ms-4 flex-grow-1">
                                    <input type="text" class="form-control" name="busqueda" id="busqueda" placeholder="Buscar por nombre o dni">
                                </div>

                                <div class="ms-1">
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalRegistrarCliente">
                                        <i class="fa-solid fa-circle-plus"></i>
                                        Nuevo
                                    </button>
                                </div>
                                <div class="ms-1 mt-1 me-2">
                                    <!--<button type="button" class="btn btn-primary btn-sm me-1 mb-1"><i class="fas fa-eye"></i></button>
                                    <button type="button" class="btn btn-secondary btn-sm me-1 mb-1"><i class="fas fa-edit"></i></button>
                                    <button type="button" class="btn btn-warning btn-sm me-1 mb-1"><i class="fas fa-exclamation-circle"></i></button>-->
                                    <button type="button" class="btn btn-primary btn-sm me-1 mb-1 btn-recargar " id="btn-recargar"><i class="fas fa-sync"></i>Recargar</button>
                                    <button type="button" class="btn btn-danger btn-sm mb-1"><i class="fas fa-trash"></i>Eliminar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="container mt-5">
                    <div class="table-responsive">
                        <table class="table table-striped" id="tablaClientes">
                            <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Vehículo(s)</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="listaClientes"></tbody>
                        </table>
                    </div>

                </div>





                <!-- Modal para detalles del cliente -->
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

                <!-- Modal para modificar cliente -->
                <div class="modal fade" id="modalModificarCliente" tabindex="-1" aria-labelledby="modalModificarClienteLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalModificarClienteLabel">Modificar Cliente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="formModificarCliente">
                                    <div class="mb-3">
                                        <label for="modificarDni" class="form-label">DNI</label>
                                        <input type="text" class="form-control" id="modificarDni" name="dni" readonly>
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
                                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalRegistrarCliente" tabindex="-1" aria-labelledby="modalRegistrarClienteLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalRegistrarClienteLabel">Registrar Cliente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="clienteForm" action="javascript:void(0);" method="POST">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5>Datos del Cliente</h5>
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
                                            </div>

                                            <div class="col-md-6">
                                                <h5>Datos del Vehículo</h5>
                                                <div class="mb-3">
                                                    <label for="patente" class="form-label">Patente</label>
                                                    <input type="text" class="form-control" id="patente" name="patente" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="modelo" class="form-label">Modelo</label>
                                                    <input type="text" class="form-control" id="modelo" name="modelo" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tipo" class="form-label">Tipo de Vehículo</label>
                                                    <select class="form-select" id="tipo" name="tipo" required>
                                                        <option value="">Seleccionar tipo</option>
                                                        <option value="Camioneta">Camioneta</option>
                                                        <option value="Auto">Auto</option>
                                                        <option value="Moto">Moto</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modal para mensaje de éxito -->
                <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                <i id="successModalIcon" class="fas fa-3x mb-3"></i>
                                <h5 class="modal-title" id="successModalLabel"></h5>
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