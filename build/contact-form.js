!function(){var t,e={290:function(t,e,r){"use strict";var n=window.wp.element,o=(window.wp.i18n,r(184)),i=r.n(o),c=window.wp.blocks,a=window.wp.blockEditor,s=window.wp.serverSideRender,u=r.n(s);window.wp.components,window.lodash;const{createBlock:l}=wp.blocks,f={from:[{type:"block",blocks:["oik-block/contact-form"],transform:function(t){return l("oik/contact-form",{})}},{type:"shortcode",tag:"bw_contact_form",attributes:{user:{type:"string",shortcode:t=>{let{named:{user:e}}=t;return e}},contact:{type:"string",shortcode:t=>{let{named:{contact:e}}=t;return e}},email:{type:"string",shortcode:t=>{let{named:{email:e}}=t;return e}}}}]};(0,c.registerBlockType)("oik/contact-form",{example:{},transforms:f,edit:t=>{const{attributes:e,setAttributes:r,instanceId:o,focus:c,isSelected:s}=t,{textAlign:l,label:f}=t.attributes,p=(0,a.useBlockProps)({className:i()({[`has-text-align-${l}`]:l})});return(0,n.createElement)("div",p,(0,n.createElement)(u(),{block:"oik/contact-form",attributes:t.attributes}))},save:()=>null})},184:function(t,e){var r;!function(){"use strict";var n={}.hasOwnProperty;function o(){for(var t=[],e=0;e<arguments.length;e++){var r=arguments[e];if(r){var i=typeof r;if("string"===i||"number"===i)t.push(r);else if(Array.isArray(r)){if(r.length){var c=o.apply(null,r);c&&t.push(c)}}else if("object"===i)if(r.toString===Object.prototype.toString)for(var a in r)n.call(r,a)&&r[a]&&t.push(a);else t.push(r.toString())}}return t.join(" ")}t.exports?(o.default=o,t.exports=o):void 0===(r=function(){return o}.apply(e,[]))||(t.exports=r)}()}},r={};function n(t){var o=r[t];if(void 0!==o)return o.exports;var i=r[t]={exports:{}};return e[t](i,i.exports,n),i.exports}n.m=e,t=[],n.O=function(e,r,o,i){if(!r){var c=1/0;for(l=0;l<t.length;l++){r=t[l][0],o=t[l][1],i=t[l][2];for(var a=!0,s=0;s<r.length;s++)(!1&i||c>=i)&&Object.keys(n.O).every((function(t){return n.O[t](r[s])}))?r.splice(s--,1):(a=!1,i<c&&(c=i));if(a){t.splice(l--,1);var u=o();void 0!==u&&(e=u)}}return e}i=i||0;for(var l=t.length;l>0&&t[l-1][2]>i;l--)t[l]=t[l-1];t[l]=[r,o,i]},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,{a:e}),e},n.d=function(t,e){for(var r in e)n.o(e,r)&&!n.o(t,r)&&Object.defineProperty(t,r,{enumerable:!0,get:e[r]})},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},function(){var t={264:0,837:0};n.O.j=function(e){return 0===t[e]};var e=function(e,r){var o,i,c=r[0],a=r[1],s=r[2],u=0;if(c.some((function(e){return 0!==t[e]}))){for(o in a)n.o(a,o)&&(n.m[o]=a[o]);if(s)var l=s(n)}for(e&&e(r);u<c.length;u++)i=c[u],n.o(t,i)&&t[i]&&t[i][0](),t[i]=0;return n.O(l)},r=self.webpackChunkoik=self.webpackChunkoik||[];r.forEach(e.bind(null,0)),r.push=e.bind(null,r.push.bind(r))}();var o=n.O(void 0,[837],(function(){return n(290)}));o=n.O(o)}();