(()=>{"use strict";var e,t={463:()=>{const e=window.React,t=window.wp.i18n,l=window.wp.blocks,r=window.wp.blockEditor,a=(window.wp.serverSideRender,window.wp.components),n=window.wp.element,o=(window.lodash,({setAttributes:t,type:l,name:r,required:n})=>"textarea"===l?(0,e.createElement)(a.TextareaControl,{type:l,name:r,required:n}):"checkbox"===l?(0,e.createElement)(a.CheckboxControl,{name:r,required:n}):(0,e.createElement)(a.TextControl,{type:l,name:r,required:n})),i=[{label:(0,t.__)("Text","oik"),value:"text"},{label:(0,t.__)("Textarea","oik"),value:"textarea"},{label:(0,t.__)("Email","oik"),value:"email"},{label:(0,t.__)("Checkbox","oik"),value:"checkbox"}];(0,l.registerBlockType)("oik/contact-field",{example:{},edit:l=>{const{attributes:c,setAttributes:u,instanceId:s,focus:d,isSelected:m}=l,{textAlign:b,label:p}=l.attributes,v=(0,r.useBlockProps)();return(0,e.createElement)(n.Fragment,null,(0,e.createElement)(r.InspectorControls,null,(0,e.createElement)(a.PanelBody,null,(0,e.createElement)(a.PanelRow,null,(0,e.createElement)(a.TextControl,{label:(0,t.__)("Label","oik"),value:c.label,onChange:e=>{l.setAttributes({label:e})}}))),(0,e.createElement)(a.PanelBody,null,(0,e.createElement)(a.PanelRow,null,(0,e.createElement)(a.SelectControl,{label:(0,t.__)("Type","oik"),value:c.type,onChange:e=>{l.setAttributes({type:e})},options:i}))),(0,e.createElement)(a.PanelBody,null,(0,e.createElement)(a.PanelRow,null,(0,e.createElement)(a.ToggleControl,{label:(0,t.__)("Required?","oik"),checked:!!c.required,onChange:e=>{l.setAttributes({required:e})}})),(0,e.createElement)(a.PanelRow,null,(0,e.createElement)(a.TextControl,{label:(0,t.__)("Required indicator","oik"),value:c.requiredIndicator,onChange:e=>{l.setAttributes({requiredIndicator:e})}})))),(0,e.createElement)("div",{...v},(0,e.createElement)("div",null,(0,e.createElement)("div",{className:"label"},(0,e.createElement)("label",{htmlFor:c.name},c.label,c.required&&(0,e.createElement)("span",{className:"required"},c.requiredIndicator))),(0,e.createElement)("div",{className:"field"},(0,e.createElement)(o,{type:c.type,name:c.name,required:c.required})))))},save:()=>null})}},l={};function r(e){var a=l[e];if(void 0!==a)return a.exports;var n=l[e]={exports:{}};return t[e](n,n.exports,r),n.exports}r.m=t,e=[],r.O=(t,l,a,n)=>{if(!l){var o=1/0;for(s=0;s<e.length;s++){for(var[l,a,n]=e[s],i=!0,c=0;c<l.length;c++)(!1&n||o>=n)&&Object.keys(r.O).every((e=>r.O[e](l[c])))?l.splice(c--,1):(i=!1,n<o&&(o=n));if(i){e.splice(s--,1);var u=a();void 0!==u&&(t=u)}}return t}n=n||0;for(var s=e.length;s>0&&e[s-1][2]>n;s--)e[s]=e[s-1];e[s]=[l,a,n]},r.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e={490:0,305:0};r.O.j=t=>0===e[t];var t=(t,l)=>{var a,n,[o,i,c]=l,u=0;if(o.some((t=>0!==e[t]))){for(a in i)r.o(i,a)&&(r.m[a]=i[a]);if(c)var s=c(r)}for(t&&t(l);u<o.length;u++)n=o[u],r.o(e,n)&&e[n]&&e[n][0](),e[n]=0;return r.O(s)},l=globalThis.webpackChunkoik=globalThis.webpackChunkoik||[];l.forEach(t.bind(null,0)),l.push=t.bind(null,l.push.bind(l))})();var a=r.O(void 0,[305],(()=>r(463)));a=r.O(a)})();