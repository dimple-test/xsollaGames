<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    </head>
    <body>
        <main>
            <div class="container">
                <?php include_once("header.php"); ?>
                <div>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        Add Game Details
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Game Details</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="mb-3">
                                    <label for="inputTitle" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="inputTitle" aria-describedby="titleHelp">
                                </div>
                                <div class="mb-3">
                                    <label for="inputPlatform" class="form-label">Platform</label>
                                    <input type="text" class="form-control" id="inputPlatform" aria-describedby="platformHelp">
                                </div>
                                <div class="mb-3">
                                    <label for="inputRating" class="form-label">Rating</label>
                                    <input type="text" class="form-control" id="inputRating" aria-describedby="ratingHelp">
                                </div>
                                <div class="mb-3">
                                    <label for="inputReview" class="form-label">Review</label>
                                    <textarea class="form-control" id="inputReview" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="inputLastPlay" class="form-label">Lastly Played on</label>
                                    <input type="text" class="form-control" id="inputLastPlay" aria-describedby="platformHelp">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">submit</button>
                        </div>
                        </div>
                    </div>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Platform</th>
                            <th scope="col">Star Rating</th>
                            <th scope="col">Review Text</th>
                            <th scope="col">Last_played</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php include_once("footer.php"); ?>
            </div>
        </main>
        <script src="/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="/js/script.js" crossorigin="anonymous"></script>
    </body>
</html>


