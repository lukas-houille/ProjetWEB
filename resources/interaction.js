function showFilterOffers() {
    document.getElementById("offers-popup-filter").style.display = "block";
}

window.onload = function() {
    document.getElementById("offers-popup-filter-close").addEventListener('click', function hidePopUp() {
        document.getElementsByClassName("pop-up")[0].style.display = "none";
    });
}