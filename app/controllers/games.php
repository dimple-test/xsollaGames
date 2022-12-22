<?php
    class Games extends Controller{

        public function index() {
            $game = $this->model('GamesModel');
            $gamesList = $game->getGamesList();
            $this->view('list', $gamesList);
        }
    }
?>

