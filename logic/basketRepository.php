<?php
    include_once 'basketEntity.php';

    class BasketRepository {
        public function find($id): ?BasketEntity {
            "select * from basket, products";
            return new Basket($arrayProductos);
        }
        public function search(array $filters): array {
        }

        public function store(BasketEntity $basket): void {
        }

        public function delete(): void {

        }

        private function prepareFilters(array $filters): array
        {
        }
    }