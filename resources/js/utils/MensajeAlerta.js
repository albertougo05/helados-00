import Swal from "sweetalert2";

/**
 * Muestra modal de mensaje de alerta
 * 
 * @param {*} mensaje 
 * @param {*} icono ('success', 'error', 'warning', 'info', 'question')
 * @param {*} position 
 * @returns 
 */

const MensajeAlerta = (mensaje = "", icono = "error", position = "center", timer = 5000) => {
    Swal.fire({
        position: position,
        icon: icono,
        title: mensaje,
        timer: timer,
    });

    return null;
}

const mensajeErrorEntendido = (mensaje = '', error) => {
    Swal.fire({
        title: 'Error !!',
        text: mensaje + error,
        icon: 'error',
        confirmButtonText: 'Entendido'
    });

    return null;
};

export { MensajeAlerta as default, mensajeErrorEntendido }