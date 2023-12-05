/**
 * Dibuja los datos en la tabla de comprobantes del modal 
 * 
 */

import { strToCurrency } from "../utils/solo-numeros";


export default class DibujarTablaComprobs {
    constructor() {
        this.bodyTabla = document.querySelector('#bodyTablaComprobs');
    }

    iniciar(comprobantes) {
        this.vaciarBody();

        comprobantes.forEach(comprob => {
            this._armoLinea(comprob);
        });
    }

    vaciarBody() {
        this.bodyTabla.innerHTML = "";
        return null;
    }

    _armoLinea(data) {
        const letra_comprobante = 'B';   // Deberá venir en los datos del comprobante
        const tipos_comprobs = ['', 'FA', 'NC', 'ND', 'Egr', 'Ing'];
        const columnas = [
            "fecha",
            "hora",
            "tipo",
            "numero",
            //"sucursal",
            "caja_nro",
            "concepto",
            "efectivo",
            "debito",
            "tarjeta",
            "valores",
            "transf",
            "ctacte",
            "importe",
        ];
        const tr = document.createElement("tr");
        tr.dataset.idcomp = data.id;
        tr.classList.add("text-gray-700", "text-sm", "hover:bg-gray-100");

        columnas.forEach((e) => {
            const td = document.createElement("td");

            if (data.tipo_comprobante_id == 2 || data.tipo_comprobante_id == 4) {
                tr.classList.remove("text-gray-700");
                tr.classList.add("text-red-800", "italic");
            }

            switch (e) {
                case "fecha":
                    td.classList.add("pl-3", "py-2", "text-left");
                    td.textContent = this._getFecha(data.fecha_hora);
                    break;
                case "hora":
                    td.classList.add("text-center", "py-2", "pr-1");
                    td.textContent = this._getHora(data.fecha_hora);
                    break;
                case "tipo":
                    td.classList.add("text-center", "py-2", "pr-1");
                    td.textContent = tipos_comprobs[data.tipo_comprobante_id];
                    break;
                case "numero":
                    td.classList.add("text-center", "py-2", "pr-1");
                    td.textContent = this._getNumero(
                        data.tipo_comprobante_id,
                        data.codigo_comprob, 
                        data.nro_comprobante,
                        letra_comprobante);
                    break;
                // case "sucursal":
                //     td.classList.add("text-left", "py-2", "pr-1");
                //     td.textContent = data.nombre;
                //     break;
                case "caja_nro":
                    td.classList.add("text-center", "py-2", "pr-1");
                    td.textContent = data.caja_nro;
                    break;
                case "concepto":
                    td.classList.add("text-left", "py-2", "pr-1");
                    // td.textContent = data.concepto;  
                    td.textContent = this._getConcepto(data);
                    break;
                case "efectivo":
                    td.classList.add("text-right", "py-2", "pr-2");
                    td.textContent = strToCurrency(data.total_efectivo, true);
                    break;
                case "debito":
                    td.classList.add("text-right", "py-2", "pr-2");
                    td.textContent = strToCurrency(data.total_debito, true);
                    break;
                case "tarjeta":
                    td.classList.add("text-right", "py-2", "pr-2");
                    td.textContent = strToCurrency(data.total_tarjeta, true);
                    break;
                case "valores":
                    td.classList.add("text-right", "py-2", "pr-2");
                    td.textContent = strToCurrency(data.total_valores, true);
                    break;
                case "transf":
                    td.classList.add("text-right", "py-2", "pr-2");
                    td.textContent = strToCurrency(data.total_transfer, true);
                    break;
                case "ctacte":
                    td.classList.add("text-right", "py-2", "pr-2");
                    td.textContent = strToCurrency(data.cuenta_corriente, true);
                    break;
                case "importe":
                    td.classList.add("text-right", "py-2", "pr-2");
                    if (data.tipo_comprobante_id == 2) {
                        td.textContent = '-' + strToCurrency(data.importe);
                    } else {
                        td.textContent = strToCurrency(data.importe);
                    }
                    break;
            }
            tr.appendChild(td);
        });
        this.bodyTabla.appendChild(tr);

        return null;
    }

    _getFecha(fecha_hora) {
        const fecha = fecha_hora.substring(0, 10);
        const arrFecha = fecha.split('-');
        return arrFecha[2] + '/' + arrFecha[1] + '/' + arrFecha[0];
    }

    _getHora(fecha_hora) {
        return fecha_hora.substring(11, 16);
    }

    _getNumero(tipo, codigo, numero, letra) {
        let num = '';

        if ( tipo == 4 || tipo == 5) {
            num = '';
        } else {
            //num = letra + ' ' + codigo.toString().padStart(4, '0');
            num = letra + ' ' + codigo.toString();
            //num += '-' + numero.toString().padStart(8, '0');
            num += '-' + numero.toString();
        }
        return num;
    }

    _getConcepto(data) {
        let concepto = data.concepto;

        // if (data.total_efectivo > 0) {
        //     concepto += ' Efect.';
        // } else if (data.total_debito > 0) {
        //     concepto += ' c/T.Déb.';
        // } else if (data.total_tarjeta > 0) {
        //     concepto += ' c/T. Créd.';
        // } else if (data.total_valores > 0) {
        //     concepto += ' c/valores';
        // } else if (data.total_transfer > 0) {
        //     concepto += ' c/Transf.';
        // } else if (data.cuenta_corriente > 0) {
        //     concepto += ' a Cta.Cte.';
        // } else if (data.total_otros > 0) {
        //     concepto += ' c/otros val.';
        // }

        if (data.estado == 0) concepto += ' (Anul.)';

        return concepto;
    }
}
