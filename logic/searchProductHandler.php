<?php
    include_once 'productRepository.php';

    if(!empty($_POST['filters'])) {
        $productRepository = new ProductRepository();
        $products = $productRepository->search($_POST['filters']);
    }