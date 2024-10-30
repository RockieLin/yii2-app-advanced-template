function readImgURL(e, t) {
    if (e.files && e.files[0]) {
        var o = new FileReader;
        o.onload = function (e) {
            // t.attr("src", e.target.result)
            t.css('background-image', 'url(' + e.target.result + ')')
        }, o.readAsDataURL(e.files[0])
    }
}
let load = false;
function imageUploadPreview() {
    if(!load){
        $("body").on("click", ".imgUploadButton", function (e) {
            uploadBtn = $(this);
            fileInput = uploadBtn.siblings(".form-group").find('input:file');
            fileInput.click();
            fileInput.change(function () {
                readImgURL(this, uploadBtn.siblings(".imageLink").children(".imageContent"));
            })
        });
        load = true;
    }
}