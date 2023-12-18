<?php
    include_once 'baseRepository.php';
    include_once 'productEntity.php';

    class ProductRepository extends BaseRepository {
        public function find(int $id): ?ProductEntity {
            $result = $this->execute(sprintf("select * from products where id = %s", $id));

            if(empty($result)) {
                return null;
            }
            
            return new ProductEntity(
                $result[0]['id'],
                $result[0]['name']
            );
        }

        public function search(array $filters): array {
            $sql = 'select * from product';
            $where = $this->prepareFilters($filters);
            if(!empty($where)) {
                $sql = sprintf('%s WHERE %s', $sql, implode(' AND ', $where));
            }

            $result = [];
            $products = $this->execute($sql);
            foreach($products as $product) {            
                $products[] = new Product(
                    $product['id'],
                    $product['name']
                );
            }
            return $result;
        }

        public function store(ProductEntity $product): void {
            if($product->getId() == null) {
                $this->execute(sprintf('INSERT INTO products (name) VALUES ("%s")', addslashes($product->getName())));
            } else {
                $this->execute(sprintf('UPDATE products SET name = "%s" WHERE id = %s', addslashes($product->getName()), $product->getId()));
            }
        }

        public function delete(int $id): void {
            $this->execute(sprintf('DELETE products WHERE id = %s', $id));
        }

        private function prepareFilters(array $filters): array
        {
            $where = [];
            foreach($filters as $filterColumn => $filterValue) {
                if(empty($filterValue)) {
                    continue;
                }
                switch($filterColumn) {
                    case 'category':
                        $where[] = sprintf('category IN (%s)', '"'.implode('","', $filterValue).'"');
                    break;
                    case 'name':
                        $where[] = sprintf('name LIKE %s', $filterValue);
                    break;
                }
            }
            return $where;
        }
    }