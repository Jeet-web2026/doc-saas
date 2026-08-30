$(document).ready(function () {
    $(document).on("click", ".x", function () {
        $(this).closest(".message-body").remove();
    });
});
