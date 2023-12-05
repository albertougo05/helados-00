//
// Clase para autocompletado de input buscar producto
// (https://codepen.io/delineas/pen/yLVLOmx)
//
import axios from 'axios';


class Autocomplete {
    constructor (input, autocomplete_results, div_autocomplete_results, input_costo, input_cantidad) {
        this.input = input;
        this.autocomplete_results = autocomplete_results;
        this.div_autocomplete_results = div_autocomplete_results;
        this.input_costo = input_costo;
        this.input_cantidad = input_cantidad;
        this.listaDesplegada = false;
        this.textoDeBusqueda = '';
    }

    init() {
        this.addEventsToInput();
        this.addEventsToLista();
    }

    reset() {
        this.div_autocomplete_results.classList.add('hidden');
        this.listaDesplegada = false;
        this.textoDeBusqueda = '';
    }

    addEventsToInput() {
        this.input.addEventListener('keyup', (event) =>  {
            let key = event.target.value;

            if (key.length === 0) {

                if (event.keyCode === 13) {
                    event.preventDefault();
                        this.textoDeBusqueda = key;
                        this.search(key);
                    return null;
                }

                this.reset();
                document.querySelector('#busq_codigo').value = '';
                this.input_costo.typedValue = '';
                this.input_cantidad.typedValue  = '';
                document.querySelector('#busq_idprod').value = '';
                return null;
            }

            if(key.length > 0) {
                //console.log('Buscar key:', key);
                if (key !== this.textoDeBusqueda) {
                    this.textoDeBusqueda = key;
                    this.search(key);
                }
            }
        });

        this.input.addEventListener('focus', (e) =>  {
            e.target.select();
        });
    }

    /**
     * Agrega eventos a la lista desplegable
     */
    addEventsToLista() {
        this.autocomplete_results.addEventListener('click', (e) => {

            if (e.target && e.target.matches("li")) {
                this.input.value = e.target.innerText;
                document.querySelector('#busq_codigo').value = e.target.dataset.codigo;
                this.input_costo.typedValue = e.target.dataset.costo;
                this.input_cantidad.typedValue  = '1';
                document.querySelector('#busq_idprod').value = e.target.dataset.idprod;
                const btnAgregarProd = document.querySelector('#btnAgregarProd');
                btnAgregarProd.disabled = false;
                btnAgregarProd.style.cursor = "pointer";
                document.querySelector('#busq_cantidad').focus();
                this.reset();
            }
        });
    }

    /**
     * Construye la lista de búsqueda
     * 
     * @param array items 
     */
    buildList(items) {
        const clases = "block px-2 py-1 mt-1 cursor-pointer text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50";
        let linea;

        //console.log('elems', items)
            
        if(items === undefined) {
            items = [];
            this.autocomplete_results.innerHTML = '';
            this.listaDesplegada = false;

            return null;
        }

        this.autocomplete_results.innerHTML = '';

        items.forEach(item => {
            let linea = `<li class="${clases}" tabindex="-1" data-codigo="${item.codigo}" `;
            linea += `data-costo="${item.costo}" data-idprod="${item.id}">${item.descripcion}</li>`;

            this.autocomplete_results.innerHTML += linea;
        });

        this.listaDesplegada = true;
        this.div_autocomplete_results.classList.remove('hidden');
    }

    /**
     * Busca texto ingresado en DB
     * 
     * @param {string} key 
     * @return null
     */
    async search(key) {
        const api = `/producto/buscar?buscar=${encodeURI(key)}`;

        return await axios.get(api)
            .then((response) => {

                if(Array.isArray(response.data)) {
                    // this.buildList(response.data.map(item => {
                    //    return item;
                    // }));
                    this.buildList(response.data);
                }
            })
            .catch(error => {
                console.log(error);
        });
    }
}

export default Autocomplete;
