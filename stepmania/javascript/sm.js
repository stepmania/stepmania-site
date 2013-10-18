// http://stackoverflow.com/questions/4817029/whats-the-best-way-to-detect-a-touch-screen-device-using-javascript
function is_touch_device() {
	return 'ontouchstart' in window // works on most browsers 
		|| 'onmsgesturechange' in window; // works on ie10
};

// http://paulirish.com/2011/requestanimationframe-for-smart-animating/
// http://my.opera.com/emoller/blog/2011/12/20/requestanimationframe-for-smart-er-animating
// requestAnimationFrame polyfill by Erik Möller
// fixes from Paul Irish and Tino Zijdel
// list-based fallback implementation by Jonas Finnemann Jensen

(function() {
	var lastTime = 0;
	var vendors = ['ms', 'moz', 'webkit', 'o'];
	for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
		window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
		window.cancelAnimationFrame = window[vendors[x]+'CancelAnimationFrame'] ||
			window[vendors[x]+'CancelRequestAnimationFrame'];
	}
	if (!window.requestAnimationFrame){
		var tid = null, cbs = [], nb = 0, ts = 0;
		var animate = function() {
			var i, clist = cbs, len = cbs.length;
			tid = null;
			ts = Date.now();
			cbs = [];
			nb += clist.length;
			for (i = 0; i < len; i++){
				if(clist[i])
				clist[i](ts);
			}
		};
		window.requestAnimationFrame = function(cb) {
			if (tid === null)
				tid = window.setTimeout(animate, Math.max(0, 20 + ts - Date.now()));
			return cbs.push(cb) + nb;
		};
		window.cancelAnimationFrame = function(id) {
			delete cbs[id - nb - 1];
		};
	}
}());

// object deep copy
function clone(destination, source) {
	for (var property in source) {
		if (typeof source[property] === "object" && source[property] !== null && destination[property]) {
			clone(destination[property], source[property]);
		} else {
			destination[property] = source[property];
		}
	}
}

jQuery.fn.exists = function(){ return this.length>0; }

var Renderer_2D = {
	init: function() {},
	resize: function() {}
}

var Renderer_WebGL = {
	init: function(state) {
		console.log("initializing webgl");
		var gl = state.context;
		gl.clearColor(1.0, 0.0, 1.0, 1.0);
		gl.clear(gl.COLOR_BUFFER_BIT);
	},
	resize: function(state, e) {
		console.log("resizin" + e);
	}
}

var StepMania = {
	main: function(state) {
		this.state = state;
		state.renderer.init(state);
		window.addEventListener("resize", this.resize.bind(this));
	},
	resize: function(e) {
		this.state.renderer.resize(this.state, e);
		console.log("got resize event");
	}
}

$(function(){
	var canvas = $("#smjs");
	if (canvas.exists) {
		console.log("Couldn't find the canvas! Something is really wrong here!");
		return;
	}
	var ctx = null;
	var modes = [
		"webgl",
		"webgl-experimental",
		"2d"
	];
	var renderers = {
		"2d": Renderer_2D,
		"webgl": Renderer_WebGL
	};
	var state = {
		mode: null,
		size: null,
		fullscreen: false,
		canvas: canvas[0],
		touch: is_touch_device()
	}
	for (var i = 0; i < modes.length; i++) {
		ctx = canvas[0].getContext(modes[i]);
		if (ctx) {
			if (modes[i] == "webgl-experimental") {
				state.mode = "webgl";
			} else {
				state.mode = modes[i];
			}
			break;
		}
	}
	state.context = ctx;
	state.renderer = renderers[state.mode];
	state.message = $("#smjs-message");

	StepMania.main(state);
});
