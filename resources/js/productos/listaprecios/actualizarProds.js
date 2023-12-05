import MensajeAlerta from "../../utils/MensajeAlerta";


const _actualizarProds = listado => {

    Promise.all([ _persistirDatos(listado) ])
        .then(function (results) {
            _ocultaSpinGuardando();
            MensajeAlerta('Precios actualizados con exito !', 'success');
        })
        .catch(function (error) {
            //console.log('Error:', error);
            MensajeAlerta('Error salvando precio !', 'error');
        });

    return null;
};

const _persistirDatos = listado => {
    return axios.post(_LISTA_PREC._pathSalvarLista, listado);
}

const _ocultaSpinGuardando = () => {
    const spinGuardando = document.querySelector('#spinGuardando');
    spinGuardando.classList.add('invisible');

    return null;
};

export default _actualizarProds;