$(document).ready(function() {
    $("#list-load").show();
    

    //TODO::get games list
    // getGameList();

    $('#gameListTable').DataTable({
        processing: false,
        serverSide: true,
        select: {
            style: 'multi',
            selector: '.select-checkbox',
            items: 'row',
        },
        columns: [
            {
                "name": "id",
                "searchable":false,
                "orderable": false
            },
            {
                "name": "title",
                "searchable":true,
                "orderable":true
            },
            {
                "name": "platform",
                "searchable":true,
                "orderable":true
            },
            {
                "name": "star_rating",
                "searchable":true,
                "orderable":true
            },
            {
                "name": "review",
                "searchable":true,
                "orderable":false
            },
            {
                "name": "last_played",
                "searchable":true,
                "orderable":true,
                
            },
            {
                "data": null,
                "defaultContent": "",
            },
        ],
        fixedColumns: true,
        order: [[0, 'desc']],
        "columnDefs": [
            {
                "orderable":false,
                "targets": 0,
                "render": function(data, type, row, meta){
                    return '<input type="checkbox" class="form-check-input checkbox" value='+row[0]+' name="ids[]" />';  
                }
            },
            { 
                width: 100,
                "targets": 2,
            },
            { 
                "targets": 3,
                "render": function(data, type, row, meta){
                    let td = "";
                    for (let i = 1; i <= 5; i++) {
                        if (i <= row[3])
                            td += '<img src="/img/selectedStar.svg" style="height:16px">';
                        else 
                        td += '<img src="/img/unselectedStar.svg" style="height:16px">';
                    }
                    return td;
                }
            },
            { 
                width: 100,
                "targets": 4,
                "className": "text-truncate mw-2"
            },
            { 
                "targets": 5,
                "render": function(data, type, row, meta){
                    return new tempusDominus.DateTime( row[5]).format({ dateStyle: 'short', timeStyle: 'short'});
                }
            },
            { 
                "orderable":false,
                "targets": 6,
                "render": function(data, type, row, meta){
                   return '<a class="editGame" data-id='+row[0]+' href="#" data-bs-toggle="modal" data-bs-target="#addEditGameModal"><i class="bi bi-pencil-square me-3"></i></a><a href="#" data-id='+row[0]+' class="deleteGame"><i class="bi bi-trash"></i></a>';  
                }
            }            
        ],
        ajax: {
            url:'/app/games/dataTableList',
            type:'POST',
            /* Error handling */
            error: function(xhr, error, thrown){
                $(".example-grid-error").html("");
                $("#example").append('<tbody class="example-grid-error"><tr><th colspan="3">No data found in the server. Error: ' + xhr.responseText + ' </th></tr></tbody>');
                $("#example-grid_processing").css("display","none");
            }
        }
    });

    //TODO::enable Delete all button when select any
    changeDeleteButtonState()

    function getGameList() {
        $.ajax({
            url: "/app/games/prepareGameList",
            type: "GET",
            dataType: "json",
            success: function(res) {
                if (res.status) {
                    $(".tableContent").html(res.data);
                    if (res.recordCount == 0) {
                        //Disable delete button
                        $("#deleteGames").prop("disabled",true);
                        $('#select_all').prop('checked',false);
                        $("#select_all").prop("disabled",true);
                    }
                }
            },
            complete: function () {
                // $("#list-load").hide(); //Request is complete so hide spinner
                $('#list-load').attr('style','display:none !important');
            },
            error: function (request, error) {
                console.log("Error" + error);
            }
        }).done(function() {
            // $( this ).addClass( "done" );
        }); 
    }

    //TODO::Initialize datetimepicker
    const datePicker = new tempusDominus.TempusDominus(document.getElementById('datetimepicker1'), {
        restrictions: {
            maxDate: new tempusDominus.DateTime(),
            minDate: new tempusDominus.DateTime('1970/01/01'),
        },
        keepInvalid: false,
        localization: {
            format: 'mm/dd/yyyy hh:mm',
        },
        display: {
            buttons: {
                close: true,
            },
        },
    });
    
        

    $(document).on('click', "#select_all",function( e ) {
        if (this.checked) {
            $('.checkbox').each(function(){
                this.checked = true;
            });
        } else {
            $('.checkbox').each(function(){
                this.checked = false;
            });
        }
        changeDeleteButtonState();
    });
    
    $(document).on('click', ".checkbox",function( e ) {
        if($('.checkbox:checked').length == $('.checkbox').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        }
        changeDeleteButtonState();
    });

    function changeDeleteButtonState() {
        if ($('.checkbox:checked').length >= 1) {
            $("#deleteGames").prop("disabled",false);
        } else {
            $("#deleteGames").prop("disabled",true);
        }
    }

    //TODO::star rating
    rating.create({
        'selector': '#rating',
        'outOf': 5,
        'defaultRating': 0,
        'name':'rating',
        'ratingClass': ['me-2']
    });

    //TODO::reset add form
    $("#addGameBtn").on("click", function() {
        $("#addEditGameModalLabel").html("Add Game Detail");

        $("#addEditGameForm")[0].reset();
        $("#inputGameId").val("");

        //TODO::reset star rating
        $("#inputRating").val(0);
        setReviewStar(0);

        //TODO::reset last played value to default
        // var DateTimeVal = moment().subtract(1, 'hours').toDate();
        var DateTimeVal = new tempusDominus.DateTime();
        DateTimeVal.minutes = DateTimeVal.minutes - 1;
        datePicker.dates.setValue(tempusDominus.DateTime.convert(DateTimeVal));
    });
    
    //TODO::set alert
    const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
    const alert = (message, type) => {
        const wrapper = document.createElement('div')
        wrapper.innerHTML = [
            `<div class="alert alert-${type} alert-dismissible fade show" role="alert" data-mdb-delay="3000">`,
            `   <div>${message}</div>`,
            '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
            '</div>'
        ].join('')

        alertPlaceholder.append(wrapper);

        setTimeout(() => {
            $('.alert').alert('close');
        }, 3000);
    }

    //TODO::Validate form
    jQuery.validator.addMethod("greaterThanZero", function(value, element) {
        return this.optional(element) || (value > 0);
    }, "Rating selection is required.");

    var validator = $("#addEditGameForm").validate({
        ignore: "",
        rules: {
            title: {
                required: true,
            },
            platform: {
                required: true,
            },
            rating: {
                greaterThanZero : true
            },
            review: {
                required: true,
            },
            last_played: {
                required: true,
            }
        },
        messages: {
            title: "Title field is required.",
            platform: "Platform field is required.",
            rating: "Rating selection is required.",
            review: "Review field is required.",
            last_played: "Last played field is required.",

        },
        errorPlacement: function(error, element) {
            if(element.attr("name") == "last_played") {
                error.insertAfter($("#datetimepicker1"))
            } else if (element.attr("name") == "rating") {
                error.insertAfter($("#rating"))
            } else {
                error.insertAfter(element);
            }
        }
    });

    //TODO::Submit game form
    $( "#addEditGameForm" ).submit(function( e ) {
        //disable submit button

        e.preventDefault();
        if ($( "#addEditGameForm" ).valid()) {
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
                        // getGameList();
    
                        $("#addEditGameModal").modal('hide');
                        $("#addEditGameForm")[0].reset();

                        alert(res.message, 'success');
                        //TODO::reload datatable
                        $('#gameListTable').DataTable().ajax.reload();
                    } else {
                        $("#addEditGameModal").modal('hide');
                        $("#addEditGameForm")[0].reset();

                        alert(res.message, 'danger')
                    }
                },
                error: function (request, error) {
                    console.log("Error" + error);
                    alert("Something went wrong, please try again!", 'danger');
                }
            });
        }
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

    $(".model-close-btn").on("click", function() {
        if (typeof validator != "undefined") 
            validator.resetForm();
    });

    //TODO::get game existing data on update
    $(document).on('click', "a.editGame",function( e ) {
        e.preventDefault();
        var gameId = $(this).data('id');
        $(inputGameId).val(gameId);
        $("#addEditGameModalLabel").html("Update Game Detail");
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
                    setReviewStar(res.data.star_rating ? res.data.star_rating : 0);

                    $("#inputReview").val(res.data.review ? res.data.review : '');
                    
                    // var DateTimeVal = res.data.last_played ? moment(res.data.last_played).toDate() : '';
                    var DateTimeVal = res.data.last_played ? new tempusDominus.DateTime(res.data.last_played) : '';
                    
                    datePicker.dates.setValue(tempusDominus.DateTime.convert(DateTimeVal));
                } else {

                }
            },
            error: function (request, error) {
                console.log("Error" + error);
            }
        });
    });
    

    //TODO::delete game
    $(document).on('click', "a.deleteGame",function( e ) {
        var gameId = $(this).data('id');
        if (confirm("Are you sure you want to delete this game?") == true) {
            $.ajax({
                url: "/app/games/deleteGame",
                type: "GET",
                dataType: "json",
                data: {id: gameId},
                success: function(res) {
                    $("#list-load").show();
                    //TODO::update games list
                    // getGameList();

                    //TODO::reload datatable
                    $('#gameListTable').DataTable().ajax.reload();

                    alert(res.message, 'success');
                },
                error: function (request, error) {
                    console.log("Error" + error);
                    alert("Something went wrong, please try again!", 'danger');
                }
            });
        }
    });

    $(document).on('click', "#deleteGames",function( e ) {
        if (confirm("Are you sure you want to delete selected games?") == true) {
            var delete_ids = $.map($('input[name^="ids"]:checked'), function(c){return c.value; });
            $.ajax({
                url: "/app/games/deleteGames",
                type: "POST",
                dataType: "json",
                data: {ids: delete_ids},
                success: function(res) {
                    $("#list-load").show();
                    //TODO::update games list
                    // getGameList();
                    //TODO::reload datatable
                    $('#gameListTable').DataTable().ajax.reload();

                    alert(res.message, 'success');
                },
                error: function (request, error) {
                    console.log("Error" + error);
                    alert("Something went wrong, please try again!", 'danger');
                }
            });
        }
    });
    
    

 });