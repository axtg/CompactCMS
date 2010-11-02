 /**
 * Copyright (C) 2008 - 2010 by Xander Groesbeek (CompactCMS.nl)
 * 
 * Last changed: $LastChangedDate$
 * @author $Author$
 * @version $Revision$
 * @package CompactCMS.nl
 * @license GNU General Public License v3
 * 
 * This file is part of CompactCMS.
 * 
 * CompactCMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * CompactCMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * A reference to the original author of CompactCMS and its copyright
 * should be clearly visible AT ALL TIMES for the user of the back-
 * end. You are NOT allowed to remove any references to the original
 * author, communicating the product to be your own, without written
 * permission of the original copyright owner.
 * 
 * You should have received a copy of the GNU General Public License
 * along with CompactCMS. If not, see <http://www.gnu.org/licenses/>.
 * 
 * > Contact me for any inquiries.
 * > E: Xander@CompactCMS.nl
 * > W: http://community.CompactCMS.nl/forum
**/

window.addEvent('domready', function(){
	
	function editin_init() {
		$$('.liveedit').each(function(el) {
			
			el.addEvent('click',function() {
				el.set('class','liveedit2');
				var before = el.get('html').trim();
				el.set('html','');
				
				var input = new Element('textarea', { 'wrap':'soft', 'class':'textarea', 'text':before });
				
				input.addEvent('click', function (e) {
					e.stop();
					return;
				});
				
				input.addEvent('keydown', function(e) { if(e.key == 'enter') { this.fireEvent('blur'); } });
				input.inject(el).select();
				
				//add blur event to input
				input.addEvent('blur', function() {
					//get value, place it in original element
					val = input.get('value').trim();
					el.set('text',val);
					
					//save respective record
					var content = el.get('text');
					var request = new Request.HTML({
						url:'./includes/process.inc.php?action=liveedit&part='+el.get('rel'),
						method:'post',
						update: el,
						data: 'action=liveedit&id='+el.get('id')+'&content='+encodeURIComponent(content),
						onRequest: function() {
							el.set("html","<img src='./img/saving.gif' alt='Saving' />");
						},
						onComplete: function() {
							el.set("class","sprite-hover liveedit");
						}
					}).send();
				});
			});
		});
	}
	
	// Show list onload
	var dyn_list = $('dyn_list').empty().addClass('loading');

	var req = new Request.HTML({
		method: 'get',
		url: './includes/process.inc.php?action=update',
		update: dyn_list,
		onComplete: function() {
			// We're done, so hide loading class
			dyn_list.removeClass('loading');
			// Hide the create and menu contents
			$('menu_wrapper').slide('hide');
			$('form_wrapper').slide('hide');
			// Execute other functions
			editin_init();
			editPlace();
			externalLinks();
			renderList();
			doEditor();
		}
	}).send();
	
	/**
	*
	* Actions based on user clicks
	*
	*/
	// Process new page
	$('addForm').addEvent('submit', function(add) {
		new Event(add).stop();
		
		// Setting waiting style 
		var notify = $('notify_res').empty();
		var status = $('notify').addClass('loading');
		closeMenu();
		
		// Wait for response and act
		new Request.HTML({
			method: 'post',
			url: './includes/process.inc.php',
			update: notify,
			onComplete: function() {
				status.removeClass('loading');
				refreshContent();
			}
		}).post($('addForm'));
	});

	// Process delete page
	$('delete').addEvent('submit', function(remove) {
		var agree = confirm($('ad_msg01').value);
		
		if(agree) {
			closeMenu();
			new Event(remove).stop();
			
			//var url = this.href;
			var notify = $('notify_res').empty();
			var status = $('notify').addClass('loading');
			
			// Wait for response and act
			new Request.HTML({
				method: 'post',
				url: './includes/process.inc.php',
				update: notify,
				onComplete: function() {
					status.removeClass('loading');
					refreshContent();
				}
			}).post($('delete'));
		} else {
			new Event(remove).stop();
		}
	});
	
	// Process menu order preference
	$('menuForm').addEvent('submit', function(menu) {
		new Event(menu).stop();
		
		// Setting waiting style 
		var notify = $('notify_res').empty();
		var status = $('notify').addClass('loading');
		
		// Wait for response and act
		new Request.HTML({
			url: './includes/process.inc.php',
			method: 'post',
			update: notify,
			onComplete: function() {
				status.removeClass('loading');
				refreshContent();
			}
		}).post($('menuForm'));
	});

	/**
	*
	* Functions
	*
	*/
	// Render menu depth list
	function renderList() {
		var menudepth = $('menuFields').addClass('loading');
		
		new Request.HTML({
			method: 'get',
			url: './includes/process.inc.php?action=renderlist',
			update: menudepth,
			onComplete: function() {
				menudepth.removeClass('loading');
				isLink();
			}
		}).send();
	}

	// Change linkage preference
	function isLink() {
		$$('.islink').addEvent('click', function(islink) {  
	        var item_id	= this.id;
	        var cvalue	= this.checked;
	        var status	= $('notify').addClass('loading');
	        var islink	= $('td-islink-'+item_id).addClass('printloading');
			
	        new Request({
	            url:'./includes/process.inc.php?action=islink',
	            method:'post',
	            autoCancel:true,
	            data:'cvalue=' + cvalue + '&action=islink&id='+item_id,
	            onSuccess: function() {
					status.removeClass('loading');
					islink.removeClass('printloading');
	            },
	            onFailure: function() {
					$('notify').set('text','Undocumented error!');
				}
	        }).send();
	    });
	}
	
	// Refresh on print/publish update
	function refreshContent() {
		var dyn_list = $('dyn_list').empty().addClass('loading');
		var notify = $('notify_res');
		var status = $('notify').addClass('loading');
		
		notify.setStyle('border', 'none');
		
		new Request.HTML({
			method: 'get',
			url: './includes/process.inc.php?action=update',
			update: dyn_list,
			onComplete: function() {
				editin_init();
				editPlace();
				externalLinks();
				renderList();
				doEditor();
				dyn_list.removeClass('loading');
				status.removeClass('loading');
			}
		}).send();
	}
	
	// Change print or publish value
	function editPlace() {
		$$('.editinplace').addEvent('click', function(editinplace) {
			new Event(editinplace).stop();
			closeMenu();
			
			var url = './includes/process.inc.php?action=editinplace&id='+this.id+'&s='+this.rel;	
			var status = $(this.id).addClass('printloading');
			
			new Request.HTML({
				method: 'get',
				url: url,
				update: status,
				onComplete: function() {
					status.removeClass('printloading');
					refreshContent();
				}
			}).send();
		});	
	}
	
	// Apply editor window to all $$('.tabs') links
	function doEditor() {
		MUI.myChain = new Chain();
		MUI.myChain.chain(
			function(){MUI.Desktop.initialize();},
			function(){MUI.Dock.initialize();},
			function(){initializeWindows();}
		).callChain();	
	}

	/**
	*
	* Single calls
	*
	*/	
	// Fade non-focused divs
	$$('.container').each(function(container) {
		container.getChildren().each(function(child) {
			var siblings = child.getParent().getChildren().erase(child);
			child.addEvents({
				mouseenter: function() { siblings.tween('opacity',0.6); },
				mouseleave: function() { siblings.tween('opacity',1); }
			});
		});
	});
	
	// Toggle editor options
	var edtOpt = new Fx.Slide('editor-options');
	 
	$('f_mod').addEvent('change', function(e){
		e = new Event(e);
		e.stop();
		
		if(this.value!='editor') { 
			edtOpt.slideOut(); 
		} else { 
			edtOpt.slideIn();
		}
	});
	
	// Collapsible
	$$('.toggle').addEvent('click', function(e){
		e = new Event(e);
		e.stop();
		var target = new Fx.Slide(this.rel);
		target.toggle();
	});
	
	// Close only (menuDepth)
	function closeMenu() {
		var menuClose = new Fx.Slide('menu_wrapper');
		menuClose.slideOut();
	}
	
	// Tips links
	$$('span.ss_help').each(function(element,index) {  
		var content = element.get('title').split('::');  
		element.store('tip:title', content[0]);  
		element.store('tip:text', content[1]);  
	});  
  
	// Create the tooltips  
	var tipz = new Tips('.ss_help',{  
		className: 'ss_help',  
		fixed: true,  
		hideDelay: 50,  
		showDelay: 50  
	}); 

	/**
	*
	* Editor window preferences
	*
	*/
	initializeWindows = function(){
	
		// Examples
		MUI.editWindow = function(id,url,title){
			new MUI.Window({
				id: id+'_ccms',
				title: title,
				loadMethod: 'iframe',
				contentURL: url,
				width: 910,
				height: 640,
				padding: {top: 0, right:0, left:0, bottom:0},
				toolbar: false
			});
		};
		if ($$('a.tabs')) {
			$$('a.tabs').addEvent('click', function(e){
			new Event(e).stop();
				MUI.editWindow(this.id,this.href,this.rel);
			});
		}
		
		MUI.clockWindow = function(){
			new MUI.Window({
				id: 'clock',
				title: 'Clock',
				addClass: 'transparent',
				contentURL: '../lib/includes/js/plugins/clock.html',
				shape: 'gauge',
				headerHeight: 30,
				width: 160,
				height: 160,
				x: 10,
				y: 10,
				padding: { top: 0, right: 0, bottom: 0, left: 0 },
				require: {			
					js: ['../lib/includes/js/plugins/clock.js'],
					onload: function(){
						if (CoolClock) new CoolClock();
					}	
				}				
			});	
		};
		if ($$('.clock')){
			$$('.clock').addEvent('click', function(e){	
				new Event(e).stop();
				MUI.clockWindow();
			});
		}	
	
		// Deactivate menu header links
		$$('a.returnFalse').each(function(el) {
			el.addEvent('click', function(e) {
				new Event(e).stop();
			});
		});
		
		// Build windows onLoad
		//MUI.myChain.callChain();
	};
});

// External links script (rel=external)
function externalLinks() {
	if (!document.getElementsByTagName) return;
	var anchors = document.getElementsByTagName("a");
	
	for (var i=0; i<anchors.length; i++) {
   	var anchor = anchors[i];
		if (anchor.getAttribute("href") &&
			anchor.getAttribute("class") == "external")
			anchor.target = "_blank";
	}
}
window.onload = externalLinks;