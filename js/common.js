    function GetCookieJS(name) {
	var matches = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + '=([^;]*)'));
	return matches ? decodeURIComponent(matches[1]) : undefined;
    };
    function SaveCookiesJS(key,result,days){
	var exdate=new Date();
        exdate.setDate(exdate.getDate() + days);        
        document.cookie=key+"="+result+"; path=/; expires="+exdate.toUTCString();	
	$.cookie(key,result);
    };
$(function() {         
    $.jgrid.extend({
	setColWidth: function (iCol, newWidth, adjustGridWidth) {
	    return this.each(function () {
		var $self = $(this), grid = this.grid, p = this.p, colName, colModel = p.colModel, i, nCol;
		if (typeof iCol === "string") {
		    colName = iCol;
		    for (i = 0, nCol = colModel.length; i < nCol; i++) {
			if (colModel[i].name === colName) {
			    iCol = i;
			    break;
			}
		    }
		    if (i >= nCol) {
			return; 
		    }
		} else if (typeof iCol !== "number") {
		    return;
		}
		grid.resizing = { idx: iCol };
		grid.headers[iCol].newWidth = newWidth;
		grid.newWidth = p.tblwidth + newWidth - grid.headers[iCol].width;
		grid.dragEnd(); 
		if (adjustGridWidth !== false) {
		    $self.jqGrid("setGridWidth", grid.newWidth, false); 
		}
	    });
	}
    });  
    $.jgrid.extend({
	saveCommonParam: function(stname){	    
	    //информация о колонках
	     colarray=$(this).jqGrid('getGridParam','colModel');
	     localStorage.setItem(stname, JSON.stringify(colarray));
	     console.log(JSON.stringify(colarray));	     	     
	},
	loadCommonParam: function(stname){	    	
	    if (localStorage[stname]!=undefined) {
		colarray=localStorage[stname];
		if (colarray!=""){
		    obj_for_load=JSON.parse(colarray);   // загружаем JSON в массив     
		    for (i in obj_for_load) {
			//console.log("name:",obj_for_load[i].name);
			//console.log("width:",obj_for_load[i].width);			
			if  (obj_for_load[i].hidden==true){
			   $(this).hideCol(obj_for_load[i].name);
			} else {
			   $(this).showCol(obj_for_load[i].name);
			   if (obj_for_load[i].fixed==true){
				$(this).setColWidth(obj_for_load[i].name, obj_for_load[i].width);
			   };
			}			
		    };    
		}
	    } else {
		console.log("в локальном хранилище не найден ключ "+stname);
	    };
	}
    });
});