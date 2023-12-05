import { strToFloat } from '../../utils/solo-numeros';

const _blurInputs = elem => {
    const valorActual = strToFloat(elem.value),
          original = parseFloat(elem.dataset.valororig);

    if (valorActual !== original) {
        elem.classList.add('bg-orange-300');
    }
};

export default _blurInputs;