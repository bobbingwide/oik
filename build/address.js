!function(){var e,t={904:function(e,t,n){"use strict";var r=window.wp.element,o=window.wp.i18n,a=n(184),i=n.n(a),l=window.wp.blocks,s=window.wp.blockEditor,u=window.wp.serverSideRender,c=n.n(u),f=window.wp.components,p=window.lodash;const{createBlock:d}=wp.blocks,b={from:[{type:"block",blocks:["oik-block/address"],transform:function(e){return d("oik/address",{tag:e.tag})}},{type:"shortcode",tag:"bw_address",attributes:{tag:{type:"string",shortcode:e=>{let{named:{tag:t}}=e;return t}}}}]},v={div:(0,o.__)("Block","oik"),span:(0,o.__)("Inline","oik")};(0,l.registerBlockType)("oik/address",{example:{},transforms:b,edit:e=>{const{attributes:t,setAttributes:n,instanceId:a,focus:l,isSelected:u}=e,{textAlign:d,label:b}=e.attributes,w=(0,s.useBlockProps)({className:i()({[`has-text-align-${d}`]:d})});return(0,r.createElement)(r.Fragment,null,(0,r.createElement)(s.InspectorControls,null,(0,r.createElement)(f.PanelBody,null,(0,r.createElement)(f.PanelRow,null,(0,r.createElement)(f.SelectControl,{label:(0,o.__)("Display","oik"),value:e.attributes.tag,options:(0,p.map)(v,((e,t)=>({value:t,label:e}))),onChange:(0,p.partial)((function(t,n){e.setAttributes({[t]:n})}),"tag")})),(0,r.createElement)(f.PanelRow,null,(0,o.__)("Equivalent shortcode","oik"),(0,r.createElement)("br",null),"[bw_address tag=",e.attributes.tag,"]"))),(0,r.createElement)("div",w,(0,r.createElement)(c(),{block:"oik/address",attributes:e.attributes})))},save:()=>null})},184:function(e,t){var n;!function(){"use strict";var r={}.hasOwnProperty;function o(){for(var e=[],t=0;t<arguments.length;t++){var n=arguments[t];if(n){var a=typeof n;if("string"===a||"number"===a)e.push(n);else if(Array.isArray(n)){if(n.length){var i=o.apply(null,n);i&&e.push(i)}}else if("object"===a)if(n.toString===Object.prototype.toString)for(var l in n)r.call(n,l)&&n[l]&&e.push(l);else e.push(n.toString())}}return e.join(" ")}e.exports?(o.default=o,e.exports=o):void 0===(n=function(){return o}.apply(t,[]))||(e.exports=n)}()}},n={};function r(e){var o=n[e];if(void 0!==o)return o.exports;var a=n[e]={exports:{}};return t[e](a,a.exports,r),a.exports}r.m=t,e=[],r.O=function(t,n,o,a){if(!n){var i=1/0;for(c=0;c<e.length;c++){n=e[c][0],o=e[c][1],a=e[c][2];for(var l=!0,s=0;s<n.length;s++)(!1&a||i>=a)&&Object.keys(r.O).every((function(e){return r.O[e](n[s])}))?n.splice(s--,1):(l=!1,a<i&&(i=a));if(l){e.splice(c--,1);var u=o();void 0!==u&&(t=u)}}return t}a=a||0;for(var c=e.length;c>0&&e[c-1][2]>a;c--)e[c]=e[c-1];e[c]=[n,o,a]},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,{a:t}),t},r.d=function(e,t){for(var n in t)r.o(t,n)&&!r.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},function(){var e={577:0,231:0};r.O.j=function(t){return 0===e[t]};var t=function(t,n){var o,a,i=n[0],l=n[1],s=n[2],u=0;if(i.some((function(t){return 0!==e[t]}))){for(o in l)r.o(l,o)&&(r.m[o]=l[o]);if(s)var c=s(r)}for(t&&t(n);u<i.length;u++)a=i[u],r.o(e,a)&&e[a]&&e[a][0](),e[a]=0;return r.O(c)},n=self.webpackChunkoik=self.webpackChunkoik||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))}();var o=r.O(void 0,[231],(function(){return r(904)}));o=r.O(o)}();