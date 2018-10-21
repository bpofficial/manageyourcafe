
(function() {
    var a, b, c, d, e, f = function(a, b) {
            return function() {
                return a.apply(b, arguments)
            }
        },
        g = [].indexOf || function(a) {
            for (var b = 0, c = this.length; c > b; b++)
                if (b in this && this[b] === a) return b;
            return -1
        };
    b = function() {
        function a() {}
        return a.prototype.extend = function(a, b) {
            var c, d;
            for (c in b) d = b[c], null == a[c] && (a[c] = d);
            return a
        }, a.prototype.isMobile = function(a) {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(a)
        }, a.prototype.createEvent = function(a, b, c, d) {
            var e;
            return null == b && (b = !1), null == c && (c = !1), null == d && (d = null), null != document.createEvent ? (e = document.createEvent("CustomEvent"), e.initCustomEvent(a, b, c, d)) : null != document.createEventObject ? (e = document.createEventObject(), e.eventType = a) : e.eventName = a, e
        }, a.prototype.emitEvent = function(a, b) {
            return null != a.dispatchEvent ? a.dispatchEvent(b) : b in (null != a) ? a[b]() : "on" + b in (null != a) ? a["on" + b]() : void 0
        }, a.prototype.addEvent = function(a, b, c) {
            return null != a.addEventListener ? a.addEventListener(b, c, !1) : null != a.attachEvent ? a.attachEvent("on" + b, c) : a[b] = c
        }, a.prototype.removeEvent = function(a, b, c) {
            return null != a.removeEventListener ? a.removeEventListener(b, c, !1) : null != a.detachEvent ? a.detachEvent("on" + b, c) : delete a[b]
        }, a.prototype.innerHeight = function() {
            return "innerHeight" in window ? window.innerHeight : document.documentElement.clientHeight
        }, a
    }(), c = this.WeakMap || this.MozWeakMap || (c = function() {
        function a() {
            this.keys = [], this.values = []
        }
        return a.prototype.get = function(a) {
            var b, c, d, e, f;
            for (f = this.keys, b = d = 0, e = f.length; e > d; b = ++d)
                if (c = f[b], c === a) return this.values[b]
        }, a.prototype.set = function(a, b) {
            var c, d, e, f, g;
            for (g = this.keys, c = e = 0, f = g.length; f > e; c = ++e)
                if (d = g[c], d === a) return void(this.values[c] = b);
            return this.keys.push(a), this.values.push(b)
        }, a
    }()), a = this.MutationObserver || this.WebkitMutationObserver || this.MozMutationObserver || (a = function() {
        function a() {
            "undefined" != typeof console && null !== console && console.warn("MutationObserver is not supported by your browser."), "undefined" != typeof console && null !== console && console.warn("WOW.js cannot detect dom mutations, please call .sync() after loading new content.")
        }
        return a.notSupported = !0, a.prototype.observe = function() {}, a
    }()), d = this.getComputedStyle || function(a, b) {
        return this.getPropertyValue = function(b) {
            var c;
            return "float" === b && (b = "styleFloat"), e.test(b) && b.replace(e, function(a, b) {
                return b.toUpperCase()
            }), (null != (c = a.currentStyle) ? c[b] : void 0) || null
        }, this
    }, e = /(\-([a-z]){1})/g, this.WOW = function() {
        function e(a) {
            null == a && (a = {}), this.scrollCallback = f(this.scrollCallback, this), this.scrollHandler = f(this.scrollHandler, this), this.resetAnimation = f(this.resetAnimation, this), this.start = f(this.start, this), this.scrolled = !0, this.config = this.util().extend(a, this.defaults), null != a.scrollContainer && (this.config.scrollContainer = document.querySelector(a.scrollContainer)), this.animationNameCache = new c, this.wowEvent = this.util().createEvent(this.config.boxClass)
        }
        return e.prototype.defaults = {
            boxClass: "wow",
            animateClass: "animated",
            offset: 0,
            mobile: !0,
            live: !0,
            callback: null,
            scrollContainer: null
        }, e.prototype.init = function() {
            var a;
            return this.element = window.document.documentElement, "interactive" === (a = document.readyState) || "complete" === a ? this.start() : this.util().addEvent(document, "DOMContentLoaded", this.start), this.finished = []
        }, e.prototype.start = function() {
            var b, c, d, e;
            if (this.stopped = !1, this.boxes = function() {
                    var a, c, d, e;
                    for (d = this.element.querySelectorAll("." + this.config.boxClass), e = [], a = 0, c = d.length; c > a; a++) b = d[a], e.push(b);
                    return e
                }.call(this), this.all = function() {
                    var a, c, d, e;
                    for (d = this.boxes, e = [], a = 0, c = d.length; c > a; a++) b = d[a], e.push(b);
                    return e
                }.call(this), this.boxes.length)
                if (this.disabled()) this.resetStyle();
                else
                    for (e = this.boxes, c = 0, d = e.length; d > c; c++) b = e[c], this.applyStyle(b, !0);
            return this.disabled() || (this.util().addEvent(this.config.scrollContainer || window, "scroll", this.scrollHandler), this.util().addEvent(window, "resize", this.scrollHandler), this.interval = setInterval(this.scrollCallback, 50)), this.config.live ? new a(function(a) {
                return function(b) {
                    var c, d, e, f, g;
                    for (g = [], c = 0, d = b.length; d > c; c++) f = b[c], g.push(function() {
                        var a, b, c, d;
                        for (c = f.addedNodes || [], d = [], a = 0, b = c.length; b > a; a++) e = c[a], d.push(this.doSync(e));
                        return d
                    }.call(a));
                    return g
                }
            }(this)).observe(document.body, {
                childList: !0,
                subtree: !0
            }) : void 0
        }, e.prototype.stop = function() {
            return this.stopped = !0, this.util().removeEvent(this.config.scrollContainer || window, "scroll", this.scrollHandler), this.util().removeEvent(window, "resize", this.scrollHandler), null != this.interval ? clearInterval(this.interval) : void 0
        }, e.prototype.sync = function(b) {
            return a.notSupported ? this.doSync(this.element) : void 0
        }, e.prototype.doSync = function(a) {
            var b, c, d, e, f;
            if (null == a && (a = this.element), 1 === a.nodeType) {
                for (a = a.parentNode || a, e = a.querySelectorAll("." + this.config.boxClass), f = [], c = 0, d = e.length; d > c; c++) b = e[c], g.call(this.all, b) < 0 ? (this.boxes.push(b), this.all.push(b), this.stopped || this.disabled() ? this.resetStyle() : this.applyStyle(b, !0), f.push(this.scrolled = !0)) : f.push(void 0);
                return f
            }
        }, e.prototype.show = function(a) {
            return this.applyStyle(a), a.className = a.className + " " + this.config.animateClass, null != this.config.callback && this.config.callback(a), this.util().emitEvent(a, this.wowEvent), this.util().addEvent(a, "animationend", this.resetAnimation), this.util().addEvent(a, "oanimationend", this.resetAnimation), this.util().addEvent(a, "webkitAnimationEnd", this.resetAnimation), this.util().addEvent(a, "MSAnimationEnd", this.resetAnimation), a
        }, e.prototype.applyStyle = function(a, b) {
            var c, d, e;
            return d = a.getAttribute("data-wow-duration"), c = a.getAttribute("data-wow-delay"), e = a.getAttribute("data-wow-iteration"), this.animate(function(f) {
                return function() {
                    return f.customStyle(a, b, d, c, e)
                }
            }(this))
        }, e.prototype.animate = function() {
            return "requestAnimationFrame" in window ? function(a) {
                return window.requestAnimationFrame(a)
            } : function(a) {
                return a()
            }
        }(), e.prototype.resetStyle = function() {
            var a, b, c, d, e;
            for (d = this.boxes, e = [], b = 0, c = d.length; c > b; b++) a = d[b], e.push(a.style.visibility = "visible");
            return e
        }, e.prototype.resetAnimation = function(a) {
            var b;
            return a.type.toLowerCase().indexOf("animationend") >= 0 ? (b = a.target || a.srcElement, b.className = b.className.replace(this.config.animateClass, "").trim()) : void 0
        }, e.prototype.customStyle = function(a, b, c, d, e) {
            return b && this.cacheAnimationName(a), a.style.visibility = b ? "hidden" : "visible", c && this.vendorSet(a.style, {
                animationDuration: c
            }), d && this.vendorSet(a.style, {
                animationDelay: d
            }), e && this.vendorSet(a.style, {
                animationIterationCount: e
            }), this.vendorSet(a.style, {
                animationName: b ? "none" : this.cachedAnimationName(a)
            }), a
        }, e.prototype.vendors = ["moz", "webkit"], e.prototype.vendorSet = function(a, b) {
            var c, d, e, f;
            d = [];
            for (c in b) e = b[c], a["" + c] = e, d.push(function() {
                var b, d, g, h;
                for (g = this.vendors, h = [], b = 0, d = g.length; d > b; b++) f = g[b], h.push(a["" + f + c.charAt(0).toUpperCase() + c.substr(1)] = e);
                return h
            }.call(this));
            return d
        }, e.prototype.vendorCSS = function(a, b) {
            var c, e, f, g, h, i;
            for (h = d(a), g = h.getPropertyCSSValue(b), f = this.vendors, c = 0, e = f.length; e > c; c++) i = f[c], g = g || h.getPropertyCSSValue("-" + i + "-" + b);
            return g
        }, e.prototype.animationName = function(a) {
            var b;
            try {
                b = this.vendorCSS(a, "animation-name").cssText
            } catch (c) {
                b = d(a).getPropertyValue("animation-name")
            }
            return "none" === b ? "" : b
        }, e.prototype.cacheAnimationName = function(a) {
            return this.animationNameCache.set(a, this.animationName(a))
        }, e.prototype.cachedAnimationName = function(a) {
            return this.animationNameCache.get(a)
        }, e.prototype.scrollHandler = function() {
            return this.scrolled = !0
        }, e.prototype.scrollCallback = function() {
            var a;
            return !this.scrolled || (this.scrolled = !1, this.boxes = function() {
                var b, c, d, e;
                for (d = this.boxes, e = [], b = 0, c = d.length; c > b; b++) a = d[b], a && (this.isVisible(a) ? this.show(a) : e.push(a));
                return e
            }.call(this), this.boxes.length || this.config.live) ? void 0 : this.stop()
        }, e.prototype.offsetTop = function(a) {
            for (var b; void 0 === a.offsetTop;) a = a.parentNode;
            for (b = a.offsetTop; a = a.offsetParent;) b += a.offsetTop;
            return b
        }, e.prototype.isVisible = function(a) {
            var b, c, d, e, f;
            return c = a.getAttribute("data-wow-offset") || this.config.offset, f = this.config.scrollContainer && this.config.scrollContainer.scrollTop || window.pageYOffset, e = f + Math.min(this.element.clientHeight, this.util().innerHeight()) - c, d = this.offsetTop(a), b = d + a.clientHeight, e >= d && b >= f
        }, e.prototype.util = function() {
            return null != this._util ? this._util : this._util = new b
        }, e.prototype.disabled = function() {
            return !this.config.mobile && this.util().isMobile(navigator.userAgent)
        }, e
    }()
}).call(this);

!function(t, e) {
    "object" == typeof exports && "object" == typeof module ? module.exports = e() : "function" == typeof define && define.amd ? define([], e) : "object" == typeof exports ? exports.swal = e() : t.swal = e()
}(this, function() {
    return function(t) {
        function e(o) {
            if (n[o]) return n[o].exports;
            var r = n[o] = {
                i: o,
                l: !1,
                exports: {}
            };
            return t[o].call(r.exports, r, r.exports, e), r.l = !0, r.exports
        }
        var n = {};
        return e.m = t, e.c = n, e.d = function(t, n, o) {
            e.o(t, n) || Object.defineProperty(t, n, {
                configurable: !1,
                enumerable: !0,
                get: o
            })
        }, e.n = function(t) {
            var n = t && t.__esModule ? function() {
                return t.default
            } : function() {
                return t
            };
            return e.d(n, "a", n), n
        }, e.o = function(t, e) {
            return Object.prototype.hasOwnProperty.call(t, e)
        }, e.p = "", e(e.s = 8)
    }([function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = "swal-button";
        e.CLASS_NAMES = {
            MODAL: "swal-modal",
            OVERLAY: "swal-overlay",
            SHOW_MODAL: "swal-overlay--show-modal",
            MODAL_TITLE: "swal-title",
            MODAL_TEXT: "swal-text",
            ICON: "swal-icon",
            ICON_CUSTOM: "swal-icon--custom",
            CONTENT: "swal-content",
            FOOTER: "swal-footer",
            BUTTON_CONTAINER: "swal-button-container",
            BUTTON: o,
            CONFIRM_BUTTON: o + "--confirm",
            CANCEL_BUTTON: o + "--cancel",
            DANGER_BUTTON: o + "--danger",
            BUTTON_LOADING: o + "--loading",
            BUTTON_LOADER: o + "__loader"
        }, e.default = e.CLASS_NAMES
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        }), e.getNode = function(t) {
            var e = "." + t;
            return document.querySelector(e)
        }, e.stringToNode = function(t) {
            var e = document.createElement("div");
            return e.innerHTML = t.trim(), e.firstChild
        }, e.insertAfter = function(t, e) {
            var n = e.nextSibling;
            e.parentNode.insertBefore(t, n)
        }, e.removeNode = function(t) {
            t.parentElement.removeChild(t)
        }, e.throwErr = function(t) {
            throw t = t.replace(/ +(?= )/g, ""), "SweetAlert: " + (t = t.trim())
        }, e.isPlainObject = function(t) {
            if ("[object Object]" !== Object.prototype.toString.call(t)) return !1;
            var e = Object.getPrototypeOf(t);
            return null === e || e === Object.prototype
        }, e.ordinalSuffixOf = function(t) {
            var e = t % 10,
                n = t % 100;
            return 1 === e && 11 !== n ? t + "st" : 2 === e && 12 !== n ? t + "nd" : 3 === e && 13 !== n ? t + "rd" : t + "th"
        }
    }, function(t, e, n) {
        "use strict";

        function o(t) {
            for (var n in t) e.hasOwnProperty(n) || (e[n] = t[n])
        }
        Object.defineProperty(e, "__esModule", {
            value: !0
        }), o(n(25));
        var r = n(26);
        e.overlayMarkup = r.default, o(n(27)), o(n(28)), o(n(29));
        var i = n(0),
            a = i.default.MODAL_TITLE,
            s = i.default.MODAL_TEXT,
            c = i.default.ICON,
            l = i.default.FOOTER;
        e.iconMarkup = '\n  <div class="' + c + '"></div>', e.titleMarkup = '\n  <div class="' + a + '"></div>\n', e.textMarkup = '\n  <div class="' + s + '"></div>', e.footerMarkup = '\n  <div class="' + l + '"></div>\n'
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = n(1);
        e.CONFIRM_KEY = "confirm", e.CANCEL_KEY = "cancel";
        var r = {
                visible: !0,
                text: null,
                value: null,
                className: "",
                closeModal: !0
            },
            i = Object.assign({}, r, {
                visible: !1,
                text: "Cancel",
                value: null
            }),
            a = Object.assign({}, r, {
                text: "OK",
                value: !0
            });
        e.defaultButtonList = {
            cancel: i,
            confirm: a
        };
        var s = function(t) {
                switch (t) {
                    case e.CONFIRM_KEY:
                        return a;
                    case e.CANCEL_KEY:
                        return i;
                    default:
                        var n = t.charAt(0).toUpperCase() + t.slice(1);
                        return Object.assign({}, r, {
                            text: n,
                            value: t
                        })
                }
            },
            c = function(t, e) {
                var n = s(t);
                return !0 === e ? Object.assign({}, n, {
                    visible: !0
                }) : "string" == typeof e ? Object.assign({}, n, {
                    visible: !0,
                    text: e
                }) : o.isPlainObject(e) ? Object.assign({
                    visible: !0
                }, n, e) : Object.assign({}, n, {
                    visible: !1
                })
            },
            l = function(t) {
                for (var e = {}, n = 0, o = Object.keys(t); n < o.length; n++) {
                    var r = o[n],
                        a = t[r],
                        s = c(r, a);
                    e[r] = s
                }
                return e.cancel || (e.cancel = i), e
            },
            u = function(t) {
                var n = {};
                switch (t.length) {
                    case 1:
                        n[e.CANCEL_KEY] = Object.assign({}, i, {
                            visible: !1
                        });
                        break;
                    case 2:
                        n[e.CANCEL_KEY] = c(e.CANCEL_KEY, t[0]), n[e.CONFIRM_KEY] = c(e.CONFIRM_KEY, t[1]);
                        break;
                    default:
                        o.throwErr("Invalid number of 'buttons' in array (" + t.length + ").\n      If you want more than 2 buttons, you need to use an object!")
                }
                return n
            };
        e.getButtonListOpts = function(t) {
            var n = e.defaultButtonList;
            return "string" == typeof t ? n[e.CONFIRM_KEY] = c(e.CONFIRM_KEY, t) : Array.isArray(t) ? n = u(t) : o.isPlainObject(t) ? n = l(t) : !0 === t ? n = u([!0, !0]) : !1 === t ? n = u([!1, !1]) : void 0 === t && (n = e.defaultButtonList), n
        }
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = n(1),
            r = n(2),
            i = n(0),
            a = i.default.MODAL,
            s = i.default.OVERLAY,
            c = n(30),
            l = n(31),
            u = n(32),
            f = n(33);
        e.injectElIntoModal = function(t) {
            var e = o.getNode(a),
                n = o.stringToNode(t);
            return e.appendChild(n), n
        };
        var d = function(t) {
                t.className = a, t.textContent = ""
            },
            p = function(t, e) {
                d(t);
                var n = e.className;
                n && t.classList.add(n)
            };
        e.initModalContent = function(t) {
            var e = o.getNode(a);
            p(e, t), c.default(t.icon), l.initTitle(t.title), l.initText(t.text), f.default(t.content), u.default(t.buttons, t.dangerMode)
        };
        var m = function() {
            var t = o.getNode(s),
                e = o.stringToNode(r.modalMarkup);
            t.appendChild(e)
        };
        e.default = m
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = n(3),
            r = {
                isOpen: !1,
                promise: null,
                actions: {},
                timer: null
            },
            i = Object.assign({}, r);
        e.resetState = function() {
            i = Object.assign({}, r)
        }, e.setActionValue = function(t) {
            if ("string" == typeof t) return a(o.CONFIRM_KEY, t);
            for (var e in t) a(e, t[e])
        };
        var a = function(t, e) {
            i.actions[t] || (i.actions[t] = {}), Object.assign(i.actions[t], {
                value: e
            })
        };
        e.setActionOptionsFor = function(t, e) {
            var n = (void 0 === e ? {} : e).closeModal,
                o = void 0 === n || n;
            Object.assign(i.actions[t], {
                closeModal: o
            })
        }, e.default = i
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = n(1),
            r = n(3),
            i = n(0),
            a = i.default.OVERLAY,
            s = i.default.SHOW_MODAL,
            c = i.default.BUTTON,
            l = i.default.BUTTON_LOADING,
            u = n(5);
        e.openModal = function() {
            o.getNode(a).classList.add(s), u.default.isOpen = !0
        };
        var f = function() {
            o.getNode(a).classList.remove(s), u.default.isOpen = !1
        };
        e.onAction = function(t) {
            void 0 === t && (t = r.CANCEL_KEY);
            var e = u.default.actions[t],
                n = e.value;
            if (!1 === e.closeModal) {
                var i = c + "--" + t;
                o.getNode(i).classList.add(l)
            } else f();
            u.default.promise.resolve(n)
        }, e.getState = function() {
            var t = Object.assign({}, u.default);
            return delete t.promise, delete t.timer, t
        }, e.stopLoading = function() {
            for (var t = document.querySelectorAll("." + c), e = 0; e < t.length; e++) {
                t[e].classList.remove(l)
            }
        }
    }, function(t, e) {
        var n;
        n = function() {
            return this
        }();
        try {
            n = n || Function("return this")() || (0, eval)("this")
        } catch (t) {
            "object" == typeof window && (n = window)
        }
        t.exports = n
    }, function(t, e, n) {
        (function(e) {
            t.exports = e.sweetAlert = n(9)
        }).call(e, n(7))
    }, function(t, e, n) {
        (function(e) {
            t.exports = e.swal = n(10)
        }).call(e, n(7))
    }, function(t, e, n) {
        "undefined" != typeof window && n(11), n(16);
        var o = n(23).default;
        t.exports = o
    }, function(t, e, n) {
        var o = n(12);
        "string" == typeof o && (o = [
            [t.i, o, ""]
        ]);
        var r = {
            insertAt: "top"
        };
        r.transform = void 0;
        n(14)(o, r);
        o.locals && (t.exports = o.locals)
    }, function(t, e, n) {
        e = t.exports = n(13)(void 0), e.push([t.i, '.swal-icon--error{border-color:#f27474;-webkit-animation:animateErrorIcon .5s;animation:animateErrorIcon .5s}.swal-icon--error__x-mark{position:relative;display:block;-webkit-animation:animateXMark .5s;animation:animateXMark .5s}.swal-icon--error__line{position:absolute;height:5px;width:47px;background-color:#f27474;display:block;top:37px;border-radius:2px}.swal-icon--error__line--left{-webkit-transform:rotate(45deg);transform:rotate(45deg);left:17px}.swal-icon--error__line--right{-webkit-transform:rotate(-45deg);transform:rotate(-45deg);right:16px}@-webkit-keyframes animateErrorIcon{0%{-webkit-transform:rotateX(100deg);transform:rotateX(100deg);opacity:0}to{-webkit-transform:rotateX(0deg);transform:rotateX(0deg);opacity:1}}@keyframes animateErrorIcon{0%{-webkit-transform:rotateX(100deg);transform:rotateX(100deg);opacity:0}to{-webkit-transform:rotateX(0deg);transform:rotateX(0deg);opacity:1}}@-webkit-keyframes animateXMark{0%{-webkit-transform:scale(.4);transform:scale(.4);margin-top:26px;opacity:0}50%{-webkit-transform:scale(.4);transform:scale(.4);margin-top:26px;opacity:0}80%{-webkit-transform:scale(1.15);transform:scale(1.15);margin-top:-6px}to{-webkit-transform:scale(1);transform:scale(1);margin-top:0;opacity:1}}@keyframes animateXMark{0%{-webkit-transform:scale(.4);transform:scale(.4);margin-top:26px;opacity:0}50%{-webkit-transform:scale(.4);transform:scale(.4);margin-top:26px;opacity:0}80%{-webkit-transform:scale(1.15);transform:scale(1.15);margin-top:-6px}to{-webkit-transform:scale(1);transform:scale(1);margin-top:0;opacity:1}}.swal-icon--warning{border-color:#f8bb86;-webkit-animation:pulseWarning .75s infinite alternate;animation:pulseWarning .75s infinite alternate}.swal-icon--warning__body{width:5px;height:47px;top:10px;border-radius:2px;margin-left:-2px}.swal-icon--warning__body,.swal-icon--warning__dot{position:absolute;left:50%;background-color:#f8bb86}.swal-icon--warning__dot{width:7px;height:7px;border-radius:50%;margin-left:-4px;bottom:-11px}@-webkit-keyframes pulseWarning{0%{border-color:#f8d486}to{border-color:#f8bb86}}@keyframes pulseWarning{0%{border-color:#f8d486}to{border-color:#f8bb86}}.swal-icon--success{border-color:#a5dc86}.swal-icon--success:after,.swal-icon--success:before{content:"";border-radius:50%;position:absolute;width:60px;height:120px;background:#fff;-webkit-transform:rotate(45deg);transform:rotate(45deg)}.swal-icon--success:before{border-radius:120px 0 0 120px;top:-7px;left:-33px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-transform-origin:60px 60px;transform-origin:60px 60px}.swal-icon--success:after{border-radius:0 120px 120px 0;top:-11px;left:30px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-transform-origin:0 60px;transform-origin:0 60px;-webkit-animation:rotatePlaceholder 4.25s ease-in;animation:rotatePlaceholder 4.25s ease-in}.swal-icon--success__ring{width:80px;height:80px;border:4px solid hsla(98,55%,69%,.2);border-radius:50%;box-sizing:content-box;position:absolute;left:-4px;top:-4px;z-index:2}.swal-icon--success__hide-corners{width:5px;height:90px;background-color:#fff;position:absolute;left:28px;top:8px;z-index:1;-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}.swal-icon--success__line{height:5px;background-color:#a5dc86;display:block;border-radius:2px;position:absolute;z-index:2}.swal-icon--success__line--tip{width:25px;left:14px;top:46px;-webkit-transform:rotate(45deg);transform:rotate(45deg);-webkit-animation:animateSuccessTip .75s;animation:animateSuccessTip .75s}.swal-icon--success__line--long{width:47px;right:8px;top:38px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-animation:animateSuccessLong .75s;animation:animateSuccessLong .75s}@-webkit-keyframes rotatePlaceholder{0%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}5%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}12%{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}to{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}}@keyframes rotatePlaceholder{0%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}5%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}12%{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}to{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}}@-webkit-keyframes animateSuccessTip{0%{width:0;left:1px;top:19px}54%{width:0;left:1px;top:19px}70%{width:50px;left:-8px;top:37px}84%{width:17px;left:21px;top:48px}to{width:25px;left:14px;top:45px}}@keyframes animateSuccessTip{0%{width:0;left:1px;top:19px}54%{width:0;left:1px;top:19px}70%{width:50px;left:-8px;top:37px}84%{width:17px;left:21px;top:48px}to{width:25px;left:14px;top:45px}}@-webkit-keyframes animateSuccessLong{0%{width:0;right:46px;top:54px}65%{width:0;right:46px;top:54px}84%{width:55px;right:0;top:35px}to{width:47px;right:8px;top:38px}}@keyframes animateSuccessLong{0%{width:0;right:46px;top:54px}65%{width:0;right:46px;top:54px}84%{width:55px;right:0;top:35px}to{width:47px;right:8px;top:38px}}.swal-icon--info{border-color:#c9dae1}.swal-icon--info:before{width:5px;height:29px;bottom:17px;border-radius:2px;margin-left:-2px}.swal-icon--info:after,.swal-icon--info:before{content:"";position:absolute;left:50%;background-color:#c9dae1}.swal-icon--info:after{width:7px;height:7px;border-radius:50%;margin-left:-3px;top:19px}.swal-icon{width:80px;height:80px;border-width:4px;border-style:solid;border-radius:50%;padding:0;position:relative;box-sizing:content-box;margin:20px auto}.swal-icon:first-child{margin-top:32px}.swal-icon--custom{width:auto;height:auto;max-width:100%;border:none;border-radius:0}.swal-icon img{max-width:100%;max-height:100%}.swal-title{color:rgba(0,0,0,.65);font-weight:600;text-transform:none;position:relative;display:block;padding:13px 16px;font-size:27px;line-height:normal;text-align:center;margin-bottom:0}.swal-title:first-child{margin-top:26px}.swal-title:not(:first-child){padding-bottom:0}.swal-title:not(:last-child){margin-bottom:13px}.swal-text{font-size:16px;position:relative;float:none;line-height:normal;vertical-align:top;text-align:left;display:inline-block;margin:0;padding:0 10px;font-weight:400;color:rgba(0,0,0,.64);max-width:calc(100% - 20px);overflow-wrap:break-word;box-sizing:border-box}.swal-text:first-child{margin-top:45px}.swal-text:last-child{margin-bottom:45px}.swal-footer{text-align:right;padding-top:13px;margin-top:13px;padding:13px 16px;border-radius:inherit;border-top-left-radius:0;border-top-right-radius:0}.swal-button-container{margin:5px;display:inline-block;position:relative}.swal-button{background-color:#7cd1f9;color:#fff;border:none;box-shadow:none;border-radius:5px;font-weight:600;font-size:14px;padding:10px 24px;margin:0;cursor:pointer}.swal-button[not:disabled]:hover{background-color:#78cbf2}.swal-button:active{background-color:#70bce0}.swal-button:focus{outline:none;box-shadow:0 0 0 1px #fff,0 0 0 3px rgba(43,114,165,.29)}.swal-button[disabled]{opacity:.5;cursor:default}.swal-button::-moz-focus-inner{border:0}.swal-button--cancel{color:#555;background-color:#efefef}.swal-button--cancel[not:disabled]:hover{background-color:#e8e8e8}.swal-button--cancel:active{background-color:#d7d7d7}.swal-button--cancel:focus{box-shadow:0 0 0 1px #fff,0 0 0 3px rgba(116,136,150,.29)}.swal-button--danger{background-color:#e64942}.swal-button--danger[not:disabled]:hover{background-color:#df4740}.swal-button--danger:active{background-color:#cf423b}.swal-button--danger:focus{box-shadow:0 0 0 1px #fff,0 0 0 3px rgba(165,43,43,.29)}.swal-content{padding:0 20px;margin-top:20px;font-size:medium}.swal-content:last-child{margin-bottom:20px}.swal-content__input,.swal-content__textarea{-webkit-appearance:none;background-color:#fff;border:none;font-size:14px;display:block;box-sizing:border-box;width:100%;border:1px solid rgba(0,0,0,.14);padding:10px 13px;border-radius:2px;transition:border-color .2s}.swal-content__input:focus,.swal-content__textarea:focus{outline:none;border-color:#6db8ff}.swal-content__textarea{resize:vertical}.swal-button--loading{color:transparent}.swal-button--loading~.swal-button__loader{opacity:1}.swal-button__loader{position:absolute;height:auto;width:43px;z-index:2;left:50%;top:50%;-webkit-transform:translateX(-50%) translateY(-50%);transform:translateX(-50%) translateY(-50%);text-align:center;pointer-events:none;opacity:0}.swal-button__loader div{display:inline-block;float:none;vertical-align:baseline;width:9px;height:9px;padding:0;border:none;margin:2px;opacity:.4;border-radius:7px;background-color:hsla(0,0%,100%,.9);transition:background .2s;-webkit-animation:swal-loading-anim 1s infinite;animation:swal-loading-anim 1s infinite}.swal-button__loader div:nth-child(3n+2){-webkit-animation-delay:.15s;animation-delay:.15s}.swal-button__loader div:nth-child(3n+3){-webkit-animation-delay:.3s;animation-delay:.3s}@-webkit-keyframes swal-loading-anim{0%{opacity:.4}20%{opacity:.4}50%{opacity:1}to{opacity:.4}}@keyframes swal-loading-anim{0%{opacity:.4}20%{opacity:.4}50%{opacity:1}to{opacity:.4}}.swal-overlay{position:fixed;top:0;bottom:0;left:0;right:0;text-align:center;font-size:0;overflow-y:scroll;background-color:rgba(0,0,0,.4);z-index:10000;pointer-events:none;opacity:0;transition:opacity .3s}.swal-overlay:before{content:" ";display:inline-block;vertical-align:middle;height:100%}.swal-overlay--show-modal{opacity:1;pointer-events:auto}.swal-overlay--show-modal .swal-modal{opacity:1;pointer-events:auto;box-sizing:border-box;-webkit-animation:showSweetAlert .3s;animation:showSweetAlert .3s;will-change:transform}.swal-modal{width:478px;opacity:0;pointer-events:none;background-color:#fff;text-align:center;border-radius:5px;position:static;margin:20px auto;display:inline-block;vertical-align:middle;-webkit-transform:scale(1);transform:scale(1);-webkit-transform-origin:50% 50%;transform-origin:50% 50%;z-index:10001;transition:opacity .2s,-webkit-transform .3s;transition:transform .3s,opacity .2s;transition:transform .3s,opacity .2s,-webkit-transform .3s}@media (max-width:500px){.swal-modal{width:calc(100% - 20px)}}@-webkit-keyframes showSweetAlert{0%{-webkit-transform:scale(1);transform:scale(1)}1%{-webkit-transform:scale(.5);transform:scale(.5)}45%{-webkit-transform:scale(1.05);transform:scale(1.05)}80%{-webkit-transform:scale(.95);transform:scale(.95)}to{-webkit-transform:scale(1);transform:scale(1)}}@keyframes showSweetAlert{0%{-webkit-transform:scale(1);transform:scale(1)}1%{-webkit-transform:scale(.5);transform:scale(.5)}45%{-webkit-transform:scale(1.05);transform:scale(1.05)}80%{-webkit-transform:scale(.95);transform:scale(.95)}to{-webkit-transform:scale(1);transform:scale(1)}}', ""])
    }, function(t, e) {
        function n(t, e) {
            var n = t[1] || "",
                r = t[3];
            if (!r) return n;
            if (e && "function" == typeof btoa) {
                var i = o(r);
                return [n].concat(r.sources.map(function(t) {
                    return "/*# sourceURL=" + r.sourceRoot + t + " */"
                })).concat([i]).join("\n")
            }
            return [n].join("\n")
        }

        function o(t) {
            return "/*# sourceMappingURL=data:application/json;charset=utf-8;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(t)))) + " */"
        }
        t.exports = function(t) {
            var e = [];
            return e.toString = function() {
                return this.map(function(e) {
                    var o = n(e, t);
                    return e[2] ? "@media " + e[2] + "{" + o + "}" : o
                }).join("")
            }, e.i = function(t, n) {
                "string" == typeof t && (t = [
                    [null, t, ""]
                ]);
                for (var o = {}, r = 0; r < this.length; r++) {
                    var i = this[r][0];
                    "number" == typeof i && (o[i] = !0)
                }
                for (r = 0; r < t.length; r++) {
                    var a = t[r];
                    "number" == typeof a[0] && o[a[0]] || (n && !a[2] ? a[2] = n : n && (a[2] = "(" + a[2] + ") and (" + n + ")"), e.push(a))
                }
            }, e
        }
    }, function(t, e, n) {
        function o(t, e) {
            for (var n = 0; n < t.length; n++) {
                var o = t[n],
                    r = m[o.id];
                if (r) {
                    r.refs++;
                    for (var i = 0; i < r.parts.length; i++) r.parts[i](o.parts[i]);
                    for (; i < o.parts.length; i++) r.parts.push(u(o.parts[i], e))
                } else {
                    for (var a = [], i = 0; i < o.parts.length; i++) a.push(u(o.parts[i], e));
                    m[o.id] = {
                        id: o.id,
                        refs: 1,
                        parts: a
                    }
                }
            }
        }

        function r(t, e) {
            for (var n = [], o = {}, r = 0; r < t.length; r++) {
                var i = t[r],
                    a = e.base ? i[0] + e.base : i[0],
                    s = i[1],
                    c = i[2],
                    l = i[3],
                    u = {
                        css: s,
                        media: c,
                        sourceMap: l
                    };
                o[a] ? o[a].parts.push(u) : n.push(o[a] = {
                    id: a,
                    parts: [u]
                })
            }
            return n
        }

        function i(t, e) {
            var n = v(t.insertInto);
            if (!n) throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");
            var o = w[w.length - 1];
            if ("top" === t.insertAt) o ? o.nextSibling ? n.insertBefore(e, o.nextSibling) : n.appendChild(e) : n.insertBefore(e, n.firstChild), w.push(e);
            else {
                if ("bottom" !== t.insertAt) throw new Error("Invalid value for parameter 'insertAt'. Must be 'top' or 'bottom'.");
                n.appendChild(e)
            }
        }

        function a(t) {
            if (null === t.parentNode) return !1;
            t.parentNode.removeChild(t);
            var e = w.indexOf(t);
            e >= 0 && w.splice(e, 1)
        }

        function s(t) {
            var e = document.createElement("style");
            return t.attrs.type = "text/css", l(e, t.attrs), i(t, e), e
        }

        function c(t) {
            var e = document.createElement("link");
            return t.attrs.type = "text/css", t.attrs.rel = "stylesheet", l(e, t.attrs), i(t, e), e
        }

        function l(t, e) {
            Object.keys(e).forEach(function(n) {
                t.setAttribute(n, e[n])
            })
        }

        function u(t, e) {
            var n, o, r, i;
            if (e.transform && t.css) {
                if (!(i = e.transform(t.css))) return function() {};
                t.css = i
            }
            if (e.singleton) {
                var l = h++;
                n = g || (g = s(e)), o = f.bind(null, n, l, !1), r = f.bind(null, n, l, !0)
            } else t.sourceMap && "function" == typeof URL && "function" == typeof URL.createObjectURL && "function" == typeof URL.revokeObjectURL && "function" == typeof Blob && "function" == typeof btoa ? (n = c(e), o = p.bind(null, n, e), r = function() {
                a(n), n.href && URL.revokeObjectURL(n.href)
            }) : (n = s(e), o = d.bind(null, n), r = function() {
                a(n)
            });
            return o(t),
                function(e) {
                    if (e) {
                        if (e.css === t.css && e.media === t.media && e.sourceMap === t.sourceMap) return;
                        o(t = e)
                    } else r()
                }
        }

        function f(t, e, n, o) {
            var r = n ? "" : o.css;
            if (t.styleSheet) t.styleSheet.cssText = x(e, r);
            else {
                var i = document.createTextNode(r),
                    a = t.childNodes;
                a[e] && t.removeChild(a[e]), a.length ? t.insertBefore(i, a[e]) : t.appendChild(i)
            }
        }

        function d(t, e) {
            var n = e.css,
                o = e.media;
            if (o && t.setAttribute("media", o), t.styleSheet) t.styleSheet.cssText = n;
            else {
                for (; t.firstChild;) t.removeChild(t.firstChild);
                t.appendChild(document.createTextNode(n))
            }
        }

        function p(t, e, n) {
            var o = n.css,
                r = n.sourceMap,
                i = void 0 === e.convertToAbsoluteUrls && r;
            (e.convertToAbsoluteUrls || i) && (o = y(o)), r && (o += "\n/*# sourceMappingURL=data:application/json;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(r)))) + " */");
            var a = new Blob([o], {
                    type: "text/css"
                }),
                s = t.href;
            t.href = URL.createObjectURL(a), s && URL.revokeObjectURL(s)
        }
        var m = {},
            b = function(t) {
                var e;
                return function() {
                    return void 0 === e && (e = t.apply(this, arguments)), e
                }
            }(function() {
                return window && document && document.all && !window.atob
            }),
            v = function(t) {
                var e = {};
                return function(n) {
                    return void 0 === e[n] && (e[n] = t.call(this, n)), e[n]
                }
            }(function(t) {
                return document.querySelector(t)
            }),
            g = null,
            h = 0,
            w = [],
            y = n(15);
        t.exports = function(t, e) {
            if ("undefined" != typeof DEBUG && DEBUG && "object" != typeof document) throw new Error("The style-loader cannot be used in a non-browser environment");
            e = e || {}, e.attrs = "object" == typeof e.attrs ? e.attrs : {}, e.singleton || (e.singleton = b()), e.insertInto || (e.insertInto = "head"), e.insertAt || (e.insertAt = "bottom");
            var n = r(t, e);
            return o(n, e),
                function(t) {
                    for (var i = [], a = 0; a < n.length; a++) {
                        var s = n[a],
                            c = m[s.id];
                        c.refs--, i.push(c)
                    }
                    if (t) {
                        o(r(t, e), e)
                    }
                    for (var a = 0; a < i.length; a++) {
                        var c = i[a];
                        if (0 === c.refs) {
                            for (var l = 0; l < c.parts.length; l++) c.parts[l]();
                            delete m[c.id]
                        }
                    }
                }
        };
        var x = function() {
            var t = [];
            return function(e, n) {
                return t[e] = n, t.filter(Boolean).join("\n")
            }
        }()
    }, function(t, e) {
        t.exports = function(t) {
            var e = "undefined" != typeof window && window.location;
            if (!e) throw new Error("fixUrls requires window.location");
            if (!t || "string" != typeof t) return t;
            var n = e.protocol + "//" + e.host,
                o = n + e.pathname.replace(/\/[^\/]*$/, "/");
            return t.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi, function(t, e) {
                var r = e.trim().replace(/^"(.*)"$/, function(t, e) {
                    return e
                }).replace(/^'(.*)'$/, function(t, e) {
                    return e
                });
                if (/^(#|data:|http:\/\/|https:\/\/|file:\/\/\/)/i.test(r)) return t;
                var i;
                return i = 0 === r.indexOf("//") ? r : 0 === r.indexOf("/") ? n + r : o + r.replace(/^\.\//, ""), "url(" + JSON.stringify(i) + ")"
            })
        }
    }, function(t, e, n) {
        var o = n(17);
        "undefined" == typeof window || window.Promise || (window.Promise = o), n(21), String.prototype.includes || (String.prototype.includes = function(t, e) {
            "use strict";
            return "number" != typeof e && (e = 0), !(e + t.length > this.length) && -1 !== this.indexOf(t, e)
        }), Array.prototype.includes || Object.defineProperty(Array.prototype, "includes", {
            value: function(t, e) {
                if (null == this) throw new TypeError('"this" is null or not defined');
                var n = Object(this),
                    o = n.length >>> 0;
                if (0 === o) return !1;
                for (var r = 0 | e, i = Math.max(r >= 0 ? r : o - Math.abs(r), 0); i < o;) {
                    if (function(t, e) {
                            return t === e || "number" == typeof t && "number" == typeof e && isNaN(t) && isNaN(e)
                        }(n[i], t)) return !0;
                    i++
                }
                return !1
            }
        }), "undefined" != typeof window && function(t) {
            t.forEach(function(t) {
                t.hasOwnProperty("remove") || Object.defineProperty(t, "remove", {
                    configurable: !0,
                    enumerable: !0,
                    writable: !0,
                    value: function() {
                        this.parentNode.removeChild(this)
                    }
                })
            })
        }([Element.prototype, CharacterData.prototype, DocumentType.prototype])
    }, function(t, e, n) {
        (function(e) {
            ! function(n) {
                function o() {}

                function r(t, e) {
                    return function() {
                        t.apply(e, arguments)
                    }
                }

                function i(t) {
                    if ("object" != typeof this) throw new TypeError("Promises must be constructed via new");
                    if ("function" != typeof t) throw new TypeError("not a function");
                    this._state = 0, this._handled = !1, this._value = void 0, this._deferreds = [], f(t, this)
                }

                function a(t, e) {
                    for (; 3 === t._state;) t = t._value;
                    if (0 === t._state) return void t._deferreds.push(e);
                    t._handled = !0, i._immediateFn(function() {
                        var n = 1 === t._state ? e.onFulfilled : e.onRejected;
                        if (null === n) return void(1 === t._state ? s : c)(e.promise, t._value);
                        var o;
                        try {
                            o = n(t._value)
                        } catch (t) {
                            return void c(e.promise, t)
                        }
                        s(e.promise, o)
                    })
                }

                function s(t, e) {
                    try {
                        if (e === t) throw new TypeError("A promise cannot be resolved with itself.");
                        if (e && ("object" == typeof e || "function" == typeof e)) {
                            var n = e.then;
                            if (e instanceof i) return t._state = 3, t._value = e, void l(t);
                            if ("function" == typeof n) return void f(r(n, e), t)
                        }
                        t._state = 1, t._value = e, l(t)
                    } catch (e) {
                        c(t, e)
                    }
                }

                function c(t, e) {
                    t._state = 2, t._value = e, l(t)
                }

                function l(t) {
                    2 === t._state && 0 === t._deferreds.length && i._immediateFn(function() {
                        t._handled || i._unhandledRejectionFn(t._value)
                    });
                    for (var e = 0, n = t._deferreds.length; e < n; e++) a(t, t._deferreds[e]);
                    t._deferreds = null
                }

                function u(t, e, n) {
                    this.onFulfilled = "function" == typeof t ? t : null, this.onRejected = "function" == typeof e ? e : null, this.promise = n
                }

                function f(t, e) {
                    var n = !1;
                    try {
                        t(function(t) {
                            n || (n = !0, s(e, t))
                        }, function(t) {
                            n || (n = !0, c(e, t))
                        })
                    } catch (t) {
                        if (n) return;
                        n = !0, c(e, t)
                    }
                }
                var d = setTimeout;
                i.prototype.catch = function(t) {
                    return this.then(null, t)
                }, i.prototype.then = function(t, e) {
                    var n = new this.constructor(o);
                    return a(this, new u(t, e, n)), n
                }, i.all = function(t) {
                    var e = Array.prototype.slice.call(t);
                    return new i(function(t, n) {
                        function o(i, a) {
                            try {
                                if (a && ("object" == typeof a || "function" == typeof a)) {
                                    var s = a.then;
                                    if ("function" == typeof s) return void s.call(a, function(t) {
                                        o(i, t)
                                    }, n)
                                }
                                e[i] = a, 0 == --r && t(e)
                            } catch (t) {
                                n(t)
                            }
                        }
                        if (0 === e.length) return t([]);
                        for (var r = e.length, i = 0; i < e.length; i++) o(i, e[i])
                    })
                }, i.resolve = function(t) {
                    return t && "object" == typeof t && t.constructor === i ? t : new i(function(e) {
                        e(t)
                    })
                }, i.reject = function(t) {
                    return new i(function(e, n) {
                        n(t)
                    })
                }, i.race = function(t) {
                    return new i(function(e, n) {
                        for (var o = 0, r = t.length; o < r; o++) t[o].then(e, n)
                    })
                }, i._immediateFn = "function" == typeof e && function(t) {
                    e(t)
                } || function(t) {
                    d(t, 0)
                }, i._unhandledRejectionFn = function(t) {
                    "undefined" != typeof console && console && console.warn("Possible Unhandled Promise Rejection:", t)
                }, i._setImmediateFn = function(t) {
                    i._immediateFn = t
                }, i._setUnhandledRejectionFn = function(t) {
                    i._unhandledRejectionFn = t
                }, void 0 !== t && t.exports ? t.exports = i : n.Promise || (n.Promise = i)
            }(this)
        }).call(e, n(18).setImmediate)
    }, function(t, e, n) {
        function o(t, e) {
            this._id = t, this._clearFn = e
        }
        var r = Function.prototype.apply;
        e.setTimeout = function() {
            return new o(r.call(setTimeout, window, arguments), clearTimeout)
        }, e.setInterval = function() {
            return new o(r.call(setInterval, window, arguments), clearInterval)
        }, e.clearTimeout = e.clearInterval = function(t) {
            t && t.close()
        }, o.prototype.unref = o.prototype.ref = function() {}, o.prototype.close = function() {
            this._clearFn.call(window, this._id)
        }, e.enroll = function(t, e) {
            clearTimeout(t._idleTimeoutId), t._idleTimeout = e
        }, e.unenroll = function(t) {
            clearTimeout(t._idleTimeoutId), t._idleTimeout = -1
        }, e._unrefActive = e.active = function(t) {
            clearTimeout(t._idleTimeoutId);
            var e = t._idleTimeout;
            e >= 0 && (t._idleTimeoutId = setTimeout(function() {
                t._onTimeout && t._onTimeout()
            }, e))
        }, n(19), e.setImmediate = setImmediate, e.clearImmediate = clearImmediate
    }, function(t, e, n) {
        (function(t, e) {
            ! function(t, n) {
                "use strict";

                function o(t) {
                    "function" != typeof t && (t = new Function("" + t));
                    for (var e = new Array(arguments.length - 1), n = 0; n < e.length; n++) e[n] = arguments[n + 1];
                    var o = {
                        callback: t,
                        args: e
                    };
                    return l[c] = o, s(c), c++
                }

                function r(t) {
                    delete l[t]
                }

                function i(t) {
                    var e = t.callback,
                        o = t.args;
                    switch (o.length) {
                        case 0:
                            e();
                            break;
                        case 1:
                            e(o[0]);
                            break;
                        case 2:
                            e(o[0], o[1]);
                            break;
                        case 3:
                            e(o[0], o[1], o[2]);
                            break;
                        default:
                            e.apply(n, o)
                    }
                }

                function a(t) {
                    if (u) setTimeout(a, 0, t);
                    else {
                        var e = l[t];
                        if (e) {
                            u = !0;
                            try {
                                i(e)
                            } finally {
                                r(t), u = !1
                            }
                        }
                    }
                }
                if (!t.setImmediate) {
                    var s, c = 1,
                        l = {},
                        u = !1,
                        f = t.document,
                        d = Object.getPrototypeOf && Object.getPrototypeOf(t);
                    d = d && d.setTimeout ? d : t, "[object process]" === {}.toString.call(t.process) ? function() {
                        s = function(t) {
                            e.nextTick(function() {
                                a(t)
                            })
                        }
                    }() : function() {
                        if (t.postMessage && !t.importScripts) {
                            var e = !0,
                                n = t.onmessage;
                            return t.onmessage = function() {
                                e = !1
                            }, t.postMessage("", "*"), t.onmessage = n, e
                        }
                    }() ? function() {
                        var e = "setImmediate$" + Math.random() + "$",
                            n = function(n) {
                                n.source === t && "string" == typeof n.data && 0 === n.data.indexOf(e) && a(+n.data.slice(e.length))
                            };
                        t.addEventListener ? t.addEventListener("message", n, !1) : t.attachEvent("onmessage", n), s = function(n) {
                            t.postMessage(e + n, "*")
                        }
                    }() : t.MessageChannel ? function() {
                        var t = new MessageChannel;
                        t.port1.onmessage = function(t) {
                            a(t.data)
                        }, s = function(e) {
                            t.port2.postMessage(e)
                        }
                    }() : f && "onreadystatechange" in f.createElement("script") ? function() {
                        var t = f.documentElement;
                        s = function(e) {
                            var n = f.createElement("script");
                            n.onreadystatechange = function() {
                                a(e), n.onreadystatechange = null, t.removeChild(n), n = null
                            }, t.appendChild(n)
                        }
                    }() : function() {
                        s = function(t) {
                            setTimeout(a, 0, t)
                        }
                    }(), d.setImmediate = o, d.clearImmediate = r
                }
            }("undefined" == typeof self ? void 0 === t ? this : t : self)
        }).call(e, n(7), n(20))
    }, function(t, e) {
        function n() {
            throw new Error("setTimeout has not been defined")
        }

        function o() {
            throw new Error("clearTimeout has not been defined")
        }

        function r(t) {
            if (u === setTimeout) return setTimeout(t, 0);
            if ((u === n || !u) && setTimeout) return u = setTimeout, setTimeout(t, 0);
            try {
                return u(t, 0)
            } catch (e) {
                try {
                    return u.call(null, t, 0)
                } catch (e) {
                    return u.call(this, t, 0)
                }
            }
        }

        function i(t) {
            if (f === clearTimeout) return clearTimeout(t);
            if ((f === o || !f) && clearTimeout) return f = clearTimeout, clearTimeout(t);
            try {
                return f(t)
            } catch (e) {
                try {
                    return f.call(null, t)
                } catch (e) {
                    return f.call(this, t)
                }
            }
        }

        function a() {
            b && p && (b = !1, p.length ? m = p.concat(m) : v = -1, m.length && s())
        }

        function s() {
            if (!b) {
                var t = r(a);
                b = !0;
                for (var e = m.length; e;) {
                    for (p = m, m = []; ++v < e;) p && p[v].run();
                    v = -1, e = m.length
                }
                p = null, b = !1, i(t)
            }
        }

        function c(t, e) {
            this.fun = t, this.array = e
        }

        function l() {}
        var u, f, d = t.exports = {};
        ! function() {
            try {
                u = "function" == typeof setTimeout ? setTimeout : n
            } catch (t) {
                u = n
            }
            try {
                f = "function" == typeof clearTimeout ? clearTimeout : o
            } catch (t) {
                f = o
            }
        }();
        var p, m = [],
            b = !1,
            v = -1;
        d.nextTick = function(t) {
            var e = new Array(arguments.length - 1);
            if (arguments.length > 1)
                for (var n = 1; n < arguments.length; n++) e[n - 1] = arguments[n];
            m.push(new c(t, e)), 1 !== m.length || b || r(s)
        }, c.prototype.run = function() {
            this.fun.apply(null, this.array)
        }, d.title = "browser", d.browser = !0, d.env = {}, d.argv = [], d.version = "", d.versions = {}, d.on = l, d.addListener = l, d.once = l, d.off = l, d.removeListener = l, d.removeAllListeners = l, d.emit = l, d.prependListener = l, d.prependOnceListener = l, d.listeners = function(t) {
            return []
        }, d.binding = function(t) {
            throw new Error("process.binding is not supported")
        }, d.cwd = function() {
            return "/"
        }, d.chdir = function(t) {
            throw new Error("process.chdir is not supported")
        }, d.umask = function() {
            return 0
        }
    }, function(t, e, n) {
        "use strict";
        n(22).polyfill()
    }, function(t, e, n) {
        "use strict";

        function o(t, e) {
            if (void 0 === t || null === t) throw new TypeError("Cannot convert first argument to object");
            for (var n = Object(t), o = 1; o < arguments.length; o++) {
                var r = arguments[o];
                if (void 0 !== r && null !== r)
                    for (var i = Object.keys(Object(r)), a = 0, s = i.length; a < s; a++) {
                        var c = i[a],
                            l = Object.getOwnPropertyDescriptor(r, c);
                        void 0 !== l && l.enumerable && (n[c] = r[c])
                    }
            }
            return n
        }

        function r() {
            Object.assign || Object.defineProperty(Object, "assign", {
                enumerable: !1,
                configurable: !0,
                writable: !0,
                value: o
            })
        }
        t.exports = {
            assign: o,
            polyfill: r
        }
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = n(24),
            r = n(6),
            i = n(5),
            a = n(36),
            s = function() {
                for (var t = [], e = 0; e < arguments.length; e++) t[e] = arguments[e];
                if ("undefined" != typeof window) {
                    var n = a.getOpts.apply(void 0, t);
                    return new Promise(function(t, e) {
                        i.default.promise = {
                            resolve: t,
                            reject: e
                        }, o.default(n), setTimeout(function() {
                            r.openModal()
                        })
                    })
                }
            };
        s.close = r.onAction, s.getState = r.getState, s.setActionValue = i.setActionValue, s.stopLoading = r.stopLoading, s.setDefaults = a.setDefaults, e.default = s
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = n(1),
            r = n(0),
            i = r.default.MODAL,
            a = n(4),
            s = n(34),
            c = n(35),
            l = n(1);
        e.init = function(t) {
            o.getNode(i) || (document.body || l.throwErr("You can only use SweetAlert AFTER the DOM has loaded!"), s.default(), a.default()), a.initModalContent(t), c.default(t)
        }, e.default = e.init
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = n(0),
            r = o.default.MODAL;
        e.modalMarkup = '\n  <div class="' + r + '"></div>', e.default = e.modalMarkup
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = n(0),
            r = o.default.OVERLAY,
            i = '<div \n    class="' + r + '"\n    tabIndex="-1">\n  </div>';
        e.default = i
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = n(0),
            r = o.default.ICON;
        e.errorIconMarkup = function() {
            var t = r + "--error",
                e = t + "__line";
            return '\n    <div class="' + t + '__x-mark">\n      <span class="' + e + " " + e + '--left"></span>\n      <span class="' + e + " " + e + '--right"></span>\n    </div>\n  '
        }, e.warningIconMarkup = function() {
            var t = r + "--warning";
            return '\n    <span class="' + t + '__body">\n      <span class="' + t + '__dot"></span>\n    </span>\n  '
        }, e.successIconMarkup = function() {
            var t = r + "--success";
            return '\n    <span class="' + t + "__line " + t + '__line--long"></span>\n    <span class="' + t + "__line " + t + '__line--tip"></span>\n\n    <div class="' + t + '__ring"></div>\n    <div class="' + t + '__hide-corners"></div>\n  '
        }
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = n(0),
            r = o.default.CONTENT;
        e.contentMarkup = '\n  <div class="' + r + '">\n\n  </div>\n'
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = n(0),
            r = o.default.BUTTON_CONTAINER,
            i = o.default.BUTTON,
            a = o.default.BUTTON_LOADER;
        e.buttonMarkup = '\n  <div class="' + r + '">\n\n    <button\n      class="' + i + '"\n    ></button>\n\n    <div class="' + a + '">\n      <div></div>\n      <div></div>\n      <div></div>\n    </div>\n\n  </div>\n'
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = n(4),
            r = n(2),
            i = n(0),
            a = i.default.ICON,
            s = i.default.ICON_CUSTOM,
            c = ["error", "warning", "success", "info"],
            l = {
                error: r.errorIconMarkup(),
                warning: r.warningIconMarkup(),
                success: r.successIconMarkup()
            },
            u = function(t, e) {
                var n = a + "--" + t;
                e.classList.add(n);
                var o = l[t];
                o && (e.innerHTML = o)
            },
            f = function(t, e) {
                e.classList.add(s);
                var n = document.createElement("img");
                n.src = t, e.appendChild(n)
            },
            d = function(t) {
                if (t) {
                    var e = o.injectElIntoModal(r.iconMarkup);
                    c.includes(t) ? u(t, e) : f(t, e)
                }
            };
        e.default = d
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = n(2),
            r = n(4),
            i = function(t) {
                navigator.userAgent.includes("AppleWebKit") && (t.style.display = "none", t.offsetHeight, t.style.display = "")
            };
        e.initTitle = function(t) {
            if (t) {
                var e = r.injectElIntoModal(o.titleMarkup);
                e.textContent = t, i(e)
            }
        }, e.initText = function(t) {
            if (t) {
                var e = r.injectElIntoModal(o.textMarkup);
                e.textContent = t, i(e)
            }
        }
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = n(1),
            r = n(4),
            i = n(0),
            a = i.default.BUTTON,
            s = i.default.DANGER_BUTTON,
            c = n(3),
            l = n(2),
            u = n(6),
            f = n(5),
            d = function(t, e, n) {
                var r = e.text,
                    i = e.value,
                    d = e.className,
                    p = e.closeModal,
                    m = o.stringToNode(l.buttonMarkup),
                    b = m.querySelector("." + a),
                    v = a + "--" + t;
                b.classList.add(v), d && b.classList.add(d), n && t === c.CONFIRM_KEY && b.classList.add(s), b.textContent = r;
                var g = {};
                return g[t] = i, f.setActionValue(g), f.setActionOptionsFor(t, {
                    closeModal: p
                }), b.addEventListener("click", function() {
                    return u.onAction(t)
                }), m
            },
            p = function(t, e) {
                var n = r.injectElIntoModal(l.footerMarkup);
                for (var o in t) {
                    var i = t[o],
                        a = d(o, i, e);
                    i.visible && n.appendChild(a)
                }
                0 === n.children.length && n.remove()
            };
        e.default = p
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = n(3),
            r = n(4),
            i = n(2),
            a = n(5),
            s = n(6),
            c = n(0),
            l = c.default.CONTENT,
            u = function(t) {
                t.addEventListener("input", function(t) {
                    var e = t.target,
                        n = e.value;
                    a.setActionValue(n)
                }), t.addEventListener("keyup", function(t) {
                    if ("Enter" === t.key) return s.onAction(o.CONFIRM_KEY)
                }), setTimeout(function() {
                    t.focus(), a.setActionValue("")
                }, 0)
            },
            f = function(t, e, n) {
                var o = document.createElement(e),
                    r = l + "__" + e;
                o.classList.add(r);
                for (var i in n) {
                    var a = n[i];
                    o[i] = a
                }
                "input" === e && u(o), t.appendChild(o)
            },
            d = function(t) {
                if (t) {
                    var e = r.injectElIntoModal(i.contentMarkup),
                        n = t.element,
                        o = t.attributes;
                    "string" == typeof n ? f(e, n, o) : e.appendChild(n)
                }
            };
        e.default = d
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = n(1),
            r = n(2),
            i = function() {
                var t = o.stringToNode(r.overlayMarkup);
                document.body.appendChild(t)
            };
        e.default = i
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = n(5),
            r = n(6),
            i = n(1),
            a = n(3),
            s = n(0),
            c = s.default.MODAL,
            l = s.default.BUTTON,
            u = s.default.OVERLAY,
            f = function(t) {
                t.preventDefault(), v()
            },
            d = function(t) {
                t.preventDefault(), g()
            },
            p = function(t) {
                if (o.default.isOpen) switch (t.key) {
                    case "Escape":
                        return r.onAction(a.CANCEL_KEY)
                }
            },
            m = function(t) {
                if (o.default.isOpen) switch (t.key) {
                    case "Tab":
                        return f(t)
                }
            },
            b = function(t) {
                if (o.default.isOpen) return "Tab" === t.key && t.shiftKey ? d(t) : void 0
            },
            v = function() {
                var t = i.getNode(l);
                t && (t.tabIndex = 0, t.focus())
            },
            g = function() {
                var t = i.getNode(c),
                    e = t.querySelectorAll("." + l),
                    n = e.length - 1,
                    o = e[n];
                o && o.focus()
            },
            h = function(t) {
                t[t.length - 1].addEventListener("keydown", m)
            },
            w = function(t) {
                t[0].addEventListener("keydown", b)
            },
            y = function() {
                var t = i.getNode(c),
                    e = t.querySelectorAll("." + l);
                e.length && (h(e), w(e))
            },
            x = function(t) {
                if (i.getNode(u) === t.target) return r.onAction(a.CANCEL_KEY)
            },
            _ = function(t) {
                var e = i.getNode(u);
                e.removeEventListener("click", x), t && e.addEventListener("click", x)
            },
            k = function(t) {
                o.default.timer && clearTimeout(o.default.timer), t && (o.default.timer = window.setTimeout(function() {
                    return r.onAction(a.CANCEL_KEY)
                }, t))
            },
            O = function(t) {
                t.closeOnEsc ? document.addEventListener("keyup", p) : document.removeEventListener("keyup", p), t.dangerMode ? v() : g(), y(), _(t.closeOnClickOutside), k(t.timer)
            };
        e.default = O
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = n(1),
            r = n(3),
            i = n(37),
            a = n(38),
            s = {
                title: null,
                text: null,
                icon: null,
                buttons: r.defaultButtonList,
                content: null,
                className: null,
                closeOnClickOutside: !0,
                closeOnEsc: !0,
                dangerMode: !1,
                timer: null
            },
            c = Object.assign({}, s);
        e.setDefaults = function(t) {
            c = Object.assign({}, s, t)
        };
        var l = function(t) {
                var e = t && t.button,
                    n = t && t.buttons;
                return void 0 !== e && void 0 !== n && o.throwErr("Cannot set both 'button' and 'buttons' options!"), void 0 !== e ? {
                    confirm: e
                } : n
            },
            u = function(t) {
                return o.ordinalSuffixOf(t + 1)
            },
            f = function(t, e) {
                o.throwErr(u(e) + " argument ('" + t + "') is invalid")
            },
            d = function(t, e) {
                var n = t + 1,
                    r = e[n];
                o.isPlainObject(r) || void 0 === r || o.throwErr("Expected " + u(n) + " argument ('" + r + "') to be a plain object")
            },
            p = function(t, e) {
                var n = t + 1,
                    r = e[n];
                void 0 !== r && o.throwErr("Unexpected " + u(n) + " argument (" + r + ")")
            },
            m = function(t, e, n, r) {
                var i = typeof e,
                    a = "string" === i,
                    s = e instanceof Element;
                if (a) {
                    if (0 === n) return {
                        text: e
                    };
                    if (1 === n) return {
                        text: e,
                        title: r[0]
                    };
                    if (2 === n) return d(n, r), {
                        icon: e
                    };
                    f(e, n)
                } else {
                    if (s && 0 === n) return d(n, r), {
                        content: e
                    };
                    if (o.isPlainObject(e)) return p(n, r), e;
                    f(e, n)
                }
            };
        e.getOpts = function() {
            for (var t = [], e = 0; e < arguments.length; e++) t[e] = arguments[e];
            var n = {};
            t.forEach(function(e, o) {
                var r = m(0, e, o, t);
                Object.assign(n, r)
            });
            var o = l(n);
            n.buttons = r.getButtonListOpts(o), delete n.button, n.content = i.getContentOpts(n.content);
            var u = Object.assign({}, s, c, n);
            return Object.keys(u).forEach(function(t) {
                a.DEPRECATED_OPTS[t] && a.logDeprecation(t)
            }), u
        }
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        });
        var o = n(1),
            r = {
                element: "input",
                attributes: {
                    placeholder: ""
                }
            };
        e.getContentOpts = function(t) {
            var e = {};
            return o.isPlainObject(t) ? Object.assign(e, t) : t instanceof Element ? {
                element: t
            } : "input" === t ? r : null
        }
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        }), e.logDeprecation = function(t) {
            var n = e.DEPRECATED_OPTS[t],
                o = n.onlyRename,
                r = n.replacement,
                i = n.subOption,
                a = n.link,
                s = o ? "renamed" : "deprecated",
                c = 'SweetAlert warning: "' + t + '" option has been ' + s + ".";
            if (r) {
                c += " Please use" + (i ? ' "' + i + '" in ' : " ") + '"' + r + '" instead.'
            }
            var l = "https://sweetalert.js.org";
            c += a ? " More details: " + l + a : " More details: " + l + "/guides/#upgrading-from-1x", console.warn(c)
        }, e.DEPRECATED_OPTS = {
            type: {
                replacement: "icon",
                link: "/docs/#icon"
            },
            imageUrl: {
                replacement: "icon",
                link: "/docs/#icon"
            },
            customClass: {
                replacement: "className",
                onlyRename: !0,
                link: "/docs/#classname"
            },
            imageSize: {},
            showCancelButton: {
                replacement: "buttons",
                link: "/docs/#buttons"
            },
            showConfirmButton: {
                replacement: "button",
                link: "/docs/#button"
            },
            confirmButtonText: {
                replacement: "button",
                link: "/docs/#button"
            },
            confirmButtonColor: {},
            cancelButtonText: {
                replacement: "buttons",
                link: "/docs/#buttons"
            },
            closeOnConfirm: {
                replacement: "button",
                subOption: "closeModal",
                link: "/docs/#button"
            },
            closeOnCancel: {
                replacement: "buttons",
                subOption: "closeModal",
                link: "/docs/#buttons"
            },
            showLoaderOnConfirm: {
                replacement: "buttons"
            },
            animation: {},
            inputType: {
                replacement: "content",
                link: "/docs/#content"
            },
            inputValue: {
                replacement: "content",
                link: "/docs/#content"
            },
            inputPlaceholder: {
                replacement: "content",
                link: "/docs/#content"
            },
            html: {
                replacement: "content",
                link: "/docs/#content"
            },
            allowEscapeKey: {
                replacement: "closeOnEsc",
                onlyRename: !0,
                link: "/docs/#closeonesc"
            },
            allowClickOutside: {
                replacement: "closeOnClickOutside",
                onlyRename: !0,
                link: "/docs/#closeonclickoutside"
            }
        }
    }])
});

/*!
 * Chart.js
 * http://chartjs.org/
 * Version: 2.7.2
 *
 * Copyright 2018 Chart.js Contributors
 * Released under the MIT license
 * https://github.com/chartjs/Chart.js/blob/master/LICENSE.md
 */
!function(t) {
    if ("object" == typeof exports && "undefined" != typeof module) module.exports = t();
    else if ("function" == typeof define && define.amd) define([], t);
    else {
        ("undefined" != typeof window ? window : "undefined" != typeof global ? global : "undefined" != typeof self ? self : this).Chart = t()
    }
}(function() {
    return function t(e, i, n) {
        function a(o, s) {
            if (!i[o]) {
                if (!e[o]) {
                    var l = "function" == typeof require && require;
                    if (!s && l) return l(o, !0);
                    if (r) return r(o, !0);
                    var u = new Error("Cannot find module '" + o + "'");
                    throw u.code = "MODULE_NOT_FOUND", u
                }
                var d = i[o] = {
                    exports: {}
                };
                e[o][0].call(d.exports, function(t) {
                    var i = e[o][1][t];
                    return a(i || t)
                }, d, d.exports, t, e, i, n)
            }
            return i[o].exports
        }
        for (var r = "function" == typeof require && require, o = 0; o < n.length; o++) a(n[o]);
        return a
    }({
        1: [function(t, e, i) {
            var n = t(5);

            function a(t) {
                if (t) {
                    var e = [0, 0, 0],
                        i = 1,
                        a = t.match(/^#([a-fA-F0-9]{3})$/i);
                    if (a) {
                        a = a[1];
                        for (var r = 0; r < e.length; r++) e[r] = parseInt(a[r] + a[r], 16)
                    } else if (a = t.match(/^#([a-fA-F0-9]{6})$/i)) {
                        a = a[1];
                        for (r = 0; r < e.length; r++) e[r] = parseInt(a.slice(2 * r, 2 * r + 2), 16)
                    } else if (a = t.match(/^rgba?\(\s*([+-]?\d+)\s*,\s*([+-]?\d+)\s*,\s*([+-]?\d+)\s*(?:,\s*([+-]?[\d\.]+)\s*)?\)$/i)) {
                        for (r = 0; r < e.length; r++) e[r] = parseInt(a[r + 1]);
                        i = parseFloat(a[4])
                    } else if (a = t.match(/^rgba?\(\s*([+-]?[\d\.]+)\%\s*,\s*([+-]?[\d\.]+)\%\s*,\s*([+-]?[\d\.]+)\%\s*(?:,\s*([+-]?[\d\.]+)\s*)?\)$/i)) {
                        for (r = 0; r < e.length; r++) e[r] = Math.round(2.55 * parseFloat(a[r + 1]));
                        i = parseFloat(a[4])
                    } else if (a = t.match(/(\w+)/)) {
                        if ("transparent" == a[1]) return [0, 0, 0, 0];
                        if (!(e = n[a[1]])) return
                    }
                    for (r = 0; r < e.length; r++) e[r] = d(e[r], 0, 255);
                    return i = i || 0 == i ? d(i, 0, 1) : 1, e[3] = i, e
                }
            }

            function r(t) {
                if (t) {
                    var e = t.match(/^hsla?\(\s*([+-]?\d+)(?:deg)?\s*,\s*([+-]?[\d\.]+)%\s*,\s*([+-]?[\d\.]+)%\s*(?:,\s*([+-]?[\d\.]+)\s*)?\)/);
                    if (e) {
                        var i = parseFloat(e[4]);
                        return [d(parseInt(e[1]), 0, 360), d(parseFloat(e[2]), 0, 100), d(parseFloat(e[3]), 0, 100), d(isNaN(i) ? 1 : i, 0, 1)]
                    }
                }
            }

            function o(t) {
                if (t) {
                    var e = t.match(/^hwb\(\s*([+-]?\d+)(?:deg)?\s*,\s*([+-]?[\d\.]+)%\s*,\s*([+-]?[\d\.]+)%\s*(?:,\s*([+-]?[\d\.]+)\s*)?\)/);
                    if (e) {
                        var i = parseFloat(e[4]);
                        return [d(parseInt(e[1]), 0, 360), d(parseFloat(e[2]), 0, 100), d(parseFloat(e[3]), 0, 100), d(isNaN(i) ? 1 : i, 0, 1)]
                    }
                }
            }

            function s(t, e) {
                return void 0 === e && (e = void 0 !== t[3] ? t[3] : 1), "rgba(" + t[0] + ", " + t[1] + ", " + t[2] + ", " + e + ")"
            }

            function l(t, e) {
                return "rgba(" + Math.round(t[0] / 255 * 100) + "%, " + Math.round(t[1] / 255 * 100) + "%, " + Math.round(t[2] / 255 * 100) + "%, " + (e || t[3] || 1) + ")"
            }

            function u(t, e) {
                return void 0 === e && (e = void 0 !== t[3] ? t[3] : 1), "hsla(" + t[0] + ", " + t[1] + "%, " + t[2] + "%, " + e + ")"
            }

            function d(t, e, i) {
                return Math.min(Math.max(e, t), i)
            }

            function h(t) {
                var e = t.toString(16).toUpperCase();
                return e.length < 2 ? "0" + e : e
            }
            e.exports = {
                getRgba: a,
                getHsla: r,
                getRgb: function(t) {
                    var e = a(t);
                    return e && e.slice(0, 3)
                },
                getHsl: function(t) {
                    var e = r(t);
                    return e && e.slice(0, 3)
                },
                getHwb: o,
                getAlpha: function(t) {
                    var e = a(t); {
                        if (e) return e[3];
                        if (e = r(t)) return e[3];
                        if (e = o(t)) return e[3]
                    }
                },
                hexString: function(t) {
                    return "#" + h(t[0]) + h(t[1]) + h(t[2])
                },
                rgbString: function(t, e) {
                    if (e < 1 || t[3] && t[3] < 1) return s(t, e);
                    return "rgb(" + t[0] + ", " + t[1] + ", " + t[2] + ")"
                },
                rgbaString: s,
                percentString: function(t, e) {
                    if (e < 1 || t[3] && t[3] < 1) return l(t, e);
                    var i = Math.round(t[0] / 255 * 100),
                        n = Math.round(t[1] / 255 * 100),
                        a = Math.round(t[2] / 255 * 100);
                    return "rgb(" + i + "%, " + n + "%, " + a + "%)"
                },
                percentaString: l,
                hslString: function(t, e) {
                    if (e < 1 || t[3] && t[3] < 1) return u(t, e);
                    return "hsl(" + t[0] + ", " + t[1] + "%, " + t[2] + "%)"
                },
                hslaString: u,
                hwbString: function(t, e) {
                    void 0 === e && (e = void 0 !== t[3] ? t[3] : 1);
                    return "hwb(" + t[0] + ", " + t[1] + "%, " + t[2] + "%" + (void 0 !== e && 1 !== e ? ", " + e : "") + ")"
                },
                keyword: function(t) {
                    return c[t.slice(0, 3)]
                }
            };
            var c = {};
            for (var f in n) c[n[f]] = f
        }, {
            5: 5
        }],
        2: [function(t, e, i) {
            var n = t(4),
                a = t(1),
                r = function(t) {
                    return t instanceof r ? t : this instanceof r ? (this.valid = !1, this.values = {
                        rgb: [0, 0, 0],
                        hsl: [0, 0, 0],
                        hsv: [0, 0, 0],
                        hwb: [0, 0, 0],
                        cmyk: [0, 0, 0, 0],
                        alpha: 1
                    }, void("string" == typeof t ? (e = a.getRgba(t)) ? this.setValues("rgb", e) : (e = a.getHsla(t)) ? this.setValues("hsl", e) : (e = a.getHwb(t)) && this.setValues("hwb", e) : "object" == typeof t && (void 0 !== (e = t).r || void 0 !== e.red ? this.setValues("rgb", e) : void 0 !== e.l || void 0 !== e.lightness ? this.setValues("hsl", e) : void 0 !== e.v || void 0 !== e.value ? this.setValues("hsv", e) : void 0 !== e.w || void 0 !== e.whiteness ? this.setValues("hwb", e) : void 0 === e.c && void 0 === e.cyan || this.setValues("cmyk", e)))) : new r(t);
                    var e
                };
            r.prototype = {
                isValid: function() {
                    return this.valid
                },
                rgb: function() {
                    return this.setSpace("rgb", arguments)
                },
                hsl: function() {
                    return this.setSpace("hsl", arguments)
                },
                hsv: function() {
                    return this.setSpace("hsv", arguments)
                },
                hwb: function() {
                    return this.setSpace("hwb", arguments)
                },
                cmyk: function() {
                    return this.setSpace("cmyk", arguments)
                },
                rgbArray: function() {
                    return this.values.rgb
                },
                hslArray: function() {
                    return this.values.hsl
                },
                hsvArray: function() {
                    return this.values.hsv
                },
                hwbArray: function() {
                    var t = this.values;
                    return 1 !== t.alpha ? t.hwb.concat([t.alpha]) : t.hwb
                },
                cmykArray: function() {
                    return this.values.cmyk
                },
                rgbaArray: function() {
                    var t = this.values;
                    return t.rgb.concat([t.alpha])
                },
                hslaArray: function() {
                    var t = this.values;
                    return t.hsl.concat([t.alpha])
                },
                alpha: function(t) {
                    return void 0 === t ? this.values.alpha : (this.setValues("alpha", t), this)
                },
                red: function(t) {
                    return this.setChannel("rgb", 0, t)
                },
                green: function(t) {
                    return this.setChannel("rgb", 1, t)
                },
                blue: function(t) {
                    return this.setChannel("rgb", 2, t)
                },
                hue: function(t) {
                    return t && (t = (t %= 360) < 0 ? 360 + t : t), this.setChannel("hsl", 0, t)
                },
                saturation: function(t) {
                    return this.setChannel("hsl", 1, t)
                },
                lightness: function(t) {
                    return this.setChannel("hsl", 2, t)
                },
                saturationv: function(t) {
                    return this.setChannel("hsv", 1, t)
                },
                whiteness: function(t) {
                    return this.setChannel("hwb", 1, t)
                },
                blackness: function(t) {
                    return this.setChannel("hwb", 2, t)
                },
                value: function(t) {
                    return this.setChannel("hsv", 2, t)
                },
                cyan: function(t) {
                    return this.setChannel("cmyk", 0, t)
                },
                magenta: function(t) {
                    return this.setChannel("cmyk", 1, t)
                },
                yellow: function(t) {
                    return this.setChannel("cmyk", 2, t)
                },
                black: function(t) {
                    return this.setChannel("cmyk", 3, t)
                },
                hexString: function() {
                    return a.hexString(this.values.rgb)
                },
                rgbString: function() {
                    return a.rgbString(this.values.rgb, this.values.alpha)
                },
                rgbaString: function() {
                    return a.rgbaString(this.values.rgb, this.values.alpha)
                },
                percentString: function() {
                    return a.percentString(this.values.rgb, this.values.alpha)
                },
                hslString: function() {
                    return a.hslString(this.values.hsl, this.values.alpha)
                },
                hslaString: function() {
                    return a.hslaString(this.values.hsl, this.values.alpha)
                },
                hwbString: function() {
                    return a.hwbString(this.values.hwb, this.values.alpha)
                },
                keyword: function() {
                    return a.keyword(this.values.rgb, this.values.alpha)
                },
                rgbNumber: function() {
                    var t = this.values.rgb;
                    return t[0] << 16 | t[1] << 8 | t[2]
                },
                luminosity: function() {
                    for (var t = this.values.rgb, e = [], i = 0; i < t.length; i++) {
                        var n = t[i] / 255;
                        e[i] = n <= .03928 ? n / 12.92 : Math.pow((n + .055) / 1.055, 2.4)
                    }
                    return .2126 * e[0] + .7152 * e[1] + .0722 * e[2]
                },
                contrast: function(t) {
                    var e = this.luminosity(),
                        i = t.luminosity();
                    return e > i ? (e + .05) / (i + .05) : (i + .05) / (e + .05)
                },
                level: function(t) {
                    var e = this.contrast(t);
                    return e >= 7.1 ? "AAA" : e >= 4.5 ? "AA" : ""
                },
                dark: function() {
                    var t = this.values.rgb;
                    return (299 * t[0] + 587 * t[1] + 114 * t[2]) / 1e3 < 128
                },
                light: function() {
                    return !this.dark()
                },
                negate: function() {
                    for (var t = [], e = 0; e < 3; e++) t[e] = 255 - this.values.rgb[e];
                    return this.setValues("rgb", t), this
                },
                lighten: function(t) {
                    var e = this.values.hsl;
                    return e[2] += e[2] * t, this.setValues("hsl", e), this
                },
                darken: function(t) {
                    var e = this.values.hsl;
                    return e[2] -= e[2] * t, this.setValues("hsl", e), this
                },
                saturate: function(t) {
                    var e = this.values.hsl;
                    return e[1] += e[1] * t, this.setValues("hsl", e), this
                },
                desaturate: function(t) {
                    var e = this.values.hsl;
                    return e[1] -= e[1] * t, this.setValues("hsl", e), this
                },
                whiten: function(t) {
                    var e = this.values.hwb;
                    return e[1] += e[1] * t, this.setValues("hwb", e), this
                },
                blacken: function(t) {
                    var e = this.values.hwb;
                    return e[2] += e[2] * t, this.setValues("hwb", e), this
                },
                greyscale: function() {
                    var t = this.values.rgb,
                        e = .3 * t[0] + .59 * t[1] + .11 * t[2];
                    return this.setValues("rgb", [e, e, e]), this
                },
                clearer: function(t) {
                    var e = this.values.alpha;
                    return this.setValues("alpha", e - e * t), this
                },
                opaquer: function(t) {
                    var e = this.values.alpha;
                    return this.setValues("alpha", e + e * t), this
                },
                rotate: function(t) {
                    var e = this.values.hsl,
                        i = (e[0] + t) % 360;
                    return e[0] = i < 0 ? 360 + i : i, this.setValues("hsl", e), this
                },
                mix: function(t, e) {
                    var i = this,
                        n = t,
                        a = void 0 === e ? .5 : e,
                        r = 2 * a - 1,
                        o = i.alpha() - n.alpha(),
                        s = ((r * o == -1 ? r : (r + o) / (1 + r * o)) + 1) / 2,
                        l = 1 - s;
                    return this.rgb(s * i.red() + l * n.red(), s * i.green() + l * n.green(), s * i.blue() + l * n.blue()).alpha(i.alpha() * a + n.alpha() * (1 - a))
                },
                toJSON: function() {
                    return this.rgb()
                },
                clone: function() {
                    var t, e, i = new r,
                        n = this.values,
                        a = i.values;
                    for (var o in n) n.hasOwnProperty(o) && (t = n[o], "[object Array]" === (e = {}.toString.call(t)) ? a[o] = t.slice(0) : "[object Number]" === e ? a[o] = t : console.error("unexpected color value:", t));
                    return i
                }
            }, r.prototype.spaces = {
                rgb: ["red", "green", "blue"],
                hsl: ["hue", "saturation", "lightness"],
                hsv: ["hue", "saturation", "value"],
                hwb: ["hue", "whiteness", "blackness"],
                cmyk: ["cyan", "magenta", "yellow", "black"]
            }, r.prototype.maxes = {
                rgb: [255, 255, 255],
                hsl: [360, 100, 100],
                hsv: [360, 100, 100],
                hwb: [360, 100, 100],
                cmyk: [100, 100, 100, 100]
            }, r.prototype.getValues = function(t) {
                for (var e = this.values, i = {}, n = 0; n < t.length; n++) i[t.charAt(n)] = e[t][n];
                return 1 !== e.alpha && (i.a = e.alpha), i
            }, r.prototype.setValues = function(t, e) {
                var i, a, r = this.values,
                    o = this.spaces,
                    s = this.maxes,
                    l = 1;
                if (this.valid = !0, "alpha" === t) l = e;
                else if (e.length) r[t] = e.slice(0, t.length), l = e[t.length];
                else if (void 0 !== e[t.charAt(0)]) {
                    for (i = 0; i < t.length; i++) r[t][i] = e[t.charAt(i)];
                    l = e.a
                } else if (void 0 !== e[o[t][0]]) {
                    var u = o[t];
                    for (i = 0; i < t.length; i++) r[t][i] = e[u[i]];
                    l = e.alpha
                }
                if (r.alpha = Math.max(0, Math.min(1, void 0 === l ? r.alpha : l)), "alpha" === t) return !1;
                for (i = 0; i < t.length; i++) a = Math.max(0, Math.min(s[t][i], r[t][i])), r[t][i] = Math.round(a);
                for (var d in o) d !== t && (r[d] = n[t][d](r[t]));
                return !0
            }, r.prototype.setSpace = function(t, e) {
                var i = e[0];
                return void 0 === i ? this.getValues(t) : ("number" == typeof i && (i = Array.prototype.slice.call(e)), this.setValues(t, i), this)
            }, r.prototype.setChannel = function(t, e, i) {
                var n = this.values[t];
                return void 0 === i ? n[e] : i === n[e] ? this : (n[e] = i, this.setValues(t, n), this)
            }, "undefined" != typeof window && (window.Color = r), e.exports = r
        }, {
            1: 1,
            4: 4
        }],
        3: [function(t, e, i) {
            function n(t) {
                var e, i, n = t[0] / 255,
                    a = t[1] / 255,
                    r = t[2] / 255,
                    o = Math.min(n, a, r),
                    s = Math.max(n, a, r),
                    l = s - o;
                return s == o ? e = 0 : n == s ? e = (a - r) / l : a == s ? e = 2 + (r - n) / l : r == s && (e = 4 + (n - a) / l), (e = Math.min(60 * e, 360)) < 0 && (e += 360), i = (o + s) / 2, [e, 100 * (s == o ? 0 : i <= .5 ? l / (s + o) : l / (2 - s - o)), 100 * i]
            }

            function a(t) {
                var e, i, n = t[0],
                    a = t[1],
                    r = t[2],
                    o = Math.min(n, a, r),
                    s = Math.max(n, a, r),
                    l = s - o;
                return i = 0 == s ? 0 : l / s * 1e3 / 10, s == o ? e = 0 : n == s ? e = (a - r) / l : a == s ? e = 2 + (r - n) / l : r == s && (e = 4 + (n - a) / l), (e = Math.min(60 * e, 360)) < 0 && (e += 360), [e, i, s / 255 * 1e3 / 10]
            }

            function o(t) {
                var e = t[0],
                    i = t[1],
                    a = t[2];
                return [n(t)[0], 100 * (1 / 255 * Math.min(e, Math.min(i, a))), 100 * (a = 1 - 1 / 255 * Math.max(e, Math.max(i, a)))]
            }

            function s(t) {
                var e, i = t[0] / 255,
                    n = t[1] / 255,
                    a = t[2] / 255;
                return [100 * ((1 - i - (e = Math.min(1 - i, 1 - n, 1 - a))) / (1 - e) || 0), 100 * ((1 - n - e) / (1 - e) || 0), 100 * ((1 - a - e) / (1 - e) || 0), 100 * e]
            }

            function l(t) {
                return S[JSON.stringify(t)]
            }

            function u(t) {
                var e = t[0] / 255,
                    i = t[1] / 255,
                    n = t[2] / 255;
                return [100 * (.4124 * (e = e > .04045 ? Math.pow((e + .055) / 1.055, 2.4) : e / 12.92) + .3576 * (i = i > .04045 ? Math.pow((i + .055) / 1.055, 2.4) : i / 12.92) + .1805 * (n = n > .04045 ? Math.pow((n + .055) / 1.055, 2.4) : n / 12.92)), 100 * (.2126 * e + .7152 * i + .0722 * n), 100 * (.0193 * e + .1192 * i + .9505 * n)]
            }

            function d(t) {
                var e = u(t),
                    i = e[0],
                    n = e[1],
                    a = e[2];
                return n /= 100, a /= 108.883, i = (i /= 95.047) > .008856 ? Math.pow(i, 1 / 3) : 7.787 * i + 16 / 116, [116 * (n = n > .008856 ? Math.pow(n, 1 / 3) : 7.787 * n + 16 / 116) - 16, 500 * (i - n), 200 * (n - (a = a > .008856 ? Math.pow(a, 1 / 3) : 7.787 * a + 16 / 116))]
            }

            function h(t) {
                var e, i, n, a, r, o = t[0] / 360,
                    s = t[1] / 100,
                    l = t[2] / 100;
                if (0 == s) return [r = 255 * l, r, r];
                e = 2 * l - (i = l < .5 ? l * (1 + s) : l + s - l * s), a = [0, 0, 0];
                for (var u = 0; u < 3; u++)(n = o + 1 / 3 * -(u - 1)) < 0 && n++, n > 1 && n--, r = 6 * n < 1 ? e + 6 * (i - e) * n : 2 * n < 1 ? i : 3 * n < 2 ? e + (i - e) * (2 / 3 - n) * 6 : e, a[u] = 255 * r;
                return a
            }

            function c(t) {
                var e = t[0] / 60,
                    i = t[1] / 100,
                    n = t[2] / 100,
                    a = Math.floor(e) % 6,
                    r = e - Math.floor(e),
                    o = 255 * n * (1 - i),
                    s = 255 * n * (1 - i * r),
                    l = 255 * n * (1 - i * (1 - r));
                n *= 255;
                switch (a) {
                    case 0:
                        return [n, l, o];
                    case 1:
                        return [s, n, o];
                    case 2:
                        return [o, n, l];
                    case 3:
                        return [o, s, n];
                    case 4:
                        return [l, o, n];
                    case 5:
                        return [n, o, s]
                }
            }

            function f(t) {
                var e, i, n, a, o = t[0] / 360,
                    s = t[1] / 100,
                    l = t[2] / 100,
                    u = s + l;
                switch (u > 1 && (s /= u, l /= u), n = 6 * o - (e = Math.floor(6 * o)), 0 != (1 & e) && (n = 1 - n), a = s + n * ((i = 1 - l) - s), e) {
                    default:
                        case 6:
                        case 0:
                        r = i,
                    g = a,
                    b = s;
                    break;
                    case 1:
                            r = a,
                        g = i,
                        b = s;
                        break;
                    case 2:
                            r = s,
                        g = i,
                        b = a;
                        break;
                    case 3:
                            r = s,
                        g = a,
                        b = i;
                        break;
                    case 4:
                            r = a,
                        g = s,
                        b = i;
                        break;
                    case 5:
                            r = i,
                        g = s,
                        b = a
                }
                return [255 * r, 255 * g, 255 * b]
            }

            function m(t) {
                var e = t[0] / 100,
                    i = t[1] / 100,
                    n = t[2] / 100,
                    a = t[3] / 100;
                return [255 * (1 - Math.min(1, e * (1 - a) + a)), 255 * (1 - Math.min(1, i * (1 - a) + a)), 255 * (1 - Math.min(1, n * (1 - a) + a))]
            }

            function p(t) {
                var e, i, n, a = t[0] / 100,
                    r = t[1] / 100,
                    o = t[2] / 100;
                return i = -.9689 * a + 1.8758 * r + .0415 * o, n = .0557 * a + -.204 * r + 1.057 * o, e = (e = 3.2406 * a + -1.5372 * r + -.4986 * o) > .0031308 ? 1.055 * Math.pow(e, 1 / 2.4) - .055 : e *= 12.92, i = i > .0031308 ? 1.055 * Math.pow(i, 1 / 2.4) - .055 : i *= 12.92, n = n > .0031308 ? 1.055 * Math.pow(n, 1 / 2.4) - .055 : n *= 12.92, [255 * (e = Math.min(Math.max(0, e), 1)), 255 * (i = Math.min(Math.max(0, i), 1)), 255 * (n = Math.min(Math.max(0, n), 1))]
            }

            function v(t) {
                var e = t[0],
                    i = t[1],
                    n = t[2];
                return i /= 100, n /= 108.883, e = (e /= 95.047) > .008856 ? Math.pow(e, 1 / 3) : 7.787 * e + 16 / 116, [116 * (i = i > .008856 ? Math.pow(i, 1 / 3) : 7.787 * i + 16 / 116) - 16, 500 * (e - i), 200 * (i - (n = n > .008856 ? Math.pow(n, 1 / 3) : 7.787 * n + 16 / 116))]
            }

            function y(t) {
                var e, i, n, a, r = t[0],
                    o = t[1],
                    s = t[2];
                return r <= 8 ? a = (i = 100 * r / 903.3) / 100 * 7.787 + 16 / 116 : (i = 100 * Math.pow((r + 16) / 116, 3), a = Math.pow(i / 100, 1 / 3)), [e = e / 95.047 <= .008856 ? e = 95.047 * (o / 500 + a - 16 / 116) / 7.787 : 95.047 * Math.pow(o / 500 + a, 3), i, n = n / 108.883 <= .008859 ? n = 108.883 * (a - s / 200 - 16 / 116) / 7.787 : 108.883 * Math.pow(a - s / 200, 3)]
            }

            function x(t) {
                var e, i = t[0],
                    n = t[1],
                    a = t[2];
                return (e = 360 * Math.atan2(a, n) / 2 / Math.PI) < 0 && (e += 360), [i, Math.sqrt(n * n + a * a), e]
            }

            function _(t) {
                return p(y(t))
            }

            function k(t) {
                var e, i = t[0],
                    n = t[1];
                return e = t[2] / 360 * 2 * Math.PI, [i, n * Math.cos(e), n * Math.sin(e)]
            }

            function w(t) {
                return M[t]
            }
            e.exports = {
                rgb2hsl: n,
                rgb2hsv: a,
                rgb2hwb: o,
                rgb2cmyk: s,
                rgb2keyword: l,
                rgb2xyz: u,
                rgb2lab: d,
                rgb2lch: function(t) {
                    return x(d(t))
                },
                hsl2rgb: h,
                hsl2hsv: function(t) {
                    var e = t[0],
                        i = t[1] / 100,
                        n = t[2] / 100;
                    if (0 === n) return [0, 0, 0];
                    return [e, 100 * (2 * (i *= (n *= 2) <= 1 ? n : 2 - n) / (n + i)), 100 * ((n + i) / 2)]
                },
                hsl2hwb: function(t) {
                    return o(h(t))
                },
                hsl2cmyk: function(t) {
                    return s(h(t))
                },
                hsl2keyword: function(t) {
                    return l(h(t))
                },
                hsv2rgb: c,
                hsv2hsl: function(t) {
                    var e, i, n = t[0],
                        a = t[1] / 100,
                        r = t[2] / 100;
                    return e = a * r, [n, 100 * (e = (e /= (i = (2 - a) * r) <= 1 ? i : 2 - i) || 0), 100 * (i /= 2)]
                },
                hsv2hwb: function(t) {
                    return o(c(t))
                },
                hsv2cmyk: function(t) {
                    return s(c(t))
                },
                hsv2keyword: function(t) {
                    return l(c(t))
                },
                hwb2rgb: f,
                hwb2hsl: function(t) {
                    return n(f(t))
                },
                hwb2hsv: function(t) {
                    return a(f(t))
                },
                hwb2cmyk: function(t) {
                    return s(f(t))
                },
                hwb2keyword: function(t) {
                    return l(f(t))
                },
                cmyk2rgb: m,
                cmyk2hsl: function(t) {
                    return n(m(t))
                },
                cmyk2hsv: function(t) {
                    return a(m(t))
                },
                cmyk2hwb: function(t) {
                    return o(m(t))
                },
                cmyk2keyword: function(t) {
                    return l(m(t))
                },
                keyword2rgb: w,
                keyword2hsl: function(t) {
                    return n(w(t))
                },
                keyword2hsv: function(t) {
                    return a(w(t))
                },
                keyword2hwb: function(t) {
                    return o(w(t))
                },
                keyword2cmyk: function(t) {
                    return s(w(t))
                },
                keyword2lab: function(t) {
                    return d(w(t))
                },
                keyword2xyz: function(t) {
                    return u(w(t))
                },
                xyz2rgb: p,
                xyz2lab: v,
                xyz2lch: function(t) {
                    return x(v(t))
                },
                lab2xyz: y,
                lab2rgb: _,
                lab2lch: x,
                lch2lab: k,
                lch2xyz: function(t) {
                    return y(k(t))
                },
                lch2rgb: function(t) {
                    return _(k(t))
                }
            };
            var M = {
                    aliceblue: [240, 248, 255],
                    antiquewhite: [250, 235, 215],
                    aqua: [0, 255, 255],
                    aquamarine: [127, 255, 212],
                    azure: [240, 255, 255],
                    beige: [245, 245, 220],
                    bisque: [255, 228, 196],
                    black: [0, 0, 0],
                    blanchedalmond: [255, 235, 205],
                    blue: [0, 0, 255],
                    blueviolet: [138, 43, 226],
                    brown: [165, 42, 42],
                    burlywood: [222, 184, 135],
                    cadetblue: [95, 158, 160],
                    chartreuse: [127, 255, 0],
                    chocolate: [210, 105, 30],
                    coral: [255, 127, 80],
                    cornflowerblue: [100, 149, 237],
                    cornsilk: [255, 248, 220],
                    crimson: [220, 20, 60],
                    cyan: [0, 255, 255],
                    darkblue: [0, 0, 139],
                    darkcyan: [0, 139, 139],
                    darkgoldenrod: [184, 134, 11],
                    darkgray: [169, 169, 169],
                    darkgreen: [0, 100, 0],
                    darkgrey: [169, 169, 169],
                    darkkhaki: [189, 183, 107],
                    darkmagenta: [139, 0, 139],
                    darkolivegreen: [85, 107, 47],
                    darkorange: [255, 140, 0],
                    darkorchid: [153, 50, 204],
                    darkred: [139, 0, 0],
                    darksalmon: [233, 150, 122],
                    darkseagreen: [143, 188, 143],
                    darkslateblue: [72, 61, 139],
                    darkslategray: [47, 79, 79],
                    darkslategrey: [47, 79, 79],
                    darkturquoise: [0, 206, 209],
                    darkviolet: [148, 0, 211],
                    deeppink: [255, 20, 147],
                    deepskyblue: [0, 191, 255],
                    dimgray: [105, 105, 105],
                    dimgrey: [105, 105, 105],
                    dodgerblue: [30, 144, 255],
                    firebrick: [178, 34, 34],
                    floralwhite: [255, 250, 240],
                    forestgreen: [34, 139, 34],
                    fuchsia: [255, 0, 255],
                    gainsboro: [220, 220, 220],
                    ghostwhite: [248, 248, 255],
                    gold: [255, 215, 0],
                    goldenrod: [218, 165, 32],
                    gray: [128, 128, 128],
                    green: [0, 128, 0],
                    greenyellow: [173, 255, 47],
                    grey: [128, 128, 128],
                    honeydew: [240, 255, 240],
                    hotpink: [255, 105, 180],
                    indianred: [205, 92, 92],
                    indigo: [75, 0, 130],
                    ivory: [255, 255, 240],
                    khaki: [240, 230, 140],
                    lavender: [230, 230, 250],
                    lavenderblush: [255, 240, 245],
                    lawngreen: [124, 252, 0],
                    lemonchiffon: [255, 250, 205],
                    lightblue: [173, 216, 230],
                    lightcoral: [240, 128, 128],
                    lightcyan: [224, 255, 255],
                    lightgoldenrodyellow: [250, 250, 210],
                    lightgray: [211, 211, 211],
                    lightgreen: [144, 238, 144],
                    lightgrey: [211, 211, 211],
                    lightpink: [255, 182, 193],
                    lightsalmon: [255, 160, 122],
                    lightseagreen: [32, 178, 170],
                    lightskyblue: [135, 206, 250],
                    lightslategray: [119, 136, 153],
                    lightslategrey: [119, 136, 153],
                    lightsteelblue: [176, 196, 222],
                    lightyellow: [255, 255, 224],
                    lime: [0, 255, 0],
                    limegreen: [50, 205, 50],
                    linen: [250, 240, 230],
                    magenta: [255, 0, 255],
                    maroon: [128, 0, 0],
                    mediumaquamarine: [102, 205, 170],
                    mediumblue: [0, 0, 205],
                    mediumorchid: [186, 85, 211],
                    mediumpurple: [147, 112, 219],
                    mediumseagreen: [60, 179, 113],
                    mediumslateblue: [123, 104, 238],
                    mediumspringgreen: [0, 250, 154],
                    mediumturquoise: [72, 209, 204],
                    mediumvioletred: [199, 21, 133],
                    midnightblue: [25, 25, 112],
                    mintcream: [245, 255, 250],
                    mistyrose: [255, 228, 225],
                    moccasin: [255, 228, 181],
                    navajowhite: [255, 222, 173],
                    navy: [0, 0, 128],
                    oldlace: [253, 245, 230],
                    olive: [128, 128, 0],
                    olivedrab: [107, 142, 35],
                    orange: [255, 165, 0],
                    orangered: [255, 69, 0],
                    orchid: [218, 112, 214],
                    palegoldenrod: [238, 232, 170],
                    palegreen: [152, 251, 152],
                    paleturquoise: [175, 238, 238],
                    palevioletred: [219, 112, 147],
                    papayawhip: [255, 239, 213],
                    peachpuff: [255, 218, 185],
                    peru: [205, 133, 63],
                    pink: [255, 192, 203],
                    plum: [221, 160, 221],
                    powderblue: [176, 224, 230],
                    purple: [128, 0, 128],
                    rebeccapurple: [102, 51, 153],
                    red: [255, 0, 0],
                    rosybrown: [188, 143, 143],
                    royalblue: [65, 105, 225],
                    saddlebrown: [139, 69, 19],
                    salmon: [250, 128, 114],
                    sandybrown: [244, 164, 96],
                    seagreen: [46, 139, 87],
                    seashell: [255, 245, 238],
                    sienna: [160, 82, 45],
                    silver: [192, 192, 192],
                    skyblue: [135, 206, 235],
                    slateblue: [106, 90, 205],
                    slategray: [112, 128, 144],
                    slategrey: [112, 128, 144],
                    snow: [255, 250, 250],
                    springgreen: [0, 255, 127],
                    steelblue: [70, 130, 180],
                    tan: [210, 180, 140],
                    teal: [0, 128, 128],
                    thistle: [216, 191, 216],
                    tomato: [255, 99, 71],
                    turquoise: [64, 224, 208],
                    violet: [238, 130, 238],
                    wheat: [245, 222, 179],
                    white: [255, 255, 255],
                    whitesmoke: [245, 245, 245],
                    yellow: [255, 255, 0],
                    yellowgreen: [154, 205, 50]
                },
                S = {};
            for (var D in M) S[JSON.stringify(M[D])] = D
        }, {}],
        4: [function(t, e, i) {
            var n = t(3),
                a = function() {
                    return new u
                };
            for (var r in n) {
                a[r + "Raw"] = function(t) {
                    return function(e) {
                        return "number" == typeof e && (e = Array.prototype.slice.call(arguments)), n[t](e)
                    }
                }(r);
                var o = /(\w+)2(\w+)/.exec(r),
                    s = o[1],
                    l = o[2];
                (a[s] = a[s] || {})[l] = a[r] = function(t) {
                    return function(e) {
                        "number" == typeof e && (e = Array.prototype.slice.call(arguments));
                        var i = n[t](e);
                        if ("string" == typeof i || void 0 === i) return i;
                        for (var a = 0; a < i.length; a++) i[a] = Math.round(i[a]);
                        return i
                    }
                }(r)
            }
            var u = function() {
                this.convs = {}
            };
            u.prototype.routeSpace = function(t, e) {
                var i = e[0];
                return void 0 === i ? this.getValues(t) : ("number" == typeof i && (i = Array.prototype.slice.call(e)), this.setValues(t, i))
            }, u.prototype.setValues = function(t, e) {
                return this.space = t, this.convs = {}, this.convs[t] = e, this
            }, u.prototype.getValues = function(t) {
                var e = this.convs[t];
                if (!e) {
                    var i = this.space,
                        n = this.convs[i];
                    e = a[i][t](n), this.convs[t] = e
                }
                return e
            }, ["rgb", "hsl", "hsv", "cmyk", "keyword"].forEach(function(t) {
                u.prototype[t] = function(e) {
                    return this.routeSpace(t, arguments)
                }
            }), e.exports = a
        }, {
            3: 3
        }],
        5: [function(t, e, i) {
            "use strict";
            e.exports = {
                aliceblue: [240, 248, 255],
                antiquewhite: [250, 235, 215],
                aqua: [0, 255, 255],
                aquamarine: [127, 255, 212],
                azure: [240, 255, 255],
                beige: [245, 245, 220],
                bisque: [255, 228, 196],
                black: [0, 0, 0],
                blanchedalmond: [255, 235, 205],
                blue: [0, 0, 255],
                blueviolet: [138, 43, 226],
                brown: [165, 42, 42],
                burlywood: [222, 184, 135],
                cadetblue: [95, 158, 160],
                chartreuse: [127, 255, 0],
                chocolate: [210, 105, 30],
                coral: [255, 127, 80],
                cornflowerblue: [100, 149, 237],
                cornsilk: [255, 248, 220],
                crimson: [220, 20, 60],
                cyan: [0, 255, 255],
                darkblue: [0, 0, 139],
                darkcyan: [0, 139, 139],
                darkgoldenrod: [184, 134, 11],
                darkgray: [169, 169, 169],
                darkgreen: [0, 100, 0],
                darkgrey: [169, 169, 169],
                darkkhaki: [189, 183, 107],
                darkmagenta: [139, 0, 139],
                darkolivegreen: [85, 107, 47],
                darkorange: [255, 140, 0],
                darkorchid: [153, 50, 204],
                darkred: [139, 0, 0],
                darksalmon: [233, 150, 122],
                darkseagreen: [143, 188, 143],
                darkslateblue: [72, 61, 139],
                darkslategray: [47, 79, 79],
                darkslategrey: [47, 79, 79],
                darkturquoise: [0, 206, 209],
                darkviolet: [148, 0, 211],
                deeppink: [255, 20, 147],
                deepskyblue: [0, 191, 255],
                dimgray: [105, 105, 105],
                dimgrey: [105, 105, 105],
                dodgerblue: [30, 144, 255],
                firebrick: [178, 34, 34],
                floralwhite: [255, 250, 240],
                forestgreen: [34, 139, 34],
                fuchsia: [255, 0, 255],
                gainsboro: [220, 220, 220],
                ghostwhite: [248, 248, 255],
                gold: [255, 215, 0],
                goldenrod: [218, 165, 32],
                gray: [128, 128, 128],
                green: [0, 128, 0],
                greenyellow: [173, 255, 47],
                grey: [128, 128, 128],
                honeydew: [240, 255, 240],
                hotpink: [255, 105, 180],
                indianred: [205, 92, 92],
                indigo: [75, 0, 130],
                ivory: [255, 255, 240],
                khaki: [240, 230, 140],
                lavender: [230, 230, 250],
                lavenderblush: [255, 240, 245],
                lawngreen: [124, 252, 0],
                lemonchiffon: [255, 250, 205],
                lightblue: [173, 216, 230],
                lightcoral: [240, 128, 128],
                lightcyan: [224, 255, 255],
                lightgoldenrodyellow: [250, 250, 210],
                lightgray: [211, 211, 211],
                lightgreen: [144, 238, 144],
                lightgrey: [211, 211, 211],
                lightpink: [255, 182, 193],
                lightsalmon: [255, 160, 122],
                lightseagreen: [32, 178, 170],
                lightskyblue: [135, 206, 250],
                lightslategray: [119, 136, 153],
                lightslategrey: [119, 136, 153],
                lightsteelblue: [176, 196, 222],
                lightyellow: [255, 255, 224],
                lime: [0, 255, 0],
                limegreen: [50, 205, 50],
                linen: [250, 240, 230],
                magenta: [255, 0, 255],
                maroon: [128, 0, 0],
                mediumaquamarine: [102, 205, 170],
                mediumblue: [0, 0, 205],
                mediumorchid: [186, 85, 211],
                mediumpurple: [147, 112, 219],
                mediumseagreen: [60, 179, 113],
                mediumslateblue: [123, 104, 238],
                mediumspringgreen: [0, 250, 154],
                mediumturquoise: [72, 209, 204],
                mediumvioletred: [199, 21, 133],
                midnightblue: [25, 25, 112],
                mintcream: [245, 255, 250],
                mistyrose: [255, 228, 225],
                moccasin: [255, 228, 181],
                navajowhite: [255, 222, 173],
                navy: [0, 0, 128],
                oldlace: [253, 245, 230],
                olive: [128, 128, 0],
                olivedrab: [107, 142, 35],
                orange: [255, 165, 0],
                orangered: [255, 69, 0],
                orchid: [218, 112, 214],
                palegoldenrod: [238, 232, 170],
                palegreen: [152, 251, 152],
                paleturquoise: [175, 238, 238],
                palevioletred: [219, 112, 147],
                papayawhip: [255, 239, 213],
                peachpuff: [255, 218, 185],
                peru: [205, 133, 63],
                pink: [255, 192, 203],
                plum: [221, 160, 221],
                powderblue: [176, 224, 230],
                purple: [128, 0, 128],
                rebeccapurple: [102, 51, 153],
                red: [255, 0, 0],
                rosybrown: [188, 143, 143],
                royalblue: [65, 105, 225],
                saddlebrown: [139, 69, 19],
                salmon: [250, 128, 114],
                sandybrown: [244, 164, 96],
                seagreen: [46, 139, 87],
                seashell: [255, 245, 238],
                sienna: [160, 82, 45],
                silver: [192, 192, 192],
                skyblue: [135, 206, 235],
                slateblue: [106, 90, 205],
                slategray: [112, 128, 144],
                slategrey: [112, 128, 144],
                snow: [255, 250, 250],
                springgreen: [0, 255, 127],
                steelblue: [70, 130, 180],
                tan: [210, 180, 140],
                teal: [0, 128, 128],
                thistle: [216, 191, 216],
                tomato: [255, 99, 71],
                turquoise: [64, 224, 208],
                violet: [238, 130, 238],
                wheat: [245, 222, 179],
                white: [255, 255, 255],
                whitesmoke: [245, 245, 245],
                yellow: [255, 255, 0],
                yellowgreen: [154, 205, 50]
            }
        }, {}],
        6: [function(t, e, i) {
            var n, a;
            n = this, a = function() {
                "use strict";
                var i, n;

                function a() {
                    return i.apply(null, arguments)
                }

                function r(t) {
                    return t instanceof Array || "[object Array]" === Object.prototype.toString.call(t)
                }

                function o(t) {
                    return null != t && "[object Object]" === Object.prototype.toString.call(t)
                }

                function s(t) {
                    return void 0 === t
                }

                function l(t) {
                    return "number" == typeof t || "[object Number]" === Object.prototype.toString.call(t)
                }

                function u(t) {
                    return t instanceof Date || "[object Date]" === Object.prototype.toString.call(t)
                }

                function d(t, e) {
                    var i, n = [];
                    for (i = 0; i < t.length; ++i) n.push(e(t[i], i));
                    return n
                }

                function h(t, e) {
                    return Object.prototype.hasOwnProperty.call(t, e)
                }

                function c(t, e) {
                    for (var i in e) h(e, i) && (t[i] = e[i]);
                    return h(e, "toString") && (t.toString = e.toString), h(e, "valueOf") && (t.valueOf = e.valueOf), t
                }

                function f(t, e, i, n) {
                    return Pe(t, e, i, n, !0).utc()
                }

                function g(t) {
                    return null == t._pf && (t._pf = {
                        empty: !1,
                        unusedTokens: [],
                        unusedInput: [],
                        overflow: -2,
                        charsLeftOver: 0,
                        nullInput: !1,
                        invalidMonth: null,
                        invalidFormat: !1,
                        userInvalidated: !1,
                        iso: !1,
                        parsedDateParts: [],
                        meridiem: null,
                        rfc2822: !1,
                        weekdayMismatch: !1
                    }), t._pf
                }

                function m(t) {
                    if (null == t._isValid) {
                        var e = g(t),
                            i = n.call(e.parsedDateParts, function(t) {
                                return null != t
                            }),
                            a = !isNaN(t._d.getTime()) && e.overflow < 0 && !e.empty && !e.invalidMonth && !e.invalidWeekday && !e.weekdayMismatch && !e.nullInput && !e.invalidFormat && !e.userInvalidated && (!e.meridiem || e.meridiem && i);
                        if (t._strict && (a = a && 0 === e.charsLeftOver && 0 === e.unusedTokens.length && void 0 === e.bigHour), null != Object.isFrozen && Object.isFrozen(t)) return a;
                        t._isValid = a
                    }
                    return t._isValid
                }

                function p(t) {
                    var e = f(NaN);
                    return null != t ? c(g(e), t) : g(e).userInvalidated = !0, e
                }
                n = Array.prototype.some ? Array.prototype.some : function(t) {
                    for (var e = Object(this), i = e.length >>> 0, n = 0; n < i; n++)
                        if (n in e && t.call(this, e[n], n, e)) return !0;
                    return !1
                };
                var v = a.momentProperties = [];

                function y(t, e) {
                    var i, n, a;
                    if (s(e._isAMomentObject) || (t._isAMomentObject = e._isAMomentObject), s(e._i) || (t._i = e._i), s(e._f) || (t._f = e._f), s(e._l) || (t._l = e._l), s(e._strict) || (t._strict = e._strict), s(e._tzm) || (t._tzm = e._tzm), s(e._isUTC) || (t._isUTC = e._isUTC), s(e._offset) || (t._offset = e._offset), s(e._pf) || (t._pf = g(e)), s(e._locale) || (t._locale = e._locale), v.length > 0)
                        for (i = 0; i < v.length; i++) s(a = e[n = v[i]]) || (t[n] = a);
                    return t
                }
                var b = !1;

                function x(t) {
                    y(this, t), this._d = new Date(null != t._d ? t._d.getTime() : NaN), this.isValid() || (this._d = new Date(NaN)), !1 === b && (b = !0, a.updateOffset(this), b = !1)
                }

                function _(t) {
                    return t instanceof x || null != t && null != t._isAMomentObject
                }

                function k(t) {
                    return t < 0 ? Math.ceil(t) || 0 : Math.floor(t)
                }

                function w(t) {
                    var e = +t,
                        i = 0;
                    return 0 !== e && isFinite(e) && (i = k(e)), i
                }

                function M(t, e, i) {
                    var n, a = Math.min(t.length, e.length),
                        r = Math.abs(t.length - e.length),
                        o = 0;
                    for (n = 0; n < a; n++)(i && t[n] !== e[n] || !i && w(t[n]) !== w(e[n])) && o++;
                    return o + r
                }

                function S(t) {
                    !1 === a.suppressDeprecationWarnings && "undefined" != typeof console && console.warn && console.warn("Deprecation warning: " + t)
                }

                function D(t, e) {
                    var i = !0;
                    return c(function() {
                        if (null != a.deprecationHandler && a.deprecationHandler(null, t), i) {
                            for (var n, r = [], o = 0; o < arguments.length; o++) {
                                if (n = "", "object" == typeof arguments[o]) {
                                    for (var s in n += "\n[" + o + "] ", arguments[0]) n += s + ": " + arguments[0][s] + ", ";
                                    n = n.slice(0, -2)
                                } else n = arguments[o];
                                r.push(n)
                            }
                            S(t + "\nArguments: " + Array.prototype.slice.call(r).join("") + "\n" + (new Error).stack), i = !1
                        }
                        return e.apply(this, arguments)
                    }, e)
                }
                var C, P = {};

                function T(t, e) {
                    null != a.deprecationHandler && a.deprecationHandler(t, e), P[t] || (S(e), P[t] = !0)
                }

                function O(t) {
                    return t instanceof Function || "[object Function]" === Object.prototype.toString.call(t)
                }

                function I(t, e) {
                    var i, n = c({}, t);
                    for (i in e) h(e, i) && (o(t[i]) && o(e[i]) ? (n[i] = {}, c(n[i], t[i]), c(n[i], e[i])) : null != e[i] ? n[i] = e[i] : delete n[i]);
                    for (i in t) h(t, i) && !h(e, i) && o(t[i]) && (n[i] = c({}, n[i]));
                    return n
                }

                function A(t) {
                    null != t && this.set(t)
                }
                a.suppressDeprecationWarnings = !1, a.deprecationHandler = null, C = Object.keys ? Object.keys : function(t) {
                    var e, i = [];
                    for (e in t) h(t, e) && i.push(e);
                    return i
                };
                var F = {};

                function R(t, e) {
                    var i = t.toLowerCase();
                    F[i] = F[i + "s"] = F[e] = t
                }

                function L(t) {
                    return "string" == typeof t ? F[t] || F[t.toLowerCase()] : void 0
                }

                function W(t) {
                    var e, i, n = {};
                    for (i in t) h(t, i) && (e = L(i)) && (n[e] = t[i]);
                    return n
                }
                var Y = {};

                function N(t, e) {
                    Y[t] = e
                }

                function z(t, e, i) {
                    var n = "" + Math.abs(t),
                        a = e - n.length;
                    return (t >= 0 ? i ? "+" : "" : "-") + Math.pow(10, Math.max(0, a)).toString().substr(1) + n
                }
                var H = /(\[[^\[]*\])|(\\)?([Hh]mm(ss)?|Mo|MM?M?M?|Do|DDDo|DD?D?D?|ddd?d?|do?|w[o|w]?|W[o|W]?|Qo?|YYYYYY|YYYYY|YYYY|YY|gg(ggg?)?|GG(GGG?)?|e|E|a|A|hh?|HH?|kk?|mm?|ss?|S{1,9}|x|X|zz?|ZZ?|.)/g,
                    V = /(\[[^\[]*\])|(\\)?(LTS|LT|LL?L?L?|l{1,4})/g,
                    B = {},
                    E = {};

                function j(t, e, i, n) {
                    var a = n;
                    "string" == typeof n && (a = function() {
                        return this[n]()
                    }), t && (E[t] = a), e && (E[e[0]] = function() {
                        return z(a.apply(this, arguments), e[1], e[2])
                    }), i && (E[i] = function() {
                        return this.localeData().ordinal(a.apply(this, arguments), t)
                    })
                }

                function U(t, e) {
                    return t.isValid() ? (e = q(e, t.localeData()), B[e] = B[e] || function(t) {
                        var e, i, n, a = t.match(H);
                        for (e = 0, i = a.length; e < i; e++) E[a[e]] ? a[e] = E[a[e]] : a[e] = (n = a[e]).match(/\[[\s\S]/) ? n.replace(/^\[|\]$/g, "") : n.replace(/\\/g, "");
                        return function(e) {
                            var n, r = "";
                            for (n = 0; n < i; n++) r += O(a[n]) ? a[n].call(e, t) : a[n];
                            return r
                        }
                    }(e), B[e](t)) : t.localeData().invalidDate()
                }

                function q(t, e) {
                    var i = 5;

                    function n(t) {
                        return e.longDateFormat(t) || t
                    }
                    for (V.lastIndex = 0; i >= 0 && V.test(t);) t = t.replace(V, n), V.lastIndex = 0, i -= 1;
                    return t
                }
                var G = /\d/,
                    Z = /\d\d/,
                    X = /\d{3}/,
                    J = /\d{4}/,
                    K = /[+-]?\d{6}/,
                    $ = /\d\d?/,
                    Q = /\d\d\d\d?/,
                    tt = /\d\d\d\d\d\d?/,
                    et = /\d{1,3}/,
                    it = /\d{1,4}/,
                    nt = /[+-]?\d{1,6}/,
                    at = /\d+/,
                    rt = /[+-]?\d+/,
                    ot = /Z|[+-]\d\d:?\d\d/gi,
                    st = /Z|[+-]\d\d(?::?\d\d)?/gi,
                    lt = /[0-9]{0,256}['a-z\u00A0-\u05FF\u0700-\uD7FF\uF900-\uFDCF\uFDF0-\uFF07\uFF10-\uFFEF]{1,256}|[\u0600-\u06FF\/]{1,256}(\s*?[\u0600-\u06FF]{1,256}){1,2}/i,
                    ut = {};

                function dt(t, e, i) {
                    ut[t] = O(e) ? e : function(t, n) {
                        return t && i ? i : e
                    }
                }

                function ht(t, e) {
                    return h(ut, t) ? ut[t](e._strict, e._locale) : new RegExp(ct(t.replace("\\", "").replace(/\\(\[)|\\(\])|\[([^\]\[]*)\]|\\(.)/g, function(t, e, i, n, a) {
                        return e || i || n || a
                    })))
                }

                function ct(t) {
                    return t.replace(/[-\/\\^$*+?.()|[\]{}]/g, "\\$&")
                }
                var ft = {};

                function gt(t, e) {
                    var i, n = e;
                    for ("string" == typeof t && (t = [t]), l(e) && (n = function(t, i) {
                            i[e] = w(t)
                        }), i = 0; i < t.length; i++) ft[t[i]] = n
                }

                function mt(t, e) {
                    gt(t, function(t, i, n, a) {
                        n._w = n._w || {}, e(t, n._w, n, a)
                    })
                }
                var pt = 0,
                    vt = 1,
                    yt = 2,
                    bt = 3,
                    xt = 4,
                    _t = 5,
                    kt = 6,
                    wt = 7,
                    Mt = 8;

                function St(t) {
                    return Dt(t) ? 366 : 365
                }

                function Dt(t) {
                    return t % 4 == 0 && t % 100 != 0 || t % 400 == 0
                }
                j("Y", 0, 0, function() {
                    var t = this.year();
                    return t <= 9999 ? "" + t : "+" + t
                }), j(0, ["YY", 2], 0, function() {
                    return this.year() % 100
                }), j(0, ["YYYY", 4], 0, "year"), j(0, ["YYYYY", 5], 0, "year"), j(0, ["YYYYYY", 6, !0], 0, "year"), R("year", "y"), N("year", 1), dt("Y", rt), dt("YY", $, Z), dt("YYYY", it, J), dt("YYYYY", nt, K), dt("YYYYYY", nt, K), gt(["YYYYY", "YYYYYY"], pt), gt("YYYY", function(t, e) {
                    e[pt] = 2 === t.length ? a.parseTwoDigitYear(t) : w(t)
                }), gt("YY", function(t, e) {
                    e[pt] = a.parseTwoDigitYear(t)
                }), gt("Y", function(t, e) {
                    e[pt] = parseInt(t, 10)
                }), a.parseTwoDigitYear = function(t) {
                    return w(t) + (w(t) > 68 ? 1900 : 2e3)
                };
                var Ct, Pt = Tt("FullYear", !0);

                function Tt(t, e) {
                    return function(i) {
                        return null != i ? (It(this, t, i), a.updateOffset(this, e), this) : Ot(this, t)
                    }
                }

                function Ot(t, e) {
                    return t.isValid() ? t._d["get" + (t._isUTC ? "UTC" : "") + e]() : NaN
                }

                function It(t, e, i) {
                    t.isValid() && !isNaN(i) && ("FullYear" === e && Dt(t.year()) && 1 === t.month() && 29 === t.date() ? t._d["set" + (t._isUTC ? "UTC" : "") + e](i, t.month(), At(i, t.month())) : t._d["set" + (t._isUTC ? "UTC" : "") + e](i))
                }

                function At(t, e) {
                    if (isNaN(t) || isNaN(e)) return NaN;
                    var i, n = (e % (i = 12) + i) % i;
                    return t += (e - n) / 12, 1 === n ? Dt(t) ? 29 : 28 : 31 - n % 7 % 2
                }
                Ct = Array.prototype.indexOf ? Array.prototype.indexOf : function(t) {
                    var e;
                    for (e = 0; e < this.length; ++e)
                        if (this[e] === t) return e;
                    return -1
                }, j("M", ["MM", 2], "Mo", function() {
                    return this.month() + 1
                }), j("MMM", 0, 0, function(t) {
                    return this.localeData().monthsShort(this, t)
                }), j("MMMM", 0, 0, function(t) {
                    return this.localeData().months(this, t)
                }), R("month", "M"), N("month", 8), dt("M", $), dt("MM", $, Z), dt("MMM", function(t, e) {
                    return e.monthsShortRegex(t)
                }), dt("MMMM", function(t, e) {
                    return e.monthsRegex(t)
                }), gt(["M", "MM"], function(t, e) {
                    e[vt] = w(t) - 1
                }), gt(["MMM", "MMMM"], function(t, e, i, n) {
                    var a = i._locale.monthsParse(t, n, i._strict);
                    null != a ? e[vt] = a : g(i).invalidMonth = t
                });
                var Ft = /D[oD]?(\[[^\[\]]*\]|\s)+MMMM?/,
                    Rt = "January_February_March_April_May_June_July_August_September_October_November_December".split("_");
                var Lt = "Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec".split("_");

                function Wt(t, e) {
                    var i;
                    if (!t.isValid()) return t;
                    if ("string" == typeof e)
                        if (/^\d+$/.test(e)) e = w(e);
                        else if (!l(e = t.localeData().monthsParse(e))) return t;
                    return i = Math.min(t.date(), At(t.year(), e)), t._d["set" + (t._isUTC ? "UTC" : "") + "Month"](e, i), t
                }

                function Yt(t) {
                    return null != t ? (Wt(this, t), a.updateOffset(this, !0), this) : Ot(this, "Month")
                }
                var Nt = lt;
                var zt = lt;

                function Ht() {
                    function t(t, e) {
                        return e.length - t.length
                    }
                    var e, i, n = [],
                        a = [],
                        r = [];
                    for (e = 0; e < 12; e++) i = f([2e3, e]), n.push(this.monthsShort(i, "")), a.push(this.months(i, "")), r.push(this.months(i, "")), r.push(this.monthsShort(i, ""));
                    for (n.sort(t), a.sort(t), r.sort(t), e = 0; e < 12; e++) n[e] = ct(n[e]), a[e] = ct(a[e]);
                    for (e = 0; e < 24; e++) r[e] = ct(r[e]);
                    this._monthsRegex = new RegExp("^(" + r.join("|") + ")", "i"), this._monthsShortRegex = this._monthsRegex, this._monthsStrictRegex = new RegExp("^(" + a.join("|") + ")", "i"), this._monthsShortStrictRegex = new RegExp("^(" + n.join("|") + ")", "i")
                }

                function Vt(t) {
                    var e = new Date(Date.UTC.apply(null, arguments));
                    return t < 100 && t >= 0 && isFinite(e.getUTCFullYear()) && e.setUTCFullYear(t), e
                }

                function Bt(t, e, i) {
                    var n = 7 + e - i;
                    return -((7 + Vt(t, 0, n).getUTCDay() - e) % 7) + n - 1
                }

                function Et(t, e, i, n, a) {
                    var r, o, s = 1 + 7 * (e - 1) + (7 + i - n) % 7 + Bt(t, n, a);
                    return s <= 0 ? o = St(r = t - 1) + s : s > St(t) ? (r = t + 1, o = s - St(t)) : (r = t, o = s), {
                        year: r,
                        dayOfYear: o
                    }
                }

                function jt(t, e, i) {
                    var n, a, r = Bt(t.year(), e, i),
                        o = Math.floor((t.dayOfYear() - r - 1) / 7) + 1;
                    return o < 1 ? n = o + Ut(a = t.year() - 1, e, i) : o > Ut(t.year(), e, i) ? (n = o - Ut(t.year(), e, i), a = t.year() + 1) : (a = t.year(), n = o), {
                        week: n,
                        year: a
                    }
                }

                function Ut(t, e, i) {
                    var n = Bt(t, e, i),
                        a = Bt(t + 1, e, i);
                    return (St(t) - n + a) / 7
                }
                j("w", ["ww", 2], "wo", "week"), j("W", ["WW", 2], "Wo", "isoWeek"), R("week", "w"), R("isoWeek", "W"), N("week", 5), N("isoWeek", 5), dt("w", $), dt("ww", $, Z), dt("W", $), dt("WW", $, Z), mt(["w", "ww", "W", "WW"], function(t, e, i, n) {
                    e[n.substr(0, 1)] = w(t)
                });
                j("d", 0, "do", "day"), j("dd", 0, 0, function(t) {
                    return this.localeData().weekdaysMin(this, t)
                }), j("ddd", 0, 0, function(t) {
                    return this.localeData().weekdaysShort(this, t)
                }), j("dddd", 0, 0, function(t) {
                    return this.localeData().weekdays(this, t)
                }), j("e", 0, 0, "weekday"), j("E", 0, 0, "isoWeekday"), R("day", "d"), R("weekday", "e"), R("isoWeekday", "E"), N("day", 11), N("weekday", 11), N("isoWeekday", 11), dt("d", $), dt("e", $), dt("E", $), dt("dd", function(t, e) {
                    return e.weekdaysMinRegex(t)
                }), dt("ddd", function(t, e) {
                    return e.weekdaysShortRegex(t)
                }), dt("dddd", function(t, e) {
                    return e.weekdaysRegex(t)
                }), mt(["dd", "ddd", "dddd"], function(t, e, i, n) {
                    var a = i._locale.weekdaysParse(t, n, i._strict);
                    null != a ? e.d = a : g(i).invalidWeekday = t
                }), mt(["d", "e", "E"], function(t, e, i, n) {
                    e[n] = w(t)
                });
                var qt = "Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_");
                var Gt = "Sun_Mon_Tue_Wed_Thu_Fri_Sat".split("_");
                var Zt = "Su_Mo_Tu_We_Th_Fr_Sa".split("_");
                var Xt = lt;
                var Jt = lt;
                var Kt = lt;

                function $t() {
                    function t(t, e) {
                        return e.length - t.length
                    }
                    var e, i, n, a, r, o = [],
                        s = [],
                        l = [],
                        u = [];
                    for (e = 0; e < 7; e++) i = f([2e3, 1]).day(e), n = this.weekdaysMin(i, ""), a = this.weekdaysShort(i, ""), r = this.weekdays(i, ""), o.push(n), s.push(a), l.push(r), u.push(n), u.push(a), u.push(r);
                    for (o.sort(t), s.sort(t), l.sort(t), u.sort(t), e = 0; e < 7; e++) s[e] = ct(s[e]), l[e] = ct(l[e]), u[e] = ct(u[e]);
                    this._weekdaysRegex = new RegExp("^(" + u.join("|") + ")", "i"), this._weekdaysShortRegex = this._weekdaysRegex, this._weekdaysMinRegex = this._weekdaysRegex, this._weekdaysStrictRegex = new RegExp("^(" + l.join("|") + ")", "i"), this._weekdaysShortStrictRegex = new RegExp("^(" + s.join("|") + ")", "i"), this._weekdaysMinStrictRegex = new RegExp("^(" + o.join("|") + ")", "i")
                }

                function Qt() {
                    return this.hours() % 12 || 12
                }

                function te(t, e) {
                    j(t, 0, 0, function() {
                        return this.localeData().meridiem(this.hours(), this.minutes(), e)
                    })
                }

                function ee(t, e) {
                    return e._meridiemParse
                }
                j("H", ["HH", 2], 0, "hour"), j("h", ["hh", 2], 0, Qt), j("k", ["kk", 2], 0, function() {
                    return this.hours() || 24
                }), j("hmm", 0, 0, function() {
                    return "" + Qt.apply(this) + z(this.minutes(), 2)
                }), j("hmmss", 0, 0, function() {
                    return "" + Qt.apply(this) + z(this.minutes(), 2) + z(this.seconds(), 2)
                }), j("Hmm", 0, 0, function() {
                    return "" + this.hours() + z(this.minutes(), 2)
                }), j("Hmmss", 0, 0, function() {
                    return "" + this.hours() + z(this.minutes(), 2) + z(this.seconds(), 2)
                }), te("a", !0), te("A", !1), R("hour", "h"), N("hour", 13), dt("a", ee), dt("A", ee), dt("H", $), dt("h", $), dt("k", $), dt("HH", $, Z), dt("hh", $, Z), dt("kk", $, Z), dt("hmm", Q), dt("hmmss", tt), dt("Hmm", Q), dt("Hmmss", tt), gt(["H", "HH"], bt), gt(["k", "kk"], function(t, e, i) {
                    var n = w(t);
                    e[bt] = 24 === n ? 0 : n
                }), gt(["a", "A"], function(t, e, i) {
                    i._isPm = i._locale.isPM(t), i._meridiem = t
                }), gt(["h", "hh"], function(t, e, i) {
                    e[bt] = w(t), g(i).bigHour = !0
                }), gt("hmm", function(t, e, i) {
                    var n = t.length - 2;
                    e[bt] = w(t.substr(0, n)), e[xt] = w(t.substr(n)), g(i).bigHour = !0
                }), gt("hmmss", function(t, e, i) {
                    var n = t.length - 4,
                        a = t.length - 2;
                    e[bt] = w(t.substr(0, n)), e[xt] = w(t.substr(n, 2)), e[_t] = w(t.substr(a)), g(i).bigHour = !0
                }), gt("Hmm", function(t, e, i) {
                    var n = t.length - 2;
                    e[bt] = w(t.substr(0, n)), e[xt] = w(t.substr(n))
                }), gt("Hmmss", function(t, e, i) {
                    var n = t.length - 4,
                        a = t.length - 2;
                    e[bt] = w(t.substr(0, n)), e[xt] = w(t.substr(n, 2)), e[_t] = w(t.substr(a))
                });
                var ie, ne = Tt("Hours", !0),
                    ae = {
                        calendar: {
                            sameDay: "[Today at] LT",
                            nextDay: "[Tomorrow at] LT",
                            nextWeek: "dddd [at] LT",
                            lastDay: "[Yesterday at] LT",
                            lastWeek: "[Last] dddd [at] LT",
                            sameElse: "L"
                        },
                        longDateFormat: {
                            LTS: "h:mm:ss A",
                            LT: "h:mm A",
                            L: "MM/DD/YYYY",
                            LL: "MMMM D, YYYY",
                            LLL: "MMMM D, YYYY h:mm A",
                            LLLL: "dddd, MMMM D, YYYY h:mm A"
                        },
                        invalidDate: "Invalid date",
                        ordinal: "%d",
                        dayOfMonthOrdinalParse: /\d{1,2}/,
                        relativeTime: {
                            future: "in %s",
                            past: "%s ago",
                            s: "a few seconds",
                            ss: "%d seconds",
                            m: "a minute",
                            mm: "%d minutes",
                            h: "an hour",
                            hh: "%d hours",
                            d: "a day",
                            dd: "%d days",
                            M: "a month",
                            MM: "%d months",
                            y: "a year",
                            yy: "%d years"
                        },
                        months: Rt,
                        monthsShort: Lt,
                        week: {
                            dow: 0,
                            doy: 6
                        },
                        weekdays: qt,
                        weekdaysMin: Zt,
                        weekdaysShort: Gt,
                        meridiemParse: /[ap]\.?m?\.?/i
                    },
                    re = {},
                    oe = {};

                function se(t) {
                    return t ? t.toLowerCase().replace("_", "-") : t
                }

                function le(i) {
                    var n = null;
                    if (!re[i] && void 0 !== e && e && e.exports) try {
                        n = ie._abbr, t("./locale/" + i), ue(n)
                    } catch (t) {}
                    return re[i]
                }

                function ue(t, e) {
                    var i;
                    return t && (i = s(e) ? he(t) : de(t, e)) && (ie = i), ie._abbr
                }

                function de(t, e) {
                    if (null !== e) {
                        var i = ae;
                        if (e.abbr = t, null != re[t]) T("defineLocaleOverride", "use moment.updateLocale(localeName, config) to change an existing locale. moment.defineLocale(localeName, config) should only be used for creating a new locale See http://momentjs.com/guides/#/warnings/define-locale/ for more info."), i = re[t]._config;
                        else if (null != e.parentLocale) {
                            if (null == re[e.parentLocale]) return oe[e.parentLocale] || (oe[e.parentLocale] = []), oe[e.parentLocale].push({
                                name: t,
                                config: e
                            }), null;
                            i = re[e.parentLocale]._config
                        }
                        return re[t] = new A(I(i, e)), oe[t] && oe[t].forEach(function(t) {
                            de(t.name, t.config)
                        }), ue(t), re[t]
                    }
                    return delete re[t], null
                }

                function he(t) {
                    var e;
                    if (t && t._locale && t._locale._abbr && (t = t._locale._abbr), !t) return ie;
                    if (!r(t)) {
                        if (e = le(t)) return e;
                        t = [t]
                    }
                    return function(t) {
                        for (var e, i, n, a, r = 0; r < t.length;) {
                            for (e = (a = se(t[r]).split("-")).length, i = (i = se(t[r + 1])) ? i.split("-") : null; e > 0;) {
                                if (n = le(a.slice(0, e).join("-"))) return n;
                                if (i && i.length >= e && M(a, i, !0) >= e - 1) break;
                                e--
                            }
                            r++
                        }
                        return null
                    }(t)
                }

                function ce(t) {
                    var e, i = t._a;
                    return i && -2 === g(t).overflow && (e = i[vt] < 0 || i[vt] > 11 ? vt : i[yt] < 1 || i[yt] > At(i[pt], i[vt]) ? yt : i[bt] < 0 || i[bt] > 24 || 24 === i[bt] && (0 !== i[xt] || 0 !== i[_t] || 0 !== i[kt]) ? bt : i[xt] < 0 || i[xt] > 59 ? xt : i[_t] < 0 || i[_t] > 59 ? _t : i[kt] < 0 || i[kt] > 999 ? kt : -1, g(t)._overflowDayOfYear && (e < pt || e > yt) && (e = yt), g(t)._overflowWeeks && -1 === e && (e = wt), g(t)._overflowWeekday && -1 === e && (e = Mt), g(t).overflow = e), t
                }

                function fe(t, e, i) {
                    return null != t ? t : null != e ? e : i
                }

                function ge(t) {
                    var e, i, n, r, o, s = [];
                    if (!t._d) {
                        var l, u;
                        for (l = t, u = new Date(a.now()), n = l._useUTC ? [u.getUTCFullYear(), u.getUTCMonth(), u.getUTCDate()] : [u.getFullYear(), u.getMonth(), u.getDate()], t._w && null == t._a[yt] && null == t._a[vt] && function(t) {
                                var e, i, n, a, r, o, s, l;
                                if (null != (e = t._w).GG || null != e.W || null != e.E) r = 1, o = 4, i = fe(e.GG, t._a[pt], jt(Te(), 1, 4).year), n = fe(e.W, 1), ((a = fe(e.E, 1)) < 1 || a > 7) && (l = !0);
                                else {
                                    r = t._locale._week.dow, o = t._locale._week.doy;
                                    var u = jt(Te(), r, o);
                                    i = fe(e.gg, t._a[pt], u.year), n = fe(e.w, u.week), null != e.d ? ((a = e.d) < 0 || a > 6) && (l = !0) : null != e.e ? (a = e.e + r, (e.e < 0 || e.e > 6) && (l = !0)) : a = r
                                }
                                n < 1 || n > Ut(i, r, o) ? g(t)._overflowWeeks = !0 : null != l ? g(t)._overflowWeekday = !0 : (s = Et(i, n, a, r, o), t._a[pt] = s.year, t._dayOfYear = s.dayOfYear)
                            }(t), null != t._dayOfYear && (o = fe(t._a[pt], n[pt]), (t._dayOfYear > St(o) || 0 === t._dayOfYear) && (g(t)._overflowDayOfYear = !0), i = Vt(o, 0, t._dayOfYear), t._a[vt] = i.getUTCMonth(), t._a[yt] = i.getUTCDate()), e = 0; e < 3 && null == t._a[e]; ++e) t._a[e] = s[e] = n[e];
                        for (; e < 7; e++) t._a[e] = s[e] = null == t._a[e] ? 2 === e ? 1 : 0 : t._a[e];
                        24 === t._a[bt] && 0 === t._a[xt] && 0 === t._a[_t] && 0 === t._a[kt] && (t._nextDay = !0, t._a[bt] = 0), t._d = (t._useUTC ? Vt : function(t, e, i, n, a, r, o) {
                            var s = new Date(t, e, i, n, a, r, o);
                            return t < 100 && t >= 0 && isFinite(s.getFullYear()) && s.setFullYear(t), s
                        }).apply(null, s), r = t._useUTC ? t._d.getUTCDay() : t._d.getDay(), null != t._tzm && t._d.setUTCMinutes(t._d.getUTCMinutes() - t._tzm), t._nextDay && (t._a[bt] = 24), t._w && void 0 !== t._w.d && t._w.d !== r && (g(t).weekdayMismatch = !0)
                    }
                }
                var me = /^\s*((?:[+-]\d{6}|\d{4})-(?:\d\d-\d\d|W\d\d-\d|W\d\d|\d\d\d|\d\d))(?:(T| )(\d\d(?::\d\d(?::\d\d(?:[.,]\d+)?)?)?)([\+\-]\d\d(?::?\d\d)?|\s*Z)?)?$/,
                    pe = /^\s*((?:[+-]\d{6}|\d{4})(?:\d\d\d\d|W\d\d\d|W\d\d|\d\d\d|\d\d))(?:(T| )(\d\d(?:\d\d(?:\d\d(?:[.,]\d+)?)?)?)([\+\-]\d\d(?::?\d\d)?|\s*Z)?)?$/,
                    ve = /Z|[+-]\d\d(?::?\d\d)?/,
                    ye = [
                        ["YYYYYY-MM-DD", /[+-]\d{6}-\d\d-\d\d/],
                        ["YYYY-MM-DD", /\d{4}-\d\d-\d\d/],
                        ["GGGG-[W]WW-E", /\d{4}-W\d\d-\d/],
                        ["GGGG-[W]WW", /\d{4}-W\d\d/, !1],
                        ["YYYY-DDD", /\d{4}-\d{3}/],
                        ["YYYY-MM", /\d{4}-\d\d/, !1],
                        ["YYYYYYMMDD", /[+-]\d{10}/],
                        ["YYYYMMDD", /\d{8}/],
                        ["GGGG[W]WWE", /\d{4}W\d{3}/],
                        ["GGGG[W]WW", /\d{4}W\d{2}/, !1],
                        ["YYYYDDD", /\d{7}/]
                    ],
                    be = [
                        ["HH:mm:ss.SSSS", /\d\d:\d\d:\d\d\.\d+/],
                        ["HH:mm:ss,SSSS", /\d\d:\d\d:\d\d,\d+/],
                        ["HH:mm:ss", /\d\d:\d\d:\d\d/],
                        ["HH:mm", /\d\d:\d\d/],
                        ["HHmmss.SSSS", /\d\d\d\d\d\d\.\d+/],
                        ["HHmmss,SSSS", /\d\d\d\d\d\d,\d+/],
                        ["HHmmss", /\d\d\d\d\d\d/],
                        ["HHmm", /\d\d\d\d/],
                        ["HH", /\d\d/]
                    ],
                    xe = /^\/?Date\((\-?\d+)/i;

                function _e(t) {
                    var e, i, n, a, r, o, s = t._i,
                        l = me.exec(s) || pe.exec(s);
                    if (l) {
                        for (g(t).iso = !0, e = 0, i = ye.length; e < i; e++)
                            if (ye[e][1].exec(l[1])) {
                                a = ye[e][0], n = !1 !== ye[e][2];
                                break
                            }
                        if (null == a) return void(t._isValid = !1);
                        if (l[3]) {
                            for (e = 0, i = be.length; e < i; e++)
                                if (be[e][1].exec(l[3])) {
                                    r = (l[2] || " ") + be[e][0];
                                    break
                                }
                            if (null == r) return void(t._isValid = !1)
                        }
                        if (!n && null != r) return void(t._isValid = !1);
                        if (l[4]) {
                            if (!ve.exec(l[4])) return void(t._isValid = !1);
                            o = "Z"
                        }
                        t._f = a + (r || "") + (o || ""), De(t)
                    } else t._isValid = !1
                }
                var ke = /^(?:(Mon|Tue|Wed|Thu|Fri|Sat|Sun),?\s)?(\d{1,2})\s(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s(\d{2,4})\s(\d\d):(\d\d)(?::(\d\d))?\s(?:(UT|GMT|[ECMP][SD]T)|([Zz])|([+-]\d{4}))$/;

                function we(t, e, i, n, a, r) {
                    var o = [function(t) {
                        var e = parseInt(t, 10); {
                            if (e <= 49) return 2e3 + e;
                            if (e <= 999) return 1900 + e
                        }
                        return e
                    }(t), Lt.indexOf(e), parseInt(i, 10), parseInt(n, 10), parseInt(a, 10)];
                    return r && o.push(parseInt(r, 10)), o
                }
                var Me = {
                    UT: 0,
                    GMT: 0,
                    EDT: -240,
                    EST: -300,
                    CDT: -300,
                    CST: -360,
                    MDT: -360,
                    MST: -420,
                    PDT: -420,
                    PST: -480
                };

                function Se(t) {
                    var e, i, n, a = ke.exec(t._i.replace(/\([^)]*\)|[\n\t]/g, " ").replace(/(\s\s+)/g, " ").trim());
                    if (a) {
                        var r = we(a[4], a[3], a[2], a[5], a[6], a[7]);
                        if (e = a[1], i = r, n = t, e && Gt.indexOf(e) !== new Date(i[0], i[1], i[2]).getDay() && (g(n).weekdayMismatch = !0, n._isValid = !1, 1)) return;
                        t._a = r, t._tzm = function(t, e, i) {
                            if (t) return Me[t];
                            if (e) return 0;
                            var n = parseInt(i, 10),
                                a = n % 100;
                            return (n - a) / 100 * 60 + a
                        }(a[8], a[9], a[10]), t._d = Vt.apply(null, t._a), t._d.setUTCMinutes(t._d.getUTCMinutes() - t._tzm), g(t).rfc2822 = !0
                    } else t._isValid = !1
                }

                function De(t) {
                    if (t._f !== a.ISO_8601)
                        if (t._f !== a.RFC_2822) {
                            t._a = [], g(t).empty = !0;
                            var e, i, n, r, o, s, l, u, d = "" + t._i,
                                c = d.length,
                                f = 0;
                            for (n = q(t._f, t._locale).match(H) || [], e = 0; e < n.length; e++) r = n[e], (i = (d.match(ht(r, t)) || [])[0]) && ((o = d.substr(0, d.indexOf(i))).length > 0 && g(t).unusedInput.push(o), d = d.slice(d.indexOf(i) + i.length), f += i.length), E[r] ? (i ? g(t).empty = !1 : g(t).unusedTokens.push(r), s = r, u = t, null != (l = i) && h(ft, s) && ft[s](l, u._a, u, s)) : t._strict && !i && g(t).unusedTokens.push(r);
                            g(t).charsLeftOver = c - f, d.length > 0 && g(t).unusedInput.push(d), t._a[bt] <= 12 && !0 === g(t).bigHour && t._a[bt] > 0 && (g(t).bigHour = void 0), g(t).parsedDateParts = t._a.slice(0), g(t).meridiem = t._meridiem, t._a[bt] = function(t, e, i) {
                                var n;
                                if (null == i) return e;
                                return null != t.meridiemHour ? t.meridiemHour(e, i) : null != t.isPM ? ((n = t.isPM(i)) && e < 12 && (e += 12), n || 12 !== e || (e = 0), e) : e
                            }(t._locale, t._a[bt], t._meridiem), ge(t), ce(t)
                        } else Se(t);
                    else _e(t)
                }

                function Ce(t) {
                    var e, i, n, h, f = t._i,
                        v = t._f;
                    return t._locale = t._locale || he(t._l), null === f || void 0 === v && "" === f ? p({
                        nullInput: !0
                    }) : ("string" == typeof f && (t._i = f = t._locale.preparse(f)), _(f) ? new x(ce(f)) : (u(f) ? t._d = f : r(v) ? function(t) {
                        var e, i, n, a, r;
                        if (0 === t._f.length) return g(t).invalidFormat = !0, void(t._d = new Date(NaN));
                        for (a = 0; a < t._f.length; a++) r = 0, e = y({}, t), null != t._useUTC && (e._useUTC = t._useUTC), e._f = t._f[a], De(e), m(e) && (r += g(e).charsLeftOver, r += 10 * g(e).unusedTokens.length, g(e).score = r, (null == n || r < n) && (n = r, i = e));
                        c(t, i || e)
                    }(t) : v ? De(t) : s(i = (e = t)._i) ? e._d = new Date(a.now()) : u(i) ? e._d = new Date(i.valueOf()) : "string" == typeof i ? (n = e, null === (h = xe.exec(n._i)) ? (_e(n), !1 === n._isValid && (delete n._isValid, Se(n), !1 === n._isValid && (delete n._isValid, a.createFromInputFallback(n)))) : n._d = new Date(+h[1])) : r(i) ? (e._a = d(i.slice(0), function(t) {
                        return parseInt(t, 10)
                    }), ge(e)) : o(i) ? function(t) {
                        if (!t._d) {
                            var e = W(t._i);
                            t._a = d([e.year, e.month, e.day || e.date, e.hour, e.minute, e.second, e.millisecond], function(t) {
                                return t && parseInt(t, 10)
                            }), ge(t)
                        }
                    }(e) : l(i) ? e._d = new Date(i) : a.createFromInputFallback(e), m(t) || (t._d = null), t))
                }

                function Pe(t, e, i, n, a) {
                    var s, l = {};
                    return !0 !== i && !1 !== i || (n = i, i = void 0), (o(t) && function(t) {
                        if (Object.getOwnPropertyNames) return 0 === Object.getOwnPropertyNames(t).length;
                        var e;
                        for (e in t)
                            if (t.hasOwnProperty(e)) return !1;
                        return !0
                    }(t) || r(t) && 0 === t.length) && (t = void 0), l._isAMomentObject = !0, l._useUTC = l._isUTC = a, l._l = i, l._i = t, l._f = e, l._strict = n, (s = new x(ce(Ce(l))))._nextDay && (s.add(1, "d"), s._nextDay = void 0), s
                }

                function Te(t, e, i, n) {
                    return Pe(t, e, i, n, !1)
                }
                a.createFromInputFallback = D("value provided is not in a recognized RFC2822 or ISO format. moment construction falls back to js Date(), which is not reliable across all browsers and versions. Non RFC2822/ISO date formats are discouraged and will be removed in an upcoming major release. Please refer to http://momentjs.com/guides/#/warnings/js-date/ for more info.", function(t) {
                    t._d = new Date(t._i + (t._useUTC ? " UTC" : ""))
                }), a.ISO_8601 = function() {}, a.RFC_2822 = function() {};
                var Oe = D("moment().min is deprecated, use moment.max instead. http://momentjs.com/guides/#/warnings/min-max/", function() {
                        var t = Te.apply(null, arguments);
                        return this.isValid() && t.isValid() ? t < this ? this : t : p()
                    }),
                    Ie = D("moment().max is deprecated, use moment.min instead. http://momentjs.com/guides/#/warnings/min-max/", function() {
                        var t = Te.apply(null, arguments);
                        return this.isValid() && t.isValid() ? t > this ? this : t : p()
                    });

                function Ae(t, e) {
                    var i, n;
                    if (1 === e.length && r(e[0]) && (e = e[0]), !e.length) return Te();
                    for (i = e[0], n = 1; n < e.length; ++n) e[n].isValid() && !e[n][t](i) || (i = e[n]);
                    return i
                }
                var Fe = ["year", "quarter", "month", "week", "day", "hour", "minute", "second", "millisecond"];

                function Re(t) {
                    var e = W(t),
                        i = e.year || 0,
                        n = e.quarter || 0,
                        a = e.month || 0,
                        r = e.week || 0,
                        o = e.day || 0,
                        s = e.hour || 0,
                        l = e.minute || 0,
                        u = e.second || 0,
                        d = e.millisecond || 0;
                    this._isValid = function(t) {
                        for (var e in t)
                            if (-1 === Ct.call(Fe, e) || null != t[e] && isNaN(t[e])) return !1;
                        for (var i = !1, n = 0; n < Fe.length; ++n)
                            if (t[Fe[n]]) {
                                if (i) return !1;
                                parseFloat(t[Fe[n]]) !== w(t[Fe[n]]) && (i = !0)
                            }
                        return !0
                    }(e), this._milliseconds = +d + 1e3 * u + 6e4 * l + 1e3 * s * 60 * 60, this._days = +o + 7 * r, this._months = +a + 3 * n + 12 * i, this._data = {}, this._locale = he(), this._bubble()
                }

                function Le(t) {
                    return t instanceof Re
                }

                function We(t) {
                    return t < 0 ? -1 * Math.round(-1 * t) : Math.round(t)
                }

                function Ye(t, e) {
                    j(t, 0, 0, function() {
                        var t = this.utcOffset(),
                            i = "+";
                        return t < 0 && (t = -t, i = "-"), i + z(~~(t / 60), 2) + e + z(~~t % 60, 2)
                    })
                }
                Ye("Z", ":"), Ye("ZZ", ""), dt("Z", st), dt("ZZ", st), gt(["Z", "ZZ"], function(t, e, i) {
                    i._useUTC = !0, i._tzm = ze(st, t)
                });
                var Ne = /([\+\-]|\d\d)/gi;

                function ze(t, e) {
                    var i = (e || "").match(t);
                    if (null === i) return null;
                    var n = ((i[i.length - 1] || []) + "").match(Ne) || ["-", 0, 0],
                        a = 60 * n[1] + w(n[2]);
                    return 0 === a ? 0 : "+" === n[0] ? a : -a
                }

                function He(t, e) {
                    var i, n;
                    return e._isUTC ? (i = e.clone(), n = (_(t) || u(t) ? t.valueOf() : Te(t).valueOf()) - i.valueOf(), i._d.setTime(i._d.valueOf() + n), a.updateOffset(i, !1), i) : Te(t).local()
                }

                function Ve(t) {
                    return 15 * -Math.round(t._d.getTimezoneOffset() / 15)
                }

                function Be() {
                    return !!this.isValid() && (this._isUTC && 0 === this._offset)
                }
                a.updateOffset = function() {};
                var Ee = /^(\-|\+)?(?:(\d*)[. ])?(\d+)\:(\d+)(?:\:(\d+)(\.\d*)?)?$/,
                    je = /^(-|\+)?P(?:([-+]?[0-9,.]*)Y)?(?:([-+]?[0-9,.]*)M)?(?:([-+]?[0-9,.]*)W)?(?:([-+]?[0-9,.]*)D)?(?:T(?:([-+]?[0-9,.]*)H)?(?:([-+]?[0-9,.]*)M)?(?:([-+]?[0-9,.]*)S)?)?$/;

                function Ue(t, e) {
                    var i, n, a, r = t,
                        o = null;
                    return Le(t) ? r = {
                        ms: t._milliseconds,
                        d: t._days,
                        M: t._months
                    } : l(t) ? (r = {}, e ? r[e] = t : r.milliseconds = t) : (o = Ee.exec(t)) ? (i = "-" === o[1] ? -1 : 1, r = {
                        y: 0,
                        d: w(o[yt]) * i,
                        h: w(o[bt]) * i,
                        m: w(o[xt]) * i,
                        s: w(o[_t]) * i,
                        ms: w(We(1e3 * o[kt])) * i
                    }) : (o = je.exec(t)) ? (i = "-" === o[1] ? -1 : (o[1], 1), r = {
                        y: qe(o[2], i),
                        M: qe(o[3], i),
                        w: qe(o[4], i),
                        d: qe(o[5], i),
                        h: qe(o[6], i),
                        m: qe(o[7], i),
                        s: qe(o[8], i)
                    }) : null == r ? r = {} : "object" == typeof r && ("from" in r || "to" in r) && (a = function(t, e) {
                        var i;
                        if (!t.isValid() || !e.isValid()) return {
                            milliseconds: 0,
                            months: 0
                        };
                        e = He(e, t), t.isBefore(e) ? i = Ge(t, e) : ((i = Ge(e, t)).milliseconds = -i.milliseconds, i.months = -i.months);
                        return i
                    }(Te(r.from), Te(r.to)), (r = {}).ms = a.milliseconds, r.M = a.months), n = new Re(r), Le(t) && h(t, "_locale") && (n._locale = t._locale), n
                }

                function qe(t, e) {
                    var i = t && parseFloat(t.replace(",", "."));
                    return (isNaN(i) ? 0 : i) * e
                }

                function Ge(t, e) {
                    var i = {
                        milliseconds: 0,
                        months: 0
                    };
                    return i.months = e.month() - t.month() + 12 * (e.year() - t.year()), t.clone().add(i.months, "M").isAfter(e) && --i.months, i.milliseconds = +e - +t.clone().add(i.months, "M"), i
                }

                function Ze(t, e) {
                    return function(i, n) {
                        var a;
                        return null === n || isNaN(+n) || (T(e, "moment()." + e + "(period, number) is deprecated. Please use moment()." + e + "(number, period). See http://momentjs.com/guides/#/warnings/add-inverted-param/ for more info."), a = i, i = n, n = a), Xe(this, Ue(i = "string" == typeof i ? +i : i, n), t), this
                    }
                }

                function Xe(t, e, i, n) {
                    var r = e._milliseconds,
                        o = We(e._days),
                        s = We(e._months);
                    t.isValid() && (n = null == n || n, s && Wt(t, Ot(t, "Month") + s * i), o && It(t, "Date", Ot(t, "Date") + o * i), r && t._d.setTime(t._d.valueOf() + r * i), n && a.updateOffset(t, o || s))
                }
                Ue.fn = Re.prototype, Ue.invalid = function() {
                    return Ue(NaN)
                };
                var Je = Ze(1, "add"),
                    Ke = Ze(-1, "subtract");

                function $e(t, e) {
                    var i = 12 * (e.year() - t.year()) + (e.month() - t.month()),
                        n = t.clone().add(i, "months");
                    return -(i + (e - n < 0 ? (e - n) / (n - t.clone().add(i - 1, "months")) : (e - n) / (t.clone().add(i + 1, "months") - n))) || 0
                }

                function Qe(t) {
                    var e;
                    return void 0 === t ? this._locale._abbr : (null != (e = he(t)) && (this._locale = e), this)
                }
                a.defaultFormat = "YYYY-MM-DDTHH:mm:ssZ", a.defaultFormatUtc = "YYYY-MM-DDTHH:mm:ss[Z]";
                var ti = D("moment().lang() is deprecated. Instead, use moment().localeData() to get the language configuration. Use moment().locale() to change languages.", function(t) {
                    return void 0 === t ? this.localeData() : this.locale(t)
                });

                function ei() {
                    return this._locale
                }

                function ii(t, e) {
                    j(0, [t, t.length], 0, e)
                }

                function ni(t, e, i, n, a) {
                    var r;
                    return null == t ? jt(this, n, a).year : (e > (r = Ut(t, n, a)) && (e = r), function(t, e, i, n, a) {
                        var r = Et(t, e, i, n, a),
                            o = Vt(r.year, 0, r.dayOfYear);
                        return this.year(o.getUTCFullYear()), this.month(o.getUTCMonth()), this.date(o.getUTCDate()), this
                    }.call(this, t, e, i, n, a))
                }
                j(0, ["gg", 2], 0, function() {
                    return this.weekYear() % 100
                }), j(0, ["GG", 2], 0, function() {
                    return this.isoWeekYear() % 100
                }), ii("gggg", "weekYear"), ii("ggggg", "weekYear"), ii("GGGG", "isoWeekYear"), ii("GGGGG", "isoWeekYear"), R("weekYear", "gg"), R("isoWeekYear", "GG"), N("weekYear", 1), N("isoWeekYear", 1), dt("G", rt), dt("g", rt), dt("GG", $, Z), dt("gg", $, Z), dt("GGGG", it, J), dt("gggg", it, J), dt("GGGGG", nt, K), dt("ggggg", nt, K), mt(["gggg", "ggggg", "GGGG", "GGGGG"], function(t, e, i, n) {
                    e[n.substr(0, 2)] = w(t)
                }), mt(["gg", "GG"], function(t, e, i, n) {
                    e[n] = a.parseTwoDigitYear(t)
                }), j("Q", 0, "Qo", "quarter"), R("quarter", "Q"), N("quarter", 7), dt("Q", G), gt("Q", function(t, e) {
                    e[vt] = 3 * (w(t) - 1)
                }), j("D", ["DD", 2], "Do", "date"), R("date", "D"), N("date", 9), dt("D", $), dt("DD", $, Z), dt("Do", function(t, e) {
                    return t ? e._dayOfMonthOrdinalParse || e._ordinalParse : e._dayOfMonthOrdinalParseLenient
                }), gt(["D", "DD"], yt), gt("Do", function(t, e) {
                    e[yt] = w(t.match($)[0])
                });
                var ai = Tt("Date", !0);
                j("DDD", ["DDDD", 3], "DDDo", "dayOfYear"), R("dayOfYear", "DDD"), N("dayOfYear", 4), dt("DDD", et), dt("DDDD", X), gt(["DDD", "DDDD"], function(t, e, i) {
                    i._dayOfYear = w(t)
                }), j("m", ["mm", 2], 0, "minute"), R("minute", "m"), N("minute", 14), dt("m", $), dt("mm", $, Z), gt(["m", "mm"], xt);
                var ri = Tt("Minutes", !1);
                j("s", ["ss", 2], 0, "second"), R("second", "s"), N("second", 15), dt("s", $), dt("ss", $, Z), gt(["s", "ss"], _t);
                var oi, si = Tt("Seconds", !1);
                for (j("S", 0, 0, function() {
                        return ~~(this.millisecond() / 100)
                    }), j(0, ["SS", 2], 0, function() {
                        return ~~(this.millisecond() / 10)
                    }), j(0, ["SSS", 3], 0, "millisecond"), j(0, ["SSSS", 4], 0, function() {
                        return 10 * this.millisecond()
                    }), j(0, ["SSSSS", 5], 0, function() {
                        return 100 * this.millisecond()
                    }), j(0, ["SSSSSS", 6], 0, function() {
                        return 1e3 * this.millisecond()
                    }), j(0, ["SSSSSSS", 7], 0, function() {
                        return 1e4 * this.millisecond()
                    }), j(0, ["SSSSSSSS", 8], 0, function() {
                        return 1e5 * this.millisecond()
                    }), j(0, ["SSSSSSSSS", 9], 0, function() {
                        return 1e6 * this.millisecond()
                    }), R("millisecond", "ms"), N("millisecond", 16), dt("S", et, G), dt("SS", et, Z), dt("SSS", et, X), oi = "SSSS"; oi.length <= 9; oi += "S") dt(oi, at);

                function li(t, e) {
                    e[kt] = w(1e3 * ("0." + t))
                }
                for (oi = "S"; oi.length <= 9; oi += "S") gt(oi, li);
                var ui = Tt("Milliseconds", !1);
                j("z", 0, 0, "zoneAbbr"), j("zz", 0, 0, "zoneName");
                var di = x.prototype;

                function hi(t) {
                    return t
                }
                di.add = Je, di.calendar = function(t, e) {
                    var i = t || Te(),
                        n = He(i, this).startOf("day"),
                        r = a.calendarFormat(this, n) || "sameElse",
                        o = e && (O(e[r]) ? e[r].call(this, i) : e[r]);
                    return this.format(o || this.localeData().calendar(r, this, Te(i)))
                }, di.clone = function() {
                    return new x(this)
                }, di.diff = function(t, e, i) {
                    var n, a, r;
                    if (!this.isValid()) return NaN;
                    if (!(n = He(t, this)).isValid()) return NaN;
                    switch (a = 6e4 * (n.utcOffset() - this.utcOffset()), e = L(e)) {
                        case "year":
                            r = $e(this, n) / 12;
                            break;
                        case "month":
                            r = $e(this, n);
                            break;
                        case "quarter":
                            r = $e(this, n) / 3;
                            break;
                        case "second":
                            r = (this - n) / 1e3;
                            break;
                        case "minute":
                            r = (this - n) / 6e4;
                            break;
                        case "hour":
                            r = (this - n) / 36e5;
                            break;
                        case "day":
                            r = (this - n - a) / 864e5;
                            break;
                        case "week":
                            r = (this - n - a) / 6048e5;
                            break;
                        default:
                            r = this - n
                    }
                    return i ? r : k(r)
                }, di.endOf = function(t) {
                    return void 0 === (t = L(t)) || "millisecond" === t ? this : ("date" === t && (t = "day"), this.startOf(t).add(1, "isoWeek" === t ? "week" : t).subtract(1, "ms"))
                }, di.format = function(t) {
                    t || (t = this.isUtc() ? a.defaultFormatUtc : a.defaultFormat);
                    var e = U(this, t);
                    return this.localeData().postformat(e)
                }, di.from = function(t, e) {
                    return this.isValid() && (_(t) && t.isValid() || Te(t).isValid()) ? Ue({
                        to: this,
                        from: t
                    }).locale(this.locale()).humanize(!e) : this.localeData().invalidDate()
                }, di.fromNow = function(t) {
                    return this.from(Te(), t)
                }, di.to = function(t, e) {
                    return this.isValid() && (_(t) && t.isValid() || Te(t).isValid()) ? Ue({
                        from: this,
                        to: t
                    }).locale(this.locale()).humanize(!e) : this.localeData().invalidDate()
                }, di.toNow = function(t) {
                    return this.to(Te(), t)
                }, di.get = function(t) {
                    return O(this[t = L(t)]) ? this[t]() : this
                }, di.invalidAt = function() {
                    return g(this).overflow
                }, di.isAfter = function(t, e) {
                    var i = _(t) ? t : Te(t);
                    return !(!this.isValid() || !i.isValid()) && ("millisecond" === (e = L(s(e) ? "millisecond" : e)) ? this.valueOf() > i.valueOf() : i.valueOf() < this.clone().startOf(e).valueOf())
                }, di.isBefore = function(t, e) {
                    var i = _(t) ? t : Te(t);
                    return !(!this.isValid() || !i.isValid()) && ("millisecond" === (e = L(s(e) ? "millisecond" : e)) ? this.valueOf() < i.valueOf() : this.clone().endOf(e).valueOf() < i.valueOf())
                }, di.isBetween = function(t, e, i, n) {
                    return ("(" === (n = n || "()")[0] ? this.isAfter(t, i) : !this.isBefore(t, i)) && (")" === n[1] ? this.isBefore(e, i) : !this.isAfter(e, i))
                }, di.isSame = function(t, e) {
                    var i, n = _(t) ? t : Te(t);
                    return !(!this.isValid() || !n.isValid()) && ("millisecond" === (e = L(e || "millisecond")) ? this.valueOf() === n.valueOf() : (i = n.valueOf(), this.clone().startOf(e).valueOf() <= i && i <= this.clone().endOf(e).valueOf()))
                }, di.isSameOrAfter = function(t, e) {
                    return this.isSame(t, e) || this.isAfter(t, e)
                }, di.isSameOrBefore = function(t, e) {
                    return this.isSame(t, e) || this.isBefore(t, e)
                }, di.isValid = function() {
                    return m(this)
                }, di.lang = ti, di.locale = Qe, di.localeData = ei, di.max = Ie, di.min = Oe, di.parsingFlags = function() {
                    return c({}, g(this))
                }, di.set = function(t, e) {
                    if ("object" == typeof t)
                        for (var i = function(t) {
                                var e = [];
                                for (var i in t) e.push({
                                    unit: i,
                                    priority: Y[i]
                                });
                                return e.sort(function(t, e) {
                                    return t.priority - e.priority
                                }), e
                            }(t = W(t)), n = 0; n < i.length; n++) this[i[n].unit](t[i[n].unit]);
                    else if (O(this[t = L(t)])) return this[t](e);
                    return this
                }, di.startOf = function(t) {
                    switch (t = L(t)) {
                        case "year":
                            this.month(0);
                        case "quarter":
                        case "month":
                            this.date(1);
                        case "week":
                        case "isoWeek":
                        case "day":
                        case "date":
                            this.hours(0);
                        case "hour":
                            this.minutes(0);
                        case "minute":
                            this.seconds(0);
                        case "second":
                            this.milliseconds(0)
                    }
                    return "week" === t && this.weekday(0), "isoWeek" === t && this.isoWeekday(1), "quarter" === t && this.month(3 * Math.floor(this.month() / 3)), this
                }, di.subtract = Ke, di.toArray = function() {
                    var t = this;
                    return [t.year(), t.month(), t.date(), t.hour(), t.minute(), t.second(), t.millisecond()]
                }, di.toObject = function() {
                    var t = this;
                    return {
                        years: t.year(),
                        months: t.month(),
                        date: t.date(),
                        hours: t.hours(),
                        minutes: t.minutes(),
                        seconds: t.seconds(),
                        milliseconds: t.milliseconds()
                    }
                }, di.toDate = function() {
                    return new Date(this.valueOf())
                }, di.toISOString = function(t) {
                    if (!this.isValid()) return null;
                    var e = !0 !== t,
                        i = e ? this.clone().utc() : this;
                    return i.year() < 0 || i.year() > 9999 ? U(i, e ? "YYYYYY-MM-DD[T]HH:mm:ss.SSS[Z]" : "YYYYYY-MM-DD[T]HH:mm:ss.SSSZ") : O(Date.prototype.toISOString) ? e ? this.toDate().toISOString() : new Date(this._d.valueOf()).toISOString().replace("Z", U(i, "Z")) : U(i, e ? "YYYY-MM-DD[T]HH:mm:ss.SSS[Z]" : "YYYY-MM-DD[T]HH:mm:ss.SSSZ")
                }, di.inspect = function() {
                    if (!this.isValid()) return "moment.invalid(/* " + this._i + " */)";
                    var t = "moment",
                        e = "";
                    this.isLocal() || (t = 0 === this.utcOffset() ? "moment.utc" : "moment.parseZone", e = "Z");
                    var i = "[" + t + '("]',
                        n = 0 <= this.year() && this.year() <= 9999 ? "YYYY" : "YYYYYY",
                        a = e + '[")]';
                    return this.format(i + n + "-MM-DD[T]HH:mm:ss.SSS" + a)
                }, di.toJSON = function() {
                    return this.isValid() ? this.toISOString() : null
                }, di.toString = function() {
                    return this.clone().locale("en").format("ddd MMM DD YYYY HH:mm:ss [GMT]ZZ")
                }, di.unix = function() {
                    return Math.floor(this.valueOf() / 1e3)
                }, di.valueOf = function() {
                    return this._d.valueOf() - 6e4 * (this._offset || 0)
                }, di.creationData = function() {
                    return {
                        input: this._i,
                        format: this._f,
                        locale: this._locale,
                        isUTC: this._isUTC,
                        strict: this._strict
                    }
                }, di.year = Pt, di.isLeapYear = function() {
                    return Dt(this.year())
                }, di.weekYear = function(t) {
                    return ni.call(this, t, this.week(), this.weekday(), this.localeData()._week.dow, this.localeData()._week.doy)
                }, di.isoWeekYear = function(t) {
                    return ni.call(this, t, this.isoWeek(), this.isoWeekday(), 1, 4)
                }, di.quarter = di.quarters = function(t) {
                    return null == t ? Math.ceil((this.month() + 1) / 3) : this.month(3 * (t - 1) + this.month() % 3)
                }, di.month = Yt, di.daysInMonth = function() {
                    return At(this.year(), this.month())
                }, di.week = di.weeks = function(t) {
                    var e = this.localeData().week(this);
                    return null == t ? e : this.add(7 * (t - e), "d")
                }, di.isoWeek = di.isoWeeks = function(t) {
                    var e = jt(this, 1, 4).week;
                    return null == t ? e : this.add(7 * (t - e), "d")
                }, di.weeksInYear = function() {
                    var t = this.localeData()._week;
                    return Ut(this.year(), t.dow, t.doy)
                }, di.isoWeeksInYear = function() {
                    return Ut(this.year(), 1, 4)
                }, di.date = ai, di.day = di.days = function(t) {
                    if (!this.isValid()) return null != t ? this : NaN;
                    var e, i, n = this._isUTC ? this._d.getUTCDay() : this._d.getDay();
                    return null != t ? (e = t, i = this.localeData(), t = "string" != typeof e ? e : isNaN(e) ? "number" == typeof(e = i.weekdaysParse(e)) ? e : null : parseInt(e, 10), this.add(t - n, "d")) : n
                }, di.weekday = function(t) {
                    if (!this.isValid()) return null != t ? this : NaN;
                    var e = (this.day() + 7 - this.localeData()._week.dow) % 7;
                    return null == t ? e : this.add(t - e, "d")
                }, di.isoWeekday = function(t) {
                    if (!this.isValid()) return null != t ? this : NaN;
                    if (null != t) {
                        var e = (i = t, n = this.localeData(), "string" == typeof i ? n.weekdaysParse(i) % 7 || 7 : isNaN(i) ? null : i);
                        return this.day(this.day() % 7 ? e : e - 7)
                    }
                    return this.day() || 7;
                    var i, n
                }, di.dayOfYear = function(t) {
                    var e = Math.round((this.clone().startOf("day") - this.clone().startOf("year")) / 864e5) + 1;
                    return null == t ? e : this.add(t - e, "d")
                }, di.hour = di.hours = ne, di.minute = di.minutes = ri, di.second = di.seconds = si, di.millisecond = di.milliseconds = ui, di.utcOffset = function(t, e, i) {
                    var n, r = this._offset || 0;
                    if (!this.isValid()) return null != t ? this : NaN;
                    if (null != t) {
                        if ("string" == typeof t) {
                            if (null === (t = ze(st, t))) return this
                        } else Math.abs(t) < 16 && !i && (t *= 60);
                        return !this._isUTC && e && (n = Ve(this)), this._offset = t, this._isUTC = !0, null != n && this.add(n, "m"), r !== t && (!e || this._changeInProgress ? Xe(this, Ue(t - r, "m"), 1, !1) : this._changeInProgress || (this._changeInProgress = !0, a.updateOffset(this, !0), this._changeInProgress = null)), this
                    }
                    return this._isUTC ? r : Ve(this)
                }, di.utc = function(t) {
                    return this.utcOffset(0, t)
                }, di.local = function(t) {
                    return this._isUTC && (this.utcOffset(0, t), this._isUTC = !1, t && this.subtract(Ve(this), "m")), this
                }, di.parseZone = function() {
                    if (null != this._tzm) this.utcOffset(this._tzm, !1, !0);
                    else if ("string" == typeof this._i) {
                        var t = ze(ot, this._i);
                        null != t ? this.utcOffset(t) : this.utcOffset(0, !0)
                    }
                    return this
                }, di.hasAlignedHourOffset = function(t) {
                    return !!this.isValid() && (t = t ? Te(t).utcOffset() : 0, (this.utcOffset() - t) % 60 == 0)
                }, di.isDST = function() {
                    return this.utcOffset() > this.clone().month(0).utcOffset() || this.utcOffset() > this.clone().month(5).utcOffset()
                }, di.isLocal = function() {
                    return !!this.isValid() && !this._isUTC
                }, di.isUtcOffset = function() {
                    return !!this.isValid() && this._isUTC
                }, di.isUtc = Be, di.isUTC = Be, di.zoneAbbr = function() {
                    return this._isUTC ? "UTC" : ""
                }, di.zoneName = function() {
                    return this._isUTC ? "Coordinated Universal Time" : ""
                }, di.dates = D("dates accessor is deprecated. Use date instead.", ai), di.months = D("months accessor is deprecated. Use month instead", Yt), di.years = D("years accessor is deprecated. Use year instead", Pt), di.zone = D("moment().zone is deprecated, use moment().utcOffset instead. http://momentjs.com/guides/#/warnings/zone/", function(t, e) {
                    return null != t ? ("string" != typeof t && (t = -t), this.utcOffset(t, e), this) : -this.utcOffset()
                }), di.isDSTShifted = D("isDSTShifted is deprecated. See http://momentjs.com/guides/#/warnings/dst-shifted/ for more information", function() {
                    if (!s(this._isDSTShifted)) return this._isDSTShifted;
                    var t = {};
                    if (y(t, this), (t = Ce(t))._a) {
                        var e = t._isUTC ? f(t._a) : Te(t._a);
                        this._isDSTShifted = this.isValid() && M(t._a, e.toArray()) > 0
                    } else this._isDSTShifted = !1;
                    return this._isDSTShifted
                });
                var ci = A.prototype;

                function fi(t, e, i, n) {
                    var a = he(),
                        r = f().set(n, e);
                    return a[i](r, t)
                }

                function gi(t, e, i) {
                    if (l(t) && (e = t, t = void 0), t = t || "", null != e) return fi(t, e, i, "month");
                    var n, a = [];
                    for (n = 0; n < 12; n++) a[n] = fi(t, n, i, "month");
                    return a
                }

                function mi(t, e, i, n) {
                    "boolean" == typeof t ? (l(e) && (i = e, e = void 0), e = e || "") : (i = e = t, t = !1, l(e) && (i = e, e = void 0), e = e || "");
                    var a, r = he(),
                        o = t ? r._week.dow : 0;
                    if (null != i) return fi(e, (i + o) % 7, n, "day");
                    var s = [];
                    for (a = 0; a < 7; a++) s[a] = fi(e, (a + o) % 7, n, "day");
                    return s
                }
                ci.calendar = function(t, e, i) {
                    var n = this._calendar[t] || this._calendar.sameElse;
                    return O(n) ? n.call(e, i) : n
                }, ci.longDateFormat = function(t) {
                    var e = this._longDateFormat[t],
                        i = this._longDateFormat[t.toUpperCase()];
                    return e || !i ? e : (this._longDateFormat[t] = i.replace(/MMMM|MM|DD|dddd/g, function(t) {
                        return t.slice(1)
                    }), this._longDateFormat[t])
                }, ci.invalidDate = function() {
                    return this._invalidDate
                }, ci.ordinal = function(t) {
                    return this._ordinal.replace("%d", t)
                }, ci.preparse = hi, ci.postformat = hi, ci.relativeTime = function(t, e, i, n) {
                    var a = this._relativeTime[i];
                    return O(a) ? a(t, e, i, n) : a.replace(/%d/i, t)
                }, ci.pastFuture = function(t, e) {
                    var i = this._relativeTime[t > 0 ? "future" : "past"];
                    return O(i) ? i(e) : i.replace(/%s/i, e)
                }, ci.set = function(t) {
                    var e, i;
                    for (i in t) O(e = t[i]) ? this[i] = e : this["_" + i] = e;
                    this._config = t, this._dayOfMonthOrdinalParseLenient = new RegExp((this._dayOfMonthOrdinalParse.source || this._ordinalParse.source) + "|" + /\d{1,2}/.source)
                }, ci.months = function(t, e) {
                    return t ? r(this._months) ? this._months[t.month()] : this._months[(this._months.isFormat || Ft).test(e) ? "format" : "standalone"][t.month()] : r(this._months) ? this._months : this._months.standalone
                }, ci.monthsShort = function(t, e) {
                    return t ? r(this._monthsShort) ? this._monthsShort[t.month()] : this._monthsShort[Ft.test(e) ? "format" : "standalone"][t.month()] : r(this._monthsShort) ? this._monthsShort : this._monthsShort.standalone
                }, ci.monthsParse = function(t, e, i) {
                    var n, a, r;
                    if (this._monthsParseExact) return function(t, e, i) {
                        var n, a, r, o = t.toLocaleLowerCase();
                        if (!this._monthsParse)
                            for (this._monthsParse = [], this._longMonthsParse = [], this._shortMonthsParse = [], n = 0; n < 12; ++n) r = f([2e3, n]), this._shortMonthsParse[n] = this.monthsShort(r, "").toLocaleLowerCase(), this._longMonthsParse[n] = this.months(r, "").toLocaleLowerCase();
                        return i ? "MMM" === e ? -1 !== (a = Ct.call(this._shortMonthsParse, o)) ? a : null : -1 !== (a = Ct.call(this._longMonthsParse, o)) ? a : null : "MMM" === e ? -1 !== (a = Ct.call(this._shortMonthsParse, o)) ? a : -1 !== (a = Ct.call(this._longMonthsParse, o)) ? a : null : -1 !== (a = Ct.call(this._longMonthsParse, o)) ? a : -1 !== (a = Ct.call(this._shortMonthsParse, o)) ? a : null
                    }.call(this, t, e, i);
                    for (this._monthsParse || (this._monthsParse = [], this._longMonthsParse = [], this._shortMonthsParse = []), n = 0; n < 12; n++) {
                        if (a = f([2e3, n]), i && !this._longMonthsParse[n] && (this._longMonthsParse[n] = new RegExp("^" + this.months(a, "").replace(".", "") + "$", "i"), this._shortMonthsParse[n] = new RegExp("^" + this.monthsShort(a, "").replace(".", "") + "$", "i")), i || this._monthsParse[n] || (r = "^" + this.months(a, "") + "|^" + this.monthsShort(a, ""), this._monthsParse[n] = new RegExp(r.replace(".", ""), "i")), i && "MMMM" === e && this._longMonthsParse[n].test(t)) return n;
                        if (i && "MMM" === e && this._shortMonthsParse[n].test(t)) return n;
                        if (!i && this._monthsParse[n].test(t)) return n
                    }
                }, ci.monthsRegex = function(t) {
                    return this._monthsParseExact ? (h(this, "_monthsRegex") || Ht.call(this), t ? this._monthsStrictRegex : this._monthsRegex) : (h(this, "_monthsRegex") || (this._monthsRegex = zt), this._monthsStrictRegex && t ? this._monthsStrictRegex : this._monthsRegex)
                }, ci.monthsShortRegex = function(t) {
                    return this._monthsParseExact ? (h(this, "_monthsRegex") || Ht.call(this), t ? this._monthsShortStrictRegex : this._monthsShortRegex) : (h(this, "_monthsShortRegex") || (this._monthsShortRegex = Nt), this._monthsShortStrictRegex && t ? this._monthsShortStrictRegex : this._monthsShortRegex)
                }, ci.week = function(t) {
                    return jt(t, this._week.dow, this._week.doy).week
                }, ci.firstDayOfYear = function() {
                    return this._week.doy
                }, ci.firstDayOfWeek = function() {
                    return this._week.dow
                }, ci.weekdays = function(t, e) {
                    return t ? r(this._weekdays) ? this._weekdays[t.day()] : this._weekdays[this._weekdays.isFormat.test(e) ? "format" : "standalone"][t.day()] : r(this._weekdays) ? this._weekdays : this._weekdays.standalone
                }, ci.weekdaysMin = function(t) {
                    return t ? this._weekdaysMin[t.day()] : this._weekdaysMin
                }, ci.weekdaysShort = function(t) {
                    return t ? this._weekdaysShort[t.day()] : this._weekdaysShort
                }, ci.weekdaysParse = function(t, e, i) {
                    var n, a, r;
                    if (this._weekdaysParseExact) return function(t, e, i) {
                        var n, a, r, o = t.toLocaleLowerCase();
                        if (!this._weekdaysParse)
                            for (this._weekdaysParse = [], this._shortWeekdaysParse = [], this._minWeekdaysParse = [], n = 0; n < 7; ++n) r = f([2e3, 1]).day(n), this._minWeekdaysParse[n] = this.weekdaysMin(r, "").toLocaleLowerCase(), this._shortWeekdaysParse[n] = this.weekdaysShort(r, "").toLocaleLowerCase(), this._weekdaysParse[n] = this.weekdays(r, "").toLocaleLowerCase();
                        return i ? "dddd" === e ? -1 !== (a = Ct.call(this._weekdaysParse, o)) ? a : null : "ddd" === e ? -1 !== (a = Ct.call(this._shortWeekdaysParse, o)) ? a : null : -1 !== (a = Ct.call(this._minWeekdaysParse, o)) ? a : null : "dddd" === e ? -1 !== (a = Ct.call(this._weekdaysParse, o)) ? a : -1 !== (a = Ct.call(this._shortWeekdaysParse, o)) ? a : -1 !== (a = Ct.call(this._minWeekdaysParse, o)) ? a : null : "ddd" === e ? -1 !== (a = Ct.call(this._shortWeekdaysParse, o)) ? a : -1 !== (a = Ct.call(this._weekdaysParse, o)) ? a : -1 !== (a = Ct.call(this._minWeekdaysParse, o)) ? a : null : -1 !== (a = Ct.call(this._minWeekdaysParse, o)) ? a : -1 !== (a = Ct.call(this._weekdaysParse, o)) ? a : -1 !== (a = Ct.call(this._shortWeekdaysParse, o)) ? a : null
                    }.call(this, t, e, i);
                    for (this._weekdaysParse || (this._weekdaysParse = [], this._minWeekdaysParse = [], this._shortWeekdaysParse = [], this._fullWeekdaysParse = []), n = 0; n < 7; n++) {
                        if (a = f([2e3, 1]).day(n), i && !this._fullWeekdaysParse[n] && (this._fullWeekdaysParse[n] = new RegExp("^" + this.weekdays(a, "").replace(".", ".?") + "$", "i"), this._shortWeekdaysParse[n] = new RegExp("^" + this.weekdaysShort(a, "").replace(".", ".?") + "$", "i"), this._minWeekdaysParse[n] = new RegExp("^" + this.weekdaysMin(a, "").replace(".", ".?") + "$", "i")), this._weekdaysParse[n] || (r = "^" + this.weekdays(a, "") + "|^" + this.weekdaysShort(a, "") + "|^" + this.weekdaysMin(a, ""), this._weekdaysParse[n] = new RegExp(r.replace(".", ""), "i")), i && "dddd" === e && this._fullWeekdaysParse[n].test(t)) return n;
                        if (i && "ddd" === e && this._shortWeekdaysParse[n].test(t)) return n;
                        if (i && "dd" === e && this._minWeekdaysParse[n].test(t)) return n;
                        if (!i && this._weekdaysParse[n].test(t)) return n
                    }
                }, ci.weekdaysRegex = function(t) {
                    return this._weekdaysParseExact ? (h(this, "_weekdaysRegex") || $t.call(this), t ? this._weekdaysStrictRegex : this._weekdaysRegex) : (h(this, "_weekdaysRegex") || (this._weekdaysRegex = Xt), this._weekdaysStrictRegex && t ? this._weekdaysStrictRegex : this._weekdaysRegex)
                }, ci.weekdaysShortRegex = function(t) {
                    return this._weekdaysParseExact ? (h(this, "_weekdaysRegex") || $t.call(this), t ? this._weekdaysShortStrictRegex : this._weekdaysShortRegex) : (h(this, "_weekdaysShortRegex") || (this._weekdaysShortRegex = Jt), this._weekdaysShortStrictRegex && t ? this._weekdaysShortStrictRegex : this._weekdaysShortRegex)
                }, ci.weekdaysMinRegex = function(t) {
                    return this._weekdaysParseExact ? (h(this, "_weekdaysRegex") || $t.call(this), t ? this._weekdaysMinStrictRegex : this._weekdaysMinRegex) : (h(this, "_weekdaysMinRegex") || (this._weekdaysMinRegex = Kt), this._weekdaysMinStrictRegex && t ? this._weekdaysMinStrictRegex : this._weekdaysMinRegex)
                }, ci.isPM = function(t) {
                    return "p" === (t + "").toLowerCase().charAt(0)
                }, ci.meridiem = function(t, e, i) {
                    return t > 11 ? i ? "pm" : "PM" : i ? "am" : "AM"
                }, ue("en", {
                    dayOfMonthOrdinalParse: /\d{1,2}(th|st|nd|rd)/,
                    ordinal: function(t) {
                        var e = t % 10;
                        return t + (1 === w(t % 100 / 10) ? "th" : 1 === e ? "st" : 2 === e ? "nd" : 3 === e ? "rd" : "th")
                    }
                }), a.lang = D("moment.lang is deprecated. Use moment.locale instead.", ue), a.langData = D("moment.langData is deprecated. Use moment.localeData instead.", he);
                var pi = Math.abs;

                function vi(t, e, i, n) {
                    var a = Ue(e, i);
                    return t._milliseconds += n * a._milliseconds, t._days += n * a._days, t._months += n * a._months, t._bubble()
                }

                function yi(t) {
                    return t < 0 ? Math.floor(t) : Math.ceil(t)
                }

                function bi(t) {
                    return 4800 * t / 146097
                }

                function xi(t) {
                    return 146097 * t / 4800
                }

                function _i(t) {
                    return function() {
                        return this.as(t)
                    }
                }
                var ki = _i("ms"),
                    wi = _i("s"),
                    Mi = _i("m"),
                    Si = _i("h"),
                    Di = _i("d"),
                    Ci = _i("w"),
                    Pi = _i("M"),
                    Ti = _i("y");

                function Oi(t) {
                    return function() {
                        return this.isValid() ? this._data[t] : NaN
                    }
                }
                var Ii = Oi("milliseconds"),
                    Ai = Oi("seconds"),
                    Fi = Oi("minutes"),
                    Ri = Oi("hours"),
                    Li = Oi("days"),
                    Wi = Oi("months"),
                    Yi = Oi("years");
                var Ni = Math.round,
                    zi = {
                        ss: 44,
                        s: 45,
                        m: 45,
                        h: 22,
                        d: 26,
                        M: 11
                    };
                var Hi = Math.abs;

                function Vi(t) {
                    return (t > 0) - (t < 0) || +t
                }

                function Bi() {
                    if (!this.isValid()) return this.localeData().invalidDate();
                    var t, e, i = Hi(this._milliseconds) / 1e3,
                        n = Hi(this._days),
                        a = Hi(this._months);
                    e = k((t = k(i / 60)) / 60), i %= 60, t %= 60;
                    var r = k(a / 12),
                        o = a %= 12,
                        s = n,
                        l = e,
                        u = t,
                        d = i ? i.toFixed(3).replace(/\.?0+$/, "") : "",
                        h = this.asSeconds();
                    if (!h) return "P0D";
                    var c = h < 0 ? "-" : "",
                        f = Vi(this._months) !== Vi(h) ? "-" : "",
                        g = Vi(this._days) !== Vi(h) ? "-" : "",
                        m = Vi(this._milliseconds) !== Vi(h) ? "-" : "";
                    return c + "P" + (r ? f + r + "Y" : "") + (o ? f + o + "M" : "") + (s ? g + s + "D" : "") + (l || u || d ? "T" : "") + (l ? m + l + "H" : "") + (u ? m + u + "M" : "") + (d ? m + d + "S" : "")
                }
                var Ei = Re.prototype;
                return Ei.isValid = function() {
                    return this._isValid
                }, Ei.abs = function() {
                    var t = this._data;
                    return this._milliseconds = pi(this._milliseconds), this._days = pi(this._days), this._months = pi(this._months), t.milliseconds = pi(t.milliseconds), t.seconds = pi(t.seconds), t.minutes = pi(t.minutes), t.hours = pi(t.hours), t.months = pi(t.months), t.years = pi(t.years), this
                }, Ei.add = function(t, e) {
                    return vi(this, t, e, 1)
                }, Ei.subtract = function(t, e) {
                    return vi(this, t, e, -1)
                }, Ei.as = function(t) {
                    if (!this.isValid()) return NaN;
                    var e, i, n = this._milliseconds;
                    if ("month" === (t = L(t)) || "year" === t) return e = this._days + n / 864e5, i = this._months + bi(e), "month" === t ? i : i / 12;
                    switch (e = this._days + Math.round(xi(this._months)), t) {
                        case "week":
                            return e / 7 + n / 6048e5;
                        case "day":
                            return e + n / 864e5;
                        case "hour":
                            return 24 * e + n / 36e5;
                        case "minute":
                            return 1440 * e + n / 6e4;
                        case "second":
                            return 86400 * e + n / 1e3;
                        case "millisecond":
                            return Math.floor(864e5 * e) + n;
                        default:
                            throw new Error("Unknown unit " + t)
                    }
                }, Ei.asMilliseconds = ki, Ei.asSeconds = wi, Ei.asMinutes = Mi, Ei.asHours = Si, Ei.asDays = Di, Ei.asWeeks = Ci, Ei.asMonths = Pi, Ei.asYears = Ti, Ei.valueOf = function() {
                    return this.isValid() ? this._milliseconds + 864e5 * this._days + this._months % 12 * 2592e6 + 31536e6 * w(this._months / 12) : NaN
                }, Ei._bubble = function() {
                    var t, e, i, n, a, r = this._milliseconds,
                        o = this._days,
                        s = this._months,
                        l = this._data;
                    return r >= 0 && o >= 0 && s >= 0 || r <= 0 && o <= 0 && s <= 0 || (r += 864e5 * yi(xi(s) + o), o = 0, s = 0), l.milliseconds = r % 1e3, t = k(r / 1e3), l.seconds = t % 60, e = k(t / 60), l.minutes = e % 60, i = k(e / 60), l.hours = i % 24, s += a = k(bi(o += k(i / 24))), o -= yi(xi(a)), n = k(s / 12), s %= 12, l.days = o, l.months = s, l.years = n, this
                }, Ei.clone = function() {
                    return Ue(this)
                }, Ei.get = function(t) {
                    return t = L(t), this.isValid() ? this[t + "s"]() : NaN
                }, Ei.milliseconds = Ii, Ei.seconds = Ai, Ei.minutes = Fi, Ei.hours = Ri, Ei.days = Li, Ei.weeks = function() {
                    return k(this.days() / 7)
                }, Ei.months = Wi, Ei.years = Yi, Ei.humanize = function(t) {
                    if (!this.isValid()) return this.localeData().invalidDate();
                    var e, i, n, a, r, o, s, l, u, d, h, c = this.localeData(),
                        f = (i = !t, n = c, a = Ue(e = this).abs(), r = Ni(a.as("s")), o = Ni(a.as("m")), s = Ni(a.as("h")), l = Ni(a.as("d")), u = Ni(a.as("M")), d = Ni(a.as("y")), (h = r <= zi.ss && ["s", r] || r < zi.s && ["ss", r] || o <= 1 && ["m"] || o < zi.m && ["mm", o] || s <= 1 && ["h"] || s < zi.h && ["hh", s] || l <= 1 && ["d"] || l < zi.d && ["dd", l] || u <= 1 && ["M"] || u < zi.M && ["MM", u] || d <= 1 && ["y"] || ["yy", d])[2] = i, h[3] = +e > 0, h[4] = n, function(t, e, i, n, a) {
                            return a.relativeTime(e || 1, !!i, t, n)
                        }.apply(null, h));
                    return t && (f = c.pastFuture(+this, f)), c.postformat(f)
                }, Ei.toISOString = Bi, Ei.toString = Bi, Ei.toJSON = Bi, Ei.locale = Qe, Ei.localeData = ei, Ei.toIsoString = D("toIsoString() is deprecated. Please use toISOString() instead (notice the capitals)", Bi), Ei.lang = ti, j("X", 0, 0, "unix"), j("x", 0, 0, "valueOf"), dt("x", rt), dt("X", /[+-]?\d+(\.\d{1,3})?/), gt("X", function(t, e, i) {
                    i._d = new Date(1e3 * parseFloat(t, 10))
                }), gt("x", function(t, e, i) {
                    i._d = new Date(w(t))
                }), a.version = "2.20.1", i = Te, a.fn = di, a.min = function() {
                    return Ae("isBefore", [].slice.call(arguments, 0))
                }, a.max = function() {
                    return Ae("isAfter", [].slice.call(arguments, 0))
                }, a.now = function() {
                    return Date.now ? Date.now() : +new Date
                }, a.utc = f, a.unix = function(t) {
                    return Te(1e3 * t)
                }, a.months = function(t, e) {
                    return gi(t, e, "months")
                }, a.isDate = u, a.locale = ue, a.invalid = p, a.duration = Ue, a.isMoment = _, a.weekdays = function(t, e, i) {
                    return mi(t, e, i, "weekdays")
                }, a.parseZone = function() {
                    return Te.apply(null, arguments).parseZone()
                }, a.localeData = he, a.isDuration = Le, a.monthsShort = function(t, e) {
                    return gi(t, e, "monthsShort")
                }, a.weekdaysMin = function(t, e, i) {
                    return mi(t, e, i, "weekdaysMin")
                }, a.defineLocale = de, a.updateLocale = function(t, e) {
                    if (null != e) {
                        var i, n, a = ae;
                        null != (n = le(t)) && (a = n._config), (i = new A(e = I(a, e))).parentLocale = re[t], re[t] = i, ue(t)
                    } else null != re[t] && (null != re[t].parentLocale ? re[t] = re[t].parentLocale : null != re[t] && delete re[t]);
                    return re[t]
                }, a.locales = function() {
                    return C(re)
                }, a.weekdaysShort = function(t, e, i) {
                    return mi(t, e, i, "weekdaysShort")
                }, a.normalizeUnits = L, a.relativeTimeRounding = function(t) {
                    return void 0 === t ? Ni : "function" == typeof t && (Ni = t, !0)
                }, a.relativeTimeThreshold = function(t, e) {
                    return void 0 !== zi[t] && (void 0 === e ? zi[t] : (zi[t] = e, "s" === t && (zi.ss = e - 1), !0))
                }, a.calendarFormat = function(t, e) {
                    var i = t.diff(e, "days", !0);
                    return i < -6 ? "sameElse" : i < -1 ? "lastWeek" : i < 0 ? "lastDay" : i < 1 ? "sameDay" : i < 2 ? "nextDay" : i < 7 ? "nextWeek" : "sameElse"
                }, a.prototype = di, a.HTML5_FMT = {
                    DATETIME_LOCAL: "YYYY-MM-DDTHH:mm",
                    DATETIME_LOCAL_SECONDS: "YYYY-MM-DDTHH:mm:ss",
                    DATETIME_LOCAL_MS: "YYYY-MM-DDTHH:mm:ss.SSS",
                    DATE: "YYYY-MM-DD",
                    TIME: "HH:mm",
                    TIME_SECONDS: "HH:mm:ss",
                    TIME_MS: "HH:mm:ss.SSS",
                    WEEK: "YYYY-[W]WW",
                    MONTH: "YYYY-MM"
                }, a
            }, "object" == typeof i && void 0 !== e ? e.exports = a() : n.moment = a()
        }, {}],
        7: [function(t, e, i) {
            var n = t(29)();
            n.helpers = t(45), t(27)(n), n.defaults = t(25), n.Element = t(26), n.elements = t(40), n.Interaction = t(28), n.layouts = t(30), n.platform = t(48), n.plugins = t(31), n.Ticks = t(34), t(22)(n), t(23)(n), t(24)(n), t(33)(n), t(32)(n), t(35)(n), t(55)(n), t(53)(n), t(54)(n), t(56)(n), t(57)(n), t(58)(n), t(15)(n), t(16)(n), t(17)(n), t(18)(n), t(19)(n), t(20)(n), t(21)(n), t(8)(n), t(9)(n), t(10)(n), t(11)(n), t(12)(n), t(13)(n), t(14)(n);
            var a = t(49);
            for (var r in a) a.hasOwnProperty(r) && n.plugins.register(a[r]);
            n.platform.initialize(), e.exports = n, "undefined" != typeof window && (window.Chart = n), n.Legend = a.legend._element, n.Title = a.title._element, n.pluginService = n.plugins, n.PluginBase = n.Element.extend({}), n.canvasHelpers = n.helpers.canvas, n.layoutService = n.layouts
        }, {
            10: 10,
            11: 11,
            12: 12,
            13: 13,
            14: 14,
            15: 15,
            16: 16,
            17: 17,
            18: 18,
            19: 19,
            20: 20,
            21: 21,
            22: 22,
            23: 23,
            24: 24,
            25: 25,
            26: 26,
            27: 27,
            28: 28,
            29: 29,
            30: 30,
            31: 31,
            32: 32,
            33: 33,
            34: 34,
            35: 35,
            40: 40,
            45: 45,
            48: 48,
            49: 49,
            53: 53,
            54: 54,
            55: 55,
            56: 56,
            57: 57,
            58: 58,
            8: 8,
            9: 9
        }],
        8: [function(t, e, i) {
            "use strict";
            e.exports = function(t) {
                t.Bar = function(e, i) {
                    return i.type = "bar", new t(e, i)
                }
            }
        }, {}],
        9: [function(t, e, i) {
            "use strict";
            e.exports = function(t) {
                t.Bubble = function(e, i) {
                    return i.type = "bubble", new t(e, i)
                }
            }
        }, {}],
        10: [function(t, e, i) {
            "use strict";
            e.exports = function(t) {
                t.Doughnut = function(e, i) {
                    return i.type = "doughnut", new t(e, i)
                }
            }
        }, {}],
        11: [function(t, e, i) {
            "use strict";
            e.exports = function(t) {
                t.Line = function(e, i) {
                    return i.type = "line", new t(e, i)
                }
            }
        }, {}],
        12: [function(t, e, i) {
            "use strict";
            e.exports = function(t) {
                t.PolarArea = function(e, i) {
                    return i.type = "polarArea", new t(e, i)
                }
            }
        }, {}],
        13: [function(t, e, i) {
            "use strict";
            e.exports = function(t) {
                t.Radar = function(e, i) {
                    return i.type = "radar", new t(e, i)
                }
            }
        }, {}],
        14: [function(t, e, i) {
            "use strict";
            e.exports = function(t) {
                t.Scatter = function(e, i) {
                    return i.type = "scatter", new t(e, i)
                }
            }
        }, {}],
        15: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(40),
                r = t(45);
            n._set("bar", {
                hover: {
                    mode: "label"
                },
                scales: {
                    xAxes: [{
                        type: "category",
                        categoryPercentage: .8,
                        barPercentage: .9,
                        offset: !0,
                        gridLines: {
                            offsetGridLines: !0
                        }
                    }],
                    yAxes: [{
                        type: "linear"
                    }]
                }
            }), n._set("horizontalBar", {
                hover: {
                    mode: "index",
                    axis: "y"
                },
                scales: {
                    xAxes: [{
                        type: "linear",
                        position: "bottom"
                    }],
                    yAxes: [{
                        position: "left",
                        type: "category",
                        categoryPercentage: .8,
                        barPercentage: .9,
                        offset: !0,
                        gridLines: {
                            offsetGridLines: !0
                        }
                    }]
                },
                elements: {
                    rectangle: {
                        borderSkipped: "left"
                    }
                },
                tooltips: {
                    callbacks: {
                        title: function(t, e) {
                            var i = "";
                            return t.length > 0 && (t[0].yLabel ? i = t[0].yLabel : e.labels.length > 0 && t[0].index < e.labels.length && (i = e.labels[t[0].index])), i
                        },
                        label: function(t, e) {
                            return (e.datasets[t.datasetIndex].label || "") + ": " + t.xLabel
                        }
                    },
                    mode: "index",
                    axis: "y"
                }
            }), e.exports = function(t) {
                t.controllers.bar = t.DatasetController.extend({
                    dataElementType: a.Rectangle,
                    initialize: function() {
                        var e;
                        t.DatasetController.prototype.initialize.apply(this, arguments), (e = this.getMeta()).stack = this.getDataset().stack, e.bar = !0
                    },
                    update: function(t) {
                        var e, i, n = this.getMeta().data;
                        for (this._ruler = this.getRuler(), e = 0, i = n.length; e < i; ++e) this.updateElement(n[e], e, t)
                    },
                    updateElement: function(t, e, i) {
                        var n = this,
                            a = n.chart,
                            o = n.getMeta(),
                            s = n.getDataset(),
                            l = t.custom || {},
                            u = a.options.elements.rectangle;
                        t._xScale = n.getScaleForId(o.xAxisID), t._yScale = n.getScaleForId(o.yAxisID), t._datasetIndex = n.index, t._index = e, t._model = {
                            datasetLabel: s.label,
                            label: a.data.labels[e],
                            borderSkipped: l.borderSkipped ? l.borderSkipped : u.borderSkipped,
                            backgroundColor: l.backgroundColor ? l.backgroundColor : r.valueAtIndexOrDefault(s.backgroundColor, e, u.backgroundColor),
                            borderColor: l.borderColor ? l.borderColor : r.valueAtIndexOrDefault(s.borderColor, e, u.borderColor),
                            borderWidth: l.borderWidth ? l.borderWidth : r.valueAtIndexOrDefault(s.borderWidth, e, u.borderWidth)
                        }, n.updateElementGeometry(t, e, i), t.pivot()
                    },
                    updateElementGeometry: function(t, e, i) {
                        var n = this,
                            a = t._model,
                            r = n.getValueScale(),
                            o = r.getBasePixel(),
                            s = r.isHorizontal(),
                            l = n._ruler || n.getRuler(),
                            u = n.calculateBarValuePixels(n.index, e),
                            d = n.calculateBarIndexPixels(n.index, e, l);
                        a.horizontal = s, a.base = i ? o : u.base, a.x = s ? i ? o : u.head : d.center, a.y = s ? d.center : i ? o : u.head, a.height = s ? d.size : void 0, a.width = s ? void 0 : d.size
                    },
                    getValueScaleId: function() {
                        return this.getMeta().yAxisID
                    },
                    getIndexScaleId: function() {
                        return this.getMeta().xAxisID
                    },
                    getValueScale: function() {
                        return this.getScaleForId(this.getValueScaleId())
                    },
                    getIndexScale: function() {
                        return this.getScaleForId(this.getIndexScaleId())
                    },
                    _getStacks: function(t) {
                        var e, i, n = this.chart,
                            a = this.getIndexScale().options.stacked,
                            r = void 0 === t ? n.data.datasets.length : t + 1,
                            o = [];
                        for (e = 0; e < r; ++e)(i = n.getDatasetMeta(e)).bar && n.isDatasetVisible(e) && (!1 === a || !0 === a && -1 === o.indexOf(i.stack) || void 0 === a && (void 0 === i.stack || -1 === o.indexOf(i.stack))) && o.push(i.stack);
                        return o
                    },
                    getStackCount: function() {
                        return this._getStacks().length
                    },
                    getStackIndex: function(t, e) {
                        var i = this._getStacks(t),
                            n = void 0 !== e ? i.indexOf(e) : -1;
                        return -1 === n ? i.length - 1 : n
                    },
                    getRuler: function() {
                        var t, e, i = this.getIndexScale(),
                            n = this.getStackCount(),
                            a = this.index,
                            o = i.isHorizontal(),
                            s = o ? i.left : i.top,
                            l = s + (o ? i.width : i.height),
                            u = [];
                        for (t = 0, e = this.getMeta().data.length; t < e; ++t) u.push(i.getPixelForValue(null, t, a));
                        return {
                            min: r.isNullOrUndef(i.options.barThickness) ? function(t, e) {
                                var i, n, a, r, o = t.isHorizontal() ? t.width : t.height,
                                    s = t.getTicks();
                                for (a = 1, r = e.length; a < r; ++a) o = Math.min(o, e[a] - e[a - 1]);
                                for (a = 0, r = s.length; a < r; ++a) n = t.getPixelForTick(a), o = a > 0 ? Math.min(o, n - i) : o, i = n;
                                return o
                            }(i, u) : -1,
                            pixels: u,
                            start: s,
                            end: l,
                            stackCount: n,
                            scale: i
                        }
                    },
                    calculateBarValuePixels: function(t, e) {
                        var i, n, a, r, o, s, l = this.chart,
                            u = this.getMeta(),
                            d = this.getValueScale(),
                            h = l.data.datasets,
                            c = d.getRightValue(h[t].data[e]),
                            f = d.options.stacked,
                            g = u.stack,
                            m = 0;
                        if (f || void 0 === f && void 0 !== g)
                            for (i = 0; i < t; ++i)(n = l.getDatasetMeta(i)).bar && n.stack === g && n.controller.getValueScaleId() === d.id && l.isDatasetVisible(i) && (a = d.getRightValue(h[i].data[e]), (c < 0 && a < 0 || c >= 0 && a > 0) && (m += a));
                        return r = d.getPixelForValue(m), {
                            size: s = ((o = d.getPixelForValue(m + c)) - r) / 2,
                            base: r,
                            head: o,
                            center: o + s / 2
                        }
                    },
                    calculateBarIndexPixels: function(t, e, i) {
                        var n, a, o, s, l, u, d, h, c, f, g, m, p, v, y, b, x, _ = i.scale.options,
                            k = "flex" === _.barThickness ? (c = e, g = _, p = (f = i).pixels, v = p[c], y = c > 0 ? p[c - 1] : null, b = c < p.length - 1 ? p[c + 1] : null, x = g.categoryPercentage, null === y && (y = v - (null === b ? f.end - v : b - v)), null === b && (b = v + v - y), m = v - (v - y) / 2 * x, {
                                chunk: (b - y) / 2 * x / f.stackCount,
                                ratio: g.barPercentage,
                                start: m
                            }) : (n = e, a = i, u = (o = _).barThickness, d = a.stackCount, h = a.pixels[n], r.isNullOrUndef(u) ? (s = a.min * o.categoryPercentage, l = o.barPercentage) : (s = u * d, l = 1), {
                                chunk: s / d,
                                ratio: l,
                                start: h - s / 2
                            }),
                            w = this.getStackIndex(t, this.getMeta().stack),
                            M = k.start + k.chunk * w + k.chunk / 2,
                            S = Math.min(r.valueOrDefault(_.maxBarThickness, 1 / 0), k.chunk * k.ratio);
                        return {
                            base: M - S / 2,
                            head: M + S / 2,
                            center: M,
                            size: S
                        }
                    },
                    draw: function() {
                        var t = this.chart,
                            e = this.getValueScale(),
                            i = this.getMeta().data,
                            n = this.getDataset(),
                            a = i.length,
                            o = 0;
                        for (r.canvas.clipArea(t.ctx, t.chartArea); o < a; ++o) isNaN(e.getRightValue(n.data[o])) || i[o].draw();
                        r.canvas.unclipArea(t.ctx)
                    },
                    setHoverStyle: function(t) {
                        var e = this.chart.data.datasets[t._datasetIndex],
                            i = t._index,
                            n = t.custom || {},
                            a = t._model;
                        a.backgroundColor = n.hoverBackgroundColor ? n.hoverBackgroundColor : r.valueAtIndexOrDefault(e.hoverBackgroundColor, i, r.getHoverColor(a.backgroundColor)), a.borderColor = n.hoverBorderColor ? n.hoverBorderColor : r.valueAtIndexOrDefault(e.hoverBorderColor, i, r.getHoverColor(a.borderColor)), a.borderWidth = n.hoverBorderWidth ? n.hoverBorderWidth : r.valueAtIndexOrDefault(e.hoverBorderWidth, i, a.borderWidth)
                    },
                    removeHoverStyle: function(t) {
                        var e = this.chart.data.datasets[t._datasetIndex],
                            i = t._index,
                            n = t.custom || {},
                            a = t._model,
                            o = this.chart.options.elements.rectangle;
                        a.backgroundColor = n.backgroundColor ? n.backgroundColor : r.valueAtIndexOrDefault(e.backgroundColor, i, o.backgroundColor), a.borderColor = n.borderColor ? n.borderColor : r.valueAtIndexOrDefault(e.borderColor, i, o.borderColor), a.borderWidth = n.borderWidth ? n.borderWidth : r.valueAtIndexOrDefault(e.borderWidth, i, o.borderWidth)
                    }
                }), t.controllers.horizontalBar = t.controllers.bar.extend({
                    getValueScaleId: function() {
                        return this.getMeta().xAxisID
                    },
                    getIndexScaleId: function() {
                        return this.getMeta().yAxisID
                    }
                })
            }
        }, {
            25: 25,
            40: 40,
            45: 45
        }],
        16: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(40),
                r = t(45);
            n._set("bubble", {
                hover: {
                    mode: "single"
                },
                scales: {
                    xAxes: [{
                        type: "linear",
                        position: "bottom",
                        id: "x-axis-0"
                    }],
                    yAxes: [{
                        type: "linear",
                        position: "left",
                        id: "y-axis-0"
                    }]
                },
                tooltips: {
                    callbacks: {
                        title: function() {
                            return ""
                        },
                        label: function(t, e) {
                            var i = e.datasets[t.datasetIndex].label || "",
                                n = e.datasets[t.datasetIndex].data[t.index];
                            return i + ": (" + t.xLabel + ", " + t.yLabel + ", " + n.r + ")"
                        }
                    }
                }
            }), e.exports = function(t) {
                t.controllers.bubble = t.DatasetController.extend({
                    dataElementType: a.Point,
                    update: function(t) {
                        var e = this,
                            i = e.getMeta().data;
                        r.each(i, function(i, n) {
                            e.updateElement(i, n, t)
                        })
                    },
                    updateElement: function(t, e, i) {
                        var n = this,
                            a = n.getMeta(),
                            r = t.custom || {},
                            o = n.getScaleForId(a.xAxisID),
                            s = n.getScaleForId(a.yAxisID),
                            l = n._resolveElementOptions(t, e),
                            u = n.getDataset().data[e],
                            d = n.index,
                            h = i ? o.getPixelForDecimal(.5) : o.getPixelForValue("object" == typeof u ? u : NaN, e, d),
                            c = i ? s.getBasePixel() : s.getPixelForValue(u, e, d);
                        t._xScale = o, t._yScale = s, t._options = l, t._datasetIndex = d, t._index = e, t._model = {
                            backgroundColor: l.backgroundColor,
                            borderColor: l.borderColor,
                            borderWidth: l.borderWidth,
                            hitRadius: l.hitRadius,
                            pointStyle: l.pointStyle,
                            radius: i ? 0 : l.radius,
                            skip: r.skip || isNaN(h) || isNaN(c),
                            x: h,
                            y: c
                        }, t.pivot()
                    },
                    setHoverStyle: function(t) {
                        var e = t._model,
                            i = t._options;
                        e.backgroundColor = r.valueOrDefault(i.hoverBackgroundColor, r.getHoverColor(i.backgroundColor)), e.borderColor = r.valueOrDefault(i.hoverBorderColor, r.getHoverColor(i.borderColor)), e.borderWidth = r.valueOrDefault(i.hoverBorderWidth, i.borderWidth), e.radius = i.radius + i.hoverRadius
                    },
                    removeHoverStyle: function(t) {
                        var e = t._model,
                            i = t._options;
                        e.backgroundColor = i.backgroundColor, e.borderColor = i.borderColor, e.borderWidth = i.borderWidth, e.radius = i.radius
                    },
                    _resolveElementOptions: function(t, e) {
                        var i, n, a, o = this.chart,
                            s = o.data.datasets[this.index],
                            l = t.custom || {},
                            u = o.options.elements.point,
                            d = r.options.resolve,
                            h = s.data[e],
                            c = {},
                            f = {
                                chart: o,
                                dataIndex: e,
                                dataset: s,
                                datasetIndex: this.index
                            },
                            g = ["backgroundColor", "borderColor", "borderWidth", "hoverBackgroundColor", "hoverBorderColor", "hoverBorderWidth", "hoverRadius", "hitRadius", "pointStyle"];
                        for (i = 0, n = g.length; i < n; ++i) c[a = g[i]] = d([l[a], s[a], u[a]], f, e);
                        return c.radius = d([l.radius, h ? h.r : void 0, s.radius, u.radius], f, e), c
                    }
                })
            }
        }, {
            25: 25,
            40: 40,
            45: 45
        }],
        17: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(40),
                r = t(45);
            n._set("doughnut", {
                animation: {
                    animateRotate: !0,
                    animateScale: !1
                },
                hover: {
                    mode: "single"
                },
                legendCallback: function(t) {
                    var e = [];
                    e.push('<ul class="' + t.id + '-legend">');
                    var i = t.data,
                        n = i.datasets,
                        a = i.labels;
                    if (n.length)
                        for (var r = 0; r < n[0].data.length; ++r) e.push('<li><span style="background-color:' + n[0].backgroundColor[r] + '"></span>'), a[r] && e.push(a[r]), e.push("</li>");
                    return e.push("</ul>"), e.join("")
                },
                legend: {
                    labels: {
                        generateLabels: function(t) {
                            var e = t.data;
                            return e.labels.length && e.datasets.length ? e.labels.map(function(i, n) {
                                var a = t.getDatasetMeta(0),
                                    o = e.datasets[0],
                                    s = a.data[n],
                                    l = s && s.custom || {},
                                    u = r.valueAtIndexOrDefault,
                                    d = t.options.elements.arc;
                                return {
                                    text: i,
                                    fillStyle: l.backgroundColor ? l.backgroundColor : u(o.backgroundColor, n, d.backgroundColor),
                                    strokeStyle: l.borderColor ? l.borderColor : u(o.borderColor, n, d.borderColor),
                                    lineWidth: l.borderWidth ? l.borderWidth : u(o.borderWidth, n, d.borderWidth),
                                    hidden: isNaN(o.data[n]) || a.data[n].hidden,
                                    index: n
                                }
                            }) : []
                        }
                    },
                    onClick: function(t, e) {
                        var i, n, a, r = e.index,
                            o = this.chart;
                        for (i = 0, n = (o.data.datasets || []).length; i < n; ++i)(a = o.getDatasetMeta(i)).data[r] && (a.data[r].hidden = !a.data[r].hidden);
                        o.update()
                    }
                },
                cutoutPercentage: 50,
                rotation: -.5 * Math.PI,
                circumference: 2 * Math.PI,
                tooltips: {
                    callbacks: {
                        title: function() {
                            return ""
                        },
                        label: function(t, e) {
                            var i = e.labels[t.index],
                                n = ": " + e.datasets[t.datasetIndex].data[t.index];
                            return r.isArray(i) ? (i = i.slice())[0] += n : i += n, i
                        }
                    }
                }
            }), n._set("pie", r.clone(n.doughnut)), n._set("pie", {
                cutoutPercentage: 0
            }), e.exports = function(t) {
                t.controllers.doughnut = t.controllers.pie = t.DatasetController.extend({
                    dataElementType: a.Arc,
                    linkScales: r.noop,
                    getRingIndex: function(t) {
                        for (var e = 0, i = 0; i < t; ++i) this.chart.isDatasetVisible(i) && ++e;
                        return e
                    },
                    update: function(t) {
                        var e = this,
                            i = e.chart,
                            n = i.chartArea,
                            a = i.options,
                            o = a.elements.arc,
                            s = n.right - n.left - o.borderWidth,
                            l = n.bottom - n.top - o.borderWidth,
                            u = Math.min(s, l),
                            d = {
                                x: 0,
                                y: 0
                            },
                            h = e.getMeta(),
                            c = a.cutoutPercentage,
                            f = a.circumference;
                        if (f < 2 * Math.PI) {
                            var g = a.rotation % (2 * Math.PI),
                                m = (g += 2 * Math.PI * (g >= Math.PI ? -1 : g < -Math.PI ? 1 : 0)) + f,
                                p = Math.cos(g),
                                v = Math.sin(g),
                                y = Math.cos(m),
                                b = Math.sin(m),
                                x = g <= 0 && m >= 0 || g <= 2 * Math.PI && 2 * Math.PI <= m,
                                _ = g <= .5 * Math.PI && .5 * Math.PI <= m || g <= 2.5 * Math.PI && 2.5 * Math.PI <= m,
                                k = g <= -Math.PI && -Math.PI <= m || g <= Math.PI && Math.PI <= m,
                                w = g <= .5 * -Math.PI && .5 * -Math.PI <= m || g <= 1.5 * Math.PI && 1.5 * Math.PI <= m,
                                M = c / 100,
                                S = k ? -1 : Math.min(p * (p < 0 ? 1 : M), y * (y < 0 ? 1 : M)),
                                D = w ? -1 : Math.min(v * (v < 0 ? 1 : M), b * (b < 0 ? 1 : M)),
                                C = x ? 1 : Math.max(p * (p > 0 ? 1 : M), y * (y > 0 ? 1 : M)),
                                P = _ ? 1 : Math.max(v * (v > 0 ? 1 : M), b * (b > 0 ? 1 : M)),
                                T = .5 * (C - S),
                                O = .5 * (P - D);
                            u = Math.min(s / T, l / O), d = {
                                x: -.5 * (C + S),
                                y: -.5 * (P + D)
                            }
                        }
                        i.borderWidth = e.getMaxBorderWidth(h.data), i.outerRadius = Math.max((u - i.borderWidth) / 2, 0), i.innerRadius = Math.max(c ? i.outerRadius / 100 * c : 0, 0), i.radiusLength = (i.outerRadius - i.innerRadius) / i.getVisibleDatasetCount(), i.offsetX = d.x * i.outerRadius, i.offsetY = d.y * i.outerRadius, h.total = e.calculateTotal(), e.outerRadius = i.outerRadius - i.radiusLength * e.getRingIndex(e.index), e.innerRadius = Math.max(e.outerRadius - i.radiusLength, 0), r.each(h.data, function(i, n) {
                            e.updateElement(i, n, t)
                        })
                    },
                    updateElement: function(t, e, i) {
                        var n = this,
                            a = n.chart,
                            o = a.chartArea,
                            s = a.options,
                            l = s.animation,
                            u = (o.left + o.right) / 2,
                            d = (o.top + o.bottom) / 2,
                            h = s.rotation,
                            c = s.rotation,
                            f = n.getDataset(),
                            g = i && l.animateRotate ? 0 : t.hidden ? 0 : n.calculateCircumference(f.data[e]) * (s.circumference / (2 * Math.PI)),
                            m = i && l.animateScale ? 0 : n.innerRadius,
                            p = i && l.animateScale ? 0 : n.outerRadius,
                            v = r.valueAtIndexOrDefault;
                        r.extend(t, {
                            _datasetIndex: n.index,
                            _index: e,
                            _model: {
                                x: u + a.offsetX,
                                y: d + a.offsetY,
                                startAngle: h,
                                endAngle: c,
                                circumference: g,
                                outerRadius: p,
                                innerRadius: m,
                                label: v(f.label, e, a.data.labels[e])
                            }
                        });
                        var y = t._model;
                        this.removeHoverStyle(t), i && l.animateRotate || (y.startAngle = 0 === e ? s.rotation : n.getMeta().data[e - 1]._model.endAngle, y.endAngle = y.startAngle + y.circumference), t.pivot()
                    },
                    removeHoverStyle: function(e) {
                        t.DatasetController.prototype.removeHoverStyle.call(this, e, this.chart.options.elements.arc)
                    },
                    calculateTotal: function() {
                        var t, e = this.getDataset(),
                            i = this.getMeta(),
                            n = 0;
                        return r.each(i.data, function(i, a) {
                            t = e.data[a], isNaN(t) || i.hidden || (n += Math.abs(t))
                        }), n
                    },
                    calculateCircumference: function(t) {
                        var e = this.getMeta().total;
                        return e > 0 && !isNaN(t) ? 2 * Math.PI * (Math.abs(t) / e) : 0
                    },
                    getMaxBorderWidth: function(t) {
                        for (var e, i, n = 0, a = this.index, r = t.length, o = 0; o < r; o++) e = t[o]._model ? t[o]._model.borderWidth : 0, n = (i = t[o]._chart ? t[o]._chart.config.data.datasets[a].hoverBorderWidth : 0) > (n = e > n ? e : n) ? i : n;
                        return n
                    }
                })
            }
        }, {
            25: 25,
            40: 40,
            45: 45
        }],
        18: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(40),
                r = t(45);
            n._set("line", {
                showLines: !0,
                spanGaps: !1,
                hover: {
                    mode: "label"
                },
                scales: {
                    xAxes: [{
                        type: "category",
                        id: "x-axis-0"
                    }],
                    yAxes: [{
                        type: "linear",
                        id: "y-axis-0"
                    }]
                }
            }), e.exports = function(t) {
                function e(t, e) {
                    return r.valueOrDefault(t.showLine, e.showLines)
                }
                t.controllers.line = t.DatasetController.extend({
                    datasetElementType: a.Line,
                    dataElementType: a.Point,
                    update: function(t) {
                        var i, n, a, o = this,
                            s = o.getMeta(),
                            l = s.dataset,
                            u = s.data || [],
                            d = o.chart.options,
                            h = d.elements.line,
                            c = o.getScaleForId(s.yAxisID),
                            f = o.getDataset(),
                            g = e(f, d);
                        for (g && (a = l.custom || {}, void 0 !== f.tension && void 0 === f.lineTension && (f.lineTension = f.tension), l._scale = c, l._datasetIndex = o.index, l._children = u, l._model = {
                                spanGaps: f.spanGaps ? f.spanGaps : d.spanGaps,
                                tension: a.tension ? a.tension : r.valueOrDefault(f.lineTension, h.tension),
                                backgroundColor: a.backgroundColor ? a.backgroundColor : f.backgroundColor || h.backgroundColor,
                                borderWidth: a.borderWidth ? a.borderWidth : f.borderWidth || h.borderWidth,
                                borderColor: a.borderColor ? a.borderColor : f.borderColor || h.borderColor,
                                borderCapStyle: a.borderCapStyle ? a.borderCapStyle : f.borderCapStyle || h.borderCapStyle,
                                borderDash: a.borderDash ? a.borderDash : f.borderDash || h.borderDash,
                                borderDashOffset: a.borderDashOffset ? a.borderDashOffset : f.borderDashOffset || h.borderDashOffset,
                                borderJoinStyle: a.borderJoinStyle ? a.borderJoinStyle : f.borderJoinStyle || h.borderJoinStyle,
                                fill: a.fill ? a.fill : void 0 !== f.fill ? f.fill : h.fill,
                                steppedLine: a.steppedLine ? a.steppedLine : r.valueOrDefault(f.steppedLine, h.stepped),
                                cubicInterpolationMode: a.cubicInterpolationMode ? a.cubicInterpolationMode : r.valueOrDefault(f.cubicInterpolationMode, h.cubicInterpolationMode)
                            }, l.pivot()), i = 0, n = u.length; i < n; ++i) o.updateElement(u[i], i, t);
                        for (g && 0 !== l._model.tension && o.updateBezierControlPoints(), i = 0, n = u.length; i < n; ++i) u[i].pivot()
                    },
                    getPointBackgroundColor: function(t, e) {
                        var i = this.chart.options.elements.point.backgroundColor,
                            n = this.getDataset(),
                            a = t.custom || {};
                        return a.backgroundColor ? i = a.backgroundColor : n.pointBackgroundColor ? i = r.valueAtIndexOrDefault(n.pointBackgroundColor, e, i) : n.backgroundColor && (i = n.backgroundColor), i
                    },
                    getPointBorderColor: function(t, e) {
                        var i = this.chart.options.elements.point.borderColor,
                            n = this.getDataset(),
                            a = t.custom || {};
                        return a.borderColor ? i = a.borderColor : n.pointBorderColor ? i = r.valueAtIndexOrDefault(n.pointBorderColor, e, i) : n.borderColor && (i = n.borderColor), i
                    },
                    getPointBorderWidth: function(t, e) {
                        var i = this.chart.options.elements.point.borderWidth,
                            n = this.getDataset(),
                            a = t.custom || {};
                        return isNaN(a.borderWidth) ? !isNaN(n.pointBorderWidth) || r.isArray(n.pointBorderWidth) ? i = r.valueAtIndexOrDefault(n.pointBorderWidth, e, i) : isNaN(n.borderWidth) || (i = n.borderWidth) : i = a.borderWidth, i
                    },
                    updateElement: function(t, e, i) {
                        var n, a, o = this,
                            s = o.getMeta(),
                            l = t.custom || {},
                            u = o.getDataset(),
                            d = o.index,
                            h = u.data[e],
                            c = o.getScaleForId(s.yAxisID),
                            f = o.getScaleForId(s.xAxisID),
                            g = o.chart.options.elements.point;
                        void 0 !== u.radius && void 0 === u.pointRadius && (u.pointRadius = u.radius), void 0 !== u.hitRadius && void 0 === u.pointHitRadius && (u.pointHitRadius = u.hitRadius), n = f.getPixelForValue("object" == typeof h ? h : NaN, e, d), a = i ? c.getBasePixel() : o.calculatePointY(h, e, d), t._xScale = f, t._yScale = c, t._datasetIndex = d, t._index = e, t._model = {
                            x: n,
                            y: a,
                            skip: l.skip || isNaN(n) || isNaN(a),
                            radius: l.radius || r.valueAtIndexOrDefault(u.pointRadius, e, g.radius),
                            pointStyle: l.pointStyle || r.valueAtIndexOrDefault(u.pointStyle, e, g.pointStyle),
                            backgroundColor: o.getPointBackgroundColor(t, e),
                            borderColor: o.getPointBorderColor(t, e),
                            borderWidth: o.getPointBorderWidth(t, e),
                            tension: s.dataset._model ? s.dataset._model.tension : 0,
                            steppedLine: !!s.dataset._model && s.dataset._model.steppedLine,
                            hitRadius: l.hitRadius || r.valueAtIndexOrDefault(u.pointHitRadius, e, g.hitRadius)
                        }
                    },
                    calculatePointY: function(t, e, i) {
                        var n, a, r, o = this.chart,
                            s = this.getMeta(),
                            l = this.getScaleForId(s.yAxisID),
                            u = 0,
                            d = 0;
                        if (l.options.stacked) {
                            for (n = 0; n < i; n++)
                                if (a = o.data.datasets[n], "line" === (r = o.getDatasetMeta(n)).type && r.yAxisID === l.id && o.isDatasetVisible(n)) {
                                    var h = Number(l.getRightValue(a.data[e]));
                                    h < 0 ? d += h || 0 : u += h || 0
                                }
                            var c = Number(l.getRightValue(t));
                            return c < 0 ? l.getPixelForValue(d + c) : l.getPixelForValue(u + c)
                        }
                        return l.getPixelForValue(t)
                    },
                    updateBezierControlPoints: function() {
                        var t, e, i, n, a = this.getMeta(),
                            o = this.chart.chartArea,
                            s = a.data || [];

                        function l(t, e, i) {
                            return Math.max(Math.min(t, i), e)
                        }
                        if (a.dataset._model.spanGaps && (s = s.filter(function(t) {
                                return !t._model.skip
                            })), "monotone" === a.dataset._model.cubicInterpolationMode) r.splineCurveMonotone(s);
                        else
                            for (t = 0, e = s.length; t < e; ++t) i = s[t]._model, n = r.splineCurve(r.previousItem(s, t)._model, i, r.nextItem(s, t)._model, a.dataset._model.tension), i.controlPointPreviousX = n.previous.x, i.controlPointPreviousY = n.previous.y, i.controlPointNextX = n.next.x, i.controlPointNextY = n.next.y;
                        if (this.chart.options.elements.line.capBezierPoints)
                            for (t = 0, e = s.length; t < e; ++t)(i = s[t]._model).controlPointPreviousX = l(i.controlPointPreviousX, o.left, o.right), i.controlPointPreviousY = l(i.controlPointPreviousY, o.top, o.bottom), i.controlPointNextX = l(i.controlPointNextX, o.left, o.right), i.controlPointNextY = l(i.controlPointNextY, o.top, o.bottom)
                    },
                    draw: function() {
                        var t = this.chart,
                            i = this.getMeta(),
                            n = i.data || [],
                            a = t.chartArea,
                            o = n.length,
                            s = 0;
                        for (r.canvas.clipArea(t.ctx, a), e(this.getDataset(), t.options) && i.dataset.draw(), r.canvas.unclipArea(t.ctx); s < o; ++s) n[s].draw(a)
                    },
                    setHoverStyle: function(t) {
                        var e = this.chart.data.datasets[t._datasetIndex],
                            i = t._index,
                            n = t.custom || {},
                            a = t._model;
                        a.radius = n.hoverRadius || r.valueAtIndexOrDefault(e.pointHoverRadius, i, this.chart.options.elements.point.hoverRadius), a.backgroundColor = n.hoverBackgroundColor || r.valueAtIndexOrDefault(e.pointHoverBackgroundColor, i, r.getHoverColor(a.backgroundColor)), a.borderColor = n.hoverBorderColor || r.valueAtIndexOrDefault(e.pointHoverBorderColor, i, r.getHoverColor(a.borderColor)), a.borderWidth = n.hoverBorderWidth || r.valueAtIndexOrDefault(e.pointHoverBorderWidth, i, a.borderWidth)
                    },
                    removeHoverStyle: function(t) {
                        var e = this,
                            i = e.chart.data.datasets[t._datasetIndex],
                            n = t._index,
                            a = t.custom || {},
                            o = t._model;
                        void 0 !== i.radius && void 0 === i.pointRadius && (i.pointRadius = i.radius), o.radius = a.radius || r.valueAtIndexOrDefault(i.pointRadius, n, e.chart.options.elements.point.radius), o.backgroundColor = e.getPointBackgroundColor(t, n), o.borderColor = e.getPointBorderColor(t, n), o.borderWidth = e.getPointBorderWidth(t, n)
                    }
                })
            }
        }, {
            25: 25,
            40: 40,
            45: 45
        }],
        19: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(40),
                r = t(45);
            n._set("polarArea", {
                scale: {
                    type: "radialLinear",
                    angleLines: {
                        display: !1
                    },
                    gridLines: {
                        circular: !0
                    },
                    pointLabels: {
                        display: !1
                    },
                    ticks: {
                        beginAtZero: !0
                    }
                },
                animation: {
                    animateRotate: !0,
                    animateScale: !0
                },
                startAngle: -.5 * Math.PI,
                legendCallback: function(t) {
                    var e = [];
                    e.push('<ul class="' + t.id + '-legend">');
                    var i = t.data,
                        n = i.datasets,
                        a = i.labels;
                    if (n.length)
                        for (var r = 0; r < n[0].data.length; ++r) e.push('<li><span style="background-color:' + n[0].backgroundColor[r] + '"></span>'), a[r] && e.push(a[r]), e.push("</li>");
                    return e.push("</ul>"), e.join("")
                },
                legend: {
                    labels: {
                        generateLabels: function(t) {
                            var e = t.data;
                            return e.labels.length && e.datasets.length ? e.labels.map(function(i, n) {
                                var a = t.getDatasetMeta(0),
                                    o = e.datasets[0],
                                    s = a.data[n].custom || {},
                                    l = r.valueAtIndexOrDefault,
                                    u = t.options.elements.arc;
                                return {
                                    text: i,
                                    fillStyle: s.backgroundColor ? s.backgroundColor : l(o.backgroundColor, n, u.backgroundColor),
                                    strokeStyle: s.borderColor ? s.borderColor : l(o.borderColor, n, u.borderColor),
                                    lineWidth: s.borderWidth ? s.borderWidth : l(o.borderWidth, n, u.borderWidth),
                                    hidden: isNaN(o.data[n]) || a.data[n].hidden,
                                    index: n
                                }
                            }) : []
                        }
                    },
                    onClick: function(t, e) {
                        var i, n, a, r = e.index,
                            o = this.chart;
                        for (i = 0, n = (o.data.datasets || []).length; i < n; ++i)(a = o.getDatasetMeta(i)).data[r].hidden = !a.data[r].hidden;
                        o.update()
                    }
                },
                tooltips: {
                    callbacks: {
                        title: function() {
                            return ""
                        },
                        label: function(t, e) {
                            return e.labels[t.index] + ": " + t.yLabel
                        }
                    }
                }
            }), e.exports = function(t) {
                t.controllers.polarArea = t.DatasetController.extend({
                    dataElementType: a.Arc,
                    linkScales: r.noop,
                    update: function(t) {
                        var e = this,
                            i = e.chart,
                            n = i.chartArea,
                            a = e.getMeta(),
                            o = i.options,
                            s = o.elements.arc,
                            l = Math.min(n.right - n.left, n.bottom - n.top);
                        i.outerRadius = Math.max((l - s.borderWidth / 2) / 2, 0), i.innerRadius = Math.max(o.cutoutPercentage ? i.outerRadius / 100 * o.cutoutPercentage : 1, 0), i.radiusLength = (i.outerRadius - i.innerRadius) / i.getVisibleDatasetCount(), e.outerRadius = i.outerRadius - i.radiusLength * e.index, e.innerRadius = e.outerRadius - i.radiusLength, a.count = e.countVisibleElements(), r.each(a.data, function(i, n) {
                            e.updateElement(i, n, t)
                        })
                    },
                    updateElement: function(t, e, i) {
                        for (var n = this, a = n.chart, o = n.getDataset(), s = a.options, l = s.animation, u = a.scale, d = a.data.labels, h = n.calculateCircumference(o.data[e]), c = u.xCenter, f = u.yCenter, g = 0, m = n.getMeta(), p = 0; p < e; ++p) isNaN(o.data[p]) || m.data[p].hidden || ++g;
                        var v = s.startAngle,
                            y = t.hidden ? 0 : u.getDistanceFromCenterForValue(o.data[e]),
                            b = v + h * g,
                            x = b + (t.hidden ? 0 : h),
                            _ = l.animateScale ? 0 : u.getDistanceFromCenterForValue(o.data[e]);
                        r.extend(t, {
                            _datasetIndex: n.index,
                            _index: e,
                            _scale: u,
                            _model: {
                                x: c,
                                y: f,
                                innerRadius: 0,
                                outerRadius: i ? _ : y,
                                startAngle: i && l.animateRotate ? v : b,
                                endAngle: i && l.animateRotate ? v : x,
                                label: r.valueAtIndexOrDefault(d, e, d[e])
                            }
                        }), n.removeHoverStyle(t), t.pivot()
                    },
                    removeHoverStyle: function(e) {
                        t.DatasetController.prototype.removeHoverStyle.call(this, e, this.chart.options.elements.arc)
                    },
                    countVisibleElements: function() {
                        var t = this.getDataset(),
                            e = this.getMeta(),
                            i = 0;
                        return r.each(e.data, function(e, n) {
                            isNaN(t.data[n]) || e.hidden || i++
                        }), i
                    },
                    calculateCircumference: function(t) {
                        var e = this.getMeta().count;
                        return e > 0 && !isNaN(t) ? 2 * Math.PI / e : 0
                    }
                })
            }
        }, {
            25: 25,
            40: 40,
            45: 45
        }],
        20: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(40),
                r = t(45);
            n._set("radar", {
                scale: {
                    type: "radialLinear"
                },
                elements: {
                    line: {
                        tension: 0
                    }
                }
            }), e.exports = function(t) {
                t.controllers.radar = t.DatasetController.extend({
                    datasetElementType: a.Line,
                    dataElementType: a.Point,
                    linkScales: r.noop,
                    update: function(t) {
                        var e = this,
                            i = e.getMeta(),
                            n = i.dataset,
                            a = i.data,
                            o = n.custom || {},
                            s = e.getDataset(),
                            l = e.chart.options.elements.line,
                            u = e.chart.scale;
                        void 0 !== s.tension && void 0 === s.lineTension && (s.lineTension = s.tension), r.extend(i.dataset, {
                            _datasetIndex: e.index,
                            _scale: u,
                            _children: a,
                            _loop: !0,
                            _model: {
                                tension: o.tension ? o.tension : r.valueOrDefault(s.lineTension, l.tension),
                                backgroundColor: o.backgroundColor ? o.backgroundColor : s.backgroundColor || l.backgroundColor,
                                borderWidth: o.borderWidth ? o.borderWidth : s.borderWidth || l.borderWidth,
                                borderColor: o.borderColor ? o.borderColor : s.borderColor || l.borderColor,
                                fill: o.fill ? o.fill : void 0 !== s.fill ? s.fill : l.fill,
                                borderCapStyle: o.borderCapStyle ? o.borderCapStyle : s.borderCapStyle || l.borderCapStyle,
                                borderDash: o.borderDash ? o.borderDash : s.borderDash || l.borderDash,
                                borderDashOffset: o.borderDashOffset ? o.borderDashOffset : s.borderDashOffset || l.borderDashOffset,
                                borderJoinStyle: o.borderJoinStyle ? o.borderJoinStyle : s.borderJoinStyle || l.borderJoinStyle
                            }
                        }), i.dataset.pivot(), r.each(a, function(i, n) {
                            e.updateElement(i, n, t)
                        }, e), e.updateBezierControlPoints()
                    },
                    updateElement: function(t, e, i) {
                        var n = this,
                            a = t.custom || {},
                            o = n.getDataset(),
                            s = n.chart.scale,
                            l = n.chart.options.elements.point,
                            u = s.getPointPositionForValue(e, o.data[e]);
                        void 0 !== o.radius && void 0 === o.pointRadius && (o.pointRadius = o.radius), void 0 !== o.hitRadius && void 0 === o.pointHitRadius && (o.pointHitRadius = o.hitRadius), r.extend(t, {
                            _datasetIndex: n.index,
                            _index: e,
                            _scale: s,
                            _model: {
                                x: i ? s.xCenter : u.x,
                                y: i ? s.yCenter : u.y,
                                tension: a.tension ? a.tension : r.valueOrDefault(o.lineTension, n.chart.options.elements.line.tension),
                                radius: a.radius ? a.radius : r.valueAtIndexOrDefault(o.pointRadius, e, l.radius),
                                backgroundColor: a.backgroundColor ? a.backgroundColor : r.valueAtIndexOrDefault(o.pointBackgroundColor, e, l.backgroundColor),
                                borderColor: a.borderColor ? a.borderColor : r.valueAtIndexOrDefault(o.pointBorderColor, e, l.borderColor),
                                borderWidth: a.borderWidth ? a.borderWidth : r.valueAtIndexOrDefault(o.pointBorderWidth, e, l.borderWidth),
                                pointStyle: a.pointStyle ? a.pointStyle : r.valueAtIndexOrDefault(o.pointStyle, e, l.pointStyle),
                                hitRadius: a.hitRadius ? a.hitRadius : r.valueAtIndexOrDefault(o.pointHitRadius, e, l.hitRadius)
                            }
                        }), t._model.skip = a.skip ? a.skip : isNaN(t._model.x) || isNaN(t._model.y)
                    },
                    updateBezierControlPoints: function() {
                        var t = this.chart.chartArea,
                            e = this.getMeta();
                        r.each(e.data, function(i, n) {
                            var a = i._model,
                                o = r.splineCurve(r.previousItem(e.data, n, !0)._model, a, r.nextItem(e.data, n, !0)._model, a.tension);
                            a.controlPointPreviousX = Math.max(Math.min(o.previous.x, t.right), t.left), a.controlPointPreviousY = Math.max(Math.min(o.previous.y, t.bottom), t.top), a.controlPointNextX = Math.max(Math.min(o.next.x, t.right), t.left), a.controlPointNextY = Math.max(Math.min(o.next.y, t.bottom), t.top), i.pivot()
                        })
                    },
                    setHoverStyle: function(t) {
                        var e = this.chart.data.datasets[t._datasetIndex],
                            i = t.custom || {},
                            n = t._index,
                            a = t._model;
                        a.radius = i.hoverRadius ? i.hoverRadius : r.valueAtIndexOrDefault(e.pointHoverRadius, n, this.chart.options.elements.point.hoverRadius), a.backgroundColor = i.hoverBackgroundColor ? i.hoverBackgroundColor : r.valueAtIndexOrDefault(e.pointHoverBackgroundColor, n, r.getHoverColor(a.backgroundColor)), a.borderColor = i.hoverBorderColor ? i.hoverBorderColor : r.valueAtIndexOrDefault(e.pointHoverBorderColor, n, r.getHoverColor(a.borderColor)), a.borderWidth = i.hoverBorderWidth ? i.hoverBorderWidth : r.valueAtIndexOrDefault(e.pointHoverBorderWidth, n, a.borderWidth)
                    },
                    removeHoverStyle: function(t) {
                        var e = this.chart.data.datasets[t._datasetIndex],
                            i = t.custom || {},
                            n = t._index,
                            a = t._model,
                            o = this.chart.options.elements.point;
                        a.radius = i.radius ? i.radius : r.valueAtIndexOrDefault(e.pointRadius, n, o.radius), a.backgroundColor = i.backgroundColor ? i.backgroundColor : r.valueAtIndexOrDefault(e.pointBackgroundColor, n, o.backgroundColor), a.borderColor = i.borderColor ? i.borderColor : r.valueAtIndexOrDefault(e.pointBorderColor, n, o.borderColor), a.borderWidth = i.borderWidth ? i.borderWidth : r.valueAtIndexOrDefault(e.pointBorderWidth, n, o.borderWidth)
                    }
                })
            }
        }, {
            25: 25,
            40: 40,
            45: 45
        }],
        21: [function(t, e, i) {
            "use strict";
            t(25)._set("scatter", {
                hover: {
                    mode: "single"
                },
                scales: {
                    xAxes: [{
                        id: "x-axis-1",
                        type: "linear",
                        position: "bottom"
                    }],
                    yAxes: [{
                        id: "y-axis-1",
                        type: "linear",
                        position: "left"
                    }]
                },
                showLines: !1,
                tooltips: {
                    callbacks: {
                        title: function() {
                            return ""
                        },
                        label: function(t) {
                            return "(" + t.xLabel + ", " + t.yLabel + ")"
                        }
                    }
                }
            }), e.exports = function(t) {
                t.controllers.scatter = t.controllers.line
            }
        }, {
            25: 25
        }],
        22: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(26),
                r = t(45);
            n._set("global", {
                animation: {
                    duration: 1e3,
                    easing: "easeOutQuart",
                    onProgress: r.noop,
                    onComplete: r.noop
                }
            }), e.exports = function(t) {
                t.Animation = a.extend({
                    chart: null,
                    currentStep: 0,
                    numSteps: 60,
                    easing: "",
                    render: null,
                    onAnimationProgress: null,
                    onAnimationComplete: null
                }), t.animationService = {
                    frameDuration: 17,
                    animations: [],
                    dropFrames: 0,
                    request: null,
                    addAnimation: function(t, e, i, n) {
                        var a, r, o = this.animations;
                        for (e.chart = t, n || (t.animating = !0), a = 0, r = o.length; a < r; ++a)
                            if (o[a].chart === t) return void(o[a] = e);
                        o.push(e), 1 === o.length && this.requestAnimationFrame()
                    },
                    cancelAnimation: function(t) {
                        var e = r.findIndex(this.animations, function(e) {
                            return e.chart === t
                        }); - 1 !== e && (this.animations.splice(e, 1), t.animating = !1)
                    },
                    requestAnimationFrame: function() {
                        var t = this;
                        null === t.request && (t.request = r.requestAnimFrame.call(window, function() {
                            t.request = null, t.startDigest()
                        }))
                    },
                    startDigest: function() {
                        var t = this,
                            e = Date.now(),
                            i = 0;
                        t.dropFrames > 1 && (i = Math.floor(t.dropFrames), t.dropFrames = t.dropFrames % 1), t.advance(1 + i);
                        var n = Date.now();
                        t.dropFrames += (n - e) / t.frameDuration, t.animations.length > 0 && t.requestAnimationFrame()
                    },
                    advance: function(t) {
                        for (var e, i, n = this.animations, a = 0; a < n.length;) i = (e = n[a]).chart, e.currentStep = (e.currentStep || 0) + t, e.currentStep = Math.min(e.currentStep, e.numSteps), r.callback(e.render, [i, e], i), r.callback(e.onAnimationProgress, [e], i), e.currentStep >= e.numSteps ? (r.callback(e.onAnimationComplete, [e], i), i.animating = !1, n.splice(a, 1)) : ++a
                    }
                }, Object.defineProperty(t.Animation.prototype, "animationObject", {
                    get: function() {
                        return this
                    }
                }), Object.defineProperty(t.Animation.prototype, "chartInstance", {
                    get: function() {
                        return this.chart
                    },
                    set: function(t) {
                        this.chart = t
                    }
                })
            }
        }, {
            25: 25,
            26: 26,
            45: 45
        }],
        23: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(45),
                r = t(28),
                o = t(30),
                s = t(48),
                l = t(31);
            e.exports = function(t) {
                function e(t) {
                    return "top" === t || "bottom" === t
                }
                t.types = {}, t.instances = {}, t.controllers = {}, a.extend(t.prototype, {
                    construct: function(e, i) {
                        var r, o, l = this;
                        (o = (r = (r = i) || {}).data = r.data || {}).datasets = o.datasets || [], o.labels = o.labels || [], r.options = a.configMerge(n.global, n[r.type], r.options || {}), i = r;
                        var u = s.acquireContext(e, i),
                            d = u && u.canvas,
                            h = d && d.height,
                            c = d && d.width;
                        l.id = a.uid(), l.ctx = u, l.canvas = d, l.config = i, l.width = c, l.height = h, l.aspectRatio = h ? c / h : null, l.options = i.options, l._bufferedRender = !1, l.chart = l, l.controller = l, t.instances[l.id] = l, Object.defineProperty(l, "data", {
                            get: function() {
                                return l.config.data
                            },
                            set: function(t) {
                                l.config.data = t
                            }
                        }), u && d ? (l.initialize(), l.update()) : console.error("Failed to create chart: can't acquire context from the given item")
                    },
                    initialize: function() {
                        var t = this;
                        return l.notify(t, "beforeInit"), a.retinaScale(t, t.options.devicePixelRatio), t.bindEvents(), t.options.responsive && t.resize(!0), t.ensureScalesHaveIDs(), t.buildOrUpdateScales(), t.initToolTip(), l.notify(t, "afterInit"), t
                    },
                    clear: function() {
                        return a.canvas.clear(this), this
                    },
                    stop: function() {
                        return t.animationService.cancelAnimation(this), this
                    },
                    resize: function(t) {
                        var e = this,
                            i = e.options,
                            n = e.canvas,
                            r = i.maintainAspectRatio && e.aspectRatio || null,
                            o = Math.max(0, Math.floor(a.getMaximumWidth(n))),
                            s = Math.max(0, Math.floor(r ? o / r : a.getMaximumHeight(n)));
                        if ((e.width !== o || e.height !== s) && (n.width = e.width = o, n.height = e.height = s, n.style.width = o + "px", n.style.height = s + "px", a.retinaScale(e, i.devicePixelRatio), !t)) {
                            var u = {
                                width: o,
                                height: s
                            };
                            l.notify(e, "resize", [u]), e.options.onResize && e.options.onResize(e, u), e.stop(), e.update(e.options.responsiveAnimationDuration)
                        }
                    },
                    ensureScalesHaveIDs: function() {
                        var t = this.options,
                            e = t.scales || {},
                            i = t.scale;
                        a.each(e.xAxes, function(t, e) {
                            t.id = t.id || "x-axis-" + e
                        }), a.each(e.yAxes, function(t, e) {
                            t.id = t.id || "y-axis-" + e
                        }), i && (i.id = i.id || "scale")
                    },
                    buildOrUpdateScales: function() {
                        var i = this,
                            n = i.options,
                            r = i.scales || {},
                            o = [],
                            s = Object.keys(r).reduce(function(t, e) {
                                return t[e] = !1, t
                            }, {});
                        n.scales && (o = o.concat((n.scales.xAxes || []).map(function(t) {
                            return {
                                options: t,
                                dtype: "category",
                                dposition: "bottom"
                            }
                        }), (n.scales.yAxes || []).map(function(t) {
                            return {
                                options: t,
                                dtype: "linear",
                                dposition: "left"
                            }
                        }))), n.scale && o.push({
                            options: n.scale,
                            dtype: "radialLinear",
                            isDefault: !0,
                            dposition: "chartArea"
                        }), a.each(o, function(n) {
                            var o = n.options,
                                l = o.id,
                                u = a.valueOrDefault(o.type, n.dtype);
                            e(o.position) !== e(n.dposition) && (o.position = n.dposition), s[l] = !0;
                            var d = null;
                            if (l in r && r[l].type === u)(d = r[l]).options = o, d.ctx = i.ctx, d.chart = i;
                            else {
                                var h = t.scaleService.getScaleConstructor(u);
                                if (!h) return;
                                d = new h({
                                    id: l,
                                    type: u,
                                    options: o,
                                    ctx: i.ctx,
                                    chart: i
                                }), r[d.id] = d
                            }
                            d.mergeTicksOptions(), n.isDefault && (i.scale = d)
                        }), a.each(s, function(t, e) {
                            t || delete r[e]
                        }), i.scales = r, t.scaleService.addScalesToLayout(this)
                    },
                    buildOrUpdateControllers: function() {
                        var e = this,
                            i = [],
                            n = [];
                        return a.each(e.data.datasets, function(a, r) {
                            var o = e.getDatasetMeta(r),
                                s = a.type || e.config.type;
                            if (o.type && o.type !== s && (e.destroyDatasetMeta(r), o = e.getDatasetMeta(r)), o.type = s, i.push(o.type), o.controller) o.controller.updateIndex(r), o.controller.linkScales();
                            else {
                                var l = t.controllers[o.type];
                                if (void 0 === l) throw new Error('"' + o.type + '" is not a chart type.');
                                o.controller = new l(e, r), n.push(o.controller)
                            }
                        }, e), n
                    },
                    resetElements: function() {
                        var t = this;
                        a.each(t.data.datasets, function(e, i) {
                            t.getDatasetMeta(i).controller.reset()
                        }, t)
                    },
                    reset: function() {
                        this.resetElements(), this.tooltip.initialize()
                    },
                    update: function(e) {
                        var i, n, r = this;
                        if (e && "object" == typeof e || (e = {
                                duration: e,
                                lazy: arguments[1]
                            }), n = (i = r).options, a.each(i.scales, function(t) {
                                o.removeBox(i, t)
                            }), n = a.configMerge(t.defaults.global, t.defaults[i.config.type], n), i.options = i.config.options = n, i.ensureScalesHaveIDs(), i.buildOrUpdateScales(), i.tooltip._options = n.tooltips, i.tooltip.initialize(), l._invalidate(r), !1 !== l.notify(r, "beforeUpdate")) {
                            r.tooltip._data = r.data;
                            var s = r.buildOrUpdateControllers();
                            a.each(r.data.datasets, function(t, e) {
                                r.getDatasetMeta(e).controller.buildOrUpdateElements()
                            }, r), r.updateLayout(), r.options.animation && r.options.animation.duration && a.each(s, function(t) {
                                t.reset()
                            }), r.updateDatasets(), r.tooltip.initialize(), r.lastActive = [], l.notify(r, "afterUpdate"), r._bufferedRender ? r._bufferedRequest = {
                                duration: e.duration,
                                easing: e.easing,
                                lazy: e.lazy
                            } : r.render(e)
                        }
                    },
                    updateLayout: function() {
                        !1 !== l.notify(this, "beforeLayout") && (o.update(this, this.width, this.height), l.notify(this, "afterScaleUpdate"), l.notify(this, "afterLayout"))
                    },
                    updateDatasets: function() {
                        if (!1 !== l.notify(this, "beforeDatasetsUpdate")) {
                            for (var t = 0, e = this.data.datasets.length; t < e; ++t) this.updateDataset(t);
                            l.notify(this, "afterDatasetsUpdate")
                        }
                    },
                    updateDataset: function(t) {
                        var e = this.getDatasetMeta(t),
                            i = {
                                meta: e,
                                index: t
                            };
                        !1 !== l.notify(this, "beforeDatasetUpdate", [i]) && (e.controller.update(), l.notify(this, "afterDatasetUpdate", [i]))
                    },
                    render: function(e) {
                        var i = this;
                        e && "object" == typeof e || (e = {
                            duration: e,
                            lazy: arguments[1]
                        });
                        var n = e.duration,
                            r = e.lazy;
                        if (!1 !== l.notify(i, "beforeRender")) {
                            var o = i.options.animation,
                                s = function(t) {
                                    l.notify(i, "afterRender"), a.callback(o && o.onComplete, [t], i)
                                };
                            if (o && (void 0 !== n && 0 !== n || void 0 === n && 0 !== o.duration)) {
                                var u = new t.Animation({
                                    numSteps: (n || o.duration) / 16.66,
                                    easing: e.easing || o.easing,
                                    render: function(t, e) {
                                        var i = a.easing.effects[e.easing],
                                            n = e.currentStep,
                                            r = n / e.numSteps;
                                        t.draw(i(r), r, n)
                                    },
                                    onAnimationProgress: o.onProgress,
                                    onAnimationComplete: s
                                });
                                t.animationService.addAnimation(i, u, n, r)
                            } else i.draw(), s(new t.Animation({
                                numSteps: 0,
                                chart: i
                            }));
                            return i
                        }
                    },
                    draw: function(t) {
                        var e = this;
                        e.clear(), a.isNullOrUndef(t) && (t = 1), e.transition(t), !1 !== l.notify(e, "beforeDraw", [t]) && (a.each(e.boxes, function(t) {
                            t.draw(e.chartArea)
                        }, e), e.scale && e.scale.draw(), e.drawDatasets(t), e._drawTooltip(t), l.notify(e, "afterDraw", [t]))
                    },
                    transition: function(t) {
                        for (var e = 0, i = (this.data.datasets || []).length; e < i; ++e) this.isDatasetVisible(e) && this.getDatasetMeta(e).controller.transition(t);
                        this.tooltip.transition(t)
                    },
                    drawDatasets: function(t) {
                        var e = this;
                        if (!1 !== l.notify(e, "beforeDatasetsDraw", [t])) {
                            for (var i = (e.data.datasets || []).length - 1; i >= 0; --i) e.isDatasetVisible(i) && e.drawDataset(i, t);
                            l.notify(e, "afterDatasetsDraw", [t])
                        }
                    },
                    drawDataset: function(t, e) {
                        var i = this.getDatasetMeta(t),
                            n = {
                                meta: i,
                                index: t,
                                easingValue: e
                            };
                        !1 !== l.notify(this, "beforeDatasetDraw", [n]) && (i.controller.draw(e), l.notify(this, "afterDatasetDraw", [n]))
                    },
                    _drawTooltip: function(t) {
                        var e = this.tooltip,
                            i = {
                                tooltip: e,
                                easingValue: t
                            };
                        !1 !== l.notify(this, "beforeTooltipDraw", [i]) && (e.draw(), l.notify(this, "afterTooltipDraw", [i]))
                    },
                    getElementAtEvent: function(t) {
                        return r.modes.single(this, t)
                    },
                    getElementsAtEvent: function(t) {
                        return r.modes.label(this, t, {
                            intersect: !0
                        })
                    },
                    getElementsAtXAxis: function(t) {
                        return r.modes["x-axis"](this, t, {
                            intersect: !0
                        })
                    },
                    getElementsAtEventForMode: function(t, e, i) {
                        var n = r.modes[e];
                        return "function" == typeof n ? n(this, t, i) : []
                    },
                    getDatasetAtEvent: function(t) {
                        return r.modes.dataset(this, t, {
                            intersect: !0
                        })
                    },
                    getDatasetMeta: function(t) {
                        var e = this.data.datasets[t];
                        e._meta || (e._meta = {});
                        var i = e._meta[this.id];
                        return i || (i = e._meta[this.id] = {
                            type: null,
                            data: [],
                            dataset: null,
                            controller: null,
                            hidden: null,
                            xAxisID: null,
                            yAxisID: null
                        }), i
                    },
                    getVisibleDatasetCount: function() {
                        for (var t = 0, e = 0, i = this.data.datasets.length; e < i; ++e) this.isDatasetVisible(e) && t++;
                        return t
                    },
                    isDatasetVisible: function(t) {
                        var e = this.getDatasetMeta(t);
                        return "boolean" == typeof e.hidden ? !e.hidden : !this.data.datasets[t].hidden
                    },
                    generateLegend: function() {
                        return this.options.legendCallback(this)
                    },
                    destroyDatasetMeta: function(t) {
                        var e = this.id,
                            i = this.data.datasets[t],
                            n = i._meta && i._meta[e];
                        n && (n.controller.destroy(), delete i._meta[e])
                    },
                    destroy: function() {
                        var e, i, n = this,
                            r = n.canvas;
                        for (n.stop(), e = 0, i = n.data.datasets.length; e < i; ++e) n.destroyDatasetMeta(e);
                        r && (n.unbindEvents(), a.canvas.clear(n), s.releaseContext(n.ctx), n.canvas = null, n.ctx = null), l.notify(n, "destroy"), delete t.instances[n.id]
                    },
                    toBase64Image: function() {
                        return this.canvas.toDataURL.apply(this.canvas, arguments)
                    },
                    initToolTip: function() {
                        var e = this;
                        e.tooltip = new t.Tooltip({
                            _chart: e,
                            _chartInstance: e,
                            _data: e.data,
                            _options: e.options.tooltips
                        }, e)
                    },
                    bindEvents: function() {
                        var t = this,
                            e = t._listeners = {},
                            i = function() {
                                t.eventHandler.apply(t, arguments)
                            };
                        a.each(t.options.events, function(n) {
                            s.addEventListener(t, n, i), e[n] = i
                        }), t.options.responsive && (i = function() {
                            t.resize()
                        }, s.addEventListener(t, "resize", i), e.resize = i)
                    },
                    unbindEvents: function() {
                        var t = this,
                            e = t._listeners;
                        e && (delete t._listeners, a.each(e, function(e, i) {
                            s.removeEventListener(t, i, e)
                        }))
                    },
                    updateHoverStyle: function(t, e, i) {
                        var n, a, r, o = i ? "setHoverStyle" : "removeHoverStyle";
                        for (a = 0, r = t.length; a < r; ++a)(n = t[a]) && this.getDatasetMeta(n._datasetIndex).controller[o](n)
                    },
                    eventHandler: function(t) {
                        var e = this,
                            i = e.tooltip;
                        if (!1 !== l.notify(e, "beforeEvent", [t])) {
                            e._bufferedRender = !0, e._bufferedRequest = null;
                            var n = e.handleEvent(t);
                            i && (n = i._start ? i.handleEvent(t) : n | i.handleEvent(t)), l.notify(e, "afterEvent", [t]);
                            var a = e._bufferedRequest;
                            return a ? e.render(a) : n && !e.animating && (e.stop(), e.render(e.options.hover.animationDuration, !0)), e._bufferedRender = !1, e._bufferedRequest = null, e
                        }
                    },
                    handleEvent: function(t) {
                        var e, i = this,
                            n = i.options || {},
                            r = n.hover;
                        return i.lastActive = i.lastActive || [], "mouseout" === t.type ? i.active = [] : i.active = i.getElementsAtEventForMode(t, r.mode, r), a.callback(n.onHover || n.hover.onHover, [t.native, i.active], i), "mouseup" !== t.type && "click" !== t.type || n.onClick && n.onClick.call(i, t.native, i.active), i.lastActive.length && i.updateHoverStyle(i.lastActive, r.mode, !1), i.active.length && r.mode && i.updateHoverStyle(i.active, r.mode, !0), e = !a.arrayEquals(i.active, i.lastActive), i.lastActive = i.active, e
                    }
                }), t.Controller = t
            }
        }, {
            25: 25,
            28: 28,
            30: 30,
            31: 31,
            45: 45,
            48: 48
        }],
        24: [function(t, e, i) {
            "use strict";
            var n = t(45);
            e.exports = function(t) {
                var e = ["push", "pop", "shift", "splice", "unshift"];

                function i(t, i) {
                    var n = t._chartjs;
                    if (n) {
                        var a = n.listeners,
                            r = a.indexOf(i); - 1 !== r && a.splice(r, 1), a.length > 0 || (e.forEach(function(e) {
                            delete t[e]
                        }), delete t._chartjs)
                    }
                }
                t.DatasetController = function(t, e) {
                    this.initialize(t, e)
                }, n.extend(t.DatasetController.prototype, {
                    datasetElementType: null,
                    dataElementType: null,
                    initialize: function(t, e) {
                        this.chart = t, this.index = e, this.linkScales(), this.addElements()
                    },
                    updateIndex: function(t) {
                        this.index = t
                    },
                    linkScales: function() {
                        var t = this,
                            e = t.getMeta(),
                            i = t.getDataset();
                        null !== e.xAxisID && e.xAxisID in t.chart.scales || (e.xAxisID = i.xAxisID || t.chart.options.scales.xAxes[0].id), null !== e.yAxisID && e.yAxisID in t.chart.scales || (e.yAxisID = i.yAxisID || t.chart.options.scales.yAxes[0].id)
                    },
                    getDataset: function() {
                        return this.chart.data.datasets[this.index]
                    },
                    getMeta: function() {
                        return this.chart.getDatasetMeta(this.index)
                    },
                    getScaleForId: function(t) {
                        return this.chart.scales[t]
                    },
                    reset: function() {
                        this.update(!0)
                    },
                    destroy: function() {
                        this._data && i(this._data, this)
                    },
                    createMetaDataset: function() {
                        var t = this.datasetElementType;
                        return t && new t({
                            _chart: this.chart,
                            _datasetIndex: this.index
                        })
                    },
                    createMetaData: function(t) {
                        var e = this.dataElementType;
                        return e && new e({
                            _chart: this.chart,
                            _datasetIndex: this.index,
                            _index: t
                        })
                    },
                    addElements: function() {
                        var t, e, i = this.getMeta(),
                            n = this.getDataset().data || [],
                            a = i.data;
                        for (t = 0, e = n.length; t < e; ++t) a[t] = a[t] || this.createMetaData(t);
                        i.dataset = i.dataset || this.createMetaDataset()
                    },
                    addElementAndReset: function(t) {
                        var e = this.createMetaData(t);
                        this.getMeta().data.splice(t, 0, e), this.updateElement(e, t, !0)
                    },
                    buildOrUpdateElements: function() {
                        var t, a, r = this,
                            o = r.getDataset(),
                            s = o.data || (o.data = []);
                        r._data !== s && (r._data && i(r._data, r), a = r, (t = s)._chartjs ? t._chartjs.listeners.push(a) : (Object.defineProperty(t, "_chartjs", {
                            configurable: !0,
                            enumerable: !1,
                            value: {
                                listeners: [a]
                            }
                        }), e.forEach(function(e) {
                            var i = "onData" + e.charAt(0).toUpperCase() + e.slice(1),
                                a = t[e];
                            Object.defineProperty(t, e, {
                                configurable: !0,
                                enumerable: !1,
                                value: function() {
                                    var e = Array.prototype.slice.call(arguments),
                                        r = a.apply(this, e);
                                    return n.each(t._chartjs.listeners, function(t) {
                                        "function" == typeof t[i] && t[i].apply(t, e)
                                    }), r
                                }
                            })
                        })), r._data = s), r.resyncElements()
                    },
                    update: n.noop,
                    transition: function(t) {
                        for (var e = this.getMeta(), i = e.data || [], n = i.length, a = 0; a < n; ++a) i[a].transition(t);
                        e.dataset && e.dataset.transition(t)
                    },
                    draw: function() {
                        var t = this.getMeta(),
                            e = t.data || [],
                            i = e.length,
                            n = 0;
                        for (t.dataset && t.dataset.draw(); n < i; ++n) e[n].draw()
                    },
                    removeHoverStyle: function(t, e) {
                        var i = this.chart.data.datasets[t._datasetIndex],
                            a = t._index,
                            r = t.custom || {},
                            o = n.valueAtIndexOrDefault,
                            s = t._model;
                        s.backgroundColor = r.backgroundColor ? r.backgroundColor : o(i.backgroundColor, a, e.backgroundColor), s.borderColor = r.borderColor ? r.borderColor : o(i.borderColor, a, e.borderColor), s.borderWidth = r.borderWidth ? r.borderWidth : o(i.borderWidth, a, e.borderWidth)
                    },
                    setHoverStyle: function(t) {
                        var e = this.chart.data.datasets[t._datasetIndex],
                            i = t._index,
                            a = t.custom || {},
                            r = n.valueAtIndexOrDefault,
                            o = n.getHoverColor,
                            s = t._model;
                        s.backgroundColor = a.hoverBackgroundColor ? a.hoverBackgroundColor : r(e.hoverBackgroundColor, i, o(s.backgroundColor)), s.borderColor = a.hoverBorderColor ? a.hoverBorderColor : r(e.hoverBorderColor, i, o(s.borderColor)), s.borderWidth = a.hoverBorderWidth ? a.hoverBorderWidth : r(e.hoverBorderWidth, i, s.borderWidth)
                    },
                    resyncElements: function() {
                        var t = this.getMeta(),
                            e = this.getDataset().data,
                            i = t.data.length,
                            n = e.length;
                        n < i ? t.data.splice(n, i - n) : n > i && this.insertElements(i, n - i)
                    },
                    insertElements: function(t, e) {
                        for (var i = 0; i < e; ++i) this.addElementAndReset(t + i)
                    },
                    onDataPush: function() {
                        this.insertElements(this.getDataset().data.length - 1, arguments.length)
                    },
                    onDataPop: function() {
                        this.getMeta().data.pop()
                    },
                    onDataShift: function() {
                        this.getMeta().data.shift()
                    },
                    onDataSplice: function(t, e) {
                        this.getMeta().data.splice(t, e), this.insertElements(t, arguments.length - 2)
                    },
                    onDataUnshift: function() {
                        this.insertElements(0, arguments.length)
                    }
                }), t.DatasetController.extend = n.inherits
            }
        }, {
            45: 45
        }],
        25: [function(t, e, i) {
            "use strict";
            var n = t(45);
            e.exports = {
                _set: function(t, e) {
                    return n.merge(this[t] || (this[t] = {}), e)
                }
            }
        }, {
            45: 45
        }],
        26: [function(t, e, i) {
            "use strict";
            var n = t(2),
                a = t(45);
            var r = function(t) {
                a.extend(this, t), this.initialize.apply(this, arguments)
            };
            a.extend(r.prototype, {
                initialize: function() {
                    this.hidden = !1
                },
                pivot: function() {
                    var t = this;
                    return t._view || (t._view = a.clone(t._model)), t._start = {}, t
                },
                transition: function(t) {
                    var e = this,
                        i = e._model,
                        a = e._start,
                        r = e._view;
                    return i && 1 !== t ? (r || (r = e._view = {}), a || (a = e._start = {}), function(t, e, i, a) {
                        var r, o, s, l, u, d, h, c, f, g = Object.keys(i);
                        for (r = 0, o = g.length; r < o; ++r)
                            if (d = i[s = g[r]], e.hasOwnProperty(s) || (e[s] = d), (l = e[s]) !== d && "_" !== s[0]) {
                                if (t.hasOwnProperty(s) || (t[s] = l), (h = typeof d) == typeof(u = t[s]))
                                    if ("string" === h) {
                                        if ((c = n(u)).valid && (f = n(d)).valid) {
                                            e[s] = f.mix(c, a).rgbString();
                                            continue
                                        }
                                    } else if ("number" === h && isFinite(u) && isFinite(d)) {
                                    e[s] = u + (d - u) * a;
                                    continue
                                }
                                e[s] = d
                            }
                    }(a, r, i, t), e) : (e._view = i, e._start = null, e)
                },
                tooltipPosition: function() {
                    return {
                        x: this._model.x,
                        y: this._model.y
                    }
                },
                hasValue: function() {
                    return a.isNumber(this._model.x) && a.isNumber(this._model.y)
                }
            }), r.extend = a.inherits, e.exports = r
        }, {
            2: 2,
            45: 45
        }],
        27: [function(t, e, i) {
            "use strict";
            var n = t(2),
                a = t(25),
                r = t(45);
            e.exports = function(t) {
                function e(t, e, i) {
                    var n;
                    return "string" == typeof t ? (n = parseInt(t, 10), -1 !== t.indexOf("%") && (n = n / 100 * e.parentNode[i])) : n = t, n
                }

                function i(t) {
                    return null != t && "none" !== t
                }

                function o(t, n, a) {
                    var r = document.defaultView,
                        o = t.parentNode,
                        s = r.getComputedStyle(t)[n],
                        l = r.getComputedStyle(o)[n],
                        u = i(s),
                        d = i(l),
                        h = Number.POSITIVE_INFINITY;
                    return u || d ? Math.min(u ? e(s, t, a) : h, d ? e(l, o, a) : h) : "none"
                }
                r.configMerge = function() {
                    return r.merge(r.clone(arguments[0]), [].slice.call(arguments, 1), {
                        merger: function(e, i, n, a) {
                            var o = i[e] || {},
                                s = n[e];
                            "scales" === e ? i[e] = r.scaleMerge(o, s) : "scale" === e ? i[e] = r.merge(o, [t.scaleService.getScaleDefaults(s.type), s]) : r._merger(e, i, n, a)
                        }
                    })
                }, r.scaleMerge = function() {
                    return r.merge(r.clone(arguments[0]), [].slice.call(arguments, 1), {
                        merger: function(e, i, n, a) {
                            if ("xAxes" === e || "yAxes" === e) {
                                var o, s, l, u = n[e].length;
                                for (i[e] || (i[e] = []), o = 0; o < u; ++o) l = n[e][o], s = r.valueOrDefault(l.type, "xAxes" === e ? "category" : "linear"), o >= i[e].length && i[e].push({}), !i[e][o].type || l.type && l.type !== i[e][o].type ? r.merge(i[e][o], [t.scaleService.getScaleDefaults(s), l]) : r.merge(i[e][o], l)
                            } else r._merger(e, i, n, a)
                        }
                    })
                }, r.where = function(t, e) {
                    if (r.isArray(t) && Array.prototype.filter) return t.filter(e);
                    var i = [];
                    return r.each(t, function(t) {
                        e(t) && i.push(t)
                    }), i
                }, r.findIndex = Array.prototype.findIndex ? function(t, e, i) {
                    return t.findIndex(e, i)
                } : function(t, e, i) {
                    i = void 0 === i ? t : i;
                    for (var n = 0, a = t.length; n < a; ++n)
                        if (e.call(i, t[n], n, t)) return n;
                    return -1
                }, r.findNextWhere = function(t, e, i) {
                    r.isNullOrUndef(i) && (i = -1);
                    for (var n = i + 1; n < t.length; n++) {
                        var a = t[n];
                        if (e(a)) return a
                    }
                }, r.findPreviousWhere = function(t, e, i) {
                    r.isNullOrUndef(i) && (i = t.length);
                    for (var n = i - 1; n >= 0; n--) {
                        var a = t[n];
                        if (e(a)) return a
                    }
                }, r.isNumber = function(t) {
                    return !isNaN(parseFloat(t)) && isFinite(t)
                }, r.almostEquals = function(t, e, i) {
                    return Math.abs(t - e) < i
                }, r.almostWhole = function(t, e) {
                    var i = Math.round(t);
                    return i - e < t && i + e > t
                }, r.max = function(t) {
                    return t.reduce(function(t, e) {
                        return isNaN(e) ? t : Math.max(t, e)
                    }, Number.NEGATIVE_INFINITY)
                }, r.min = function(t) {
                    return t.reduce(function(t, e) {
                        return isNaN(e) ? t : Math.min(t, e)
                    }, Number.POSITIVE_INFINITY)
                }, r.sign = Math.sign ? function(t) {
                    return Math.sign(t)
                } : function(t) {
                    return 0 === (t = +t) || isNaN(t) ? t : t > 0 ? 1 : -1
                }, r.log10 = Math.log10 ? function(t) {
                    return Math.log10(t)
                } : function(t) {
                    var e = Math.log(t) * Math.LOG10E,
                        i = Math.round(e);
                    return t === Math.pow(10, i) ? i : e
                }, r.toRadians = function(t) {
                    return t * (Math.PI / 180)
                }, r.toDegrees = function(t) {
                    return t * (180 / Math.PI)
                }, r.getAngleFromPoint = function(t, e) {
                    var i = e.x - t.x,
                        n = e.y - t.y,
                        a = Math.sqrt(i * i + n * n),
                        r = Math.atan2(n, i);
                    return r < -.5 * Math.PI && (r += 2 * Math.PI), {
                        angle: r,
                        distance: a
                    }
                }, r.distanceBetweenPoints = function(t, e) {
                    return Math.sqrt(Math.pow(e.x - t.x, 2) + Math.pow(e.y - t.y, 2))
                }, r.aliasPixel = function(t) {
                    return t % 2 == 0 ? 0 : .5
                }, r.splineCurve = function(t, e, i, n) {
                    var a = t.skip ? e : t,
                        r = e,
                        o = i.skip ? e : i,
                        s = Math.sqrt(Math.pow(r.x - a.x, 2) + Math.pow(r.y - a.y, 2)),
                        l = Math.sqrt(Math.pow(o.x - r.x, 2) + Math.pow(o.y - r.y, 2)),
                        u = s / (s + l),
                        d = l / (s + l),
                        h = n * (u = isNaN(u) ? 0 : u),
                        c = n * (d = isNaN(d) ? 0 : d);
                    return {
                        previous: {
                            x: r.x - h * (o.x - a.x),
                            y: r.y - h * (o.y - a.y)
                        },
                        next: {
                            x: r.x + c * (o.x - a.x),
                            y: r.y + c * (o.y - a.y)
                        }
                    }
                }, r.EPSILON = Number.EPSILON || 1e-14, r.splineCurveMonotone = function(t) {
                    var e, i, n, a, o, s, l, u, d, h = (t || []).map(function(t) {
                            return {
                                model: t._model,
                                deltaK: 0,
                                mK: 0
                            }
                        }),
                        c = h.length;
                    for (e = 0; e < c; ++e)
                        if (!(n = h[e]).model.skip) {
                            if (i = e > 0 ? h[e - 1] : null, (a = e < c - 1 ? h[e + 1] : null) && !a.model.skip) {
                                var f = a.model.x - n.model.x;
                                n.deltaK = 0 !== f ? (a.model.y - n.model.y) / f : 0
                            }!i || i.model.skip ? n.mK = n.deltaK : !a || a.model.skip ? n.mK = i.deltaK : this.sign(i.deltaK) !== this.sign(n.deltaK) ? n.mK = 0 : n.mK = (i.deltaK + n.deltaK) / 2
                        }
                    for (e = 0; e < c - 1; ++e) n = h[e], a = h[e + 1], n.model.skip || a.model.skip || (r.almostEquals(n.deltaK, 0, this.EPSILON) ? n.mK = a.mK = 0 : (o = n.mK / n.deltaK, s = a.mK / n.deltaK, (u = Math.pow(o, 2) + Math.pow(s, 2)) <= 9 || (l = 3 / Math.sqrt(u), n.mK = o * l * n.deltaK, a.mK = s * l * n.deltaK)));
                    for (e = 0; e < c; ++e)(n = h[e]).model.skip || (i = e > 0 ? h[e - 1] : null, a = e < c - 1 ? h[e + 1] : null, i && !i.model.skip && (d = (n.model.x - i.model.x) / 3, n.model.controlPointPreviousX = n.model.x - d, n.model.controlPointPreviousY = n.model.y - d * n.mK), a && !a.model.skip && (d = (a.model.x - n.model.x) / 3, n.model.controlPointNextX = n.model.x + d, n.model.controlPointNextY = n.model.y + d * n.mK))
                }, r.nextItem = function(t, e, i) {
                    return i ? e >= t.length - 1 ? t[0] : t[e + 1] : e >= t.length - 1 ? t[t.length - 1] : t[e + 1]
                }, r.previousItem = function(t, e, i) {
                    return i ? e <= 0 ? t[t.length - 1] : t[e - 1] : e <= 0 ? t[0] : t[e - 1]
                }, r.niceNum = function(t, e) {
                    var i = Math.floor(r.log10(t)),
                        n = t / Math.pow(10, i);
                    return (e ? n < 1.5 ? 1 : n < 3 ? 2 : n < 7 ? 5 : 10 : n <= 1 ? 1 : n <= 2 ? 2 : n <= 5 ? 5 : 10) * Math.pow(10, i)
                }, r.requestAnimFrame = "undefined" == typeof window ? function(t) {
                    t()
                } : window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function(t) {
                    return window.setTimeout(t, 1e3 / 60)
                }, r.getRelativePosition = function(t, e) {
                    var i, n, a = t.originalEvent || t,
                        o = t.currentTarget || t.srcElement,
                        s = o.getBoundingClientRect(),
                        l = a.touches;
                    l && l.length > 0 ? (i = l[0].clientX, n = l[0].clientY) : (i = a.clientX, n = a.clientY);
                    var u = parseFloat(r.getStyle(o, "padding-left")),
                        d = parseFloat(r.getStyle(o, "padding-top")),
                        h = parseFloat(r.getStyle(o, "padding-right")),
                        c = parseFloat(r.getStyle(o, "padding-bottom")),
                        f = s.right - s.left - u - h,
                        g = s.bottom - s.top - d - c;
                    return {
                        x: i = Math.round((i - s.left - u) / f * o.width / e.currentDevicePixelRatio),
                        y: n = Math.round((n - s.top - d) / g * o.height / e.currentDevicePixelRatio)
                    }
                }, r.getConstraintWidth = function(t) {
                    return o(t, "max-width", "clientWidth")
                }, r.getConstraintHeight = function(t) {
                    return o(t, "max-height", "clientHeight")
                }, r.getMaximumWidth = function(t) {
                    var e = t.parentNode;
                    if (!e) return t.clientWidth;
                    var i = parseInt(r.getStyle(e, "padding-left"), 10),
                        n = parseInt(r.getStyle(e, "padding-right"), 10),
                        a = e.clientWidth - i - n,
                        o = r.getConstraintWidth(t);
                    return isNaN(o) ? a : Math.min(a, o)
                }, r.getMaximumHeight = function(t) {
                    var e = t.parentNode;
                    if (!e) return t.clientHeight;
                    var i = parseInt(r.getStyle(e, "padding-top"), 10),
                        n = parseInt(r.getStyle(e, "padding-bottom"), 10),
                        a = e.clientHeight - i - n,
                        o = r.getConstraintHeight(t);
                    return isNaN(o) ? a : Math.min(a, o)
                }, r.getStyle = function(t, e) {
                    return t.currentStyle ? t.currentStyle[e] : document.defaultView.getComputedStyle(t, null).getPropertyValue(e)
                }, r.retinaScale = function(t, e) {
                    var i = t.currentDevicePixelRatio = e || window.devicePixelRatio || 1;
                    if (1 !== i) {
                        var n = t.canvas,
                            a = t.height,
                            r = t.width;
                        n.height = a * i, n.width = r * i, t.ctx.scale(i, i), n.style.height || n.style.width || (n.style.height = a + "px", n.style.width = r + "px")
                    }
                }, r.fontString = function(t, e, i) {
                    return e + " " + t + "px " + i
                }, r.longestText = function(t, e, i, n) {
                    var a = (n = n || {}).data = n.data || {},
                        o = n.garbageCollect = n.garbageCollect || [];
                    n.font !== e && (a = n.data = {}, o = n.garbageCollect = [], n.font = e), t.font = e;
                    var s = 0;
                    r.each(i, function(e) {
                        null != e && !0 !== r.isArray(e) ? s = r.measureText(t, a, o, s, e) : r.isArray(e) && r.each(e, function(e) {
                            null == e || r.isArray(e) || (s = r.measureText(t, a, o, s, e))
                        })
                    });
                    var l = o.length / 2;
                    if (l > i.length) {
                        for (var u = 0; u < l; u++) delete a[o[u]];
                        o.splice(0, l)
                    }
                    return s
                }, r.measureText = function(t, e, i, n, a) {
                    var r = e[a];
                    return r || (r = e[a] = t.measureText(a).width, i.push(a)), r > n && (n = r), n
                }, r.numberOfLabelLines = function(t) {
                    var e = 1;
                    return r.each(t, function(t) {
                        r.isArray(t) && t.length > e && (e = t.length)
                    }), e
                }, r.color = n ? function(t) {
                    return t instanceof CanvasGradient && (t = a.global.defaultColor), n(t)
                } : function(t) {
                    return console.error("Color.js not found!"), t
                }, r.getHoverColor = function(t) {
                    return t instanceof CanvasPattern ? t : r.color(t).saturate(.5).darken(.1).rgbString()
                }
            }
        }, {
            2: 2,
            25: 25,
            45: 45
        }],
        28: [function(t, e, i) {
            "use strict";
            var n = t(45);

            function a(t, e) {
                return t.native ? {
                    x: t.x,
                    y: t.y
                } : n.getRelativePosition(t, e)
            }

            function r(t, e) {
                var i, n, a, r, o;
                for (n = 0, r = t.data.datasets.length; n < r; ++n)
                    if (t.isDatasetVisible(n))
                        for (a = 0, o = (i = t.getDatasetMeta(n)).data.length; a < o; ++a) {
                            var s = i.data[a];
                            s._view.skip || e(s)
                        }
            }

            function o(t, e) {
                var i = [];
                return r(t, function(t) {
                    t.inRange(e.x, e.y) && i.push(t)
                }), i
            }

            function s(t, e, i, n) {
                var a = Number.POSITIVE_INFINITY,
                    o = [];
                return r(t, function(t) {
                    if (!i || t.inRange(e.x, e.y)) {
                        var r = t.getCenterPoint(),
                            s = n(e, r);
                        s < a ? (o = [t], a = s) : s === a && o.push(t)
                    }
                }), o
            }

            function l(t) {
                var e = -1 !== t.indexOf("x"),
                    i = -1 !== t.indexOf("y");
                return function(t, n) {
                    var a = e ? Math.abs(t.x - n.x) : 0,
                        r = i ? Math.abs(t.y - n.y) : 0;
                    return Math.sqrt(Math.pow(a, 2) + Math.pow(r, 2))
                }
            }

            function u(t, e, i) {
                var n = a(e, t);
                i.axis = i.axis || "x";
                var r = l(i.axis),
                    u = i.intersect ? o(t, n) : s(t, n, !1, r),
                    d = [];
                return u.length ? (t.data.datasets.forEach(function(e, i) {
                    if (t.isDatasetVisible(i)) {
                        var n = t.getDatasetMeta(i).data[u[0]._index];
                        n && !n._view.skip && d.push(n)
                    }
                }), d) : []
            }
            e.exports = {
                modes: {
                    single: function(t, e) {
                        var i = a(e, t),
                            n = [];
                        return r(t, function(t) {
                            if (t.inRange(i.x, i.y)) return n.push(t), n
                        }), n.slice(0, 1)
                    },
                    label: u,
                    index: u,
                    dataset: function(t, e, i) {
                        var n = a(e, t);
                        i.axis = i.axis || "xy";
                        var r = l(i.axis),
                            u = i.intersect ? o(t, n) : s(t, n, !1, r);
                        return u.length > 0 && (u = t.getDatasetMeta(u[0]._datasetIndex).data), u
                    },
                    "x-axis": function(t, e) {
                        return u(t, e, {
                            intersect: !1
                        })
                    },
                    point: function(t, e) {
                        return o(t, a(e, t))
                    },
                    nearest: function(t, e, i) {
                        var n = a(e, t);
                        i.axis = i.axis || "xy";
                        var r = l(i.axis),
                            o = s(t, n, i.intersect, r);
                        return o.length > 1 && o.sort(function(t, e) {
                            var i = t.getArea() - e.getArea();
                            return 0 === i && (i = t._datasetIndex - e._datasetIndex), i
                        }), o.slice(0, 1)
                    },
                    x: function(t, e, i) {
                        var n = a(e, t),
                            o = [],
                            s = !1;
                        return r(t, function(t) {
                            t.inXRange(n.x) && o.push(t), t.inRange(n.x, n.y) && (s = !0)
                        }), i.intersect && !s && (o = []), o
                    },
                    y: function(t, e, i) {
                        var n = a(e, t),
                            o = [],
                            s = !1;
                        return r(t, function(t) {
                            t.inYRange(n.y) && o.push(t), t.inRange(n.x, n.y) && (s = !0)
                        }), i.intersect && !s && (o = []), o
                    }
                }
            }
        }, {
            45: 45
        }],
        29: [function(t, e, i) {
            "use strict";
            t(25)._set("global", {
                responsive: !0,
                responsiveAnimationDuration: 0,
                maintainAspectRatio: !0,
                events: ["mousemove", "mouseout", "click", "touchstart", "touchmove"],
                hover: {
                    onHover: null,
                    mode: "nearest",
                    intersect: !0,
                    animationDuration: 400
                },
                onClick: null,
                defaultColor: "rgba(0,0,0,0.1)",
                defaultFontColor: "#666",
                defaultFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
                defaultFontSize: 12,
                defaultFontStyle: "normal",
                showLines: !0,
                elements: {},
                layout: {
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0
                    }
                }
            }), e.exports = function() {
                var t = function(t, e) {
                    return this.construct(t, e), this
                };
                return t.Chart = t, t
            }
        }, {
            25: 25
        }],
        30: [function(t, e, i) {
            "use strict";
            var n = t(45);

            function a(t, e) {
                return n.where(t, function(t) {
                    return t.position === e
                })
            }

            function r(t, e) {
                t.forEach(function(t, e) {
                    return t._tmpIndex_ = e, t
                }), t.sort(function(t, i) {
                    var n = e ? i : t,
                        a = e ? t : i;
                    return n.weight === a.weight ? n._tmpIndex_ - a._tmpIndex_ : n.weight - a.weight
                }), t.forEach(function(t) {
                    delete t._tmpIndex_
                })
            }
            e.exports = {
                defaults: {},
                addBox: function(t, e) {
                    t.boxes || (t.boxes = []), e.fullWidth = e.fullWidth || !1, e.position = e.position || "top", e.weight = e.weight || 0, t.boxes.push(e)
                },
                removeBox: function(t, e) {
                    var i = t.boxes ? t.boxes.indexOf(e) : -1; - 1 !== i && t.boxes.splice(i, 1)
                },
                configure: function(t, e, i) {
                    for (var n, a = ["fullWidth", "position", "weight"], r = a.length, o = 0; o < r; ++o) n = a[o], i.hasOwnProperty(n) && (e[n] = i[n])
                },
                update: function(t, e, i) {
                    if (t) {
                        var o = t.options.layout || {},
                            s = n.options.toPadding(o.padding),
                            l = s.left,
                            u = s.right,
                            d = s.top,
                            h = s.bottom,
                            c = a(t.boxes, "left"),
                            f = a(t.boxes, "right"),
                            g = a(t.boxes, "top"),
                            m = a(t.boxes, "bottom"),
                            p = a(t.boxes, "chartArea");
                        r(c, !0), r(f, !1), r(g, !0), r(m, !1);
                        var v = e - l - u,
                            y = i - d - h,
                            b = y / 2,
                            x = (e - v / 2) / (c.length + f.length),
                            _ = (i - b) / (g.length + m.length),
                            k = v,
                            w = y,
                            M = [];
                        n.each(c.concat(f, g, m), function(t) {
                            var e, i = t.isHorizontal();
                            i ? (e = t.update(t.fullWidth ? v : k, _), w -= e.height) : (e = t.update(x, w), k -= e.width), M.push({
                                horizontal: i,
                                minSize: e,
                                box: t
                            })
                        });
                        var S = 0,
                            D = 0,
                            C = 0,
                            P = 0;
                        n.each(g.concat(m), function(t) {
                            if (t.getPadding) {
                                var e = t.getPadding();
                                S = Math.max(S, e.left), D = Math.max(D, e.right)
                            }
                        }), n.each(c.concat(f), function(t) {
                            if (t.getPadding) {
                                var e = t.getPadding();
                                C = Math.max(C, e.top), P = Math.max(P, e.bottom)
                            }
                        });
                        var T = l,
                            O = u,
                            I = d,
                            A = h;
                        n.each(c.concat(f), z), n.each(c, function(t) {
                            T += t.width
                        }), n.each(f, function(t) {
                            O += t.width
                        }), n.each(g.concat(m), z), n.each(g, function(t) {
                            I += t.height
                        }), n.each(m, function(t) {
                            A += t.height
                        }), n.each(c.concat(f), function(t) {
                            var e = n.findNextWhere(M, function(e) {
                                    return e.box === t
                                }),
                                i = {
                                    left: 0,
                                    right: 0,
                                    top: I,
                                    bottom: A
                                };
                            e && t.update(e.minSize.width, w, i)
                        }), T = l, O = u, I = d, A = h, n.each(c, function(t) {
                            T += t.width
                        }), n.each(f, function(t) {
                            O += t.width
                        }), n.each(g, function(t) {
                            I += t.height
                        }), n.each(m, function(t) {
                            A += t.height
                        });
                        var F = Math.max(S - T, 0);
                        T += F, O += Math.max(D - O, 0);
                        var R = Math.max(C - I, 0);
                        I += R, A += Math.max(P - A, 0);
                        var L = i - I - A,
                            W = e - T - O;
                        W === k && L === w || (n.each(c, function(t) {
                            t.height = L
                        }), n.each(f, function(t) {
                            t.height = L
                        }), n.each(g, function(t) {
                            t.fullWidth || (t.width = W)
                        }), n.each(m, function(t) {
                            t.fullWidth || (t.width = W)
                        }), w = L, k = W);
                        var Y = l + F,
                            N = d + R;
                        n.each(c.concat(g), H), Y += k, N += w, n.each(f, H), n.each(m, H), t.chartArea = {
                            left: T,
                            top: I,
                            right: T + k,
                            bottom: I + w
                        }, n.each(p, function(e) {
                            e.left = t.chartArea.left, e.top = t.chartArea.top, e.right = t.chartArea.right, e.bottom = t.chartArea.bottom, e.update(k, w)
                        })
                    }

                    function z(t) {
                        var e = n.findNextWhere(M, function(e) {
                            return e.box === t
                        });
                        if (e)
                            if (t.isHorizontal()) {
                                var i = {
                                    left: Math.max(T, S),
                                    right: Math.max(O, D),
                                    top: 0,
                                    bottom: 0
                                };
                                t.update(t.fullWidth ? v : k, y / 2, i)
                            } else t.update(e.minSize.width, w)
                    }

                    function H(t) {
                        t.isHorizontal() ? (t.left = t.fullWidth ? l : T, t.right = t.fullWidth ? e - u : T + k, t.top = N, t.bottom = N + t.height, N = t.bottom) : (t.left = Y, t.right = Y + t.width, t.top = I, t.bottom = I + w, Y = t.right)
                    }
                }
            }
        }, {
            45: 45
        }],
        31: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(45);
            n._set("global", {
                plugins: {}
            }), e.exports = {
                _plugins: [],
                _cacheId: 0,
                register: function(t) {
                    var e = this._plugins;
                    [].concat(t).forEach(function(t) {
                        -1 === e.indexOf(t) && e.push(t)
                    }), this._cacheId++
                },
                unregister: function(t) {
                    var e = this._plugins;
                    [].concat(t).forEach(function(t) {
                        var i = e.indexOf(t); - 1 !== i && e.splice(i, 1)
                    }), this._cacheId++
                },
                clear: function() {
                    this._plugins = [], this._cacheId++
                },
                count: function() {
                    return this._plugins.length
                },
                getAll: function() {
                    return this._plugins
                },
                notify: function(t, e, i) {
                    var n, a, r, o, s, l = this.descriptors(t),
                        u = l.length;
                    for (n = 0; n < u; ++n)
                        if ("function" == typeof(s = (r = (a = l[n]).plugin)[e]) && ((o = [t].concat(i || [])).push(a.options), !1 === s.apply(r, o))) return !1;
                    return !0
                },
                descriptors: function(t) {
                    var e = t.$plugins || (t.$plugins = {});
                    if (e.id === this._cacheId) return e.descriptors;
                    var i = [],
                        r = [],
                        o = t && t.config || {},
                        s = o.options && o.options.plugins || {};
                    return this._plugins.concat(o.plugins || []).forEach(function(t) {
                        if (-1 === i.indexOf(t)) {
                            var e = t.id,
                                o = s[e];
                            !1 !== o && (!0 === o && (o = a.clone(n.global.plugins[e])), i.push(t), r.push({
                                plugin: t,
                                options: o || {}
                            }))
                        }
                    }), e.descriptors = r, e.id = this._cacheId, r
                },
                _invalidate: function(t) {
                    delete t.$plugins
                }
            }
        }, {
            25: 25,
            45: 45
        }],
        32: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(26),
                r = t(45),
                o = t(34);

            function s(t) {
                var e, i, n = [];
                for (e = 0, i = t.length; e < i; ++e) n.push(t[e].label);
                return n
            }

            function l(t, e, i) {
                var n = t.getPixelForTick(e);
                return i && (n -= 0 === e ? (t.getPixelForTick(1) - n) / 2 : (n - t.getPixelForTick(e - 1)) / 2), n
            }
            n._set("scale", {
                display: !0,
                position: "left",
                offset: !1,
                gridLines: {
                    display: !0,
                    color: "rgba(0, 0, 0, 0.1)",
                    lineWidth: 1,
                    drawBorder: !0,
                    drawOnChartArea: !0,
                    drawTicks: !0,
                    tickMarkLength: 10,
                    zeroLineWidth: 1,
                    zeroLineColor: "rgba(0,0,0,0.25)",
                    zeroLineBorderDash: [],
                    zeroLineBorderDashOffset: 0,
                    offsetGridLines: !1,
                    borderDash: [],
                    borderDashOffset: 0
                },
                scaleLabel: {
                    display: !1,
                    labelString: "",
                    lineHeight: 1.2,
                    padding: {
                        top: 4,
                        bottom: 4
                    }
                },
                ticks: {
                    beginAtZero: !1,
                    minRotation: 0,
                    maxRotation: 50,
                    mirror: !1,
                    padding: 0,
                    reverse: !1,
                    display: !0,
                    autoSkip: !0,
                    autoSkipPadding: 0,
                    labelOffset: 0,
                    callback: o.formatters.values,
                    minor: {},
                    major: {}
                }
            }), e.exports = function(t) {
                function e(t, e, i) {
                    return r.isArray(e) ? r.longestText(t, i, e) : t.measureText(e).width
                }

                function i(t) {
                    var e = r.valueOrDefault,
                        i = n.global,
                        a = e(t.fontSize, i.defaultFontSize),
                        o = e(t.fontStyle, i.defaultFontStyle),
                        s = e(t.fontFamily, i.defaultFontFamily);
                    return {
                        size: a,
                        style: o,
                        family: s,
                        font: r.fontString(a, o, s)
                    }
                }

                function o(t) {
                    return r.options.toLineHeight(r.valueOrDefault(t.lineHeight, 1.2), r.valueOrDefault(t.fontSize, n.global.defaultFontSize))
                }
                t.Scale = a.extend({
                    getPadding: function() {
                        return {
                            left: this.paddingLeft || 0,
                            top: this.paddingTop || 0,
                            right: this.paddingRight || 0,
                            bottom: this.paddingBottom || 0
                        }
                    },
                    getTicks: function() {
                        return this._ticks
                    },
                    mergeTicksOptions: function() {
                        var t = this.options.ticks;
                        for (var e in !1 === t.minor && (t.minor = {
                                display: !1
                            }), !1 === t.major && (t.major = {
                                display: !1
                            }), t) "major" !== e && "minor" !== e && (void 0 === t.minor[e] && (t.minor[e] = t[e]), void 0 === t.major[e] && (t.major[e] = t[e]))
                    },
                    beforeUpdate: function() {
                        r.callback(this.options.beforeUpdate, [this])
                    },
                    update: function(t, e, i) {
                        var n, a, o, s, l, u, d = this;
                        for (d.beforeUpdate(), d.maxWidth = t, d.maxHeight = e, d.margins = r.extend({
                                left: 0,
                                right: 0,
                                top: 0,
                                bottom: 0
                            }, i), d.longestTextCache = d.longestTextCache || {}, d.beforeSetDimensions(), d.setDimensions(), d.afterSetDimensions(), d.beforeDataLimits(), d.determineDataLimits(), d.afterDataLimits(), d.beforeBuildTicks(), l = d.buildTicks() || [], d.afterBuildTicks(), d.beforeTickToLabelConversion(), o = d.convertTicksToLabels(l) || d.ticks, d.afterTickToLabelConversion(), d.ticks = o, n = 0, a = o.length; n < a; ++n) s = o[n], (u = l[n]) ? u.label = s : l.push(u = {
                            label: s,
                            major: !1
                        });
                        return d._ticks = l, d.beforeCalculateTickRotation(), d.calculateTickRotation(), d.afterCalculateTickRotation(), d.beforeFit(), d.fit(), d.afterFit(), d.afterUpdate(), d.minSize
                    },
                    afterUpdate: function() {
                        r.callback(this.options.afterUpdate, [this])
                    },
                    beforeSetDimensions: function() {
                        r.callback(this.options.beforeSetDimensions, [this])
                    },
                    setDimensions: function() {
                        var t = this;
                        t.isHorizontal() ? (t.width = t.maxWidth, t.left = 0, t.right = t.width) : (t.height = t.maxHeight, t.top = 0, t.bottom = t.height), t.paddingLeft = 0, t.paddingTop = 0, t.paddingRight = 0, t.paddingBottom = 0
                    },
                    afterSetDimensions: function() {
                        r.callback(this.options.afterSetDimensions, [this])
                    },
                    beforeDataLimits: function() {
                        r.callback(this.options.beforeDataLimits, [this])
                    },
                    determineDataLimits: r.noop,
                    afterDataLimits: function() {
                        r.callback(this.options.afterDataLimits, [this])
                    },
                    beforeBuildTicks: function() {
                        r.callback(this.options.beforeBuildTicks, [this])
                    },
                    buildTicks: r.noop,
                    afterBuildTicks: function() {
                        r.callback(this.options.afterBuildTicks, [this])
                    },
                    beforeTickToLabelConversion: function() {
                        r.callback(this.options.beforeTickToLabelConversion, [this])
                    },
                    convertTicksToLabels: function() {
                        var t = this.options.ticks;
                        this.ticks = this.ticks.map(t.userCallback || t.callback, this)
                    },
                    afterTickToLabelConversion: function() {
                        r.callback(this.options.afterTickToLabelConversion, [this])
                    },
                    beforeCalculateTickRotation: function() {
                        r.callback(this.options.beforeCalculateTickRotation, [this])
                    },
                    calculateTickRotation: function() {
                        var t = this,
                            e = t.ctx,
                            n = t.options.ticks,
                            a = s(t._ticks),
                            o = i(n);
                        e.font = o.font;
                        var l = n.minRotation || 0;
                        if (a.length && t.options.display && t.isHorizontal())
                            for (var u, d = r.longestText(e, o.font, a, t.longestTextCache), h = d, c = t.getPixelForTick(1) - t.getPixelForTick(0) - 6; h > c && l < n.maxRotation;) {
                                var f = r.toRadians(l);
                                if (u = Math.cos(f), Math.sin(f) * d > t.maxHeight) {
                                    l--;
                                    break
                                }
                                l++, h = u * d
                            }
                        t.labelRotation = l
                    },
                    afterCalculateTickRotation: function() {
                        r.callback(this.options.afterCalculateTickRotation, [this])
                    },
                    beforeFit: function() {
                        r.callback(this.options.beforeFit, [this])
                    },
                    fit: function() {
                        var t = this,
                            n = t.minSize = {
                                width: 0,
                                height: 0
                            },
                            a = s(t._ticks),
                            l = t.options,
                            u = l.ticks,
                            d = l.scaleLabel,
                            h = l.gridLines,
                            c = l.display,
                            f = t.isHorizontal(),
                            g = i(u),
                            m = l.gridLines.tickMarkLength;
                        if (n.width = f ? t.isFullWidth() ? t.maxWidth - t.margins.left - t.margins.right : t.maxWidth : c && h.drawTicks ? m : 0, n.height = f ? c && h.drawTicks ? m : 0 : t.maxHeight, d.display && c) {
                            var p = o(d) + r.options.toPadding(d.padding).height;
                            f ? n.height += p : n.width += p
                        }
                        if (u.display && c) {
                            var v = r.longestText(t.ctx, g.font, a, t.longestTextCache),
                                y = r.numberOfLabelLines(a),
                                b = .5 * g.size,
                                x = t.options.ticks.padding;
                            if (f) {
                                t.longestLabelWidth = v;
                                var _ = r.toRadians(t.labelRotation),
                                    k = Math.cos(_),
                                    w = Math.sin(_) * v + g.size * y + b * (y - 1) + b;
                                n.height = Math.min(t.maxHeight, n.height + w + x), t.ctx.font = g.font;
                                var M = e(t.ctx, a[0], g.font),
                                    S = e(t.ctx, a[a.length - 1], g.font);
                                0 !== t.labelRotation ? (t.paddingLeft = "bottom" === l.position ? k * M + 3 : k * b + 3, t.paddingRight = "bottom" === l.position ? k * b + 3 : k * S + 3) : (t.paddingLeft = M / 2 + 3, t.paddingRight = S / 2 + 3)
                            } else u.mirror ? v = 0 : v += x + b, n.width = Math.min(t.maxWidth, n.width + v), t.paddingTop = g.size / 2, t.paddingBottom = g.size / 2
                        }
                        t.handleMargins(), t.width = n.width, t.height = n.height
                    },
                    handleMargins: function() {
                        var t = this;
                        t.margins && (t.paddingLeft = Math.max(t.paddingLeft - t.margins.left, 0), t.paddingTop = Math.max(t.paddingTop - t.margins.top, 0), t.paddingRight = Math.max(t.paddingRight - t.margins.right, 0), t.paddingBottom = Math.max(t.paddingBottom - t.margins.bottom, 0))
                    },
                    afterFit: function() {
                        r.callback(this.options.afterFit, [this])
                    },
                    isHorizontal: function() {
                        return "top" === this.options.position || "bottom" === this.options.position
                    },
                    isFullWidth: function() {
                        return this.options.fullWidth
                    },
                    getRightValue: function(t) {
                        if (r.isNullOrUndef(t)) return NaN;
                        if ("number" == typeof t && !isFinite(t)) return NaN;
                        if (t)
                            if (this.isHorizontal()) {
                                if (void 0 !== t.x) return this.getRightValue(t.x)
                            } else if (void 0 !== t.y) return this.getRightValue(t.y);
                        return t
                    },
                    getLabelForIndex: r.noop,
                    getPixelForValue: r.noop,
                    getValueForPixel: r.noop,
                    getPixelForTick: function(t) {
                        var e = this,
                            i = e.options.offset;
                        if (e.isHorizontal()) {
                            var n = (e.width - (e.paddingLeft + e.paddingRight)) / Math.max(e._ticks.length - (i ? 0 : 1), 1),
                                a = n * t + e.paddingLeft;
                            i && (a += n / 2);
                            var r = e.left + Math.round(a);
                            return r += e.isFullWidth() ? e.margins.left : 0
                        }
                        var o = e.height - (e.paddingTop + e.paddingBottom);
                        return e.top + t * (o / (e._ticks.length - 1))
                    },
                    getPixelForDecimal: function(t) {
                        var e = this;
                        if (e.isHorizontal()) {
                            var i = (e.width - (e.paddingLeft + e.paddingRight)) * t + e.paddingLeft,
                                n = e.left + Math.round(i);
                            return n += e.isFullWidth() ? e.margins.left : 0
                        }
                        return e.top + t * e.height
                    },
                    getBasePixel: function() {
                        return this.getPixelForValue(this.getBaseValue())
                    },
                    getBaseValue: function() {
                        var t = this.min,
                            e = this.max;
                        return this.beginAtZero ? 0 : t < 0 && e < 0 ? e : t > 0 && e > 0 ? t : 0
                    },
                    _autoSkip: function(t) {
                        var e, i, n, a, o = this,
                            s = o.isHorizontal(),
                            l = o.options.ticks.minor,
                            u = t.length,
                            d = r.toRadians(o.labelRotation),
                            h = Math.cos(d),
                            c = o.longestLabelWidth * h,
                            f = [];
                        for (l.maxTicksLimit && (a = l.maxTicksLimit), s && (e = !1, (c + l.autoSkipPadding) * u > o.width - (o.paddingLeft + o.paddingRight) && (e = 1 + Math.floor((c + l.autoSkipPadding) * u / (o.width - (o.paddingLeft + o.paddingRight)))), a && u > a && (e = Math.max(e, Math.floor(u / a)))), i = 0; i < u; i++) n = t[i], (e > 1 && i % e > 0 || i % e == 0 && i + e >= u) && i !== u - 1 && delete n.label, f.push(n);
                        return f
                    },
                    draw: function(t) {
                        var e = this,
                            a = e.options;
                        if (a.display) {
                            var s = e.ctx,
                                u = n.global,
                                d = a.ticks.minor,
                                h = a.ticks.major || d,
                                c = a.gridLines,
                                f = a.scaleLabel,
                                g = 0 !== e.labelRotation,
                                m = e.isHorizontal(),
                                p = d.autoSkip ? e._autoSkip(e.getTicks()) : e.getTicks(),
                                v = r.valueOrDefault(d.fontColor, u.defaultFontColor),
                                y = i(d),
                                b = r.valueOrDefault(h.fontColor, u.defaultFontColor),
                                x = i(h),
                                _ = c.drawTicks ? c.tickMarkLength : 0,
                                k = r.valueOrDefault(f.fontColor, u.defaultFontColor),
                                w = i(f),
                                M = r.options.toPadding(f.padding),
                                S = r.toRadians(e.labelRotation),
                                D = [],
                                C = e.options.gridLines.lineWidth,
                                P = "right" === a.position ? e.right : e.right - C - _,
                                T = "right" === a.position ? e.right + _ : e.right,
                                O = "bottom" === a.position ? e.top + C : e.bottom - _ - C,
                                I = "bottom" === a.position ? e.top + C + _ : e.bottom + C;
                            if (r.each(p, function(i, n) {
                                    if (!r.isNullOrUndef(i.label)) {
                                        var o, s, h, f, v, y, b, x, k, w, M, A, F, R, L = i.label;
                                        n === e.zeroLineIndex && a.offset === c.offsetGridLines ? (o = c.zeroLineWidth, s = c.zeroLineColor, h = c.zeroLineBorderDash, f = c.zeroLineBorderDashOffset) : (o = r.valueAtIndexOrDefault(c.lineWidth, n), s = r.valueAtIndexOrDefault(c.color, n), h = r.valueOrDefault(c.borderDash, u.borderDash), f = r.valueOrDefault(c.borderDashOffset, u.borderDashOffset));
                                        var W = "middle",
                                            Y = "middle",
                                            N = d.padding;
                                        if (m) {
                                            var z = _ + N;
                                            "bottom" === a.position ? (Y = g ? "middle" : "top", W = g ? "right" : "center", R = e.top + z) : (Y = g ? "middle" : "bottom", W = g ? "left" : "center", R = e.bottom - z);
                                            var H = l(e, n, c.offsetGridLines && p.length > 1);
                                            H < e.left && (s = "rgba(0,0,0,0)"), H += r.aliasPixel(o), F = e.getPixelForTick(n) + d.labelOffset, v = b = k = M = H, y = O, x = I, w = t.top, A = t.bottom + C
                                        } else {
                                            var V, B = "left" === a.position;
                                            d.mirror ? (W = B ? "left" : "right", V = N) : (W = B ? "right" : "left", V = _ + N), F = B ? e.right - V : e.left + V;
                                            var E = l(e, n, c.offsetGridLines && p.length > 1);
                                            E < e.top && (s = "rgba(0,0,0,0)"), E += r.aliasPixel(o), R = e.getPixelForTick(n) + d.labelOffset, v = P, b = T, k = t.left, M = t.right + C, y = x = w = A = E
                                        }
                                        D.push({
                                            tx1: v,
                                            ty1: y,
                                            tx2: b,
                                            ty2: x,
                                            x1: k,
                                            y1: w,
                                            x2: M,
                                            y2: A,
                                            labelX: F,
                                            labelY: R,
                                            glWidth: o,
                                            glColor: s,
                                            glBorderDash: h,
                                            glBorderDashOffset: f,
                                            rotation: -1 * S,
                                            label: L,
                                            major: i.major,
                                            textBaseline: Y,
                                            textAlign: W
                                        })
                                    }
                                }), r.each(D, function(t) {
                                    if (c.display && (s.save(), s.lineWidth = t.glWidth, s.strokeStyle = t.glColor, s.setLineDash && (s.setLineDash(t.glBorderDash), s.lineDashOffset = t.glBorderDashOffset), s.beginPath(), c.drawTicks && (s.moveTo(t.tx1, t.ty1), s.lineTo(t.tx2, t.ty2)), c.drawOnChartArea && (s.moveTo(t.x1, t.y1), s.lineTo(t.x2, t.y2)), s.stroke(), s.restore()), d.display) {
                                        s.save(), s.translate(t.labelX, t.labelY), s.rotate(t.rotation), s.font = t.major ? x.font : y.font, s.fillStyle = t.major ? b : v, s.textBaseline = t.textBaseline, s.textAlign = t.textAlign;
                                        var i = t.label;
                                        if (r.isArray(i))
                                            for (var n = i.length, a = 1.5 * y.size, o = e.isHorizontal() ? 0 : -a * (n - 1) / 2, l = 0; l < n; ++l) s.fillText("" + i[l], 0, o), o += a;
                                        else s.fillText(i, 0, 0);
                                        s.restore()
                                    }
                                }), f.display) {
                                var A, F, R = 0,
                                    L = o(f) / 2;
                                if (m) A = e.left + (e.right - e.left) / 2, F = "bottom" === a.position ? e.bottom - L - M.bottom : e.top + L + M.top;
                                else {
                                    var W = "left" === a.position;
                                    A = W ? e.left + L + M.top : e.right - L - M.top, F = e.top + (e.bottom - e.top) / 2, R = W ? -.5 * Math.PI : .5 * Math.PI
                                }
                                s.save(), s.translate(A, F), s.rotate(R), s.textAlign = "center", s.textBaseline = "middle", s.fillStyle = k, s.font = w.font, s.fillText(f.labelString, 0, 0), s.restore()
                            }
                            if (c.drawBorder) {
                                s.lineWidth = r.valueAtIndexOrDefault(c.lineWidth, 0), s.strokeStyle = r.valueAtIndexOrDefault(c.color, 0);
                                var Y = e.left,
                                    N = e.right + C,
                                    z = e.top,
                                    H = e.bottom + C,
                                    V = r.aliasPixel(s.lineWidth);
                                m ? (z = H = "top" === a.position ? e.bottom : e.top, z += V, H += V) : (Y = N = "left" === a.position ? e.right : e.left, Y += V, N += V), s.beginPath(), s.moveTo(Y, z), s.lineTo(N, H), s.stroke()
                            }
                        }
                    }
                })
            }
        }, {
            25: 25,
            26: 26,
            34: 34,
            45: 45
        }],
        33: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(45),
                r = t(30);
            e.exports = function(t) {
                t.scaleService = {
                    constructors: {},
                    defaults: {},
                    registerScaleType: function(t, e, i) {
                        this.constructors[t] = e, this.defaults[t] = a.clone(i)
                    },
                    getScaleConstructor: function(t) {
                        return this.constructors.hasOwnProperty(t) ? this.constructors[t] : void 0
                    },
                    getScaleDefaults: function(t) {
                        return this.defaults.hasOwnProperty(t) ? a.merge({}, [n.scale, this.defaults[t]]) : {}
                    },
                    updateScaleDefaults: function(t, e) {
                        this.defaults.hasOwnProperty(t) && (this.defaults[t] = a.extend(this.defaults[t], e))
                    },
                    addScalesToLayout: function(t) {
                        a.each(t.scales, function(e) {
                            e.fullWidth = e.options.fullWidth, e.position = e.options.position, e.weight = e.options.weight, r.addBox(t, e)
                        })
                    }
                }
            }
        }, {
            25: 25,
            30: 30,
            45: 45
        }],
        34: [function(t, e, i) {
            "use strict";
            var n = t(45);
            e.exports = {
                formatters: {
                    values: function(t) {
                        return n.isArray(t) ? t : "" + t
                    },
                    linear: function(t, e, i) {
                        var a = i.length > 3 ? i[2] - i[1] : i[1] - i[0];
                        Math.abs(a) > 1 && t !== Math.floor(t) && (a = t - Math.floor(t));
                        var r = n.log10(Math.abs(a)),
                            o = "";
                        if (0 !== t) {
                            var s = -1 * Math.floor(r);
                            s = Math.max(Math.min(s, 20), 0), o = t.toFixed(s)
                        } else o = "0";
                        return o
                    },
                    logarithmic: function(t, e, i) {
                        var a = t / Math.pow(10, Math.floor(n.log10(t)));
                        return 0 === t ? "0" : 1 === a || 2 === a || 5 === a || 0 === e || e === i.length - 1 ? t.toExponential() : ""
                    }
                }
            }
        }, {
            45: 45
        }],
        35: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(26),
                r = t(45);
            n._set("global", {
                tooltips: {
                    enabled: !0,
                    custom: null,
                    mode: "nearest",
                    position: "average",
                    intersect: !0,
                    backgroundColor: "rgba(0,0,0,0.8)",
                    titleFontStyle: "bold",
                    titleSpacing: 2,
                    titleMarginBottom: 6,
                    titleFontColor: "#fff",
                    titleAlign: "left",
                    bodySpacing: 2,
                    bodyFontColor: "#fff",
                    bodyAlign: "left",
                    footerFontStyle: "bold",
                    footerSpacing: 2,
                    footerMarginTop: 6,
                    footerFontColor: "#fff",
                    footerAlign: "left",
                    yPadding: 6,
                    xPadding: 6,
                    caretPadding: 2,
                    caretSize: 5,
                    cornerRadius: 6,
                    multiKeyBackground: "#fff",
                    displayColors: !0,
                    borderColor: "rgba(0,0,0,0)",
                    borderWidth: 0,
                    callbacks: {
                        beforeTitle: r.noop,
                        title: function(t, e) {
                            var i = "",
                                n = e.labels,
                                a = n ? n.length : 0;
                            if (t.length > 0) {
                                var r = t[0];
                                r.xLabel ? i = r.xLabel : a > 0 && r.index < a && (i = n[r.index])
                            }
                            return i
                        },
                        afterTitle: r.noop,
                        beforeBody: r.noop,
                        beforeLabel: r.noop,
                        label: function(t, e) {
                            var i = e.datasets[t.datasetIndex].label || "";
                            return i && (i += ": "), i += t.yLabel
                        },
                        labelColor: function(t, e) {
                            var i = e.getDatasetMeta(t.datasetIndex).data[t.index]._view;
                            return {
                                borderColor: i.borderColor,
                                backgroundColor: i.backgroundColor
                            }
                        },
                        labelTextColor: function() {
                            return this._options.bodyFontColor
                        },
                        afterLabel: r.noop,
                        afterBody: r.noop,
                        beforeFooter: r.noop,
                        footer: r.noop,
                        afterFooter: r.noop
                    }
                }
            }), e.exports = function(t) {
                function e(t, e) {
                    var i = r.color(t);
                    return i.alpha(e * i.alpha()).rgbaString()
                }

                function i(t, e) {
                    return e && (r.isArray(e) ? Array.prototype.push.apply(t, e) : t.push(e)), t
                }

                function o(t) {
                    var e = n.global,
                        i = r.valueOrDefault;
                    return {
                        xPadding: t.xPadding,
                        yPadding: t.yPadding,
                        xAlign: t.xAlign,
                        yAlign: t.yAlign,
                        bodyFontColor: t.bodyFontColor,
                        _bodyFontFamily: i(t.bodyFontFamily, e.defaultFontFamily),
                        _bodyFontStyle: i(t.bodyFontStyle, e.defaultFontStyle),
                        _bodyAlign: t.bodyAlign,
                        bodyFontSize: i(t.bodyFontSize, e.defaultFontSize),
                        bodySpacing: t.bodySpacing,
                        titleFontColor: t.titleFontColor,
                        _titleFontFamily: i(t.titleFontFamily, e.defaultFontFamily),
                        _titleFontStyle: i(t.titleFontStyle, e.defaultFontStyle),
                        titleFontSize: i(t.titleFontSize, e.defaultFontSize),
                        _titleAlign: t.titleAlign,
                        titleSpacing: t.titleSpacing,
                        titleMarginBottom: t.titleMarginBottom,
                        footerFontColor: t.footerFontColor,
                        _footerFontFamily: i(t.footerFontFamily, e.defaultFontFamily),
                        _footerFontStyle: i(t.footerFontStyle, e.defaultFontStyle),
                        footerFontSize: i(t.footerFontSize, e.defaultFontSize),
                        _footerAlign: t.footerAlign,
                        footerSpacing: t.footerSpacing,
                        footerMarginTop: t.footerMarginTop,
                        caretSize: t.caretSize,
                        cornerRadius: t.cornerRadius,
                        backgroundColor: t.backgroundColor,
                        opacity: 0,
                        legendColorBackground: t.multiKeyBackground,
                        displayColors: t.displayColors,
                        borderColor: t.borderColor,
                        borderWidth: t.borderWidth
                    }
                }
                t.Tooltip = a.extend({
                    initialize: function() {
                        this._model = o(this._options), this._lastActive = []
                    },
                    getTitle: function() {
                        var t = this._options.callbacks,
                            e = t.beforeTitle.apply(this, arguments),
                            n = t.title.apply(this, arguments),
                            a = t.afterTitle.apply(this, arguments),
                            r = [];
                        return r = i(r = i(r = i(r, e), n), a)
                    },
                    getBeforeBody: function() {
                        var t = this._options.callbacks.beforeBody.apply(this, arguments);
                        return r.isArray(t) ? t : void 0 !== t ? [t] : []
                    },
                    getBody: function(t, e) {
                        var n = this,
                            a = n._options.callbacks,
                            o = [];
                        return r.each(t, function(t) {
                            var r = {
                                before: [],
                                lines: [],
                                after: []
                            };
                            i(r.before, a.beforeLabel.call(n, t, e)), i(r.lines, a.label.call(n, t, e)), i(r.after, a.afterLabel.call(n, t, e)), o.push(r)
                        }), o
                    },
                    getAfterBody: function() {
                        var t = this._options.callbacks.afterBody.apply(this, arguments);
                        return r.isArray(t) ? t : void 0 !== t ? [t] : []
                    },
                    getFooter: function() {
                        var t = this._options.callbacks,
                            e = t.beforeFooter.apply(this, arguments),
                            n = t.footer.apply(this, arguments),
                            a = t.afterFooter.apply(this, arguments),
                            r = [];
                        return r = i(r = i(r = i(r, e), n), a)
                    },
                    update: function(e) {
                        var i, n, a, s, l, u, d, h, c, f, g, m, p, v, y, b, x, _, k, w, M = this,
                            S = M._options,
                            D = M._model,
                            C = M._model = o(S),
                            P = M._active,
                            T = M._data,
                            O = {
                                xAlign: D.xAlign,
                                yAlign: D.yAlign
                            },
                            I = {
                                x: D.x,
                                y: D.y
                            },
                            A = {
                                width: D.width,
                                height: D.height
                            },
                            F = {
                                x: D.caretX,
                                y: D.caretY
                            };
                        if (P.length) {
                            C.opacity = 1;
                            var R = [],
                                L = [];
                            F = t.Tooltip.positioners[S.position].call(M, P, M._eventPosition);
                            var W = [];
                            for (i = 0, n = P.length; i < n; ++i) W.push((b = P[i], x = void 0, _ = void 0, void 0, void 0, x = b._xScale, _ = b._yScale || b._scale, k = b._index, w = b._datasetIndex, {
                                xLabel: x ? x.getLabelForIndex(k, w) : "",
                                yLabel: _ ? _.getLabelForIndex(k, w) : "",
                                index: k,
                                datasetIndex: w,
                                x: b._model.x,
                                y: b._model.y
                            }));
                            S.filter && (W = W.filter(function(t) {
                                return S.filter(t, T)
                            })), S.itemSort && (W = W.sort(function(t, e) {
                                return S.itemSort(t, e, T)
                            })), r.each(W, function(t) {
                                R.push(S.callbacks.labelColor.call(M, t, M._chart)), L.push(S.callbacks.labelTextColor.call(M, t, M._chart))
                            }), C.title = M.getTitle(W, T), C.beforeBody = M.getBeforeBody(W, T), C.body = M.getBody(W, T), C.afterBody = M.getAfterBody(W, T), C.footer = M.getFooter(W, T), C.x = Math.round(F.x), C.y = Math.round(F.y), C.caretPadding = S.caretPadding, C.labelColors = R, C.labelTextColors = L, C.dataPoints = W, O = function(t, e) {
                                var i, n, a, r, o, s = t._model,
                                    l = t._chart,
                                    u = t._chart.chartArea,
                                    d = "center",
                                    h = "center";
                                s.y < e.height ? h = "top" : s.y > l.height - e.height && (h = "bottom");
                                var c = (u.left + u.right) / 2,
                                    f = (u.top + u.bottom) / 2;
                                "center" === h ? (i = function(t) {
                                    return t <= c
                                }, n = function(t) {
                                    return t > c
                                }) : (i = function(t) {
                                    return t <= e.width / 2
                                }, n = function(t) {
                                    return t >= l.width - e.width / 2
                                }), a = function(t) {
                                    return t + e.width + s.caretSize + s.caretPadding > l.width
                                }, r = function(t) {
                                    return t - e.width - s.caretSize - s.caretPadding < 0
                                }, o = function(t) {
                                    return t <= f ? "top" : "bottom"
                                }, i(s.x) ? (d = "left", a(s.x) && (d = "center", h = o(s.y))) : n(s.x) && (d = "right", r(s.x) && (d = "center", h = o(s.y)));
                                var g = t._options;
                                return {
                                    xAlign: g.xAlign ? g.xAlign : d,
                                    yAlign: g.yAlign ? g.yAlign : h
                                }
                            }(this, A = function(t, e) {
                                var i = t._chart.ctx,
                                    n = 2 * e.yPadding,
                                    a = 0,
                                    o = e.body,
                                    s = o.reduce(function(t, e) {
                                        return t + e.before.length + e.lines.length + e.after.length
                                    }, 0);
                                s += e.beforeBody.length + e.afterBody.length;
                                var l = e.title.length,
                                    u = e.footer.length,
                                    d = e.titleFontSize,
                                    h = e.bodyFontSize,
                                    c = e.footerFontSize;
                                n += l * d, n += l ? (l - 1) * e.titleSpacing : 0, n += l ? e.titleMarginBottom : 0, n += s * h, n += s ? (s - 1) * e.bodySpacing : 0, n += u ? e.footerMarginTop : 0, n += u * c, n += u ? (u - 1) * e.footerSpacing : 0;
                                var f = 0,
                                    g = function(t) {
                                        a = Math.max(a, i.measureText(t).width + f)
                                    };
                                return i.font = r.fontString(d, e._titleFontStyle, e._titleFontFamily), r.each(e.title, g), i.font = r.fontString(h, e._bodyFontStyle, e._bodyFontFamily), r.each(e.beforeBody.concat(e.afterBody), g), f = e.displayColors ? h + 2 : 0, r.each(o, function(t) {
                                    r.each(t.before, g), r.each(t.lines, g), r.each(t.after, g)
                                }), f = 0, i.font = r.fontString(c, e._footerFontStyle, e._footerFontFamily), r.each(e.footer, g), {
                                    width: a += 2 * e.xPadding,
                                    height: n
                                }
                            }(this, C)), a = C, s = A, l = O, u = M._chart, d = a.x, h = a.y, c = a.caretSize, f = a.caretPadding, g = a.cornerRadius, m = l.xAlign, p = l.yAlign, v = c + f, y = g + f, "right" === m ? d -= s.width : "center" === m && ((d -= s.width / 2) + s.width > u.width && (d = u.width - s.width), d < 0 && (d = 0)), "top" === p ? h += v : h -= "bottom" === p ? s.height + v : s.height / 2, "center" === p ? "left" === m ? d += v : "right" === m && (d -= v) : "left" === m ? d -= y : "right" === m && (d += y), I = {
                                x: d,
                                y: h
                            }
                        } else C.opacity = 0;
                        return C.xAlign = O.xAlign, C.yAlign = O.yAlign, C.x = I.x, C.y = I.y, C.width = A.width, C.height = A.height, C.caretX = F.x, C.caretY = F.y, M._model = C, e && S.custom && S.custom.call(M, C), M
                    },
                    drawCaret: function(t, e) {
                        var i = this._chart.ctx,
                            n = this._view,
                            a = this.getCaretPosition(t, e, n);
                        i.lineTo(a.x1, a.y1), i.lineTo(a.x2, a.y2), i.lineTo(a.x3, a.y3)
                    },
                    getCaretPosition: function(t, e, i) {
                        var n, a, r, o, s, l, u = i.caretSize,
                            d = i.cornerRadius,
                            h = i.xAlign,
                            c = i.yAlign,
                            f = t.x,
                            g = t.y,
                            m = e.width,
                            p = e.height;
                        if ("center" === c) s = g + p / 2, "left" === h ? (a = (n = f) - u, r = n, o = s + u, l = s - u) : (a = (n = f + m) + u, r = n, o = s - u, l = s + u);
                        else if ("left" === h ? (n = (a = f + d + u) - u, r = a + u) : "right" === h ? (n = (a = f + m - d - u) - u, r = a + u) : (n = (a = i.caretX) - u, r = a + u), "top" === c) s = (o = g) - u, l = o;
                        else {
                            s = (o = g + p) + u, l = o;
                            var v = r;
                            r = n, n = v
                        }
                        return {
                            x1: n,
                            x2: a,
                            x3: r,
                            y1: o,
                            y2: s,
                            y3: l
                        }
                    },
                    drawTitle: function(t, i, n, a) {
                        var o = i.title;
                        if (o.length) {
                            n.textAlign = i._titleAlign, n.textBaseline = "top";
                            var s, l, u = i.titleFontSize,
                                d = i.titleSpacing;
                            for (n.fillStyle = e(i.titleFontColor, a), n.font = r.fontString(u, i._titleFontStyle, i._titleFontFamily), s = 0, l = o.length; s < l; ++s) n.fillText(o[s], t.x, t.y), t.y += u + d, s + 1 === o.length && (t.y += i.titleMarginBottom - d)
                        }
                    },
                    drawBody: function(t, i, n, a) {
                        var o = i.bodyFontSize,
                            s = i.bodySpacing,
                            l = i.body;
                        n.textAlign = i._bodyAlign, n.textBaseline = "top", n.font = r.fontString(o, i._bodyFontStyle, i._bodyFontFamily);
                        var u = 0,
                            d = function(e) {
                                n.fillText(e, t.x + u, t.y), t.y += o + s
                            };
                        n.fillStyle = e(i.bodyFontColor, a), r.each(i.beforeBody, d);
                        var h = i.displayColors;
                        u = h ? o + 2 : 0, r.each(l, function(s, l) {
                            var u = e(i.labelTextColors[l], a);
                            n.fillStyle = u, r.each(s.before, d), r.each(s.lines, function(r) {
                                h && (n.fillStyle = e(i.legendColorBackground, a), n.fillRect(t.x, t.y, o, o), n.lineWidth = 1, n.strokeStyle = e(i.labelColors[l].borderColor, a), n.strokeRect(t.x, t.y, o, o), n.fillStyle = e(i.labelColors[l].backgroundColor, a), n.fillRect(t.x + 1, t.y + 1, o - 2, o - 2), n.fillStyle = u), d(r)
                            }), r.each(s.after, d)
                        }), u = 0, r.each(i.afterBody, d), t.y -= s
                    },
                    drawFooter: function(t, i, n, a) {
                        var o = i.footer;
                        o.length && (t.y += i.footerMarginTop, n.textAlign = i._footerAlign, n.textBaseline = "top", n.fillStyle = e(i.footerFontColor, a), n.font = r.fontString(i.footerFontSize, i._footerFontStyle, i._footerFontFamily), r.each(o, function(e) {
                            n.fillText(e, t.x, t.y), t.y += i.footerFontSize + i.footerSpacing
                        }))
                    },
                    drawBackground: function(t, i, n, a, r) {
                        n.fillStyle = e(i.backgroundColor, r), n.strokeStyle = e(i.borderColor, r), n.lineWidth = i.borderWidth;
                        var o = i.xAlign,
                            s = i.yAlign,
                            l = t.x,
                            u = t.y,
                            d = a.width,
                            h = a.height,
                            c = i.cornerRadius;
                        n.beginPath(), n.moveTo(l + c, u), "top" === s && this.drawCaret(t, a), n.lineTo(l + d - c, u), n.quadraticCurveTo(l + d, u, l + d, u + c), "center" === s && "right" === o && this.drawCaret(t, a), n.lineTo(l + d, u + h - c), n.quadraticCurveTo(l + d, u + h, l + d - c, u + h), "bottom" === s && this.drawCaret(t, a), n.lineTo(l + c, u + h), n.quadraticCurveTo(l, u + h, l, u + h - c), "center" === s && "left" === o && this.drawCaret(t, a), n.lineTo(l, u + c), n.quadraticCurveTo(l, u, l + c, u), n.closePath(), n.fill(), i.borderWidth > 0 && n.stroke()
                    },
                    draw: function() {
                        var t = this._chart.ctx,
                            e = this._view;
                        if (0 !== e.opacity) {
                            var i = {
                                    width: e.width,
                                    height: e.height
                                },
                                n = {
                                    x: e.x,
                                    y: e.y
                                },
                                a = Math.abs(e.opacity < .001) ? 0 : e.opacity,
                                r = e.title.length || e.beforeBody.length || e.body.length || e.afterBody.length || e.footer.length;
                            this._options.enabled && r && (this.drawBackground(n, e, t, i, a), n.x += e.xPadding, n.y += e.yPadding, this.drawTitle(n, e, t, a), this.drawBody(n, e, t, a), this.drawFooter(n, e, t, a))
                        }
                    },
                    handleEvent: function(t) {
                        var e, i = this,
                            n = i._options;
                        return i._lastActive = i._lastActive || [], "mouseout" === t.type ? i._active = [] : i._active = i._chart.getElementsAtEventForMode(t, n.mode, n), (e = !r.arrayEquals(i._active, i._lastActive)) && (i._lastActive = i._active, (n.enabled || n.custom) && (i._eventPosition = {
                            x: t.x,
                            y: t.y
                        }, i.update(!0), i.pivot())), e
                    }
                }), t.Tooltip.positioners = {
                    average: function(t) {
                        if (!t.length) return !1;
                        var e, i, n = 0,
                            a = 0,
                            r = 0;
                        for (e = 0, i = t.length; e < i; ++e) {
                            var o = t[e];
                            if (o && o.hasValue()) {
                                var s = o.tooltipPosition();
                                n += s.x, a += s.y, ++r
                            }
                        }
                        return {
                            x: Math.round(n / r),
                            y: Math.round(a / r)
                        }
                    },
                    nearest: function(t, e) {
                        var i, n, a, o = e.x,
                            s = e.y,
                            l = Number.POSITIVE_INFINITY;
                        for (i = 0, n = t.length; i < n; ++i) {
                            var u = t[i];
                            if (u && u.hasValue()) {
                                var d = u.getCenterPoint(),
                                    h = r.distanceBetweenPoints(e, d);
                                h < l && (l = h, a = u)
                            }
                        }
                        if (a) {
                            var c = a.tooltipPosition();
                            o = c.x, s = c.y
                        }
                        return {
                            x: o,
                            y: s
                        }
                    }
                }
            }
        }, {
            25: 25,
            26: 26,
            45: 45
        }],
        36: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(26),
                r = t(45);
            n._set("global", {
                elements: {
                    arc: {
                        backgroundColor: n.global.defaultColor,
                        borderColor: "#fff",
                        borderWidth: 2
                    }
                }
            }), e.exports = a.extend({
                inLabelRange: function(t) {
                    var e = this._view;
                    return !!e && Math.pow(t - e.x, 2) < Math.pow(e.radius + e.hoverRadius, 2)
                },
                inRange: function(t, e) {
                    var i = this._view;
                    if (i) {
                        for (var n = r.getAngleFromPoint(i, {
                                x: t,
                                y: e
                            }), a = n.angle, o = n.distance, s = i.startAngle, l = i.endAngle; l < s;) l += 2 * Math.PI;
                        for (; a > l;) a -= 2 * Math.PI;
                        for (; a < s;) a += 2 * Math.PI;
                        var u = a >= s && a <= l,
                            d = o >= i.innerRadius && o <= i.outerRadius;
                        return u && d
                    }
                    return !1
                },
                getCenterPoint: function() {
                    var t = this._view,
                        e = (t.startAngle + t.endAngle) / 2,
                        i = (t.innerRadius + t.outerRadius) / 2;
                    return {
                        x: t.x + Math.cos(e) * i,
                        y: t.y + Math.sin(e) * i
                    }
                },
                getArea: function() {
                    var t = this._view;
                    return Math.PI * ((t.endAngle - t.startAngle) / (2 * Math.PI)) * (Math.pow(t.outerRadius, 2) - Math.pow(t.innerRadius, 2))
                },
                tooltipPosition: function() {
                    var t = this._view,
                        e = t.startAngle + (t.endAngle - t.startAngle) / 2,
                        i = (t.outerRadius - t.innerRadius) / 2 + t.innerRadius;
                    return {
                        x: t.x + Math.cos(e) * i,
                        y: t.y + Math.sin(e) * i
                    }
                },
                draw: function() {
                    var t = this._chart.ctx,
                        e = this._view,
                        i = e.startAngle,
                        n = e.endAngle;
                    t.beginPath(), t.arc(e.x, e.y, e.outerRadius, i, n), t.arc(e.x, e.y, e.innerRadius, n, i, !0), t.closePath(), t.strokeStyle = e.borderColor, t.lineWidth = e.borderWidth, t.fillStyle = e.backgroundColor, t.fill(), t.lineJoin = "bevel", e.borderWidth && t.stroke()
                }
            })
        }, {
            25: 25,
            26: 26,
            45: 45
        }],
        37: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(26),
                r = t(45),
                o = n.global;
            n._set("global", {
                elements: {
                    line: {
                        tension: .4,
                        backgroundColor: o.defaultColor,
                        borderWidth: 3,
                        borderColor: o.defaultColor,
                        borderCapStyle: "butt",
                        borderDash: [],
                        borderDashOffset: 0,
                        borderJoinStyle: "miter",
                        capBezierPoints: !0,
                        fill: !0
                    }
                }
            }), e.exports = a.extend({
                draw: function() {
                    var t, e, i, n, a = this._view,
                        s = this._chart.ctx,
                        l = a.spanGaps,
                        u = this._children.slice(),
                        d = o.elements.line,
                        h = -1;
                    for (this._loop && u.length && u.push(u[0]), s.save(), s.lineCap = a.borderCapStyle || d.borderCapStyle, s.setLineDash && s.setLineDash(a.borderDash || d.borderDash), s.lineDashOffset = a.borderDashOffset || d.borderDashOffset, s.lineJoin = a.borderJoinStyle || d.borderJoinStyle, s.lineWidth = a.borderWidth || d.borderWidth, s.strokeStyle = a.borderColor || o.defaultColor, s.beginPath(), h = -1, t = 0; t < u.length; ++t) e = u[t], i = r.previousItem(u, t), n = e._view, 0 === t ? n.skip || (s.moveTo(n.x, n.y), h = t) : (i = -1 === h ? i : u[h], n.skip || (h !== t - 1 && !l || -1 === h ? s.moveTo(n.x, n.y) : r.canvas.lineTo(s, i._view, e._view), h = t));
                    s.stroke(), s.restore()
                }
            })
        }, {
            25: 25,
            26: 26,
            45: 45
        }],
        38: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(26),
                r = t(45),
                o = n.global.defaultColor;

            function s(t) {
                var e = this._view;
                return !!e && Math.abs(t - e.x) < e.radius + e.hitRadius
            }
            n._set("global", {
                elements: {
                    point: {
                        radius: 3,
                        pointStyle: "circle",
                        backgroundColor: o,
                        borderColor: o,
                        borderWidth: 1,
                        hitRadius: 1,
                        hoverRadius: 4,
                        hoverBorderWidth: 1
                    }
                }
            }), e.exports = a.extend({
                inRange: function(t, e) {
                    var i = this._view;
                    return !!i && Math.pow(t - i.x, 2) + Math.pow(e - i.y, 2) < Math.pow(i.hitRadius + i.radius, 2)
                },
                inLabelRange: s,
                inXRange: s,
                inYRange: function(t) {
                    var e = this._view;
                    return !!e && Math.abs(t - e.y) < e.radius + e.hitRadius
                },
                getCenterPoint: function() {
                    var t = this._view;
                    return {
                        x: t.x,
                        y: t.y
                    }
                },
                getArea: function() {
                    return Math.PI * Math.pow(this._view.radius, 2)
                },
                tooltipPosition: function() {
                    var t = this._view;
                    return {
                        x: t.x,
                        y: t.y,
                        padding: t.radius + t.borderWidth
                    }
                },
                draw: function(t) {
                    var e = this._view,
                        i = this._model,
                        a = this._chart.ctx,
                        s = e.pointStyle,
                        l = e.radius,
                        u = e.x,
                        d = e.y,
                        h = r.color,
                        c = 0;
                    e.skip || (a.strokeStyle = e.borderColor || o, a.lineWidth = r.valueOrDefault(e.borderWidth, n.global.elements.point.borderWidth), a.fillStyle = e.backgroundColor || o, void 0 !== t && (i.x < t.left || 1.01 * t.right < i.x || i.y < t.top || 1.01 * t.bottom < i.y) && (i.x < t.left ? c = (u - i.x) / (t.left - i.x) : 1.01 * t.right < i.x ? c = (i.x - u) / (i.x - t.right) : i.y < t.top ? c = (d - i.y) / (t.top - i.y) : 1.01 * t.bottom < i.y && (c = (i.y - d) / (i.y - t.bottom)), c = Math.round(100 * c) / 100, a.strokeStyle = h(a.strokeStyle).alpha(c).rgbString(), a.fillStyle = h(a.fillStyle).alpha(c).rgbString()), r.canvas.drawPoint(a, s, l, u, d))
                }
            })
        }, {
            25: 25,
            26: 26,
            45: 45
        }],
        39: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(26);

            function r(t) {
                return void 0 !== t._view.width
            }

            function o(t) {
                var e, i, n, a, o = t._view;
                if (r(t)) {
                    var s = o.width / 2;
                    e = o.x - s, i = o.x + s, n = Math.min(o.y, o.base), a = Math.max(o.y, o.base)
                } else {
                    var l = o.height / 2;
                    e = Math.min(o.x, o.base), i = Math.max(o.x, o.base), n = o.y - l, a = o.y + l
                }
                return {
                    left: e,
                    top: n,
                    right: i,
                    bottom: a
                }
            }
            n._set("global", {
                elements: {
                    rectangle: {
                        backgroundColor: n.global.defaultColor,
                        borderColor: n.global.defaultColor,
                        borderSkipped: "bottom",
                        borderWidth: 0
                    }
                }
            }), e.exports = a.extend({
                draw: function() {
                    var t, e, i, n, a, r, o, s = this._chart.ctx,
                        l = this._view,
                        u = l.borderWidth;
                    if (l.horizontal ? (t = l.base, e = l.x, i = l.y - l.height / 2, n = l.y + l.height / 2, a = e > t ? 1 : -1, r = 1, o = l.borderSkipped || "left") : (t = l.x - l.width / 2, e = l.x + l.width / 2, i = l.y, a = 1, r = (n = l.base) > i ? 1 : -1, o = l.borderSkipped || "bottom"), u) {
                        var d = Math.min(Math.abs(t - e), Math.abs(i - n)),
                            h = (u = u > d ? d : u) / 2,
                            c = t + ("left" !== o ? h * a : 0),
                            f = e + ("right" !== o ? -h * a : 0),
                            g = i + ("top" !== o ? h * r : 0),
                            m = n + ("bottom" !== o ? -h * r : 0);
                        c !== f && (i = g, n = m), g !== m && (t = c, e = f)
                    }
                    s.beginPath(), s.fillStyle = l.backgroundColor, s.strokeStyle = l.borderColor, s.lineWidth = u;
                    var p = [
                            [t, n],
                            [t, i],
                            [e, i],
                            [e, n]
                        ],
                        v = ["bottom", "left", "top", "right"].indexOf(o, 0);

                    function y(t) {
                        return p[(v + t) % 4]
                    } - 1 === v && (v = 0);
                    var b = y(0);
                    s.moveTo(b[0], b[1]);
                    for (var x = 1; x < 4; x++) b = y(x), s.lineTo(b[0], b[1]);
                    s.fill(), u && s.stroke()
                },
                height: function() {
                    var t = this._view;
                    return t.base - t.y
                },
                inRange: function(t, e) {
                    var i = !1;
                    if (this._view) {
                        var n = o(this);
                        i = t >= n.left && t <= n.right && e >= n.top && e <= n.bottom
                    }
                    return i
                },
                inLabelRange: function(t, e) {
                    if (!this._view) return !1;
                    var i = o(this);
                    return r(this) ? t >= i.left && t <= i.right : e >= i.top && e <= i.bottom
                },
                inXRange: function(t) {
                    var e = o(this);
                    return t >= e.left && t <= e.right
                },
                inYRange: function(t) {
                    var e = o(this);
                    return t >= e.top && t <= e.bottom
                },
                getCenterPoint: function() {
                    var t, e, i = this._view;
                    return r(this) ? (t = i.x, e = (i.y + i.base) / 2) : (t = (i.x + i.base) / 2, e = i.y), {
                        x: t,
                        y: e
                    }
                },
                getArea: function() {
                    var t = this._view;
                    return t.width * Math.abs(t.y - t.base)
                },
                tooltipPosition: function() {
                    var t = this._view;
                    return {
                        x: t.x,
                        y: t.y
                    }
                }
            })
        }, {
            25: 25,
            26: 26
        }],
        40: [function(t, e, i) {
            "use strict";
            e.exports = {}, e.exports.Arc = t(36), e.exports.Line = t(37), e.exports.Point = t(38), e.exports.Rectangle = t(39)
        }, {
            36: 36,
            37: 37,
            38: 38,
            39: 39
        }],
        41: [function(t, e, i) {
            "use strict";
            var n = t(42);
            i = e.exports = {
                clear: function(t) {
                    t.ctx.clearRect(0, 0, t.width, t.height)
                },
                roundedRect: function(t, e, i, n, a, r) {
                    if (r) {
                        var o = Math.min(r, n / 2),
                            s = Math.min(r, a / 2);
                        t.moveTo(e + o, i), t.lineTo(e + n - o, i), t.quadraticCurveTo(e + n, i, e + n, i + s), t.lineTo(e + n, i + a - s), t.quadraticCurveTo(e + n, i + a, e + n - o, i + a), t.lineTo(e + o, i + a), t.quadraticCurveTo(e, i + a, e, i + a - s), t.lineTo(e, i + s), t.quadraticCurveTo(e, i, e + o, i)
                    } else t.rect(e, i, n, a)
                },
                drawPoint: function(t, e, i, n, a) {
                    var r, o, s, l, u, d;
                    if (!e || "object" != typeof e || "[object HTMLImageElement]" !== (r = e.toString()) && "[object HTMLCanvasElement]" !== r) {
                        if (!(isNaN(i) || i <= 0)) {
                            switch (e) {
                                default: t.beginPath(),
                                t.arc(n, a, i, 0, 2 * Math.PI),
                                t.closePath(),
                                t.fill();
                                break;
                                case "triangle":
                                        t.beginPath(),
                                    u = (o = 3 * i / Math.sqrt(3)) * Math.sqrt(3) / 2,
                                    t.moveTo(n - o / 2, a + u / 3),
                                    t.lineTo(n + o / 2, a + u / 3),
                                    t.lineTo(n, a - 2 * u / 3),
                                    t.closePath(),
                                    t.fill();
                                    break;
                                case "rect":
                                        d = 1 / Math.SQRT2 * i,
                                    t.beginPath(),
                                    t.fillRect(n - d, a - d, 2 * d, 2 * d),
                                    t.strokeRect(n - d, a - d, 2 * d, 2 * d);
                                    break;
                                case "rectRounded":
                                        var h = i / Math.SQRT2,
                                        c = n - h,
                                        f = a - h,
                                        g = Math.SQRT2 * i;t.beginPath(),
                                    this.roundedRect(t, c, f, g, g, i / 2),
                                    t.closePath(),
                                    t.fill();
                                    break;
                                case "rectRot":
                                        d = 1 / Math.SQRT2 * i,
                                    t.beginPath(),
                                    t.moveTo(n - d, a),
                                    t.lineTo(n, a + d),
                                    t.lineTo(n + d, a),
                                    t.lineTo(n, a - d),
                                    t.closePath(),
                                    t.fill();
                                    break;
                                case "cross":
                                        t.beginPath(),
                                    t.moveTo(n, a + i),
                                    t.lineTo(n, a - i),
                                    t.moveTo(n - i, a),
                                    t.lineTo(n + i, a),
                                    t.closePath();
                                    break;
                                case "crossRot":
                                        t.beginPath(),
                                    s = Math.cos(Math.PI / 4) * i,
                                    l = Math.sin(Math.PI / 4) * i,
                                    t.moveTo(n - s, a - l),
                                    t.lineTo(n + s, a + l),
                                    t.moveTo(n - s, a + l),
                                    t.lineTo(n + s, a - l),
                                    t.closePath();
                                    break;
                                case "star":
                                        t.beginPath(),
                                    t.moveTo(n, a + i),
                                    t.lineTo(n, a - i),
                                    t.moveTo(n - i, a),
                                    t.lineTo(n + i, a),
                                    s = Math.cos(Math.PI / 4) * i,
                                    l = Math.sin(Math.PI / 4) * i,
                                    t.moveTo(n - s, a - l),
                                    t.lineTo(n + s, a + l),
                                    t.moveTo(n - s, a + l),
                                    t.lineTo(n + s, a - l),
                                    t.closePath();
                                    break;
                                case "line":
                                        t.beginPath(),
                                    t.moveTo(n - i, a),
                                    t.lineTo(n + i, a),
                                    t.closePath();
                                    break;
                                case "dash":
                                        t.beginPath(),
                                    t.moveTo(n, a),
                                    t.lineTo(n + i, a),
                                    t.closePath()
                            }
                            t.stroke()
                        }
                    } else t.drawImage(e, n - e.width / 2, a - e.height / 2, e.width, e.height)
                },
                clipArea: function(t, e) {
                    t.save(), t.beginPath(), t.rect(e.left, e.top, e.right - e.left, e.bottom - e.top), t.clip()
                },
                unclipArea: function(t) {
                    t.restore()
                },
                lineTo: function(t, e, i, n) {
                    if (i.steppedLine) return "after" === i.steppedLine && !n || "after" !== i.steppedLine && n ? t.lineTo(e.x, i.y) : t.lineTo(i.x, e.y), void t.lineTo(i.x, i.y);
                    i.tension ? t.bezierCurveTo(n ? e.controlPointPreviousX : e.controlPointNextX, n ? e.controlPointPreviousY : e.controlPointNextY, n ? i.controlPointNextX : i.controlPointPreviousX, n ? i.controlPointNextY : i.controlPointPreviousY, i.x, i.y) : t.lineTo(i.x, i.y)
                }
            };
            n.clear = i.clear, n.drawRoundedRectangle = function(t) {
                t.beginPath(), i.roundedRect.apply(i, arguments), t.closePath()
            }
        }, {
            42: 42
        }],
        42: [function(t, e, i) {
            "use strict";
            var n, a = {
                noop: function() {},
                uid: (n = 0, function() {
                    return n++
                }),
                isNullOrUndef: function(t) {
                    return null == t
                },
                isArray: Array.isArray ? Array.isArray : function(t) {
                    return "[object Array]" === Object.prototype.toString.call(t)
                },
                isObject: function(t) {
                    return null !== t && "[object Object]" === Object.prototype.toString.call(t)
                },
                valueOrDefault: function(t, e) {
                    return void 0 === t ? e : t
                },
                valueAtIndexOrDefault: function(t, e, i) {
                    return a.valueOrDefault(a.isArray(t) ? t[e] : t, i)
                },
                callback: function(t, e, i) {
                    if (t && "function" == typeof t.call) return t.apply(i, e)
                },
                each: function(t, e, i, n) {
                    var r, o, s;
                    if (a.isArray(t))
                        if (o = t.length, n)
                            for (r = o - 1; r >= 0; r--) e.call(i, t[r], r);
                        else
                            for (r = 0; r < o; r++) e.call(i, t[r], r);
                    else if (a.isObject(t))
                        for (o = (s = Object.keys(t)).length, r = 0; r < o; r++) e.call(i, t[s[r]], s[r])
                },
                arrayEquals: function(t, e) {
                    var i, n, r, o;
                    if (!t || !e || t.length !== e.length) return !1;
                    for (i = 0, n = t.length; i < n; ++i)
                        if (r = t[i], o = e[i], r instanceof Array && o instanceof Array) {
                            if (!a.arrayEquals(r, o)) return !1
                        } else if (r !== o) return !1;
                    return !0
                },
                clone: function(t) {
                    if (a.isArray(t)) return t.map(a.clone);
                    if (a.isObject(t)) {
                        for (var e = {}, i = Object.keys(t), n = i.length, r = 0; r < n; ++r) e[i[r]] = a.clone(t[i[r]]);
                        return e
                    }
                    return t
                },
                _merger: function(t, e, i, n) {
                    var r = e[t],
                        o = i[t];
                    a.isObject(r) && a.isObject(o) ? a.merge(r, o, n) : e[t] = a.clone(o)
                },
                _mergerIf: function(t, e, i) {
                    var n = e[t],
                        r = i[t];
                    a.isObject(n) && a.isObject(r) ? a.mergeIf(n, r) : e.hasOwnProperty(t) || (e[t] = a.clone(r))
                },
                merge: function(t, e, i) {
                    var n, r, o, s, l, u = a.isArray(e) ? e : [e],
                        d = u.length;
                    if (!a.isObject(t)) return t;
                    for (n = (i = i || {}).merger || a._merger, r = 0; r < d; ++r)
                        if (e = u[r], a.isObject(e))
                            for (l = 0, s = (o = Object.keys(e)).length; l < s; ++l) n(o[l], t, e, i);
                    return t
                },
                mergeIf: function(t, e) {
                    return a.merge(t, e, {
                        merger: a._mergerIf
                    })
                },
                extend: function(t) {
                    for (var e = function(e, i) {
                            t[i] = e
                        }, i = 1, n = arguments.length; i < n; ++i) a.each(arguments[i], e);
                    return t
                },
                inherits: function(t) {
                    var e = this,
                        i = t && t.hasOwnProperty("constructor") ? t.constructor : function() {
                            return e.apply(this, arguments)
                        },
                        n = function() {
                            this.constructor = i
                        };
                    return n.prototype = e.prototype, i.prototype = new n, i.extend = a.inherits, t && a.extend(i.prototype, t), i.__super__ = e.prototype, i
                }
            };
            e.exports = a, a.callCallback = a.callback, a.indexOf = function(t, e, i) {
                return Array.prototype.indexOf.call(t, e, i)
            }, a.getValueOrDefault = a.valueOrDefault, a.getValueAtIndexOrDefault = a.valueAtIndexOrDefault
        }, {}],
        43: [function(t, e, i) {
            "use strict";
            var n = t(42),
                a = {
                    linear: function(t) {
                        return t
                    },
                    easeInQuad: function(t) {
                        return t * t
                    },
                    easeOutQuad: function(t) {
                        return -t * (t - 2)
                    },
                    easeInOutQuad: function(t) {
                        return (t /= .5) < 1 ? .5 * t * t : -.5 * (--t * (t - 2) - 1)
                    },
                    easeInCubic: function(t) {
                        return t * t * t
                    },
                    easeOutCubic: function(t) {
                        return (t -= 1) * t * t + 1
                    },
                    easeInOutCubic: function(t) {
                        return (t /= .5) < 1 ? .5 * t * t * t : .5 * ((t -= 2) * t * t + 2)
                    },
                    easeInQuart: function(t) {
                        return t * t * t * t
                    },
                    easeOutQuart: function(t) {
                        return -((t -= 1) * t * t * t - 1)
                    },
                    easeInOutQuart: function(t) {
                        return (t /= .5) < 1 ? .5 * t * t * t * t : -.5 * ((t -= 2) * t * t * t - 2)
                    },
                    easeInQuint: function(t) {
                        return t * t * t * t * t
                    },
                    easeOutQuint: function(t) {
                        return (t -= 1) * t * t * t * t + 1
                    },
                    easeInOutQuint: function(t) {
                        return (t /= .5) < 1 ? .5 * t * t * t * t * t : .5 * ((t -= 2) * t * t * t * t + 2)
                    },
                    easeInSine: function(t) {
                        return 1 - Math.cos(t * (Math.PI / 2))
                    },
                    easeOutSine: function(t) {
                        return Math.sin(t * (Math.PI / 2))
                    },
                    easeInOutSine: function(t) {
                        return -.5 * (Math.cos(Math.PI * t) - 1)
                    },
                    easeInExpo: function(t) {
                        return 0 === t ? 0 : Math.pow(2, 10 * (t - 1))
                    },
                    easeOutExpo: function(t) {
                        return 1 === t ? 1 : 1 - Math.pow(2, -10 * t)
                    },
                    easeInOutExpo: function(t) {
                        return 0 === t ? 0 : 1 === t ? 1 : (t /= .5) < 1 ? .5 * Math.pow(2, 10 * (t - 1)) : .5 * (2 - Math.pow(2, -10 * --t))
                    },
                    easeInCirc: function(t) {
                        return t >= 1 ? t : -(Math.sqrt(1 - t * t) - 1)
                    },
                    easeOutCirc: function(t) {
                        return Math.sqrt(1 - (t -= 1) * t)
                    },
                    easeInOutCirc: function(t) {
                        return (t /= .5) < 1 ? -.5 * (Math.sqrt(1 - t * t) - 1) : .5 * (Math.sqrt(1 - (t -= 2) * t) + 1)
                    },
                    easeInElastic: function(t) {
                        var e = 1.70158,
                            i = 0,
                            n = 1;
                        return 0 === t ? 0 : 1 === t ? 1 : (i || (i = .3), n < 1 ? (n = 1, e = i / 4) : e = i / (2 * Math.PI) * Math.asin(1 / n), -n * Math.pow(2, 10 * (t -= 1)) * Math.sin((t - e) * (2 * Math.PI) / i))
                    },
                    easeOutElastic: function(t) {
                        var e = 1.70158,
                            i = 0,
                            n = 1;
                        return 0 === t ? 0 : 1 === t ? 1 : (i || (i = .3), n < 1 ? (n = 1, e = i / 4) : e = i / (2 * Math.PI) * Math.asin(1 / n), n * Math.pow(2, -10 * t) * Math.sin((t - e) * (2 * Math.PI) / i) + 1)
                    },
                    easeInOutElastic: function(t) {
                        var e = 1.70158,
                            i = 0,
                            n = 1;
                        return 0 === t ? 0 : 2 == (t /= .5) ? 1 : (i || (i = .45), n < 1 ? (n = 1, e = i / 4) : e = i / (2 * Math.PI) * Math.asin(1 / n), t < 1 ? n * Math.pow(2, 10 * (t -= 1)) * Math.sin((t - e) * (2 * Math.PI) / i) * -.5 : n * Math.pow(2, -10 * (t -= 1)) * Math.sin((t - e) * (2 * Math.PI) / i) * .5 + 1)
                    },
                    easeInBack: function(t) {
                        return t * t * (2.70158 * t - 1.70158)
                    },
                    easeOutBack: function(t) {
                        return (t -= 1) * t * (2.70158 * t + 1.70158) + 1
                    },
                    easeInOutBack: function(t) {
                        var e = 1.70158;
                        return (t /= .5) < 1 ? t * t * ((1 + (e *= 1.525)) * t - e) * .5 : .5 * ((t -= 2) * t * ((1 + (e *= 1.525)) * t + e) + 2)
                    },
                    easeInBounce: function(t) {
                        return 1 - a.easeOutBounce(1 - t)
                    },
                    easeOutBounce: function(t) {
                        return t < 1 / 2.75 ? 7.5625 * t * t : t < 2 / 2.75 ? 7.5625 * (t -= 1.5 / 2.75) * t + .75 : t < 2.5 / 2.75 ? 7.5625 * (t -= 2.25 / 2.75) * t + .9375 : 7.5625 * (t -= 2.625 / 2.75) * t + .984375
                    },
                    easeInOutBounce: function(t) {
                        return t < .5 ? .5 * a.easeInBounce(2 * t) : .5 * a.easeOutBounce(2 * t - 1) + .5
                    }
                };
            e.exports = {
                effects: a
            }, n.easingEffects = a
        }, {
            42: 42
        }],
        44: [function(t, e, i) {
            "use strict";
            var n = t(42);
            e.exports = {
                toLineHeight: function(t, e) {
                    var i = ("" + t).match(/^(normal|(\d+(?:\.\d+)?)(px|em|%)?)$/);
                    if (!i || "normal" === i[1]) return 1.2 * e;
                    switch (t = +i[2], i[3]) {
                        case "px":
                            return t;
                        case "%":
                            t /= 100
                    }
                    return e * t
                },
                toPadding: function(t) {
                    var e, i, a, r;
                    return n.isObject(t) ? (e = +t.top || 0, i = +t.right || 0, a = +t.bottom || 0, r = +t.left || 0) : e = i = a = r = +t || 0, {
                        top: e,
                        right: i,
                        bottom: a,
                        left: r,
                        height: e + a,
                        width: r + i
                    }
                },
                resolve: function(t, e, i) {
                    var a, r, o;
                    for (a = 0, r = t.length; a < r; ++a)
                        if (void 0 !== (o = t[a]) && (void 0 !== e && "function" == typeof o && (o = o(e)), void 0 !== i && n.isArray(o) && (o = o[i]), void 0 !== o)) return o
                }
            }
        }, {
            42: 42
        }],
        45: [function(t, e, i) {
            "use strict";
            e.exports = t(42), e.exports.easing = t(43), e.exports.canvas = t(41), e.exports.options = t(44)
        }, {
            41: 41,
            42: 42,
            43: 43,
            44: 44
        }],
        46: [function(t, e, i) {
            e.exports = {
                acquireContext: function(t) {
                    return t && t.canvas && (t = t.canvas), t && t.getContext("2d") || null
                }
            }
        }, {}],
        47: [function(t, e, i) {
            "use strict";
            var n = t(45),
                a = "$chartjs",
                r = "chartjs-",
                o = r + "render-monitor",
                s = r + "render-animation",
                l = ["animationstart", "webkitAnimationStart"],
                u = {
                    touchstart: "mousedown",
                    touchmove: "mousemove",
                    touchend: "mouseup",
                    pointerenter: "mouseenter",
                    pointerdown: "mousedown",
                    pointermove: "mousemove",
                    pointerup: "mouseup",
                    pointerleave: "mouseout",
                    pointerout: "mouseout"
                };

            function d(t, e) {
                var i = n.getStyle(t, e),
                    a = i && i.match(/^(\d+)(\.\d+)?px$/);
                return a ? Number(a[1]) : void 0
            }
            var h = !! function() {
                var t = !1;
                try {
                    var e = Object.defineProperty({}, "passive", {
                        get: function() {
                            t = !0
                        }
                    });
                    window.addEventListener("e", null, e)
                } catch (t) {}
                return t
            }() && {
                passive: !0
            };

            function c(t, e, i) {
                t.addEventListener(e, i, h)
            }

            function f(t, e, i) {
                t.removeEventListener(e, i, h)
            }

            function g(t, e, i, n, a) {
                return {
                    type: t,
                    chart: e,
                    native: a || null,
                    x: void 0 !== i ? i : null,
                    y: void 0 !== n ? n : null
                }
            }

            function m(t, e, i) {
                var u, d, h, f, m, p, v, y, b = t[a] || (t[a] = {}),
                    x = b.resizer = function(t) {
                        var e = document.createElement("div"),
                            i = r + "size-monitor",
                            n = "position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;";
                        e.style.cssText = n, e.className = i, e.innerHTML = '<div class="' + i + '-expand" style="' + n + '"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="' + i + '-shrink" style="' + n + '"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div>';
                        var a = e.childNodes[0],
                            o = e.childNodes[1];
                        e._reset = function() {
                            a.scrollLeft = 1e6, a.scrollTop = 1e6, o.scrollLeft = 1e6, o.scrollTop = 1e6
                        };
                        var s = function() {
                            e._reset(), t()
                        };
                        return c(a, "scroll", s.bind(a, "expand")), c(o, "scroll", s.bind(o, "shrink")), e
                    }((u = function() {
                        if (b.resizer) return e(g("resize", i))
                    }, h = !1, f = [], function() {
                        f = Array.prototype.slice.call(arguments), d = d || this, h || (h = !0, n.requestAnimFrame.call(window, function() {
                            h = !1, u.apply(d, f)
                        }))
                    }));
                p = function() {
                    if (b.resizer) {
                        var e = t.parentNode;
                        e && e !== x.parentNode && e.insertBefore(x, e.firstChild), x._reset()
                    }
                }, v = (m = t)[a] || (m[a] = {}), y = v.renderProxy = function(t) {
                    t.animationName === s && p()
                }, n.each(l, function(t) {
                    c(m, t, y)
                }), v.reflow = !!m.offsetParent, m.classList.add(o)
            }

            function p(t) {
                var e, i, r, s = t[a] || {},
                    u = s.resizer;
                delete s.resizer, i = (e = t)[a] || {}, (r = i.renderProxy) && (n.each(l, function(t) {
                    f(e, t, r)
                }), delete i.renderProxy), e.classList.remove(o), u && u.parentNode && u.parentNode.removeChild(u)
            }
            e.exports = {
                _enabled: "undefined" != typeof window && "undefined" != typeof document,
                initialize: function() {
                    var t, e, i, n = "from{opacity:0.99}to{opacity:1}";
                    e = "@-webkit-keyframes " + s + "{" + n + "}@keyframes " + s + "{" + n + "}." + o + "{-webkit-animation:" + s + " 0.001s;animation:" + s + " 0.001s;}", i = (t = this)._style || document.createElement("style"), t._style || (t._style = i, e = "/* Chart.js */\n" + e, i.setAttribute("type", "text/css"), document.getElementsByTagName("head")[0].appendChild(i)), i.appendChild(document.createTextNode(e))
                },
                acquireContext: function(t, e) {
                    "string" == typeof t ? t = document.getElementById(t) : t.length && (t = t[0]), t && t.canvas && (t = t.canvas);
                    var i = t && t.getContext && t.getContext("2d");
                    return i && i.canvas === t ? (function(t, e) {
                        var i = t.style,
                            n = t.getAttribute("height"),
                            r = t.getAttribute("width");
                        if (t[a] = {
                                initial: {
                                    height: n,
                                    width: r,
                                    style: {
                                        display: i.display,
                                        height: i.height,
                                        width: i.width
                                    }
                                }
                            }, i.display = i.display || "block", null === r || "" === r) {
                            var o = d(t, "width");
                            void 0 !== o && (t.width = o)
                        }
                        if (null === n || "" === n)
                            if ("" === t.style.height) t.height = t.width / (e.options.aspectRatio || 2);
                            else {
                                var s = d(t, "height");
                                void 0 !== o && (t.height = s)
                            }
                    }(t, e), i) : null
                },
                releaseContext: function(t) {
                    var e = t.canvas;
                    if (e[a]) {
                        var i = e[a].initial;
                        ["height", "width"].forEach(function(t) {
                            var a = i[t];
                            n.isNullOrUndef(a) ? e.removeAttribute(t) : e.setAttribute(t, a)
                        }), n.each(i.style || {}, function(t, i) {
                            e.style[i] = t
                        }), e.width = e.width, delete e[a]
                    }
                },
                addEventListener: function(t, e, i) {
                    var r = t.canvas;
                    if ("resize" !== e) {
                        var o = i[a] || (i[a] = {});
                        c(r, e, (o.proxies || (o.proxies = {}))[t.id + "_" + e] = function(e) {
                            var a, r, o, s;
                            i((r = t, o = u[(a = e).type] || a.type, s = n.getRelativePosition(a, r), g(o, r, s.x, s.y, a)))
                        })
                    } else m(r, i, t)
                },
                removeEventListener: function(t, e, i) {
                    var n = t.canvas;
                    if ("resize" !== e) {
                        var r = ((i[a] || {}).proxies || {})[t.id + "_" + e];
                        r && f(n, e, r)
                    } else p(n)
                }
            }, n.addEvent = c, n.removeEvent = f
        }, {
            45: 45
        }],
        48: [function(t, e, i) {
            "use strict";
            var n = t(45),
                a = t(46),
                r = t(47),
                o = r._enabled ? r : a;
            e.exports = n.extend({
                initialize: function() {},
                acquireContext: function() {},
                releaseContext: function() {},
                addEventListener: function() {},
                removeEventListener: function() {}
            }, o)
        }, {
            45: 45,
            46: 46,
            47: 47
        }],
        49: [function(t, e, i) {
            "use strict";
            e.exports = {}, e.exports.filler = t(50), e.exports.legend = t(51), e.exports.title = t(52)
        }, {
            50: 50,
            51: 51,
            52: 52
        }],
        50: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(40),
                r = t(45);
            n._set("global", {
                plugins: {
                    filler: {
                        propagate: !0
                    }
                }
            });
            var o = {
                dataset: function(t) {
                    var e = t.fill,
                        i = t.chart,
                        n = i.getDatasetMeta(e),
                        a = n && i.isDatasetVisible(e) && n.dataset._children || [],
                        r = a.length || 0;
                    return r ? function(t, e) {
                        return e < r && a[e]._view || null
                    } : null
                },
                boundary: function(t) {
                    var e = t.boundary,
                        i = e ? e.x : null,
                        n = e ? e.y : null;
                    return function(t) {
                        return {
                            x: null === i ? t.x : i,
                            y: null === n ? t.y : n
                        }
                    }
                }
            };

            function s(t, e, i) {
                var n, a = t._model || {},
                    r = a.fill;
                if (void 0 === r && (r = !!a.backgroundColor), !1 === r || null === r) return !1;
                if (!0 === r) return "origin";
                if (n = parseFloat(r, 10), isFinite(n) && Math.floor(n) === n) return "-" !== r[0] && "+" !== r[0] || (n = e + n), !(n === e || n < 0 || n >= i) && n;
                switch (r) {
                    case "bottom":
                        return "start";
                    case "top":
                        return "end";
                    case "zero":
                        return "origin";
                    case "origin":
                    case "start":
                    case "end":
                        return r;
                    default:
                        return !1
                }
            }

            function l(t) {
                var e, i = t.el._model || {},
                    n = t.el._scale || {},
                    a = t.fill,
                    r = null;
                if (isFinite(a)) return null;
                if ("start" === a ? r = void 0 === i.scaleBottom ? n.bottom : i.scaleBottom : "end" === a ? r = void 0 === i.scaleTop ? n.top : i.scaleTop : void 0 !== i.scaleZero ? r = i.scaleZero : n.getBasePosition ? r = n.getBasePosition() : n.getBasePixel && (r = n.getBasePixel()), null != r) {
                    if (void 0 !== r.x && void 0 !== r.y) return r;
                    if ("number" == typeof r && isFinite(r)) return {
                        x: (e = n.isHorizontal()) ? r : null,
                        y: e ? null : r
                    }
                }
                return null
            }

            function u(t, e, i) {
                var n, a = t[e].fill,
                    r = [e];
                if (!i) return a;
                for (; !1 !== a && -1 === r.indexOf(a);) {
                    if (!isFinite(a)) return a;
                    if (!(n = t[a])) return !1;
                    if (n.visible) return a;
                    r.push(a), a = n.fill
                }
                return !1
            }

            function d(t) {
                return t && !t.skip
            }

            function h(t, e, i, n, a) {
                var o;
                if (n && a) {
                    for (t.moveTo(e[0].x, e[0].y), o = 1; o < n; ++o) r.canvas.lineTo(t, e[o - 1], e[o]);
                    for (t.lineTo(i[a - 1].x, i[a - 1].y), o = a - 1; o > 0; --o) r.canvas.lineTo(t, i[o], i[o - 1], !0)
                }
            }
            e.exports = {
                id: "filler",
                afterDatasetsUpdate: function(t, e) {
                    var i, n, r, d, h, c, f, g = (t.data.datasets || []).length,
                        m = e.propagate,
                        p = [];
                    for (n = 0; n < g; ++n) d = null, (r = (i = t.getDatasetMeta(n)).dataset) && r._model && r instanceof a.Line && (d = {
                        visible: t.isDatasetVisible(n),
                        fill: s(r, n, g),
                        chart: t,
                        el: r
                    }), i.$filler = d, p.push(d);
                    for (n = 0; n < g; ++n)(d = p[n]) && (d.fill = u(p, n, m), d.boundary = l(d), d.mapper = (void 0, f = void 0, c = (h = d).fill, f = "dataset", !1 === c ? null : (isFinite(c) || (f = "boundary"), o[f](h))))
                },
                beforeDatasetDraw: function(t, e) {
                    var i = e.meta.$filler;
                    if (i) {
                        var a = t.ctx,
                            o = i.el,
                            s = o._view,
                            l = o._children || [],
                            u = i.mapper,
                            c = s.backgroundColor || n.global.defaultColor;
                        u && c && l.length && (r.canvas.clipArea(a, t.chartArea), function(t, e, i, n, a, r) {
                            var o, s, l, u, c, f, g, m = e.length,
                                p = n.spanGaps,
                                v = [],
                                y = [],
                                b = 0,
                                x = 0;
                            for (t.beginPath(), o = 0, s = m + !!r; o < s; ++o) c = i(u = e[l = o % m]._view, l, n), f = d(u), g = d(c), f && g ? (b = v.push(u), x = y.push(c)) : b && x && (p ? (f && v.push(u), g && y.push(c)) : (h(t, v, y, b, x), b = x = 0, v = [], y = []));
                            h(t, v, y, b, x), t.closePath(), t.fillStyle = a, t.fill()
                        }(a, l, u, s, c, o._loop), r.canvas.unclipArea(a))
                    }
                }
            }
        }, {
            25: 25,
            40: 40,
            45: 45
        }],
        51: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(26),
                r = t(45),
                o = t(30),
                s = r.noop;

            function l(t, e) {
                return t.usePointStyle ? e * Math.SQRT2 : t.boxWidth
            }
            n._set("global", {
                legend: {
                    display: !0,
                    position: "top",
                    fullWidth: !0,
                    reverse: !1,
                    weight: 1e3,
                    onClick: function(t, e) {
                        var i = e.datasetIndex,
                            n = this.chart,
                            a = n.getDatasetMeta(i);
                        a.hidden = null === a.hidden ? !n.data.datasets[i].hidden : null, n.update()
                    },
                    onHover: null,
                    labels: {
                        boxWidth: 40,
                        padding: 10,
                        generateLabels: function(t) {
                            var e = t.data;
                            return r.isArray(e.datasets) ? e.datasets.map(function(e, i) {
                                return {
                                    text: e.label,
                                    fillStyle: r.isArray(e.backgroundColor) ? e.backgroundColor[0] : e.backgroundColor,
                                    hidden: !t.isDatasetVisible(i),
                                    lineCap: e.borderCapStyle,
                                    lineDash: e.borderDash,
                                    lineDashOffset: e.borderDashOffset,
                                    lineJoin: e.borderJoinStyle,
                                    lineWidth: e.borderWidth,
                                    strokeStyle: e.borderColor,
                                    pointStyle: e.pointStyle,
                                    datasetIndex: i
                                }
                            }, this) : []
                        }
                    }
                },
                legendCallback: function(t) {
                    var e = [];
                    e.push('<ul class="' + t.id + '-legend">');
                    for (var i = 0; i < t.data.datasets.length; i++) e.push('<li><span style="background-color:' + t.data.datasets[i].backgroundColor + '"></span>'), t.data.datasets[i].label && e.push(t.data.datasets[i].label), e.push("</li>");
                    return e.push("</ul>"), e.join("")
                }
            });
            var u = a.extend({
                initialize: function(t) {
                    r.extend(this, t), this.legendHitBoxes = [], this.doughnutMode = !1
                },
                beforeUpdate: s,
                update: function(t, e, i) {
                    var n = this;
                    return n.beforeUpdate(), n.maxWidth = t, n.maxHeight = e, n.margins = i, n.beforeSetDimensions(), n.setDimensions(), n.afterSetDimensions(), n.beforeBuildLabels(), n.buildLabels(), n.afterBuildLabels(), n.beforeFit(), n.fit(), n.afterFit(), n.afterUpdate(), n.minSize
                },
                afterUpdate: s,
                beforeSetDimensions: s,
                setDimensions: function() {
                    var t = this;
                    t.isHorizontal() ? (t.width = t.maxWidth, t.left = 0, t.right = t.width) : (t.height = t.maxHeight, t.top = 0, t.bottom = t.height), t.paddingLeft = 0, t.paddingTop = 0, t.paddingRight = 0, t.paddingBottom = 0, t.minSize = {
                        width: 0,
                        height: 0
                    }
                },
                afterSetDimensions: s,
                beforeBuildLabels: s,
                buildLabels: function() {
                    var t = this,
                        e = t.options.labels || {},
                        i = r.callback(e.generateLabels, [t.chart], t) || [];
                    e.filter && (i = i.filter(function(i) {
                        return e.filter(i, t.chart.data)
                    })), t.options.reverse && i.reverse(), t.legendItems = i
                },
                afterBuildLabels: s,
                beforeFit: s,
                fit: function() {
                    var t = this,
                        e = t.options,
                        i = e.labels,
                        a = e.display,
                        o = t.ctx,
                        s = n.global,
                        u = r.valueOrDefault,
                        d = u(i.fontSize, s.defaultFontSize),
                        h = u(i.fontStyle, s.defaultFontStyle),
                        c = u(i.fontFamily, s.defaultFontFamily),
                        f = r.fontString(d, h, c),
                        g = t.legendHitBoxes = [],
                        m = t.minSize,
                        p = t.isHorizontal();
                    if (p ? (m.width = t.maxWidth, m.height = a ? 10 : 0) : (m.width = a ? 10 : 0, m.height = t.maxHeight), a)
                        if (o.font = f, p) {
                            var v = t.lineWidths = [0],
                                y = t.legendItems.length ? d + i.padding : 0;
                            o.textAlign = "left", o.textBaseline = "top", r.each(t.legendItems, function(e, n) {
                                var a = l(i, d) + d / 2 + o.measureText(e.text).width;
                                v[v.length - 1] + a + i.padding >= t.width && (y += d + i.padding, v[v.length] = t.left), g[n] = {
                                    left: 0,
                                    top: 0,
                                    width: a,
                                    height: d
                                }, v[v.length - 1] += a + i.padding
                            }), m.height += y
                        } else {
                            var b = i.padding,
                                x = t.columnWidths = [],
                                _ = i.padding,
                                k = 0,
                                w = 0,
                                M = d + b;
                            r.each(t.legendItems, function(t, e) {
                                var n = l(i, d) + d / 2 + o.measureText(t.text).width;
                                w + M > m.height && (_ += k + i.padding, x.push(k), k = 0, w = 0), k = Math.max(k, n), w += M, g[e] = {
                                    left: 0,
                                    top: 0,
                                    width: n,
                                    height: d
                                }
                            }), _ += k, x.push(k), m.width += _
                        }
                    t.width = m.width, t.height = m.height
                },
                afterFit: s,
                isHorizontal: function() {
                    return "top" === this.options.position || "bottom" === this.options.position
                },
                draw: function() {
                    var t = this,
                        e = t.options,
                        i = e.labels,
                        a = n.global,
                        o = a.elements.line,
                        s = t.width,
                        u = t.lineWidths;
                    if (e.display) {
                        var d, h = t.ctx,
                            c = r.valueOrDefault,
                            f = c(i.fontColor, a.defaultFontColor),
                            g = c(i.fontSize, a.defaultFontSize),
                            m = c(i.fontStyle, a.defaultFontStyle),
                            p = c(i.fontFamily, a.defaultFontFamily),
                            v = r.fontString(g, m, p);
                        h.textAlign = "left", h.textBaseline = "middle", h.lineWidth = .5, h.strokeStyle = f, h.fillStyle = f, h.font = v;
                        var y = l(i, g),
                            b = t.legendHitBoxes,
                            x = t.isHorizontal();
                        d = x ? {
                            x: t.left + (s - u[0]) / 2,
                            y: t.top + i.padding,
                            line: 0
                        } : {
                            x: t.left + i.padding,
                            y: t.top + i.padding,
                            line: 0
                        };
                        var _ = g + i.padding;
                        r.each(t.legendItems, function(n, l) {
                            var f, m, p, v, k, w = h.measureText(n.text).width,
                                M = y + g / 2 + w,
                                S = d.x,
                                D = d.y;
                            x ? S + M >= s && (D = d.y += _, d.line++, S = d.x = t.left + (s - u[d.line]) / 2) : D + _ > t.bottom && (S = d.x = S + t.columnWidths[d.line] + i.padding, D = d.y = t.top + i.padding, d.line++),
                                function(t, i, n) {
                                    if (!(isNaN(y) || y <= 0)) {
                                        h.save(), h.fillStyle = c(n.fillStyle, a.defaultColor), h.lineCap = c(n.lineCap, o.borderCapStyle), h.lineDashOffset = c(n.lineDashOffset, o.borderDashOffset), h.lineJoin = c(n.lineJoin, o.borderJoinStyle), h.lineWidth = c(n.lineWidth, o.borderWidth), h.strokeStyle = c(n.strokeStyle, a.defaultColor);
                                        var s = 0 === c(n.lineWidth, o.borderWidth);
                                        if (h.setLineDash && h.setLineDash(c(n.lineDash, o.borderDash)), e.labels && e.labels.usePointStyle) {
                                            var l = g * Math.SQRT2 / 2,
                                                u = l / Math.SQRT2,
                                                d = t + u,
                                                f = i + u;
                                            r.canvas.drawPoint(h, n.pointStyle, l, d, f)
                                        } else s || h.strokeRect(t, i, y, g), h.fillRect(t, i, y, g);
                                        h.restore()
                                    }
                                }(S, D, n), b[l].left = S, b[l].top = D, f = n, m = w, v = y + (p = g / 2) + S, k = D + p, h.fillText(f.text, v, k), f.hidden && (h.beginPath(), h.lineWidth = 2, h.moveTo(v, k), h.lineTo(v + m, k), h.stroke()), x ? d.x += M + i.padding : d.y += _
                        })
                    }
                },
                handleEvent: function(t) {
                    var e = this,
                        i = e.options,
                        n = "mouseup" === t.type ? "click" : t.type,
                        a = !1;
                    if ("mousemove" === n) {
                        if (!i.onHover) return
                    } else {
                        if ("click" !== n) return;
                        if (!i.onClick) return
                    }
                    var r = t.x,
                        o = t.y;
                    if (r >= e.left && r <= e.right && o >= e.top && o <= e.bottom)
                        for (var s = e.legendHitBoxes, l = 0; l < s.length; ++l) {
                            var u = s[l];
                            if (r >= u.left && r <= u.left + u.width && o >= u.top && o <= u.top + u.height) {
                                if ("click" === n) {
                                    i.onClick.call(e, t.native, e.legendItems[l]), a = !0;
                                    break
                                }
                                if ("mousemove" === n) {
                                    i.onHover.call(e, t.native, e.legendItems[l]), a = !0;
                                    break
                                }
                            }
                        }
                    return a
                }
            });

            function d(t, e) {
                var i = new u({
                    ctx: t.ctx,
                    options: e,
                    chart: t
                });
                o.configure(t, i, e), o.addBox(t, i), t.legend = i
            }
            e.exports = {
                id: "legend",
                _element: u,
                beforeInit: function(t) {
                    var e = t.options.legend;
                    e && d(t, e)
                },
                beforeUpdate: function(t) {
                    var e = t.options.legend,
                        i = t.legend;
                    e ? (r.mergeIf(e, n.global.legend), i ? (o.configure(t, i, e), i.options = e) : d(t, e)) : i && (o.removeBox(t, i), delete t.legend)
                },
                afterEvent: function(t, e) {
                    var i = t.legend;
                    i && i.handleEvent(e)
                }
            }
        }, {
            25: 25,
            26: 26,
            30: 30,
            45: 45
        }],
        52: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(26),
                r = t(45),
                o = t(30),
                s = r.noop;
            n._set("global", {
                title: {
                    display: !1,
                    fontStyle: "bold",
                    fullWidth: !0,
                    lineHeight: 1.2,
                    padding: 10,
                    position: "top",
                    text: "",
                    weight: 2e3
                }
            });
            var l = a.extend({
                initialize: function(t) {
                    r.extend(this, t), this.legendHitBoxes = []
                },
                beforeUpdate: s,
                update: function(t, e, i) {
                    var n = this;
                    return n.beforeUpdate(), n.maxWidth = t, n.maxHeight = e, n.margins = i, n.beforeSetDimensions(), n.setDimensions(), n.afterSetDimensions(), n.beforeBuildLabels(), n.buildLabels(), n.afterBuildLabels(), n.beforeFit(), n.fit(), n.afterFit(), n.afterUpdate(), n.minSize
                },
                afterUpdate: s,
                beforeSetDimensions: s,
                setDimensions: function() {
                    var t = this;
                    t.isHorizontal() ? (t.width = t.maxWidth, t.left = 0, t.right = t.width) : (t.height = t.maxHeight, t.top = 0, t.bottom = t.height), t.paddingLeft = 0, t.paddingTop = 0, t.paddingRight = 0, t.paddingBottom = 0, t.minSize = {
                        width: 0,
                        height: 0
                    }
                },
                afterSetDimensions: s,
                beforeBuildLabels: s,
                buildLabels: s,
                afterBuildLabels: s,
                beforeFit: s,
                fit: function() {
                    var t = r.valueOrDefault,
                        e = this.options,
                        i = e.display,
                        a = t(e.fontSize, n.global.defaultFontSize),
                        o = this.minSize,
                        s = r.isArray(e.text) ? e.text.length : 1,
                        l = r.options.toLineHeight(e.lineHeight, a),
                        u = i ? s * l + 2 * e.padding : 0;
                    this.isHorizontal() ? (o.width = this.maxWidth, o.height = u) : (o.width = u, o.height = this.maxHeight), this.width = o.width, this.height = o.height
                },
                afterFit: s,
                isHorizontal: function() {
                    var t = this.options.position;
                    return "top" === t || "bottom" === t
                },
                draw: function() {
                    var t = this.ctx,
                        e = r.valueOrDefault,
                        i = this.options,
                        a = n.global;
                    if (i.display) {
                        var o, s, l, u = e(i.fontSize, a.defaultFontSize),
                            d = e(i.fontStyle, a.defaultFontStyle),
                            h = e(i.fontFamily, a.defaultFontFamily),
                            c = r.fontString(u, d, h),
                            f = r.options.toLineHeight(i.lineHeight, u),
                            g = f / 2 + i.padding,
                            m = 0,
                            p = this.top,
                            v = this.left,
                            y = this.bottom,
                            b = this.right;
                        t.fillStyle = e(i.fontColor, a.defaultFontColor), t.font = c, this.isHorizontal() ? (s = v + (b - v) / 2, l = p + g, o = b - v) : (s = "left" === i.position ? v + g : b - g, l = p + (y - p) / 2, o = y - p, m = Math.PI * ("left" === i.position ? -.5 : .5)), t.save(), t.translate(s, l), t.rotate(m), t.textAlign = "center", t.textBaseline = "middle";
                        var x = i.text;
                        if (r.isArray(x))
                            for (var _ = 0, k = 0; k < x.length; ++k) t.fillText(x[k], 0, _, o), _ += f;
                        else t.fillText(x, 0, 0, o);
                        t.restore()
                    }
                }
            });

            function u(t, e) {
                var i = new l({
                    ctx: t.ctx,
                    options: e,
                    chart: t
                });
                o.configure(t, i, e), o.addBox(t, i), t.titleBlock = i
            }
            e.exports = {
                id: "title",
                _element: l,
                beforeInit: function(t) {
                    var e = t.options.title;
                    e && u(t, e)
                },
                beforeUpdate: function(t) {
                    var e = t.options.title,
                        i = t.titleBlock;
                    e ? (r.mergeIf(e, n.global.title), i ? (o.configure(t, i, e), i.options = e) : u(t, e)) : i && (o.removeBox(t, i), delete t.titleBlock)
                }
            }
        }, {
            25: 25,
            26: 26,
            30: 30,
            45: 45
        }],
        53: [function(t, e, i) {
            "use strict";
            e.exports = function(t) {
                var e = t.Scale.extend({
                    getLabels: function() {
                        var t = this.chart.data;
                        return this.options.labels || (this.isHorizontal() ? t.xLabels : t.yLabels) || t.labels
                    },
                    determineDataLimits: function() {
                        var t, e = this,
                            i = e.getLabels();
                        e.minIndex = 0, e.maxIndex = i.length - 1, void 0 !== e.options.ticks.min && (t = i.indexOf(e.options.ticks.min), e.minIndex = -1 !== t ? t : e.minIndex), void 0 !== e.options.ticks.max && (t = i.indexOf(e.options.ticks.max), e.maxIndex = -1 !== t ? t : e.maxIndex), e.min = i[e.minIndex], e.max = i[e.maxIndex]
                    },
                    buildTicks: function() {
                        var t = this.getLabels();
                        this.ticks = 0 === this.minIndex && this.maxIndex === t.length - 1 ? t : t.slice(this.minIndex, this.maxIndex + 1)
                    },
                    getLabelForIndex: function(t, e) {
                        var i = this.chart.data,
                            n = this.isHorizontal();
                        return i.yLabels && !n ? this.getRightValue(i.datasets[e].data[t]) : this.ticks[t - this.minIndex]
                    },
                    getPixelForValue: function(t, e) {
                        var i, n = this,
                            a = n.options.offset,
                            r = Math.max(n.maxIndex + 1 - n.minIndex - (a ? 0 : 1), 1);
                        if (null != t && (i = n.isHorizontal() ? t.x : t.y), void 0 !== i || void 0 !== t && isNaN(e)) {
                            t = i || t;
                            var o = n.getLabels().indexOf(t);
                            e = -1 !== o ? o : e
                        }
                        if (n.isHorizontal()) {
                            var s = n.width / r,
                                l = s * (e - n.minIndex);
                            return a && (l += s / 2), n.left + Math.round(l)
                        }
                        var u = n.height / r,
                            d = u * (e - n.minIndex);
                        return a && (d += u / 2), n.top + Math.round(d)
                    },
                    getPixelForTick: function(t) {
                        return this.getPixelForValue(this.ticks[t], t + this.minIndex, null)
                    },
                    getValueForPixel: function(t) {
                        var e = this.options.offset,
                            i = Math.max(this._ticks.length - (e ? 0 : 1), 1),
                            n = this.isHorizontal(),
                            a = (n ? this.width : this.height) / i;
                        return t -= n ? this.left : this.top, e && (t -= a / 2), (t <= 0 ? 0 : Math.round(t / a)) + this.minIndex
                    },
                    getBasePixel: function() {
                        return this.bottom
                    }
                });
                t.scaleService.registerScaleType("category", e, {
                    position: "bottom"
                })
            }
        }, {}],
        54: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(45),
                r = t(34);
            e.exports = function(t) {
                var e = {
                        position: "left",
                        ticks: {
                            callback: r.formatters.linear
                        }
                    },
                    i = t.LinearScaleBase.extend({
                        determineDataLimits: function() {
                            var t = this,
                                e = t.options,
                                i = t.chart,
                                n = i.data.datasets,
                                r = t.isHorizontal();

                            function o(e) {
                                return r ? e.xAxisID === t.id : e.yAxisID === t.id
                            }
                            t.min = null, t.max = null;
                            var s = e.stacked;
                            if (void 0 === s && a.each(n, function(t, e) {
                                    if (!s) {
                                        var n = i.getDatasetMeta(e);
                                        i.isDatasetVisible(e) && o(n) && void 0 !== n.stack && (s = !0)
                                    }
                                }), e.stacked || s) {
                                var l = {};
                                a.each(n, function(n, r) {
                                    var s = i.getDatasetMeta(r),
                                        u = [s.type, void 0 === e.stacked && void 0 === s.stack ? r : "", s.stack].join(".");
                                    void 0 === l[u] && (l[u] = {
                                        positiveValues: [],
                                        negativeValues: []
                                    });
                                    var d = l[u].positiveValues,
                                        h = l[u].negativeValues;
                                    i.isDatasetVisible(r) && o(s) && a.each(n.data, function(i, n) {
                                        var a = +t.getRightValue(i);
                                        isNaN(a) || s.data[n].hidden || (d[n] = d[n] || 0, h[n] = h[n] || 0, e.relativePoints ? d[n] = 100 : a < 0 ? h[n] += a : d[n] += a)
                                    })
                                }), a.each(l, function(e) {
                                    var i = e.positiveValues.concat(e.negativeValues),
                                        n = a.min(i),
                                        r = a.max(i);
                                    t.min = null === t.min ? n : Math.min(t.min, n), t.max = null === t.max ? r : Math.max(t.max, r)
                                })
                            } else a.each(n, function(e, n) {
                                var r = i.getDatasetMeta(n);
                                i.isDatasetVisible(n) && o(r) && a.each(e.data, function(e, i) {
                                    var n = +t.getRightValue(e);
                                    isNaN(n) || r.data[i].hidden || (null === t.min ? t.min = n : n < t.min && (t.min = n), null === t.max ? t.max = n : n > t.max && (t.max = n))
                                })
                            });
                            t.min = isFinite(t.min) && !isNaN(t.min) ? t.min : 0, t.max = isFinite(t.max) && !isNaN(t.max) ? t.max : 1, this.handleTickRangeOptions()
                        },
                        getTickLimit: function() {
                            var t, e = this.options.ticks;
                            if (this.isHorizontal()) t = Math.min(e.maxTicksLimit ? e.maxTicksLimit : 11, Math.ceil(this.width / 50));
                            else {
                                var i = a.valueOrDefault(e.fontSize, n.global.defaultFontSize);
                                t = Math.min(e.maxTicksLimit ? e.maxTicksLimit : 11, Math.ceil(this.height / (2 * i)))
                            }
                            return t
                        },
                        handleDirectionalChanges: function() {
                            this.isHorizontal() || this.ticks.reverse()
                        },
                        getLabelForIndex: function(t, e) {
                            return +this.getRightValue(this.chart.data.datasets[e].data[t])
                        },
                        getPixelForValue: function(t) {
                            var e = this.start,
                                i = +this.getRightValue(t),
                                n = this.end - e;
                            return this.isHorizontal() ? this.left + this.width / n * (i - e) : this.bottom - this.height / n * (i - e)
                        },
                        getValueForPixel: function(t) {
                            var e = this.isHorizontal(),
                                i = e ? this.width : this.height,
                                n = (e ? t - this.left : this.bottom - t) / i;
                            return this.start + (this.end - this.start) * n
                        },
                        getPixelForTick: function(t) {
                            return this.getPixelForValue(this.ticksAsNumbers[t])
                        }
                    });
                t.scaleService.registerScaleType("linear", i, e)
            }
        }, {
            25: 25,
            34: 34,
            45: 45
        }],
        55: [function(t, e, i) {
            "use strict";
            var n = t(45);
            e.exports = function(t) {
                var e = n.noop;
                t.LinearScaleBase = t.Scale.extend({
                    getRightValue: function(e) {
                        return "string" == typeof e ? +e : t.Scale.prototype.getRightValue.call(this, e)
                    },
                    handleTickRangeOptions: function() {
                        var t = this,
                            e = t.options.ticks;
                        if (e.beginAtZero) {
                            var i = n.sign(t.min),
                                a = n.sign(t.max);
                            i < 0 && a < 0 ? t.max = 0 : i > 0 && a > 0 && (t.min = 0)
                        }
                        var r = void 0 !== e.min || void 0 !== e.suggestedMin,
                            o = void 0 !== e.max || void 0 !== e.suggestedMax;
                        void 0 !== e.min ? t.min = e.min : void 0 !== e.suggestedMin && (null === t.min ? t.min = e.suggestedMin : t.min = Math.min(t.min, e.suggestedMin)), void 0 !== e.max ? t.max = e.max : void 0 !== e.suggestedMax && (null === t.max ? t.max = e.suggestedMax : t.max = Math.max(t.max, e.suggestedMax)), r !== o && t.min >= t.max && (r ? t.max = t.min + 1 : t.min = t.max - 1), t.min === t.max && (t.max++, e.beginAtZero || t.min--)
                    },
                    getTickLimit: e,
                    handleDirectionalChanges: e,
                    buildTicks: function() {
                        var t = this,
                            e = t.options.ticks,
                            i = t.getTickLimit(),
                            a = {
                                maxTicks: i = Math.max(2, i),
                                min: e.min,
                                max: e.max,
                                stepSize: n.valueOrDefault(e.fixedStepSize, e.stepSize)
                            },
                            r = t.ticks = function(t, e) {
                                var i, a = [];
                                if (t.stepSize && t.stepSize > 0) i = t.stepSize;
                                else {
                                    var r = n.niceNum(e.max - e.min, !1);
                                    i = n.niceNum(r / (t.maxTicks - 1), !0)
                                }
                                var o = Math.floor(e.min / i) * i,
                                    s = Math.ceil(e.max / i) * i;
                                t.min && t.max && t.stepSize && n.almostWhole((t.max - t.min) / t.stepSize, i / 1e3) && (o = t.min, s = t.max);
                                var l = (s - o) / i;
                                l = n.almostEquals(l, Math.round(l), i / 1e3) ? Math.round(l) : Math.ceil(l);
                                var u = 1;
                                i < 1 && (u = Math.pow(10, i.toString().length - 2), o = Math.round(o * u) / u, s = Math.round(s * u) / u), a.push(void 0 !== t.min ? t.min : o);
                                for (var d = 1; d < l; ++d) a.push(Math.round((o + d * i) * u) / u);
                                return a.push(void 0 !== t.max ? t.max : s), a
                            }(a, t);
                        t.handleDirectionalChanges(), t.max = n.max(r), t.min = n.min(r), e.reverse ? (r.reverse(), t.start = t.max, t.end = t.min) : (t.start = t.min, t.end = t.max)
                    },
                    convertTicksToLabels: function() {
                        this.ticksAsNumbers = this.ticks.slice(), this.zeroLineIndex = this.ticks.indexOf(0), t.Scale.prototype.convertTicksToLabels.call(this)
                    }
                })
            }
        }, {
            45: 45
        }],
        56: [function(t, e, i) {
            "use strict";
            var n = t(45),
                a = t(34);
            e.exports = function(t) {
                var e = {
                        position: "left",
                        ticks: {
                            callback: a.formatters.logarithmic
                        }
                    },
                    i = t.Scale.extend({
                        determineDataLimits: function() {
                            var t = this,
                                e = t.options,
                                i = t.chart,
                                a = i.data.datasets,
                                r = t.isHorizontal();

                            function o(e) {
                                return r ? e.xAxisID === t.id : e.yAxisID === t.id
                            }
                            t.min = null, t.max = null, t.minNotZero = null;
                            var s = e.stacked;
                            if (void 0 === s && n.each(a, function(t, e) {
                                    if (!s) {
                                        var n = i.getDatasetMeta(e);
                                        i.isDatasetVisible(e) && o(n) && void 0 !== n.stack && (s = !0)
                                    }
                                }), e.stacked || s) {
                                var l = {};
                                n.each(a, function(a, r) {
                                    var s = i.getDatasetMeta(r),
                                        u = [s.type, void 0 === e.stacked && void 0 === s.stack ? r : "", s.stack].join(".");
                                    i.isDatasetVisible(r) && o(s) && (void 0 === l[u] && (l[u] = []), n.each(a.data, function(e, i) {
                                        var n = l[u],
                                            a = +t.getRightValue(e);
                                        isNaN(a) || s.data[i].hidden || a < 0 || (n[i] = n[i] || 0, n[i] += a)
                                    }))
                                }), n.each(l, function(e) {
                                    if (e.length > 0) {
                                        var i = n.min(e),
                                            a = n.max(e);
                                        t.min = null === t.min ? i : Math.min(t.min, i), t.max = null === t.max ? a : Math.max(t.max, a)
                                    }
                                })
                            } else n.each(a, function(e, a) {
                                var r = i.getDatasetMeta(a);
                                i.isDatasetVisible(a) && o(r) && n.each(e.data, function(e, i) {
                                    var n = +t.getRightValue(e);
                                    isNaN(n) || r.data[i].hidden || n < 0 || (null === t.min ? t.min = n : n < t.min && (t.min = n), null === t.max ? t.max = n : n > t.max && (t.max = n), 0 !== n && (null === t.minNotZero || n < t.minNotZero) && (t.minNotZero = n))
                                })
                            });
                            this.handleTickRangeOptions()
                        },
                        handleTickRangeOptions: function() {
                            var t = this,
                                e = t.options.ticks,
                                i = n.valueOrDefault;
                            t.min = i(e.min, t.min), t.max = i(e.max, t.max), t.min === t.max && (0 !== t.min && null !== t.min ? (t.min = Math.pow(10, Math.floor(n.log10(t.min)) - 1), t.max = Math.pow(10, Math.floor(n.log10(t.max)) + 1)) : (t.min = 1, t.max = 10)), null === t.min && (t.min = Math.pow(10, Math.floor(n.log10(t.max)) - 1)), null === t.max && (t.max = 0 !== t.min ? Math.pow(10, Math.floor(n.log10(t.min)) + 1) : 10), null === t.minNotZero && (t.min > 0 ? t.minNotZero = t.min : t.max < 1 ? t.minNotZero = Math.pow(10, Math.floor(n.log10(t.max))) : t.minNotZero = 1)
                        },
                        buildTicks: function() {
                            var t = this,
                                e = t.options.ticks,
                                i = !t.isHorizontal(),
                                a = {
                                    min: e.min,
                                    max: e.max
                                },
                                r = t.ticks = function(t, e) {
                                    var i, a, r = [],
                                        o = n.valueOrDefault,
                                        s = o(t.min, Math.pow(10, Math.floor(n.log10(e.min)))),
                                        l = Math.floor(n.log10(e.max)),
                                        u = Math.ceil(e.max / Math.pow(10, l));
                                    0 === s ? (i = Math.floor(n.log10(e.minNotZero)), a = Math.floor(e.minNotZero / Math.pow(10, i)), r.push(s), s = a * Math.pow(10, i)) : (i = Math.floor(n.log10(s)), a = Math.floor(s / Math.pow(10, i)));
                                    for (var d = i < 0 ? Math.pow(10, Math.abs(i)) : 1; r.push(s), 10 == ++a && (a = 1, d = ++i >= 0 ? 1 : d), s = Math.round(a * Math.pow(10, i) * d) / d, i < l || i === l && a < u;);
                                    var h = o(t.max, s);
                                    return r.push(h), r
                                }(a, t);
                            t.max = n.max(r), t.min = n.min(r), e.reverse ? (i = !i, t.start = t.max, t.end = t.min) : (t.start = t.min, t.end = t.max), i && r.reverse()
                        },
                        convertTicksToLabels: function() {
                            this.tickValues = this.ticks.slice(), t.Scale.prototype.convertTicksToLabels.call(this)
                        },
                        getLabelForIndex: function(t, e) {
                            return +this.getRightValue(this.chart.data.datasets[e].data[t])
                        },
                        getPixelForTick: function(t) {
                            return this.getPixelForValue(this.tickValues[t])
                        },
                        _getFirstTickValue: function(t) {
                            var e = Math.floor(n.log10(t));
                            return Math.floor(t / Math.pow(10, e)) * Math.pow(10, e)
                        },
                        getPixelForValue: function(e) {
                            var i, a, r, o, s, l = this,
                                u = l.options.ticks.reverse,
                                d = n.log10,
                                h = l._getFirstTickValue(l.minNotZero),
                                c = 0;
                            return e = +l.getRightValue(e), u ? (r = l.end, o = l.start, s = -1) : (r = l.start, o = l.end, s = 1), l.isHorizontal() ? (i = l.width, a = u ? l.right : l.left) : (i = l.height, s *= -1, a = u ? l.top : l.bottom), e !== r && (0 === r && (i -= c = n.getValueOrDefault(l.options.ticks.fontSize, t.defaults.global.defaultFontSize), r = h), 0 !== e && (c += i / (d(o) - d(r)) * (d(e) - d(r))), a += s * c), a
                        },
                        getValueForPixel: function(e) {
                            var i, a, r, o, s = this,
                                l = s.options.ticks.reverse,
                                u = n.log10,
                                d = s._getFirstTickValue(s.minNotZero);
                            if (l ? (a = s.end, r = s.start) : (a = s.start, r = s.end), s.isHorizontal() ? (i = s.width, o = l ? s.right - e : e - s.left) : (i = s.height, o = l ? e - s.top : s.bottom - e), o !== a) {
                                if (0 === a) {
                                    var h = n.getValueOrDefault(s.options.ticks.fontSize, t.defaults.global.defaultFontSize);
                                    o -= h, i -= h, a = d
                                }
                                o *= u(r) - u(a), o /= i, o = Math.pow(10, u(a) + o)
                            }
                            return o
                        }
                    });
                t.scaleService.registerScaleType("logarithmic", i, e)
            }
        }, {
            34: 34,
            45: 45
        }],
        57: [function(t, e, i) {
            "use strict";
            var n = t(25),
                a = t(45),
                r = t(34);
            e.exports = function(t) {
                var e = n.global,
                    i = {
                        display: !0,
                        animate: !0,
                        position: "chartArea",
                        angleLines: {
                            display: !0,
                            color: "rgba(0, 0, 0, 0.1)",
                            lineWidth: 1
                        },
                        gridLines: {
                            circular: !1
                        },
                        ticks: {
                            showLabelBackdrop: !0,
                            backdropColor: "rgba(255,255,255,0.75)",
                            backdropPaddingY: 2,
                            backdropPaddingX: 2,
                            callback: r.formatters.linear
                        },
                        pointLabels: {
                            display: !0,
                            fontSize: 10,
                            callback: function(t) {
                                return t
                            }
                        }
                    };

                function o(t) {
                    var e = t.options;
                    return e.angleLines.display || e.pointLabels.display ? t.chart.data.labels.length : 0
                }

                function s(t) {
                    var i = t.options.pointLabels,
                        n = a.valueOrDefault(i.fontSize, e.defaultFontSize),
                        r = a.valueOrDefault(i.fontStyle, e.defaultFontStyle),
                        o = a.valueOrDefault(i.fontFamily, e.defaultFontFamily);
                    return {
                        size: n,
                        style: r,
                        family: o,
                        font: a.fontString(n, r, o)
                    }
                }

                function l(t, e, i, n, a) {
                    return t === n || t === a ? {
                        start: e - i / 2,
                        end: e + i / 2
                    } : t < n || t > a ? {
                        start: e - i - 5,
                        end: e
                    } : {
                        start: e,
                        end: e + i + 5
                    }
                }

                function u(t, e, i, n) {
                    if (a.isArray(e))
                        for (var r = i.y, o = 1.5 * n, s = 0; s < e.length; ++s) t.fillText(e[s], i.x, r), r += o;
                    else t.fillText(e, i.x, i.y)
                }

                function d(t) {
                    return a.isNumber(t) ? t : 0
                }
                var h = t.LinearScaleBase.extend({
                    setDimensions: function() {
                        var t = this,
                            i = t.options,
                            n = i.ticks;
                        t.width = t.maxWidth, t.height = t.maxHeight, t.xCenter = Math.round(t.width / 2), t.yCenter = Math.round(t.height / 2);
                        var r = a.min([t.height, t.width]),
                            o = a.valueOrDefault(n.fontSize, e.defaultFontSize);
                        t.drawingArea = i.display ? r / 2 - (o / 2 + n.backdropPaddingY) : r / 2
                    },
                    determineDataLimits: function() {
                        var t = this,
                            e = t.chart,
                            i = Number.POSITIVE_INFINITY,
                            n = Number.NEGATIVE_INFINITY;
                        a.each(e.data.datasets, function(r, o) {
                            if (e.isDatasetVisible(o)) {
                                var s = e.getDatasetMeta(o);
                                a.each(r.data, function(e, a) {
                                    var r = +t.getRightValue(e);
                                    isNaN(r) || s.data[a].hidden || (i = Math.min(r, i), n = Math.max(r, n))
                                })
                            }
                        }), t.min = i === Number.POSITIVE_INFINITY ? 0 : i, t.max = n === Number.NEGATIVE_INFINITY ? 0 : n, t.handleTickRangeOptions()
                    },
                    getTickLimit: function() {
                        var t = this.options.ticks,
                            i = a.valueOrDefault(t.fontSize, e.defaultFontSize);
                        return Math.min(t.maxTicksLimit ? t.maxTicksLimit : 11, Math.ceil(this.drawingArea / (1.5 * i)))
                    },
                    convertTicksToLabels: function() {
                        t.LinearScaleBase.prototype.convertTicksToLabels.call(this), this.pointLabels = this.chart.data.labels.map(this.options.pointLabels.callback, this)
                    },
                    getLabelForIndex: function(t, e) {
                        return +this.getRightValue(this.chart.data.datasets[e].data[t])
                    },
                    fit: function() {
                        var t, e;
                        this.options.pointLabels.display ? function(t) {
                            var e, i, n, r = s(t),
                                u = Math.min(t.height / 2, t.width / 2),
                                d = {
                                    r: t.width,
                                    l: 0,
                                    t: t.height,
                                    b: 0
                                },
                                h = {};
                            t.ctx.font = r.font, t._pointLabelSizes = [];
                            var c, f, g, m = o(t);
                            for (e = 0; e < m; e++) {
                                n = t.getPointPosition(e, u), c = t.ctx, f = r.size, g = t.pointLabels[e] || "", i = a.isArray(g) ? {
                                    w: a.longestText(c, c.font, g),
                                    h: g.length * f + 1.5 * (g.length - 1) * f
                                } : {
                                    w: c.measureText(g).width,
                                    h: f
                                }, t._pointLabelSizes[e] = i;
                                var p = t.getIndexAngle(e),
                                    v = a.toDegrees(p) % 360,
                                    y = l(v, n.x, i.w, 0, 180),
                                    b = l(v, n.y, i.h, 90, 270);
                                y.start < d.l && (d.l = y.start, h.l = p), y.end > d.r && (d.r = y.end, h.r = p), b.start < d.t && (d.t = b.start, h.t = p), b.end > d.b && (d.b = b.end, h.b = p)
                            }
                            t.setReductions(u, d, h)
                        }(this) : (t = this, e = Math.min(t.height / 2, t.width / 2), t.drawingArea = Math.round(e), t.setCenterPoint(0, 0, 0, 0))
                    },
                    setReductions: function(t, e, i) {
                        var n = e.l / Math.sin(i.l),
                            a = Math.max(e.r - this.width, 0) / Math.sin(i.r),
                            r = -e.t / Math.cos(i.t),
                            o = -Math.max(e.b - this.height, 0) / Math.cos(i.b);
                        n = d(n), a = d(a), r = d(r), o = d(o), this.drawingArea = Math.min(Math.round(t - (n + a) / 2), Math.round(t - (r + o) / 2)), this.setCenterPoint(n, a, r, o)
                    },
                    setCenterPoint: function(t, e, i, n) {
                        var a = this,
                            r = a.width - e - a.drawingArea,
                            o = t + a.drawingArea,
                            s = i + a.drawingArea,
                            l = a.height - n - a.drawingArea;
                        a.xCenter = Math.round((o + r) / 2 + a.left), a.yCenter = Math.round((s + l) / 2 + a.top)
                    },
                    getIndexAngle: function(t) {
                        return t * (2 * Math.PI / o(this)) + (this.chart.options && this.chart.options.startAngle ? this.chart.options.startAngle : 0) * Math.PI * 2 / 360
                    },
                    getDistanceFromCenterForValue: function(t) {
                        if (null === t) return 0;
                        var e = this.drawingArea / (this.max - this.min);
                        return this.options.ticks.reverse ? (this.max - t) * e : (t - this.min) * e
                    },
                    getPointPosition: function(t, e) {
                        var i = this.getIndexAngle(t) - Math.PI / 2;
                        return {
                            x: Math.round(Math.cos(i) * e) + this.xCenter,
                            y: Math.round(Math.sin(i) * e) + this.yCenter
                        }
                    },
                    getPointPositionForValue: function(t, e) {
                        return this.getPointPosition(t, this.getDistanceFromCenterForValue(e))
                    },
                    getBasePosition: function() {
                        var t = this.min,
                            e = this.max;
                        return this.getPointPositionForValue(0, this.beginAtZero ? 0 : t < 0 && e < 0 ? e : t > 0 && e > 0 ? t : 0)
                    },
                    draw: function() {
                        var t = this,
                            i = t.options,
                            n = i.gridLines,
                            r = i.ticks,
                            l = a.valueOrDefault;
                        if (i.display) {
                            var d = t.ctx,
                                h = this.getIndexAngle(0),
                                c = l(r.fontSize, e.defaultFontSize),
                                f = l(r.fontStyle, e.defaultFontStyle),
                                g = l(r.fontFamily, e.defaultFontFamily),
                                m = a.fontString(c, f, g);
                            a.each(t.ticks, function(i, s) {
                                if (s > 0 || r.reverse) {
                                    var u = t.getDistanceFromCenterForValue(t.ticksAsNumbers[s]);
                                    if (n.display && 0 !== s && function(t, e, i, n) {
                                            var r = t.ctx;
                                            if (r.strokeStyle = a.valueAtIndexOrDefault(e.color, n - 1), r.lineWidth = a.valueAtIndexOrDefault(e.lineWidth, n - 1), t.options.gridLines.circular) r.beginPath(), r.arc(t.xCenter, t.yCenter, i, 0, 2 * Math.PI), r.closePath(), r.stroke();
                                            else {
                                                var s = o(t);
                                                if (0 === s) return;
                                                r.beginPath();
                                                var l = t.getPointPosition(0, i);
                                                r.moveTo(l.x, l.y);
                                                for (var u = 1; u < s; u++) l = t.getPointPosition(u, i), r.lineTo(l.x, l.y);
                                                r.closePath(), r.stroke()
                                            }
                                        }(t, n, u, s), r.display) {
                                        var f = l(r.fontColor, e.defaultFontColor);
                                        if (d.font = m, d.save(), d.translate(t.xCenter, t.yCenter), d.rotate(h), r.showLabelBackdrop) {
                                            var g = d.measureText(i).width;
                                            d.fillStyle = r.backdropColor, d.fillRect(-g / 2 - r.backdropPaddingX, -u - c / 2 - r.backdropPaddingY, g + 2 * r.backdropPaddingX, c + 2 * r.backdropPaddingY)
                                        }
                                        d.textAlign = "center", d.textBaseline = "middle", d.fillStyle = f, d.fillText(i, 0, -u), d.restore()
                                    }
                                }
                            }), (i.angleLines.display || i.pointLabels.display) && function(t) {
                                var i = t.ctx,
                                    n = t.options,
                                    r = n.angleLines,
                                    l = n.pointLabels;
                                i.lineWidth = r.lineWidth, i.strokeStyle = r.color;
                                var d, h, c, f, g = t.getDistanceFromCenterForValue(n.ticks.reverse ? t.min : t.max),
                                    m = s(t);
                                i.textBaseline = "top";
                                for (var p = o(t) - 1; p >= 0; p--) {
                                    if (r.display) {
                                        var v = t.getPointPosition(p, g);
                                        i.beginPath(), i.moveTo(t.xCenter, t.yCenter), i.lineTo(v.x, v.y), i.stroke(), i.closePath()
                                    }
                                    if (l.display) {
                                        var y = t.getPointPosition(p, g + 5),
                                            b = a.valueAtIndexOrDefault(l.fontColor, p, e.defaultFontColor);
                                        i.font = m.font, i.fillStyle = b;
                                        var x = t.getIndexAngle(p),
                                            _ = a.toDegrees(x);
                                        i.textAlign = 0 === (f = _) || 180 === f ? "center" : f < 180 ? "left" : "right", d = _, h = t._pointLabelSizes[p], c = y, 90 === d || 270 === d ? c.y -= h.h / 2 : (d > 270 || d < 90) && (c.y -= h.h), u(i, t.pointLabels[p] || "", y, m.size)
                                    }
                                }
                            }(t)
                        }
                    }
                });
                t.scaleService.registerScaleType("radialLinear", h, i)
            }
        }, {
            25: 25,
            34: 34,
            45: 45
        }],
        58: [function(t, e, i) {
            "use strict";
            var n = t(6);
            n = "function" == typeof n ? n : window.moment;
            var a = t(25),
                r = t(45),
                o = Number.MIN_SAFE_INTEGER || -9007199254740991,
                s = Number.MAX_SAFE_INTEGER || 9007199254740991,
                l = {
                    millisecond: {
                        common: !0,
                        size: 1,
                        steps: [1, 2, 5, 10, 20, 50, 100, 250, 500]
                    },
                    second: {
                        common: !0,
                        size: 1e3,
                        steps: [1, 2, 5, 10, 30]
                    },
                    minute: {
                        common: !0,
                        size: 6e4,
                        steps: [1, 2, 5, 10, 30]
                    },
                    hour: {
                        common: !0,
                        size: 36e5,
                        steps: [1, 2, 3, 6, 12]
                    },
                    day: {
                        common: !0,
                        size: 864e5,
                        steps: [1, 2, 5]
                    },
                    week: {
                        common: !1,
                        size: 6048e5,
                        steps: [1, 2, 3, 4]
                    },
                    month: {
                        common: !0,
                        size: 2628e6,
                        steps: [1, 2, 3]
                    },
                    quarter: {
                        common: !1,
                        size: 7884e6,
                        steps: [1, 2, 3, 4]
                    },
                    year: {
                        common: !0,
                        size: 3154e7
                    }
                },
                u = Object.keys(l);

            function d(t, e) {
                return t - e
            }

            function h(t) {
                var e, i, n, a = {},
                    r = [];
                for (e = 0, i = t.length; e < i; ++e) a[n = t[e]] || (a[n] = !0, r.push(n));
                return r
            }

            function c(t, e, i, n) {
                var a = function(t, e, i) {
                        for (var n, a, r, o = 0, s = t.length - 1; o >= 0 && o <= s;) {
                            if (a = t[(n = o + s >> 1) - 1] || null, r = t[n], !a) return {
                                lo: null,
                                hi: r
                            };
                            if (r[e] < i) o = n + 1;
                            else {
                                if (!(a[e] > i)) return {
                                    lo: a,
                                    hi: r
                                };
                                s = n - 1
                            }
                        }
                        return {
                            lo: r,
                            hi: null
                        }
                    }(t, e, i),
                    r = a.lo ? a.hi ? a.lo : t[t.length - 2] : t[0],
                    o = a.lo ? a.hi ? a.hi : t[t.length - 1] : t[1],
                    s = o[e] - r[e],
                    l = s ? (i - r[e]) / s : 0,
                    u = (o[n] - r[n]) * l;
                return r[n] + u
            }

            function f(t, e) {
                var i = e.parser,
                    a = e.parser || e.format;
                return "function" == typeof i ? i(t) : "string" == typeof t && "string" == typeof a ? n(t, a) : (t instanceof n || (t = n(t)), t.isValid() ? t : "function" == typeof a ? a(t) : t)
            }

            function g(t, e) {
                if (r.isNullOrUndef(t)) return null;
                var i = e.options.time,
                    n = f(e.getRightValue(t), i);
                return n.isValid() ? (i.round && n.startOf(i.round), n.valueOf()) : null
            }

            function m(t) {
                for (var e = u.indexOf(t) + 1, i = u.length; e < i; ++e)
                    if (l[u[e]].common) return u[e]
            }

            function p(t, e, i, a) {
                var o, d = a.time,
                    h = d.unit || function(t, e, i, n) {
                        var a, r, o, d = u.length;
                        for (a = u.indexOf(t); a < d - 1; ++a)
                            if (o = (r = l[u[a]]).steps ? r.steps[r.steps.length - 1] : s, r.common && Math.ceil((i - e) / (o * r.size)) <= n) return u[a];
                        return u[d - 1]
                    }(d.minUnit, t, e, i),
                    c = m(h),
                    f = r.valueOrDefault(d.stepSize, d.unitStepSize),
                    g = "week" === h && d.isoWeekday,
                    p = a.ticks.major.enabled,
                    v = l[h],
                    y = n(t),
                    b = n(e),
                    x = [];
                for (f || (f = function(t, e, i, n) {
                        var a, r, o, s = e - t,
                            u = l[i],
                            d = u.size,
                            h = u.steps;
                        if (!h) return Math.ceil(s / (n * d));
                        for (a = 0, r = h.length; a < r && (o = h[a], !(Math.ceil(s / (d * o)) <= n)); ++a);
                        return o
                    }(t, e, h, i)), g && (y = y.isoWeekday(g), b = b.isoWeekday(g)), y = y.startOf(g ? "day" : h), (b = b.startOf(g ? "day" : h)) < e && b.add(1, h), o = n(y), p && c && !g && !d.round && (o.startOf(c), o.add(~~((y - o) / (v.size * f)) * f, h)); o < b; o.add(f, h)) x.push(+o);
                return x.push(+o), x
            }
            e.exports = function(t) {
                var e = t.Scale.extend({
                    initialize: function() {
                        if (!n) throw new Error("Chart.js - Moment.js could not be found! You must include it before Chart.js to use the time scale. Download at https://momentjs.com");
                        this.mergeTicksOptions(), t.Scale.prototype.initialize.call(this)
                    },
                    update: function() {
                        var e = this.options;
                        return e.time && e.time.format && console.warn("options.time.format is deprecated and replaced by options.time.parser."), t.Scale.prototype.update.apply(this, arguments)
                    },
                    getRightValue: function(e) {
                        return e && void 0 !== e.t && (e = e.t), t.Scale.prototype.getRightValue.call(this, e)
                    },
                    determineDataLimits: function() {
                        var t, e, i, a, l, u, c = this,
                            f = c.chart,
                            m = c.options.time,
                            p = m.unit || "day",
                            v = s,
                            y = o,
                            b = [],
                            x = [],
                            _ = [];
                        for (t = 0, i = f.data.labels.length; t < i; ++t) _.push(g(f.data.labels[t], c));
                        for (t = 0, i = (f.data.datasets || []).length; t < i; ++t)
                            if (f.isDatasetVisible(t))
                                if (l = f.data.datasets[t].data, r.isObject(l[0]))
                                    for (x[t] = [], e = 0, a = l.length; e < a; ++e) u = g(l[e], c), b.push(u), x[t][e] = u;
                                else b.push.apply(b, _), x[t] = _.slice(0);
                        else x[t] = [];
                        _.length && (_ = h(_).sort(d), v = Math.min(v, _[0]), y = Math.max(y, _[_.length - 1])), b.length && (b = h(b).sort(d), v = Math.min(v, b[0]), y = Math.max(y, b[b.length - 1])), v = g(m.min, c) || v, y = g(m.max, c) || y, v = v === s ? +n().startOf(p) : v, y = y === o ? +n().endOf(p) + 1 : y, c.min = Math.min(v, y), c.max = Math.max(v + 1, y), c._horizontal = c.isHorizontal(), c._table = [], c._timestamps = {
                            data: b,
                            datasets: x,
                            labels: _
                        }
                    },
                    buildTicks: function() {
                        var t, e, i, a, r, o, s, d, h, v, y, b, x = this,
                            _ = x.min,
                            k = x.max,
                            w = x.options,
                            M = w.time,
                            S = [],
                            D = [];
                        switch (w.ticks.source) {
                            case "data":
                                S = x._timestamps.data;
                                break;
                            case "labels":
                                S = x._timestamps.labels;
                                break;
                            case "auto":
                            default:
                                S = p(_, k, x.getLabelCapacity(_), w)
                        }
                        for ("ticks" === w.bounds && S.length && (_ = S[0], k = S[S.length - 1]), _ = g(M.min, x) || _, k = g(M.max, x) || k, t = 0, e = S.length; t < e; ++t)(i = S[t]) >= _ && i <= k && D.push(i);
                        return x.min = _, x.max = k, x._unit = M.unit || function(t, e, i, a) {
                                var r, o, s = n.duration(n(a).diff(n(i)));
                                for (r = u.length - 1; r >= u.indexOf(e); r--)
                                    if (o = u[r], l[o].common && s.as(o) >= t.length) return o;
                                return u[e ? u.indexOf(e) : 0]
                            }(D, M.minUnit, x.min, x.max), x._majorUnit = m(x._unit), x._table = function(t, e, i, n) {
                                if ("linear" === n || !t.length) return [{
                                    time: e,
                                    pos: 0
                                }, {
                                    time: i,
                                    pos: 1
                                }];
                                var a, r, o, s, l, u = [],
                                    d = [e];
                                for (a = 0, r = t.length; a < r; ++a)(s = t[a]) > e && s < i && d.push(s);
                                for (d.push(i), a = 0, r = d.length; a < r; ++a) l = d[a + 1], o = d[a - 1], s = d[a], void 0 !== o && void 0 !== l && Math.round((l + o) / 2) === s || u.push({
                                    time: s,
                                    pos: a / (r - 1)
                                });
                                return u
                            }(x._timestamps.data, _, k, w.distribution), x._offsets = (a = x._table, r = D, o = _, s = k, y = 0, b = 0, (d = w).offset && r.length && (d.time.min || (h = r.length > 1 ? r[1] : s, v = r[0], y = (c(a, "time", h, "pos") - c(a, "time", v, "pos")) / 2), d.time.max || (h = r[r.length - 1], v = r.length > 1 ? r[r.length - 2] : o, b = (c(a, "time", h, "pos") - c(a, "time", v, "pos")) / 2)), {
                                left: y,
                                right: b
                            }), x._labelFormat = function(t, e) {
                                var i, n, a, r = t.length;
                                for (i = 0; i < r; i++) {
                                    if (0 !== (n = f(t[i], e)).millisecond()) return "MMM D, YYYY h:mm:ss.SSS a";
                                    0 === n.second() && 0 === n.minute() && 0 === n.hour() || (a = !0)
                                }
                                return a ? "MMM D, YYYY h:mm:ss a" : "MMM D, YYYY"
                            }(x._timestamps.data, M),
                            function(t, e) {
                                var i, a, r, o, s = [];
                                for (i = 0, a = t.length; i < a; ++i) r = t[i], o = !!e && r === +n(r).startOf(e), s.push({
                                    value: r,
                                    major: o
                                });
                                return s
                            }(D, x._majorUnit)
                    },
                    getLabelForIndex: function(t, e) {
                        var i = this.chart.data,
                            n = this.options.time,
                            a = i.labels && t < i.labels.length ? i.labels[t] : "",
                            o = i.datasets[e].data[t];
                        return r.isObject(o) && (a = this.getRightValue(o)), n.tooltipFormat ? f(a, n).format(n.tooltipFormat) : "string" == typeof a ? a : f(a, n).format(this._labelFormat)
                    },
                    tickFormatFunction: function(t, e, i, n) {
                        var a = this.options,
                            o = t.valueOf(),
                            s = a.time.displayFormats,
                            l = s[this._unit],
                            u = this._majorUnit,
                            d = s[u],
                            h = t.clone().startOf(u).valueOf(),
                            c = a.ticks.major,
                            f = c.enabled && u && d && o === h,
                            g = t.format(n || (f ? d : l)),
                            m = f ? c : a.ticks.minor,
                            p = r.valueOrDefault(m.callback, m.userCallback);
                        return p ? p(g, e, i) : g
                    },
                    convertTicksToLabels: function(t) {
                        var e, i, a = [];
                        for (e = 0, i = t.length; e < i; ++e) a.push(this.tickFormatFunction(n(t[e].value), e, t));
                        return a
                    },
                    getPixelForOffset: function(t) {
                        var e = this,
                            i = e._horizontal ? e.width : e.height,
                            n = e._horizontal ? e.left : e.top,
                            a = c(e._table, "time", t, "pos");
                        return n + i * (e._offsets.left + a) / (e._offsets.left + 1 + e._offsets.right)
                    },
                    getPixelForValue: function(t, e, i) {
                        var n = null;
                        if (void 0 !== e && void 0 !== i && (n = this._timestamps.datasets[i][e]), null === n && (n = g(t, this)), null !== n) return this.getPixelForOffset(n)
                    },
                    getPixelForTick: function(t) {
                        var e = this.getTicks();
                        return t >= 0 && t < e.length ? this.getPixelForOffset(e[t].value) : null
                    },
                    getValueForPixel: function(t) {
                        var e = this,
                            i = e._horizontal ? e.width : e.height,
                            a = e._horizontal ? e.left : e.top,
                            r = (i ? (t - a) / i : 0) * (e._offsets.left + 1 + e._offsets.left) - e._offsets.right,
                            o = c(e._table, "pos", r, "time");
                        return n(o)
                    },
                    getLabelWidth: function(t) {
                        var e = this.options.ticks,
                            i = this.ctx.measureText(t).width,
                            n = r.toRadians(e.maxRotation),
                            o = Math.cos(n),
                            s = Math.sin(n);
                        return i * o + r.valueOrDefault(e.fontSize, a.global.defaultFontSize) * s
                    },
                    getLabelCapacity: function(t) {
                        var e = this.options.time.displayFormats.millisecond,
                            i = this.tickFormatFunction(n(t), 0, [], e),
                            a = this.getLabelWidth(i),
                            r = this.isHorizontal() ? this.width : this.height,
                            o = Math.floor(r / a);
                        return o > 0 ? o : 1
                    }
                });
                t.scaleService.registerScaleType("time", e, {
                    position: "bottom",
                    distribution: "linear",
                    bounds: "data",
                    time: {
                        parser: !1,
                        format: !1,
                        unit: !1,
                        round: !1,
                        displayFormat: !1,
                        isoWeekday: !1,
                        minUnit: "millisecond",
                        displayFormats: {
                            millisecond: "h:mm:ss.SSS a",
                            second: "h:mm:ss a",
                            minute: "h:mm a",
                            hour: "hA",
                            day: "MMM D",
                            week: "ll",
                            month: "MMM YYYY",
                            quarter: "[Q]Q - YYYY",
                            year: "YYYY"
                        }
                    },
                    ticks: {
                        autoSkip: !1,
                        source: "auto",
                        major: {
                            enabled: !1
                        }
                    }
                })
            }
        }, {
            25: 25,
            45: 45,
            6: 6
        }]
    }, {}, [7])(7)
});


(function ($) {
  // USE STRICT
  "use strict";

  try {
    //WidgetChart 1
    var ctx = document.getElementById("widgetChart1");
    if (ctx) {
      ctx.height = 130;
      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
          type: 'line',
          datasets: [{
            data: [78, 81, 80, 45, 34, 12, 40],
            label: 'Dataset',
            backgroundColor: 'rgba(255,255,255,.1)',
            borderColor: 'rgba(255,255,255,.55)',
          },]
        },
        options: {
          maintainAspectRatio: true,
          legend: {
            display: false
          },
          layout: {
            padding: {
              left: 0,
              right: 0,
              top: 0,
              bottom: 0
            }
          },
          responsive: true,
          scales: {
            xAxes: [{
              gridLines: {
                color: 'transparent',
                zeroLineColor: 'transparent'
              },
              ticks: {
                fontSize: 2,
                fontColor: 'transparent'
              }
            }],
            yAxes: [{
              display: false,
              ticks: {
                display: false,
              }
            }]
          },
          title: {
            display: false,
          },
          elements: {
            line: {
              borderWidth: 0
            },
            point: {
              radius: 0,
              hitRadius: 10,
              hoverRadius: 4
            }
          }
        }
      });
    }


    //WidgetChart 2
    var ctx = document.getElementById("widgetChart2");
    if (ctx) {
      ctx.height = 130;
      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['January', 'February', 'March', 'April', 'May', 'June'],
          type: 'line',
          datasets: [{
            data: [1, 18, 9, 17, 34, 22],
            label: 'Dataset',
            backgroundColor: 'transparent',
            borderColor: 'rgba(255,255,255,.55)',
          },]
        },
        options: {

          maintainAspectRatio: false,
          legend: {
            display: false
          },
          responsive: true,
          tooltips: {
            mode: 'index',
            titleFontSize: 12,
            titleFontColor: '#000',
            bodyFontColor: '#000',
            backgroundColor: '#fff',
            titleFontFamily: 'Montserrat',
            bodyFontFamily: 'Montserrat',
            cornerRadius: 3,
            intersect: false,
          },
          scales: {
            xAxes: [{
              gridLines: {
                color: 'transparent',
                zeroLineColor: 'transparent'
              },
              ticks: {
                fontSize: 2,
                fontColor: 'transparent'
              }
            }],
            yAxes: [{
              display: false,
              ticks: {
                display: false,
              }
            }]
          },
          title: {
            display: false,
          },
          elements: {
            line: {
              tension: 0.00001,
              borderWidth: 1
            },
            point: {
              radius: 4,
              hitRadius: 10,
              hoverRadius: 4
            }
          }
        }
      });
    }


    //WidgetChart 3
    var ctx = document.getElementById("widgetChart3");
    if (ctx) {
      ctx.height = 130;
      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['January', 'February', 'March', 'April', 'May', 'June'],
          type: 'line',
          datasets: [{
            data: [65, 59, 84, 84, 51, 55],
            label: 'Dataset',
            backgroundColor: 'transparent',
            borderColor: 'rgba(255,255,255,.55)',
          },]
        },
        options: {

          maintainAspectRatio: false,
          legend: {
            display: false
          },
          responsive: true,
          tooltips: {
            mode: 'index',
            titleFontSize: 12,
            titleFontColor: '#000',
            bodyFontColor: '#000',
            backgroundColor: '#fff',
            titleFontFamily: 'Montserrat',
            bodyFontFamily: 'Montserrat',
            cornerRadius: 3,
            intersect: false,
          },
          scales: {
            xAxes: [{
              gridLines: {
                color: 'transparent',
                zeroLineColor: 'transparent'
              },
              ticks: {
                fontSize: 2,
                fontColor: 'transparent'
              }
            }],
            yAxes: [{
              display: false,
              ticks: {
                display: false,
              }
            }]
          },
          title: {
            display: false,
          },
          elements: {
            line: {
              borderWidth: 1
            },
            point: {
              radius: 4,
              hitRadius: 10,
              hoverRadius: 4
            }
          }
        }
      });
    }


    //WidgetChart 4
    var ctx = document.getElementById("widgetChart4");
    if (ctx) {
      ctx.height = 115;
      var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
          datasets: [
            {
              label: "My First dataset",
              data: [78, 81, 80, 65, 58, 75, 60, 75, 65, 60, 60, 75],
              borderColor: "transparent",
              borderWidth: "0",
              backgroundColor: "rgba(255,255,255,.3)"
            }
          ]
        },
        options: {
          maintainAspectRatio: true,
          legend: {
            display: false
          },
          scales: {
            xAxes: [{
              display: false,
              categoryPercentage: 1,
              barPercentage: 0.65
            }],
            yAxes: [{
              display: false
            }]
          }
        }
      });
    }

    // Recent Report
    const brandProduct = 'rgba(0,181,233,0.8)'
    const brandService = 'rgba(0,173,95,0.8)'

    var elements = 10
    var data1 = [52, 60, 55, 50, 65, 80, 57, 70, 105, 115]
    var data2 = [102, 70, 80, 100, 56, 53, 80, 75, 65, 90]

    var ctx = document.getElementById("recent-rep-chart");
    if (ctx) {
      ctx.height = 250;
      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', ''],
          datasets: [
            {
              label: 'My First dataset',
              backgroundColor: brandService,
              borderColor: 'transparent',
              pointHoverBackgroundColor: '#fff',
              borderWidth: 0,
              data: data1

            },
            {
              label: 'My Second dataset',
              backgroundColor: brandProduct,
              borderColor: 'transparent',
              pointHoverBackgroundColor: '#fff',
              borderWidth: 0,
              data: data2

            }
          ]
        },
        options: {
          maintainAspectRatio: true,
          legend: {
            display: false
          },
          responsive: true,
          scales: {
            xAxes: [{
              gridLines: {
                drawOnChartArea: true,
                color: '#f2f2f2'
              },
              ticks: {
                fontFamily: "Poppins",
                fontSize: 12
              }
            }],
            yAxes: [{
              ticks: {
                beginAtZero: true,
                maxTicksLimit: 5,
                stepSize: 50,
                max: 150,
                fontFamily: "Poppins",
                fontSize: 12
              },
              gridLines: {
                display: true,
                color: '#f2f2f2'

              }
            }]
          },
          elements: {
            point: {
              radius: 0,
              hitRadius: 10,
              hoverRadius: 4,
              hoverBorderWidth: 3
            }
          }


        }
      });
    }

    // Percent Chart
    var ctx = document.getElementById("percent-chart");
    if (ctx) {
      ctx.height = 280;
      var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          datasets: [
            {
              label: "My First dataset",
              data: [60, 40],
              backgroundColor: [
                '#00b5e9',
                '#fa4251'
              ],
              hoverBackgroundColor: [
                '#00b5e9',
                '#fa4251'
              ],
              borderWidth: [
                0, 0
              ],
              hoverBorderColor: [
                'transparent',
                'transparent'
              ]
            }
          ],
          labels: [
            'Products',
            'Services'
          ]
        },
        options: {
          maintainAspectRatio: false,
          responsive: true,
          cutoutPercentage: 55,
          animation: {
            animateScale: true,
            animateRotate: true
          },
          legend: {
            display: false
          },
          tooltips: {
            titleFontFamily: "Poppins",
            xPadding: 15,
            yPadding: 10,
            caretPadding: 0,
            bodyFontSize: 16
          }
        }
      });
    }

  } catch (error) {
    console.log(error);
  }



  try {

    // Recent Report 2
    const bd_brandProduct2 = 'rgba(0,181,233,0.9)'
    const bd_brandService2 = 'rgba(0,173,95,0.9)'
    const brandProduct2 = 'rgba(0,181,233,0.2)'
    const brandService2 = 'rgba(0,173,95,0.2)'

    var data3 = [52, 60, 55, 50, 65, 80, 57, 70, 105, 115]
    var data4 = [102, 70, 80, 100, 56, 53, 80, 75, 65, 90]

    var ctx = document.getElementById("recent-rep2-chart");
    if (ctx) {
      ctx.height = 230;
      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', ''],
          datasets: [
            {
              label: 'My First dataset',
              backgroundColor: brandService2,
              borderColor: bd_brandService2,
              pointHoverBackgroundColor: '#fff',
              borderWidth: 0,
              data: data3

            },
            {
              label: 'My Second dataset',
              backgroundColor: brandProduct2,
              borderColor: bd_brandProduct2,
              pointHoverBackgroundColor: '#fff',
              borderWidth: 0,
              data: data4

            }
          ]
        },
        options: {
          maintainAspectRatio: true,
          legend: {
            display: false
          },
          responsive: true,
          scales: {
            xAxes: [{
              gridLines: {
                drawOnChartArea: true,
                color: '#f2f2f2'
              },
              ticks: {
                fontFamily: "Poppins",
                fontSize: 12
              }
            }],
            yAxes: [{
              ticks: {
                beginAtZero: true,
                maxTicksLimit: 5,
                stepSize: 50,
                max: 150,
                fontFamily: "Poppins",
                fontSize: 12
              },
              gridLines: {
                display: true,
                color: '#f2f2f2'

              }
            }]
          },
          elements: {
            point: {
              radius: 0,
              hitRadius: 10,
              hoverRadius: 4,
              hoverBorderWidth: 3
            },
            line: {
              tension: 0
            }
          }


        }
      });
    }

  } catch (error) {
    console.log(error);
  }


  try {

    // Recent Report 3
    const bd_brandProduct3 = 'rgba(0,181,233,0.9)';
    const bd_brandService3 = 'rgba(0,173,95,0.9)';
    const brandProduct3 = 'transparent';
    const brandService3 = 'transparent';

    var data5 = [52, 60, 55, 50, 65, 80, 57, 115];
    var data6 = [102, 70, 80, 100, 56, 53, 80, 90];

    var ctx = document.getElementById("recent-rep3-chart");
    if (ctx) {
      ctx.height = 230;
      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', ''],
          datasets: [
            {
              label: 'My First dataset',
              backgroundColor: brandService3,
              borderColor: bd_brandService3,
              pointHoverBackgroundColor: '#fff',
              borderWidth: 0,
              data: data5,
              pointBackgroundColor: bd_brandService3
            },
            {
              label: 'My Second dataset',
              backgroundColor: brandProduct3,
              borderColor: bd_brandProduct3,
              pointHoverBackgroundColor: '#fff',
              borderWidth: 0,
              data: data6,
              pointBackgroundColor: bd_brandProduct3

            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          legend: {
            display: false
          },
          responsive: true,
          scales: {
            xAxes: [{
              gridLines: {
                drawOnChartArea: true,
                color: '#f2f2f2'
              },
              ticks: {
                fontFamily: "Poppins",
                fontSize: 12
              }
            }],
            yAxes: [{
              ticks: {
                beginAtZero: true,
                maxTicksLimit: 5,
                stepSize: 50,
                max: 150,
                fontFamily: "Poppins",
                fontSize: 12
              },
              gridLines: {
                display: false,
                color: '#f2f2f2'
              }
            }]
          },
          elements: {
            point: {
              radius: 3,
              hoverRadius: 4,
              hoverBorderWidth: 3,
              backgroundColor: '#333'
            }
          }


        }
      });
    }

  } catch (error) {
    console.log(error);
  }

  try {
    //WidgetChart 5
    var ctx = document.getElementById("widgetChart5");
    if (ctx) {
      ctx.height = 220;
      var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
          datasets: [
            {
              label: "My First dataset",
              data: [78, 81, 80, 64, 65, 80, 70, 75, 67, 85, 66, 68],
              borderColor: "transparent",
              borderWidth: "0",
              backgroundColor: "#ccc",
            }
          ]
        },
        options: {
          maintainAspectRatio: true,
          legend: {
            display: false
          },
          scales: {
            xAxes: [{
              display: false,
              categoryPercentage: 1,
              barPercentage: 0.65
            }],
            yAxes: [{
              display: false
            }]
          }
        }
      });
    }

  } catch (error) {
    console.log(error);
  }

  try {

    // Percent Chart 2
    var ctx = document.getElementById("percent-chart2");
    if (ctx) {
      ctx.height = 209;
      var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          datasets: [
            {
              label: "My First dataset",
              data: [60, 40],
              backgroundColor: [
                '#00b5e9',
                '#fa4251'
              ],
              hoverBackgroundColor: [
                '#00b5e9',
                '#fa4251'
              ],
              borderWidth: [
                0, 0
              ],
              hoverBorderColor: [
                'transparent',
                'transparent'
              ]
            }
          ],
          labels: [
            'Products',
            'Services'
          ]
        },
        options: {
          maintainAspectRatio: false,
          responsive: true,
          cutoutPercentage: 87,
          animation: {
            animateScale: true,
            animateRotate: true
          },
          legend: {
            display: false,
            position: 'bottom',
            labels: {
              fontSize: 14,
              fontFamily: "Poppins,sans-serif"
            }

          },
          tooltips: {
            titleFontFamily: "Poppins",
            xPadding: 15,
            yPadding: 10,
            caretPadding: 0,
            bodyFontSize: 16,
          }
        }
      });
    }

  } catch (error) {
    console.log(error);
  }

  try {
    //Sales chart
    var ctx = document.getElementById("sales-chart");
    if (ctx) {
      ctx.height = 150;
      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: ["2010", "2011", "2012", "2013", "2014", "2015", "2016"],
          type: 'line',
          defaultFontFamily: 'Poppins',
          datasets: [{
            label: "Foods",
            data: [0, 30, 10, 120, 50, 63, 10],
            backgroundColor: 'transparent',
            borderColor: 'rgba(220,53,69,0.75)',
            borderWidth: 3,
            pointStyle: 'circle',
            pointRadius: 5,
            pointBorderColor: 'transparent',
            pointBackgroundColor: 'rgba(220,53,69,0.75)',
          }, {
            label: "Electronics",
            data: [0, 50, 40, 80, 40, 79, 120],
            backgroundColor: 'transparent',
            borderColor: 'rgba(40,167,69,0.75)',
            borderWidth: 3,
            pointStyle: 'circle',
            pointRadius: 5,
            pointBorderColor: 'transparent',
            pointBackgroundColor: 'rgba(40,167,69,0.75)',
          }]
        },
        options: {
          responsive: true,
          tooltips: {
            mode: 'index',
            titleFontSize: 12,
            titleFontColor: '#000',
            bodyFontColor: '#000',
            backgroundColor: '#fff',
            titleFontFamily: 'Poppins',
            bodyFontFamily: 'Poppins',
            cornerRadius: 3,
            intersect: false,
          },
          legend: {
            display: false,
            labels: {
              usePointStyle: true,
              fontFamily: 'Poppins',
            },
          },
          scales: {
            xAxes: [{
              display: true,
              gridLines: {
                display: false,
                drawBorder: false
              },
              scaleLabel: {
                display: false,
                labelString: 'Month'
              },
              ticks: {
                fontFamily: "Poppins"
              }
            }],
            yAxes: [{
              display: true,
              gridLines: {
                display: false,
                drawBorder: false
              },
              scaleLabel: {
                display: true,
                labelString: 'Value',
                fontFamily: "Poppins"

              },
              ticks: {
                fontFamily: "Poppins"
              }
            }]
          },
          title: {
            display: false,
            text: 'Normal Legend'
          }
        }
      });
    }


  } catch (error) {
    console.log(error);
  }

  try {

    //Team chart
    var ctx = document.getElementById("team-chart");
    if (ctx) {
      ctx.height = 150;
      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: ["2010", "2011", "2012", "2013", "2014", "2015", "2016"],
          type: 'line',
          defaultFontFamily: 'Poppins',
          datasets: [{
            data: [0, 7, 3, 5, 2, 10, 7],
            label: "Expense",
            backgroundColor: 'rgba(0,103,255,.15)',
            borderColor: 'rgba(0,103,255,0.5)',
            borderWidth: 3.5,
            pointStyle: 'circle',
            pointRadius: 5,
            pointBorderColor: 'transparent',
            pointBackgroundColor: 'rgba(0,103,255,0.5)',
          },]
        },
        options: {
          responsive: true,
          tooltips: {
            mode: 'index',
            titleFontSize: 12,
            titleFontColor: '#000',
            bodyFontColor: '#000',
            backgroundColor: '#fff',
            titleFontFamily: 'Poppins',
            bodyFontFamily: 'Poppins',
            cornerRadius: 3,
            intersect: false,
          },
          legend: {
            display: false,
            position: 'top',
            labels: {
              usePointStyle: true,
              fontFamily: 'Poppins',
            },


          },
          scales: {
            xAxes: [{
              display: true,
              gridLines: {
                display: false,
                drawBorder: false
              },
              scaleLabel: {
                display: false,
                labelString: 'Month'
              },
              ticks: {
                fontFamily: "Poppins"
              }
            }],
            yAxes: [{
              display: true,
              gridLines: {
                display: false,
                drawBorder: false
              },
              scaleLabel: {
                display: true,
                labelString: 'Value',
                fontFamily: "Poppins"
              },
              ticks: {
                fontFamily: "Poppins"
              }
            }]
          },
          title: {
            display: false,
          }
        }
      });
    }


  } catch (error) {
    console.log(error);
  }

  try {
    //bar chart
    var ctx = document.getElementById("barChart");
    if (ctx) {
      ctx.height = 200;
      var myChart = new Chart(ctx, {
        type: 'bar',
        defaultFontFamily: 'Poppins',
        data: {
          labels: ["January", "February", "March", "April", "May", "June", "July"],
          datasets: [
            {
              label: "My First dataset",
              data: [65, 59, 80, 81, 56, 55, 40],
              borderColor: "rgba(0, 123, 255, 0.9)",
              borderWidth: "0",
              backgroundColor: "rgba(0, 123, 255, 0.5)",
              fontFamily: "Poppins"
            },
            {
              label: "My Second dataset",
              data: [28, 48, 40, 19, 86, 27, 90],
              borderColor: "rgba(0,0,0,0.09)",
              borderWidth: "0",
              backgroundColor: "rgba(0,0,0,0.07)",
              fontFamily: "Poppins"
            }
          ]
        },
        options: {
          legend: {
            position: 'top',
            labels: {
              fontFamily: 'Poppins'
            }

          },
          scales: {
            xAxes: [{
              ticks: {
                fontFamily: "Poppins"

              }
            }],
            yAxes: [{
              ticks: {
                beginAtZero: true,
                fontFamily: "Poppins"
              }
            }]
          }
        }
      });
    }


  } catch (error) {
    console.log(error);
  }

  try {

    //radar chart
    var ctx = document.getElementById("radarChart");
    if (ctx) {
      ctx.height = 200;
      var myChart = new Chart(ctx, {
        type: 'radar',
        data: {
          labels: [["Eating", "Dinner"], ["Drinking", "Water"], "Sleeping", ["Designing", "Graphics"], "Coding", "Cycling", "Running"],
          defaultFontFamily: 'Poppins',
          datasets: [
            {
              label: "My First dataset",
              data: [65, 59, 66, 45, 56, 55, 40],
              borderColor: "rgba(0, 123, 255, 0.6)",
              borderWidth: "1",
              backgroundColor: "rgba(0, 123, 255, 0.4)"
            },
            {
              label: "My Second dataset",
              data: [28, 12, 40, 19, 63, 27, 87],
              borderColor: "rgba(0, 123, 255, 0.7",
              borderWidth: "1",
              backgroundColor: "rgba(0, 123, 255, 0.5)"
            }
          ]
        },
        options: {
          legend: {
            position: 'top',
            labels: {
              fontFamily: 'Poppins'
            }

          },
          scale: {
            ticks: {
              beginAtZero: true,
              fontFamily: "Poppins"
            }
          }
        }
      });
    }

  } catch (error) {
    console.log(error)
  }

  try {

    //line chart
    var ctx = document.getElementById("lineChart");
    if (ctx) {
      ctx.height = 150;
      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: ["January", "February", "March", "April", "May", "June", "July"],
          defaultFontFamily: "Poppins",
          datasets: [
            {
              label: "My First dataset",
              borderColor: "rgba(0,0,0,.09)",
              borderWidth: "1",
              backgroundColor: "rgba(0,0,0,.07)",
              data: [22, 44, 67, 43, 76, 45, 12]
            },
            {
              label: "My Second dataset",
              borderColor: "rgba(0, 123, 255, 0.9)",
              borderWidth: "1",
              backgroundColor: "rgba(0, 123, 255, 0.5)",
              pointHighlightStroke: "rgba(26,179,148,1)",
              data: [16, 32, 18, 26, 42, 33, 44]
            }
          ]
        },
        options: {
          legend: {
            position: 'top',
            labels: {
              fontFamily: 'Poppins'
            }

          },
          responsive: true,
          tooltips: {
            mode: 'index',
            intersect: false
          },
          hover: {
            mode: 'nearest',
            intersect: true
          },
          scales: {
            xAxes: [{
              ticks: {
                fontFamily: "Poppins"

              }
            }],
            yAxes: [{
              ticks: {
                beginAtZero: true,
                fontFamily: "Poppins"
              }
            }]
          }

        }
      });
    }


  } catch (error) {
    console.log(error);
  }


  try {

    //doughut chart
    var ctx = document.getElementById("doughutChart");
    if (ctx) {
      ctx.height = 150;
      var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          datasets: [{
            data: [45, 25, 20, 10],
            backgroundColor: [
              "rgba(0, 123, 255,0.9)",
              "rgba(0, 123, 255,0.7)",
              "rgba(0, 123, 255,0.5)",
              "rgba(0,0,0,0.07)"
            ],
            hoverBackgroundColor: [
              "rgba(0, 123, 255,0.9)",
              "rgba(0, 123, 255,0.7)",
              "rgba(0, 123, 255,0.5)",
              "rgba(0,0,0,0.07)"
            ]

          }],
          labels: [
            "Green",
            "Green",
            "Green",
            "Green"
          ]
        },
        options: {
          legend: {
            position: 'top',
            labels: {
              fontFamily: 'Poppins'
            }

          },
          responsive: true
        }
      });
    }


  } catch (error) {
    console.log(error);
  }


  try {

    //pie chart
    var ctx = document.getElementById("pieChart");
    if (ctx) {
      ctx.height = 200;
      var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
          datasets: [{
            data: [45, 25, 20, 10],
            backgroundColor: [
              "rgba(0, 123, 255,0.9)",
              "rgba(0, 123, 255,0.7)",
              "rgba(0, 123, 255,0.5)",
              "rgba(0,0,0,0.07)"
            ],
            hoverBackgroundColor: [
              "rgba(0, 123, 255,0.9)",
              "rgba(0, 123, 255,0.7)",
              "rgba(0, 123, 255,0.5)",
              "rgba(0,0,0,0.07)"
            ]

          }],
          labels: [
            "Green",
            "Green",
            "Green"
          ]
        },
        options: {
          legend: {
            position: 'top',
            labels: {
              fontFamily: 'Poppins'
            }

          },
          responsive: true
        }
      });
    }


  } catch (error) {
    console.log(error);
  }

  try {

    // polar chart
    var ctx = document.getElementById("polarChart");
    if (ctx) {
      ctx.height = 200;
      var myChart = new Chart(ctx, {
        type: 'polarArea',
        data: {
          datasets: [{
            data: [15, 18, 9, 6, 19],
            backgroundColor: [
              "rgba(0, 123, 255,0.9)",
              "rgba(0, 123, 255,0.8)",
              "rgba(0, 123, 255,0.7)",
              "rgba(0,0,0,0.2)",
              "rgba(0, 123, 255,0.5)"
            ]

          }],
          labels: [
            "Green",
            "Green",
            "Green",
            "Green"
          ]
        },
        options: {
          legend: {
            position: 'top',
            labels: {
              fontFamily: 'Poppins'
            }

          },
          responsive: true
        }
      });
    }

  } catch (error) {
    console.log(error);
  }

  try {

    // single bar chart
    var ctx = document.getElementById("singelBarChart");
    if (ctx) {
      ctx.height = 150;
      var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ["Sun", "Mon", "Tu", "Wed", "Th", "Fri", "Sat"],
          datasets: [
            {
              label: "My First dataset",
              data: [40, 55, 75, 81, 56, 55, 40],
              borderColor: "rgba(0, 123, 255, 0.9)",
              borderWidth: "0",
              backgroundColor: "rgba(0, 123, 255, 0.5)"
            }
          ]
        },
        options: {
          legend: {
            position: 'top',
            labels: {
              fontFamily: 'Poppins'
            }

          },
          scales: {
            xAxes: [{
              ticks: {
                fontFamily: "Poppins"

              }
            }],
            yAxes: [{
              ticks: {
                beginAtZero: true,
                fontFamily: "Poppins"
              }
            }]
          }
        }
      });
    }

  } catch (error) {
    console.log(error);
  }

})(jQuery);


/*
(function ($) {
    // USE STRICT
    "use strict";
    $(".animsition").animsition({
      inClass: 'fade-in',
      outClass: 'fade-out',
      inDuration: 900,
      outDuration: 900,
      linkElement: 'a:not([target="_blank"]):not([href^="#"]):not([class^="chosen-single"])',
      loading: true,
      loadingParentElement: 'html',
      loadingClass: 'page-loader',
      loadingInner: '<div class="page-loader__spin"></div>',
      timeout: false,
      timeoutCountdown: 5000,
      onLoadEvent: true,
      browser: ['animation-duration', '-webkit-animation-duration'],
      overlay: false,
      overlayClass: 'animsition-overlay-slide',
      overlayParentElement: 'html',
      transition: function (url) {
        window.location.href = url;
      }
    });
  
  
  })(jQuery);
 */

(function ($) {
  // Use Strict
  "use strict";
  try {
    var progressbarSimple = $('.js-progressbar-simple');
    progressbarSimple.each(function () {
      var that = $(this);
      var executed = false;
      $(window).on('load', function () {

        that.waypoint(function () {
          if (!executed) {
            executed = true;
            /*progress bar*/
            that.progressbar({
              update: function (current_percentage, $this) {
                $this.find('.js-value').html(current_percentage + '%');
              }
            });
          }
        }, {
            offset: 'bottom-in-view'
          });

      });
    });
  } catch (err) {
    console.log(err);
  }
})(jQuery);
(function ($) {
  // USE STRICT
  "use strict";

  // Scroll Bar
  try {
    var jscr1 = $('.js-scrollbar1');
    if(jscr1[0]) {
      const ps1 = new PerfectScrollbar('.js-scrollbar1');      
    }

    var jscr2 = $('.js-scrollbar2');
    if (jscr2[0]) {
      const ps2 = new PerfectScrollbar('.js-scrollbar2');

    }

  } catch (error) {
    console.log(error);
  }

})(jQuery);
(function ($) {
  // USE STRICT
  "use strict";

  // Select 2
  try {

    $(".js-select2").each(function () {
      $(this).select2({
        minimumResultsForSearch: 20,
        dropdownParent: $(this).next('.dropDownSelect2')
      });
    });

  } catch (error) {
    console.log(error);
  }


})(jQuery);
(function ($) {
  // USE STRICT
  "use strict";

  // Dropdown 
  try {
    var menu = $('.js-item-menu');
    var sub_menu_is_showed = -1;

    for (var i = 0; i < menu.length; i++) {
      $(menu[i]).on('click', function (e) {
        e.preventDefault();
        $('.js-right-sidebar').removeClass("show-sidebar");        
        if (jQuery.inArray(this, menu) == sub_menu_is_showed) {
          $(this).toggleClass('show-dropdown');
          sub_menu_is_showed = -1;
        }
        else {
          for (var i = 0; i < menu.length; i++) {
            $(menu[i]).removeClass("show-dropdown");
          }
          $(this).toggleClass('show-dropdown');
          sub_menu_is_showed = jQuery.inArray(this, menu);
        }
      });
    }
    $(".js-item-menu, .js-dropdown").click(function (event) {
      event.stopPropagation();
    });

    $("body,html").on("click", function () {
      for (var i = 0; i < menu.length; i++) {
        menu[i].classList.remove("show-dropdown");
      }
      sub_menu_is_showed = -1;
    });

  } catch (error) {
    console.log(error);
  }

  var wW = $(window).width();
    // Right Sidebar
    var right_sidebar = $('.js-right-sidebar');
    var sidebar_btn = $('.js-sidebar-btn');

    sidebar_btn.on('click', function (e) {
      e.preventDefault();
      for (var i = 0; i < menu.length; i++) {
        menu[i].classList.remove("show-dropdown");
      }
      sub_menu_is_showed = -1;
      right_sidebar.toggleClass("show-sidebar");
    });

    $(".js-right-sidebar, .js-sidebar-btn").click(function (event) {
      event.stopPropagation();
    });

    $("body,html").on("click", function () {
      right_sidebar.removeClass("show-sidebar");

    });
 

  // Sublist Sidebar
  try {
    var arrow = $('.js-arrow');
    arrow.each(function () {
      var that = $(this);
      that.on('click', function (e) {
        e.preventDefault();
        that.find(".arrow").toggleClass("up");
        that.toggleClass("open");
        that.parent().find('.js-sub-list').slideToggle("250");
      });
    });

  } catch (error) {
    console.log(error);
  }


  try {
    // Hamburger Menu
    $('.hamburger').on('click', function () {
      $(this).toggleClass('is-active');
      $('.navbar-mobile').slideToggle('500');
    });
    $('.navbar-mobile__list li.has-dropdown > a').on('click', function () {
      var dropdown = $(this).siblings('ul.navbar-mobile__dropdown');
      $(this).toggleClass('active');
      $(dropdown).slideToggle('500');
      return false;
    });
  } catch (error) {
    console.log(error);
  }
})(jQuery);
(function ($) {
  // USE STRICT
  "use strict";

  // Load more
  try {
    var list_load = $('.js-list-load');
    if (list_load[0]) {
      list_load.each(function () {
        var that = $(this);
        that.find('.js-load-item').hide();
        var load_btn = that.find('.js-load-btn');
        load_btn.on('click', function (e) {
          $(this).text("Loading...").delay(1500).queue(function (next) {
            $(this).hide();
            that.find(".js-load-item").fadeToggle("slow", 'swing');
          });
          e.preventDefault();
        });
      })

    }
  } catch (error) {
    console.log(error);
  }

})(jQuery);
(function ($) {
  // USE STRICT
  "use strict";

  try {
    
    $('[data-toggle="tooltip"]').tooltip();

  } catch (error) {
    console.log(error);
  }

  // Chatbox
  try {
    var inbox_wrap = $('.js-inbox');
    var message = $('.au-message__item');
    message.each(function(){
      var that = $(this);

      that.on('click', function(){
        $(this).parent().parent().parent().toggleClass('show-chat-box');
      });
    });
    

  } catch (error) {
    console.log(error);
  }

})(jQuery);