//
// productos/mostrarImagen.js
//

const mostrarImgCreate = function () {
   const inputImg = document.querySelector('input#imagen');

    if (FileReader) {
        let reader = new FileReader();
        reader.readAsDataURL(inputImg.files[0]);
        reader.onload = function (e) {
            let image= new Image();
            image.src = e.target.result;
            image.onload = function () {
                let divImg = document.getElementById('divImagen');
                const divInputImg = document.querySelector('#divInputImagen');
                document.getElementById('previewImagen').src = image.src;
                divImg.classList.remove('hidden');
                divInputImg.classList.add('hidden');
            };
        }
    }
};


export { mostrarImgCreate  };