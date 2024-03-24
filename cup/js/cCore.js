//\///////
//\ DO NOT REMOVE OR CHANGE THIS NOTICE.
//\ coolTip - Core Module v1.71 - Copyright Robert E Boughner 2004. All rights reserved.
//\   Initial: February 28, 2003 - Last Revised: August 16, 2006
//\
//\ coolTip is based on Erik Bosrup's overLIB utility (see
//\ http://www.bosrup.com/web/overlib/) except that it uses an object-oriented approach
//\ so that more than one popup at a time can be present on a page, each with their own
//\ set of properties. Users are free to employ this code for personel use on their
//\ web pages as long as this notice is kept in its entirety. Substantial modifications and changes
//\ to the code, or incorporation into commercial packages, requires prior written permission from the
//\ author and must be documented so that any such modifications and/or changes stand out from the
//\ original code.
//\ 
//\ Give credit on sites that use coolTip.
//\ DO NOT REMOVE OR CHANGE THIS NOTICE.
//\  THIS IS A VERY MODIFIED VERSION. DO NOT EDIT OR PUBLISH. GET THE ORIGINAL!
//\///////
var cLoaded=0,pmStart=10000000,pmUpper=10001000,pmCount=pmStart+1,pms=new Array(),pmt='',cInfo=new Info(1.71,0),FREPLACE=0,FBEFORE=1,FAFTER=2,FALTERNATE=3,FCHAIN=4,hookPts=new Array(),postParse=new Array(),cmdLine=new Array(),runTime=new Array(),parentLyrs=new Array(),coreCmds='donothing,inarray,caparray,sticky,background,noclose,caption,left,right,center,offsetx,offsety,'+
'fgcolor,bgcolor,textcolor,capcolor,closecolor,width,border,cellpad,status,autostatus,'+
'autostatuscap,height,closetext,snapx,snapy,fixx,fixy,relx,rely,fgbackground,bgbackground,'+
'padx,pady,fullhtml,above,below,capicon,textfont,captionfont,closefont,textsize,'+
'captionsize,closesize,timeout,function,delay,hauto,vauto,closeclick,wrap,followmouse,'+
'mouseoff,closetitle,puid,keepctd,multi,cssoff,compatmode,capalign,textalign,cssclass,fgclass,bgclass,textfontclass,'+
'captionfontclass,closefontclass';registerCommands(coreCmds);
function ud(v){return eval('typeof cd_'+v+'=="undefined"');}
var cUdf='undefined',coreValues='donothing||inarray||caparray||sticky|0|background||noclose||caption||left||right|RIGHT|center||offsetx|10|offsety|10|'+
'fgcolor|#CCCCFF|bgcolor|#333399|textcolor|#000000|capcolor|#FFFFFF|closecolor|#9999FF|width|200|border|1|cellpad|2|status||autostatus|0|'+
'autostatuscap||height|-1|closetext|Close|snapx|0|snapy|0|fixx|-1|fixy|-1|relx|null|rely|null|fgbackground||bgbackground||padx|1|pady|1|'+
'fullhtml|0|above||below|BELOW|capicon||textfont|Verdana,Arial,Helvetica|captionfont|Verdana,Arial,Helvetica|closefont|Verdana,Arial,Helvetica|'+
'textsize|1|captionsize|1|closesize|1|timeout|0|function|null|delay|0|hauto|0|vauto|0|closeclick|0|wrap|0|followmouse|1|'+
'mouseoff|0|closetitle|Click to Close|puid||keepctd|0|multi||cssoff|CSSOFF|compatmode|0|capalign||textalign||cssclass||fgclass||bgclass||textfontclass||'+
'captionfontclass||closefontclass|';setDefaultVariables(coreValues);if(ud('aboveheight'))var cd_aboveheight=0;if(ud('frame'))var cd_frame=self;if(ud('text'))var cd_text="Default Text";if(ud('texts'))var cd_texts=new Array("Text 0","Text 1");if(ud('caps'))var cd_caps=new Array("Caption 0","Caption 1");
var cFrame=self,cTip=null,po,cZindex,isMac=(navigator.userAgent.indexOf("Mac")!=-1),cOp=(navigator.userAgent.toLowerCase().indexOf('opera')>-1&&document.createTextNode),cNs4=(navigator.appName=='Netscape'&&parseInt(navigator.appVersion)==4),cNs6=(document.getElementById)? true:false,cKq=cNs6&&/konqueror/i.test(navigator.userAgent),cSaf=cNs6&&/safari/i.test(navigator.userAgent),cIe4=(document.all)?true:false,cIe5=false,cIe55=false,cMx=0,cMy=0,docRoot='document.body';
if(cNs4){var oW=window.innerWidth;var oH=window.innerHeight;window.onresize=function(){if(oW!=window.innerWidth||oH!=window.innerHeight)location.reload();}}
if(cIe4){var agent=navigator.userAgent;if(/MSIE/.test(agent)){var versNum=parseFloat(agent.match(/MSIE[ ](\d\.\d+)\.*/)[1]);if(versNum>=5){cIe5=true;cIe55=(versNum>=5.5&&!cOp)?true:false;if(cNs6)cNs6=false;}}
if(cNs6)cIe4=false;}
if(document.compatMode&&document.compatMode=='CSS1Compat')docRoot=((cIe4&&!cOp)?'document.documentElement':docRoot);if(window.addEventListener)window.addEventListener("load",ctLoadHandler,false);else if(window.attachEvent)window.attachEvent("onload",ctLoadHandler);
var capExtent;
function cooltip(){return coolTip(arguments);}
function coolTip(){var theDiv,args=(typeof arguments[0]=='object')?arguments[0]:arguments;if(!cLoaded||isExclusive(args))return true;if(ctCheckMouseCapture)ctMouseCapture();if(cTip){cTip=(typeof cTip.id!='string')?null:cTip;if(cTip&&cTip.id=='ctDiv'&&!hasKeepCtD(args))cClick();}
cFrame=cd_frame;
cTip=initLayerObject((theDiv=divID(args)),hasCommand(0,args,MULTI)!=-1);po=cTip.pop;setRunTimeVariables(po);parseTokens('x',args);if(typeof cParams!=cUdf&&typeof cParams[theDiv]!=cUdf&&cParams[theDiv].length)parseTokens('x',cParams[theDiv]);if(!postParseChecks('x',args))return false;if(po.delay==0){return runHook("ctMain",FREPLACE,cTip.id);}else{po.delayid=setTimeout("runHook('ctMain',FREPLACE,'"+cTip.id+"')",po.delay);return false;}}
function nd(id,time){if(cLoaded&&!isExclusive()){var lyr=cTip,po,ID='',args=arguments,num=args.length;switch(num){case 2:
case 1:
if(typeof args[0]=='number'){time=args[0];ID=(num==2)?args[1]:null;}else{ID=args[0];time=(num==2)?args[1]:null;}
break;default: ID='ctDiv';}
if(ID)lyr=fetchObjectLyr(ID);if(lyr){po=lyr.pop;hideDelay(time,lyr);if(po){if( po.showingsticky )runHook('setPosition',FCHAIN,po);if( po.removecounter>=1 ){po.showingsticky=0 };if( po.showingsticky==0 ){po.allowmove=po.sticknow=0;if(lyr!=null&&po.timerid==0)runHook("hideObject",FREPLACE,lyr);}else po.removecounter++;}}}
return true;}
function cClick(id){if(cLoaded){var lyr=cTip,po;if(id)lyr=fetchObjectLyr(id);po=lyr.pop;runHook("hideObject",FREPLACE,lyr);if(po&&po.showingsticky)po.showingsticky=po.sticknow=0;}
return false;}
function ctPageDefaults(){var args=(typeof arguments[0]=='object'?arguments[0]:arguments),id=divID(args);if(id){var i,k=new Array()
if(typeof cParams==cUdf)cParams=new Array();for(i=0;i<args.length;i++){if(typeof args[i]=='number'&&args[i]==PUID){i++;continue;}
k[k.length++]=args[i];}
args=k
k=new Array()
if(typeof cParams[id]!=cUdf&&cParams[id].length){if(typeof args[0]=='string'){if(typeof cParams[id][0]=='string')cParams[id][0]=args[0];else{k[0]=args[0];k=k.concat(cParams[id]);cParams[id]=k;}
args=args.slice(1);}
cParams[id]=cParams[id].concat(args);}else cParams[id]=args;}else parseTokens('cd_',args);}
function ctMain(id){
var layerhtml,styleType,cls;cTip=fetchObjectLyr(id);po=cTip.pop;runHook("ctMain",FBEFORE,id);with(po){if(background!=""||fullhtml){
layerhtml=runHook('ctContentBackground',FALTERNATE,css,text,background,fullhtml);}else{
if(fgbackground!="")fgbackground="background=\""+fgbackground+"\"";styleType=(pms[css-1-pmStart]=="cssoff"||pms[css-1-pmStart]=="cssclass");if(bgbackground!="")bgbackground=( styleType?"background=\""+bgbackground+"\"":bgbackground);
if(fgcolor!="")fgcolor=(styleType?"bgcolor=\""+fgcolor+"\"":fgcolor);if(bgcolor!="")bgcolor=(styleType?"bgcolor=\""+bgcolor+"\"":bgcolor);
if(height>0)height=(styleType?"height=\""+height+"\"":height);else height="";
if(cap==""){
layerhtml=runHook('ctContentSimple',FALTERNATE,css,text);}else{cls=(sticky)?close:'';layerhtml=runHook('ctContentCaption',FALTERNATE,css,text,cap,cls);}}
if(sticky){if(timerid>0){clearTimeout(timerid);timerid=0;}
showingsticky=1;removecounter=0;}
if(!runHook("ctCreatePopup",FREPLACE,layerhtml))return false;
if(autostatus>0){status=text;if(autostatus>1)status=cap;if(wrap)status=status.replace(/&nbsp;/g,' ');}
allowmove=0;
if(timeout>0){if(timerid>0)clearTimeout(timerid);timerid=setTimeout("cClick('"+cTip.id+"')",timeout);}
runHook("disp",FREPLACE,status);runHook("ctMain",FAFTER,id);return(cOp&&event&&event.type=='mouseover'&&!status)?'':(status!='');}}
function ctContentSimple(text){var txt;with(po){txt='<table width="'+width+'" border="0" cellpadding="'+border+'" cellspacing="0" '+(bgclass?'class="'+bgclass+'"':bgcolor+' '+height)+'><tr><td>'+ctInnerTable(text)+'</td></tr></table>';}
set_background("");return txt;}
function ctContentCaption(text,title,close){var txt,nameId,doClose=(close!="");var closing="";var closeevent="onmouseover";with(po){if(closeclick==1)closeevent=(closetitle?"title='"+closetitle+"'":"")+" onclick";if(capicon!=""){nameId=' hspace="5"'+' align="middle" alt=""';if(typeof dragimg!=cUdf&&dragimg)nameId=' name="'+dragimg+'" id="'+dragimg+'" hspace="5" align="middle" alt="Drag Enabled" title="Drag Enabled"';capicon='<img src="'+capicon+'"'+nameId+' />';}
if(doClose)
closing='<td '+(!compatmode&&closefontclass?'class="'+closefontclass:'align="right')+'"><a href="javascript:return '+fnRef+'cClick(\''+cTip.id+'\');" '+((compatmode&&closefontclass)?' class="'+closefontclass+'" ':' ')+closeevent+'="return '+fnRef+'cClick(\''+cTip.id+'\');">'+(closefontclass?'':wrapStr(0,closesize,'close'))+close+(closefontclass?'':wrapStr(1,closesize,'close'))+'</a></td>';txt='<table width="'+width+'" border="0" cellpadding="'+border+'" cellspacing="0" '+(bgclass?'class="'+bgclass+'"':bgcolor+' '+bgbackground+' '+height)+'><tr><td><table width="100%" border="0" cellpadding="1" cellspacing="0"><tr><td'+(capalign?' align="'+capalign+'"': '')+(captionfontclass?' class="'+captionfontclass+'">':'>')+(captionfontclass?'':'<b>'+wrapStr(0,captionsize,'caption'))+capicon+title+(captionfontclass?'':wrapStr(1,captionsize)+'</b>')+'</td>'+closing+'</tr></table>'+ctInnerTable(text)+'</td></tr></table>';}
set_background("");return txt;}
function ctContentBackground(text,picture,hasfullhtml){var txt;if(hasfullhtml){txt=text;}else{with(po){txt='<table width="'+width+'" border="0" cellpadding="0" cellspacing="0" height="'+height+'"><tr><td colspan="3" height="'+padyt+'"></td></tr><tr><td width="'+padxl+'"></td><td valign="TOP"'+(textalign?' align="'+textalign+'"':'')+' width="'+(width-padxl-padxr)+'">'+wrapStr(0,textsize,'text')+text+wrapStr(1,textsize)+'</td><td width="'+padxr+'"></td></tr><tr><td colspan="3" height="'+padyb+'"></td></tr></table>';}}
set_background(picture);return txt;}
function set_background(pic){if(pic==""){if(cNs4)cTip.background.src=null;else if(cTip.style)cTip.style.backgroundImage="none";}else{if(cNs4){cTip.background.src=pic;}else if(cTip.style){cTip.style.width=cTip.pop.width+'px';cTip.style.backgroundImage="url("+pic+")";}}}
function ctInnerTable(text){var txt,cIsMultiple=/,/.test(po.cellpad);with(po){txt='<table width="100%" border="0" '+((cNs4||!cIsMultiple)?'cellpadding="'+cellpad+'" ':'')+'cellspacing="0" '+(fgclass?'class="'+fgclass+'"':fgcolor+' '+fgbackground+' '+height)+'><tr><td valign="TOP"'+(textalign?' align="'+textalign+'"':'')+(textfontclass?' class="'+textfontclass+'">':((!cNs4&&cIsMultiple)?' style="'+setCellPadStr(cellpad)+'">':'>'))+(textfontclass?'':wrapStr(0,textsize,'text'))+text+(textfontclass?'':wrapStr(1,textsize))+'</td></tr></table>';}
return txt;}
function disp(statustext){
runHook("disp",FBEFORE,statustext);with(po){if(allowmove==0)  {runHook("placeLayer",FREPLACE);(cNs6&&ShowId<0)?ShowId=setTimeout("runHook('showObject',FREPLACE,cTip)",1):runHook("showObject",FREPLACE,cTip);allowmove=(!followmouse)?0:1;}}
runHook("disp",FAFTER,statustext);if(statustext!="")self.status=statustext;}
function ctCreatePopup(lyrContent){
runHook("ctCreatePopup",FBEFORE,lyrContent);with(po){if(wrap){var wd,ww,cO=(cNs4?cTip:cTip.style);cO.top=cO.left=(cIe4&&!cOp?0:-10000)+(!cNs4?'px':0);layerWrite(lyrContent);wd=(cNs4?cTip.clip.width:cTip.offsetWidth);if(wd>(ww=windowWidth())){lyrContent=lyrContent.replace(/\ /g,' ');width=ww;wrap=0;}}
layerWrite(lyrContent);
if(wrap)width=(cNs4?cTip.clip.width:cTip.offsetWidth);}
runHook("ctCreatePopup",FAFTER,lyrContent);return true;}
function ctMouseMove(e){var sCT,sPo,l;e=(e)? e:event;if(e.pageX){cMx=e.pageX;cMy=e.pageY;}
else if(e.clientX){cMx=eval('e.clientX+cFrame.'+docRoot+'.scrollLeft');cMy=eval('e.clientY+cFrame.'+docRoot+'.scrollTop');}
if(cTip){sCT=cTip;sPo=po;l=document.popups;for(i=0;i<l.length;i++){cTip=l[i];po=cTip.pop;if(po.allowmove==1&&!po.sticknow||(po.scroll&&po.showingsticky))runHook("placeLayer",FREPLACE);}
po=sPo;cTip=sCT;if(po.HideForm)hideSelectBox();
if(po.hoveringSwitch&&!cNs4&&runHook("cursorOff",FREPLACE)){if(po.delayHide)hideDelay(po.delayHide,cTip);else cClick();po.hoveringSwitch=!po.hoveringSwitch;}}}
function ctMouseCapture(){capExtent=document;var fN,str='',l,k,f,wMv,sS,mseHandler=ctMouseMove;var re=/function\s+(\w*)\(/;wMv=(!cIe4&&window.onmousemove);if(document.onmousemove ||wMv){if(wMv)capExtent=window
f=capExtent.onmousemove.toString();fN=f.match(re);if(fN==null){str=f+'(e);';}else if(fN[1]=='anonymous'||fN[1]=='ctMouseMove'||(wMv&&fN[1]=='onmousemove')){if(!cOp&&wMv){l=f.indexOf('{')+1;k=f.lastIndexOf('}');sS=f.substring(l,k);if((l=sS.indexOf('('))!=-1){sS=sS.substring(0,l).replace(/^\s+/,'').replace(/\s+$/,'');if(ud(sS))window.onmousemove=null;else str=sS+'(e);';}}
if(!str){ctCheckMouseCapture=false;return;}
}else{if(fN[1])str=fN[1]+'(e);';else{l=f.indexOf('{')+1;k=f.lastIndexOf('}');str=f.substring(l,k);}}
str+='ctMouseMove(e);';mseHandler=new Function('e',str)}
capExtent.onmousemove=mseHandler;if(cNs4)capExtent.captureEvents(Event.MOUSEMOVE)}
function parseTokens(pf,ar){
var v,i,md=-1,par=(pf!='cd_');pf=(par)?'po.':pf,fnMark=(par&&!ar.length?1:0);for(i=0;i<ar.length;i++){if(md<0){
if(typeof ar[i]=='number'&&ar[i]>pmStart&&ar[i]<pmUpper){fnMark=(par?1:0);i--;}else{switch(pf){case 'cd_':
cd_text=unpack(ar[i]);break;default:
po.text=unpack(ar[i]);}}
md=0;}else{
if(ar[i]>=pmCount||ar[i]==DONOTHING||ar[i]==MULTI){continue;}
if(ar[i]==INARRAY){fnMark=0;opt_ARRAY(ar[i],ar[++i],(pf+'text'));continue;}
if(ar[i]==CAPARRAY){opt_ARRAY(ar[i],ar[++i],(pf+'cap'));continue;}
if(ar[i]==STICKY){if(pf!='cd_')eval(pf+'sticky=1');continue;}
if(ar[i]==BACKGROUND){eval(pf+'background="'+ar[++i]+'"');continue;}
if(ar[i]==NOCLOSE){if(pf!='cd_')opt_NOCLOSE();continue;}
if(ar[i]==CAPTION){eval(pf+"cap='"+escSglQuote(ar[++i])+"'");continue;}
if(ar[i]==CENTER||ar[i]==LEFT||ar[i]==RIGHT){eval(pf+'hpos='+ar[i]);continue;}
if(ar[i]==OFFSETX){eval(pf+'offsetx='+ar[++i]);continue;}
if(ar[i]==OFFSETY){eval(pf+'offsety='+ar[++i]);continue;}
if(ar[i]==FGCOLOR){eval(pf+'fgcolor="'+ar[++i]+'"');continue;}
if(ar[i]==BGCOLOR){eval(pf+'bgcolor="'+ar[++i]+'"');continue;}
if(ar[i]==TEXTCOLOR){eval(pf+'textcolor="'+ar[++i]+'"');continue;}
if(ar[i]==CAPCOLOR){eval(pf+'capcolor="'+ar[++i]+'"');continue;}
if(ar[i]==CLOSECOLOR){eval(pf+'closecolor="'+ar[++i]+'"');continue;}
if(ar[i]==WIDTH){eval(pf+'width='+ar[++i]);continue;}
if(ar[i]==BORDER){eval(pf+'border='+ar[++i]);continue;}
if(ar[i]==STATUS){eval(pf+"status='"+escSglQuote(ar[++i])+"'");continue;}
if(ar[i]==AUTOSTATUS){eval(pf+'autostatus=('+pf+'autostatus==1)?0:1');continue;}
if(ar[i]==AUTOSTATUSCAP){eval(pf+'autostatus=('+pf+'autostatus==2)?0:2');continue;}
if(ar[i]==HEIGHT){eval(pf+'height='+pf+'aboveheight='+ar[++i]);continue;}
if(ar[i]==CLOSETEXT){eval(pf+"close='"+escSglQuote(ar[++i])+"'");continue;}
if(ar[i]==SNAPX){eval(pf+'snapx='+ar[++i]);continue;}
if(ar[i]==SNAPY){eval(pf+'snapy='+ar[++i]);continue;}
if(ar[i]==FIXX){eval(pf+'fixx='+ar[++i]);continue;}
if(ar[i]==FIXY){eval(pf+'fixy='+ar[++i]);continue;}
if(ar[i]==RELX){eval(pf+'relx='+ar[++i]);continue;}
if(ar[i]==RELY){eval(pf+'rely='+ar[++i]);continue;}
if(ar[i]==FGBACKGROUND){eval(pf+'fgbackground="'+ar[++i]+'"');continue;}
if(ar[i]==BGBACKGROUND){eval(pf+'bgbackground="'+ar[++i]+'"');continue;}
if(ar[i]==PADX){eval(pf+'padxl='+ar[++i]);eval(pf+'padxr='+ar[++i]);continue;}
if(ar[i]==PADY){eval(pf+'padyt='+ar[++i]);eval(pf+'padyb='+ar[++i]);continue;}
if(ar[i]==FULLHTML){if(pf!='cd_')eval(pf+'fullhtml=1');continue;}
if(ar[i]==BELOW||ar[i]==ABOVE){eval(pf+'vpos='+ar[i]);continue;}
if(ar[i]==CAPICON){eval(pf+'capicon="'+ar[++i]+'"');continue;}
if(ar[i]==TEXTFONT){eval(pf+"textfont='"+escSglQuote(ar[++i])+"'");continue;}
if(ar[i]==CAPTIONFONT){eval(pf+"captionfont='"+escSglQuote(ar[++i])+"'");continue;}
if(ar[i]==CLOSEFONT){eval(pf+"closefont='"+escSglQuote(ar[++i])+"'");continue;}
if(ar[i]==TEXTSIZE){eval(pf+'textsize="'+ar[++i]+'"');continue;}
if(ar[i]==CAPTIONSIZE){eval(pf+'captionsize="'+ar[++i]+'"');continue;}
if(ar[i]==CLOSESIZE){eval(pf+'closesize="'+ar[++i]+'"');continue;}
if(ar[i]==TIMEOUT){eval(pf+'timeout='+ar[++i]);continue;}
if(ar[i]==FUNCTION){if(pf=='cd_'){if(typeof ar[i+1]!='number'){v=ar[++i];cd_function=(typeof v=='function'?v:null);}}else{fnMark=0;v=null;if(typeof ar[i+1]!='number')v=ar[++i]; opt_FUNCTION(v);} continue;}
if(ar[i]==DELAY){eval(pf+'delay='+ar[++i]);continue;}
if(ar[i]==HAUTO){eval(pf+'hauto=('+pf+'hauto==0)?1:0');continue;}
if(ar[i]==VAUTO){eval(pf+'vauto=('+pf+'vauto==0)?1:0');continue;}
if(ar[i]==CLOSECLICK){eval(pf+'closeclick=('+pf+'closeclick==0)?1:0');continue;}
if(ar[i]==WRAP){eval(pf+'wrap=('+pf+'wrap==0)?1:0');continue;}
if(ar[i]==FOLLOWMOUSE){eval(pf+'followmouse=('+pf+'followmouse==1)?0:1');continue;}
if(ar[i]==MOUSEOFF){eval(pf+'mouseoff=('+pf+'mouseoff==0)?1:0');v=ar[i+1];if(pf!='cd_'&&eval(pf+'mouseoff')&&typeof v=='number'&&(v<pmStart||v>pmUpper))eval(pf+'delayHide=ar[++i]');continue;}
if(ar[i]==CLOSETITLE){eval(pf+'closetitle="'+ar[++i]+'"');continue;}
if(ar[i]==PUID){if(typeof ar[i+1]!='number'){if(pf=='cd_'){eval(pf+'puid="'+ar[++i]+'"');}} continue;}
if(ar[i]==KEEPCTD){eval(pf+'keepctd=('+pf+'keepctd==0)?1:0');continue;}
if(ar[i]==CSSOFF||ar[i]==CSSCLASS){eval(pf+'css='+ar[i]);continue;}
if(ar[i]==COMPATMODE){eval(pf+'compatmode=('+pf+'compatmode==0)?1:0');continue;}
if(ar[i]==CAPALIGN){eval(pf+'capalign="'+ar[++i].toLowerCase()+'"');continue;}
if(ar[i]==TEXTALIGN){eval(pf+'textalign="'+ar[++i].toLowerCase()+'"');continue;}
if(ar[i]==CELLPAD){i=opt_MULTIPLEARGS(++i,ar,(pf+'cellpad'));continue;}
if(ar[i]==FGCLASS){eval(pf+'fgclass="'+ar[++i]+'"');continue;}
if(ar[i]==BGCLASS){eval(pf+'bgclass="'+ar[++i]+'"');continue;}
if(ar[i]==TEXTFONTCLASS){eval(pf+'textfontclass="'+ar[++i]+'"');continue;}
if(ar[i]==CAPTIONFONTCLASS){eval(pf+'captionfontclass="'+ar[++i]+'"');continue;}
if(ar[i]==CLOSEFONTCLASS){eval(pf+'closefontclass="'+ar[++i]+'"');continue;}
i=parseCmdLine(pf,i,ar);}}
if(par){var obj=eval(pf.substring(0,pf.length-1));with(obj){if(fnMark&&Function)text=callFunction(Function);if(wrap){width=0;var tReg=/<.*\n*>/ig;if(!tReg.test(text))text=text.replace(/[ ]+/g,'&nbsp;');if(!tReg.test(cap))cap=cap.replace(/[ ]+/g,'&nbsp;');}
if(sticky){if(!close&&(cFrame!=cd_frame))close=cd_close;if(mouseoff&&(cFrame==cd_frame))opt_NOCLOSE(' ');}}}}
function layerWrite(txt){var po=cTip.pop;if(po&&!po.doXml)txt+="\n";if(po&&po.doXml)cTip=resetNodeContents(txt,cTip);else if(cNs4){var lyr=cTip.document
lyr.write(txt)
lyr.close()
}else if(typeof cTip.innerHTML!=cUdf){if(cIe5&&isMac)cTip.innerHTML=''
cTip.innerHTML=txt}}
function showObject(obj){runHook("showObject",FBEFORE,obj);(cNs4?obj:obj.style).visibility='visible';obj.hasShown=1;runHook("showObject",FAFTER,obj);}
function hideObject(obj){runHook("hideObject",FBEFORE,obj);var po=obj.pop;var Obj=(cNs4?obj:obj.style);if(cNs6&&po&&po.ShowId>0){clearTimeout(po.ShowId);po.ShowId=0;}
Obj.visibility='hidden';Obj.top=Obj.left=(cIe4&&!cOp?0:-10000)+(!cNs4?'px':0);if(po){if(po.timerid>0)clearTimeout(po.timerid);if(po.delayid>0)clearTimeout(po.delayid);po.timerid=0;po.delayid=0;po.hoveringSwitch=false;}
self.status="";if(obj.onmouseout||obj.onmouseover){if(cNs4)obj.releaseEvents(Event.MOUSEOUT||Event.MOUSEOVER);obj.onmouseout=obj.onmouseover=null;}
runHook("hideObject",FAFTER,obj);deletePopup(obj);}
function repositionTo(obj,xL,yL){var Obj=(cNs4?obj:obj.style);Obj.left=xL+(!cNs4?'px':0);Obj.top=yL+(!cNs4?'px':0);}
function cursorOff(){var left,top,right,bottom;left=parseInt(cTip.style.left);top=parseInt(cTip.style.top);right=left+(po.shadow&&!isNaN(po.width)?parseInt(po.width):cTip.offsetWidth);bottom=top+(po.shadow&&!isNaN(po.aboveheight)?parseInt(po.aboveheight):cTip.offsetHeight);return(cMx<left||cMx>right||cMy<top||cMy>bottom);}
function opt_FUNCTION(callme){po.text=(callme?(typeof callme=='string'?(/.+\(.*\)/.test(callme)?eval(callme):callme):callme()):(po.Function?callFunction(po.Function):'No Function'));return 0;}
function opt_NOCLOSE(unused){if(!unused)po.close="";if(cNs4){cTip.captureEvents(Event.MOUSEOUT||Event.MOUSEOVER);cTip.onmouseout=function(e){cTip=e.target;po=cTip.pop;if(po.delayHide)hideDelay(po.delayHide,cTip);else cClick(e);}}
cTip.onmouseover=function(e){e=(e)?e:event;if(/mouseover/i.test(e.type)){cTip=this;po=cTip.pop;if(!cNs4)po.hoveringSwitch=true;if(po.timerid>0){clearTimeout(po.timerid);po.timerid=0;}
if(cNs4)return routeEvent(e);}}
return 0;}
function opt_MULTIPLEARGS(i,args,parameter){var k=i,l,re,pV,str='';for(k=i;k<args.length;k++){if(typeof args[k]=='number'&&args[k]>pmStart)break;str+=args[k]+',';}
if(str)str=str.replace(/,$/,'');k--;pV=(cNs4&&/cellpad/i.test(parameter))?str.split(',')[0]:str;eval(parameter+'="'+pV+'"');return k;}
function opt_ARRAY(cmd,cmd_value,parameter){var v=pms[cmd-pmStart-1],ar=(v=='inarray'?cd_texts[cmd_value]:(v=='caparray'?cd_caps[cmd_value]:''));return eval(parameter+'=ar');}
function nbspCleanup(){if(po&&po.wrap){po.text=po.text.replace(/&nbsp;/g,' ');po.cap=po.cap.replace(/&nbsp;/g,' ');}}
function escSglQuote(str){return unpack(str).replace(/'/g,"\\'");}
function ctLoadHandler(e){var re=/\w+\(.*\)[;\s]+/g,olre=/coolTip\(|nd\(|cClick\(/,fn,l,i;if(!cLoaded)cLoaded=1;if(window.removeEventListener&&e.eventPhase==3)window.removeEventListener("load",ctLoadHandler,false);else if(window.detachEvent){window.detachEvent("onload",ctLoadHandler);var fN=document.body.getAttribute('onload');if(fN){fN=fN.toString().match(re);if(fN&&fN.length){for(i=0;i<fN.length;i++){if(/anonymous/.test(fN[i]))continue;while((l=fN[i].search(/\)[;\s]+/))!=-1){fn=fN[i].substring(0,l+1);fN[i]=fN[i].substring(l+2);if(olre.test(fn))eval(fn);}}}}}}
function wrapStr(endWrap,fontSizeStr,whichString){var fontStr,fontColor,rtnVal,isClose=((whichString=='close')?1:0),hasDims=/[%\-a-z]+$/.test(fontSizeStr);fontSizeStr=(cNs4)?(!hasDims?fontSizeStr:'1'):fontSizeStr;if(endWrap)rtnVal=(hasDims&&!cNs4)?(isClose?'</span>':'</div>'):'</font>';else{fontStr='po.'+whichString+'font';fontColor='po.'+((whichString=='caption')? 'cap':whichString)+'color';rtnVal=(hasDims&&!cNs4)?(isClose?'<span style="font-family: '+quoteMultiNameFonts(eval(fontStr))+';color: '+eval(fontColor)+';font-size: '+fontSizeStr+';">':'<div style="font-family: '+quoteMultiNameFonts(eval(fontStr))+';color: '+eval(fontColor)+';font-size: '+fontSizeStr+';">'):'<font face="'+eval(fontStr)+'" color="'+eval(fontColor)+'" size="'+(parseInt(fontSizeStr)>7?'7':fontSizeStr)+'">';}
return rtnVal;}
function quoteMultiNameFonts(theFont){var v,pM=theFont.split(',');for(var i=0;i<pM.length;i++){v=pM[i];v=v.replace(/^\s+/,'').replace(/\s+$/,'');if(/\s/.test(v)&&!/['"]/.test(v)){v="\'"+v+"\'";pM[i]=v;}}
return pM.join();}
function isExclusive(args){return false;}
function hideDelay(time,lyr){var po=lyr.pop;if(lyr&&po){if(time&&!po.delay){if(po.timerid>0)clearTimeout(po.timerid);po.timerid=setTimeout("cClick('"+lyr.id+"')",(po.timeout=time));}}}
function setCellPadStr(parameter){var Str='',j=0,ary,top,bottom,left,right;Str+='padding: ';ary=parameter.replace(/\s+/g,'').split(',');switch(ary.length){case 2:
top=bottom=ary[j];left=right=ary[++j];break;case 3:
top=ary[j];left=right=ary[++j];bottom=ary[++j];break;case 4:
top=ary[j];right=ary[++j];bottom=ary[++j];left=ary[++j];break;}
Str+=((ary.length==1)?ary[0]+'px;':top+'px '+right+'px '+bottom+'px '+left+'px;');return Str;}
function divID(args){var theDiv='';for(var i=0;i<args.length;i++){if(typeof args[i]!='number'||args[i]!=PUID)continue;if(typeof args[i+1]=='number')continue;theDiv=args[i+1];break;}
return theDiv;}
function hasKeepCtD(a){if(typeof a=='object')if(hasCommand(0,a,KEEPCTD)>=0)return(po&&po.keepctd==0)?true:false;return(po&&po.keepctd)?true:false;}
function hasCommand(istrt,args,COMMAND){for(var i=istrt;i<args.length;i++){if(typeof args[i]=='number'&&args[i]==COMMAND)return i;}
return-1;}
function setPosition(obj){if(typeof obj=='object'){obj.sticknow=1;}
return 1;}
function unpack(txt){if(typeof decodeURLComponent!=cUdf)return decodeURIComponent(txt);else return unescape(txt);}
function initLayerObject(theDiv,isMultiple,frm){theDiv=(theDiv||'ctDiv');isMultiple=(isMultiple||!/ctDiv/.test(theDiv));isMultiple=(cNs6&&isMultiple);var lyr,obj;lyr=createDivContainer(theDiv,frm);lyr.hasShown=(isMultiple?1:0);lyr.pop=new PopObject(lyr);obj=(cNs4?lyr:lyr.style);if(obj)obj.zIndex=getNextZ(lyr);return lyr;}
function createDivContainer(id,frm,zVal){id=(id||'ctDiv'),frm=(frm||cFrame),zVal=(zVal||1000);var objRef,divContainer=fetchObjectLyr(id);if(divContainer==null){if(cNs4){divContainer=frm.document.layers[id]=new Layer(window.innerWidth,frm);objRef=divContainer;}else{var body=(cIe4?frm.document.all.tags('BODY')[0]:frm.document.getElementsByTagName("BODY")[0]);if(cIe4&&!document.getElementById){body.insertAdjacentHTML("beforeEnd",'<div id="'+id+'"></div>');divContainer=layerReference(id);}else{divContainer=frm.document.createElement("DIV");divContainer.id=id;body.appendChild(divContainer);}
objRef=divContainer.style;}
objRef.position='absolute';objRef.visibility='hidden';objRef.zIndex=zVal;objRef.top=objRef.left=(cIe4&&!cOp?0:-10000)+(!cNs4?'px':0);}
return divContainer;}
function horizontalPlacement(browserWidth,horizontalScrollAmount,widthFix){var placeX,rD=0,ofx,iwidth=browserWidth,winoffset=horizontalScrollAmount;
var parsedWidth=(po.shadow&&!isNaN(po.width))?parseInt(po.width):(cNs4?cTip.clip.width:cTip.offsetWidth);if(po.fixx>-1||po.relx!=null){
placeX=(po.relx!=null?( po.relx<0?winoffset+po.relx+iwidth-parsedWidth-widthFix:winoffset+po.relx):po.fixx);}else{
if(po.hauto==1){ofx=Math.abs(po.offsetx);if((cMx-winoffset)>(iwidth/2)&&cMx-winoffset+(parsedWidth+ofx)>(iwidth-widthFix)){po.hpos=LEFT;rD=1;}else if((cMx-ofx-parsedWidth)<winoffset){po.hpos=RIGHT;rD=1;}
if(rD)po.offsetx=Math.abs(cd_offsetx);}
if(po.hpos==CENTER||po.hpos==RIGHT){placeX=cMx+po.offsetx;if(po.hpos==CENTER)placeX-=(parsedWidth/2);if((placeX+parsedWidth)>(winoffset+iwidth-widthFix))placeX=iwidth+winoffset-parsedWidth-widthFix;if(placeX<winoffset)placeX=winoffset;}
if(po.hpos==LEFT){placeX=cMx-po.offsetx-parsedWidth;if(placeX<winoffset)placeX=winoffset;}
if(po.snapx>1){var snapping=placeX % po.snapx;if(po.hpos==LEFT){placeX=placeX-(po.snapx+snapping);}else{
placeX=placeX+(po.snapx-snapping);}
if(placeX<winoffset)placeX=winoffset;}}
return placeX;}
function verticalPlacement(browserHeight,verticalScrollAmount){var placeY,rD=0,ofy,iheight=browserHeight,scrolloffset=verticalScrollAmount;
var parsedHeight=(po.shadow&&!isNaN(po.aboveheight))?parseInt(po.aboveheight):(cNs4?cTip.clip.height:cTip.offsetHeight);if(po.fixy>-1||po.rely!=null){
placeY=(po.rely!=null?(po.rely<0?scrolloffset+po.rely+iheight-parsedHeight:scrolloffset+po.rely):po.fixy);}else{
if(po.vauto==1){ofy=Math.abs(po.offsety);if((cMy-scrolloffset)>(iheight/2)){if(cMy-scrolloffset+parsedHeight+ofy>iheight){po.vpos=ABOVE;rD=1;}
}else if(cMy-scrolloffset-ofy-parsedHeight<0){po.vpos=BELOW;rD=1;}
if(rD)po.offsety=Math.abs(cd_offsety);}
if(po.vpos==ABOVE){placeY=cMy-(parsedHeight+po.offsety);if(placeY<scrolloffset)placeY=scrolloffset;}else{
placeY=cMy+po.offsety;}
if(po.snapy>1){var snapping=placeY % po.snapy;if(parsedHeight>0&&po.vpos==ABOVE){placeY=placeY-(po.snapy+snapping);}else{placeY=placeY+(po.snapy-snapping);}
if(placeY<scrolloffset)placeY=scrolloffset;}}
return placeY;}
function placeLayer(){var placeX,placeY,winoffset,scrolloffset,iwidth,iheight,widthFix=0;if(cFrame.innerWidth)widthFix=20;iwidth=windowWidth();
winoffset=(cIe4)?eval('cFrame.'+docRoot+'.scrollLeft'):cFrame.pageXOffset;placeX=runHook('horizontalPlacement',FCHAIN,iwidth,winoffset,widthFix);if(cFrame.innerHeight)iheight=cFrame.innerHeight;else if(eval('cFrame.'+docRoot)&&eval("typeof cFrame."+docRoot+".clientHeight=='number'")&&eval('cFrame.'+docRoot+'.clientHeight'))
iheight=eval('cFrame.'+docRoot+'.clientHeight');
scrolloffset=(cIe4)?eval('cFrame.'+docRoot+'.scrollTop'):cFrame.pageYOffset;placeY=runHook('verticalPlacement',FCHAIN,iheight,scrolloffset);
repositionTo(cTip,placeX,placeY);}
function windowWidth(){var w;if(cFrame.innerWidth)w=cFrame.innerWidth;else if(eval('cFrame.'+docRoot)&&eval("typeof cFrame."+docRoot+".clientWidth=='number'")&&eval('cFrame.'+docRoot+'.clientWidth'))
w=eval('cFrame.'+docRoot+'.clientWidth');return w;}
function setRunTimeVariables(obj){if(typeof runTime!=cUdf&&runTime.length)
for(var k=0;k<runTime.length;k++)runTime[k](obj);}
function parseCmdLine(pf,i,args){if(typeof cmdLine!=cUdf&&cmdLine.length){for(var k=0;k<cmdLine.length;k++){var j=cmdLine[k](pf,i,args);if(j>-1){i=j;break;}}}
return i;}
function postParseChecks(pf,args){var rtnVal=true;if(typeof postParse!=cUdf&&postParse.length){for(var k=0;k<postParse.length;k++){if(postParse[k](pf,args))continue;rtnVal=false;break;}}
return rtnVal;}
function setDefaultVariables(xVar){if(xVar&&typeof xVar=='string'){var v,j,k,vN,val=xVar.split('|'),y,a;for(k=0;k<val.length;k++){v=val[k];vN=val[++k];if(v=='caption'){if(ud('cap'))cd_cap=(!vN)?'':'"'+vN+'"';continue;}
if(/autostatuscap|caparray|donothing|inarray|noclose/.test(v)){continue;}
if(/above|below/.test(v)){if(vN&&ud('vpos'))cd_vpos=eval(vN);continue;}
if(/closetext/.test(v)){if(ud('close'))cd_close=vN;continue;}
if(/cssoff|cssstyle|cssw3c|cssclass/.test(v)){if(vN&&ud('css'))cd_css=eval(vN);continue;}
if(/border|captionsize|closesize|textsize|width/.test(v)){if(ud(v))eval('cd_'+v+'="'+vN+'"');continue;}
if(/autostatus|function|relx|rely|frame/.test(v)){if(ud(v))eval('cd_'+v+'='+vN);continue;}
if(/padx|pady/.test(v)){a=vN.split(':');if(a.length==1)a[a.length++]=a[0];y=(v=='pady');for(j=0;j<2;j++)
if(ud(v+(j?(y?'b':'r'):(y?'t':'l'))))eval('cd_'+v+(j?(y?'b':'r'):(y?'t':'l'))+'='+a[j]);continue;}
if(v=='center'||/left|right/.test(v)){if(vN&&ud('hpos'))cd_hpos=eval(vN);continue;}
if(ud(v))eval('cd_'+v+'='+(!vN||isNaN(vN)?'"'+vN+'"':Number(vN)));}}}
function isFunction(fnRef){var rtn=true;if(typeof fnRef=='object'){for(var i=0;i<fnRef.length;i++){if(typeof fnRef[i]=='function')continue;rtn=false;break;}
}else if(typeof fnRef!='function')rtn=false;return rtn;}
function argToString(array,strtInd,argName){var jS=strtInd,aS='',ar=array;argName=(argName?argName:'ar');if(ar.length>jS){for(var k=jS;k<ar.length;k++)aS+=argName+'['+k+'], ';aS=aS.substring(0,aS.length-2);}
return aS;}
function reOrder(hookPt,fnRef,order){var newPt=new Array(),match,i,j;if(!order||typeof order==cUdf||typeof order=='number')return hookPt;if(typeof order=='function'){if(typeof fnRef=='object')newPt=newPt.concat(fnRef);else newPt[newPt.length++]=fnRef;for(i=0;i<hookPt.length;i++){match=false;if(typeof fnRef=='function'&&hookPt[i]==fnRef)continue;else{for(j=0;j<fnRef.length;j++)if(hookPt[i]==fnRef[j]){match=true;break;}}
if(!match)newPt[newPt.length++]=hookPt[i];}
newPt[newPt.length++]=order;}else if(typeof order=='object'){if(typeof fnRef=='object')newPt=newPt.concat(fnRef);else newPt[newPt.length++]=fnRef;for(j=0;j<hookPt.length;j++){match=false;if(typeof fnRef=='function'&&hookPt[j]==fnRef)continue;else{for(i=0;i<fnRef.length;i++)if(hookPt[j]==fnRef[i]){match=true;break;}}
if(!match)newPt[newPt.length++]=hookPt[j];}
hookPt=newPt;newPt.length=0;for(j=0;j<hookPt.length;j++){match=false;for(i=0;i<order.length;i++){if(hookPt[j]==order[i]){match=true;break;}}
if(!match)newPt[newPt.length++]=hookPt[j];}
newPt=newPt.concat(order);}
hookPt=newPt;return hookPt;}
function callFunction(f){return(typeof f=='function'?f():'No Function');}
function getNextZ(theLyr){var cObj,obj,zVal;if(typeof cZindex==cUdf){cObj=fetchObjectLyr('ctDiv');cZindex=(cObj)?(cNs4?cObj.zIndex:cObj.style.zIndex):1000;}
zVal=cZindex;if(typeof document.popups==cUdf){document.popups=new Array();document.popups[0]=theLyr;}else{var l=document.popups;if(l.length){obj=l[l.length-1];l[l.length++]=theLyr;zVal=parseInt((cNs4?obj:obj.style).zIndex)+1;}else l[l.length++]=theLyr;}
return zVal;}
function isCloseAllLyr(obj){var i,j,pLyr=parentLyrs,rV=0;if(pLyr.length){for(i=0;i<pLyr.length;i++){if(typeof pLyr[i].pop!=cUdf&&pLyr[i].pop.closeall&&obj==pLyr[i]){rV=1;break;}}}
return rV;}
function deletePopup(obj){var l=document.popups;if(typeof l!=cUdf){var tmpArr=new Array(),theRef;for(var i=0;i<l.length;i++){if(l[i]==obj)continue;theRef=(cNs4)?l[i]:l[i].style;if(isCloseAllLyr(obj))theRef.visibility='hidden';else tmpArr[tmpArr.length++]=l[i];}
document.popups=tmpArr;}}
function fetchObjectLyr(id){if(typeof id=='object')return(cNs4?id.target:id);else return(cNs4?cFrame.document.layers[id]:(document.all?cFrame.document.all[id]:cFrame.document.getElementById(id)));}
function registerCommands(cmdStr){if(typeof cmdStr=='string'){var pM=cmdStr.split(',');pms=pms.concat(pM);for(var i=0;i< pM.length;i++)
eval(pM[i].toUpperCase()+'='+pmCount++);}}
function registerNoParameterCommands(cmdStr){if(!cmdStr&&typeof cmdStr!='string')return;pmt=(!pmt)?cmdStr:pmt+','+cmdStr;}
function registerHook(fnHookTo,fnRef,hookType,optPm){var hookPt,last=typeof optPm;if(fnHookTo=='plgIn'||fnHookTo=='postParse')return;if(typeof hookPts[fnHookTo]==cUdf)hookPts[fnHookTo]=new FunctionReference();hookPt=hookPts[fnHookTo];if(hookType!=null){if(hookType==FREPLACE){hookPt.ovload=fnRef;if(/ctContent/.test(fnHookTo))hookPt.alt[pms[CSSOFF-1-pmStart]]=fnRef;}else if(hookType==FBEFORE||hookType==FAFTER){hookPt=(hookType==1?hookPt.before:hookPt.after);if(typeof fnRef=='object')hookPt=hookPt.concat(fnRef);else hookPt[hookPt.length++]=fnRef;if(optPm)hookPt=reOrder(hookPt,fnRef,optPm);}else if(hookType==FALTERNATE){if(last=='number')hookPt.alt[pms[optPm-1-pmStart]]=fnRef;}else if(hookType==FCHAIN){hookPt=hookPt.chain;if(typeof fnRef=='object')hookPt=hookPt.concat(fnRef);else hookPt[hookPt.length++]=fnRef;}}}
function registerRunTimeFunction(fn){if(isFunction(fn)){if(typeof fn=='object')runTime=runTime.concat(fn);else runTime[runTime.length++]=fn;}}
function registerCmdLineFunction(fn){if(isFunction(fn)){if(typeof fn=='object')cmdLine=cmdLine.concat(fn);else cmdLine[cmdLine.length++]=fn;}}
function registerPostParseFunction(fn){if(isFunction(fn)){if(typeof fn=='object')postParse=postParse.concat(fn);else postParse[postParse.length++]=fn;}}
function runHook(fnHookTo,hookType){var hookPt=hookPts[fnHookTo],k,reslt=null,optPm,arS,ar=arguments;if(hookType==FREPLACE){arS=argToString(ar,2);if(typeof hookPt==cUdf||!(hookPt=hookPt.ovload))reslt=eval(fnHookTo+'('+arS+')');else reslt=eval('hookPt('+arS+')');}else if(hookType==FBEFORE||hookType==FAFTER){if(typeof hookPt!=cUdf){hookPt=(hookType==1?hookPt.before:hookPt.after);if(hookPt.length){arS=argToString(ar,2);for(k=0;k<hookPt.length;k++)eval('hookPt[k]('+arS+')');}}
}else if(hookType==FALTERNATE){optPm=ar[2];arS=argToString(ar,3);if(typeof hookPt==cUdf||(hookPt=hookPt.alt[pms[optPm-1-pmStart]])==cUdf)reslt=eval(fnHookTo+'('+arS+')');else reslt=eval('hookPt('+arS+')');}else if(hookType==FCHAIN){arS=argToString(ar,2);hookPt=hookPt.chain;for(k=hookPt.length;k>0;k--)if((reslt=eval('hookPt[k-1]('+arS+')'))!=void(0))break;}
return reslt;}
function FunctionReference(){this.ovload=null;this.before=new Array();this.after=new Array();this.alt=new Array();this.chain=new Array();}
function Info(version,prerelease){this.version=version;this.prerelease=prerelease;
this.simpleversion=Math.round(this.version*100);this.major=parseInt(this.simpleversion/100);this.minor=parseInt(this.simpleversion/10)-this.major * 10;this.revision=parseInt(this.simpleversion)-this.major * 100-this.minor * 10;this.meets=meets;}
function meets(reqdVersion){return(!reqdVersion)?false:this.simpleversion>=Math.round(100*parseFloat(reqdVersion));}
function PopObject(lyr){var y,j,k,cmds=coreCmds.split(',');this.text=cd_text;this.cap=cd_cap;this.timerid=0;this.allowmove=0;this.delayid=0;this.closeall=0;this.hoveringSwitch=false;this.fnRef=''
this.showingsticky=0;this.removecounter=0;this.ShowId=(lyr.hasShown||(cTip&&/ctDiv/.test(cTip.id)&&cTip.hasShown))?0:-1;this.scroll=0;this.sticknow=0;this.doXml=0;this.HideForm=0;this.delayHide=0;this.aboveheight=cd_aboveheight;this.shadow=0;for(k=0;k<cmds.length;k++){if(cmds[k]=='caption'){continue;}
if(/autostatuscap|cssclass|donothing|inarray|caparray|noclose/.test(cmds[k])){continue;}
if(/above|below/.test(cmds[k])){this.vpos=cd_vpos;continue;}
if(/closetext/.test(cmds[k])){this.close=cd_close;continue;}
if(/cssoff/.test(cmds[k])){this.css=cd_css;continue;}
if(/function/.test(cmds[k])){this.Function=cd_function;continue;}
if(cmds[k]=='center'||/left|right/.test(cmds[k])){this.hpos=cd_hpos;continue;}
if(/padx|pady/.test(cmds[k])){y=(cmds[k]=='pady');for(j=0;j<2;j++)eval('this.'+cmds[k]+(j?(y?'b':'r'):(y?'t':'l'))+'=cd_'+cmds[k]+(j?(y?'b':'r'):(y?'t':'l')));continue;}
eval('this.'+cmds[k]+'=cd_'+cmds[k]);}}
registerHook("ctContentSimple",ctContentSimple,FALTERNATE,CSSOFF);registerHook("ctContentCaption",ctContentCaption,FALTERNATE,CSSOFF);registerHook("ctContentBackground",ctContentBackground,FALTERNATE,CSSOFF);registerHook("ctContentSimple",ctContentSimple,FALTERNATE,CSSCLASS);registerHook("ctContentCaption",ctContentCaption,FALTERNATE,CSSCLASS);registerHook("ctContentBackground",ctContentBackground,FALTERNATE,CSSCLASS);registerHook("hideObject",nbspCleanup,FAFTER);registerHook("horizontalPlacement",horizontalPlacement,FCHAIN);registerHook("verticalPlacement",verticalPlacement,FCHAIN);registerHook('setPosition',setPosition,FCHAIN);if(cNs4||(cIe5&&isMac)||cKq)cLoaded=1;registerNoParameterCommands('sticky,autostatus,autostatuscap,fullhtml,hauto,vauto,closeclick,wrap,followmouse,mouseoff,compatmode');
var ctCheckMouseCapture=true;if((cNs4||cNs6||cIe4))ctMouseCapture();
