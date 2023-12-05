/**
 * Devuelve la hora desde un timeStamp
 */

export default function horaFromTimestamp(timestamp = '') {
    let hora = undefined;
    if (timestamp !== '') {
        const arr = timestamp.split(' ');
        hora = arr[1];
        hora = hora.substring(0, 5);
    }

    return hora;
}