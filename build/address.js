(()=>{var e,t={904:(e,t,r)=>{"use strict";const n=window.wp.element,o=window.wp.i18n;var a=r(184),l=r.n(a);const s=window.wp.blocks,i=window.wp.blockEditor,c=window.wp.serverSideRender;var u=r.n(c);const p=window.wp.components,d=window.lodash,{createBlock:f}=wp.blocks,v={from:[{type:"block",blocks:["oik-block/address"],transform:function(e){return f("oik/address",{tag:e.tag})}},{type:"shortcode",tag:"bw_address",attributes:{tag:{type:"string",shortcode:e=>{let{named:{tag:t}}=e;return t}}}}]},b={div:(0,o.__)("Block","oik"),span:(0,o.__)("Inline","oik")};(0,s.registerBlockType)("oik/address",{example:{},transforms:v,edit:e=>{const{attributes:t,setAttributes:r,instanceId:a,focus:s,isSelected:c}=e,{textAlign:f,label:v}=e.attributes,w=(0,i.useBlockProps)({className:l()({[`has-text-align-${f}`]:f})});return(0,n.createElement)(n.Fragment,null,(0,n.createElement)(i.InspectorControls,null,(0,n.createElement)(p.PanelBody,null,(0,n.createElement)(p.PanelRow,null,(0,n.createElement)(p.SelectControl,{label:(0,o.__)("Display","oik"),value:e.attributes.tag,options:(0,d.map)(b,((e,t)=>({value:t,label:e}))),onChange:(0,d.partial)((function(t,r){e.setAttributes({[t]:r})}),"tag")})),(0,n.createElement)(p.PanelRow,null,(0,o.__)("Equivalent shortcode","oik"),(0,n.createElement)("br",null),"[bw_address tag=",e.attributes.tag,"]"))),(0,n.createElement)("div",w,(0,n.createElement)(u(),{block:"oik/address",attributes:e.attributes})))},save:()=>null})},184:(e,t)=>{var r;!function(){"use strict";var n={}.hasOwnProperty;function o(){for(var e=[],t=0;t<arguments.length;t++){var r=arguments[t];if(r){var a=typeof r;if("string"===a||"number"===a)e.push(r);else if(Array.isArray(r)){if(r.length){var l=o.apply(null,r);l&&e.push(l)}}else if("object"===a)if(r.toString===Object.prototype.toString)for(var s in r)n.call(r,s)&&r[s]&&e.push(s);else e.push(r.toString())}}return e.join(" ")}e.exports?(o.default=o,e.exports=o):void 0===(r=function(){return o}.apply(t,[]))||(e.exports=r)}()}},r={};function n(e){var o=r[e];if(void 0!==o)return o.exports;var a=r[e]={exports:{}};return t[e](a,a.exports,n),a.exports}n.m=t,e=[],n.O=(t,r,o,a)=>{if(!r){var l=1/0;for(u=0;u<e.length;u++){r=e[u][0],o=e[u][1],a=e[u][2];for(var s=!0,i=0;i<r.length;i++)(!1&a||l>=a)&&Object.keys(n.O).every((e=>n.O[e](r[i])))?r.splice(i--,1):(s=!1,a<l&&(l=a));if(s){e.splice(u--,1);var c=o();void 0!==c&&(t=c)}}return t}a=a||0;for(var u=e.length;u>0&&e[u-1][2]>a;u--)e[u]=e[u-1];e[u]=[r,o,a]},n.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return n.d(t,{a:t}),t},n.d=(e,t)=>{for(var r in t)n.o(t,r)&&!n.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},n.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e={577:0,231:0};n.O.j=t=>0===e[t];var t=(t,r)=>{var o,a,l=r[0],s=r[1],i=r[2],c=0;if(l.some((t=>0!==e[t]))){for(o in s)n.o(s,o)&&(n.m[o]=s[o]);if(i)var u=i(n)}for(t&&t(r);c<l.length;c++)a=l[c],n.o(e,a)&&e[a]&&e[a][0](),e[a]=0;return n.O(u)},r=self.webpackChunkoik=self.webpackChunkoik||[];r.forEach(t.bind(null,0)),r.push=t.bind(null,r.push.bind(r))})();var o=n.O(void 0,[231],(()=>n(904)));o=n.O(o)})();