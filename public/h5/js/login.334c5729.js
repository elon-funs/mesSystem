(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["login"],{"1dad":function(t,e,a){"use strict";var s=a("35ea"),c=a("fa2b"),n=a("5d34"),o=Object(s["a"])("checkbox-group"),i=o[0],r=o[1];e["a"]=i({mixins:[Object(n["b"])("vanCheckbox"),c["a"]],props:{max:[Number,String],disabled:Boolean,direction:String,iconSize:[Number,String],checkedColor:String,value:{type:Array,default:function(){return[]}}},watch:{value:function(t){this.$emit("change",t)}},methods:{toggleAll:function(t){if(!1!==t){var e=this.children;t||(e=e.filter((function(t){return!t.checked})));var a=e.map((function(t){return t.name}));this.$emit("input",a)}else this.$emit("input",[])}},render:function(){var t=arguments[0];return t("div",{class:r([this.direction])},[this.slots()])}})},"2c52":function(t,e,a){},"45b5":function(t,e,a){},"481b":function(t,e,a){},"495a":function(t,e,a){"use strict";var s=a("2c52"),c=a.n(s);c.a},"5cf8":function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"scan"},[t._m(0),a("footer",[a("button",{on:{click:t.startRecognize}},[t._v("1.创建控件")]),a("button",{on:{click:t.startScan}},[t._v("2.开始扫描")]),a("button",{on:{click:t.cancelScan}},[t._v("3.结束扫描")]),a("button",{on:{click:t.closeScan}},[t._v("4.关闭控件")])])])},c=[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{attrs:{id:"bcid"}},[a("div",{staticStyle:{height:"40%"}}),a("p",{staticClass:"tip"},[t._v("...载入中...")])])}],n=(a("f548"),null),o={data:function(){return{codeUrl:""}},methods:{startRecognize:function(){var t=this;function e(e,a,s){switch(e){case plus.barcode.QR:e="QR";break;case plus.barcode.EAN13:e="EAN13";break;case plus.barcode.EAN8:e="EAN8";break;default:e="其它"+e;break}a=a.replace(/\n/g,""),t.codeUrl=a,alert(a),t.closeScan()}window.plus&&(n=new plus.barcode.Barcode("bcid"),n.onmarked=e)},startScan:function(){window.plus&&n.start()},cancelScan:function(){window.plus&&n.cancel()},closeScan:function(){window.plus&&n.close()}}},i=o,r=(a("bc4a"),a("9ca4")),u=Object(r["a"])(i,s,c,!1,null,null,null);e["default"]=u.exports},"5f31":function(t,e,a){"use strict";a("7c36"),a("481b")},"8a3e":function(t,e,a){},ac2a:function(t,e,a){"use strict";a.r(e);var s,c=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("div",{staticClass:"content"},[a("p",{staticClass:"logo"}),a("p",{staticClass:"crm"},[t._v("盛彩印刷包装MES")]),a("p",[a("input",{directives:[{name:"model",rawName:"v-model",value:t.account,expression:"account"}],staticClass:"account",attrs:{type:"text",placeholder:"手机号码",value:""},domProps:{value:t.account},on:{input:function(e){e.target.composing||(t.account=e.target.value)}}})]),a("p",[a("input",{directives:[{name:"model",rawName:"v-model",value:t.password,expression:"password"}],staticClass:"password",attrs:{type:"password",placeholder:"密码",value:""},domProps:{value:t.password},on:{input:function(e){e.target.composing||(t.password=e.target.value)}}})]),a("div",{staticClass:"check flex-fs"},[a("van-checkbox",{attrs:{"icon-size":"14px",shape:"square"},model:{value:t.checked,callback:function(e){t.checked=e},expression:"checked"}},[t._v("记住密码")])],1),a("div",{staticClass:"submit",on:{click:t.submitLogin}},[t._v("登录")]),a("router-link",{staticClass:"forgetPassword",attrs:{to:{path:"/change_password"}}},[t._v("修改密码?")])],1),a("p",{staticClass:"bottom"},[t._v("*若没有账号,请联系管理员*")])])},n=[],o=(a("f548"),a("ce3c")),i=(a("5f31"),a("1dad")),r=(a("cc57"),a("b835"),a("dcf8")),u=a("c24f"),l=a("c246"),d=a("ed08"),p={name:"登录",components:(s={},Object(o["a"])(s,r["a"].name,r["a"]),Object(o["a"])(s,i["a"].name,i["a"]),s),data:function(){return{checked:!0,account:"",password:""}},created:function(){l["a"].get("userInfo")&&l["a"].get("ntoken")&&this.$router.replace({path:"/index"})},mounted:function(){var t=l["a"].get("account_m"),e=l["a"].get("account_p");t&&e&&(e=e.replace("x19980731",""),e=e.replace("!857094/",""),this.account=t,this.password=e)},methods:{submitLogin:function(){var t=this;""==Object(d["e"])(this.account)?this.$dialog.message("请输入手机号"):""==Object(d["e"])(this.password)?this.$dialog.message("请输入密码"):Object(u["c"])({mobile:this.account,password:this.password}).then((function(e){var a=e.data;l["a"].set("ntoken",a.token),l["a"].set("userInfo",JSON.stringify(a)),t.checked&&(l["a"].set("account_m",t.account),l["a"].set("account_p","!857094/"+t.password+"x19980731")),t.$router.replace({path:"/index"})})).catch((function(e){t.$dialog.message(e.msg)}))}}},m=p,h=(a("495a"),a("9ca4")),f=Object(h["a"])(m,c,n,!1,null,"028bc4ef",null);e["default"]=f.exports},b835:function(t,e,a){"use strict";a("7c36"),a("e47e"),a("8077"),a("c4ae")},bc4a:function(t,e,a){"use strict";var s=a("45b5"),c=a.n(s);c.a},c242:function(t,e,a){"use strict";a.r(e);var s,c=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("div",{staticClass:"content"},[a("p",{staticClass:"logo"}),a("P",{staticClass:"crm"},[t._v("修改密码")]),a("p",[a("input",{directives:[{name:"model",rawName:"v-model",value:t.account,expression:"account"}],staticClass:"account",attrs:{type:"text",placeholder:"手机号码",value:""},domProps:{value:t.account},on:{input:function(e){e.target.composing||(t.account=e.target.value)}}})]),a("p",[a("input",{directives:[{name:"model",rawName:"v-model",value:t.opassword,expression:"opassword"}],staticClass:"password",attrs:{type:"password",placeholder:"旧密码",value:""},domProps:{value:t.opassword},on:{input:function(e){e.target.composing||(t.opassword=e.target.value)}}})]),a("p",[a("input",{directives:[{name:"model",rawName:"v-model",value:t.npassword,expression:"npassword"}],staticClass:"password",attrs:{type:"password",placeholder:"新密码",value:""},domProps:{value:t.npassword},on:{input:function(e){e.target.composing||(t.npassword=e.target.value)}}})]),a("p",[a("input",{directives:[{name:"model",rawName:"v-model",value:t.rpassword,expression:"rpassword"}],staticClass:"password",attrs:{type:"password",placeholder:"重复密码",value:""},domProps:{value:t.rpassword},on:{input:function(e){e.target.composing||(t.rpassword=e.target.value)}}})]),a("div",{staticClass:"submit",on:{click:t.submitLogin}},[t._v("确认修改")]),a("router-link",{staticClass:"forgetPassword",attrs:{to:{path:"/login"}}},[t._v("去登陆")])],1),a("p",{staticClass:"bottom"},[t._v("*若没有账号,请联系管理员*")])])},n=[],o=(a("f548"),a("ce3c")),i=(a("5f31"),a("1dad")),r=(a("cc57"),a("b835"),a("dcf8")),u=a("c24f"),l=(a("d0a4"),a("ed08")),d={name:"修改密码",components:(s={},Object(o["a"])(s,r["a"].name,r["a"]),Object(o["a"])(s,i["a"].name,i["a"]),s),data:function(){return{checked:!0,account:"",opassword:"",npassword:"",rpassword:""}},mounted:function(){},methods:{submitLogin:function(){var t=this;""==Object(l["e"])(this.account)?this.$dialog.message("请输入手机号"):""==Object(l["e"])(this.opassword)?this.$dialog.message("请输入旧密码"):""==Object(l["e"])(this.npassword)?this.$dialog.message("请输入新密码"):Object(l["e"])(this.rpassword)!=Object(l["e"])(this.npassword)?this.$dialog.message("两次密码不一致"):Object(u["a"])({mobile:this.account,newPassword:this.npassword,oldPassword:this.opassword,passwordCopy:this.rpassword}).then((function(e){e.data;t.$dialog.message("修改成功"),t.$router.replace({path:"/"})})).catch((function(e){t.$dialog.message(e.msg)}))}}},p=d,m=(a("cf64"),a("9ca4")),h=Object(m["a"])(p,c,n,!1,null,"789c5a86",null);e["default"]=h.exports},c4ae:function(t,e,a){},cf64:function(t,e,a){"use strict";var s=a("8a3e"),c=a.n(s);c.a},dcf8:function(t,e,a){"use strict";var s=a("35ea"),c=a("7078"),n=Object(s["a"])("checkbox"),o=n[0],i=n[1];e["a"]=o({mixins:[Object(c["a"])({bem:i,role:"checkbox",parent:"vanCheckbox"})],computed:{checked:{get:function(){return this.parent?-1!==this.parent.value.indexOf(this.name):this.value},set:function(t){this.parent?this.setParentValue(t):this.$emit("input",t)}}},watch:{value:function(t){this.$emit("change",t)}},methods:{toggle:function(t){var e=this;void 0===t&&(t=!this.checked),clearTimeout(this.toggleTask),this.toggleTask=setTimeout((function(){e.checked=t}))},setParentValue:function(t){var e=this.parent,a=e.value.slice();if(t){if(e.max&&a.length>=e.max)return;-1===a.indexOf(this.name)&&(a.push(this.name),e.$emit("input",a))}else{var s=a.indexOf(this.name);-1!==s&&(a.splice(s,1),e.$emit("input",a))}}}})}}]);
//# sourceMappingURL=login.334c5729.js.map