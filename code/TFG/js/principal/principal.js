//Pruebas con uso de Default
function experimentos() {
    var col_localizador = $("#col_localizador").val();
    var col_nombre = $("#col_nombre").val();
    var col_apellidos = $("#col_apellidos").val();
    var col_documento = $("#col_documento").val();
    var col_email = $("#col_email").val();
    var col_telefono = $("#col_telefono").val();
    var col_fecha = $("#col_fecha").val();
    var col_tickets = $("#col_tickets").val();
    // console.log(col_apellidos);
    // console.log(col_nombre);
    // console.log(col_caracteristica);
    // console.log(col_email);
    $.ajax({
        type: 'POST',
        url: './controller/Disparador.php',
        dataType: 'json',
        data: {
            col_nombre: col_nombre,
            col_apellidos: col_apellidos,
            col_documento: col_documento,
            col_email: col_email,
            col_telefono: col_telefono,
            col_fecha: col_fecha,
            col_localizador: col_localizador,
            col_tickets: col_tickets,
            accion: 0
        },
        success: function (resp) {
            console.log(resp);
        }
    })
}

var valueEventoSelect;
var pagFile = 0;
var numFiles = 0;

$("#nombre_evento").on("change", function () {
    valueEventoSelect = $(this).val();
    $("#ticket_evento").val(null).trigger('change');
});


function myFunction() {
    var checkBox = document.getElementById("check_encabezado");
    var text = document.getElementById("text");
    if (checkBox.checked == true) {
        var r = confirm("Press a button!");
        if (r == true) {
            text.style.display = "block";
        } else {
            checkBox.checked = false;
        }
    } else {
        text.style.display = "none";
    }
}

// $("#nombre_evento").select2({
//     width: 'resolve',
//     placeholder: 'Ej: Boombastic',
//     ajax: {
//         url: './controller/Disparador.php',
//         type: 'POST',
//         dataType: "json",
//         data: function (params) {
//             return {
//                 eventName: params.term,
//                 accion: 1
//             }
//         }

//     },
//     minimumInputLength: 4,
//     processResults: function (data) {
//         console.log(data);
//         return {
//             results: data.results
//         }
//     },
//     allowClear: true
// });

$("#ticket_evento").select2({
    width: 'resolve',
    placeholder: 'Ej: General',
    ajax: {
        url: './controller/Disparador.php',
        type: 'POST',
        dataType: "json",
        data: function (params) {
            return {
                ticketName: params.term,
                accion: 2
                // idEvento: valueEventoSelect
            }
        }

    },
    processResults: function (data) {
        console.log(data);
        return {
            results: data.results
        }
    },
    allowClear: true
});

function enlazarTickets() {
    var ticketDB = $("#ticket_evento").val();
    var ticketCsv = $("#nombre_tickets").val();
    var fieldColTickets = $("#col_tickets").val();
    console.log(ticketDB);
    $.ajax({
        type: 'POST',
        url: './controller/Disparador.php',
        dataType: "json",
        data: {
            ticketCsv: ticketCsv,
            ticketDB: ticketDB,
            fieldColTickets: fieldColTickets,
            accion: 3
        },
        success: function (resp) {
            setTimeout(function () { alert(resp); }, 3000);
        }
    })
}

function cargarTickets() {
    var fieldColTickets = document.getElementById("col_tickets").value;

    $('#nombre_tickets option').remove();
    $('#conteo_tickets').html('Conteo de tickets:');

    $.ajax({
        type: 'POST',
        url: './controller/Disparador.php',
        dataType: "json",
        data: {
            fieldColTickets: fieldColTickets,
            accion: 4
        },
        success: function (resp, estado, jqXHR) {

            for (var i = 0; i < resp[0].length; i++) {
                $('#nombre_tickets').append($('<option>', {
                    value: resp[0][i],
                    text: resp[0][i]
                }));
            }

            for (var i = 0; i < resp[1].length; i++) {
                $('#conteo_tickets').append('<br>' + resp[1][i]);
            }

        }
    })
}

$("#file_selector").change(function () {
    numFiles = null;
    var formData = new FormData();
    var file = document.getElementById("file_selector").files[0];

    formData.append('file', file);
    formData.append('accion', 5);

    var xhttp = new XMLHttpRequest();

    xhttp.open("POST", "./controller/Disparador.php", true);
    xhttp.send(formData);
    xhttp.onload = () => {
        $("#iframe_visualizar").contents().find("body").html(xhttp.response);
    }

    cargarGrafico();
});


function importarTickets() {
    var col_nombre = $("#col_nombre").val();
    var col_apellidos = $("#col_apellidos").val();
    // var col_localizador = $("#col_localizador").val();
    // var col_documento = $("#col_documento").val();
    // var col_email = $("#col_email").val();
    // var col_telefono = $("#col_telefono").val();
    // var col_fecha = $("#col_fecha").val();
    // var col_tickets = $("#col_tickets").val();
    // console.log(col_apellidos);
    // console.log(col_nombre);
    // console.log(col_caracteristica);
    // console.log(col_email);
    $.ajax({
        type: 'POST',
        url: './controller/Disparador.php',
        dataType: 'json',
        data: {
            col_nombre: col_nombre,
            col_apellidos: col_apellidos,
            // col_documento: col_documento,
            // col_email: col_email,
            // col_telefono: col_telefono,
            // col_fecha: col_fecha,
            // col_localizador: col_localizador,
            // col_tickets: col_tickets,
            accion: 8
        },
        success: function (resp) {
            console.log(resp);
        }
    })
}

function eliminarEncabezadoTabla() {
    var checkBox = document.getElementById("check_encabezado");

    if (checkBox.checked == true) {
        var respuesta = confirm("Confirma que tiene encabezado");
        if (respuesta) {
            console.log($("#check_encabezado").prop("checked"));

            console.log("tiene encabezado");

            $.ajax({
                type: 'POST',
                url: './controller/Disparador.php',
                dataType: 'json',
                data: {
                    accion: 9
                },
                success: function (resp) {
                    console.log('Encabezado eliminada');
                }
            })
        } else {
            checkBox.checked = false;
        }
    }
}




