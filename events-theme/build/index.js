(()=>{"use strict";var e,t={797:()=>{const e=window.wp.blocks,t=window.wp.blockEditor,n=window.wp.components,a=window.wp.element,i=window.ReactJSXRuntime,r=e=>{const t=new Date(e);return`${t.toLocaleDateString("en-GB",{year:"numeric",month:"long",day:"numeric"})} @ ${t.toLocaleTimeString("en-GB",{hour:"2-digit",minute:"2-digit"})}`};(0,e.registerBlockType)("events-theme/event-details-block",{title:"Event Details",icon:"calendar",category:"common",attributes:{eventTitle:{type:"string",default:""},eventDate:{type:"string",default:""},eventLocation:{type:"string",default:""},imageUrl:{type:"string",default:""},eventLink:{type:"string",default:""}},edit({attributes:e,setAttributes:l}){const{eventTitle:s,eventDate:o,eventLocation:c,imageUrl:d,eventLink:v}=e;return(0,i.jsxs)(a.Fragment,{children:[(0,i.jsx)(t.InspectorControls,{children:(0,i.jsxs)(n.PanelBody,{title:"Event Details",children:[(0,i.jsx)(t.MediaUpload,{onSelect:e=>l({imageUrl:e.url}),allowedTypes:["image"],value:d,render:({open:e})=>(0,i.jsx)(n.Button,{isPrimary:!0,onClick:e,children:d?"Replace Image":"Upload Image"})}),(0,i.jsx)(n.TextControl,{label:"Event Title",value:s,onChange:e=>l({eventTitle:e})}),(0,i.jsx)(n.DateTimePicker,{label:"Event Date and Time",currentDate:o,onChange:e=>l({eventDate:e})}),(0,i.jsx)(n.TextControl,{label:"Event Location",value:c,onChange:e=>l({eventLocation:e})}),(0,i.jsx)(n.TextControl,{label:"Event Link",value:v,onChange:e=>l({eventLink:e})})]})}),(0,i.jsxs)("div",{className:"event-details-block",children:[d&&(0,i.jsx)("img",{src:d,alt:"Event",className:"event-image"}),(0,i.jsx)(t.RichText,{tagName:"h4",placeholder:"Event Title",value:s,onChange:e=>l({eventTitle:e}),className:"card-title"}),(0,i.jsx)("p",{children:(0,i.jsx)(t.RichText,{tagName:"span",placeholder:"Event Location",value:c,onChange:e=>l({eventLocation:e}),className:"card-text"})}),(0,i.jsxs)("p",{children:[(0,i.jsx)("strong",{children:"Date and Time: "})," ",r(o)]}),(0,i.jsx)("a",{href:v,className:"btn btn-primary",children:"View Details"})]})]})},save({attributes:e}){const{eventTitle:t,eventDate:n,eventLocation:a,imageUrl:l,eventLink:s}=e;return(0,i.jsxs)("div",{className:"card h-100",children:[l&&(0,i.jsx)("img",{src:l,className:"card-img-top",alt:t}),(0,i.jsxs)("div",{className:"card-body",children:[(0,i.jsx)("h4",{className:"card-title",children:t}),(0,i.jsx)("p",{className:"card-text",children:a}),(0,i.jsxs)("p",{className:"card-text",children:[(0,i.jsx)("strong",{children:"Date and Time: "}),r(n)]}),(0,i.jsx)("a",{href:s,className:"btn btn-primary",children:"View Details"})]})]})}})}},n={};function a(e){var i=n[e];if(void 0!==i)return i.exports;var r=n[e]={exports:{}};return t[e](r,r.exports,a),r.exports}a.m=t,e=[],a.O=(t,n,i,r)=>{if(!n){var l=1/0;for(d=0;d<e.length;d++){n=e[d][0],i=e[d][1],r=e[d][2];for(var s=!0,o=0;o<n.length;o++)(!1&r||l>=r)&&Object.keys(a.O).every((e=>a.O[e](n[o])))?n.splice(o--,1):(s=!1,r<l&&(l=r));if(s){e.splice(d--,1);var c=i();void 0!==c&&(t=c)}}return t}r=r||0;for(var d=e.length;d>0&&e[d-1][2]>r;d--)e[d]=e[d-1];e[d]=[n,i,r]},a.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e={57:0,350:0};a.O.j=t=>0===e[t];var t=(t,n)=>{var i,r,l=n[0],s=n[1],o=n[2],c=0;if(l.some((t=>0!==e[t]))){for(i in s)a.o(s,i)&&(a.m[i]=s[i]);if(o)var d=o(a)}for(t&&t(n);c<l.length;c++)r=l[c],a.o(e,r)&&e[r]&&e[r][0](),e[r]=0;return a.O(d)},n=self.webpackChunkevents_theme=self.webpackChunkevents_theme||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))})();var i=a.O(void 0,[350],(()=>a(797)));i=a.O(i)})();