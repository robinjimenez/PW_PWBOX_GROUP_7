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
    $("#file-name").val(file);
}

//Al fer onclick al button de rename
function renameModalData(file) {
    console.log(file);
    $("#renameFileModal").addClass("is-active");
    //Assignem valor al input que està hidden del modal de rename de dash.twig, per enviar-lo a bbdd després
    $("#file-name-for-rename").val(file);
}