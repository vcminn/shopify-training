changeActive();
function changeActive() {
    $('.active').each(function (e) {
        if ($(this).val() == 1) {
            $(this).attr("checked", "checked");
        } else if ($(this).val() == 0) {
            $(this).attr("checked", false);
        }
    });
}

function changeState(clicked_id, checked) {
    console.log(checked);
    if (checked === true) {
        var value = 1;
    } else {
        var value = 0;
    }
    $.ajax({
        url: "/change-state",
        method: "GET",
        data: {value: value, id: clicked_id},
        success: function () {
            sync();
        }
    });
}

function sync() {
    $.ajax({
        url: "/sync",
        method: "GET",
        success: function () {
            alert('synced');
            location.reload();
        }
    });
}
