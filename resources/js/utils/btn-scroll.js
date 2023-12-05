//
/** Funcionamiento boton UpScroll  **/
// 
const _botonUp = document.getElementById("scrollUp");

_botonUp.addEventListener("click", function () {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
});
// When the user scrolls down 250px from the top of the document, show the button
window.onscroll = function() {
    if (document.body.scrollTop > 250 || document.documentElement.scrollTop > 250) {
        //$('#scrollUp').show();
         _botonUp.style.display = "block";
    } else {
        //$('#scrollUp').hide();
         _botonUp.style.display = "none";
    }    
};
/** end boton UpScroll **/