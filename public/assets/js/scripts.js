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

var file = document.getElementById("file");
file.onchange = function(){
    if(file.files.length == 1){
        document.getElementById('filename').innerHTML = file.files[0].name;
    } else if (file.files.length > 1) {
        document.getElementById('filename').innerHTML = "multiple files";
    }
};