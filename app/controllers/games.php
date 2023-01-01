<?php
    class Games extends Controller{
        use encryptDecrypt;

        /**
         * Get games list and return on list view
         */
        public function index() {
            $this->view('home');
        }

        public function prepareGameList(){
            $response = [
                'status' => 0,
                'data' => '',
                'recordCount' => 0,
                'message' => 'Something went wrong, Please try again!'
            ];
            $game = $this->model('GamesModel');
            $gamesList = $game->getGamesList();
            $content = '';
            if (!empty($gamesList)) {
                $response['recordCount'] = count($gamesList);
                $response['content'] = count($gamesList); 
                foreach($gamesList as $k => $val) {
                    $content .='<tr>
                        <td><input type="checkbox" class="form-check-input checkbox" value='.$val["id"].' name="ids[]" /></td>
                        <td>'.$val["title"].'</td>
                        <td>'.$val["platform"].'</td>
                        <td>'.$val["star_rating"].'</td>
                        <td>'.$val["review"].'</td>
                        <td>'.date("m/d/Y h:i a", strtotime($val["last_played"])).'</td>
                        <td>
                            <a class="editGame" data-id='.$val["id"].' href="#" data-bs-toggle="modal" data-bs-target="#addEditGameModal"><i class="bi bi-pencil-square me-3"></i></a>
                            <a href="#" data-id='.$val["id"].' class="deleteGame"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>';
                }
            } else {
                $content = '<tr>  <td colspan="8" class="text-center">No games found</td> </tr>';
            }

            $response['status'] = 1;
            $response['data'] = $content;
            $response['message'] = 'Record fetched succesfully';

            echo json_encode($response);
            exit();
        }

        public function dataTableList() {
            $query = array(
                "from" => " FROM games",
            );
            require_once('config/tableData.php');
            $dataTable = new tableData($query);
            $dataTable->get();
            echo  json_encode($dataTable->get());
            exit();
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
                if($this->checkFormErrors($_POST)) {
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
                        //TODO:: Update game
                        $gameUpdated = $game->editGame($gameData, $this->convert_string('decrypt', $_POST['id']) );
                        if ($gameUpdated) {
                            $gameId = $_POST['id'];
                            $response['status'] = 1;
                            $response['message'] = 'Game details updated succesfully';
                        }
                    } else {
                        //TODO:: Add new game
                        $gameData['created'] = date('Y-m-d G:i:s');
                        $gameId = $game->addGame($gameData);
                        if ($gameId) {
                            $response['status'] = 1;
                            $response['message'] = 'Game added succesfully';
                        }
                        
                    }
                    if (!empty($gameId)) {
                        $newRecord = $game->getGameByValue('id', $gameId);
                        if (!empty($newRecord)) {
                            $response['status'] = 1;
                            $response['data'] = $newRecord;
                        }
                    }
                } else {
                    $response['message'] = "Please fill out all fields with valid data.";
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
                $gameDetail = $game->getGameByValue('id', $this->convert_string('decrypt', $_GET['id']));
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
                $gameDeleted = $game->deleteGame($this->convert_string('decrypt', $_GET['id']));
                if ($gameDeleted) {
                    $response = [
                        'status' => 1,
                        'data' => [],
                        'message' => 'Game deleted successfully!'
                    ];
                }
            }
            echo json_encode($response);
            exit();
        }

        public function deleteGames() {
            $response = [
                'status' => 0,
                'data' => [],
                'message' => 'Something went wrong, Please try again!'
            ];
            if (!empty($_POST) && !empty($_POST['ids']) && count($_POST["ids"]) > 0) {
                $game = $this->model('GamesModel');
                $decrypted_ids = array_map(function ($id) {
                    return $this->convert_string('decrypt', $id);
                }, $_POST['ids']);
                $gameDeleted = $game->deleteGames($decrypted_ids);
                if ($gameDeleted) {
                    $response = [
                        'status' => 1,
                        'data' => [],
                        'message' => 'Games deleted successfully!'
                    ];
                }
            }
            echo json_encode($response);
            exit();
        }

        public function about() {
            $this->view('about');
        }

        public function contact() {
            $this->view('contact');
        }

    }
?>

