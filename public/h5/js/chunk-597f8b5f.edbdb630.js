(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-597f8b5f"],{"02bc":function(t,a,n){"use strict";var s=function(){var t=this,a=t.$createElement;t._self._c;return t._m(0)},e=[function(){var t=this,a=t.$createElement,s=t._self._c||a;return s("div",[s("div",{staticClass:"empty"},[s("img",{attrs:{src:n("f3f6")}})])])}],i={name:"Empty",props:{}},c=i,r=n("9ca4"),o=Object(r["a"])(c,s,e,!1,null,null,null);a["a"]=o.exports},"092e":function(t,a,n){"use strict";n.r(a);var s=function(){var t=this,a=t.$createElement,n=t._self._c||a;return n("div",[null==t.data?n("Loadings"):n("div",{staticClass:"container"},[0==t.data.length?n("Empty"):t._e(),n("div",{staticClass:"list"},t._l(t.data,(function(a,s){return n("div",{key:s,staticClass:"item"},[n("div",{staticClass:"info"},[n("div",{staticClass:"company flex-sb"},[t._v("\n            "+t._s(a.company)+"\n            "),n("span",{staticClass:"time"},[t._v("过期"+t._s(a.overTime)+"天")])]),n("div",{staticClass:"name"},[t._v("地址:"+t._s(a.address))]),n("div",{staticClass:"name"},[t._v("\n            姓名:"+t._s(a.name)+"\n            "),n("span",{staticStyle:{"padding-left":"15px"}},[t._v("电话:"+t._s(a.phone))])]),n("div",{staticClass:"name"},[t._v("客户类型:"+t._s(a.status))]),n("div",{staticClass:"name"},[t._v("合同号:"+t._s(a.contract_no))]),n("div",{staticClass:"name"},[t._v("发货日期:"+t._s(t.formatDate(a.invoice_time)))]),n("div",{staticClass:"name"},[t._v("业务员:"+t._s(a.optioner))])])])})),0)],1)],1)},e=[],i=n("ce3c"),c=(n("cc57"),n("733f"),n("d470")),r=(n("e876"),n("02bc")),o=n("89af"),l={name:"OverduePay",components:Object(i["a"])({Empty:r["a"],Loadings:o["a"]},c["a"].name,c["a"]),props:{},data:function(){return{data:[]}},created:function(){},mounted:function(){},methods:{formatDate:function(t){var a=new Date(1e3*t),n=a.getFullYear(),s=a.getMonth()+1;s=s<10?"0"+s:s;var e=a.getDate();return e=e<10?"0"+e:e,n+"-"+s+"-"+e+" "}}},d=l,f=(n("bf3d"),n("9ca4")),u=Object(f["a"])(d,s,e,!1,null,"8124dd8a",null);a["default"]=u.exports},"935f":function(t,a,n){},bf3d:function(t,a,n){"use strict";var s=n("935f"),e=n.n(s);e.a},f3f6:function(t,a,n){t.exports=n.p+"h5/img/empty.f65bdbe1.png"}}]);
//# sourceMappingURL=chunk-597f8b5f.edbdb630.js.map