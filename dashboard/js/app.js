function dashboard() {
    remove();
    $.ajax({
        url: "../dashboard/dashboard.php",
        type: "post",
        success: function (response) {
            $("#shadow").fadeOut();
            $("#spinner").html(``);
            $("#all-body").html(response);
            $.ajax({
                url: "../dashboard/info.php",
                type: "post",
                success: function (data) {
                    var json = JSON.parse(data);
                    $("#total_bienes").html(json.totalBienes);
                    $("#bienes_asignados").html(json.totalBienesAsignados);
                    $("#bienes_no_asignados").html(json.totalBienesNoAsignados);
                    $("#bienes_depreciados").html(json.listaBienesDepreciados.length);
                    // para el grafico de area
                    var listaAreaAsignaciones = json.listaAreaAsignaciones;
                    var areas = [];
                    var valores = [];
                    for (var i = 0; i < listaAreaAsignaciones.length; i++) {
                        areas.push(listaAreaAsignaciones[i].area);
                        valores.push(listaAreaAsignaciones[i].total);
                    }
                    var ctx = document.getElementById('graficoAreaAsignaciones').getContext('2d');
                    // Generar colores diferentes para cada área
                    var colores = [];
                    for (var i = 0; i < areas.length; i++) {
                        // Generar un color aleatorio
                        colores.push('rgba(' + Math.floor(Math.random() * 256) + ',' + 
                                    Math.floor(Math.random() * 256) + ',' + 
                                    Math.floor(Math.random() * 256) + ',0.7)');
                    }
                    
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: areas,
                            datasets: [{
                                data: valores,
                                backgroundColor: colores,
                                borderColor: colores,
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            legend: {
                                display: false
                            },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        callback: function(value) {
                                            return value.toLocaleString();
                                        },
                                        stepSize: 1,
                                        precision: 0
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Total de bienes'
                                    }
                                }]
                            }
                        }
                    });
                    // para tiempo restante de vida util
                    let listaTiempoRestante = json.listaTiempoRestante;
                    listaTiempoRestante.sort((a, b) => b.porcentajeUtilRestante - a.porcentajeUtilRestante);
                    listaTiempoRestante.forEach(element => {
                        if (element.porcentajeUtilRestante > 75) {
                            $("#tiempo-restante").append(`
                            <div class="progress-group">
                                <span class="progress-text">${element.producto}</span>
                                <span class="float-right"><b>${element.porcentajeUtilRestante.toFixed(2)} %</b></span>
                                <small>(${element.tiempoRestante} años restantes)</small>
                                <div class="progress sm">
                                    <div class="progress-bar bg-danger" style="width: ${element.porcentajeUtilRestante.toFixed(2)}%; border-radius: 5px;"></div>
                                </div>
                            </div>`);
                        } else if (element.porcentajeUtilRestante > 50) {
                            $("#tiempo-restante").append(`
                            <div class="progress-group">
                                <span class="progress-text">${element.producto}</span>
                                <span class="float-right"><b>${element.porcentajeUtilRestante.toFixed(2)} %</b></span>
                                <small>(${element.tiempoRestante} años restantes)</small>
                                <div class="progress sm">
                                    <div class="progress-bar bg-warning" style="width: ${element.porcentajeUtilRestante.toFixed(2)}%; border-radius: 5px;"></div>
                                </div>
                            </div>`);
                        } else {
                            $("#tiempo-restante").append(`
                            <div class="progress-group">
                                <span class="progress-text">${element.producto}</span>
                                <span class="float-right"><b>${element.porcentajeUtilRestante.toFixed(2)} %</b></span>
                                <small>(${element.tiempoRestante} años restantes)</small>
                                <div class="progress sm">
                                    <div class="progress-bar bg-success" style="width: ${element.porcentajeUtilRestante.toFixed(2)}%; border-radius: 5px;"></div>
                                </div>
                            </div>`);
                        }
                    });
                    
                },
            });
        },
    });
}