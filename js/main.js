$(document).ready(function(){
    var menuActive = false,
        touched = false,
        $nav = $('nav');

    function removeActive(callback){
        $nav.find('.menuactive').removeClass('menuactive');
        callback();
    }

    function newActive($this,menu){
        removeActive(function(){
            $this.addClass('menuactive').queue(function(){
                if(menu){
                    menuActive = true;
                    touched = false;
                } else {
                    touched = true;
                }
            }).dequeue();
        });
    }

    $nav.on({
        touchstart:function(e){
            e.stopPropagation();
            newActive($(this),touched);
        },
        mouseenter:function(){
            newActive($(this),true);
        },
        click:function(e){
            e.preventDefault();

            if(menuActive){
                $(this).trigger('trueClick',e.target);
            }
        },
        trueClick:function(e,$target){
            $(this).parents('.nav').trigger('mouseleave');
            window.location.href = $target;
        }
    },'.haschildren').on('mouseleave',function(){
        removeActive(function(){
            menuActive = false;
        });
    });

    $('html').on('touchstart',function(e){
        if(menuActive){
            $nav.trigger('mouseleave');
        }
    });
});
