document.getElementById("bd").addEventListener("change", function() {
    var checkboxes = document.querySelectorAll('input[name="tablas"], input[name="vista_sql"]');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = this.checked;
    }, this);
});
document.querySelectorAll('input[name="tablas"], input[name="vista_sql"]').forEach(function(checkbox) {
    checkbox.addEventListener("change", function() {
    var bd = true;
    document.querySelectorAll('input[name="tablas"], input[name="vista_sql"]').forEach(function(checkbox) {
        if (!checkbox.checked) {
            bd = false;
        }
        });
        document.getElementById("bd").checked = bd;
    });
});



document.getElementById("excepciones").addEventListener("change", function() {
    var checkboxes = document.querySelectorAll('input[name="excepciones_s"], input[name="excepciones_c"], input[name="excepciones_i"]');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = this.checked;
    }, this);
});
document.querySelectorAll('input[name="excepciones_s"], input[name="excepciones_c"], input[name="excepciones_i"]').forEach(function(checkbox) {
    checkbox.addEventListener("change", function() {
    var excepciones = true;
    document.querySelectorAll('input[name="excepciones_s"], input[name="excepciones_c"], input[name="excepciones_i"]').forEach(function(checkbox) {
        if (!checkbox.checked) {
            excepciones = false;
        }
        });
        document.getElementById("excepciones").checked = excepciones;
    });
});


document.getElementById("reportes").addEventListener("change", function() {
    var checkboxes = document.querySelectorAll('input[name="reportes_s"], input[name="reportes_c"], input[name="reportes_i"]');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = this.checked;
    }, this);
});
document.querySelectorAll('input[name="reportes_s"], input[name="reportes_c"], input[name="reportes_i"]').forEach(function(checkbox) {
    checkbox.addEventListener("change", function() {
    var reportes = true;
    document.querySelectorAll('input[name="reportes_s"], input[name="reportes_c"], input[name="reportes_i"]').forEach(function(checkbox) {
        if (!checkbox.checked) {
            reportes = false;
        }
        });
        document.getElementById("reportes").checked = reportes;
    });
});




document.getElementById("privilegios").addEventListener("change", function() {
    var checkboxes = document.querySelectorAll('input[name="roles"], input[name="usuarios"]');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = this.checked;
    }, this);
});

document.querySelectorAll('input[name="roles"], input[name="usuarios"]').forEach(function(checkbox) {
    checkbox.addEventListener("change", function() {
        var privilegios = true;
        document.querySelectorAll('input[name="roles"], input[name="usuarios"]').forEach(function(checkbox) {
            if (!checkbox.checked) {
                privilegios = false;
            }
        });
        document.getElementById("privilegios").checked = privilegios;
    });
});







document.addEventListener("DOMContentLoaded", function() {
    habilitarBoton();
});


function habilitarBoton(){
    var checkboxes = document.querySelectorAll('input[name="bd"],input[name="excepciones"],input[name="reportes"], input[name="privilegios"], input[name="tablas"], input[name="vista_sql"], input[name="excepciones_s"], input[name="excepciones_c"], input[name="excepciones_i"], input[name="reportes_s"], input[name="reportes_c"], input[name="reportes_i"], input[name="roles"], input[name="usuarios"]');
    var boton = document.getElementById("registrar");

    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener("change", function() {
            var alMenosUnoSeleccionado = false;
            checkboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    alMenosUnoSeleccionado = true;
                }
            });
            boton.disabled = !alMenosUnoSeleccionado;
        });
    });
}

