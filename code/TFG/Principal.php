<?php

include_once 'Box/Spout/Autoloader/autoload.php';
include_once 'Box/Spout/Reader/Common/Creator/ReaderFactory.php';
include_once 'Box/Spout/Reader/CSV/Reader.php';
include './header.php'

?>

<body>
    <div class="grid p-5">
        <div class="row">
            <h2>
                Importador
            </h2>
        </div>
        <hr>
        <form method="post" enctype="multipart/form-data" id="importer">
            <div class="row d-flex justify-content-left align-items-end mb-5 gap-4">
                <div class="col-auto d-flex align-items-center ">
                    <div>
                        <label for="file" class="form-label d-flex align-items-center gap-2">Archivo a importar <span class="w-auto d-flex" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" id="hint">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill w-auto d-flex" viewBox="0 0 16 16">
                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                                </svg>
                            </span>
                        </label>
                        <form id="form_file" method="post" action="formpost.php" enctype="multipart/form-data">
                            <div class="form-group">
                                <input title="fileSelector" type="file" name="fileSelector" id="file_selector" class="form-control">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <iframe id="iframe_visualizar" class="broke" src="" frameborder="0" height="200" width="100%"></iframe>
    </div>
    <div class="grid mb-3 ms-5">
        <label for="check_encabezado">¿Tiene encabezado la tabla?</label>
        <input type="checkbox" name="check_encabezado" id="check_encabezado" onclick="eliminarEncabezadoTabla()">
        <p id="text" style="display:none">Checkbox is CHECKED!</p>
        <button id="prev_pag_btn" hidden onclick="retrocederPagina()">Prev. Pag</button>
        <button id="post_pag_btn" hidden onclick="avanzarPagina()">Post. Pag</button>
    </div>
    <div class="grid mb-1 ms-5">
        <input title="Columna Tickets" type="text" name="col_tickets" id="col_tickets" placeholder="Escribe la columna de los tickets">
        <button id="actualizar_tickets" onclick="cargarTickets()">Cargar</button>
    </div>
    <p id="conteo_tickets" class="grid bg-white mb-4 ms-5">
        Conteo de tickets:
    </p>
    <hr>
    <!-- <div class="mb-1 container text-center">
        <div class="row">
            <div class="col">
                <select id="nombre_evento" class="form-select" style="width: 76%;">
                </select>
            </div>
        </div>
    </div> -->
    <div class="container text-center">
        <div class="row mb-3">
            <div class="col">
                <select id="ticket_evento" class="form-control form-control-lg" style="width: 50%;">
                </select>
            </div>
            <div class="col">
                <select name="nombre_tickets" id="nombre_tickets" class="form-control-sm" style="width: 50%; height: 20px;">
                </select>
            </div>
        </div>
        <button class="btn btn-dark btn-lg mb-2" id="sincronizar_tickets" onclick="enlazarTickets()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link" viewBox="0 0 16 16">
                <path d="M6.354 5.5H4a3 3 0 0 0 0 6h3a3 3 0 0 0 2.83-4H9c-.086 0-.17.01-.25.031A2 2 0 0 1 7 10.5H4a2 2 0 1 1 0-4h1.535c.218-.376.495-.714.82-1z" />
                <path d="M9 5.5a3 3 0 0 0-2.83 4h1.098A2 2 0 0 1 9 6.5h3a2 2 0 1 1 0 4h-1.535a4.02 4.02 0 0 1-.82 1H12a3 3 0 1 0 0-6H9z" />
            </svg> Enlazar Tickets</button>
    </div>

    <hr>

    <div class="row g-1 mb-2">
        <div class="col-md-6">
            <input class="form-control" type="text" name="col_nombre" id="col_nombre" placeholder="Equipo">
        </div>
        <div class="col-md-6">
            <input class="form-control" type="text" name="col_apellidos" id="col_apellidos" placeholder="Victorias">
        </div>
    </div>
    <!-- <div class="row g-1 mb-2">
        <div class="col-md-6">
            <input class="form-control" type="text" name="col_documento" id="col_documento" placeholder="Columna del documento identidad">
        </div>
        <div class="col-md-6">
            <input class="form-control" type="text" name="col_email" id="col_email" placeholder="Columna de email">
        </div>
    </div> -->
    <!-- <div class="row g-1 mb-2">
        <div class="col-md-6">
            <input class="form-control" type="text" name="col_telefono" id="col_telefono" placeholder="Columna del teléfono">
        </div>
        <div class="col-md-6">
            <input class="form-control" type="text" name="col_fecha" id="col_fecha" placeholder="Columna con la fecha">
        </div>
    </div>
    <div class="row g-1 mb-2">
        <div class="col-md-6">
            <input class="form-control" type="text" name="col_localizador" id="col_localizador" placeholder="Columna del localizador">
        </div>
    </div> -->
    <div class="d-grid gap-2 col-6 mx-auto">
        <button class="btn btn-dark btn-lg " id="importar_tickets" onclick="importarTickets()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upload" viewBox="0 0 16 16">
                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z" />
            </svg> Importar tickets</button>
    </div>

    <hr>
    <hr>

    <div id="chartContainer">
        <canvas id="myChart"></canvas>
    </div>

    <script src="./js/Principal/principal.js"></script>
    <script src="./js/Principal/grafico.js"></script>
</body>

</html>