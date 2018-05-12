$(document).ready(function(){
    $("#newFile").click(function(){
        $("#newFileModal").addClass("is-active");
    });
    $(".modal-close").on('click', function(e){
        $(this).addClass("is-active");
    });
    $(".modal-background").on('click', function(e){
        $(this).addClass("is-active");
    });
});