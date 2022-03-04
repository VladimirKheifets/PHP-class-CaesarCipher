/*
	Demo PHP class CaesarCipher
	Version: 1.2.3, 2021-04-29
	Author: Vladimir Kheifets (kheifets.vladimir@online.de)
	Copyright (c) 2021 Vladimir Kheifets All Rights Reserved
*/
start = function()
{
	ViewBlock = function (){
		vblId = this.id;
		blId = vblId.substr(1);
		bl = _("#"+blId);
		sbl = _("#s"+blId);
		if(bl.ishide())
		{
			bl.show();
			sbl.content("▲");
			_("textarea").resize(10);
			Ytarget = _(this).position().top;
 			__.scroll(Ytarget);
		}
		else
		{
			bl.hide();
			sbl.content("▼");
		}
	}

	SubmitForm = function() {
		id=this.id;
		if(__.ins("alphabet", id))
		{
			_("input").checked(0);
			_("#key").selected(0);
		}
		__.send();
	};

	SendtoModal = function(){
		id=this.id;
		get = "_"+id+"=1";
		url=__.url(get);
		attr={url:url,to:"#modal",func:viewModal};
		__.send(attr);
	};

	viewModal = function(rsp, to, req){
		_(to).modal(rsp);
		if(__.ins("_la",req))
		{
			setLa = function(){
				la = this.id.substr(3);
				_("form").attr("action", "?la="+la);
				__.send();
			}
			_("span[id^='la_']").click(setLa);
		}
		else if(__.ins("_cookie=",req))
		{
			setCookie = function(e){
				attr={url:"index.php?_cookie_p=1",to:"#cookie"};
				__.send(attr);
				__.modal(0);
			}
			_("#cookie_p").click(setCookie);
		}
		else if(__.ins("_demo2",req))
		{
			ffc=_(".ffc");
			ffc.attr("readonly",true);
			ffc.resize(10);
			inp_text=_("#inp_text");
			inp_text.attr("readonly",true);
			_(to).position(__.pc);
		}
	};

	_("#alphabet").change(SubmitForm);
	_("#key").change(SubmitForm);
	_("input[type='radio']").change(SubmitForm);
	_("div[id^='vbl']").click(ViewBlock);
	_("div[id^='bl']").each(function(el){el.hide();});
	_("#cookie").click(SendtoModal);
	_("#la").click(SendtoModal);
	_("#demo2").click(SendtoModal);
	_("textarea").attribute("readonly",true);
	_(".content textarea").resize(10);

	ch_resize = function(){_(".content textarea").resize(10)};
	ch_orient = function(){__.reload()};
	__.change(ch_resize, ch_orient);
	__.modal();
	__.scroll();
}
__.ready(start);