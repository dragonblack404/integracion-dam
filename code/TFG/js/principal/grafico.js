function cargarGrafico() {
    // Datos de ejemplo
    var nombres = ['Equipo 1', 'Equipo 2', 'Equipo 3', 'Equipo 4'];
    var victorias = [5, 8, 4, 7];
    var maxPartidos = 15; // Número máximo de partidos
    var ValorMaxPartidos = Math.max(victorias);
    var legendIncrement = 5;
    var legendLabels = Math.ceil(ValorMaxPartidos / legendIncrement);


    // Calcular el porcentaje de victorias para cada Equipo
    var porcentajes = victorias.map(victoria => (victoria / maxPartidos) * 100);

    // Crear el gráfico de barras
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: nombres,
            datasets: [{
                label: 'Porcentaje de victorias',
                data: porcentajes,
                backgroundColor: 'rgba(231, 76, 60, 0.3)', // Color de las barras
                borderColor: 'rgba(231, 76, 60, 1)', // Color del borde de las barras
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    max: 100 // Límite máximo en el eje x (porcentaje)
                }
            }
        }
    });
}
