(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-7aa94354"],{"02bc":function(t,e,a){"use strict";var n=function(){var t=this,e=t.$createElement;t._self._c;return t._m(0)},o=[function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("div",{staticClass:"empty"},[n("img",{attrs:{src:a("f3f6")}})])])}],c={name:"Empty",props:{}},s=c,i=a("9ca4"),l=Object(i["a"])(s,n,o,!1,null,null,null);e["a"]=l.exports},"5a56":function(t,e,a){"use strict";var n=a("698d"),o=a.n(n);o.a},"688d":function(t,e,a){"use strict";var n=a("23c4"),o=a.n(n),c=a("d601"),s=a("35ea"),i=a("2a47"),l=a("f449"),r=a("721a"),u=Object(s["a"])("search"),d=u[0],f=u[1],v=u[2];function b(t,e,a,n){function s(){if(a.label||e.label)return t("div",{class:f("label")},[a.label?a.label():e.label])}function u(){if(e.showAction)return t("div",{class:f("action"),attrs:{role:"button",tabindex:"0"},on:{click:o}},[a.action?a.action():e.actionText||v("cancel")]);function o(){a.action||(Object(i["a"])(n,"input",""),Object(i["a"])(n,"cancel"))}}var d={attrs:n.data.attrs,on:Object(c["a"])({},n.listeners,{keypress:function(t){13===t.keyCode&&(Object(l["c"])(t),Object(i["a"])(n,"search",e.value)),Object(i["a"])(n,"keypress",t)}})},b=Object(i["b"])(n);return b.attrs=void 0,t("div",o()([{class:f({"show-action":e.showAction}),style:{background:e.background}},b]),[null==a.left?void 0:a.left(),t("div",{class:f("content",e.shape)},[s(),t(r["a"],o()([{attrs:{type:"search",border:!1,value:e.value,leftIcon:e.leftIcon,rightIcon:e.rightIcon,clearable:e.clearable,clearTrigger:e.clearTrigger},scopedSlots:{"left-icon":a["left-icon"],"right-icon":a["right-icon"]}},d]))]),u()])}b.props={value:String,label:String,rightIcon:String,actionText:String,background:String,showAction:Boolean,clearTrigger:String,shape:{type:String,default:"square"},clearable:{type:Boolean,default:!0},leftIcon:{type:String,default:"search"}},e["a"]=d(b)},"698d":function(t,e,a){},"8ef2":function(t,e,a){"use strict";a("7c36"),a("e47e"),a("8077"),a("9a3a"),a("8866"),a("cb92")},a6bd:function(t,e,a){},cb92:function(t,e,a){},ed68:function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[null==t.list?a("Loadings"):a("div",{staticClass:"container scontainer"},[a("div",{staticClass:"search-bar"},[a("van-search",{attrs:{"show-action":"",label:"查询",placeholder:"请输入搜索关键词",clearable:""},on:{search:t.searchData},scopedSlots:t._u([{key:"action",fn:function(){return[a("div",{on:{click:t.searchData}},[t._v("搜索")])]},proxy:!0}]),model:{value:t.keywords,callback:function(e){t.keywords=e},expression:"keywords"}})],1),a("div",{staticClass:"setting",on:{click:t.openSetting}},[a("van-icon",{attrs:{color:"gray",size:"20",name:"setting-o"}})],1),a("section",{staticClass:"query"},[0==t.list.length?a("Empty"):a("van-list",{staticClass:"list",attrs:{finished:t.finished,"finished-text":"没有更多了"},on:{load:t.onLoad},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},t._l(t.list,(function(e,n){return a("router-link",{key:n,staticClass:"item",attrs:{to:{path:"/InvoiceDetails/"+e.oid}}},[a("div",{staticClass:"flex-sb"},[a("div",{staticClass:"info"},[a("div",{staticClass:"flex-sb"},[a("div",[t._v("订单号: "+t._s(e.order_no))]),a("div",{staticStyle:{"text-align":"right","font-size":"12px",color:"#333",position:"relative",right:"-30px"}},[t._v("\n                  "+t._s(0==e.status?"未完成":"已完成")+"\n                ")])]),a("div",[t._v("客户: "+t._s(e.user_name))]),a("div",{staticClass:"flex-sa"},[a("div",[t._v("材质名称: "+t._s(e.name))]),a("div",[t._v("材质: "+t._s(e.material))])]),a("div",{staticClass:"flex-sa"},[a("div",[t._v("数量: "+t._s(e.number))]),a("div",[t._v("尺寸长: "+t._s(e.length))]),a("div",[t._v("尺寸宽: "+t._s(e.width))])]),a("div",[t._v("备注: "+t._s(e.remark))]),a("div",[t._v("日期: "+t._s(t.formatDate(e.add_time)))])]),a("van-icon",{attrs:{name:"arrow",color:"#ccc"}})],1)])})),1)],1)]),a("van-action-sheet",{attrs:{actions:t.actions},on:{select:t.onSelect},model:{value:t.show,callback:function(e){t.show=e},expression:"show"}})],1)},o=[],c=(a("2b45"),a("9a33"),a("f548"),a("ce3c")),s=(a("7c36"),a("80b9"),a("e47e"),a("8077"),a("34fd"),a("df3f"),a("a6bd"),a("d601")),i=a("23c4"),l=a.n(i),r=a("35ea"),u=a("2a47"),d=a("6421"),f=a("d470"),v=a("88a6"),b=a("43c9"),p=Object(r["a"])("action-sheet"),h=p[0],g=p[1];function m(t,e,a,n){var o=e.title,c=e.cancelText,s=e.closeable;function i(){Object(u["a"])(n,"input",!1),Object(u["a"])(n,"cancel")}function r(){if(o)return t("div",{class:g("header")},[o,s&&t(f["a"],{attrs:{name:e.closeIcon},class:g("close"),on:{click:i}})])}function d(){if(a.default)return t("div",{class:g("content")},[a.default()])}function p(a,o){var c=a.disabled,s=a.loading,i=a.callback;function l(t){t.stopPropagation(),c||s||(i&&i(a),Object(u["a"])(n,"select",a,o),e.closeOnClickAction&&Object(u["a"])(n,"input",!1))}function r(){return s?t(b["a"],{class:g("loading-icon")}):[t("span",{class:g("name")},[a.name]),a.subname&&t("div",{class:g("subname")},[a.subname])]}return t("button",{attrs:{type:"button"},class:[g("item",{disabled:c,loading:s}),a.className],style:{color:a.color},on:{click:l}},[r()])}function h(){if(c)return[t("div",{class:g("gap")}),t("button",{attrs:{type:"button"},class:g("cancel"),on:{click:i}},[c])]}function m(){var n=(null==a.description?void 0:a.description())||e.description;if(n)return t("div",{class:g("description")},[n])}return t(v["a"],l()([{class:g(),attrs:{position:"bottom",round:e.round,value:e.value,overlay:e.overlay,duration:e.duration,lazyRender:e.lazyRender,lockScroll:e.lockScroll,getContainer:e.getContainer,closeOnPopstate:e.closeOnPopstate,closeOnClickOverlay:e.closeOnClickOverlay,safeAreaInsetBottom:e.safeAreaInsetBottom}},Object(u["b"])(n,!0)]),[r(),m(),e.actions&&e.actions.map(p),d(),h()])}m.props=Object(s["a"])({},d["b"],{title:String,actions:Array,duration:[Number,String],cancelText:String,description:String,getContainer:[String,Function],closeOnPopstate:Boolean,closeOnClickAction:Boolean,round:{type:Boolean,default:!0},closeable:{type:Boolean,default:!0},closeIcon:{type:String,default:"cross"},safeAreaInsetBottom:{type:Boolean,default:!0},overlay:{type:Boolean,default:!0},closeOnClickOverlay:{type:Boolean,default:!0}});var y,k=h(m),O=(a("733f"),a("cc57"),a("8ef2"),a("688d")),_=a("e876"),w=a("02bc"),j=a("c246"),S=a("89af"),C={name:"DataCenter",components:(y={},Object(c["a"])(y,O["a"].name,O["a"]),Object(c["a"])(y,f["a"].name,f["a"]),Object(c["a"])(y,"Loadings",S["a"]),Object(c["a"])(y,"Empty",w["a"]),Object(c["a"])(y,k.name,k),y),props:{},data:function(){return{list:null,show:!1,actions:[{name:"修改密码"},{name:"系统刷新"},{name:"退出登录"}]}},created:function(){var t=this;Object(_["h"])().then((function(e){t.list=e.data.list}))},mounted:function(){},methods:{onSelect:function(t){"修改密码"==t.name?(this.show=!1,this.$router.replace({path:"/change_password"})):"系统刷新"==t.name?(this.show=!1,j["a"].remove("ntoken"),this.$dialog.message("清除成功")):(this.show=!1,j["a"].remove("ntoken"),this.$router.replace({path:"/"}))},openSetting:function(){this.show=!0},formatDate:function(t){var e=new Date(1e3*t),a=e.getFullYear(),n=e.getMonth()+1;n=n<10?"0"+n:n;var o=e.getDate();return o=o<10?"0"+o:o,a+"-"+n+"-"+o+" "},toThousands:function(t){var e=[],a=0;t=(t||0).toString().split("");for(var n=t.length-1;n>=0;n--)a++,e.unshift(t[n]),a%3||0==n||e.unshift(",");return e.join("")}}},x=C,B=(a("5a56"),a("9ca4")),I=Object(B["a"])(x,n,o,!1,null,"6c9a1b95",null);e["default"]=I.exports},f3f6:function(t,e,a){t.exports=a.p+"h5/img/empty.f65bdbe1.png"}}]);
//# sourceMappingURL=chunk-7aa94354.9506f3e7.js.map