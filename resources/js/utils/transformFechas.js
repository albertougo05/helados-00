/**
 * 
 * Funciones para transformar fechas
 * 
 */
import formatMinutos from '../turnos/formatMinutos';
import { DateTime } from "luxon";


//
// '2022-12-01' -> '01/12/2022'
//
const englishToSpanish = function (fecha = '') {
    if (fecha.length > 0) {
        const arrNums = fecha.split('-');
        fecha = arrNums[2] + '/' + arrNums[1] + '/' + arrNums[0];
    }

    return fecha;
}


//
// '01/12/2022' -> '2022-12-01'
//
const spanishToEnglish = function (fecha = '') {
    if (fecha.length > 0) {
        const arrNums = fecha.split('/');
        fecha = arrNums[2] + '-' + arrNums[1] + '-' + arrNums[0];
    }

    return fecha;
}

/**
 * Fecha actual
 * 
 * @param string format ('es' or 'en')
 * @return string 
 */
const fechaActual = function(format = 'es') {
    const date = new Date()
    const day = `${(date.getDate())}`.padStart(2,'0');
    const month = `${(date.getMonth()+1)}`.padStart(2,'0');
    const year = date.getFullYear();

    if (format === 'es') {
        return `${day}/${month}/${year}`;
    } else if (format === 'en') {
        return `${year}-${month}-${day}`;       // English
    }
}

/**
 * Devuelve hora actual del sistema
 * 
 * @return string
 */
const horaActual = () => {
    const _hora = new Date();

    return formatMinutos(_hora.getHours()) + ':' + formatMinutos(_hora.getMinutes());
}

/**
 * Valida que la fecha sea válida
 * 
 * @param string
 * @return boolean
 */
const _validaFecha = (fecha = null) => {
    // Compara que los dias no sean mayores a 31 y meses a 12
    const DATE_REGEX = /^(0[1-9]|[1-2]\d|3[01])(\/)(0[1-9]|1[012])\2(\d{4})$/;
    const CURRENT_YEAR = new Date().getFullYear();

    if (!fecha.match(DATE_REGEX)) {
        return false;
    }

    /* Comprobar los dias del mes */
    const day = parseInt(fecha.split('/')[0]),
          month = parseInt(fecha.split('/')[1]),
          year = parseInt(fecha.split('/')[2]),
          monthDays = new Date(year, month, 0).getDate();

    if (day > monthDays) {
        return false;
    } else if (year < CURRENT_YEAR) {
        return false;
    }

    return true;
}

const _sumaRestaFecha = (fecha, operador = '+', cantDias = 0, format = 'en') => {
    let date = undefined;

    if (fecha === '') {     // Poner fecha actual
        date = DateTime.now();
    } else {
        date = DateTime.fromISO(fecha);   // debe ser formato 'en': '2023-07-31'
    }

    switch (operador) {
        case '+':
            date = date.plus({days: cantDias});
            break;
        case '-':
            date = date.minus({days: cantDias});
            break;
    }

    if (format === 'en') {
        return date.toFormat('yyyy-MM-dd');
    } else {
        return date.toFormat('dd/MM/yyyy');
    }
};


export { englishToSpanish, spanishToEnglish, fechaActual, horaActual, _validaFecha, _sumaRestaFecha }
