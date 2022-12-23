$(document).ready(function() {
    $("#list-load").show();
    
    //TODO::get games list
    getGameList();
    function getGameList() {
        $.ajax({
            url: "/app/games/prepareGameList",
            type: "GET",
            success: function(res) {
                if (res) {
                    $("#gameList").html(res);
                }
            },
            complete: function () {
                $("#list-load").hide(); //Request is complete so hide spinner
            },
            error: function (request, error) {
                console.log("Error" + error);
            }
        }).done(function() {
            // $( this ).addClass( "done" );
        }); 
    }

    rating.create({
        'selector': '#rating',
        'outOf': 5,
        'defaultRating': 1,
        'name':'rating',
        'ratingClass': ['me-2']
    });

    //TODO::Submit game form
    $( "#addEditGameForm" ).submit(function( e ) {
        e.preventDefault();
        $.ajax({
            url: "/app/games/addEdit",
            type: "POST",
            dataType: "json",
            processData: false,
            contentType: false,
            data: new FormData(this),
            context: document.body,
            success: function(res) {
                if (res && res.status == 1) {
                    //TODO::append new added record
                    // var gameRow = getGameRow(res.data);
                    // if (gameRow) {
                    //     $("#gameListTable").prepend(gameRow);
                    // }

                    $("#list-load").show();
                    //TODO::update games list
                    getGameList();

                    $("#addEditGameModal").modal('hide');
                    $("#addEditGameForm")[0].reset();
                    
                } else {

                }
            },
            error: function (request, error) {
                console.log("Error" + error);
            }
        });
    });
    
    function getGameRow(data){
        var gameRow = "";
        if (data) {
            gameRow = `<tr>
                <th scope="row">${data.id}</th>
                <td>${data.title}</td>
                <td>${data.platform}</td>
                <td>${data.star_rating}</td>
                <td>${data.review}</td>
                <td>${data.last_played}</td>
                <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#updateModal" data-updateId=${data.id}><i class="bi bi-pencil-square me-3"></i></a>
                    <a href="#" class="deleteGame"><i class="bi bi-trash"></i></a>
                </td>`;
        }
        return gameRow;
    }

    //TODO::get game existing data on update
    $(document).on('click', "a.editGame",function( e ) {
        e.preventDefault();
        var gameId = $(this).data('id');
        $(inputGameId).val(gameId);
        
        $.ajax({
            url: "/app/games/getGameDetail",
            type: "GET",
            dataType: "json",
            data: {id: gameId},
            success: function(res) {
                if (res && res.status == 1 && res.data) {
                    $("#inputTitle").val(res.data.title ? res.data.title : '');
                    $("#inputPlatform").val(res.data.platform  ? res.data.platform : '');
                    $("#inputRating").val(res.data.star_rating ? res.data.star_rating : '');
                    $("#inputReview").val(res.data.review ? res.data.review : '');
                    $("#inputLastPlay").val(res.data.last_played ? res.data.last_played : '');
                } else {

                }
            },
            error: function (request, error) {
                console.log("Error" + error);
            }
        });
    });

    $("#addGameBtn").on("click", function() {
        $("#addEditGameForm")[0].reset();
        $("#inputGameId").val("");
    });

    //TODO::delete game
    $(document).on('click', "a.deleteGame",function( e ) {
        var gameId = $(this).data('id');
        if (confirm("Are you sure you want to delete this game?") == true) {
            console.log("sadsa", gameId);
            $.ajax({
                url: "/app/games/deleteGame",
                type: "GET",
                dataType: "json",
                data: {id: gameId},
                success: function(res) {
                    $("#list-load").show();
                    //TODO::update games list
                    getGameList();
                },
                error: function (request, error) {
                    console.log("Error" + error);
                }
            });
        }
        
    });

    // $("#rating > img").css("margin-right", "5px");

 });