window.onload = function() {
    const btnPopup = document.getElementsByClassName("btn-filter-popup")[0];
    const popup = document.getElementsByClassName("pop-up")[0];
    const btnClose = document.getElementsByClassName("popup-close")[0];

    btnPopup.addEventListener('click', function showFilter() { popup.style.display = "block"; }, false);
    btnClose.addEventListener('click', function hidePopUp() { popup.style.display = "none"; }, false);

}