alert("¡Bienvenido/a a TF Clothing! Descubrí lo último en ropa urbana.");

const formRegistro = document.getElementById("formRegistro");

if (formRegistro) {
    formRegistro.addEventListener("submit", function (evento) {
        let esValido = true;

        const nombre = document.getElementById("nombre");
        const errorNombre = document.getElementById("error-nombre");
        if (nombre.value.trim() === "") {
            errorNombre.textContent = "El nombre no puede estar vacío.";
            esValido = false;
        } else {
            errorNombre.textContent = "";
        }

        const email = document.getElementById("email");
        const errorEmail = document.getElementById("error-email");
        const patronEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!patronEmail.test(email.value)) {
            errorEmail.textContent = "Ingresá un correo electrónico válido.";
            esValido = false;
        } else {
            errorEmail.textContent = "";
        }

        if (!esValido) {
            evento.preventDefault();
        } else {
            evento.preventDefault();
            document.getElementById("resumen-nombre").textContent = nombre.value;
            document.getElementById("resumen-email").textContent = email.value;
            document.getElementById("resumen-talle").textContent = document.getElementById("talle").value;
            document.getElementById("resumen").style.display = "block";
        }
    });
}

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