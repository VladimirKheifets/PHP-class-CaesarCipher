/*
	Demo PHP class CaesarCipher
	Version: 1.2.1, 2021-04-08
	Author: Vladimir Kheifets (kheifets.vladimir@online.de)	
	Copyright (c) 2021 Vladimir Kheifets All Rights Reserved
*/

start = function() 
{	
	ViewBlock = function (e){
		vblId =e.target.id		
		blId = vblId.substr(1);
		$blId = "#"+blId;
		$sblId = "#s"+blId;	
		disp=_($blId).display();		
		if(disp != __.dbl)
		{
			_($blId).display(__.dbl);			
			_($sblId).content("▲");	
			_("textarea").resize(10);					
			Ytarget =_(e.target).position().top;				
			window.scrollTo(0, Ytarget); 			
		}
		else
		{
			_($blId).display(__.dno);
			_($sblId).content("▼");			
		}
	}
		
	SubmitForm = function(e) {	
		id = e.target.id;
		if(__.ins("alphabet", id))
		{
			_("input").checked(0);				
			_("#key").selected(0);
		}	
		__.send();
	}; 
	
	SendtoModal = function(e){
		id = e.target.id;
		a = window.location.href;		
		t = "_"+id+"=1";		
		url=__.url(a,t);		
		attr={url:url,to:"#modal",func:viewModal,debug:1};		
		__.send(attr);		
	};	
	
	viewModal = function(rsp, to, req){
		_(to).modal(rsp);		
		if(__.ins("_la",req))
		{
			setLa = function(e){				
				la = e.target.id.substr(3);				
				_("form").attribute("action", "?la="+la);
				_().send();
			}
			_("span[id^='la_']").click(setLa);
		}
		else if(__.ins("_cookie=",req))
		{
			setCookie = function(e){				
				attr={url:"index.php?_cookie_p=1",to:"#cookie"};
				_().send(attr);				
				_().modal(0);				
			}			
			_("#cookie_p").click(setCookie);
		}
		else if(__.ins("_demo2",req))
		{			
			ffc=_(".ffc");
			ffc.attribute("readonly",true);
			ffc.resize(10);
			inp_text=_("#inp_text");
			inp_text.attribute("readonly",true);		
			_(to).position(__.pc);
		}			
	};
	
	_("#alphabet").change(SubmitForm);
	_("#key").change(SubmitForm);	
	_("input[type='radio']").change(SubmitForm);	
	_("#vbl1").click(ViewBlock);
	_("#vbl2").click(ViewBlock);	
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
__.load(start);