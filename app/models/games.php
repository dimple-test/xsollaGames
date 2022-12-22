<?php

    class GamesModel extends Database{
        
        public function getGamesList($start = 0, $limit=0) {
            $result = [];
            $sql = "SELECT * FROM games ORDER BY id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            return $result;
        }
    }
?>