<?php
    include_once 'productRepository.php';

    if(!empty($_POST['id'])) {
        $productRepository = new ProductRepository();
        $product = $productRepository->find($_POST['id']);
    }