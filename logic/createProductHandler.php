<?php
    include_once 'productEntity.php';
    include_once 'productRepository.php';

    if(!empty($_POST['name'])) {
        $product = new ProductEntity(
            null,
            $_POST['name'],
        );
        $productRepository = new ProductRepository();
        $productRepository->store($product);
    }