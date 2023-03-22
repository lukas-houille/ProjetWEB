window.onload = function() {
    const btnFilterPopup = document.getElementsByClassName("btn-filter-popup")[0];
    const FilterPopup = document.getElementsByClassName("btn-filter-popup")[0];
    const allPopups = document.getElementsByClassName("popup");
    const btnClose = document.getElementsByClassName("popup-close")[0];

    btnFilterPopup.addEventListener('click', function showFilter() { FilterPopup.style.display = "block"; }, false);
    btnClose.addEventListener('click', function hidePopUp() { allPopups.style.display = "none"; }, false);
}