/**
 * Utilidades Varias con números
 */

// Cuenta caracteres de una cadena (.) puntos
String.prototype.count = function(c) { 
	var result = 0, i = 0;
	for(i;i<this.length;i++)if(this[i]==c)result++;
	return result;
  };

// Mascara de solo ingreso de número en input
const soloNumeros = e => {
    const valor = e.target.value;
    if (/\D/g.test(valor)) {
        // Filter non-digits from input value.
        e.target.value = e.target.value.replace(/\D/g, '');
    }
};

// Convierte string '1.250.000,25' A (float) 1250000.25
const strToFloat = num => {
	if (num === "" || num === null)
		return 0;

	if (typeof num === 'string') {
		num = num.replaceAll('.', '');
		num = num.replace(',', '.');
	}

    num = parseFloat(num);

    return num;
}

const strToCurrency = (num, returnSpace = false, dec = 2) => {
	if (num === "" || num === null || num === '0.00' || num === '0,00') {
		if (returnSpace) {
			return "";
		} else return num;
	}

	if (typeof num === 'string') {
		num = num.replaceAll('.', '');
		num = num.replace(',', '.');
	}

	num = parseFloat(num);

	return ( num
      .toFixed(dec) 			// decimal digits
      .replace('.', ',') 	// replace decimal point character with ,
      .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
	)
}

/**
 * Redondea a dos decimales
 * Fuente: https://www.delftstack.com/es/howto/javascript/javascript-round-to-2-decimal-places/
 * 
 * @param {*} num 
 * @returns decimal
 */
function _roundToTwo(num) {
    return +(Math.round(num + "e+2")  + "e-2");
}

/**
 * Redondea a cantidad x de decimales
 * Fuente: https://medium.com/swlh/how-to-round-to-a-certain-number-of-decimal-places-in-javascript-ed74c471c1b8
 * 
 * @param {number} num 
 * @param {integer} decPlaces
 * @returns {number}
 */
const _redondeoExacto = (num, decPlaces) =>
    Number(Math.round(num + "e" + decPlaces) + "e-" + decPlaces);


export { soloNumeros, strToFloat, strToCurrency, _roundToTwo, _redondeoExacto };
