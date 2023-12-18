<?php
    include_once 'productEntity.php';
    include_once 'productRepository.php';

    if( !empty($_POST['id']) && !empty($_POST['name'])) {
        $productRepository = new ProductRepository();
        $product = $productRepositor->get($_POST['id']);
        if($product == null) {
            echo 'Error 404 producto no encontrado';
            exit;
        }
        $product->setName($_POST['name']);
        $productRepository->store($product);
    }