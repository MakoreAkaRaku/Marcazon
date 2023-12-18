<?php
    include_once 'productRepository.php';

    if(!empty($_POST['id'])) {
        $productRepository = new ProductRepository();
        $productRepository->delete($$_POST['id']);
    }