$(document).ready(function(){
    function getPaging() {
        $.ajax({
            type : 'get',
            dataType: 'json',
            url: './read_paging.php',
            data: {page:page, list:listCount, searchOption:searchOption, searchContents:searchContents, seq:seq, pocess:process},
            success: function (data) {
                console.log(data);
                $("body").find("#list_wrap").remove();
                $("body").append(data);
            },
            error: function (request, status, error) {
                console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
            }
        });
    }
});