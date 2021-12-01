$( window ).on( "load", function() {

});
let itemBank=[];
$( document ).ready(function() {
    //detail page lister
    //değiştirilecek tamamiyle tekil listelemeyi tamamlamak için şimdilik böyle yapıyoruz framework.js,homephp,functions.php sayfalarında değişiklik olacaktur
    if ($("#pageunique").length>0) {
        let page = $("#pageunique").attr("data-page");
        let pageunique = $("#pageunique").val();
        let me = $(".detail-area[data-table='"+page+"']");
        let data = getRow(page,pageunique);
        detailAreaSaver(page);
        lister(me,data,page);
        uiFunc[page](page);
    }
    //detail pagelister end
    $( ".data-layer" ).each(function( index ) {
        let me = $( this );
        let table = me.attr("data-table");
        let datas = jsonDatas[table];
        lister(me,datas);
    });
    $(".detail").each(function (i) {
        let me = $(this);
        let params = getDivParams(me);
        let uniqe,type,event,table;
        if (params!=false){
            uniqe = params[0];
            event = params[1];
            table = params[2];
            type = params[2];
        }else{
            uniqe = me.attr("data-uniqe");
            type = me.attr("data-type");
            event = me.attr("data-event");
            table = me.attr("data-table");
        }
        detailAreaSaver(table);
        $(document).on(event,".detail:eq("+i+")",function() {
            //type == "reload"? window.location = uniqe:"";
            type=="reload" && redirect(uniqe);
            notReload(table,uniqe);
        });
    });
    /*$(".sub-str").each(function () {
        let me = $( this );
        let strend = me.attr("data-strend");
        let target = me.attr("data-taget");
        let val = "";
        if (isUndefined(target)){
            val = me.html();
        }else{
            val = me.attr(target);
        }
        strend = strend.split("-");
        let newValue = val.substring(parseInt(strend[0]),parseInt(strend[1]));
        if (isUndefined(target)){
            me.html(newValue);
        }else{
            me.attr(target,newValue);
        }
    });*/
});
function detailAreaSaver(table) {
    let area = $(".detail-area[data-table='"+table+"']");
    area.each(function (j) {
        let itemKey = table+j;
        if (!(itemKey in itemBank)){
            itemBank[table+j] = area.eq(j).html();
        }
    });
}
function getDivParams(div) {
    let params = div.attr("data-params");
    if (isUndefined(params)){
        return false;
    }else{
        params = params.substring(1);
        params = params.slice(0, -1);
        params = params.split("','");
        return params;
    }
}
function lister(div,datas,table="") {
    div.each(function (j) {
        let item;
        if(table != ""){
            item = itemBank[table+j];
        }else{
            item = div.eq(j).html();
        }
        div.eq(j).html("");
        $.each(datas,function (i) {
            let columns = (Object.keys(datas[0]));
            let changeItem = item;
            $.each(columns,function (j) {
                let column = columns[j];
                changeItem = helperFuncsDetectAndReplaceAll(changeItem,column,datas[i][column]);
            });
            div.eq(j).append(changeItem);
        });
    });
}
let uiFunc=[];
function afterDetail(table,func) {
    uiFunc[table] = function (tableUI) {
        if(table == tableUI){
            func();
        }
    }
}
function notReload(table,uniqe) {
    let data = getRow(table,uniqe);
    let me = $(".detail-area[data-table='"+table+"']");
    lister(me,data,table);
    //url title description changer ||| front end project development
    uiFunc[table](table);
}
function getRow(table,uniqe) {
    if (strFind(table,"#")){
        let tableSplit = table.split("#");
        table = tableSplit[0];
    }
    let datas = jsonDatas[table];
    let row;
    $.each(datas,function (i) {
        let columns = (Object.keys(datas[0]));
        $.each(columns,function (j) {
            let column = columns[j];
            let value = datas[i][column];
            if ($.trim(value) == $.trim(uniqe)){
                row = {0:datas[i]};
                return false;
            }
        });
    });
    return row;
}
function redirect(link) {
    window.location = link;0
}
function helperFuncsDetectAndReplaceAll(itemHtml,column,data) {
    let colCount = strFindCount(itemHtml,"{"+column);
    emptyLoop(colCount,function () {
        let find = "{"+column+"}";
        if (strFind(itemHtml,find)){
            itemHtml = itemHtml.replaceAll(find,data);
        }else{
            let helpers = ["/","!"];// "/"=>substring
            let separators = ["-",","];
            $.each(helpers,function (i) {
                let newFind = "{"+column+helpers[i];
                if (strFind(itemHtml,newFind)){
                    let newText = itemHtml.split(newFind);
                    newText = newText[1].split("}");
                    let parameters = newText[0];
                    let newData = setHelpFunctionData(helpers[i],parameters,separators[i],data);
                    itemHtml = itemHtml.replaceAll(newFind+parameters+"}",newData);
                }
            });
        }
    });
    return itemHtml;
}
function emptyLoop(loopCount,func) {
    let i = 0;
    while (i<loopCount){
        func();
        i++;
    }
}
function setHelpFunctionData(helper,params,separator,data) {
    params = params.split(separator);
    let reData;
    switch(helper) {
        case "/":
            if(isUndefined(params[1])){
                params[1] = params[0];
                params[0] = 0;
            }
            reData = data.substring(parseInt(params[0]),parseInt(params[1]));
            return reData;
            break;
        case "!":
            return "the test data";
            break;
        default:
            return data;
    }
}
function strFind(text,find) {
    if (text.indexOf(find)>=0){
        return true;
    }else{
        return false
    }
}
function strFindCount(text,find) {
    let textLength = text.length;
    let findLength = find.length;
    let replaceLength = text.replaceAll(find,"").length;
    return (textLength-replaceLength)/findLength;
}
function cnslMsg(msg) {
    console.log(msg);
}
/*function isNull(value) {
    this not working a problem detect
    if (!((value == undefined)||(value=="")||(value==null))){
        return true;
    }
}*/
function isUndefined(value) {
    if (value == undefined){
        return true;
    }else{
        return false;
    }
}
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}