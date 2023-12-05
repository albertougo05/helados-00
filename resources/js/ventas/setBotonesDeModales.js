const _setBotonesDeModales = () => {
    const closeModalVta = document.querySelectorAll('.close-modal');
    const closeModal2 = document.querySelectorAll('.close-modal-2');
    const closeModalPromo = document.querySelectorAll('.close-modal-promo');

    closeModalVta.forEach(close => {
        close.addEventListener('click', function(e) {
            document.querySelector('.modal').classList.add('hidden');
        });
    });

    closeModal2.forEach(close => {
        close.addEventListener('click', function() {
            document.querySelector('.modal-2').classList.add('hidden');
        });
    });

    closeModalPromo.forEach(close => {
        close.addEventListener('click', function() {
            document.querySelector('.modal-promo').classList.add('hidden');
        });
    });
}

export default _setBotonesDeModales;