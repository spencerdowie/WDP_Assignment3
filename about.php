<?php
$pageTitle = "About";
require_once("components/header.php");
?>

<div class="py-3">
    <h1 class="display-6 fw-bold mb-3">About GA(IN)-ME</h1>
    <p class="lead text-muted">A modern board game collection platform built for table-top enthusiasts.</p>
    <div class="row g-4 my-2">
        <div class="col-md-4">
            <div class="card h-100 p-3 shadow-sm border-0">
                <h5 class="fw-bold">Discover</h5>
                <p class="text-muted small">Explore new board games across various categories, play styles, and player counts.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 p-3 shadow-sm border-0">
                <h5 class="fw-bold">Collect</h5>
                <p class="text-muted small">Maintain your board game inventory with customized personal collection tracking.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 p-3 shadow-sm border-0">
                <h5 class="fw-bold">Review</h5>
                <p class="text-muted small">Share reviews, recommend titles, and log play sessions with the community.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once("components/footer.php"); ?>