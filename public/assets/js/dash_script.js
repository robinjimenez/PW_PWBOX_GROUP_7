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
/*
    $("#rename").click(function(){
        $("#renameFileModal").addClass("is-active");
        var filename = $(this).parent().parent().parent().parent().attr('id');
        $("#renameFileModal > .filename").innerText(filename);
    });
    */
    $(".modal-close").on('click', function(){
        $(this).parent().removeClass("is-active");
    });
    $(".modal-background").on('click', function(){
        $(this).parent().removeClass("is-active");
    });
    $("button.delete").on('click', function(){
        $(this).parent().addClass("is-hidden");
    });
});

//Al fer onclick al button de share
function shareModalData(file) {
    $("#shareModal").addClass("is-active");
    //Assignem valor al input que està hidden del modal de share de dash.twig, per enviar-lo a bbdd després
    $("#file-name").val(file.id);
}

//Al fer onclick al button de rename
function renameModalData(file) {
    //TODO: Rename action
    $("#renameFileModal").addClass("is-active");
    //Assignem valor al input que està hidden del modal de rename de dash.twig, per enviar-lo a bbdd després
    $("#file-name-for-rename").val(file.id);
}