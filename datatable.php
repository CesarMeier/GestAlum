<?php
require_once ('modelo/Persona.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTable</title>

    <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">

    <!-- Link de DataTable-->
    <link href="public/DataTables/datatables.min.css" rel="stylesheet">
    <link href="public/select2/select2.min.css" rel="stylesheet"/>
</head>
<body>

    <div class="container text-center">
        <h1>Listado De Personas</h1>
    </div>

    <div id="divtabla" class="container">
        <table id="myTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Telefono</th>
                    <th>CUIL</th>

                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>


                </tr>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formEditar" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Persona</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="idPersona" id="edit-id">
                    <div class="mb-3">
                        <label>DNI</label>
                        <input type="text" name="dni" id="edit-dni" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre" id="edit-nombre" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Apellido</label>
                        <input type="text" name="apellido" id="edit-apellido" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" id="edit-email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Telefono</label>
                        <input type="telefono" name="telefono" id="edit-telefono" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>CUIL</label>
                        <input type="cuil" name="cuil" id="edit-cuil" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="editar" name="btn_editar" class="btn btn-primary" type="submit">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalDuplicar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formDuplicar" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Duplicar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="dupli-id">
                <div class="mb-3">
                <label>DNI</label>
                <input type="text" name="dni" id="dupli-dni" class="form-control">
                </div>
                <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="nombre" id="dupli-nombre" class="form-control">
                </div>
                <div class="mb-3">
                <label>Apellido</label>
                <input type="text" name="apellido" id="dupli-apellido" class="form-control">
                </div>
                <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" id="dupli-email" class="form-control">
                </div>
                <div class="mb-3">
                <label>Telefono</label>
                <input type="tel" name="telefono" id="dupli-telefono" class="form-control">
                </div>
                <div class="mb-3">
                <label>CUIL</label>
                <input type="text" name="cuil" id="dupli-cuil" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button id="duplicar" name="btn_duplicar" class="btn btn-primary" type="submit">Guardar Cambios</button>
            </div>
            </form>
        </div>
    </div>

    <script src="public/js/jquery-3.7.1.min.js"></script>
    <script src="public/bootstrap/js/bootstrap.min.js"></script>
    
    <script src="public/select2/select2.min.js"></script>
    <script src="public/DataTables/datatables.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/2.2.2/i18n/es-ES.json"></script>

    <script>
        $(document).ready(function () {
            $('#myTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/2.2.2/i18n/es-ES.json',
                },
                ajax: {
                    url: 'controller/router.php?accion=listar',
                    dataSrc: ''
                },
                columns: [
                    { data: 'idPersona' },
                    { data: 'dni' },
                    { data: 'nombre' },
                    { data: 'apellido' },
                    { data: 'email' },
                    { data: 'telefono' },
                    { data: 'cuil' },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return `
                                <button class="btn-editar btn btn-primary" data-id="${row.idPersona}">✏️</button>
                                <button class="btn-eliminar btn btn-danger" data-id="${row.idPersona}">🗑️</button>
                                <button class="btn-duplicar btn btn-secondary" data-id="${row.idPersona}">📄</button>
                            `;
                        }
                    }
                ]
            });

            // ELIMINAR
            $('#myTable').on('click', '.btn-eliminar', function () {
                let id = $(this).data('id');
                if (confirm('¿Estás seguro de eliminar al usuario con ID: ' + id + '?')) {
                    $.post('controller/router.php?accion=eliminar', { id: id }, function (response) {
                        if (response.success) {
                            alert('Usuario eliminado correctamente');
                            $('#myTable').DataTable().ajax.reload();
                        } else {
                            alert('Error al eliminar');
                        }
                    }, 'json');
                }
            });

            // EDITAR (GUARDAR)
            $('#formEditar').submit(function (e) {
                e.preventDefault();
                $.post('controller/router.php?accion=guardar', $(this).serialize(), function (response) {
                    if (response.success) {
                        $('#modalEditar').modal('hide');
                        $('#myTable').DataTable().ajax.reload();
                    } else {
                        alert('Error al guardar');
                    }
                }, 'json');
            });

            // MOSTRAR MODAL EDITAR
            $('#myTable').on('click', '.btn-editar', function () {
                let row = $('#myTable').DataTable().row($(this).parents('tr')).data();
                $('#edit-id').val(row.idPersona);
                $('#edit-dni').val(row.dni);
                $('#edit-nombre').val(row.nombre);
                $('#edit-apellido').val(row.apellido);
                $('#edit-email').val(row.email);
                $('#edit-telefono').val(row.telefono);
                $('#edit-cuil').val(row.cuil);
                $('#modalEditar').modal('show');
            });

            // MOSTRAR MODAL DUPLICAR
            $('#myTable').on('click', '.btn-duplicar', function () {
                let row = $('#myTable').DataTable().row($(this).parents('tr')).data();
                $('#dupli-id').val('');
                $('#dupli-dni').val(row.dni);
                $('#dupli-apellido').val(row.apellido);
                $('#dupli-nombre').val(row.nombre);
                $('#dupli-email').val(row.email);
                $('#dupli-telefono').val(row.telefono);
                $('#dupli-cuil').val(row.cuil);
                $('#modalDuplicar').modal('show');
            });

            // DUPLICAR
            $('#formDuplicar').submit(function (e) {
                e.preventDefault();
                $.post('controller/router.php?accion=duplicar', $(this).serialize(), function (response) {
                    if (response.success) {
                        $('#modalDuplicar').modal('hide');
                        $('#myTable').DataTable().ajax.reload();
                    } else {
                        alert('Error al duplicar: ' + response.error);
                    }
                }, 'json');
            });

            // SELECT2 PROVINCIAS
            $('#modalEditar').on('shown.bs.modal', function () {
                $('#buscador').select2({
                    placeholder: 'Escribí la provincia que estás buscando',
                    minimumInputLength: 2,
                    delay: 250,
                    dropdownParent: $('#modalEditar'),
                    language: {
                        noresults: function () {
                            return 'No hay resultado';
                        },
                        searching: function () {
                            return 'Buscando...';
                        }
                    },
                    ajax: {
                        url: 'API/Data.class.php',
                        dataType: 'json',
                        data: function (params) {
                            return {
                                search: params.term,
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: data
                            };
                        }
                    }
                });
            });
        });
    </script>

</body>
</html>
