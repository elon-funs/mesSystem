(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-28b4e292"],{"8c39":function(e,t,n){"use strict";var a=n("b1f7"),s=n.n(a);s.a},b1f7:function(e,t,n){},c8ad:function(e,t,n){"use strict";n.r(t);var a,s=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("div",{staticClass:"content"},[n("p",{staticClass:"logo"}),n("p",{staticClass:"crm"},[e._v("个人资料修改")]),n("p",[n("input",{staticClass:"account",attrs:{type:"text",disabled:"",placeholder:""},domProps:{value:e.mobile}})]),n("p",[n("input",{directives:[{name:"model",rawName:"v-model",value:e.user_login,expression:"user_login"}],staticClass:"account",attrs:{type:"text",placeholder:"用户名",value:""},domProps:{value:e.user_login},on:{input:function(t){t.target.composing||(e.user_login=t.target.value)}}})]),n("p",[n("input",{directives:[{name:"model",rawName:"v-model",value:e.user_nickname,expression:"user_nickname"}],staticClass:"account",attrs:{type:"text",placeholder:"用户昵称",value:""},domProps:{value:e.user_nickname},on:{input:function(t){t.target.composing||(e.user_nickname=t.target.value)}}})]),n("p",[n("input",{directives:[{name:"model",rawName:"v-model",value:e.signature,expression:"signature"}],staticClass:"account",attrs:{type:"text",placeholder:"公司名称",value:""},domProps:{value:e.signature},on:{input:function(t){t.target.composing||(e.signature=t.target.value)}}})]),n("div",{staticClass:"submit",on:{click:e.submitLogin}},[e._v("确认修改")])])])},i=[],c=n("ce3c"),o=(n("8e93"),n("a41f")),u=(n("cc57"),n("733f"),n("d470")),r=n("c24f"),l=(n("c246"),n("ed08")),m={name:"Changeinfo",components:(a={},Object(c["a"])(a,u["a"].name,u["a"]),Object(c["a"])(a,o["a"].name,o["a"]),a),data:function(){return{checked:!0,user_login:"",signature:"",user_nickname:"",mobile:"",show:!1}},mounted:function(){var e=this;Object(r["a"])().then((function(t){var n=t.data;e.user_nickname=n.user.user_nickname,e.signature=n.user.signature,e.user_login=n.user.user_login,e.mobile=n.user.mobile})).catch((function(e){Object(o["a"])(e.msg)}))},methods:{openSetting:function(){this.show=!0},submitLogin:function(){""==Object(l["e"])(this.user_login)?Object(o["a"])("请输入用户名"):Object(r["e"])({user_nickname:this.user_nickname,user_login:this.user_login,signature:this.signature}).then((function(e){e.data;Object(o["a"])("修改成功"),location.reload()})).catch((function(e){Object(o["a"])(e.msg)}))}}},p=m,d=(n("8c39"),n("9ca4")),g=Object(d["a"])(p,s,i,!1,null,"05d0144a",null);t["default"]=g.exports}}]);
//# sourceMappingURL=chunk-28b4e292.ad048b62.js.map