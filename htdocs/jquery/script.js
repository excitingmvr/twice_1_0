$(document).ready(function(){
    var page = 1;
    var listCount = 10;
    var searchOption = 1;
    var searchContents = "";
    var seq = "";
    var process = "list";

    $('#list_count option[value='+listCount+']').attr('selected','selected');
    
    
    getData(page,listCount);

    $('#list_count').on("change",function(){
        listCount = $(this).val();
        getData(page,listCount);
    });

    $(document).on('click','.paging_bar a',function(e){
        e.preventDefault();
        page = $(this).data("page");
        getData(page,listCount);
    });


    $("#searchBar").on("keyup",function(e){
        searchData();
    })

    function searchData() {
        searchOption = $("#option").val();
        searchContents = $("#contents").val();
        getData(page,listCount);
        return false;
    };

    function getData(page,listCount) {
        $.ajax({
            type: 'get',
            dataType: 'html',
            url: './ajax.html',
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
    };
});