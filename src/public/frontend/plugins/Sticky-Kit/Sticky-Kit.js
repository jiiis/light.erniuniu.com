(function(){var g,s;g=this.jQuery;s=g(window);g.fn.stick_in_parent=function(e){var t,c,p,A,k,B;null==e&&(e={});p=e.sticky_class;t=e.inner_scrolling;c=e.parent;null==c&&(c=void 0);null==t&&(t=!0);null==p&&(p="is_stuck");A=function(a,e,k,u,x,l){var n,q,f,v,b,d,y,z,r,w;d=a.parent();null!=c&&(d=d.closest(c));if(!d.length)throw"failed to find stick parent";y=function(){var b,f;b=parseInt(d.css("border-top-width"),10);f=parseInt(d.css("padding-top"),10);e=parseInt(d.css("padding-bottom"),10);k=d.offset().top+
b+f;u=d.height();x=a.offset().top-parseInt(a.css("margin-top"),10);return l=a.outerHeight(!0)};y();if(l!==u)return f=a.css("float"),r=g("<div />").css({width:a.outerWidth(!0),height:l,display:a.css("display"),"float":f}),n=q=!1,v=void 0,b=0,z=!1,w=function(){var h,g,m,c;m=s.scrollTop();null!=v&&(g=m-v);v=m;q?(c=m+l+b>u+k,n&&!c&&(n=!1,a.css({position:"fixed",bottom:"",top:0}).trigger("sticky_kit:unbottom")),m<x&&(q=!1,b=0,"left"!==f&&"right"!==f||a.insertAfter(r),r.detach(),h={position:""},z&&(h.width=
""),a.css(h).removeClass(p).trigger("sticky_kit:unstick")),t&&(h=s.height(),l>h&&!n&&(b-=g,b=Math.max(h-l,b),b=Math.min(0,b),a.css({top:b+"px"})))):m>x&&(q=!0,h={position:"fixed",top:b},"none"===f&&"block"===a.css("display")&&(h.width=a.width()+"px",z=!0),a.css(h).addClass(p).after(r),"left"!==f&&"right"!==f||r.append(a),a.trigger("sticky_kit:stick"));if(q&&(null==c&&(c=m+l+b>u+k),!n&&c))return n=!0,"static"===d.css("position")&&d.css({position:"relative"}),a.css({position:"absolute",bottom:e,top:""}).trigger("sticky_kit:bottom")},
s.on("scroll",w),setTimeout(w,0),g(document.body).on("sticky_kit:recalc",function(){y();return w()})};k=0;for(B=this.length;k<B;k++)e=this[k],A(g(e));return this}}).call(this);