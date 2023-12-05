/** 
 * Reset inputs del modal
 */

const resetModal = (inputModalCosto, inputModalCantidad) => {
    const selProd = document.querySelector('#selProductoReceta');
    const inpCant = document.querySelector('#modalCantidad');
    const titulo = document.querySelector('h2#tituloModal');
    const btnElimina = document.querySelector('button#btnEliminaProdReceta');

    titulo.textContent = "Ingrese producto a receta";
    btnElimina.classList.add('hidden');
    selProd.options[0].selected = true;
    selProd.disabled = false;
    inpCant.value = '';
    inputModalCosto.typedValue = '';
    inputModalCantidad.typedValue = 1;
    selProd.focus();
};

export default resetModal;