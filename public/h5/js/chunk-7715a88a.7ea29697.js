(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-7715a88a"],{"02bc":function(t,e,a){"use strict";var n=function(){var t=this,e=t.$createElement;t._self._c;return t._m(0)},i=[function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("div",{staticClass:"empty"},[n("img",{attrs:{src:a("f3f6")}})])])}],o={name:"Empty",props:{}},s=o,c=a("c701"),r=Object(c["a"])(s,n,i,!1,null,null,null);e["a"]=r.exports},"2e2f":function(t,e,a){"use strict";var n=a("4c02"),i=a.n(n),o=a("b65a"),s=a("30a7"),c=a("1db3"),r=a("f117"),l=a("863f"),d=Object(s["a"])("search"),u=d[0],h=d[1],f=d[2];function v(t,e,a,n){function s(){if(a.label||e.label)return t("div",{class:h("label")},[a.label?a.label():e.label])}function d(){if(e.showAction)return t("div",{class:h("action"),attrs:{role:"button",tabindex:"0"},on:{click:i}},[a.action?a.action():e.actionText||f("cancel")]);function i(){a.action||(Object(c["a"])(n,"input",""),Object(c["a"])(n,"cancel"))}}var u={attrs:n.data.attrs,on:Object(o["a"])({},n.listeners,{keypress:function(t){13===t.keyCode&&(Object(r["c"])(t),Object(c["a"])(n,"search",e.value)),Object(c["a"])(n,"keypress",t)}})},v=Object(c["b"])(n);return v.attrs=void 0,t("div",i()([{class:h({"show-action":e.showAction}),style:{background:e.background}},v]),[null==a.left?void 0:a.left(),t("div",{class:h("content",e.shape)},[s(),t(l["a"],i()([{attrs:{type:"search",border:!1,value:e.value,leftIcon:e.leftIcon,rightIcon:e.rightIcon,clearable:e.clearable,clearTrigger:e.clearTrigger},scopedSlots:{"left-icon":a["left-icon"],"right-icon":a["right-icon"]}},u]))]),d()])}v.props={value:String,label:String,rightIcon:String,actionText:String,background:String,showAction:Boolean,clearTrigger:String,shape:{type:String,default:"square"},clearable:{type:Boolean,default:!0},leftIcon:{type:String,default:"search"}},e["a"]=u(v)},"48e6":function(t,e,a){},"7c8e":function(t,e,a){"use strict";var n=a("30a7"),i=a("e7f1"),o=a("2172"),s=a("be75"),c=a("4ab1"),r=Object(n["a"])("list"),l=r[0],d=r[1],u=r[2];e["a"]=l({mixins:[Object(s["a"])((function(t){this.scroller||(this.scroller=Object(o["c"])(this.$el)),t(this.scroller,"scroll",this.check)}))],model:{prop:"loading"},props:{error:Boolean,loading:Boolean,finished:Boolean,errorText:String,loadingText:String,finishedText:String,immediateCheck:{type:Boolean,default:!0},offset:{type:[Number,String],default:300},direction:{type:String,default:"down"}},data:function(){return{innerLoading:this.loading}},updated:function(){this.innerLoading=this.loading},mounted:function(){this.immediateCheck&&this.check()},watch:{loading:"check",finished:"check"},methods:{check:function(){var t=this;this.$nextTick((function(){if(!(t.innerLoading||t.finished||t.error)){var e,a=t.$el,n=t.scroller,o=t.offset,s=t.direction;e=n.getBoundingClientRect?n.getBoundingClientRect():{top:0,bottom:n.innerHeight};var c=e.bottom-e.top;if(!c||Object(i["a"])(a))return!1;var r=!1,l=t.$refs.placeholder.getBoundingClientRect();r="up"===s?e.top-l.top<=o:l.bottom-e.bottom<=o,r&&(t.innerLoading=!0,t.$emit("input",!0),t.$emit("load"))}}))},clickErrorText:function(){this.$emit("update:error",!1),this.check()},genLoading:function(){var t=this.$createElement;if(this.innerLoading&&!this.finished)return t("div",{key:"loading",class:d("loading")},[this.slots("loading")||t(c["a"],{attrs:{size:"16"}},[this.loadingText||u("loading")])])},genFinishedText:function(){var t=this.$createElement;if(this.finished){var e=this.slots("finished")||this.finishedText;if(e)return t("div",{class:d("finished-text")},[e])}},genErrorText:function(){var t=this.$createElement;if(this.error){var e=this.slots("error")||this.errorText;if(e)return t("div",{on:{click:this.clickErrorText},class:d("error-text")},[e])}}},render:function(){var t=arguments[0],e=t("div",{ref:"placeholder",key:"placeholder",class:d("placeholder")});return t("div",{class:d(),attrs:{role:"feed","aria-busy":this.innerLoading}},["down"===this.direction?this.slots():e,this.genLoading(),this.genFinishedText(),this.genErrorText(),"up"===this.direction?this.slots():e])}})},"89de":function(t,e,a){"use strict";a("35f8"),a("238b"),a("d73d")},c31e:function(t,e,a){},d73d:function(t,e,a){},dede:function(t,e,a){"use strict";a("c31e")},e968:function(t,e,a){},ed68:function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[null==t.list?a("Loadings"):a("div",{staticClass:"container scontainer"},[a("div",{staticClass:"search-bar flex-sb"},[a("van-dropdown-menu",{attrs:{"active-color":"#515151"}},[a("van-dropdown-item",{attrs:{options:t.monthList},on:{change:t.selectOption},model:{value:t.nowMonth,callback:function(e){t.nowMonth=e},expression:"nowMonth"}})],1),a("van-dropdown-menu",{attrs:{"active-color":"#515151"}},[a("van-dropdown-item",{attrs:{options:t.monthStatus},on:{change:t.selectStatus},model:{value:t.nowStatus,callback:function(e){t.nowStatus=e},expression:"nowStatus"}})],1),a("div",{staticClass:"search"},[a("van-search",{attrs:{"show-action":"",label:"查询",placeholder:"请输入搜索关键词",clearable:""},on:{search:t.searchData},scopedSlots:t._u([{key:"action",fn:function(){return[a("div",{on:{click:t.searchData}},[t._v("搜索")])]},proxy:!0}]),model:{value:t.keywords,callback:function(e){t.keywords=e},expression:"keywords"}})],1)],1),a("div",{staticClass:"setting"},[a("van-icon",{attrs:{color:"gray",size:"20",name:"setting-o"},on:{click:t.openSetting}})],1),a("section",{staticClass:"query"},[0==t.list.length?a("Empty"):a("van-list",{staticClass:"list",attrs:{finished:t.finished,"finished-text":"没有更多了"},on:{load:t.onLoad},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},t._l(t.list,(function(e,n){return a("router-link",{key:n,staticClass:"item",attrs:{to:{path:"/InvoiceDetails/"+e.oid}}},[a("div",{staticClass:"flex-sb"},[a("div",{staticClass:"info"},[a("div",{staticClass:"flex-sb h2"},[a("div",[t._v("订单号: "+t._s(e.order_no))]),a("div",{staticStyle:{"text-align":"right","font-size":"12px",color:"#333",position:"relative",width:"30%"}},[t._v("\n\n                  "+t._s(1==e.status?"待加工":2==e.status?"加工中":"已完成")+"\n                  "+t._s(e.com_pocart?e.com_pocart+"%":"")+"\n                ")])]),a("div",{staticClass:"flex-sa"},[a("div",[t._v("客户名称: "+t._s(e.user_name))]),1==e.is_return?a("div",[t._v("\n                  回厂时间: "+t._s(t.formatDate(e.return_time))+"\n                ")]):a("div",[t._v("不回厂")])]),a("div",{staticClass:"flex-sa"},[a("div",[t._v("材质名称: "+t._s(e.name))]),a("div",[t._v("材质: "+t._s(e.material))])]),a("div",{staticClass:"flex-sa"},[a("div",[t._v("来料数量: "+t._s(e.number))]),a("div",[t._v("交付数量: "+t._s(e.com_number))])]),a("div",{staticClass:"flex-sa"},[a("div",[t._v("来料长: "+t._s(e.length))]),a("div",[t._v("来料宽: "+t._s(e.width))])]),a("div",{staticClass:"flex-sa"},[a("div",[t._v("坑纸长: "+t._s(e.inch_length))]),a("div",[t._v("坑纸宽: "+t._s(e.inch_width))])]),a("div",{staticClass:"flex-sa"},[a("div",[t._v("创建日期: "+t._s(t.formatDate(e.add_time)))]),a("div",[t._v("交货日期: "+t._s(t.formatDate(e.com_time)))])]),a("div",[t._v("备注: "+t._s(e.remark))])]),a("van-icon",{attrs:{name:"arrow",color:"#ccc"}})],1)])})),1)],1)]),a("van-action-sheet",{attrs:{actions:t.actions},on:{select:t.onSelect},model:{value:t.show,callback:function(e){t.show=e},expression:"show"}})],1)},i=[],o=(a("f548"),a("28f8")),s=(a("35f8"),a("5791"),a("f1ab"),a("0353"),a("6f6a"),a("238b"),a("48e6"),a("b65a")),c=a("4c02"),r=a.n(c),l=a("30a7"),d=a("1db3"),u=a("bdec"),h=a("756f"),f=a("a62d"),v=a("4ab1"),g=Object(l["a"])("action-sheet"),p=g[0],b=g[1];function m(t,e,a,n){var i=e.title,o=e.cancelText,s=e.closeable;function c(){Object(d["a"])(n,"input",!1),Object(d["a"])(n,"cancel")}function l(){if(i)return t("div",{class:b("header")},[i,s&&t(h["a"],{attrs:{name:e.closeIcon},class:b("close"),on:{click:c}})])}function u(a,i){var o=a.disabled,s=a.loading,c=a.callback;function r(t){t.stopPropagation(),o||s||(c&&c(a),Object(d["a"])(n,"select",a,i),e.closeOnClickAction&&Object(d["a"])(n,"input",!1))}function l(){return s?t(v["a"],{class:b("loading-icon")}):[t("span",{class:b("name")},[a.name]),a.subname&&t("div",{class:b("subname")},[a.subname])]}return t("button",{attrs:{type:"button"},class:[b("item",{disabled:o,loading:s}),a.className],style:{color:a.color},on:{click:r}},[l()])}function g(){if(o)return[t("div",{class:b("gap")}),t("button",{attrs:{type:"button"},class:b("cancel"),on:{click:c}},[o])]}function p(){var n=(null==a.description?void 0:a.description())||e.description;if(n)return t("div",{class:b("description")},[n])}return t(f["a"],r()([{class:b(),attrs:{position:"bottom",round:e.round,value:e.value,overlay:e.overlay,duration:e.duration,lazyRender:e.lazyRender,lockScroll:e.lockScroll,getContainer:e.getContainer,closeOnPopstate:e.closeOnPopstate,closeOnClickOverlay:e.closeOnClickOverlay,safeAreaInsetBottom:e.safeAreaInsetBottom}},Object(d["b"])(n,!0)]),[l(),p(),t("div",{class:b("content")},[e.actions&&e.actions.map(u),null==a.default?void 0:a.default()]),g()])}m.props=Object(s["a"])({},u["b"],{title:String,actions:Array,duration:[Number,String],cancelText:String,description:String,getContainer:[String,Function],closeOnPopstate:Boolean,closeOnClickAction:Boolean,round:{type:Boolean,default:!0},closeable:{type:Boolean,default:!0},closeIcon:{type:String,default:"cross"},safeAreaInsetBottom:{type:Boolean,default:!0},overlay:{type:Boolean,default:!0},closeOnClickOverlay:{type:Boolean,default:!0}});var k,_=p(m),w=(a("47e3"),a("89de"),a("7c8e")),y=(a("f33c"),a("eee3")),x=(a("0085"),a("9153")),O=(a("8478"),a("aabe")),S=(a("cc57"),a("f44b"),a("2e2f")),j=a("e876"),C=a("02bc"),D=a("c246"),T=a("89af"),B={name:"DataCenter",components:(k={},Object(o["a"])(k,S["a"].name,S["a"]),Object(o["a"])(k,O["a"].name,O["a"]),Object(o["a"])(k,x["a"].name,x["a"]),Object(o["a"])(k,y["a"].name,y["a"]),Object(o["a"])(k,w["a"].name,w["a"]),Object(o["a"])(k,h["a"].name,h["a"]),Object(o["a"])(k,"Loadings",T["a"]),Object(o["a"])(k,"Empty",C["a"]),Object(o["a"])(k,_.name,_),k),props:{},data:function(){return{list:null,show:!1,keywords:"",month:["","0"],page:1,loading:!1,finished:!1,nowMonth:0,monthList:[{text:"月份",value:0}],nowStatus:1,monthStatus:[{text:"待加工",value:1},{text:"加工中",value:2},{text:"已完成",value:3}],actions:[{name:"修改密码"},{name:"系统刷新"},{name:"退出登录"}]}},created:function(){this.createDateDate();var t=new Date,e=this.month;e[0]=t.getFullYear(),this.month=e,this.getData()},mounted:function(){},methods:{createDateDate:function(){for(var t=[{text:"月份",value:0}],e=new Date,a=e.getFullYear(),n=e.getMonth()+1,i=0;i<6;i++){var o="";n-1!==0||(n=12,a-=1);var s=n;o={text:"".concat(s,"月"),value:s},n--,t.push(o)}this.$set(this.month,[0],a),this.monthList=t,console.log(this.monthList)},searchData:function(){""!=this.keywords&&(this.page=""),this.getData()},selectOption:function(t){var e=this.month;e[1]=t,this.month=e,this.page=1,this.getData()},selectStatus:function(t){this.nowStatus=t,this.page=1,this.getData()},onSelect:function(t){"修改密码"==t.name?(this.show=!1,this.$router.replace({path:"/change_password"})):"系统刷新"==t.name?(this.show=!1,D["a"].remove("ntoken"),Object(y["a"])("清除成功")):(this.show=!1,D["a"].remove("ntoken"),this.$router.replace({path:"/"}))},openSetting:function(){this.show=!0},formatDate:function(t){var e=new Date(1e3*t),a=e.getFullYear(),n=e.getMonth()+1;n=n<10?"0"+n:n;var i=e.getDate();return i=i<10?"0"+i:i,a+"-"+n+"-"+i+" "},getData:function(){var t=this,e={search:this.keywords,page:this.page,status:this.nowStatus};0!=this.month[1]&&(e.month=this.month),Object(j["j"])(e).then((function(e){t.list=e.data.list,t.page>1?t.list=t.list.concat(e.data.list):t.list=e.data.list,t.finished=e.data.list.length<20,t.loading=e.data.list.length<20,e.data.list.length>=20&&(t.page=t.page+1)}))},onLoad:function(){this.getData()}}},L=B,$=(a("dede"),a("c701")),E=Object($["a"])(L,n,i,!1,null,"28b7fb5d",null);e["default"]=E.exports},f3f6:function(t,e,a){t.exports=a.p+"h5/img/empty.f65bdbe1.png"},f44b:function(t,e,a){"use strict";a("35f8"),a("f1ab"),a("0353"),a("b664"),a("8dbe"),a("e968")}}]);
//# sourceMappingURL=chunk-7715a88a.7ea29697.js.map