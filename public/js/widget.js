!function (t) {
    var e = {};

    function n(r) {
        if (e[r]) return e[r].exports;
        var o = e[r] = {i: r, l: !1, exports: {}};
        return t[r].call(o.exports, o, o.exports, n), o.l = !0, o.exports
    }

    n.m = t, n.c = e, n.d = function (t, e, r) {
        n.o(t, e) || Object.defineProperty(t, e, {enumerable: !0, get: r})
    }, n.r = function (t) {
        "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, {value: "Module"}), Object.defineProperty(t, "__esModule", {value: !0})
    }, n.t = function (t, e) {
        if (1 & e && (t = n(t)), 8 & e) return t;
        if (4 & e && "object" == typeof t && t && t.__esModule) return t;
        var r = Object.create(null);
        if (n.r(r), Object.defineProperty(r, "default", {
            enumerable: !0,
            value: t
        }), 2 & e && "string" != typeof t) for (var o in t) n.d(r, o, function (e) {
            return t[e]
        }.bind(null, o));
        return r
    }, n.n = function (t) {
        var e = t && t.__esModule ? function () {
            return t.default
        } : function () {
            return t
        };
        return n.d(e, "a", e), e
    }, n.o = function (t, e) {
        return Object.prototype.hasOwnProperty.call(t, e)
    }, n.p = "", n(n.s = 49)
}([function (t, e) {
    function n(t) {
        return (n = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (t) {
            return typeof t
        } : function (t) {
            return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
        })(t)
    }

    t.exports = "object" == ("undefined" == typeof window ? "undefined" : n(window)) && window && window.Math == Math ? window : "object" == ("undefined" == typeof self ? "undefined" : n(self)) && self && self.Math == Math ? self : Function("return this")()
}, function (t, e) {
    var n = {}.hasOwnProperty;
    t.exports = function (t, e) {
        return n.call(t, e)
    }
}, function (t, e) {
    t.exports = function (t) {
        try {
            return !!t()
        } catch (t) {
            return !0
        }
    }
}, function (t, e, n) {
    t.exports = !n(2)(function () {
        return 7 != Object.defineProperty({}, "a", {
            get: function () {
                return 7
            }
        }).a
    })
}, function (t, e) {
    function n(t) {
        return (n = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (t) {
            return typeof t
        } : function (t) {
            return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
        })(t)
    }

    t.exports = function (t) {
        return "object" === n(t) ? null !== t : "function" == typeof t
    }
}, function (t, e, n) {
    var r = n(21), o = n(18);
    t.exports = n(3) ? function (t, e, n) {
        return r.f(t, e, o(1, n))
    } : function (t, e, n) {
        return t[e] = n, t
    }
}, function (t, e, n) {
    var r = n(10), o = n(11);
    t.exports = function (t) {
        return r(o(t))
    }
}, function (t, e, n) {
    var r = n(0), o = n(5);
    t.exports = function (t, e) {
        try {
            o(r, t, e)
        } catch (n) {
            r[t] = e
        }
        return e
    }
}, function (t, e, n) {
    var r = n(0), o = n(7), i = "__core-js_shared__", c = r[i] || o(i, {});
    (t.exports = function (t, e) {
        return c[t] || (c[t] = void 0 !== e ? e : {})
    })("versions", []).push({
        version: "3.0.1",
        mode: n(37) ? "pure" : "global",
        copyright: "© 2019 Denis Pushkarev (zloirock.ru)"
    })
}, function (t, e, n) {
    var r = n(1), o = n(6), i = n(30)(!1), c = n(13);
    t.exports = function (t, e) {
        var n, a = o(t), s = 0, u = [];
        for (n in a) !r(c, n) && r(a, n) && u.push(n);
        for (; e.length > s;) r(a, n = e[s++]) && (~i(u, n) || u.push(n));
        return u
    }
}, function (t, e, n) {
    var r = n(2), o = n(29), i = "".split;
    t.exports = r(function () {
        return !Object("z").propertyIsEnumerable(0)
    }) ? function (t) {
        return "String" == o(t) ? i.call(t, "") : Object(t)
    } : Object
}, function (t, e) {
    t.exports = function (t) {
        if (null == t) throw TypeError("Can't call method on " + t);
        return t
    }
}, function (t, e) {
    var n = Math.ceil, r = Math.floor;
    t.exports = function (t) {
        return isNaN(t = +t) ? 0 : (0 < t ? r : n)(t)
    }
}, function (t, e) {
    t.exports = {}
}, function (t, e) {
    t.exports = ["constructor", "hasOwnProperty", "isPrototypeOf", "propertyIsEnumerable", "toLocaleString", "toString", "valueOf"]
}, function (t, e) {
    e.f = Object.getOwnPropertySymbols
}, function (t, e, n) {
    "use strict";
    var r = {}.propertyIsEnumerable, o = Object.getOwnPropertyDescriptor, i = o && !r.call({1: 2}, 1);
    e.f = i ? function (t) {
        var e = o(this, t);
        return !!e && e.enumerable
    } : r
}, function (t, e, n) {
    var r = n(3), o = n(16), i = n(18), c = n(6), a = n(19), s = n(1), u = n(20), f = Object.getOwnPropertyDescriptor;
    e.f = r ? f : function (t, e) {
        if (t = c(t), e = a(e, !0), u) try {
            return f(t, e)
        } catch (t) {
        }
        if (s(t, e)) return i(!o.f.call(t, e), t[e])
    }
}, function (t, e) {
    t.exports = function (t, e) {
        return {enumerable: !(1 & t), configurable: !(2 & t), writable: !(4 & t), value: e}
    }
}, function (t, e, n) {
    var r = n(4);
    t.exports = function (t, e) {
        if (!r(t)) return t;
        var n, o;
        if (e && "function" == typeof (n = t.toString) && !r(o = n.call(t))) return o;
        if ("function" == typeof (n = t.valueOf) && !r(o = n.call(t))) return o;
        if (!e && "function" == typeof (n = t.toString) && !r(o = n.call(t))) return o;
        throw TypeError("Can't convert object to primitive value")
    }
}, function (t, e, n) {
    t.exports = !n(3) && !n(2)(function () {
        return 7 != Object.defineProperty(n(35)("div"), "a", {
            get: function () {
                return 7
            }
        }).a
    })
}, function (t, e, n) {
    var r = n(3), o = n(20), i = n(22), c = n(19), a = Object.defineProperty;
    e.f = r ? a : function (t, e, n) {
        if (i(t), e = c(e, !0), i(n), o) try {
            return a(t, e, n)
        } catch (t) {
        }
        if ("get" in n || "set" in n) throw TypeError("Accessors not supported");
        return "value" in n && (t[e] = n.value), t
    }
}, function (t, e, n) {
    var r = n(4);
    t.exports = function (t) {
        if (!r(t)) throw TypeError(String(t) + " is not an object");
        return t
    }
}, function (t, e, n) {
    t.exports = n(8)("native-function-to-string", Function.toString)
}, function (t, e, n) {
    t.exports = n(25)
}, function (t, e, n) {
    n(26), t.exports = n(46).Object.assign
}, function (t, e, n) {
    var r = n(27);
    n(34)({target: "Object", stat: !0, forced: Object.assign !== r}, {assign: r})
}, function (t, e, n) {
    "use strict";
    var r = n(28), o = n(15), i = n(16), c = n(33), a = n(10), s = Object.assign;
    t.exports = !s || n(2)(function () {
        var t = {}, e = {}, n = Symbol(), o = "abcdefghijklmnopqrst";
        return t[n] = 7, o.split("").forEach(function (t) {
            e[t] = t
        }), 7 != s({}, t)[n] || r(s({}, e)).join("") != o
    }) ? function (t, e) {
        for (var n = c(t), s = arguments.length, u = 1, f = o.f, l = i.f; u < s;) for (var d, p = a(arguments[u++]), h = f ? r(p).concat(f(p)) : r(p), m = h.length, v = 0; v < m;) l.call(p, d = h[v++]) && (n[d] = p[d]);
        return n
    } : s
}, function (t, e, n) {
    var r = n(9), o = n(14);
    t.exports = Object.keys || function (t) {
        return r(t, o)
    }
}, function (t, e) {
    var n = {}.toString;
    t.exports = function (t) {
        return n.call(t).slice(8, -1)
    }
}, function (t, e, n) {
    var r = n(6), o = n(31), i = n(32);
    t.exports = function (t) {
        return function (e, n, c) {
            var a, s = r(e), u = o(s.length), f = i(c, u);
            if (t && n != n) {
                for (; f < u;) if ((a = s[f++]) != a) return !0
            } else for (; f < u; f++) if ((t || f in s) && s[f] === n) return t || f || 0;
            return !t && -1
        }
    }
}, function (t, e, n) {
    var r = n(12), o = Math.min;
    t.exports = function (t) {
        return 0 < t ? o(r(t), 9007199254740991) : 0
    }
}, function (t, e, n) {
    var r = n(12), o = Math.max, i = Math.min;
    t.exports = function (t, e) {
        var n = r(t);
        return n < 0 ? o(n + e, 0) : i(n, e)
    }
}, function (t, e, n) {
    var r = n(11);
    t.exports = function (t) {
        return Object(r(t))
    }
}, function (t, e, n) {
    function r(t) {
        return (r = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (t) {
            return typeof t
        } : function (t) {
            return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
        })(t)
    }

    var o = n(0), i = n(17).f, c = n(5), a = n(36), s = n(7), u = n(42), f = n(45);
    t.exports = function (t, e) {
        var n, l, d, p, h, m = t.target, v = t.global, y = t.stat;
        if (n = v ? o : y ? o[m] || s(m, {}) : (o[m] || {}).prototype) for (l in e) {
            if (p = e[l], d = t.noTargetGet ? (h = i(n, l)) && h.value : n[l], !f(v ? l : m + (y ? "." : "#") + l, t.forced) && void 0 !== d) {
                if (r(p) === r(d)) continue;
                u(p, d)
            }
            (t.sham || d && d.sham) && c(p, "sham", !0), a(n, l, p, t)
        }
    }
}, function (t, e, n) {
    var r = n(4), o = n(0).document, i = r(o) && r(o.createElement);
    t.exports = function (t) {
        return i ? o.createElement(t) : {}
    }
}, function (t, e, n) {
    var r = n(0), o = n(5), i = n(1), c = n(7), a = n(23), s = n(38), u = s.get, f = s.enforce,
        l = String(a).split("toString");
    n(8)("inspectSource", function (t) {
        return a.call(t)
    }), (t.exports = function (t, e, n, a) {
        var s = !!a && !!a.unsafe, u = !!a && !!a.enumerable, d = !!a && !!a.noTargetGet;
        "function" == typeof n && ("string" != typeof e || i(n, "name") || o(n, "name", e), f(n).source = l.join("string" == typeof e ? e : "")), t !== r ? (s ? !d && t[e] && (u = !0) : delete t[e], u ? t[e] = n : o(t, e, n)) : u ? t[e] = n : c(e, n)
    })(Function.prototype, "toString", function () {
        return "function" == typeof this && u(this).source || a.call(this)
    })
}, function (t, e) {
    t.exports = !1
}, function (t, e, n) {
    var r, o, i, c = n(39), a = n(4), s = n(5), u = n(1), f = n(40), l = n(13), d = n(0).WeakMap;
    if (c) {
        var p = new d, h = p.get, m = p.has, v = p.set;
        r = function (t, e) {
            return v.call(p, t, e), e
        }, o = function (t) {
            return h.call(p, t) || {}
        }, i = function (t) {
            return m.call(p, t)
        }
    } else {
        var y = f("state");
        l[y] = !0, r = function (t, e) {
            return s(t, y, e), e
        }, o = function (t) {
            return u(t, y) ? t[y] : {}
        }, i = function (t) {
            return u(t, y)
        }
    }
    t.exports = {
        set: r, get: o, has: i, enforce: function (t) {
            return i(t) ? o(t) : r(t, {})
        }, getterFor: function (t) {
            return function (e) {
                var n;
                if (!a(e) || (n = o(e)).type !== t) throw TypeError("Incompatible receiver, " + t + " required");
                return n
            }
        }
    }
}, function (t, e, n) {
    var r = n(23), o = n(0).WeakMap;
    t.exports = "function" == typeof o && /native code/.test(r.call(o))
}, function (t, e, n) {
    var r = n(8)("keys"), o = n(41);
    t.exports = function (t) {
        return r[t] || (r[t] = o(t))
    }
}, function (t, e) {
    var n = 0, r = Math.random();
    t.exports = function (t) {
        return "Symbol(".concat(void 0 === t ? "" : t, ")_", (++n + r).toString(36))
    }
}, function (t, e, n) {
    var r = n(1), o = n(43), i = n(17), c = n(21);
    t.exports = function (t, e) {
        for (var n = o(e), a = c.f, s = i.f, u = 0; u < n.length; u++) {
            var f = n[u];
            r(t, f) || a(t, f, s(e, f))
        }
    }
}, function (t, e, n) {
    var r = n(44), o = n(15), i = n(22), c = n(0).Reflect;
    t.exports = c && c.ownKeys || function (t) {
        var e = r.f(i(t)), n = o.f;
        return n ? e.concat(n(t)) : e
    }
}, function (t, e, n) {
    var r = n(9), o = n(14).concat("length", "prototype");
    e.f = Object.getOwnPropertyNames || function (t) {
        return r(t, o)
    }
}, function (t, e, n) {
    function r(t, e) {
        var n = a[c(t)];
        return n == u || n != s && ("function" == typeof e ? o(e) : !!e)
    }

    var o = n(2), i = /#|\.prototype\./, c = r.normalize = function (t) {
        return String(t).replace(i, ".").toLowerCase()
    }, a = r.data = {}, s = r.NATIVE = "N", u = r.POLYFILL = "P";
    t.exports = r
}, function (t, e, n) {
    t.exports = n(0)
}, function (t, e, n) {
    "use strict";
    (function (t) {
        Object.defineProperty(e, "__esModule", {value: !0});
        var n = function (t, e, n) {
            this.name = t, this.version = e, this.os = n
        };
        e.BrowserInfo = n;
        var r = function (e) {
            this.version = e, this.name = "node", this.os = t.platform
        };
        e.NodeInfo = r;
        var o = function () {
            this.bot = !0, this.name = "bot", this.version = null, this.os = null
        };
        e.BotInfo = o;
        var i = 3,
            c = [["aol", /AOLShield\/([0-9\._]+)/], ["edge", /Edge\/([0-9\._]+)/], ["yandexbrowser", /YaBrowser\/([0-9\._]+)/], ["vivaldi", /Vivaldi\/([0-9\.]+)/], ["kakaotalk", /KAKAOTALK\s([0-9\.]+)/], ["samsung", /SamsungBrowser\/([0-9\.]+)/], ["silk", /\bSilk\/([0-9._-]+)\b/], ["miui", /MiuiBrowser\/([0-9\.]+)$/], ["beaker", /BeakerBrowser\/([0-9\.]+)/], ["edge-chromium", /Edg\/([0-9\.]+)/], ["chrome", /(?!Chrom.*OPR)Chrom(?:e|ium)\/([0-9\.]+)(:?\s|$)/], ["phantomjs", /PhantomJS\/([0-9\.]+)(:?\s|$)/], ["crios", /CriOS\/([0-9\.]+)(:?\s|$)/], ["firefox", /Firefox\/([0-9\.]+)(?:\s|$)/], ["fxios", /FxiOS\/([0-9\.]+)/], ["opera-mini", /Opera Mini.*Version\/([0-9\.]+)/], ["opera", /Opera\/([0-9\.]+)(?:\s|$)/], ["opera", /OPR\/([0-9\.]+)(:?\s|$)/], ["ie", /Trident\/7\.0.*rv\:([0-9\.]+).*\).*Gecko$/], ["ie", /MSIE\s([0-9\.]+);.*Trident\/[4-7].0/], ["ie", /MSIE\s(7\.0)/], ["bb10", /BB10;\sTouch.*Version\/([0-9\.]+)/], ["android", /Android\s([0-9\.]+)/], ["ios", /Version\/([0-9\._]+).*Mobile.*Safari.*/], ["safari", /Version\/([0-9\._]+).*Safari/], ["facebook", /FBAV\/([0-9\.]+)/], ["instagram", /Instagram\s([0-9\.]+)/], ["ios-webview", /AppleWebKit\/([0-9\.]+).*Mobile/], ["ios-webview", /AppleWebKit\/([0-9\.]+).*Gecko\)$/], ["searchbot", /alexa|bot|crawl(er|ing)|facebookexternalhit|feedburner|google web preview|nagios|postrank|pingdom|slurp|spider|yahoo!|yandex/]],
            a = [["iOS", /iP(hone|od|ad)/], ["Android OS", /Android/], ["BlackBerry OS", /BlackBerry|BB10/], ["Windows Mobile", /IEMobile/], ["Amazon OS", /Kindle/], ["Windows 3.11", /Win16/], ["Windows 95", /(Windows 95)|(Win95)|(Windows_95)/], ["Windows 98", /(Windows 98)|(Win98)/], ["Windows 2000", /(Windows NT 5.0)|(Windows 2000)/], ["Windows XP", /(Windows NT 5.1)|(Windows XP)/], ["Windows Server 2003", /(Windows NT 5.2)/], ["Windows Vista", /(Windows NT 6.0)/], ["Windows 7", /(Windows NT 6.1)/], ["Windows 8", /(Windows NT 6.2)/], ["Windows 8.1", /(Windows NT 6.3)/], ["Windows 10", /(Windows NT 10.0)/], ["Windows ME", /Windows ME/], ["Open BSD", /OpenBSD/], ["Sun OS", /SunOS/], ["Chrome OS", /CrOS/], ["Linux", /(Linux)|(X11)/], ["Mac OS", /(Mac_PowerPC)|(Macintosh)/], ["QNX", /QNX/], ["BeOS", /BeOS/], ["OS/2", /OS\/2/], ["Search Bot", /(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(MSNBot)|(Ask Jeeves\/Teoma)|(ia_archiver)/]];

        function s(t) {
            var e = "" !== t && c.reduce(function (e, n) {
                var r = n[0], o = n[1];
                if (e) return e;
                var i = o.exec(t);
                return !!i && [r, i]
            }, !1);
            if (!e) return null;
            var r = e[0], a = e[1];
            if ("searchbot" === r) return new o;
            var s = a[1] && a[1].split(/[._]/).slice(0, 3);
            return s ? s.length < i && (s = s.concat(function (t) {
                for (var e = [], n = 0; n < t; n++) e.push("0");
                return e
            }(i - s.length))) : s = [], new n(r, s.join("."), u(t))
        }

        function u(t) {
            for (var e = 0, n = a.length; e < n; e++) {
                var r = a[e], o = r[0];
                if (r[1].test(t)) return o
            }
            return null
        }

        function f() {
            return void 0 !== t && t.version ? new r(t.version.slice(1)) : null
        }

        e.detect = function () {
            return "undefined" != typeof navigator ? s(navigator.userAgent) : f()
        }, e.parseUserAgent = s, e.detectOS = u, e.getNodeVersion = f
    }).call(this, n(48))
}, function (t, e) {
    var n, r, o = t.exports = {};

    function i() {
        throw new Error("setTimeout has not been defined")
    }

    function c() {
        throw new Error("clearTimeout has not been defined")
    }

    function a(t) {
        if (n === setTimeout) return setTimeout(t, 0);
        if ((n === i || !n) && setTimeout) return n = setTimeout, setTimeout(t, 0);
        try {
            return n(t, 0)
        } catch (e) {
            try {
                return n.call(null, t, 0)
            } catch (e) {
                return n.call(this, t, 0)
            }
        }
    }

    !function () {
        try {
            n = "function" == typeof setTimeout ? setTimeout : i
        } catch (t) {
            n = i
        }
        try {
            r = "function" == typeof clearTimeout ? clearTimeout : c
        } catch (t) {
            r = c
        }
    }();
    var s, u = [], f = !1, l = -1;

    function d() {
        f && s && (f = !1, s.length ? u = s.concat(u) : l = -1, u.length && p())
    }

    function p() {
        if (!f) {
            var t = a(d);
            f = !0;
            for (var e = u.length; e;) {
                for (s = u, u = []; ++l < e;) s && s[l].run();
                l = -1, e = u.length
            }
            s = null, f = !1, function (t) {
                if (r === clearTimeout) return clearTimeout(t);
                if ((r === c || !r) && clearTimeout) return r = clearTimeout, clearTimeout(t);
                try {
                    r(t)
                } catch (e) {
                    try {
                        return r.call(null, t)
                    } catch (e) {
                        return r.call(this, t)
                    }
                }
            }(t)
        }
    }

    function h(t, e) {
        this.fun = t, this.array = e
    }

    function m() {
    }

    o.nextTick = function (t) {
        var e = new Array(arguments.length - 1);
        if (1 < arguments.length) for (var n = 1; n < arguments.length; n++) e[n - 1] = arguments[n];
        u.push(new h(t, e)), 1 !== u.length || f || a(p)
    }, h.prototype.run = function () {
        this.fun.apply(null, this.array)
    }, o.title = "browser", o.browser = !0, o.env = {}, o.argv = [], o.version = "", o.versions = {}, o.on = m, o.addListener = m, o.once = m, o.off = m, o.removeListener = m, o.removeAllListeners = m, o.emit = m, o.prependListener = m, o.prependOnceListener = m, o.listeners = function (t) {
        return []
    }, o.binding = function (t) {
        throw new Error("process.binding is not supported")
    }, o.cwd = function () {
        return "/"
    }, o.chdir = function (t) {
        throw new Error("process.chdir is not supported")
    }, o.umask = function () {
        return 0
    }
}, function (t, e, n) {
    "use strict";
    n.r(e), n(24);
    var r = new function () {
            var t = "@ZZZ_RU_WJTC@";
            this.parse = function (e) {
                var n, r = e.substr(t.length), o = r.indexOf(";"), i = null;
                if (o < 0) n = r; else {
                    n = r.substr(0, o);
                    try {
                        i = JSON.parse(r.substr(o + 1))
                    } catch (e) {
                        console.error(e)
                    }
                }
                return {action: n, data: i}
            }, this.build = function (e, n) {
                var r = t;
                return r += e.toString(), n && (r += ";" + JSON.stringify(n)), r
            }, this.isMessage = function (e) {
                return 0 === e.toString().indexOf(t)
            }
        }, o = window.addEventListener ? "addEventListener" : "attachEvent",
        i = "attachEvent" == o ? "onmessage" : "message", c = window[o], a = n(47).detect, s = s || new function (t) {
            var e = this, n = ["init", "open", "close"], o = ["frameresize", "close", "select"];
            this.pc_width = 1024, this.config = {}, this.backgroundshade = null, this.container = null, this.scrollY = null, this._callback = null, this._onclose = null, this.viewport_container = null, this.iframe = null, this.c_zIndex = 9999999, this.iframe_site_mode = !1, this.ScriptPath = "https://lk.cse.ru/api/widget/index.html", this.storedparams = {}, this.detectWidth = function () {
                return t.innerWidth || document.documentElement.clientWidth || document.body.clientWidth
            };
            var s = 620, u = this.detectWidth(), f = 997 < parseInt(u, 10) ? 997 : u - 50;
            this.apiHandle = function (t, r) {
                if (!t) throw Error("API method required.");
                if (-1 === n.indexOf(t)) throw Error("Unsupported API method:" + t);
                try {
                    e[t](r)
                } catch (t) {
                    console.error(t)
                }
                return !1
            }, this.open = function (n) {
                e.viewport_container.style.display = "block", e.backgroundshade.style.display = "block", e.container.style.display = "block", e.scrollY = t.scrollY || document.documentElement.scrollTop, e.preload(n), e._callback = n.callback, e._onclose = n.onclose, e.resize()
            }, this.close = function () {
                if (e.viewport_container.style.display = "none", e.backgroundshade.style.display = "none", e.container.style.display = "none", null !== e.scrollY) try {
                    t.scrollTo({top: e.scrollY})
                } finally {
                    e.scrollY = null
                }
                e._onclose && "function" == typeof e._onclose && e._onclose()
            }, this.init = function (t) {
                var n, r;
                void 0 !== t && (n = t.token || t, r = t.city), e.checkBrowser(), e.backgroundshade = document.createElement("div"), Object.assign(e.backgroundshade.style, {
                    background: "#000",
                    opacity: "0.5",
                    position: "fixed",
                    left: "0",
                    top: "0",
                    bottom: "0",
                    right: "0",
                    display: "none",
                    zIndex: e.c_zIndex
                }), document.body.appendChild(e.backgroundshade), e.viewport_container = document.createElement("div"), Object.assign(e.viewport_container.style, {
                    position: "fixed",
                    left: "0",
                    top: "0",
                    bottom: "0",
                    right: "0",
                    display: "none",
                    overflow: "auto",
                    zIndex: e.c_zIndex + 1,
                    webkitOverflowScrolling: "touch"
                }), e.viewport_container.onclick = function () {
                    e.close()
                }, document.body.appendChild(e.viewport_container), e.container = document.createElement("div"), e.initial_container = e.container, Object.assign(e.container.style, {
                    height: s + "px",
                    width: f + "px",
                    position: "absolute",
                    backgroundColor: "#fff",
                    display: "none",
                    zIndex: parseInt(e.backgroundshade.style.zIndex) + 1,
                    boxSizing: "content-box"
                }), e.viewport_container.appendChild(e.container), e.iframe = document.createElement("iframe"), Object.assign(e.iframe.style, {
                    borderRadius: e.container.style.borderRadius,
                    border: "0",
                    borderCollapse: "collapse",
                    width: "100%",
                    height: "100%",
                    overflow: "hidden"
                }), e.container.appendChild(e.iframe), void 0 !== n && (e.ScriptPath = e.ScriptPath + "?token=" + n, void 0 !== r && (e.ScriptPath += "&city=" + r)), e.iframe.onload = function () {
                    "" != e.iframe.src && "about:blank" != e.iframe.src && e.fireLoadLevel()
                }, c("resize", e.resize)
            }, this.preload = function (t) {
                Object.assign(e.storedparams, {
                    sitemode: !1,
                    is_sender: "sender" === t.mode,
                    orderPointCityGuid: t.orderPointCityGuid,
                    orderPointText: t.orderPointText
                }), e.iframe.src || (e.iframe.src = e.ScriptPath)
            }, this.resize = function () {
                var n = e.detectWidth();
                if (f = 997 < n ? 997 : n - (e.iframe_site_mode ? 20 : 50), !e.iframe_site_mode && e.detectWidth() >= e.pc_width) {
                    var r = e.detectWidth(),
                        o = t.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
                        i = Math.max(0, (r - f) / 2), c = Math.max(0, (o - s) / 2);
                    i < 0 && (i = 0), c < 0 && (c = 0), e.container.style.left = i + "px", e.container.style.right = i + "px", e.container.style.top = c + "px", e.container.style.width = f + "px"
                } else e.container.style.width = "100%", e.container.style.height = "100%"
            }, this.frameresize = function (t) {
                s = t.h, e.container.style.height = s + "px", f = t.w, e.container.style.width = f + "px"
            }, this.select = function (t) {
                if (!e._callback && "function" != typeof e._callback) throw Error("callback undefined");
                e._callback(t), e.iframe && e.iframe.blur(), e.close()
            }, this.checkBrowser = function () {
                var t = a();
                if ("ie" === t.name && t.version < 9 || "opera" === t.name && t.version < 34 || "firefox" === t.name && t.version < 44 || "chrome" === t.name && t.version < 50 || "yandex" === t.name && t.version < 15 || "safari" === t.name && t.version < 8) throw Error("Unsupported browser version.");
                return t
            }, this.sendRawMsg = function (t) {
                e.iframe.contentWindow.postMessage(t, e.ScriptPath)
            }, this.fireLoadLevel = function () {
                c(i, e.onMessage, !1), e.sendRawMsg(r.build("init", {ref: t.location, params: e.storedparams}))
            }, this.onMessage = function (t) {
                var n = t[t.message ? "message" : "data"];
                if (!r.isMessage(n)) return !1;
                var i = r.parse(n);
                if (-1 === o.indexOf(i.action)) throw Error("Unsupported post message:" + i.action);
                try {
                    e[i.action](i.data)
                } catch (t) {
                    console.error(t)
                }
            };
            var l = t[t.CsePvzWidget].q;
            if (l) for (; 0 < l.length;) {
                var d = l.shift();
                this.apiHandle(d[0].toLowerCase(), d[1])
            }
            t[t.CsePvzWidget] = this.apiHandle, t[t.CsePvzWidget].config = this.config
        }(window)
}]);
