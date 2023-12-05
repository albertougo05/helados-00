/**
 * Calcula diferencia entre lo ingresado como arqueo 
 * y la caja teórica (lo que debería dar)
 */

import { strToFloat, strToCurrency } from "../utils/solo-numeros";


export default class {
    constructor(in_arqueo, in_teorica, in_difer) {
        // Recibo los inputs...
        this.in_arqueo = in_arqueo;
        this.in_difer = in_difer;
        this.teorica = strToFloat(in_teorica.value);
    }

    ingresoValor(valor) {
        let importe = strToFloat(valor);
        const inputDif = document.getElementById('diferencia');
        const inputArq = document.getElementById('arqueo');

        // if (valor === '' || valor === '0,00') {
        //     this.in_difer.typedValue = '';
        //     inputDif.classList.remove('text-red-500');
        //     inputArq.classList.remove('text-red-500');
        // } else if (importe < this.teorica) {
        //     this.in_difer.typedValue = 0 - (this.teorica - importe);
        //     inputDif.classList.add('text-red-500');
        //     inputArq.classList.add('text-red-500');
        // } else if (importe >= this.teorica) {
        //     this.in_difer.typedValue = importe - this.teorica;
        //     inputDif.classList.remove('text-red-500');
        //     inputArq.classList.remove('text-red-500');
        // }

        if (valor === '' || valor === '0,00') {
            importe = 0;
            //this.in_arqueo.typedValue = 0;
        }

        if (importe < this.teorica) {
            this.in_difer.typedValue = 0 - (this.teorica - importe);
            inputDif.classList.add('text-red-500');
            inputArq.classList.add('text-red-500');
        } else if (importe >= this.teorica) {
            this.in_difer.typedValue = importe - this.teorica;
            inputDif.classList.remove('text-red-500');
            inputArq.classList.remove('text-red-500');
        }

        return null;
    }
}
