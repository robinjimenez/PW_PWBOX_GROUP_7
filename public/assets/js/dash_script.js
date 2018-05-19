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
        var file = $("#name").text();
        $("#file-name").val(file);
    });
    $("#rename").click(function(){
        $("#renameFileModal").addClass("is-active");
        var filename = $(this).parent().parent().parent().parent().attr('id');
        $("#renameFileModal > .filename").innerText(filename);
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