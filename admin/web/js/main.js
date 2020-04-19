$(function () {
    ModalInit();
    $("textarea").autosize();
});

function ModalInit() {
    $("body").on("click", ".auto-modal", function (e) {
        e.preventDefault();
        var t = $(this).data("href");
        showIframeModel(t), e.preventDefault()
    })
}

function showIframeModel(e) {
    var t = '<div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
    t += '<div class="modal-dialog" style="text-align: center;">';
    t += '<div class="modal-content autoModal" style="margin: auto; background: none; box-shadow:none;border:none;">';
    t += '<div class="modal-body">';
    t += '<iframe class="iframe-modal autoModal" frameborder="0" style="overflow:auto;margin:0;height:100%;width:100%;min-height: 80%;" scrolling="no" src="' + e + '"></iframe>';
    t += "</div>";
    t += "</div>";
    t += "</div>";
    t += "</div>";
    $("#modalSpace").html(t);
    $("#commonModal").modal("show");
}

function closeModal() {
    parent.$('.modal').modal('hide');
    parent.$('#modalSpace').html();
}

function closeModalById(id) {
    $('#' + id).modal('hide');
}

function updateRoleAssign() {
    $(".role-assign").change(function () {
        $.post("/permission/update", {item: $(this).data("name"), role: $(this).data("role")}, function (data) {
            console.log(data);
        });
    });
}

function readURL(e, t) {
    if (e.files && e.files[0]) {
        var o = new FileReader;
        o.onload = function (e) {
            // t.attr("src", e.target.result)
            t.css('background-image', 'url(' + e.target.result + ')')
        }, o.readAsDataURL(e.files[0])
    }
}

function imagePreview() {
    $("body").on("click", ".uploadButton", function (e) {
        uploadBtn = $(this);
        fileInput = uploadBtn.siblings(".form-group").find('input:file');
        fileInput.click();
        fileInput.change(function () {
            readURL(this, uploadBtn.siblings(".imageLink").children(".imageContent"));
        })
    });
}

function insertParamToUrl(key, value) {
    key = encodeURI(key);
    value = encodeURI(value);

    var kvp = document.location.search.substr(1).split('&');

    var i = kvp.length;
    var x;
    while (i--) {
        x = kvp[i].split('=');

        if (x[0] == key) {
            x[1] = value;
            kvp[i] = x.join('=');
            break;
        }
    }

    if (i < 0) {
        kvp[kvp.length] = [key, value].join('=');
    }

    //this will reload the page, it's likely better to store this until finished
    document.location.search = kvp.join('&');
}

function startLoading() {
    $("body").loading('start');
}

function stopLoading() {
    parent.$("body").loading('stop');
    $("body").loading('stop');
}
