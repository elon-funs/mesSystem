(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-ee90f042"],{"02bc":function(t,a,s){"use strict";var e=function(){var t=this,a=t.$createElement;t._self._c;return t._m(0)},n=[function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",[e("div",{staticClass:"empty"},[e("img",{attrs:{src:s("f3f6")}})])])}],i={name:"Empty",props:{}},c=i,d=s("9ca4"),r=Object(d["a"])(c,e,n,!1,null,null,null);a["a"]=r.exports},"7a94":function(t,a,s){},"7d69":function(t,a,s){"use strict";s.r(a);var e=function(){var t=this,a=t.$createElement,s=t._self._c||a;return s("div",[null==t.data?s("Loadings"):s("div",{staticClass:"container"},[s("div",{staticClass:"list "},[s("div",[s("div",{staticClass:"item"},[s("div",{staticClass:"info"},[s("div",{staticClass:"title flex-sb"},[t._v("\n              客户名称: "+t._s(t.data.customer.company)+"\n              "),s("span",{staticClass:"time"},[t._v(t._s(t.formatDate(t.data.order.cr_time)))])]),s("div",[t._v("合同号: "+t._s(t.data.order.contract_no))]),s("div",[t._v("客户电话: "+t._s(t.data.order.tele_phone))]),s("div",[t._v("业务员: "+t._s(t.data.order.optioner))]),s("div",{staticClass:"flex-sb"},[t._v("\n              总数量\n              "),s("span",[t._v("\n                "+t._s(t.data.order.num)+"\n                "),s("span",[t._v(t._s(t.data.order.unit?t.data.order.unit:"件"))])])]),s("div",{staticClass:"flex-sb"},[t._v("\n              总价格\n              "),s("span",[t._v("\n                "+t._s(t.data.order.total_price)+"\n                "),s("span",[t._v("元")])])])])])])]),s("div",{staticClass:"list"},[s("div",{staticClass:"caption"},[t._v("产品明细")]),s("div",t._l(t.data.order_product,(function(a,e){return s("div",{key:e,staticClass:"item"},[s("div",{staticClass:"info"},[s("div",{staticClass:"title"},[t._v("\n              货号"+t._s(a.number)+"\n              "),s("span",{staticStyle:{"padding-left":"20px"}},[t._v("品名"+t._s(a.title))])]),s("div",{staticClass:"tips"},[t._v("型号:"+t._s(""==a.model?"暂无":a.model))]),s("div",{staticClass:"tips"},[t._v("产品属性:"+t._s(""==a.attribute?"暂无":a.attribute))]),s("div",{staticClass:"tips"},[t._v("\n              规格:"+t._s(a.standards)+"\n              "),s("span",{staticStyle:{"padding-left":"20px"}},[t._v("单位:"+t._s(a.unit))])]),s("div",{staticClass:"tips"},[t._v(t._s(a.explanfield?a.explanfield:""))])])])})),0)])])],1)},n=[],i=s("ce3c"),c=(s("cc57"),s("733f"),s("d470")),d=s("e876"),r=s("02bc"),l=s("89af"),o={name:"MyMessage",components:Object(i["a"])({Empty:r["a"],Loadings:l["a"]},c["a"].name,c["a"]),data:function(){return{data:null}},created:function(){var t=this;Object(d["g"])({order_no:this.$route.params.type}).then((function(a){t.data=a.data}))},mounted:function(){},methods:{formatDate:function(t){var a=new Date(1e3*t),s=a.getFullYear(),e=a.getMonth()+1;e=e<10?"0"+e:e;var n=a.getDate();return n=n<10?"0"+n:n,s+"-"+e+"-"+n+" "}}},v=o,_=(s("84e8"),s("9ca4")),u=Object(_["a"])(v,e,n,!1,null,"700e40c9",null);a["default"]=u.exports},"84e8":function(t,a,s){"use strict";var e=s("7a94"),n=s.n(e);n.a},f3f6:function(t,a,s){t.exports=s.p+"h5/img/empty.f65bdbe1.png"}}]);
//# sourceMappingURL=chunk-ee90f042.cf688bf1.js.map