<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="updateModalLabel">Update Game details</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addGameForm" method="post" enctype="multipart/form-data">
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
                    <input name="rating" type="text" class="form-control" id="inputRating" aria-describedby="ratingHelp">
                </div>
                <div class="mb-3">
                    <label for="inputReview" class="form-label">Review</label>
                    <textarea name="review" class="form-control" id="inputReview" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="inputLastPlay" class="form-label">Lastly Played on</label>
                    <input name="last_played" type="text" class="form-control" id="inputLastPlay" aria-describedby="platformHelp">
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