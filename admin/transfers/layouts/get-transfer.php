<?php

require plugin_dir_path(dirname(__FILE__)) . '/assets/php/get.php';
if (isset($transfers) && isset($transfers['data'])) :
    $transfer = extract((array) $transfers['data']);
// echo json_encode($transfers['data']);
endif;
?>
<br><br>
<div class="rimplenet-bs5">
    <div class="container">
        <div class="mt-4 p-5 bg-primary text-white rounded">
            <h1 class="fw-normal">Transfer Details #<?= $transferId ?? '__' ?></h1>
            <p class="fs-5"> <?= $transferDesc ?? '__' ?></p>
            <span> <span class="fw-bold fs-5">Date: </span> <?= $formattedDate ?? '__' ?> </span>
        </div>
        <div class="row mt-5">
            <div class="col-5">
                <h4 class="fw-normal">Transfer id</h4>
            </div>
            <div class="col-7"> 
                <p class="fs-5  fw-light">#<?= $transferId ?? '__' ?></p>
            </div>
            <div class="col-5">
                <h4 class="fw-normal">Transfer to</h4>
            </div>
            <div class="col-7">
                <p class="fs-5  fw-light">
                <?= $transferTo ?? '__' ?> 
                </p>
            </div>
            <div class="col-5">
                <h4 class="fw-normal">Transfer from</h4>
            </div>
            <div class="col-7"> 
                <p class="fw-light fs-5"> <?= $transferFrom ?? '__' ?></p> 
            </div>
            <div class="col-5">
                <h4 class="fw-normal">Currency</h4>
            </div>
            <div class="col-7"> 
                <p class="fs-5  fw-light">
                    <?= strtoupper($currency ?? 'USD') ?> (<?= $symbol ?? '$' ?>) </p>
            </div>
            <div class="col-5">
                <h4 class="fw-normal">Transfer amount</h4>
            </div>
            <div class="col-7"> 
               <p class="fs-5 fw-light"><?= $symbol ?? '$' ?><?= number_format($transferAmount ?? 0, 2) ?? 0 ?></p>  
            </div>
            <div class="col-5">
                <h4 class="fw-normal">Transfer type</h4>
            </div>
            <div class="col-7"> 
               <p class="fs-5  fw-light"><?= $transferType ?? '__' ?></p>   
            </div>
            <div class="col-5">
                <h4 class="fw-normal">Total balance before</h4>
            </div>
            <div class="col-7"> 
               <p class="fs-5  fw-light"><?= $symbol ?? '$' ?><?= number_format($totalBalanceBefore ?? 0, 2) ?? '__' ?></p>   
            </div>
            <div class="col-5">
                <h4 class="fw-normal">Transfer balance after</h4>
            </div>
            <div class="col-7"> 
               <p class="fs-5  fw-light"><?= $symbol ?? '$' ?><?= number_format($totalBalanceAfter ?? 0, 2) ?? '__' ?></p>   
            </div>
        </div>
    </div>
</div>