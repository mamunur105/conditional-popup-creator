(function($){
  
    // Move modals var to global scope
    var modals = [];
    var exitmodal = [];
    var popupsdisplayed = false;

    $(document).ready(function() {
        
        var modalelement = document.querySelectorAll('.data-modal');
        for (var index = 0; index < modalelement.length; index++) {
            var datacontant =  modalelement[index].outerHTML;
            var dataexit =  modalelement[index].getAttribute('data-exit');
            var delay =  modalelement[index].getAttribute('data-delay');
            var datatitle =  modalelement[index].getAttribute('data-title');
            var delaymillisecond = delay * 1000;

    
            if(dataexit == 'exit'){
                delaymillisecond = 0;
            }
            // console.log(delayinmilisec);
            modals[index] = new jBox('Modal', {
                closeButton: false, // Remove close button
                closeOnClick: false, // Remove close on click events
                delayOpen: delaymillisecond,
                closeOnEsc: false, // Remove close on escape key events
                title: '<div class="data-title">'+datatitle+'</div>',
                minWidth: 600,
                maxWidth:800,
                // maxHeight:700,
                content: datacontant,
                onCreated: function () {
                    this.content.find('.close-modal').on('click', function() {
                    this.close();
                    }.bind(this));
                }
                // open:funjction

            });
            if(dataexit == 'pageload'){

                modals[index].open();
                
            }else{
                exitmodal.push(modals[index]);
                // console.log(exitmodal.length);
              
            }
        } 
        if (exitmodal.length > 0 && !popupsdisplayed) {
            window.onbeforeunload = function() {      
                if(!popupsdisplayed){
                    for (i in exitmodal) {
                        exitmodal[i].open();//
                    }
                    popupsdisplayed = true;
                    return true;
                } 
                return true;   
            }
            
            
        }

        
        // onload
    






    });
    










})(jQuery);

