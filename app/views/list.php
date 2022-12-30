<table class="table table-striped" id="gameListTable" style="width:100%">
    <thead>
        <tr>
            <th><input type="checkbox" id="select_all" class="form-check-input" /></th>
            <!-- <th scope="col">Id</th> -->
            <th scope="col">Title</th>
            <th scope="col">Platform</th>
            <th scope="col">Star Rating</th>
            <th scope="col">Review Text</th>
            <th scope="col">Last Played</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody class="tableContent">
        <tr>
            <td colspan='8'>
                <div id="list-load" class="d-flex justify-content-center">
                    <div class="spinner-border text-danger" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div> 
            </td>
        </tr>
    </tbody>
</table>
