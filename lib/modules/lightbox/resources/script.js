/* ImageZoom 1.2 */

	function initImageZoom(_options) {
		var options = $extend({
			rel: 'imagezoom'
		}, _options || {});
		var elements = $$(document.links).filter(function(el) {
			if ((el.rel) && (el.rel.indexOf(options.rel) != -1)) 
				return true;
			else
				return false;
		});
		for (var i = 0; i < elements.length; i++) {
			var el = elements[i];
			el.addEvent("click", function() {
				this.blur();
				var sEl = this;
				var imgCap = "";
				if (this.getElements("img").length > 0)
					sEl = this.getElements("img")[0];
				if ((sEl.alt) && (sEl.alt != ""))
					imgCap = sEl.alt;
				else if (sEl.title)
					imgCap = sEl.title;
				else if (sEl.parentNode.title)
					imgCap = sEl.parentNode.title;
				var _options = $extend({
					image: this.href,
					caption: imgCap,
					startElement: sEl
				}, options || {});
				_options.image = this.href;
				_options.caption = imgCap;
				var imagezoom = new Imagezoom(_options);
				imagezoom.preloadImage();
				imagezoom.show();
				return false;
			});
		}
	}
	
	var Imagezoom = function(_options) {		
		var options = $extend({
			image: false,
			caption: "",
			enableCaptions: true,
			startElement: false,
			x: 10,
			y: 10,
			initWidth: 50,
			initHeight: 50,
			draggable: true,
			loadImage: "imagezoom/images/loading.gif",
			loadDelay: 150,
			duration: 800,
			closeDuration: 500,
			transition: Fx.Transitions.Cubic.easeOut,
			startOpacity: 0.6,
			closeText: 'Close',
			rel: 'imagezoom',
			showCaptionBar: true,
			overlay: false,
			overlayColor: "#000",
			overlayOpacity: .75
		}, _options || {});
		
		var box = document.createElement("div");		
		var instance = this;
		
		/* shadow divs */
		var tl = document.createElement("div");
		tl.className = "s s_tl";
		var tr = document.createElement("div");
		tr.className = "s s_tr";
		var bl = document.createElement("div");
		bl.className = "s s_bl";
		var br = document.createElement("div");
		br.className = "s s_br";
		var top = document.createElement("div");
		top.className = "s s_top";
		var bottom = document.createElement("div");
		bottom.className = "s s_bottom";
		var left = document.createElement("div");
		left.className = "s s_left";
		var right = document.createElement("div");
		right.className = "s s_right";
		
		this.preloadImage = function() {
			if (options.image != false) {
				var img = new Image();
				img.src = options.image;
				img.style.visibility = "hidden";
				img.style.position = "absolute";
				img.style.top = "-9999999999px";
				img.setAttribute("id", "imagezoom-" + options.image);
				$$('body')[0].appendChild(img);
			}	
		}
		
		this.getImage = function() {
			if (($('imagezoom-' + options.image)) && ($('imagezoom-' + options.image).width != "0")) {
				var img = $('imagezoom-' + options.image).clone();
				img.setAttribute("id", "");
				img.style.position = "relative";
				img.style.top = "0px";
				img.style.visibility = "visible";
			} else {
				instance.preloadImage();
				window.setTimeout(function() {
					instance.getImage();
				}, 50);
			}
			return img;
		}		
		
		this.show = function() {
			if (options.image != false) {
				box.style.position = "absolute";
				box.style.overflow = "hidden";
				box.setAttribute("id", "imagezoom-open-" + options.image);
				
				if (options.startElement != false)
					options.startElement.blur();
				
				var x = options.x;
				var y = options.y;
				var boxWidth = options.initWidth;
				var boxHeight = options.initHeight;
				if (options.startElement != false) {
					x = options.startElement.getPosition().x;
					y = options.startElement.getPosition().y;
					boxWidth = options.startElement.offsetWidth;
					boxHeight = options.startElement.offsetHeight;
				}
				box.style.left = x + "px";
				box.style.top = y + "px";
				box.style.width = boxWidth + "px";
				box.style.height = boxHeight + "px";
				
				var fx = new Fx.Morph(box);
				fx.set({opacity: options.startOpacity});
				
				box.className = "imagezoom";
				$$('body')[0].appendChild(box);
				box.style.cursor = "pointer";
				box.addEvent("click", function() {
					var fx = new Fx.Morph(box, {duration: 200});
					fx.start({opacity: 0}).chain(function() {
						$$('body')[0].removeChild(box);
					});
				});

				this.loadImage();
			}
		}
		
		this.loadImage = function() {
			if (box.getElements(".loading").length == 0) {
				var loading = new Image();
				loading.src = options.loadImage;
				loading.className = "loading";
				box.appendChild(loading);
			}	
			if ($('imagezoom-' + options.image)) {
				var el = $('imagezoom-' + options.image);
				if (el.width != "0") {
					var newEl = new Image();
					newEl.src = options.image;
					window.setTimeout(function() { instance.insertImage(newEl) }, options.loadDelay);
				} else {
					window.setTimeout(function() { instance.loadImage(); }, 50);
				}
			} else {
				instance.preloadImage();
				window.setTimeout(function() { instance.loadImage(); }, 50);
			}
		}
		
		this.insertImage = function(img) {
			box.removeEvents("click");
			box.style.cursor = "default";
			box.style.overflow = "visible";
			var w = img.width;
			var h = img.height;
			img.style.width = w + "px";
			img.style.height = h + "px";
			img.className = 'image';
			var ptop = (window.getSize().y / 2) + window.getScroll().y - (h/2);
			var pleft = (window.getSize().x / 2) + window.getScroll().x - (w/2);
			var fx = new Fx.Morph(box, {duration: options.duration, transition: options.transition});
			fx.start({
				top: ptop,
				left: pleft,
				width: w,
				height: h,
				opacity: 1
			}).chain(function() {
				if (options.overlay == true) {
					if (!$('imagezoom_overlay')) {
						var overlay = $(document.createElement("div"));
						overlay.setAttribute("id", "imagezoom_overlay");
						overlay.style.backgroundColor = options.overlayColor;
						overlay.setOpacity(0);
						$$('body')[0].appendChild(overlay);
					} else {
						var overlay = $('imagezoom_overlay');
					}
					overlay.style.width = window.getScrollSize().x + "px";
					overlay.style.height = window.getScrollSize().y + "px";
					var overlayfx = new Fx.Morph(overlay, {duration: 600});
					overlayfx.start({
						opacity: options.overlayOpacity
					});
				}
				var close = $(document.createElement("div"));
				close.innerHTML = "<span>" + options.closeText + "</span>";
				close.className = "close";
				close.addEvent("click", function() {
					instance.close(true);
				});
				var loading = box.getElements(".loading");
				if (loading.length > 0)
					box.removeChild(loading[0]);
				var elements = [close, tl, tr, bl, br, top, bottom, left, right, img];
				for (var i = 0; i < elements.length; i++) {
					var elFx = new Fx.Morph(elements[i], {duration: 600});
					elFx.set({opacity: 0});
					box.adopt(elements[i]);
					elFx.start({opacity: 1});
				}
				var caption;
				if ((options.caption != "") && (options.enableCaptions == true)) {
					caption = document.createElement("div");
					caption.className = "caption";
					caption.innerHTML = "<p>" + options.caption + "</p>";
					box.appendChild(caption);
				}
				instance.addSetNavigation();
				if (box.getElements(".caption").length > 0) {
					caption = box.getElements(".caption")[0];
					var cfx = new Fx.Morph(caption, {duration: 200});
					cfx.set({opacity: 0});
					if (options.showCaptionBar == true) {
						caption.className += " visibleCaption";
						var cStartFx = new Fx.Morph(caption, {duration: 600});
						cStartFx.start({
							opacity: 1
						});
					}
					box.addEvent("mouseenter", function() {
						cfx.start({opacity: 1}).chain(function() { caption.className += " visibleCaption"; });
					});
					box.addEvent("mouseleave", function() {
						cfx.start({opacity: 0}).chain(function() { caption.className = caption.className.replace(/visibleCaption/g, ""); });
					});
					close.addEvent("mouseenter", function() {
						cfx.start({opacity: 0}).chain(function() { caption.className = caption.className.replace(/visibleCaption/g, ""); });
					});
					box.getElements(".image")[0].addEvent("click", function() {
						var action = "show";
						if (caption.className.indexOf("visibleCaption") != -1)
							action = "hide";
						if (action == "show")
							cfx.start({opacity: 1}).chain(function() { caption.className += " visibleCaption"; });
						else
							cfx.start({opacity: 0}).chain(function() { caption.className = caption.className.replace(/visibleCaption/g, ""); });
					});				
				}
				top.style.width = box.offsetWidth + "px";
				bottom.style.width = box.offsetWidth + "px";
				left.style.height = box.offsetHeight + "px";
				right.style.height = box.offsetHeight + "px";
				if (options.draggable == true)
					var move = new Drag.Move(box, {handle: img});
			});		
		}
		
		this.addSetNavigation = function() {
			var links = $$(document.links).filter(function(link) {
				if ((link.rel) && (link.rel.indexOf(options.rel) != -1))
					return true;
				else
					return false;
			});
			var set = false;
			for (var i = 0; i < links.length; i++) {
				if ((links[i].href.indexOf(options.image) != -1) && (links[i].rel) && (links[i].rel.indexOf(options.rel + '[' != -1))) {
					var rel = links[i].getAttribute("rel");
					set = instance.scanRel("after", options.rel + "[", this.scanRel("before", "]", rel));
				}
			}
			if (set != false) {
				var prevLink = false;
				var nextLink = false;
				var setLinks = new Array();
				for (i = 0; i < links.length; i++) {
					if (links[i].rel.indexOf(options.rel + "[" + set + "]") != -1) {
						setLinks[setLinks.length] = links[i];
					}
				}
				for (i = 0; i < setLinks.length; i++) {
					var link = setLinks[i];
					if ((link.href.indexOf(options.image) != -1) && (link.rel) && (link.rel.indexOf(options.rel != -1))) {
						if (i != 0)
							prevLink = setLinks[i - 1];
						if (i != setLinks.length - 1)
							nextLink = setLinks[i + 1];
					}
				}
				if ((prevLink != false) || (nextLink != false)) {
					if (box.getElements(".caption").length == 0) {
						var caption = document.createElement("div");
						caption.className = "caption";
						box.appendChild(caption);
					} else {
						var caption = box.getElements(".caption")[0];
					}
				}
				if (prevLink != false) {
					var previousButton = $(document.createElement("div"));
					previousButton.className = "previous";
					var prevCap = '';
					if (prevLink.title)
						prevCap = prevLink.title;
					var prevEl = prevLink;
					if (prevLink.getElements("img").length > 0)
						prevEl = prevLink.getElements("img")[0];
					previousButton.addEvent("click", function() {
						var newOptions = $unlink(options);
						var imagezoomPrev = new Imagezoom($extend(newOptions, {
							image: prevLink.href,
							caption: prevCap,
							rel: options.rel,
							startElement: prevEl,
							showCaptionBar: true
						}));
						instance.close();
						imagezoomPrev.show();
					});
					caption.appendChild(previousButton);
				}
				if (nextLink != false) {
					var nextButton = $(document.createElement("div"));
					nextButton.className = "next";
					var nextCap = '';
					if (nextLink.title)
						nextCap = nextLink.title;
					var nextEl = nextLink;
					if (nextLink.getElements("img").length > 0)
						nextEl = nextLink.getElements("img")[0];
					nextButton.addEvent("click", function() {
						var newOptions = $unlink(options);
						var imagezoomNext = new Imagezoom($extend(newOptions, {
							image: nextLink.href,
							caption: nextCap,
							rel: options.rel,
							startElement: nextEl,
							showCaptionBar: true
						}));
						instance.close();
						imagezoomNext.show();
					});
					caption.appendChild(nextButton);					
				}
			}
		}
		
		this.scanRel = function(where, needle, string) {
			var newstring = '';
			if (where == "after") {
				var startpos = string.indexOf(needle) + needle.length;
				var endpos = string.length;
			} else if (where == "before") {
				var startpos = 0;
				var endpos = string.indexOf(needle);
			}
			for (var i = startpos; i < endpos; i++) {
				newstring += string.charAt(i);
			}
			return newstring;
		}
		
		this.close = function(hideOverlay) {
			var img = box.getElements(".image")[0];
			box.removeChild(img);
			var close = box.getElements(".close")[0];
			box.removeChild(close);
			var caption = box.getElements(".caption");
			if (caption.length > 0)
				box.removeChild(caption[0]);
			var s = box.getElements(".s");
			for (var i = 0; i < s.length; i++)
				box.removeChild(s[i]);
			var x = options.x;
			var y = options.y;
			var boxWidth = options.initWidth;
			var boxHeight = options.initHeight;
			if (options.startElement != false) {
				x = options.startElement.getPosition().x;
				y = options.startElement.getPosition().y;
				boxWidth = options.startElement.offsetWidth;
				boxHeight = options.startElement.offsetHeight;
			}
			if ((hideOverlay == true) && ($('imagezoom_overlay'))) {
				var oFx = new Fx.Morph($('imagezoom_overlay'), {duration: options.closeDuration});
				oFx.start({opacity: 0}).chain(function() {
					$$('body')[0].removeChild($('imagezoom_overlay'));
				});
			}
			var fx = new Fx.Morph(box, {duration: options.closeDuration});
			fx.start({
				left: x,
				top: y,
				width: boxWidth,
				height: boxHeight,
				opacity: options.startOpacity
			}).chain(function() {
				fx.start({
					opacity: 0
				}).chain(function() {
					$$('body')[0].removeChild(box);
				});
			});
		}	
	}
