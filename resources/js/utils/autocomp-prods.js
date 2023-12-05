import axios from 'axios';

//
// Configuración Autocompletado input buscar producto
// (https://github.com/tomik23/autocomplete)
//

const autocomp_prods = {
    insertToInput: true,
    selectFirst: true,
    clearButton: true,
    howManyCharacters: 3,
    delay: 1000,
    cache: true,

    onSearch: async ({ currentValue }) => {
        const api = `/producto/buscar?buscar=${encodeURI(currentValue)}`;

        return await axios.get(api)
            .then((response) => {
                return response.data;
            })
            .catch(error => {
                console.log(error);
        });
    },

    onSelectedItem: ({ index, element, object }) => {
        element.value = object.descripcion;
        
console.log('Objeto:', object);

        _STOCK.inputBusqCantidad.typedValue = "1";
        _STOCK.inputBusqCosto.typedValue = object.costo;
        _STOCK.inputBusqCodigo.value = object.codigo;
    },

    onResults: ({ matches, template }) => {
        // checking if we have results if we don't
        // take data from the noResults method
        return matches === 0 ? template : matches
            .map(el => {
                return `
                    <li>${el.descripcion}</li>`;
            }).join('');
    },

    noResults: ({ element, template }) => template(`<li>No hay resultados de: "${element.value}"</li>`)
};

export default autocomp_prods;