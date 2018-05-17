var file = document.getElementById("file");
file.onchange = function(){
    if(file.files.length == 1){
        document.getElementById('filename').innerHTML = file.files[0].name;
    } else if (file.files.length > 1) {
        document.getElementById('filename').innerHTML = "multiple files";
    }
};

function close() {
    $(this).parent.removeClass("is-active");
}

$(document).ready(function(){
    $("#newFile").click(function(){
        $("#newFileModal").addClass("is-active");
    });
    $("#newFolder").click(function(){
        $("#newFolderModal").addClass("is-active");
    });
    $("#share").click(function(){
        $("#shareModal").addClass("is-active");
    });
    $("#rename").click(function(){
        $("#renameFileModal").addClass("is-active");
    });
    $(".modal-close").on('click', function(){
        $(this).parent().removeClass("is-active");
    });
    $(".modal-background").on('click', function(){
        $(this).parent().removeClass("is-active");
    });
});