(function(){
    ShowprintMamoria=function(button,URL){
        var RoleId=$(button).data('entry')
        var type=$(button).data('type')
        var ModalId='ShowprintMamoria';
        var modelkey=$(button).data('model-key');
        createModal(button,ModalId);
        sendSecRequestToGetAll(URL,{type,modelkey,RoleId},ModalId);
    }
    ShowprintMamoriaList=(button,URL)=>{
        if (typeof Amer.checkedItems === 'undefined' || Amer.checkedItems.length == 0)
            {
                new Noty({
                type: "warning",
                text: "<strong>{!! trans('AMER::actions.bulk_no_entries_selected_title') !!}</strong><br>{!! trans('AMER::actions.bulk_no_entries_selected_message') !!}"
              }).show();
  
                return;
            }
            var RoleId=Amer.checkedItems;
            var type=$(button).data('type');
            var ModalId='ShowprintMamoria';
            var modelkey=$(button).data('model-key');
            createModal(button,ModalId);
            sendSecRequestToGetAll(URL,{type,modelkey,RoleId},ModalId);
    }
    checkNOdeTI=(button)=>{
        var np=$(button).parent().parent().parent().parent();
        var np=np[0];
        return np.nodeName
    }
    createModal=function(button,id){
        var np=checkNOdeTI(button);
        if(np == 'TD'){
            fst=$(button).parent().parent().parent().parent().parent().children()[0];
            fst=$(fst).children()[1];
            fst=$(fst).html();
            fst=fst.toString().replace(/^\s+|\s+$/g, "");
        }else if(np == 'DIV'){
            fst=$(button).parent().parent().parent().parent().parent().parent().parent().children()[0];
            fst=$(fst).children()[0];
            fst=$(fst).children()[1];
            fst=$(fst).html();
            fst=fst.toString().replace(/^\s+|\s+$/g, "");
        }
        
        
        //console.log(fst);
        
        var myModal=$(`<div class="modal fade" id="` + id + `Modal" tabindex="-1" aria-labelledby="`+id+`ModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-fullscreen">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="`+id+`ModalLabel">`+fst+`</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>`);
        //lunch show modal
        if($(`#`+id+`Modal`).length == 0){$('body').append(myModal);}
        var myModalEl = document.querySelector(`#`+id+`Modal`)
        var modal = bootstrap.Modal.getOrCreateInstance(myModalEl) // Returns a Bootstrap modal instance
        modal.show()
    }
    sendSecRequestToGetAll=function (URL,DATA,ModalId){
        var $rowcontainer=$(`#${ModalId}Modal`).find($('.modal-body'));
        jQuery.ajax({
            url:URL,
            language:window.Amer.Language,
            dir:window.Amer.dir,
            dataType:'json',
            crossDomain:true,
            contentType:"application/x-www-form-urlencoded; charset=UTF-8",
            data:DATA,
            type:'post',
            converters :{"* text": window.String, "text html": true, "text json": jQuery.parseJSON, "text xml": jQuery.parseXML},
            beforeSend: function() {
                loader_div();
                var bold=$('#loader').clone();
                $($rowcontainer).html($(bold));
            },
            complete: function() {
                remove_loader_div();
            },
        }).done(function(data){
            $($rowcontainer).html('');
            data=data.data;
            var st=data.split(';\r\n');
            var st=st[2].split('\r\n\r\n')
            iframe= document.createElement('iframe');
            $(iframe).attr('style','top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;');
            $($rowcontainer).html(iframe);
            iframe.src="data:application/pdf;base64,"+st[1];
        })
        ;
    }
})(jQuery)
