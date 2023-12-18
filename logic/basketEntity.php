<?php
class BasketEntity {
    private array $products = [];

    function __construct(
        ?array $products = []
    ) {
        $this->products = $products;
    }

    public function getProducts(): array {
        return $this->products;
    }

    function addProduct($product)
    {
        if(!in_array($product, $this->products)) {
            $this->products[] = $product;
        }
    }

    function removeProduct(string $product)
    {
        if(in_array($product, $this->products)) {
            unset($this->products[array_search($product, $this->products)]);
        }
    }
}

?>