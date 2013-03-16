
/**
 * nmcDropDown plugin - v1.0.3
 * Author: Eli Van Zoeren
 * Copyright (c) 2009 New Media Campaigns
 * http://www.newmediacampaigns.com 
 **/
(function(a) {
    a.fn.nmcDropDown=function(b){
        var c=a.extend({},a.fn.nmcDropDown.defaults,b);
        return this.each(function(){
            menu=a(this);
            submenus=menu.children("li:has("+c.submenu_selector+")");
            if(c.fix_IE){
                menu.css("z-index",51)
                    .parents().each(function(d){
                        if(a(this).css("position")=="relative"){
                            a(this).css("z-index",(d+52))
                        }
                    });
                submenus.children(c.submenu_selector).css("z-index",50)
            }
            over=function(){
                a(this).addClass(c.active_class).children(c.submenu_selector).show("clip",{},c.show_speed);//.animate(c.show,c.show_speed);
//                a(this).addClass(c.active_class)
                return false
            };
            out=function(){
                a(this).removeClass(c.active_class).children(c.submenu_selector).hide("clip",{},c.show_speed);//.animate(c.hide,c.hide_speed);
//                a(this).removeClass(c.active_class);
                return false
            };
            if(c.trigger=="click"){
                submenus
                    .toggle(over,out)
                .children(c.submenu_selector).hide()
            } else {
                if(a().hoverIntent){
                    submenus.hoverIntent({
                        interval:c.show_delay,
                        over:over,
                        timeout:c.hide_delay,
                        out:out
                    })
                    .children(c.submenu_selector).hide()
                }
                else{
                    submenus
                        .hover(over,out)
                    .children(c.submenu_selector).hide()
                }
            }
        }
    )};
    a.fn.nmcDropDown.defaults={
        trigger:"hover",
        active_class:"open",
        submenu_selector:"ul",
        show:{opacity:"show"},
        show_speed:100,
        show_delay:0,
        hide:{opacity:"hide"},
        hide_speed:50,
        hide_delay:0,
        fix_IE:true
    }
})(jQuery);

