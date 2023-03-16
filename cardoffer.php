<?php
include 'header.php';
?>


Hello {{planet}}




<form method="get" class="card">
    <img src='resources/images/Logo small.svg' alt='logo test' class='logosmall'>
    <div class="offer-info">
        <h3>Intitulé du poste</h3>
        <p>Nom entreprise</p>
        <p>Localisation</p>
        <div class="description">
            <div class="vertical-align">
                <p>Profile:<span> varProfile</span></p>
                <p>Durée:<span> x mois</span></p>
            </div>
            <div class="vertical-align">
                <p>Publication:<span> dd/mm/aaaa</span></p>
                <p>Debut:<span> dd/mm/aaaa</span></p>
            </div>
        </div>
        <div class="submit">
            <button>Postuler</button>
        </div>
    </div>
</form>
