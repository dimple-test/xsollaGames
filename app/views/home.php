<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
        <link rel="stylesheet" href="/css/bootstrap-icons.css">
        <link href="/css/custom.css" rel="stylesheet" crossorigin="anonymous">
        
        <!-- Tempus Dominus Styles -->
        <link rel="stylesheet" href="/css/datetimepicker/tempus-dominus.min.css" crossorigin="anonymous">

    </head>
    <body>
        <main>
            <div class="container">
                <?php include_once("header.php"); ?>
                <div>
                    <!-- Button trigger modal -->
                    <button id="addGameBtn" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEditGameModal">
                        Add Game Details
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="addEditGameModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addEditGameModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="addEditGameModalLabel">Add Game Details</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="addEditGameForm" method="post" enctype="multipart/form-data">
                                    <input type="hidden" value="" name="id" id="inputGameId"/>
                                    <div class="modal-body">      
                                        <div class="mb-3">
                                            <label for="inputTitle" class="form-label">Title</label>
                                            <input name="title" type="text" class="form-control" id="inputTitle" aria-describedby="titleHelp">
                                        </div>
                                        <div class="mb-3">
                                            <label for="inputPlatform" class="form-label">Platform</label>
                                            <input name="platform" type="text" class="form-control" id="inputPlatform" aria-describedby="platformHelp">
                                        </div>
                                        <div class="mb-3">
                                            <label for="inputRating" class="form-label">Rating</label>
                                            <!-- <input name="rating" type="text" class="form-control" id="inputRating" aria-describedby="ratingHelp"> -->
                                            <div id="rating"></div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="inputReview" class="form-label">Review</label>
                                            <textarea name="review" class="form-control" id="inputReview" rows="3"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="inputLastPlay" class="form-label">Lastly Played on</label>
                                            <!-- <input name="last_played" type="text" class="form-control" id="inputLastPlay" aria-describedby="platformHelp"> -->
                                            
                                            <div class="input-group" id="datetimepicker1" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                            
                                                <input name="last_played" id="inputLastPlay" type="text" class="form-control" data-td-target="#datetimepicker1" />
                                                <span class="input-group-text" data-td-target="#datetimepicker1" data-td-toggle="datetimepicker">
                                                    <span class="fas fa-calendar"></span>
                                                </span>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button id="addGame" type="submit" class="btn btn-primary">submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="gameList">
                        <div id="list-load" class="d-flex justify-content-center">
                            <div class="spinner-border text-danger" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include_once("footer.php"); ?>
            </div>
        </main>
        <script src="/js/jquery.js" crossorigin="anonymous"></script>
        <script src="/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="/js/rating.js" crossorigin="anonymous"></script>

        <!-- DateTimePicker JavaScript -->
        <script src="/js/datetimepicker/popper.min.js" crossorigin="anonymous"></script>
        <script src="/js/datetimepicker/tempus-dominus.min.js" crossorigin="anonymous"></script>
        <script src="/js/datetimepicker/solid.min.js"></script>
        <script src="/js/datetimepicker/fontawesome.min.js"></script>
        <script src="/js/datetimepicker/jQuery-provider.js"></script>
        
        <script src="/js/jquery.validate.min.js"></script>
        <script src="/js/additional-methods.min.js"></script>

        <script src="/js/script.js" crossorigin="anonymous"></script>
    </body>
</html>


