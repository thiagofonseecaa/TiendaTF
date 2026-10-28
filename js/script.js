function actualizarReloj() {
    const elementoReloj = document.getElementById("reloj");
    if (elementoReloj) {
        const ahora = new Date();
        const fecha = ahora.toLocaleDateString("es-AR");
        const hora = ahora.toLocaleTimeString("es-AR");
        elementoReloj.textContent = fecha + " - " + hora;
    }
}

actualizarReloj();
setInterval(actualizarReloj, 1000);

const preguntas = document.querySelectorAll(".acordeon-pregunta");

preguntas.forEach(function (pregunta) {
    pregunta.addEventListener("click", function () {
        const respuesta = pregunta.nextElementSibling;
        respuesta.classList.toggle("mostrar");
    });
});

if (localStorage.getItem("modoOscuro") === "activado") {
    document.body.classList.add("oscuro");
}

const btnModoOscuro = document.getElementById("btn-modo-oscuro");

if (btnModoOscuro) {
    btnModoOscuro.addEventListener("click", function () {
        document.body.classList.toggle("oscuro");

        if (document.body.classList.contains("oscuro")) {
            localStorage.setItem("modoOscuro", "activado");
        } else {
            localStorage.setItem("modoOscuro", "desactivado");
        }
    });
}
