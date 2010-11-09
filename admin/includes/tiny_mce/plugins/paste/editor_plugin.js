(function(){var c=tinymce.each,d=null,a={paste_auto_cleanup_on_paste:true,paste_block_drop:false,paste_retain_style_properties:"none",paste_strip_class_attributes:"mso",paste_remove_spans:false,paste_remove_styles:false,paste_remove_styles_if_webkit:true,paste_convert_middot_lists:true,paste_convert_headers_to_strong:false,paste_dialog_width:"450",paste_dialog_height:"400",paste_text_use_dialog:false,paste_text_sticky:false,paste_text_notifyalways:false,paste_text_linebreaktype:"p",paste_text_replacements:[[/\u2026/g,"..."],[/[\x93\x94\u201c\u201d]/g,'"'],[/[\x60\x91\x92\u2018\u2019]/g,"'"]]};function b(e,f){return e.getParam(f,a[f])}tinymce.create("tinymce.plugins.PastePlugin",{init:function(e,f){var g=this;g.editor=e;g.url=f;g.onPreProcess=new tinymce.util.Dispatcher(g);g.onPostProcess=new tinymce.util.Dispatcher(g);g.onPreProcess.add(g._preProcess);g.onPostProcess.add(g._postProcess);g.onPreProcess.add(function(j,k){e.execCallback("paste_preprocess",j,k)});g.onPostProcess.add(function(j,k){e.execCallback("paste_postprocess",j,k)});e.pasteAsPlainText=false;function i(l,j){var k=e.dom;g.onPreProcess.dispatch(g,l);l.node=k.create("div",0,l.content);g.onPostProcess.dispatch(g,l);l.content=e.serializer.serialize(l.node,{getInner:1});if((!j)&&(e.pasteAsPlainText)){g._insertPlainText(e,k,l.content);if(!b(e,"paste_text_sticky")){e.pasteAsPlainText=false;e.controlManager.setActive("pastetext",false)}}else{if(/<(p|h[1-6]|ul|ol)/.test(l.content)){g._insertBlockContent(e,k,l.content)}else{g._insert(l.content)}}}e.addCommand("mceInsertClipboardContent",function(j,k){i(k,true)});if(!b(e,"paste_text_use_dialog")){e.addCommand("mcePasteText",function(l,j){var k=tinymce.util.Cookie;e.pasteAsPlainText=!e.pasteAsPlainText;e.controlManager.setActive("pastetext",e.pasteAsPlainText);if((e.pasteAsPlainText)&&(!k.get("tinymcePasteText"))){if(b(e,"paste_text_sticky")){e.windowManager.alert(e.translate("paste.plaintext_mode_sticky"))}else{e.windowManager.alert(e.translate("paste.plaintext_mode_sticky"))}if(!b(e,"paste_text_notifyalways")){k.set("tinymcePasteText","1",new Date(new Date().getFullYear()+1,12,31))}}})}e.addButton("pastetext",{title:"paste.paste_text_desc",cmd:"mcePasteText"});e.addButton("selectall",{title:"paste.selectall_desc",cmd:"selectall"});function h(s){var m,q,j,l=e.selection,p=e.dom,r=e.getBody(),k;if(e.pasteAsPlainText&&(s.clipboardData||p.doc.dataTransfer)){s.preventDefault();i({content:(s.clipboardData||p.doc.dataTransfer).getData("Text").replace(/\r?\n/g,"<br />")});return}if(p.get("_mcePaste")){return}m=p.add(r,"div",{id:"_mcePaste","class":"mcePaste"},'\uFEFF<br _mce_bogus="1">');if(r!=e.getDoc().body){k=p.getPos(e.selection.getStart(),r).y}else{k=r.scrollTop}p.setStyles(m,{position:"absolute",left:-10000,top:k,width:1,height:1,overflow:"hidden"});if(tinymce.isIE){j=p.doc.body.createTextRange();j.moveToElementText(m);j.execCommand("Paste");p.remove(m);if(m.innerHTML==="\uFEFF"){e.execCommand("mcePasteWord");s.preventDefault();return}i({content:m.innerHTML});return tinymce.dom.Event.cancel(s)}else{function o(n){n.preventDefault()}p.bind(e.getDoc(),"mousedown",o);p.bind(e.getDoc(),"keydown",o);q=e.selection.getRng();m=m.firstChild;j=e.getDoc().createRange();j.setStart(m,0);j.setEnd(m,1);l.setRng(j);window.setTimeout(function(){var t="",n=p.select("div.mcePaste");c(n,function(v){var u=v.firstChild;if(u&&u.nodeName=="DIV"&&u.style.marginTop&&u.style.backgroundColor){p.remove(u,1)}c(p.select("div.mcePaste",v),function(w){p.remove(w,1)});c(p.select("span.Apple-style-span",v),function(w){p.remove(w,1)});c(p.select("br[_mce_bogus]",v),function(w){p.remove(w)});t+=v.innerHTML});c(n,function(u){p.remove(u)});if(q){l.setRng(q)}i({content:t});p.unbind(e.getDoc(),"mousedown",o);p.unbind(e.getDoc(),"keydown",o)},0)}}if(b(e,"paste_auto_cleanup_on_paste")){if(tinymce.isOpera||/Firefox\/2/.test(navigator.userAgent)){e.onKeyDown.add(function(j,k){if(((tinymce.isMac?k.metaKey:k.ctrlKey)&&k.keyCode==86)||(k.shiftKey&&k.keyCode==45)){h(k)}})}else{e.onPaste.addToTop(function(j,k){return h(k)})}}if(b(e,"paste_block_drop")){e.onInit.add(function(){e.dom.bind(e.getBody(),["dragend","dragover","draggesture","dragdrop","drop","drag"],function(j){j.preventDefault();j.stopPropagation();return false})})}g._legacySupport()},getInfo:function(){return{longname:"Paste text/word",author:"Moxiecode Systems AB",authorurl:"http://tinymce.moxiecode.com",infourl:"http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/paste",version:tinymce.majorVersion+"."+tinymce.minorVersion}},_preProcess:function(j,f){var l=this.editor,k=f.content,q=tinymce.grep,e=tinymce.explode,g=tinymce.trim,m,i;function n(h){c(h,function(o){if(o.constructor==RegExp){k=k.replace(o,"")}else{k=k.replace(o[0],o[1])}})}if(/class="?Mso|style="[^"]*\bmso-|w:WordDocument/i.test(k)||f.wordContent){f.wordContent=true;n([/^\s*(&nbsp;)+/gi,/(&nbsp;|<br[^>]*>)+\s*$/gi]);if(b(l,"paste_convert_headers_to_strong")){k=k.replace(/<p [^>]*class="?MsoHeading"?[^>]*>(.*?)<\/p>/gi,"<p><strong>$1</strong></p>")}if(b(l,"paste_convert_middot_lists")){n([[/<!--\[if !supportLists\]-->/gi,"$&__MCE_ITEM__"],[/(<span[^>]+(?:mso-list:|:\s*symbol)[^>]+>)/gi,"$1__MCE_ITEM__"]])}n([/<!--[\s\S]+?-->/gi,/<(!|script[^>]*>.*?<\/script(?=[>\s])|\/?(\?xml(:\w+)?|img|meta|link|style|\w:\w+)(?=[\s\/>]))[^>]*>/gi,[/<(\/?)s>/gi,"<$1strike>"],[/&nbsp;/gi,"\u00a0"]]);do{m=k.length;k=k.replace(/(<[a-z][^>]*\s)(?:id|name|language|type|on\w+|\w+:\w+)=(?:"[^"]*"|\w+)\s?/gi,"$1")}while(m!=k.length);if(b(l,"paste_retain_style_properties").replace(/^none$/i,"").length==0){k=k.replace(/<\/?span[^>]*>/gi,"")}else{n([[/<span\s+style\s*=\s*"\s*mso-spacerun\s*:\s*yes\s*;?\s*"\s*>([\s\u00a0]*)<\/span>/gi,function(o,h){return(h.length>0)?h.replace(/./," ").slice(Math.floor(h.length/2)).split("").join("\u00a0"):""}],[/(<[a-z][^>]*)\sstyle="([^"]*)"/gi,function(u,h,t){var v=[],o=0,r=e(g(t).replace(/&quot;/gi,"'"),";");c(r,function(s){var w,y,z=e(s,":");function x(A){return A+((A!=="0")&&(/\d$/.test(A)))?"px":""}if(z.length==2){w=z[0].toLowerCase();y=z[1].toLowerCase();switch(w){case"mso-padding-alt":case"mso-padding-top-alt":case"mso-padding-right-alt":case"mso-padding-bottom-alt":case"mso-padding-left-alt":case"mso-margin-alt":case"mso-margin-top-alt":case"mso-margin-right-alt":case"mso-margin-bottom-alt":case"mso-margin-left-alt":case"mso-table-layout-alt":case"mso-height":case"mso-width":case"mso-vertical-align-alt":v[o++]=w.replace(/^mso-|-alt$/g,"")+":"+x(y);return;case"horiz-align":v[o++]="text-align:"+y;return;case"vert-align":v[o++]="vertical-align:"+y;return;case"font-color":case"mso-foreground":v[o++]="color:"+y;return;case"mso-background":case"mso-highlight":v[o++]="background:"+y;return;case"mso-default-height":v[o++]="min-height:"+x(y);return;case"mso-default-width":v[o++]="min-width:"+x(y);return;case"mso-padding-between-alt":v[o++]="border-collapse:separate;border-spacing:"+x(y);return;case"text-line-through":if((y=="single")||(y=="double")){v[o++]="text-decoration:line-through"}return;case"mso-zero-height":if(y=="yes"){v[o++]="display:none"}return}if(/^(mso|column|font-emph|lang|layout|line-break|list-image|nav|panose|punct|row|ruby|sep|size|src|tab-|table-border|text-(?!align|decor|indent|trans)|top-bar|version|vnd|word-break)/.test(w)){return}v[o++]=w+":"+z[1]}});if(o>0){return h+' style="'+v.join(";")+'"'}else{return h}}]])}}if(b(l,"paste_convert_headers_to_strong")){n([[/<h[1-6][^>]*>/gi,"<p><strong>"],[/<\/h[1-6][^>]*>/gi,"</strong></p>"]])}i=b(l,"paste_strip_class_attributes");if(i!=="none"){function p(r,o){if(i==="all"){return""}var h=q(e(o.replace(/^(["'])(.*)\1$/,"$2")," "),function(s){return(/^(?!mso)/i.test(s))});return h.length?' class="'+h.join(" ")+'"':""}k=k.replace(/ class="([^"]+)"/gi,p);k=k.replace(/ class=(\w+)/gi,p)}if(b(l,"paste_remove_spans")){k=k.replace(/<\/?span[^>]*>/gi,"")}f.content=k},_postProcess:function(h,j){var g=this,f=g.editor,i=f.dom,e;if(j.wordContent){c(i.select("a",j.node),function(k){if(!k.href||k.href.indexOf("#_Toc")!=-1){i.remove(k,1)}});if(b(f,"paste_convert_middot_lists")){g._convertLists(h,j)}e=b(f,"paste_retain_style_properties");if((tinymce.is(e,"string"))&&(e!=="all")&&(e!=="*")){e=tinymce.explode(e.replace(/^none$/i,""));c(i.select("*",j.node),function(n){var o={},l=0,m,p,k;if(e){for(m=0;m<e.length;m++){p=e[m];k=i.getStyle(n,p);if(k){o[p]=k;l++}}}i.setAttrib(n,"style","");if(e&&l>0){i.setStyles(n,o)}else{if(n.nodeName=="SPAN"&&!n.className){i.remove(n,true)}}})}}if(b(f,"paste_remove_styles")||(b(f,"paste_remove_styles_if_webkit")&&tinymce.isWebKit)){c(i.select("*[style]",j.node),function(k){k.removeAttribute("style");k.removeAttribute("_mce_style")})}else{if(tinymce.isWebKit){c(i.select("*",j.node),function(k){k.removeAttribute("_mce_style")})}}},_convertLists:function(i,f){var k=i.editor.dom,h,n,e=-1,j,m=[],g,l;c(k.select("p",f.node),function(u){var q,v="",t,s,o,r;for(q=u.firstChild;q&&q.nodeType==3;q=q.nextSibling){v+=q.nodeValue}v=u.innerHTML.replace(/<\/?\w+[^>]*>/gi,"").replace(/&nbsp;/g,"\u00a0");if(/^(__MCE_ITEM__)+[\u2022\u00b7\u00a7\u00d8o]\s*\u00a0*/.test(v)){t="ul"}if(/^__MCE_ITEM__\s*\w+\.\s*\u00a0{2,}/.test(v)){t="ol"}if(t){j=parseFloat(u.style.marginLeft||0);if(j>e){m.push(j)}if(!h||t!=g){h=k.create(t);k.insertAfter(h,u)}else{if(j>e){h=n.appendChild(k.create(t))}else{if(j<e){o=tinymce.inArray(m,j);r=k.getParents(h.parentNode,t);h=r[r.length-1-o]||h}}}c(k.select("span",u),function(w){var p=w.innerHTML.replace(/<\/?\w+[^>]*>/gi,"");if(t=="ul"&&/^[\u2022\u00b7\u00a7\u00d8o]/.test(p)){k.remove(w)}else{if(/^[\s\S]*\w+\.(&nbsp;|\u00a0)*\s*/.test(p)){k.remove(w)}}});s=u.innerHTML;if(t=="ul"){s=u.innerHTML.replace(/__MCE_ITEM__/g,"").replace(/^[\u2022\u00b7\u00a7\u00d8o]\s*(&nbsp;|\u00a0)+\s*/,"")}else{s=u.innerHTML.replace(/__MCE_ITEM__/g,"").replace(/^\s*\w+\.(&nbsp;|\u00a0)+\s*/,"")}n=h.appendChild(k.create("li",0,s));k.remove(u);e=j;g=t}else{h=e=0}});l=f.node.innerHTML;if(l.indexOf("__MCE_ITEM__")!=-1){f.node.innerHTML=l.replace(/__MCE_ITEM__/g,"")}},_insertBlockContent:function(l,i,m){var f,h,g=l.selection,p,n,e,o,j,k="mce_marker";function q(t){var s;if(tinymce.isIE){s=l.getDoc().body.createTextRange();s.moveToElementText(t);s.collapse(false);s.select()}else{g.select(t,1);g.collapse(false)}}this._insert('<span id="'+k+'"></span>',1);h=i.get(k);f=i.getParent(h,"p,h1,h2,h3,h4,h5,h6,ul,ol,th,td");if(f&&!/TD|TH/.test(f.nodeName)){h=i.split(f,h);c(i.create("div",0,m).childNodes,function(r){p=h.parentNode.insertBefore(r.cloneNode(true),h)});q(p)}else{i.setOuterHTML(h,m);g.select(l.getBody(),1);g.collapse(0)}while(n=i.get(k)){i.remove(n)}n=g.getStart();e=i.getViewPort(l.getWin());o=l.dom.getPos(n).y;j=n.clientHeight;if(o<e.y||o+j>e.y+e.h){l.getDoc().body.scrollTop=o<e.y?o:o-e.h+25}},_insert:function(g,e){var f=this.editor,i=f.selection.getRng();if(!f.selection.isCollapsed()&&i.startContainer!=i.endContainer){f.getDoc().execCommand("Delete",false,null)}f.execCommand(tinymce.isGecko?"insertHTML":"mceInsertContent",false,g,{skip_undo:e})},_insertPlainText:function(g,t,u){var s,v,l,j,q,o,e,f,n=g.getWin(),y=g.getDoc(),r=g.selection,k=tinymce.is,x=tinymce.inArray,m=b(g,"paste_text_linebreaktype"),p=b(g,"paste_text_replacements");function z(h){c(h,function(i){if(i.constructor==RegExp){u=u.replace(i,"")}else{u=u.replace(i[0],i[1])}})}if((typeof(u)==="string")&&(u.length>0)){if(!d){d=("34,quot,38,amp,39,apos,60,lt,62,gt,"+g.serializer.settings.entities).split(",")}if(/<(?:p|br|h[1-6]|ul|ol|dl|table|t[rdh]|div|blockquote|fieldset|pre|address|center)[^>]*>/i.test(u)){z([/[\n\r]+/g])}else{z([/\r+/g])}z([[/<\/(?:p|h[1-6]|ul|ol|dl|table|div|blockquote|fieldset|pre|address|center)>/gi,"\n\n"],[/<br[^>]*>|<\/tr>/gi,"\n"],[/<\/t[dh]>\s*<t[dh][^>]*>/gi,"\t"],/<[a-z!\/?][^>]*>/gi,[/&nbsp;/gi," "],[/&(#\d+|[a-z0-9]{1,10});/gi,function(i,h){if(h.charAt(0)==="#"){return String.fromCharCode(h.slice(1))}else{return((i=x(d,h))>0)?String.fromCharCode(d[i-1]):" "}}],[/(?:(?!\n)\s)*(\n+)(?:(?!\n)\s)*/gi,"$1"],[/\n{3,}/g,"\n\n"],/^\s+|\s+$/g]);u=t.encode(u);if(!r.isCollapsed()){y.execCommand("Delete",false,null)}if(k(p,"array")||(k(p,"array"))){z(p)}else{if(k(p,"string")){z(new RegExp(p,"gi"))}}if(m=="none"){z([[/\n+/g," "]])}else{if(m=="br"){z([[/\n/g,"<br />"]])}else{z([/^\s+|\s+$/g,[/\n\n/g,"</p><p>"],[/\n/g,"<br />"]])}}if((l=u.indexOf("</p><p>"))!=-1){j=u.lastIndexOf("</p><p>");q=r.getNode();o=[];do{if(q.nodeType==1){if(q.nodeName=="TD"||q.nodeName=="BODY"){break}o[o.length]=q}}while(q=q.parentNode);if(o.length>0){e=u.substring(0,l);f="";for(s=0,v=o.length;s<v;s++){e+="</"+o[s].nodeName.toLowerCase()+">";f+="<"+o[o.length-s-1].nodeName.toLowerCase()+">"}if(l==j){u=e+f+u.substring(l+7)}else{u=e+u.substring(l+4,j+4)+f+u.substring(j+7)}}}g.execCommand("mceInsertRawHTML",false,u+'<span id="_plain_text_marker">&nbsp;</span>');window.setTimeout(function(){var i=t.get("_plain_text_marker"),B,h,A,w;r.select(i,false);y.execCommand("Delete",false,null);i=null;B=r.getStart();h=t.getViewPort(n);A=t.getPos(B).y;w=B.clientHeight;if((A<h.y)||(A+w>h.y+h.h)){y.body.scrollTop=A<h.y?A:A-h.h+25}},0)}},_legacySupport:function(){var f=this,e=f.editor;e.addCommand("mcePasteWord",function(){e.windowManager.open({file:f.url+"/pasteword.htm",width:parseInt(b(e,"paste_dialog_width")),height:parseInt(b(e,"paste_dialog_height")),inline:1})});if(b(e,"paste_text_use_dialog")){e.addCommand("mcePasteText",function(){e.windowManager.open({file:f.url+"/pastetext.htm",width:parseInt(b(e,"paste_dialog_width")),height:parseInt(b(e,"paste_dialog_height")),inline:1})})}e.addButton("pasteword",{title:"paste.paste_word_desc",cmd:"mcePasteWord"})}});tinymce.PluginManager.add("paste",tinymce.plugins.PastePlugin)})();