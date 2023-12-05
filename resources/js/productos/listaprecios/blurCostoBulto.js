import { strToFloat, _roundToTwo, strToCurrency } from '../../utils/solo-numeros';


const _blurCostoBulto = (elem) => {
    let nuevo_costo_unid = 0;
    const codigo_bulto = elem.dataset.codigo,
          costo_bulto = strToFloat(elem.value),
          artindiv = elem.dataset.artindiv,
          unid_x_bulto = parseInt(elem.dataset.unidbulto),
          original = parseFloat(elem.dataset.valororig);

    if (costo_bulto !== original) {
        elem.classList.add('bg-orange-300');

        if (costo_bulto !== 0 && artindiv !== '') {
            const inputCostoUnidBulto = _buscarInputIMask(`costo_unid_${codigo_bulto}`),   //document.querySelector(`#costo_unid_${codigo_bulto}`),
                  inputCostoUnidIndiv = _buscarInputIMask(`costo_unid_${artindiv}`),     //document.querySelector(`#costo_unid_${artindiv}`),
                  inputCostoBultoIndiv = _buscarInputIMask(`costo_bulto_${artindiv}`);    // document.querySelector(`#costo_bulto_${artindiv}`);

            if (unid_x_bulto > 0) {
                nuevo_costo_unid = _roundToTwo(costo_bulto / unid_x_bulto);
                inputCostoUnidBulto.typedValue = nuevo_costo_unid; // strToCurrency(nuevo_costo_unid);
                document.querySelector(`#costo_unid_${codigo_bulto}`).classList.add('bg-orange-300');
                inputCostoUnidIndiv.typedValue = nuevo_costo_unid;  // strToCurrency(nuevo_costo_unid);
                document.querySelector(`#costo_unid_${artindiv}`).classList.add('bg-orange-300');
                inputCostoBultoIndiv.typedValue = "0,00";
            }
        }
    }
};

const _buscarInputIMask = id => {
    const idx = _LISTA_PREC._inputsIMask.findIndex(elem => elem.el.input.id === id);

    return _LISTA_PREC._inputsIMask[idx];
};


export default _blurCostoBulto;