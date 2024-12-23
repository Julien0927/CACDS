<?php
require_once 'header.php';
require_once 'templates/nav.php';
?>

<h1 class="cacds">Coupe de l'Amitié Corporative des Deux-Sèvres</h1>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}// Vérifier s'il y a un message de succès
?>
<div class="d-flex justify-content-center">
    <img src="/assets/logos/cacds_logo_CACDS.jpg" style="width: 10%; height: 10%" alt="cacds" class="img-fluid">
</div>
<section class="mt-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
            <fieldset>
                <legend>Actualités</legend>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <img src="/assets/icones/Square item bad.svg" alt="">
                    </div>
                    <div class="col-12 col-md-6">
                        <img src="/assets/icones/Square item Volley.svg" alt="">
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-12 col-md-6">
                        <img src="/assets/icones/Square item TdT.svg" alt="">
                    </div>
                    <div class="col-12 col-md-6">
                        <img src="/assets/icones/Square item petanque.svg" alt="">
                    </div>
                </div>
                <p>Contenu à l'intérieur du cadre.</p>
            </fieldset>

            </div>
        </div>
    </div>
</section>
<section class="d-flex flex-md-row flex-column justify-content-around mt-5 mb-5">
        <div >
            <img src="/assets/icones/Badminton item.svg" alt="">
        </div>
        <div >
            <img src="/assets/icones/Volley Item.svg" alt="">
        </div>
        <div >
            <img src="/assets/icones/TdT Item.svg" alt="">
        </div>
        <div >
            <img src="/assets/icones/petanque item.svg" alt="">
        </div>

</section>

<?php
require_once 'templates/footer.php';
