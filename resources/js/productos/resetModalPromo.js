/** 
 * Reset inputs del modal
 */

const resetModalPromo = (inputModalPrecio, inputModalCantidad) => {
    const selComboPromo = document.querySelector('#selComboPromo');
    const selArtPromo = document.querySelector('#selArtPromo');
    const inpCant = document.querySelector('#cantArtPromo');
    const btnElimina = document.querySelector('button#btnEliminaArtPromo');

    btnElimina.classList.add('hidden');
    selComboPromo.options[0].selected = true;
    selArtPromo.options[0].selected = true;
    selArtPromo.disabled = false;
    inpCant.value = '';
    inputModalPrecio.typedValue = '';
    inputModalCantidad.typedValue = 1;
    selComboPromo.focus();
};

export default resetModalPromo;