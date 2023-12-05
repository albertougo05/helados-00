/**
 * Mascaras para inputs
 */

const masks = {
    // Mascara de valores con decimales (precios)
    currency: {
        mask: Number,
        thousandsSeparator: '.',
        scale: 2,
        signed: true,
        padFractionalZeros: true,  // if true, then pads zeros at end to the length of scale
        normalizeZeros: true,  // appends or removes zeros at ends
        radix: ',',  // fractional delimiter
        mapToRadix: ['.']  // symbols to process as radix
    },
    // Mascara para 3 decimales
    tresDecimales: {
        mask: Number,
        thousandsSeparator: '.',
        scale: 3,
        signed: false,
        padFractionalZeros: true,  // if true, then pads zeros at end to the length of scale
        normalizeZeros: true,  // appends or removes zeros at ends
        radix: ',',  // fractional delimiter
        mapToRadix: ['.']  // symbols to process as radix
    },
    // Mascara para 4 digitos. (ceros a la izq)
    cuatroCerosIzq: {
        mask: /^\d+$/,
        commit: function (value, masked) {
            if (value !== '') 
                masked._value = value.padStart(4, '0');
        }
    },
    // Mascara para 8 digitos. (ceros a la izq)
    ochoCerosIzq: {
        mask: /^\d+$/,
        commit: function (value, masked) {
            if (value !== '') 
                masked._value = value.padStart(8, '0');
        }
    },
    // Mascar hora (00:00)
    hora: {
        mask: 'HH:mm',
        lazy: false,
        blocks: {
            HH: {
                mask: IMask.MaskedRange,
                from: 0,
                to: 23
            },
            mm: {
                mask: IMask.MaskedRange,
                from: 0,
                to: 59
            }
        }
    },

};

export default masks;