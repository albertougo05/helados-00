/**
 * Buscar Detalle de pedido seleccionado (private)
 */

async function buscarDetalleMovim(id_movim) {
    let url = _INFO_MOVS_STOCK._pathDetalleComprob.slice(0, -1);
    url += id_movim;
    let res = await axios.get(url);
    let data = res.data;

    return data.detalle;
}

export default buscarDetalleMovim;