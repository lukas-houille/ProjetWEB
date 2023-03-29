window.onload = function() {
    const allPopups = document.getElementsByClassName("popup");
    document.getElementsByClassName("popup-close")[0]?.addEventListener('click', function hidePopUp() { allPopups[0].style.display = "none"; }, false);

    openPopUp = function (id) {
        document.getElementById(id).style.display = "block";
    }
}