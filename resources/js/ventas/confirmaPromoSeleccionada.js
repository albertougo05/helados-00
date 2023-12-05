/**
 * 
 * Confirma promo, despues de seleccionar opciones
 * 
 */

import ManejoVenta from "./ManejoVenta";


const _confirmaPromoSeleccionada = () => {
    const codigosComboSelec = __getArrayCodigosCombosSelec();
    const manejoVta = new ManejoVenta();
    let promoYaEstaEnLista = false;
    //console.log('Array opciones combos:', codigosComboSelec)
    promoYaEstaEnLista = manejoVta.productoSeleccionado(_VENTA.promo_producto_seleccionado.id, codigosComboSelec.join(' '));

    if (promoYaEstaEnLista) {
        manejoVta.opcionesPromoYaEnLista(codigosComboSelec);
    } else {
        manejoVta.opcionesPromo(codigosComboSelec);
    }
    //console.log('Producto promo seleccionado:', _VENTA.promo_producto_seleccionado)
    document.querySelector('.modal-promo').classList.add('hidden');      // Oculta el modal

    return null;
};


const __getArrayCodigosCombosSelec = () => {
    const codigosCombos = [];

    for (let index = 1; index < _VENTA.promo_cant_combos + 1; index++) {
        const valor = document.querySelector('#selCombo-' + index).value;
        codigosCombos.push(valor);
    }

    return codigosCombos;
}


export { _confirmaPromoSeleccionada }