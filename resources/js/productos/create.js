import { inputsConMask, eventosDelForm } from "./init-form";
import Swal from 'sweetalert2';


window.onload = function() {
    // Set eventos
    eventosDelForm("create");
    
    // Init inputs
    const inputs = inputsConMask;

    // Click en boton Agregar producto a Receta
    const btnAgregarProd = document.querySelector("#btnAgregarProducto");
    btnAgregarProd.addEventListener("click", function () {
        Swal.fire({
            icon: "error",
            title: "Atención !",
            text: "Debe confirmar el producto, para cargar receta !!",
        });
        document.querySelector("input#carga_receta").value = "si";
    });

   // Click en boton agregar articulo a Promocion
   const btnAgregarArticulo = document.querySelector("#btnAgregarArticulo");
   btnAgregarArticulo.addEventListener("click", function () {
       Swal.fire({
           icon: "error",
           title: "Atención !",
           text: "Debe confirmar producto, para cargar artículo promoción !!",
       });
       document.querySelector("input#carga_receta").value = "si";
   });

}
    