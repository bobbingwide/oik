!function(){var e,t={624:function(e,t,r){"use strict";var n=window.wp.element;const{createBlock:o}=wp.blocks,i={from:[{type:"block",blocks:["oik-block/googlemap"],transform:function(e){return o("oik/googlemap",{})}},{type:"shortcode",tag:"bw_show_googlemap",attributes:{}}]};var a=window.wp.i18n,s=r(184),u=r.n(s),l=window.wp.blocks,c=window.wp.blockEditor;window.wp.serverSideRender,window.wp.components,window.lodash;const p=(0,n.createElement)("h3",null,(0,a.__)("Map","oik"));(0,l.registerBlockType)("oik/googlemap",{transforms:i,edit:e=>{const{attributes:t,setAttributes:r,instanceId:o,focus:i,isSelected:s}=e,{textAlign:l,label:f}=e.attributes,v=(0,c.useBlockProps)({className:u()({[`has-text-align-${l}`]:l})});return(0,n.createElement)("div",v,p,(0,n.createElement)("p",null,(0,a.__)("This is where the map will appear","oik")))},save:e=>{const t=c.useBlockProps.save();return(0,n.createElement)("div",t,p,"[","bw_show_googlemap","]")}})},184:function(e,t){var r;!function(){"use strict";var n={}.hasOwnProperty;function o(){for(var e=[],t=0;t<arguments.length;t++){var r=arguments[t];if(r){var i=typeof r;if("string"===i||"number"===i)e.push(r);else if(Array.isArray(r)){if(r.length){var a=o.apply(null,r);a&&e.push(a)}}else if("object"===i)if(r.toString===Object.prototype.toString)for(var s in r)n.call(r,s)&&r[s]&&e.push(s);else e.push(r.toString())}}return e.join(" ")}e.exports?(o.default=o,e.exports=o):void 0===(r=function(){return o}.apply(t,[]))||(e.exports=r)}()}},r={};function n(e){var o=r[e];if(void 0!==o)return o.exports;var i=r[e]={exports:{}};return t[e](i,i.exports,n),i.exports}n.m=t,e=[],n.O=function(t,r,o,i){if(!r){var a=1/0;for(c=0;c<e.length;c++){r=e[c][0],o=e[c][1],i=e[c][2];for(var s=!0,u=0;u<r.length;u++)(!1&i||a>=i)&&Object.keys(n.O).every((function(e){return n.O[e](r[u])}))?r.splice(u--,1):(s=!1,i<a&&(a=i));if(s){e.splice(c--,1);var l=o();void 0!==l&&(t=l)}}return t}i=i||0;for(var c=e.length;c>0&&e[c-1][2]>i;c--)e[c]=e[c-1];e[c]=[r,o,i]},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,{a:t}),t},n.d=function(e,t){for(var r in t)n.o(t,r)&&!n.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},function(){var e={651:0,61:0};n.O.j=function(t){return 0===e[t]};var t=function(t,r){var o,i,a=r[0],s=r[1],u=r[2],l=0;if(a.some((function(t){return 0!==e[t]}))){for(o in s)n.o(s,o)&&(n.m[o]=s[o]);if(u)var c=u(n)}for(t&&t(r);l<a.length;l++)i=a[l],n.o(e,i)&&e[i]&&e[i][0](),e[i]=0;return n.O(c)},r=self.webpackChunkoik=self.webpackChunkoik||[];r.forEach(t.bind(null,0)),r.push=t.bind(null,r.push.bind(r))}();var o=n.O(void 0,[61],(function(){return n(624)}));o=n.O(o)}();