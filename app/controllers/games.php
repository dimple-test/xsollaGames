<?php
    class Games extends Controller{

        /**
         * Get games list and return on list view
         */
        public function index() {
            $this->view('home');
        }

        public function prepareGameList(){
            $game = $this->model('GamesModel');
            $gamesList = $game->getGamesList();
            $this->view('list', $gamesList);
        }

        /**
         * Add/Edit games detail
         * 
         * return json response
         */
        public function addEdit() {
            $response = [
                'status' => 0,
                'data' => [],
                'message' => 'Something went wrong, Please try again!'
            ];
            if (!empty($_POST)) {
                $gameData = [
                    'title' => !empty($_POST['title']) ? $_POST['title'] : '',
                    'platform' => !empty($_POST['platform']) ? $_POST['platform'] : '',
                    'star_rating' => !empty($_POST['rating']) ? $_POST['rating'] : '',
                    'review' => !empty($_POST['review']) ? $_POST['review'] : '',
                    'last_played' => !empty($_POST['last_played']) ? date("Y-m-d G:i", strtotime($_POST['last_played'])) : date('Y/m/d G:i', time()),
                    'updated' => date('Y-m-d G:i:s')
                ];
                $game = $this->model('GamesModel');
                if (!empty($_POST['id'])) {
                    $gameUpdated = $game->editGame($gameData, $_POST['id']);
                    if ($gameUpdated) {
                        $gameId = $_POST['id'];
                        $response['message'] = 'Record updated succesfully';
                    }
                } else {
                    $gameData['created'] = date('Y-m-d G:i:s');
                    $gameId = $game->addGame($gameData);
                    $response['message'] = 'Record added succesfully';
                }
                if (!empty($gameId)) {
                    $newRecord = $game->getGameByValue('id', $gameId);
                    if (!empty($newRecord)) {
                        $response['status'] = 1;
                        $response['data'] = $newRecord;
                    }
                }
                
            }
            echo json_encode($response);
            exit;
        }

        public function getGameDetail() {
            $response = [
                'status' => 0,
                'data' => [],
                'message' => 'Something went wrong, Please try again!'
            ];
            if (!empty($_GET) && !empty($_GET['id'])) {
                $game = $this->model('GamesModel');
                $gameDetail = $game->getGameByValue('id', $_GET['id']);
                if(!empty($gameDetail)){
                    $response = [
                        'status' => 1,
                        'data' => $gameDetail,
                        'message' => 'Record fetched succesfully'
                    ];
                }
            }
            echo json_encode($response);
            exit();
        }

        public function deleteGame() {
            $response = [
                'status' => 0,
                'data' => [],
                'message' => 'Something went wrong, Please try again!'
            ];
            if (!empty($_GET) && !empty($_GET['id'])) {
                $game = $this->model('GamesModel');
                $gameDeleted = $game->deleteGame($_GET['id']);
                if ($gameDeleted) {
                    $response = [
                        'status' => 1,
                        'data' => [],
                        'message' => 'Record deleted successfully!'
                    ];
                }
            }
            echo json_encode($response);
            exit();
        }



    }
?>

