<?php
    class BaseRepository {
        private function getConnection(): mysqli {
            return mysqli_connect('localhost', 'user', 'password');
        }

        private function execute(string $sql): ?array {
            $conn = $this->getConnection();
            $result = mysqli_query($conn, $sql);
            if(is_bool($result)) {
                return null;
            }
            return mysqli_fetch_array($result);
        }
    }