(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-005012b6"],{"02bc":function(t,e,i){"use strict";var n=function(){var t=this,e=t.$createElement;t._self._c;return t._m(0)},a=[function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("div",{staticClass:"empty"},[n("img",{attrs:{src:i("f3f6")}})])])}],s={name:"Empty",props:{}},o=s,r=i("c701"),c=Object(r["a"])(o,n,a,!1,null,null,null);e["a"]=c.exports},"314d":function(t,e,i){"use strict";i("9ac1")},"7c8e":function(t,e,i){"use strict";var n=i("30a7"),a=i("e7f1"),s=i("2172"),o=i("be75"),r=i("4ab1"),c=Object(n["a"])("list"),d=c[0],l=c[1],h=c[2];e["a"]=d({mixins:[Object(o["a"])((function(t){this.scroller||(this.scroller=Object(s["c"])(this.$el)),t(this.scroller,"scroll",this.check)}))],model:{prop:"loading"},props:{error:Boolean,loading:Boolean,finished:Boolean,errorText:String,loadingText:String,finishedText:String,immediateCheck:{type:Boolean,default:!0},offset:{type:[Number,String],default:300},direction:{type:String,default:"down"}},data:function(){return{innerLoading:this.loading}},updated:function(){this.innerLoading=this.loading},mounted:function(){this.immediateCheck&&this.check()},watch:{loading:"check",finished:"check"},methods:{check:function(){var t=this;this.$nextTick((function(){if(!(t.innerLoading||t.finished||t.error)){var e,i=t.$el,n=t.scroller,s=t.offset,o=t.direction;e=n.getBoundingClientRect?n.getBoundingClientRect():{top:0,bottom:n.innerHeight};var r=e.bottom-e.top;if(!r||Object(a["a"])(i))return!1;var c=!1,d=t.$refs.placeholder.getBoundingClientRect();c="up"===o?e.top-d.top<=s:d.bottom-e.bottom<=s,c&&(t.innerLoading=!0,t.$emit("input",!0),t.$emit("load"))}}))},clickErrorText:function(){this.$emit("update:error",!1),this.check()},genLoading:function(){var t=this.$createElement;if(this.innerLoading&&!this.finished)return t("div",{key:"loading",class:l("loading")},[this.slots("loading")||t(r["a"],{attrs:{size:"16"}},[this.loadingText||h("loading")])])},genFinishedText:function(){var t=this.$createElement;if(this.finished){var e=this.slots("finished")||this.finishedText;if(e)return t("div",{class:l("finished-text")},[e])}},genErrorText:function(){var t=this.$createElement;if(this.error){var e=this.slots("error")||this.errorText;if(e)return t("div",{on:{click:this.clickErrorText},class:l("error-text")},[e])}}},render:function(){var t=arguments[0],e=t("div",{ref:"placeholder",key:"placeholder",class:l("placeholder")});return t("div",{class:l(),attrs:{role:"feed","aria-busy":this.innerLoading}},["down"===this.direction?this.slots():e,this.genLoading(),this.genFinishedText(),this.genErrorText(),"up"===this.direction?this.slots():e])}})},"89de":function(t,e,i){"use strict";i("35f8"),i("238b"),i("d73d")},"9ac1":function(t,e,i){},c60b:function(t,e,i){"use strict";i.r(e);var n,a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[null==t.data?i("Loadings"):i("div",{staticClass:"container"},[0==t.data.length?i("Empty"):i("van-list",{staticClass:"list",attrs:{finished:t.finished,"finished-text":"没有更多了"},on:{load:t.onLoad},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},t._l(t.data,(function(e,n){return i("router-link",{key:n,staticClass:"item",attrs:{to:{path:"/custDetails/"+e.cust_no}}},[i("div",{staticClass:"info"},[i("div",{staticClass:"company flex-sb"},[t._v("\n            "+t._s(e.company)+"\n            "),i("span",{staticClass:"time"},[t._v("过期"+t._s(e.overTime)+"天")])]),i("div",{staticClass:"name"},[t._v("地址:"+t._s(e.address))]),i("div",{staticClass:"name"},[t._v("\n            姓名:"+t._s(e.name)+"\n            "),i("span",{staticStyle:{"padding-left":"15px"}},[t._v("电话:"+t._s(e.phone))])]),i("div",{staticClass:"name"},[t._v("客户类型:"+t._s(e.status))]),i("div",{staticClass:"name"},[t._v("要求联系日期:"+t._s(t.formatDate(e.visiting_time)))]),i("div",{staticClass:"name"},[t._v("业务员:"+t._s(e.name))])])])})),1)],1)],1)},s=[],o=(i("6d57"),i("28f8")),r=(i("89de"),i("7c8e")),c=(i("cc57"),i("47e3"),i("756f")),d=i("e876"),l=i("02bc"),h=i("89af"),f={name:"ContactOverdue",components:(n={Empty:l["a"],Loadings:h["a"]},Object(o["a"])(n,c["a"].name,c["a"]),Object(o["a"])(n,r["a"].name,r["a"]),n),props:{},data:function(){return{data:[],isActive:1,pay_no:0,pay_yes:0,page:"",loading:!1,finished:!1}},created:function(){},mounted:function(){},methods:{selectType:function(t){t!=this.isActive&&(this.page="",this.isActive=t,this.visitingOverdue())},visitingOverdue:function(){var t=this;Object(d["w"])({is_pay:this.isActive,page:this.page}).then((function(e){var i;e.data.data.forEach((function(t){i=Math.floor((Date.parse(new Date)/1e3-t.visiting_time)/86400),t.overTime=i})),""!=t.page?t.data=t.data.concat(e.data.data):(t.data=e.data.data,t.pay_no=e.data.pay_no,t.pay_yes=e.data.pay_yes),t.finished=e.data.data.length<200,t.loading=e.data.data.length<200,200==e.data.data.length&&(t.page=t.data[t.data.length-1].visiting_time)}))},formatDate:function(t){var e=new Date(1e3*t),i=e.getFullYear(),n=e.getMonth()+1;n=n<10?"0"+n:n;var a=e.getDate();return a=a<10?"0"+a:a,i+"-"+n+"-"+a+" "},onLoad:function(){this.visitingOverdue()}}},u=f,g=(i("314d"),i("c701")),p=Object(g["a"])(u,a,s,!1,null,"68777ab3",null);e["default"]=p.exports},d73d:function(t,e,i){},f3f6:function(t,e,i){t.exports=i.p+"h5/img/empty.f65bdbe1.png"}}]);
//# sourceMappingURL=chunk-005012b6.a8399cf9.js.map