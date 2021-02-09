/*! modernizr 3.6.0 (Custom Build) | MIT *
 * https://new.modernizr.com/download/?-webp-setclasses !*/
(function(e, t, n) {
    function u(e, t) {
        return typeof e === t;
    }
    function a() {
        var e, t, n, s, a, f, l;
        for (var c in i) if (i.hasOwnProperty(c)) {
            e = [], t = i[c];
            if (t.name) {
                e.push(t.name.toLowerCase());
                if (t.options && t.options.aliases && t.options.aliases.length) for (n = 0; n < t.options.aliases.length; n++) e.push(t.options.aliases[n].toLowerCase());
            }
            s = u(t.fn, "function") ? t.fn() : t.fn;
            for (a = 0; a < e.length; a++) f = e[a], l = f.split("."), l.length === 1 ? o[l[0]] = s : (o[l[0]] && !(o[l[0]] instanceof Boolean) && (o[l[0]] = new Boolean(o[l[0]])), o[l[0]][l[1]] = s), r.push((s ? "" : "no-") + l.join("-"));
        }
    }
    function c(e) {
        var t = f.className, n = o._config.classPrefix || "";
        l && (t = t.baseVal);
        if (o._config.enableJSClass) {
            var r = new RegExp("(^|\\s)" + n + "no-js(\\s|$)");
            t = t.replace(r, "$1" + n + "js$2");
        }
        o._config.enableClasses && (e.length > 0 && (t += " " + n + e.join(" " + n)), l ? f.className.baseVal = t : f.className = t);
    }
    function p(e, t) {
        if (typeof e == "object") for (var n in e) h(e, n) && p(n, e[n]); else {
            e = e.toLowerCase();
            var r = e.split("."), i = o[r[0]];
            r.length === 2 && (i = i[r[1]]);
            if (typeof i != "undefined") return o;
            t = typeof t == "function" ? t() : t, r.length === 1 ? o[r[0]] = t : (o[r[0]] && !(o[r[0]] instanceof Boolean) && (o[r[0]] = new Boolean(o[r[0]])), o[r[0]][r[1]] = t), c([ (!t || t === !1 ? "no-" : "") + r.join("-") ]), o._trigger(e, t);
        }
        return o;
    }
    var r = [], i = [], s = {
        _version: "3.6.0",
        _config: {
            classPrefix: "",
            enableClasses: !0,
            enableJSClass: !0,
            usePrefixes: !0
        },
        _q: [],
        on: function(e, t) {
            var n = this;
            setTimeout(function() {
                t(n[e]);
            }, 0);
        },
        addTest: function(e, t, n) {
            i.push({
                name: e,
                fn: t,
                options: n
            });
        },
        addAsyncTest: function(e) {
            i.push({
                name: null,
                fn: e
            });
        }
    }, o = function() {};
    o.prototype = s, o = new o;
    var f = t.documentElement, l = f.nodeName.toLowerCase() === "svg", h;
    (function() {
        var e = {}.hasOwnProperty;
        !u(e, "undefined") && !u(e.call, "undefined") ? h = function(t, n) {
            return e.call(t, n);
        } : h = function(e, t) {
            return t in e && u(e.constructor.prototype[t], "undefined");
        };
    })(), s._l = {}, s.on = function(e, t) {
        this._l[e] || (this._l[e] = []), this._l[e].push(t), o.hasOwnProperty(e) && setTimeout(function() {
            o._trigger(e, o[e]);
        }, 0);
    }, s._trigger = function(e, t) {
        if (!this._l[e]) return;
        var n = this._l[e];
        setTimeout(function() {
            var e, r;
            for (e = 0; e < n.length; e++) r = n[e], r(t);
        }, 0), delete this._l[e];
    }, o._q.push(function() {
        s.addTest = p;
    }), o.addAsyncTest(function() {
        function n(e, t, n) {
            function i(t) {
                var i = t && t.type === "load" ? r.width === 1 : !1, s = e === "webp";
                p(e, s && i ? new Boolean(i) : i), n && n(t);
            }
            var r = new Image;
            r.onerror = i, r.onload = i, r.src = t;
        }
        var e = [ {
            uri: "data:image/webp;base64,UklGRiQAAABXRUJQVlA4IBgAAAAwAQCdASoBAAEAAwA0JaQAA3AA/vuUAAA=",
            name: "webp"
        }, {
            uri: "data:image/webp;base64,UklGRkoAAABXRUJQVlA4WAoAAAAQAAAAAAAAAAAAQUxQSAwAAAABBxAR/Q9ERP8DAABWUDggGAAAADABAJ0BKgEAAQADADQlpAADcAD++/1QAA==",
            name: "webp.alpha"
        }, {
            uri: "data:image/webp;base64,UklGRlIAAABXRUJQVlA4WAoAAAASAAAAAAAAAAAAQU5JTQYAAAD/////AABBTk1GJgAAAAAAAAAAAAAAAAAAAGQAAABWUDhMDQAAAC8AAAAQBxAREYiI/gcA",
            name: "webp.animation"
        }, {
            uri: "data:image/webp;base64,UklGRh4AAABXRUJQVlA4TBEAAAAvAAAAAAfQ//73v/+BiOh/AAA=",
            name: "webp.lossless"
        } ], t = e.shift();
        n(t.name, t.uri, function(t) {
            if (t && t.type === "load") for (var r = 0; r < e.length; r++) n(e[r].name, e[r].uri);
        });
    }), a(), c(r), delete s.addTest, delete s.addAsyncTest;
    for (var d = 0; d < o._q.length; d++) o._q[d]();
    e.Modernizr = o, define("modernizr-init", function() {});
})(window, document);;
