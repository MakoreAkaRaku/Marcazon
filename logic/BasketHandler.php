<?php
    include_once 'basketEntity.php';
    include_once 'basketRepository.php';

    $basketRepository = new BasketRepository ();

    $basketRepository->get($idBasket);

    $basket = new BasketEntity();
    $basket->addProduct('patata');
    $basket->addProduct('naranja');

    $basketRepository->store($basket);
?>