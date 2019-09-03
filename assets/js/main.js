(function($){
  
    // Move modals var to global scope
    var modals = [];
    var exitmodal = [];
    var popupsdisplayed = false;
    var popups_time_srart = false ;

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

            modals[index] = new jBox('Modal', {
                closeButton: false, // Remove close button
                closeOnClick: false, // Remove close on click events
                delayOpen: delaymillisecond,
                closeOnEsc: false, // Remove close on escape key events
                title: '<div class="data-title">'+datatitle+'</div>',
                minWidth: 600,
                maxWidth:800,
                content: datacontant,
                onCreated: function () {
                    this.content.find('.close-modal').on('click', function() {
                    this.close();
                    }.bind(this));
                }

            });
            if(dataexit == 'pageload'){
                modals[index].open();
            }else{
                exitmodal.push(modals[index]);
 
            }
        } 

        setTimeout(function(){   
            popups_time_srart = true ;
        }, 3000);

        var mouseY = 0;
        var topValue = 0;
        window.addEventListener("mouseout",function(e){
            mouseY = e.clientY;
            if(mouseY<topValue && popups_time_srart == true) {
                if(!popupsdisplayed){
                    for (i in exitmodal) {
                        exitmodal[i].open();//
                    }
                    popupsdisplayed = true;
                } 
            }
        },false);

    });
    
})(jQuery);

