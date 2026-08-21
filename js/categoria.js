const datosCategorias = {
    hombre: {
        imagenes: [
            "imagenes/remerahombre.png",
            "imagenes/pantaloneshombre.png",
            "imagenes/buzoshombre.png"
        ],
        nombres: ["Remeras", "Pantalones", "Buzos"]
    },
    mujer: {
        imagenes: [
            "imagenes/remeramujer.png",
            "imagenes/pantalonmujer.png",
            "imagenes/buzosmujer.png"
        ],
        nombres: ["Remeras", "Pantalones", "Buzos"]
    },
    niños: {
        imagenes: [
            "imagenes/remerasniños.png",
            "imagenes/pantalonesniños.png",
            "imagenes/buzosniños.png"
        ],
        nombres: ["Remeras", "Pantalones", "Buzos"]
    },
    accesorios: {
        imagenes: [
            "imagenes/gorras.png",
            "imagenes/cintos.png",
            "imagenes/anillo.png",
            "imagenes/reloj.png"
        ],
        nombres: ["Gorras", "Cintos", "Anillos", "Relojes"]
    }
};

const galeriaImagen = document.getElementById("galeria-imagen");

if (galeriaImagen) {
    const categoriaActual = document.body.dataset.categoria;
    const datos = datosCategorias[categoriaActual];

    let indiceActual = 0;
    const galeriaNombre = document.getElementById("galeria-nombre");
    const btnAnterior = document.getElementById("btn-anterior");
    const btnSiguiente = document.getElementById("btn-siguiente");

    btnSiguiente.addEventListener("click", function () {
        indiceActual = (indiceActual + 1) % datos.imagenes.length;
        galeriaImagen.src = datos.imagenes[indiceActual];
        galeriaNombre.textContent = datos.nombres[indiceActual];
    });

    btnAnterior.addEventListener("click", function () {
        indiceActual = (indiceActual - 1 + datos.imagenes.length) % datos.imagenes.length;
        galeriaImagen.src = datos.imagenes[indiceActual];
        galeriaNombre.textContent = datos.nombres[indiceActual];
    });

    const modal = document.getElementById("modal-imagen");
    const modalImagenGrande = document.getElementById("modal-imagen-grande");
    const cerrarModal = document.getElementById("cerrar-modal");

    galeriaImagen.addEventListener("click", function () {
        modalImagenGrande.src = galeriaImagen.src;
        modal.classList.remove("modal-oculto");
        modal.classList.add("modal-visible");
    });

    cerrarModal.addEventListener("click", function () {
        modal.classList.remove("modal-visible");
        modal.classList.add("modal-oculto");
    });
}