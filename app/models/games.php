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

        public function addGame($data = []) {
            if (!empty($data)) {
                $fields = $values = [];
                foreach($data as $key => $value) {
                    $fields[] = $key;
                    $values[] = ":{$key}";
                }

                $sql = "INSERT INTO games (".implode(',', $fields).") VALUES (".implode(',', $values).")";
                
                try{
                    $stmt = $this->conn->prepare($sql);
                    $this->conn->beginTransaction();
                    $stmt->execute($data);
                    $lastInsertedId = $this->conn->lastInsertId();
                    $this->conn->commit();
                    return $lastInsertedId;
                }catch(Exception $e) {
                    echo "Error: ".$e->getMessage();
                    $this->conn->rollback();
                }
                
            }
        }

        public function editGame($data = [], $id = "") {
            if (!empty($data) && !empty($id)) {
                $fields = "";
                foreach($data as $key => $value) {
                    $fields .= $key." = :".$key.",";
                }
                $fields = trim($fields, ",");
                $sql = "UPDATE games SET {$fields} where id = :id";
                // echo  $sql;
                // print_r($data);
                // exit;
                try{
                    $stmt = $this->conn->prepare($sql);
                    $this->conn->beginTransaction();
                    $data['id'] = $id;
                    if ($stmt->execute($data)) {
                        $this->conn->commit();
                        return true;
                    } else {
                        return false;
                    }
                    
                }catch(Exception $e) {
                    echo "Error: ".$e->getMessage();
                    $this->conn->rollback();
                    exit();
                }
                
            }
        }

        public function deleteGame($id = "") {
            if (!empty($id)) {
                $sql = "DELETE from games where id = :id";
                try{
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute([':id' => $id]);
                    if ($stmt->rowCount() > 0) {
                        return true;
                    } else {
                        return false;
                    }
                }catch(Exception $e) {
                    echo "Error: ".$e->getMessage();
                    return false;
                } 
            }
        }

        public function deleteGames($ids = "") {
            if (!empty($ids)) {
                $ids_query = str_repeat("?,", count($ids)-1) . "?";
                $sql = "DELETE from games where id in ($ids_query)";
                try{
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute($ids);
                    // print_r($stmt);
                    // exit;
                    if ($stmt->rowCount() > 0) {
                        return true;
                    } else {
                        return false;
                    }
                }catch(Exception $e) {
                    echo "Error: ".$e->getMessage();
                    return false;
                } 
            }
        }

        public function getGameByValue($field, $value) {
            $result = [];
            if (!empty($field) && !empty($value)) {
                $sql = "SELECT * FROM games WHERE {$field} = :{$field}";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$field => $value]);
                if ($stmt->rowCount() > 0) {
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    // if ($result) {
                    //     $result['last_played'] = date('m/d/Y h:i', strtotime($result['last_played']));
                    // }
                }
            }
            return $result;
        }

    }
?>