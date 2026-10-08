const datosCategorias = {
    hombre: {
        imagenes: [
            "clase5/imagenes/remera-clasica.png",
            "clase5/imagenes/jean-recto.png",
            "clase5/imagenes/buzo-infantil.png"
        ],
        nombres: ["Remera azul de hombre", "Jean recto de hombre", "Buzo negro con cierre de hombre"]
    },
    mujer: {
        imagenes: [
            "clase5/imagenes/remera-mujer.png",
            "clase5/imagenes/pantalon-mujer.png",
            "clase5/imagenes/buzo-mujer.png"
        ],
        nombres: ["Remera blanca de mujer", "Pantalón jogger de mujer", "Buzo negro de mujer"]
    },
    niños: {
        imagenes: [
            "clase5/imagenes/remera-infantil.png",
            "clase5/imagenes/pantalon-infantil.png",
            "clase5/imagenes/buzo-clasico.png"
        ],
        nombres: ["Remera blanca infantil", "Pantalón jogger infantil", "Buzo gris infantil"]
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
    galeriaImagen.src = datos.imagenes[indiceActual];
    galeriaNombre.textContent = datos.nombres[indiceActual];
    galeriaImagen.alt = datos.nombres[indiceActual];
    const btnAnterior = document.getElementById("btn-anterior");
    const btnSiguiente = document.getElementById("btn-siguiente");

    btnSiguiente.addEventListener("click", function () {
        indiceActual = (indiceActual + 1) % datos.imagenes.length;
        galeriaImagen.src = datos.imagenes[indiceActual];
        galeriaNombre.textContent = datos.nombres[indiceActual];
        galeriaImagen.alt = datos.nombres[indiceActual];
    });

    btnAnterior.addEventListener("click", function () {
        indiceActual = (indiceActual - 1 + datos.imagenes.length) % datos.imagenes.length;
        galeriaImagen.src = datos.imagenes[indiceActual];
        galeriaNombre.textContent = datos.nombres[indiceActual];
        galeriaImagen.alt = datos.nombres[indiceActual];
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
