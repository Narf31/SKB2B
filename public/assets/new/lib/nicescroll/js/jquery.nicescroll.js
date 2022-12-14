(function(a) {
    if (typeof define === "function" && define.amd) {
        define(["jquery"], a)
    } else {
        a(jQuery)
    }
}(function(m) {
    var f = false;
    var k = false;
    var a = false;
    var i = 5000;
    var q = 2000;
    var d = 0;
    var e = m;
    function x() {
        var v = document.getElementsByTagName("script");
        var z = v[v.length - 1].src.split("?")[0];
        return (z.split("/").length > 0) ? z.split("/").slice(0, -1).join("/") + "/" : ""
    }
    var w = ["ms", "moz", "webkit", "o"];
    var g = window.requestAnimationFrame || false;
    var h = window.cancelAnimationFrame || false;
    if (!g) {
        for (var t in w) {
            var n = w[t];
            if (!g) {
                g = window[n + "RequestAnimationFrame"]
            }
            if (!h) {
                h = window[n + "CancelAnimationFrame"] || window[n + "CancelRequestAnimationFrame"]
            }
        }
    }
    var p = window.MutationObserver || window.WebKitMutationObserver || false;
    var c = {
        zindex: "auto",
        cursoropacitymin: 0,
        cursoropacitymax: 1,
        cursorcolor: "#424242",
        cursorwidth: "5px",
        cursorborder: "1px solid #fff",
        cursorborderradius: "5px",
        scrollspeed: 60,
        mousescrollstep: 8 * 3,
        touchbehavior: false,
        hwacceleration: true,
        usetransition: true,
        boxzoom: false,
        dblclickzoom: true,
        gesturezoom: true,
        grabcursorenabled: true,
        autohidemode: true,
        background: "",
        iframeautoresize: true,
        cursorminheight: 32,
        preservenativescrolling: true,
        railoffset: false,
        bouncescroll: true,
        spacebarenabled: true,
        railpadding: {
            top: 0,
            right: 0,
            left: 0,
            bottom: 0
        },
        disableoutline: true,
        horizrailenabled: true,
        railalign: "right",
        railvalign: "bottom",
        enabletranslate3d: true,
        enablemousewheel: true,
        enablekeyboard: true,
        smoothscroll: true,
        sensitiverail: true,
        enablemouselockapi: true,
        cursorfixedheight: false,
        directionlockdeadzone: 6,
        hidecursordelay: 400,
        nativeparentscrolling: true,
        enablescrollonselection: true,
        overflowx: true,
        overflowy: true,
        cursordragspeed: 0.3,
        rtlmode: "auto",
        cursordragontouch: false,
        oneaxismousemode: "auto",
        scriptpath: x()
    };
    var j = false;
    var u = function() {
        if (j) {
            return j
        }
        var A = document.createElement("DIV");
        var D = {};
        D.haspointerlock = "pointerLockElement"in document || "mozPointerLockElement"in document || "webkitPointerLockElement"in document;
        D.isopera = ("opera"in window);
        D.isopera12 = (D.isopera && ("getUserMedia"in navigator));
        D.isoperamini = (Object.prototype.toString.call(window.operamini) === "[object OperaMini]");
        D.isie = (("all"in document) && ("attachEvent"in A) && !D.isopera);
        D.isieold = (D.isie && !("msInterpolationMode"in A.style));
        D.isie7 = D.isie && !D.isieold && (!("documentMode"in document) || (document.documentMode == 7));
        D.isie8 = D.isie && ("documentMode"in document) && (document.documentMode == 8);
        D.isie9 = D.isie && ("performance"in window) && (document.documentMode >= 9);
        D.isie10 = D.isie && ("performance"in window) && (document.documentMode >= 10);
        D.isie9mobile = /iemobile.9/i.test(navigator.userAgent);
        if (D.isie9mobile) {
            D.isie9 = false
        }
        D.isie7mobile = (!D.isie9mobile && D.isie7) && /iemobile/i.test(navigator.userAgent);
        D.ismozilla = ("MozAppearance"in A.style);
        D.iswebkit = ("WebkitAppearance"in A.style);
        D.ischrome = ("chrome"in window);
        D.ischrome22 = (D.ischrome && D.haspointerlock);
        D.ischrome26 = (D.ischrome && ("transition"in A.style));
        D.cantouch = ("ontouchstart"in document.documentElement) || ("ontouchstart"in window);
        D.hasmstouch = (window.navigator.msPointerEnabled || false);
        D.ismac = /^mac$/i.test(navigator.platform);
        D.isios = (D.cantouch && /iphone|ipad|ipod/i.test(navigator.platform));
        D.isios4 = ((D.isios) && !("seal"in Object));
        D.isandroid = (/android/i.test(navigator.userAgent));
        D.trstyle = false;
        D.hastransform = false;
        D.hastranslate3d = false;
        D.transitionstyle = false;
        D.hastransition = false;
        D.transitionend = false;
        var z = ["transform", "msTransform", "webkitTransform", "MozTransform", "OTransform"];
        for (var v = 0; v < z.length; v++) {
            if (typeof A.style[z[v]] != "undefined") {
                D.trstyle = z[v];
                break
            }
        }
        D.hastransform = (D.trstyle != false);
        if (D.hastransform) {
            A.style[D.trstyle] = "translate3d(1px,2px,3px)";
            D.hastranslate3d = /translate3d/.test(A.style[D.trstyle])
        }
        D.transitionstyle = false;
        D.prefixstyle = "";
        D.transitionend = false;
        var z = ["transition", "webkitTransition", "MozTransition", "OTransition", "OTransition", "msTransition", "KhtmlTransition"];
        var C = ["", "-webkit-", "-moz-", "-o-", "-o", "-ms-", "-khtml-"];
        var B = ["transitionend", "webkitTransitionEnd", "transitionend", "otransitionend", "oTransitionEnd", "msTransitionEnd", "KhtmlTransitionEnd"];
        for (var v = 0; v < z.length; v++) {
            if (z[v]in A.style) {
                D.transitionstyle = z[v];
                D.prefixstyle = C[v];
                D.transitionend = B[v];
                break
            }
        }
        if (D.ischrome26) {
            D.prefixstyle = C[1]
        }
        D.hastransition = (D.transitionstyle);
        function E() {
            var F = ["-moz-grab", "-webkit-grab", "grab"];
            if ((D.ischrome && !D.ischrome22) || D.isie) {
                F = []
            }
            for (var G = 0; G < F.length; G++) {
                var H = F[G];
                A.style.cursor = H;
                if (A.style.cursor == H) {
                    return H
                }
            }
            return "url(http://www.google.com/intl/en_ALL/mapfiles/openhand.cur),n-resize"
        }
        D.cursorgrabvalue = E();
        D.hasmousecapture = ("setCapture"in A);
        D.hasMutationObserver = (p !== false);
        A = null;
        j = D;
        return D
    };
    var b = function(A, E) {
        var J = this;
        this.version = "3.5.4";
        this.name = "nicescroll";
        this.me = E;
        this.opt = {
            doc: e("body"),
            win: false
        };
        e.extend(this.opt, c);
        this.opt.snapbackspeed = 80;
        if (A || false) {
            for (var G in J.opt) {
                if (typeof A[G] != "undefined") {
                    J.opt[G] = A[G]
                }
            }
        }
        this.doc = J.opt.doc;
        this.iddoc = (this.doc && this.doc[0]) ? this.doc[0].id || "" : "";
        this.ispage = /^BODY|HTML/.test((J.opt.win) ? J.opt.win[0].nodeName : this.doc[0].nodeName);
        this.haswrapper = (J.opt.win !== false);
        this.win = J.opt.win || (this.ispage ? e(window) : this.doc);
        this.docscroll = (this.ispage && !this.haswrapper) ? e(window) : this.win;
        this.body = e("body");
        this.viewport = false;
        this.isfixed = false;
        this.iframe = false;
        this.isiframe = ((this.doc[0].nodeName == "IFRAME") && (this.win[0].nodeName == "IFRAME"));
        this.istextarea = (this.win[0].nodeName == "TEXTAREA");
        this.forcescreen = false;
        this.canshowonmouseevent = (J.opt.autohidemode != "scroll");
        this.onmousedown = false;
        this.onmouseup = false;
        this.onmousemove = false;
        this.onmousewheel = false;
        this.onkeypress = false;
        this.ongesturezoom = false;
        this.onclick = false;
        this.onscrollstart = false;
        this.onscrollend = false;
        this.onscrollcancel = false;
        this.onzoomin = false;
        this.onzoomout = false;
        this.view = false;
        this.page = false;
        this.scroll = {
            x: 0,
            y: 0
        };
        this.scrollratio = {
            x: 0,
            y: 0
        };
        this.cursorheight = 20;
        this.scrollvaluemax = 0;
        this.isrtlmode = false;
        this.scrollrunning = false;
        this.scrollmom = false;
        this.observer = false;
        this.observerremover = false;
        do {
            this.id = "ascrail" + (q++)
        } while (document.getElementById(this.id));this.rail = false;
        this.cursor = false;
        this.cursorfreezed = false;
        this.selectiondrag = false;
        this.zoom = false;
        this.zoomactive = false;
        this.hasfocus = false;
        this.hasmousefocus = false;
        this.visibility = true;
        this.locked = false;
        this.hidden = false;
        this.cursoractive = true;
        this.wheelprevented = false;
        this.overflowx = J.opt.overflowx;
        this.overflowy = J.opt.overflowy;
        this.nativescrollingarea = false;
        this.checkarea = 0;
        this.events = [];
        this.saved = {};
        this.delaylist = {};
        this.synclist = {};
        this.lastdeltax = 0;
        this.lastdeltay = 0;
        this.detected = u();
        var I = e.extend({}, this.detected);
        this.canhwscroll = (I.hastransform && J.opt.hwacceleration);
        this.ishwscroll = (this.canhwscroll && J.haswrapper);
        this.istouchcapable = false;
        if (I.cantouch && I.iswebkit && !I.isios && !I.isandroid) {
            this.istouchcapable = true;
            I.cantouch = false
        }
        if (I.cantouch && I.ismozilla && !I.isios && !I.isandroid) {
            this.istouchcapable = true;
            I.cantouch = false
        }
        if (!J.opt.enablemouselockapi) {
            I.hasmousecapture = false;
            I.haspointerlock = false
        }
        this.delayed = function(O, Q, N, P) {
            var L = J.delaylist[O];
            var M = (new Date()).getTime();
            if (!P && L && L.tt) {
                return false
            }
            if (L && L.tt) {
                clearTimeout(L.tt)
            }
            if (L && L.last + N > M && !L.tt) {
                J.delaylist[O] = {
                    last: M + N,
                    tt: setTimeout(function() {
                        if (J || false) {
                            J.delaylist[O].tt = 0;
                            Q.call()
                        }
                    }, N)
                }
            } else {
                if (!L || !L.tt) {
                    J.delaylist[O] = {
                        last: M,
                        tt: 0
                    };
                    setTimeout(function() {
                        Q.call()
                    }, 0)
                }
            }
        }
        ;
        this.debounced = function(O, P, N) {
            var L = J.delaylist[O];
            var M = (new Date()).getTime();
            J.delaylist[O] = P;
            if (!L) {
                setTimeout(function() {
                    var Q = J.delaylist[O];
                    J.delaylist[O] = false;
                    Q.call()
                }, N)
            }
        }
        ;
        var F = false;
        this.synched = function(M, N) {
            function L() {
                if (F) {
                    return
                }
                g(function() {
                    F = false;
                    for (M in J.synclist) {
                        var O = J.synclist[M];
                        if (O) {
                            O.call(J)
                        }
                        J.synclist[M] = false
                    }
                });
                F = true
            }
            J.synclist[M] = N;
            L();
            return M
        }
        ;
        this.unsynched = function(L) {
            if (J.synclist[L]) {
                J.synclist[L] = false
            }
        }
        ;
        this.css = function(M, L) {
            for (var N in L) {
                J.saved.css.push([M, N, M.css(N)]);
                M.css(N, L[N])
            }
        }
        ;
        this.scrollTop = function(L) {
            return (typeof L == "undefined") ? J.getScrollTop() : J.setScrollTop(L)
        }
        ;
        this.scrollLeft = function(L) {
            return (typeof L == "undefined") ? J.getScrollLeft() : J.setScrollLeft(L)
        }
        ;
        BezierClass = function(M, L, N, R, Q, P, O) {
            this.st = M;
            this.ed = L;
            this.spd = N;
            this.p1 = R || 0;
            this.p2 = Q || 1;
            this.p3 = P || 0;
            this.p4 = O || 1;
            this.ts = (new Date()).getTime();
            this.df = this.ed - this.st
        }
        ;
        BezierClass.prototype = {
            B2: function(L) {
                return 3 * L * L * (1 - L)
            },
            B3: function(L) {
                return 3 * L * (1 - L) * (1 - L)
            },
            B4: function(L) {
                return (1 - L) * (1 - L) * (1 - L)
            },
            getNow: function() {
                var L = (new Date()).getTime();
                var M = 1 - ((L - this.ts) / this.spd);
                var N = this.B2(M) + this.B3(M) + this.B4(M);
                return (M < 0) ? this.ed : this.st + Math.round(this.df * N)
            },
            update: function(L, M) {
                this.st = this.getNow();
                this.ed = L;
                this.spd = M;
                this.ts = (new Date()).getTime();
                this.df = this.ed - this.st;
                return this
            }
        };
        if (this.ishwscroll) {
            this.doc.translate = {
                x: 0,
                y: 0,
                tx: "0px",
                ty: "0px"
            };
            if (I.hastranslate3d && I.isios) {
                this.doc.css("-webkit-backface-visibility", "hidden")
            }
            function K() {
                var L = J.doc.css(I.trstyle);
                if (L && (L.substr(0, 6) == "matrix")) {
                    return L.replace(/^.*\((.*)\)$/g, "$1").replace(/px/g, "").split(/, +/)
                }
                return false
            }
            this.getScrollTop = function(M) {
                if (!M) {
                    var L = K();
                    if (L) {
                        return (L.length == 16) ? -L[13] : -L[5]
                    }
                    if (J.timerscroll && J.timerscroll.bz) {
                        return J.timerscroll.bz.getNow()
                    }
                }
                return J.doc.translate.y
            }
            ;
            this.getScrollLeft = function(M) {
                if (!M) {
                    var L = K();
                    if (L) {
                        return (L.length == 16) ? -L[12] : -L[4]
                    }
                    if (J.timerscroll && J.timerscroll.bh) {
                        return J.timerscroll.bh.getNow()
                    }
                }
                return J.doc.translate.x
            }
            ;
            if (document.createEvent) {
                this.notifyScrollEvent = function(L) {
                    var M = document.createEvent("UIEvents");
                    M.initUIEvent("scroll", false, true, window, 1);
                    L.dispatchEvent(M)
                }
            } else {
                if (document.fireEvent) {
                    this.notifyScrollEvent = function(L) {
                        var M = document.createEventObject();
                        L.fireEvent("onscroll");
                        M.cancelBubble = true
                    }
                } else {
                    this.notifyScrollEvent = function(L, M) {}
                }
            }
            var z = -1;
            if (I.hastranslate3d && J.opt.enabletranslate3d) {
                this.setScrollTop = function(M, L) {
                    J.doc.translate.y = M;
                    J.doc.translate.ty = (M * -1) + "px";
                    J.doc.css(I.trstyle, "translate3d(" + J.doc.translate.tx + "," + J.doc.translate.ty + ",0px)");
                    if (!L) {
                        J.notifyScrollEvent(J.win[0])
                    }
                }
                ;
                this.setScrollLeft = function(M, L) {
                    J.doc.translate.x = M;
                    J.doc.translate.tx = (M * z) + "px";
                    J.doc.css(I.trstyle, "translate3d(" + J.doc.translate.tx + "," + J.doc.translate.ty + ",0px)");
                    if (!L) {
                        J.notifyScrollEvent(J.win[0])
                    }
                }
            } else {
                this.setScrollTop = function(M, L) {
                    J.doc.translate.y = M;
                    J.doc.translate.ty = (M * -1) + "px";
                    J.doc.css(I.trstyle, "translate(" + J.doc.translate.tx + "," + J.doc.translate.ty + ")");
                    if (!L) {
                        J.notifyScrollEvent(J.win[0])
                    }
                }
                ;
                this.setScrollLeft = function(M, L) {
                    J.doc.translate.x = M;
                    J.doc.translate.tx = (M * z) + "px";
                    J.doc.css(I.trstyle, "translate(" + J.doc.translate.tx + "," + J.doc.translate.ty + ")");
                    if (!L) {
                        J.notifyScrollEvent(J.win[0])
                    }
                }
            }
        } else {
            this.getScrollTop = function() {
                return J.docscroll.scrollTop()
            }
            ;
            this.setScrollTop = function(L) {
                return J.docscroll.scrollTop(L)
            }
            ;
            this.getScrollLeft = function() {
                return J.docscroll.scrollLeft()
            }
            ;
            this.setScrollLeft = function(L) {
                return J.docscroll.scrollLeft(L)
            }
        }
        this.getTarget = function(L) {
            if (!L) {
                return false
            }
            if (L.target) {
                return L.target
            }
            if (L.srcElement) {
                return L.srcElement
            }
            return false
        }
        ;
        this.hasParent = function(M, N) {
            if (!M) {
                return false
            }
            var L = M.target || M.srcElement || M || false;
            while (L && L.id != N) {
                L = L.parentNode || false
            }
            return (L !== false)
        }
        ;
        function B() {
            var M = J.win;
            if ("zIndex"in M) {
                return M.zIndex()
            }
            while (M.length > 0) {
                if (M[0].nodeType == 9) {
                    return false
                }
                var L = M.css("zIndex");
                if (!isNaN(L) && L != 0) {
                    return parseInt(L)
                }
                M = M.parent()
            }
            return false
        }
        var H = {
            thin: 1,
            medium: 3,
            thick: 5
        };
        function D(O, Q, N) {
            var M = O.css(Q);
            var L = parseFloat(M);
            if (isNaN(L)) {
                L = H[M] || 0;
                var P = (L == 3) ? ((N) ? (J.win.outerHeight() - J.win.innerHeight()) : (J.win.outerWidth() - J.win.innerWidth())) : 1;
                if (J.isie8 && L) {
                    L += 1
                }
                return (P) ? L : 0
            }
            return L
        }
        this.getOffset = function() {
            if (J.isfixed) {
                return {
                    top: parseFloat(J.win.css("top")),
                    left: parseFloat(J.win.css("left"))
                }
            }
            if (!J.viewport) {
                return J.win.offset()
            }
            var M = J.win.offset();
            var L = J.viewport.offset();
            return {
                top: M.top - L.top,
                left: M.left - L.left + J.viewport.scrollLeft()
            }
        }
        ;
        this.updateScrollBar = function(M) {
            if (J.ishwscroll) {
                J.rail.css({
                    height: J.win.innerHeight()
                });
                if (J.railh) {
                    J.railh.css({
                        width: J.win.innerWidth()
                    })
                }
            } else {
                var N = J.getOffset();
                var R = {
                    top: N.top,
                    left: N.left
                };
                R.top += D(J.win, "border-top-width", true);
                var Q = (J.win.outerWidth() - J.win.innerWidth()) / 2;
                R.left += (J.rail.align) ? J.win.outerWidth() - D(J.win, "border-right-width") - J.rail.width : D(J.win, "border-left-width");
                var O = J.opt.railoffset;
                if (O) {
                    if (O.top) {
                        R.top += O.top
                    }
                    if (J.rail.align && O.left) {
                        R.left += O.left
                    }
                }
                if (!J.locked) {
                    J.rail.css({
                        top: R.top,
                        left: R.left,
                        height: (M) ? M.h : J.win.innerHeight()
                    })
                }
                if (J.zoom) {
                    J.zoom.css({
                        top: R.top + 1,
                        left: (J.rail.align == 1) ? R.left - 20 : R.left + J.rail.width + 4
                    })
                }
                if (J.railh && !J.locked) {
                    var R = {
                        top: N.top,
                        left: N.left
                    };
                    var P = (J.railh.align) ? R.top + D(J.win, "border-top-width", true) + J.win.innerHeight() - J.railh.height : R.top + D(J.win, "border-top-width", true);
                    var L = R.left + D(J.win, "border-left-width");
                    J.railh.css({
                        top: P,
                        left: L,
                        width: J.railh.width
                    })
                }
            }
        }
        ;
        this.doRailClick = function(O, N, M) {
            var L, R, P, Q;
            if (J.locked) {
                return
            }
            J.cancelEvent(O);
            if (N) {
                L = (M) ? J.doScrollLeft : J.doScrollTop;
                P = (M) ? ((O.pageX - J.railh.offset().left - (J.cursorwidth / 2)) * J.scrollratio.x) : ((O.pageY - J.rail.offset().top - (J.cursorheight / 2)) * J.scrollratio.y);
                L(P)
            } else {
                L = (M) ? J.doScrollLeftBy : J.doScrollBy;
                P = (M) ? J.scroll.x : J.scroll.y;
                Q = (M) ? O.pageX - J.railh.offset().left : O.pageY - J.rail.offset().top;
                R = (M) ? J.view.w : J.view.h;
                (P >= Q) ? L(R) : L(-R)
            }
        }
        ;
        J.hasanimationframe = (g);
        J.hascancelanimationframe = (h);
        if (!J.hasanimationframe) {
            g = function(L) {
                return setTimeout(L, 15 - Math.floor((+new Date) / 1000) % 16)
            }
            ;
            h = clearInterval
        } else {
            if (!J.hascancelanimationframe) {
                h = function() {
                    J.cancelAnimationFrame = true
                }
            }
        }
        this.init = function() {
            J.saved.css = [];
            if (I.isie7mobile) {
                return true
            }
            if (I.isoperamini) {
                return true
            }
            if (I.hasmstouch) {
                J.css((J.ispage) ? e("html") : J.win, {
                    "-ms-touch-action": "none"
                })
            }
            J.zindex = "auto";
            if (!J.ispage && J.opt.zindex == "auto") {
                J.zindex = B() || "auto"
            } else {
                J.zindex = J.opt.zindex
            }
            if (!J.ispage && J.zindex != "auto") {
                if (J.zindex > d) {
                    d = J.zindex
                }
            }
            if (J.isie && J.zindex == 0 && J.opt.zindex == "auto") {
                J.zindex = "auto"
            }
            if (!J.ispage || (!I.cantouch && !I.isieold && !I.isie9mobile)) {
                var P = J.docscroll;
                if (J.ispage) {
                    P = (J.haswrapper) ? J.win : J.doc
                }
                if (!I.isie9mobile) {
                    J.css(P, {
                        "overflow-y": "hidden"
                    })
                }
                if (J.ispage && I.isie7) {
                    if (J.doc[0].nodeName == "BODY") {
                        J.css(e("html"), {
                            "overflow-y": "hidden"
                        })
                    } else {
                        if (J.doc[0].nodeName == "HTML") {
                            J.css(e("body"), {
                                "overflow-y": "hidden"
                            })
                        }
                    }
                }
                if (I.isios && !J.ispage && !J.haswrapper) {
                    J.css(e("body"), {
                        "-webkit-overflow-scrolling": "touch"
                    })
                }
                var Q = e(document.createElement("div"));
                Q.css({
                    position: "relative",
                    top: 0,
                    "float": "right",
                    width: J.opt.cursorwidth,
                    height: "0px",
                    "background-color": J.opt.cursorcolor,
                    border: J.opt.cursorborder,
                    "background-clip": "padding-box",
                    "-webkit-border-radius": J.opt.cursorborderradius,
                    "-moz-border-radius": J.opt.cursorborderradius,
                    "border-radius": J.opt.cursorborderradius
                });
                Q.hborder = parseFloat(Q.outerHeight() - Q.innerHeight());
                J.cursor = Q;
                var O = e(document.createElement("div"));
                O.attr("id", J.id);
                O.addClass("nicescroll-rails");
                var U, aa, M = ["left", "right"];
                for (var W in M) {
                    aa = M[W];
                    U = J.opt.railpadding[aa];
                    (U) ? O.css("padding-" + aa, U + "px") : J.opt.railpadding[aa] = 0
                }
                O.append(Q);
                O.width = Math.max(parseFloat(J.opt.cursorwidth), Q.outerWidth()) + J.opt.railpadding.left + J.opt.railpadding.right;
                O.css({
                    width: O.width + "px",
                    zIndex: J.zindex,
                    background: J.opt.background,
                    cursor: "default"
                });
                O.visibility = true;
                O.scrollable = true;
                O.align = (J.opt.railalign == "left") ? 0 : 1;
                J.rail = O;
                J.rail.drag = false;
                var L = false;
                if (J.opt.boxzoom && !J.ispage && !I.isieold) {
                    L = document.createElement("div");
                    J.bind(L, "click", J.doZoom);
                    J.zoom = e(L);
                    J.zoom.css({
                        cursor: "pointer",
                        "z-index": J.zindex,
                        backgroundImage: "url(" + J.opt.scriptpath + "zoomico.png)",
                        height: 18,
                        width: 18,
                        backgroundPosition: "0px 0px"
                    });
                    if (J.opt.dblclickzoom) {
                        J.bind(J.win, "dblclick", J.doZoom)
                    }
                    if (I.cantouch && J.opt.gesturezoom) {
                        J.ongesturezoom = function(ad) {
                            if (ad.scale > 1.5) {
                                J.doZoomIn(ad)
                            }
                            if (ad.scale < 0.8) {
                                J.doZoomOut(ad)
                            }
                            return J.cancelEvent(ad)
                        }
                        ;
                        J.bind(J.win, "gestureend", J.ongesturezoom)
                    }
                }
                J.railh = false;
                if (J.opt.horizrailenabled) {
                    J.css(P, {
                        "overflow-x": "hidden"
                    });
                    var Q = e(document.createElement("div"));
                    Q.css({
                        position: "relative",
                        top: 0,
                        height: J.opt.cursorwidth,
                        width: "0px",
                        "background-color": J.opt.cursorcolor,
                        border: J.opt.cursorborder,
                        "background-clip": "padding-box",
                        "-webkit-border-radius": J.opt.cursorborderradius,
                        "-moz-border-radius": J.opt.cursorborderradius,
                        "border-radius": J.opt.cursorborderradius
                    });
                    Q.wborder = parseFloat(Q.outerWidth() - Q.innerWidth());
                    J.cursorh = Q;
                    var T = e(document.createElement("div"));
                    T.attr("id", J.id + "-hr");
                    T.addClass("nicescroll-rails");
                    T.height = Math.max(parseFloat(J.opt.cursorwidth), Q.outerHeight());
                    T.css({
                        height: T.height + "px",
                        zIndex: J.zindex,
                        background: J.opt.background
                    });
                    T.append(Q);
                    T.visibility = true;
                    T.scrollable = true;
                    T.align = (J.opt.railvalign == "top") ? 0 : 1;
                    J.railh = T;
                    J.railh.drag = false
                }
                if (J.ispage) {
                    O.css({
                        position: "fixed",
                        top: "0px",
                        height: "100%"
                    });
                    (O.align) ? O.css({
                        right: "0px"
                    }) : O.css({
                        left: "0px"
                    });
                    J.body.append(O);
                    if (J.railh) {
                        T.css({
                            position: "fixed",
                            left: "0px",
                            width: "100%"
                        });
                        (T.align) ? T.css({
                            bottom: "0px"
                        }) : T.css({
                            top: "0px"
                        });
                        J.body.append(T)
                    }
                } else {
                    if (J.ishwscroll) {
                        if (J.win.css("position") == "static") {
                            J.css(J.win, {
                                position: "relative"
                            })
                        }
                        var R = (J.win[0].nodeName == "HTML") ? J.body : J.win;
                        if (J.zoom) {
                            J.zoom.css({
                                position: "absolute",
                                top: 1,
                                right: 0,
                                "margin-right": O.width + 4
                            });
                            R.append(J.zoom)
                        }
                        O.css({
                            position: "absolute",
                            top: 0
                        });
                        (O.align) ? O.css({
                            right: 0
                        }) : O.css({
                            left: 0
                        });
                        R.append(O);
                        if (T) {
                            T.css({
                                position: "absolute",
                                left: 0,
                                bottom: 0
                            });
                            (T.align) ? T.css({
                                bottom: 0
                            }) : T.css({
                                top: 0
                            });
                            R.append(T)
                        }
                    } else {
                        J.isfixed = (J.win.css("position") == "fixed");
                        var X = (J.isfixed) ? "fixed" : "absolute";
                        if (!J.isfixed) {
                            J.viewport = J.getViewport(J.win[0])
                        }
                        if (J.viewport) {
                            J.body = J.viewport;
                            if ((/fixed|relative|absolute/.test(J.viewport.css("position"))) == false) {
                                J.css(J.viewport, {
                                    position: "relative"
                                })
                            }
                        }
                        O.css({
                            position: X
                        });
                        if (J.zoom) {
                            J.zoom.css({
                                position: X
                            })
                        }
                        J.updateScrollBar();
                        J.body.append(O);
                        if (J.zoom) {
                            J.body.append(J.zoom)
                        }
                        if (J.railh) {
                            T.css({
                                position: X
                            });
                            J.body.append(T)
                        }
                    }
                    if (I.isios) {
                        J.css(J.win, {
                            "-webkit-tap-highlight-color": "rgba(0,0,0,0)",
                            "-webkit-touch-callout": "none"
                        })
                    }
                    if (I.isie && J.opt.disableoutline) {
                        J.win.attr("hideFocus", "true")
                    }
                    if (I.iswebkit && J.opt.disableoutline) {
                        J.win.css({
                            outline: "none"
                        })
                    }
                }
                if (J.opt.autohidemode === false) {
                    J.autohidedom = false;
                    J.rail.css({
                        opacity: J.opt.cursoropacitymax
                    });
                    if (J.railh) {
                        J.railh.css({
                            opacity: J.opt.cursoropacitymax
                        })
                    }
                } else {
                    if ((J.opt.autohidemode === true) || (J.opt.autohidemode === "leave")) {
                        J.autohidedom = e().add(J.rail);
                        if (I.isie8) {
                            J.autohidedom = J.autohidedom.add(J.cursor)
                        }
                        if (J.railh) {
                            J.autohidedom = J.autohidedom.add(J.railh)
                        }
                        if (J.railh && I.isie8) {
                            J.autohidedom = J.autohidedom.add(J.cursorh)
                        }
                    } else {
                        if (J.opt.autohidemode == "scroll") {
                            J.autohidedom = e().add(J.rail);
                            if (J.railh) {
                                J.autohidedom = J.autohidedom.add(J.railh)
                            }
                        } else {
                            if (J.opt.autohidemode == "cursor") {
                                J.autohidedom = e().add(J.cursor);
                                if (J.railh) {
                                    J.autohidedom = J.autohidedom.add(J.cursorh)
                                }
                            } else {
                                if (J.opt.autohidemode == "hidden") {
                                    J.autohidedom = false;
                                    J.hide();
                                    J.locked = false
                                }
                            }
                        }
                    }
                }
                if (I.isie9mobile) {
                    J.scrollmom = new s(J);
                    J.onmangotouch = function(af) {
                        var ai = J.getScrollTop();
                        var aj = J.getScrollLeft();
                        if ((ai == J.scrollmom.lastscrolly) && (aj == J.scrollmom.lastscrollx)) {
                            return true
                        }
                        var an = ai - J.mangotouch.sy;
                        var ad = aj - J.mangotouch.sx;
                        var ah = Math.round(Math.sqrt(Math.pow(ad, 2) + Math.pow(an, 2)));
                        if (ah == 0) {
                            return
                        }
                        var ak = (an < 0) ? -1 : 1;
                        var al = (ad < 0) ? -1 : 1;
                        var am = +new Date();
                        if (J.mangotouch.lazy) {
                            clearTimeout(J.mangotouch.lazy)
                        }
                        if (((am - J.mangotouch.tm) > 80) || (J.mangotouch.dry != ak) || (J.mangotouch.drx != al)) {
                            J.scrollmom.stop();
                            J.scrollmom.reset(aj, ai);
                            J.mangotouch.sy = ai;
                            J.mangotouch.ly = ai;
                            J.mangotouch.sx = aj;
                            J.mangotouch.lx = aj;
                            J.mangotouch.dry = ak;
                            J.mangotouch.drx = al;
                            J.mangotouch.tm = am
                        } else {
                            J.scrollmom.stop();
                            J.scrollmom.update(J.mangotouch.sx - ad, J.mangotouch.sy - an);
                            var ag = am - J.mangotouch.tm;
                            J.mangotouch.tm = am;
                            var ae = Math.max(Math.abs(J.mangotouch.ly - ai), Math.abs(J.mangotouch.lx - aj));
                            J.mangotouch.ly = ai;
                            J.mangotouch.lx = aj;
                            if (ae > 2) {
                                J.mangotouch.lazy = setTimeout(function() {
                                    J.mangotouch.lazy = false;
                                    J.mangotouch.dry = 0;
                                    J.mangotouch.drx = 0;
                                    J.mangotouch.tm = 0;
                                    J.scrollmom.doMomentum(30)
                                }, 100)
                            }
                        }
                    }
                    ;
                    var V = J.getScrollTop();
                    var ab = J.getScrollLeft();
                    J.mangotouch = {
                        sy: V,
                        ly: V,
                        dry: 0,
                        sx: ab,
                        lx: ab,
                        drx: 0,
                        lazy: false,
                        tm: 0
                    };
                    J.bind(J.docscroll, "scroll", J.onmangotouch)
                } else {
                    if (I.cantouch || J.istouchcapable || J.opt.touchbehavior || I.hasmstouch) {
                        J.scrollmom = new s(J);
                        J.ontouchstart = function(ai) {
                            if (ai.pointerType && ai.pointerType != 2) {
                                return false
                            }
                            J.hasmoving = false;
                            if (!J.locked) {
                                if (I.hasmstouch) {
                                    var ae = (ai.target) ? ai.target : false;
                                    while (ae) {
                                        var ag = e(ae).getNiceScroll();
                                        if ((ag.length > 0) && (ag[0].me == J.me)) {
                                            break
                                        }
                                        if (ag.length > 0) {
                                            return false
                                        }
                                        if ((ae.nodeName == "DIV") && (ae.id == J.id)) {
                                            break
                                        }
                                        ae = (ae.parentNode) ? ae.parentNode : false
                                    }
                                }
                                J.cancelScroll();
                                var ae = J.getTarget(ai);
                                if (ae) {
                                    var an = (/INPUT/i.test(ae.nodeName)) && (/range/i.test(ae.type));
                                    if (an) {
                                        return J.stopPropagation(ai)
                                    }
                                }
                                if (!("clientX"in ai) && ("changedTouches"in ai)) {
                                    ai.clientX = ai.changedTouches[0].clientX;
                                    ai.clientY = ai.changedTouches[0].clientY
                                }
                                if (J.forcescreen) {
                                    var ad = ai;
                                    var ai = {
                                        original: (ai.original) ? ai.original : ai
                                    };
                                    ai.clientX = ad.screenX;
                                    ai.clientY = ad.screenY
                                }
                                J.rail.drag = {
                                    x: ai.clientX,
                                    y: ai.clientY,
                                    sx: J.scroll.x,
                                    sy: J.scroll.y,
                                    st: J.getScrollTop(),
                                    sl: J.getScrollLeft(),
                                    pt: 2,
                                    dl: false
                                };
                                if (J.ispage || !J.opt.directionlockdeadzone) {
                                    J.rail.drag.dl = "f"
                                } else {
                                    var am = {
                                        w: e(window).width(),
                                        h: e(window).height()
                                    };
                                    var aj = {
                                        w: Math.max(document.body.scrollWidth, document.documentElement.scrollWidth),
                                        h: Math.max(document.body.scrollHeight, document.documentElement.scrollHeight)
                                    };
                                    var af = Math.max(0, aj.h - am.h);
                                    var al = Math.max(0, aj.w - am.w);
                                    if (!J.rail.scrollable && J.railh.scrollable) {
                                        J.rail.drag.ck = (af > 0) ? "v" : false
                                    } else {
                                        if (J.rail.scrollable && !J.railh.scrollable) {
                                            J.rail.drag.ck = (al > 0) ? "h" : false
                                        } else {
                                            J.rail.drag.ck = false
                                        }
                                    }
                                    if (!J.rail.drag.ck) {
                                        J.rail.drag.dl = "f"
                                    }
                                }
                                if (J.opt.touchbehavior && J.isiframe && I.isie) {
                                    var ak = J.win.position();
                                    J.rail.drag.x += ak.left;
                                    J.rail.drag.y += ak.top
                                }
                                J.hasmoving = false;
                                J.lastmouseup = false;
                                J.scrollmom.reset(ai.clientX, ai.clientY);
                                if (!I.cantouch && !this.istouchcapable && !I.hasmstouch) {
                                    var ah = (ae) ? /INPUT|SELECT|TEXTAREA/i.test(ae.nodeName) : false;
                                    if (!ah) {
                                        if (!J.ispage && I.hasmousecapture) {
                                            ae.setCapture()
                                        }
                                        if (J.opt.touchbehavior) {
                                            if (ae.onclick && !(ae._onclick || false)) {
                                                ae._onclick = ae.onclick;
                                                ae.onclick = function(ao) {
                                                    if (J.hasmoving) {
                                                        return false
                                                    }
                                                    ae._onclick.call(this, ao)
                                                }
                                            }
                                            return J.cancelEvent(ai)
                                        }
                                        return J.stopPropagation(ai)
                                    }
                                    if (/SUBMIT|CANCEL|BUTTON/i.test(e(ae).attr("type"))) {
                                        pc = {
                                            tg: ae,
                                            click: false
                                        };
                                        J.preventclick = pc
                                    }
                                }
                            }
                        }
                        ;
                        J.ontouchend = function(ad) {
                            if (ad.pointerType && ad.pointerType != 2) {
                                return false
                            }
                            if (J.rail.drag && (J.rail.drag.pt == 2)) {
                                J.scrollmom.doMomentum();
                                J.rail.drag = false;
                                if (J.hasmoving) {
                                    J.lastmouseup = true;
                                    J.hideCursor();
                                    if (I.hasmousecapture) {
                                        document.releaseCapture()
                                    }
                                    if (!I.cantouch) {
                                        return J.cancelEvent(ad)
                                    }
                                }
                            }
                        }
                        ;
                        var Z = (J.opt.touchbehavior && J.isiframe && !I.hasmousecapture);
                        J.ontouchmove = function(al, ah) {
                            if (al.pointerType && al.pointerType != 2) {
                                return false
                            }
                            if (J.rail.drag && (J.rail.drag.pt == 2)) {
                                if (I.cantouch && (typeof al.original == "undefined")) {
                                    return true
                                }
                                J.hasmoving = true;
                                if (J.preventclick && !J.preventclick.click) {
                                    J.preventclick.click = J.preventclick.tg.onclick || false;
                                    J.preventclick.tg.onclick = J.onpreventclick
                                }
                                var am = e.extend({
                                    original: al
                                }, al);
                                al = am;
                                if (("changedTouches"in al)) {
                                    al.clientX = al.changedTouches[0].clientX;
                                    al.clientY = al.changedTouches[0].clientY
                                }
                                if (J.forcescreen) {
                                    var ae = al;
                                    var al = {
                                        original: (al.original) ? al.original : al
                                    };
                                    al.clientX = ae.screenX;
                                    al.clientY = ae.screenY
                                }
                                var ai = ofy = 0;
                                if (Z && !ah) {
                                    var an = J.win.position();
                                    ai = -an.left;
                                    ofy = -an.top
                                }
                                var af = al.clientY + ofy;
                                var ap = (af - J.rail.drag.y);
                                var ag = al.clientX + ai;
                                var aq = (ag - J.rail.drag.x);
                                var aj = J.rail.drag.st - ap;
                                if (J.ishwscroll && J.opt.bouncescroll) {
                                    if (aj < 0) {
                                        aj = Math.round(aj / 2)
                                    } else {
                                        if (aj > J.page.maxh) {
                                            aj = J.page.maxh + Math.round((aj - J.page.maxh) / 2)
                                        }
                                    }
                                } else {
                                    if (aj < 0) {
                                        aj = 0;
                                        af = 0
                                    }
                                    if (aj > J.page.maxh) {
                                        aj = J.page.maxh;
                                        af = 0
                                    }
                                }
                                if (J.railh && J.railh.scrollable) {
                                    var ak = J.rail.drag.sl - aq;
                                    if (J.ishwscroll && J.opt.bouncescroll) {
                                        if (ak < 0) {
                                            ak = Math.round(ak / 2)
                                        } else {
                                            if (ak > J.page.maxw) {
                                                ak = J.page.maxw + Math.round((ak - J.page.maxw) / 2)
                                            }
                                        }
                                    } else {
                                        if (ak < 0) {
                                            ak = 0;
                                            ag = 0
                                        }
                                        if (ak > J.page.maxw) {
                                            ak = J.page.maxw;
                                            ag = 0
                                        }
                                    }
                                }
                                var ao = false;
                                if (J.rail.drag.dl) {
                                    ao = true;
                                    if (J.rail.drag.dl == "v") {
                                        ak = J.rail.drag.sl
                                    } else {
                                        if (J.rail.drag.dl == "h") {
                                            aj = J.rail.drag.st
                                        }
                                    }
                                } else {
                                    var at = Math.abs(ap);
                                    var ad = Math.abs(aq);
                                    var ar = J.opt.directionlockdeadzone;
                                    if (J.rail.drag.ck == "v") {
                                        if (at > ar && (ad <= (at * 0.3))) {
                                            J.rail.drag = false;
                                            return true
                                        } else {
                                            if (ad > ar) {
                                                J.rail.drag.dl = "f";
                                                e("body").scrollTop(e("body").scrollTop())
                                            }
                                        }
                                    } else {
                                        if (J.rail.drag.ck == "h") {
                                            if (ad > ar && (at <= (ad * 0.3))) {
                                                J.rail.drag = false;
                                                return true
                                            } else {
                                                if (at > ar) {
                                                    J.rail.drag.dl = "f";
                                                    e("body").scrollLeft(e("body").scrollLeft())
                                                }
                                            }
                                        }
                                    }
                                }
                                J.synched("touchmove", function() {
                                    if (J.rail.drag && (J.rail.drag.pt == 2)) {
                                        if (J.prepareTransition) {
                                            J.prepareTransition(0)
                                        }
                                        if (J.rail.scrollable) {
                                            J.setScrollTop(aj)
                                        }
                                        J.scrollmom.update(ag, af);
                                        if (J.railh && J.railh.scrollable) {
                                            J.setScrollLeft(ak);
                                            J.showCursor(aj, ak)
                                        } else {
                                            J.showCursor(aj)
                                        }
                                        if (I.isie10) {
                                            document.selection.clear()
                                        }
                                    }
                                });
                                if (I.ischrome && J.istouchcapable) {
                                    ao = false
                                }
                                if (ao) {
                                    return J.cancelEvent(al)
                                }
                            }
                        }
                    }
                    J.onmousedown = function(af, ad) {
                        if (J.rail.drag && J.rail.drag.pt != 1) {
                            return
                        }
                        if (J.locked) {
                            return J.cancelEvent(af)
                        }
                        J.cancelScroll();
                        J.rail.drag = {
                            x: af.clientX,
                            y: af.clientY,
                            sx: J.scroll.x,
                            sy: J.scroll.y,
                            pt: 1,
                            hr: (!!ad)
                        };
                        var ae = J.getTarget(af);
                        if (!J.ispage && I.hasmousecapture) {
                            ae.setCapture()
                        }
                        if (J.isiframe && !I.hasmousecapture) {
                            J.saved.csspointerevents = J.doc.css("pointer-events");
                            J.css(J.doc, {
                                "pointer-events": "none"
                            })
                        }
                        J.hasmoving = false;
                        return J.cancelEvent(af)
                    }
                    ;
                    J.onmouseup = function(ad) {
                        if (J.rail.drag) {
                            if (I.hasmousecapture) {
                                document.releaseCapture()
                            }
                            if (J.isiframe && !I.hasmousecapture) {
                                J.doc.css("pointer-events", J.saved.csspointerevents)
                            }
                            if (J.rail.drag.pt != 1) {
                                return
                            }
                            J.rail.drag = false;
                            if (J.hasmoving) {
                                J.triggerScrollEnd()
                            }
                            return J.cancelEvent(ad)
                        }
                    }
                    ;
                    J.onmousemove = function(ae) {
                        if (J.rail.drag) {
                            if (J.rail.drag.pt != 1) {
                                return
                            }
                            if (I.ischrome && ae.which == 0) {
                                return J.onmouseup(ae)
                            }
                            J.cursorfreezed = true;
                            J.hasmoving = true;
                            if (J.rail.drag.hr) {
                                J.scroll.x = J.rail.drag.sx + (ae.clientX - J.rail.drag.x);
                                if (J.scroll.x < 0) {
                                    J.scroll.x = 0
                                }
                                var af = J.scrollvaluemaxw;
                                if (J.scroll.x > af) {
                                    J.scroll.x = af
                                }
                            } else {
                                J.scroll.y = J.rail.drag.sy + (ae.clientY - J.rail.drag.y);
                                if (J.scroll.y < 0) {
                                    J.scroll.y = 0
                                }
                                var ad = J.scrollvaluemax;
                                if (J.scroll.y > ad) {
                                    J.scroll.y = ad
                                }
                            }
                            J.synched("mousemove", function() {
                                if (J.rail.drag && (J.rail.drag.pt == 1)) {
                                    J.showCursor();
                                    if (J.rail.drag.hr) {
                                        J.doScrollLeft(Math.round(J.scroll.x * J.scrollratio.x), J.opt.cursordragspeed)
                                    } else {
                                        J.doScrollTop(Math.round(J.scroll.y * J.scrollratio.y), J.opt.cursordragspeed)
                                    }
                                }
                            });
                            return J.cancelEvent(ae)
                        }
                    }
                    ;
                    if (I.cantouch || J.opt.touchbehavior) {
                        J.onpreventclick = function(ad) {
                            if (J.preventclick) {
                                J.preventclick.tg.onclick = J.preventclick.click;
                                J.preventclick = false;
                                return J.cancelEvent(ad)
                            }
                        }
                        ;
                        J.bind(J.win, "mousedown", J.ontouchstart);
                        J.onclick = (I.isios) ? false : function(ad) {
                            if (J.lastmouseup) {
                                J.lastmouseup = false;
                                return J.cancelEvent(ad)
                            } else {
                                return true
                            }
                        }
                        ;
                        if (J.opt.grabcursorenabled && I.cursorgrabvalue) {
                            J.css((J.ispage) ? J.doc : J.win, {
                                cursor: I.cursorgrabvalue
                            });
                            J.css(J.rail, {
                                cursor: I.cursorgrabvalue
                            })
                        }
                    } else {
                        function N(af) {
                            if (!J.selectiondrag) {
                                return
                            }
                            if (af) {
                                var ae = J.win.outerHeight();
                                var ag = (af.pageY - J.selectiondrag.top);
                                if (ag > 0 && ag < ae) {
                                    ag = 0
                                }
                                if (ag >= ae) {
                                    ag -= ae
                                }
                                J.selectiondrag.df = ag
                            }
                            if (J.selectiondrag.df == 0) {
                                return
                            }
                            var ad = -Math.floor(J.selectiondrag.df / 6) * 2;
                            J.doScrollBy(ad);
                            J.debounced("doselectionscroll", function() {
                                N()
                            }, 50)
                        }
                        if ("getSelection"in document) {
                            J.hasTextSelected = function() {
                                return (document.getSelection().rangeCount > 0)
                            }
                        } else {
                            if ("selection"in document) {
                                J.hasTextSelected = function() {
                                    return (document.selection.type != "None")
                                }
                            } else {
                                J.hasTextSelected = function() {
                                    return false
                                }
                            }
                        }
                        J.onselectionstart = function(ad) {
                            if (J.ispage) {
                                return
                            }
                            J.selectiondrag = J.win.offset()
                        }
                        ;
                        J.onselectionend = function(ad) {
                            J.selectiondrag = false
                        }
                        ;
                        J.onselectiondrag = function(ad) {
                            if (!J.selectiondrag) {
                                return
                            }
                            if (J.hasTextSelected()) {
                                J.debounced("selectionscroll", function() {
                                    N(ad)
                                }, 250)
                            }
                        }
                    }
                    if (I.hasmstouch) {
                        J.css(J.rail, {
                            "-ms-touch-action": "none"
                        });
                        J.css(J.cursor, {
                            "-ms-touch-action": "none"
                        });
                        J.bind(J.win, "MSPointerDown", J.ontouchstart);
                        J.bind(document, "MSPointerUp", J.ontouchend);
                        J.bind(document, "MSPointerMove", J.ontouchmove);
                        J.bind(J.cursor, "MSGestureHold", function(ad) {
                            ad.preventDefault()
                        });
                        J.bind(J.cursor, "contextmenu", function(ad) {
                            ad.preventDefault()
                        })
                    }
                    if (this.istouchcapable) {
                        J.bind(J.win, "touchstart", J.ontouchstart);
                        J.bind(document, "touchend", J.ontouchend);
                        J.bind(document, "touchcancel", J.ontouchend);
                        J.bind(document, "touchmove", J.ontouchmove)
                    }
                    J.bind(J.cursor, "mousedown", J.onmousedown);
                    J.bind(J.cursor, "mouseup", J.onmouseup);
                    if (J.railh) {
                        J.bind(J.cursorh, "mousedown", function(ad) {
                            J.onmousedown(ad, true)
                        });
                        J.bind(J.cursorh, "mouseup", J.onmouseup)
                    }
                    if (J.opt.cursordragontouch || !I.cantouch && !J.opt.touchbehavior) {
                        J.rail.css({
                            cursor: "default"
                        });
                        J.railh && J.railh.css({
                            cursor: "default"
                        });
                        J.jqbind(J.rail, "mouseenter", function() {
                            if (!J.win.is(":visible")) {
                                return false
                            }
                            if (J.canshowonmouseevent) {
                                J.showCursor()
                            }
                            J.rail.active = true
                        });
                        J.jqbind(J.rail, "mouseleave", function() {
                            J.rail.active = false;
                            if (!J.rail.drag) {
                                J.hideCursor()
                            }
                        });
                        if (J.opt.sensitiverail) {
                            J.bind(J.rail, "click", function(ad) {
                                J.doRailClick(ad, false, false)
                            });
                            J.bind(J.rail, "dblclick", function(ad) {
                                J.doRailClick(ad, true, false)
                            });
                            J.bind(J.cursor, "click", function(ad) {
                                J.cancelEvent(ad)
                            });
                            J.bind(J.cursor, "dblclick", function(ad) {
                                J.cancelEvent(ad)
                            })
                        }
                        if (J.railh) {
                            J.jqbind(J.railh, "mouseenter", function() {
                                if (!J.win.is(":visible")) {
                                    return false
                                }
                                if (J.canshowonmouseevent) {
                                    J.showCursor()
                                }
                                J.rail.active = true
                            });
                            J.jqbind(J.railh, "mouseleave", function() {
                                J.rail.active = false;
                                if (!J.rail.drag) {
                                    J.hideCursor()
                                }
                            });
                            if (J.opt.sensitiverail) {
                                J.bind(J.railh, "click", function(ad) {
                                    J.doRailClick(ad, false, true)
                                });
                                J.bind(J.railh, "dblclick", function(ad) {
                                    J.doRailClick(ad, true, true)
                                });
                                J.bind(J.cursorh, "click", function(ad) {
                                    J.cancelEvent(ad)
                                });
                                J.bind(J.cursorh, "dblclick", function(ad) {
                                    J.cancelEvent(ad)
                                })
                            }
                        }
                    }
                    if (!I.cantouch && !J.opt.touchbehavior) {
                        J.bind((I.hasmousecapture) ? J.win : document, "mouseup", J.onmouseup);
                        J.bind(document, "mousemove", J.onmousemove);
                        if (J.onclick) {
                            J.bind(document, "click", J.onclick)
                        }
                        if (!J.ispage && J.opt.enablescrollonselection) {
                            J.bind(J.win[0], "mousedown", J.onselectionstart);
                            J.bind(document, "mouseup", J.onselectionend);
                            J.bind(J.cursor, "mouseup", J.onselectionend);
                            if (J.cursorh) {
                                J.bind(J.cursorh, "mouseup", J.onselectionend)
                            }
                            J.bind(document, "mousemove", J.onselectiondrag)
                        }
                        if (J.zoom) {
                            J.jqbind(J.zoom, "mouseenter", function() {
                                if (J.canshowonmouseevent) {
                                    J.showCursor()
                                }
                                J.rail.active = true
                            });
                            J.jqbind(J.zoom, "mouseleave", function() {
                                J.rail.active = false;
                                if (!J.rail.drag) {
                                    J.hideCursor()
                                }
                            })
                        }
                    } else {
                        J.bind((I.hasmousecapture) ? J.win : document, "mouseup", J.ontouchend);
                        J.bind(document, "mousemove", J.ontouchmove);
                        if (J.onclick) {
                            J.bind(document, "click", J.onclick)
                        }
                        if (J.opt.cursordragontouch) {
                            J.bind(J.cursor, "mousedown", J.onmousedown);
                            J.bind(J.cursor, "mousemove", J.onmousemove);
                            J.cursorh && J.bind(J.cursorh, "mousedown", function(ad) {
                                J.onmousedown(ad, true)
                            });
                            J.cursorh && J.bind(J.cursorh, "mousemove", J.onmousemove)
                        }
                    }
                    if (J.opt.enablemousewheel) {
                        if (!J.isiframe) {
                            J.bind((I.isie && J.ispage) ? document : J.win, "mousewheel", J.onmousewheel)
                        }
                        J.bind(J.rail, "mousewheel", J.onmousewheel);
                        if (J.railh) {
                            J.bind(J.railh, "mousewheel", J.onmousewheelhr)
                        }
                    }
                    if (!J.ispage && !I.cantouch && !(/HTML|^BODY/.test(J.win[0].nodeName))) {
                        if (!J.win.attr("tabindex")) {
                            J.win.attr({
                                tabindex: i++
                            })
                        }
                        J.jqbind(J.win, "focus", function(ad) {
                            f = (J.getTarget(ad)).id || true;
                            J.hasfocus = true;
                            if (J.canshowonmouseevent) {
                                J.noticeCursor()
                            }
                        });
                        J.jqbind(J.win, "blur", function(ad) {
                            f = false;
                            J.hasfocus = false
                        });
                        J.jqbind(J.win, "mouseenter", function(ad) {
                            k = (J.getTarget(ad)).id || true;
                            J.hasmousefocus = true;
                            if (J.canshowonmouseevent) {
                                J.noticeCursor()
                            }
                        });
                        J.jqbind(J.win, "mouseleave", function() {
                            k = false;
                            J.hasmousefocus = false;
                            if (!J.rail.drag) {
                                J.hideCursor()
                            }
                        })
                    }
                }
                J.onkeypress = function(ai) {
                    if (J.locked && J.page.maxh == 0) {
                        return true
                    }
                    ai = (ai) ? ai : window.e;
                    var ah = J.getTarget(ai);
                    if (ah && /INPUT|TEXTAREA|SELECT|OPTION/.test(ah.nodeName)) {
                        var aj = ah.getAttribute("type") || ah.type || false;
                        if ((!aj) || !(/submit|button|cancel/i.tp)) {
                            return true
                        }
                    }
                    if (e(ah).attr("contenteditable")) {
                        return true
                    }
                    if (J.hasfocus || (J.hasmousefocus && !f) || (J.ispage && !f && !k)) {
                        var af = ai.keyCode;
                        if (J.locked && af != 27) {
                            return J.cancelEvent(ai)
                        }
                        var ag = ai.ctrlKey || false;
                        var ad = ai.shiftKey || false;
                        var ae = false;
                        switch (af) {
                            case 38:
                            case 63233:
                                J.doScrollBy(24 * 3);
                                ae = true;
                                break;
                            case 40:
                            case 63235:
                                J.doScrollBy(-24 * 3);
                                ae = true;
                                break;
                            case 37:
                            case 63232:
                                if (J.railh) {
                                    (ag) ? J.doScrollLeft(0) : J.doScrollLeftBy(24 * 3);
                                    ae = true
                                }
                                break;
                            case 39:
                            case 63234:
                                if (J.railh) {
                                    (ag) ? J.doScrollLeft(J.page.maxw) : J.doScrollLeftBy(-24 * 3);
                                    ae = true
                                }
                                break;
                            case 33:
                            case 63276:
                                J.doScrollBy(J.view.h);
                                ae = true;
                                break;
                            case 34:
                            case 63277:
                                J.doScrollBy(-J.view.h);
                                ae = true;
                                break;
                            case 36:
                            case 63273:
                                (J.railh && ag) ? J.doScrollPos(0, 0) : J.doScrollTo(0);
                                ae = true;
                                break;
                            case 35:
                            case 63275:
                                (J.railh && ag) ? J.doScrollPos(J.page.maxw, J.page.maxh) : J.doScrollTo(J.page.maxh);
                                ae = true;
                                break;
                            case 32:
                                if (J.opt.spacebarenabled) {
                                    (ad) ? J.doScrollBy(J.view.h) : J.doScrollBy(-J.view.h);
                                    ae = true
                                }
                                break;
                            case 27:
                                if (J.zoomactive) {
                                    J.doZoom();
                                    ae = true
                                }
                                break
                        }
                        if (ae) {
                            return J.cancelEvent(ai)
                        }
                    }
                }
                ;
                if (J.opt.enablekeyboard) {
                    J.bind(document, (I.isopera && !I.isopera12) ? "keypress" : "keydown", J.onkeypress)
                }
                J.bind(document, "keydown", function(ae) {
                    var ad = ae.ctrlKey || false;
                    if (ad) {
                        J.wheelprevented = true
                    }
                });
                J.bind(document, "keyup", function(ae) {
                    var ad = ae.ctrlKey || false;
                    if (!ad) {
                        J.wheelprevented = false
                    }
                });
                J.bind(window, "resize", J.lazyResize);
                J.bind(window, "orientationchange", J.lazyResize);
                J.bind(window, "load", J.lazyResize);
                if (I.ischrome && !J.ispage && !J.haswrapper) {
                    var Y = J.win.attr("style");
                    var ac = parseFloat(J.win.css("width")) + 1;
                    J.win.css("width", ac);
                    J.synched("chromefix", function() {
                        J.win.attr("style", Y)
                    })
                }
                J.onAttributeChange = function(ad) {
                    J.lazyResize(250)
                }
                ;
                if (!J.ispage && !J.haswrapper) {
                    if (p !== false) {
                        J.observer = new p(function(ad) {
                                ad.forEach(J.onAttributeChange)
                            }
                        );
                        J.observer.observe(J.win[0], {
                            childList: true,
                            characterData: false,
                            attributes: true,
                            subtree: false
                        });
                        J.observerremover = new p(function(ad) {
                                ad.forEach(function(af) {
                                    if (af.removedNodes.length > 0) {
                                        for (var ae in af.removedNodes) {
                                            if (af.removedNodes[ae] == J.win[0]) {
                                                return J.remove()
                                            }
                                        }
                                    }
                                })
                            }
                        );
                        J.observerremover.observe(J.win[0].parentNode, {
                            childList: true,
                            characterData: false,
                            attributes: false,
                            subtree: false
                        })
                    } else {
                        J.bind(J.win, (I.isie && !I.isie9) ? "propertychange" : "DOMAttrModified", J.onAttributeChange);
                        if (I.isie9) {
                            J.win[0].attachEvent("onpropertychange", J.onAttributeChange)
                        }
                        J.bind(J.win, "DOMNodeRemoved", function(ad) {
                            if (ad.target == J.win[0]) {
                                J.remove()
                            }
                        })
                    }
                }
                if (!J.ispage && J.opt.boxzoom) {
                    J.bind(window, "resize", J.resizeZoom)
                }
                if (J.istextarea) {
                    J.bind(J.win, "mouseup", J.lazyResize)
                }
                J.lazyResize(30)
            }
            if (this.doc[0].nodeName == "IFRAME") {
                function S(ag) {
                    J.iframexd = false;
                    try {
                        var af = "contentDocument"in this ? this.contentDocument : this.contentWindow.document;
                        var ad = af.domain
                    } catch (ag) {
                        J.iframexd = true;
                        af = false
                    }
                    if (J.iframexd) {
                        if ("console"in window) {
                            console.log("NiceScroll error: policy restriced iframe")
                        }
                        return true
                    }
                    J.forcescreen = true;
                    if (J.isiframe) {
                        J.iframe = {
                            doc: e(af),
                            html: J.doc.contents().find("html")[0],
                            body: J.doc.contents().find("body")[0]
                        };
                        J.getContentSize = function() {
                            return {
                                w: Math.max(J.iframe.html.scrollWidth, J.iframe.body.scrollWidth),
                                h: Math.max(J.iframe.html.scrollHeight, J.iframe.body.scrollHeight)
                            }
                        }
                        ;
                        J.docscroll = e(J.iframe.body)
                    }
                    if (!I.isios && J.opt.iframeautoresize && !J.isiframe) {
                        J.win.scrollTop(0);
                        J.doc.height("");
                        var ae = Math.max(af.getElementsByTagName("html")[0].scrollHeight, af.body.scrollHeight);
                        J.doc.height(ae)
                    }
                    J.lazyResize(30);
                    if (I.isie7) {
                        J.css(e(J.iframe.html), {
                            "overflow-y": "hidden"
                        })
                    }
                    J.css(e(J.iframe.body), {
                        "overflow-y": "hidden"
                    });
                    if (I.isios && J.haswrapper) {
                        J.css(e(af.body), {
                            "-webkit-transform": "translate3d(0,0,0)"
                        })
                    }
                    if ("contentWindow"in this) {
                        J.bind(this.contentWindow, "scroll", J.onscroll)
                    } else {
                        J.bind(af, "scroll", J.onscroll)
                    }
                    if (J.opt.enablemousewheel) {
                        J.bind(af, "mousewheel", J.onmousewheel)
                    }
                    if (J.opt.enablekeyboard) {
                        J.bind(af, (I.isopera) ? "keypress" : "keydown", J.onkeypress)
                    }
                    if (I.cantouch || J.opt.touchbehavior) {
                        J.bind(af, "mousedown", J.ontouchstart);
                        J.bind(af, "mousemove", function(ah) {
                            J.ontouchmove(ah, true)
                        });
                        if (J.opt.grabcursorenabled && I.cursorgrabvalue) {
                            J.css(e(af.body), {
                                cursor: I.cursorgrabvalue
                            })
                        }
                    }
                    J.bind(af, "mouseup", J.ontouchend);
                    if (J.zoom) {
                        if (J.opt.dblclickzoom) {
                            J.bind(af, "dblclick", J.doZoom)
                        }
                        if (J.ongesturezoom) {
                            J.bind(af, "gestureend", J.ongesturezoom)
                        }
                    }
                }
                if (this.doc[0].readyState && this.doc[0].readyState == "complete") {
                    setTimeout(function() {
                        S.call(J.doc[0], false)
                    }, 500)
                }
                J.bind(this.doc, "load", S)
            }
        }
        ;
        this.showCursor = function(L, M) {
            if (J.cursortimeout) {
                clearTimeout(J.cursortimeout);
                J.cursortimeout = 0
            }
            if (!J.rail) {
                return
            }
            if (J.autohidedom) {
                J.autohidedom.stop().css({
                    opacity: J.opt.cursoropacitymax
                });
                J.cursoractive = true
            }
            if (!J.rail.drag || J.rail.drag.pt != 1) {
                if ((typeof L != "undefined") && (L !== false)) {
                    J.scroll.y = Math.round(L * 1 / J.scrollratio.y)
                }
                if (typeof M != "undefined") {
                    J.scroll.x = Math.round(M * 1 / J.scrollratio.x)
                }
            }
            J.cursor.css({
                height: J.cursorheight,
                top: J.scroll.y
            });
            if (J.cursorh) {
                (!J.rail.align && J.rail.visibility) ? J.cursorh.css({
                    width: J.cursorwidth,
                    left: J.scroll.x + J.rail.width
                }) : J.cursorh.css({
                    width: J.cursorwidth,
                    left: J.scroll.x
                });
                J.cursoractive = true
            }
            if (J.zoom) {
                J.zoom.stop().css({
                    opacity: J.opt.cursoropacitymax
                })
            }
        }
        ;
        this.hideCursor = function(L) {
            if (J.cursortimeout) {
                return
            }
            if (!J.rail) {
                return
            }
            if (!J.autohidedom) {
                return
            }
            if (J.hasmousefocus && J.opt.autohidemode == "leave") {
                return
            }
            J.cursortimeout = setTimeout(function() {
                if (!J.rail.active || !J.showonmouseevent) {
                    J.autohidedom.stop().animate({
                        opacity: J.opt.cursoropacitymin
                    });
                    if (J.zoom) {
                        J.zoom.stop().animate({
                            opacity: J.opt.cursoropacitymin
                        })
                    }
                    J.cursoractive = false
                }
                J.cursortimeout = 0
            }, L || J.opt.hidecursordelay)
        }
        ;
        this.noticeCursor = function(L, M, N) {
            J.showCursor(M, N);
            if (!J.rail.active) {
                J.hideCursor(L)
            }
        }
        ;
        this.getContentSize = (J.ispage) ? function() {
                return {
                    w: Math.max(document.body.scrollWidth, document.documentElement.scrollWidth),
                    h: Math.max(document.body.scrollHeight, document.documentElement.scrollHeight)
                }
            }
            : (J.haswrapper) ? function() {
                    return {
                        w: J.doc.outerWidth() + parseInt(J.win.css("paddingLeft")) + parseInt(J.win.css("paddingRight")),
                        h: J.doc.outerHeight() + parseInt(J.win.css("paddingTop")) + parseInt(J.win.css("paddingBottom"))
                    }
                }
                : function() {
                    return {
                        w: J.docscroll[0].scrollWidth,
                        h: J.docscroll[0].scrollHeight
                    }
                }
        ;
        this.onResize = function(P, N) {
            if (!J || !J.win) {
                return false
            }
            if (!J.haswrapper && !J.ispage) {
                if (J.win.css("display") == "none") {
                    if (J.visibility) {
                        J.hideRail().hideRailHr()
                    }
                    return false
                } else {
                    if (!J.hidden && !J.visibility) {
                        J.showRail().showRailHr()
                    }
                }
            }
            var S = J.page.maxh;
            var M = J.page.maxw;
            var O = {
                h: J.view.h,
                w: J.view.w
            };
            J.view = {
                w: (J.ispage) ? J.win.width() : parseInt(J.win[0].clientWidth),
                h: (J.ispage) ? J.win.height() : parseInt(J.win[0].clientHeight)
            };
            J.page = (N) ? N : J.getContentSize();
            J.page.maxh = Math.max(0, J.page.h - J.view.h);
            J.page.maxw = Math.max(0, J.page.w - J.view.w);
            if ((J.page.maxh == S) && (J.page.maxw == M) && (J.view.w == O.w)) {
                if (!J.ispage) {
                    var R = J.win.offset();
                    if (J.lastposition) {
                        var L = J.lastposition;
                        if ((L.top == R.top) && (L.left == R.left)) {
                            return J
                        }
                    }
                    J.lastposition = R
                } else {
                    return J
                }
            }
            if (J.page.maxh == 0) {
                J.hideRail();
                J.scrollvaluemax = 0;
                J.scroll.y = 0;
                J.scrollratio.y = 0;
                J.cursorheight = 0;
                J.setScrollTop(0);
                J.rail.scrollable = false
            } else {
                J.rail.scrollable = true
            }
            if (J.page.maxw == 0) {
                J.hideRailHr();
                J.scrollvaluemaxw = 0;
                J.scroll.x = 0;
                J.scrollratio.x = 0;
                J.cursorwidth = 0;
                J.setScrollLeft(0);
                J.railh.scrollable = false
            } else {
                J.railh.scrollable = true
            }
            J.locked = (J.page.maxh == 0) && (J.page.maxw == 0);
            if (J.locked) {
                if (!J.ispage) {
                    J.updateScrollBar(J.view)
                }
                return false
            }
            if (!J.hidden && !J.visibility) {
                J.showRail().showRailHr()
            } else {
                if (!J.hidden && !J.railh.visibility) {
                    J.showRailHr()
                }
            }
            if (J.istextarea && J.win.css("resize") && J.win.css("resize") != "none") {
                J.view.h -= 20
            }
            J.cursorheight = Math.min(J.view.h, Math.round(J.view.h * (J.view.h / J.page.h)));
            J.cursorheight = (J.opt.cursorfixedheight) ? J.opt.cursorfixedheight : Math.max(J.opt.cursorminheight, J.cursorheight);
            J.cursorwidth = Math.min(J.view.w, Math.round(J.view.w * (J.view.w / J.page.w)));
            J.cursorwidth = (J.opt.cursorfixedheight) ? J.opt.cursorfixedheight : Math.max(J.opt.cursorminheight, J.cursorwidth);
            J.scrollvaluemax = J.view.h - J.cursorheight - J.cursor.hborder;
            if (J.railh) {
                J.railh.width = (J.page.maxh > 0) ? (J.view.w - J.rail.width) : J.view.w;
                J.scrollvaluemaxw = J.railh.width - J.cursorwidth - J.cursorh.wborder
            }
            if (!J.ispage) {
                J.updateScrollBar(J.view)
            }
            J.scrollratio = {
                x: (J.page.maxw / J.scrollvaluemaxw),
                y: (J.page.maxh / J.scrollvaluemax)
            };
            var Q = J.getScrollTop();
            if (Q > J.page.maxh) {
                J.doScrollTop(J.page.maxh)
            } else {
                J.scroll.y = Math.round(J.getScrollTop() * (1 / J.scrollratio.y));
                J.scroll.x = Math.round(J.getScrollLeft() * (1 / J.scrollratio.x));
                if (J.cursoractive) {
                    J.noticeCursor()
                }
            }
            if (J.scroll.y && (J.getScrollTop() == 0)) {
                J.doScrollTo(Math.floor(J.scroll.y * J.scrollratio.y))
            }
            return J
        }
        ;
        this.resize = J.onResize;
        this.lazyResize = function(L) {
            L = (isNaN(L)) ? 30 : L;
            J.delayed("resize", J.resize, L);
            return J
        }
        ;
        function v(O, M, N, L) {
            J._bind(O, M, function(Q) {
                var Q = (Q) ? Q : window.event;
                var P = {
                    original: Q,
                    target: Q.target || Q.srcElement,
                    type: "wheel",
                    deltaMode: Q.type == "MozMousePixelScroll" ? 0 : 1,
                    deltaX: 0,
                    deltaZ: 0,
                    preventDefault: function() {
                        Q.preventDefault ? Q.preventDefault() : Q.returnValue = false;
                        return false
                    },
                    stopImmediatePropagation: function() {
                        (Q.stopImmediatePropagation) ? Q.stopImmediatePropagation() : Q.cancelBubble = true
                    }
                };
                if (M == "mousewheel") {
                    P.deltaY = -1 / 40 * Q.wheelDelta;
                    Q.wheelDeltaX && (P.deltaX = -1 / 40 * Q.wheelDeltaX)
                } else {
                    P.deltaY = Q.detail
                }
                return N.call(O, P)
            }, L)
        }
        this._bind = function(O, M, N, L) {
            J.events.push({
                e: O,
                n: M,
                f: N,
                b: L,
                q: false
            });
            if (O.addEventListener) {
                O.addEventListener(M, N, L || false)
            } else {
                if (O.attachEvent) {
                    O.attachEvent("on" + M, N)
                } else {
                    O["on" + M] = N
                }
            }
        }
        ;
        this.jqbind = function(N, L, M) {
            J.events.push({
                e: N,
                n: L,
                f: M,
                q: true
            });
            e(N).bind(L, M)
        }
        ;
        this.bind = function(R, N, Q, L) {
            var P = ("jquery"in R) ? R[0] : R;
            if (N == "mousewheel") {
                if ("onwheel"in J.win) {
                    J._bind(P, "wheel", Q, L || false)
                } else {
                    var M = (typeof document.onmousewheel != "undefined") ? "mousewheel" : "DOMMouseScroll";
                    v(P, M, Q, L || false);
                    if (M == "DOMMouseScroll") {
                        v(P, "MozMousePixelScroll", Q, L || false)
                    }
                }
            } else {
                if (P.addEventListener) {
                    if (I.cantouch && /mouseup|mousedown|mousemove/.test(N)) {
                        var O = (N == "mousedown") ? "touchstart" : (N == "mouseup") ? "touchend" : "touchmove";
                        J._bind(P, O, function(T) {
                            if (T.touches) {
                                if (T.touches.length < 2) {
                                    var S = (T.touches.length) ? T.touches[0] : T;
                                    S.original = T;
                                    Q.call(this, S)
                                }
                            } else {
                                if (T.changedTouches) {
                                    var S = T.changedTouches[0];
                                    S.original = T;
                                    Q.call(this, S)
                                }
                            }
                        }, L || false)
                    }
                    J._bind(P, N, Q, L || false);
                    if (I.cantouch && N == "mouseup") {
                        J._bind(P, "touchcancel", Q, L || false)
                    }
                } else {
                    J._bind(P, N, function(S) {
                        S = S || window.event || false;
                        if (S) {
                            if (S.srcElement) {
                                S.target = S.srcElement
                            }
                        }
                        if (!("pageY"in S)) {
                            S.pageX = S.clientX + document.documentElement.scrollLeft;
                            S.pageY = S.clientY + document.documentElement.scrollTop
                        }
                        return ((Q.call(P, S) === false) || L === false) ? J.cancelEvent(S) : true
                    })
                }
            }
        }
        ;
        this._unbind = function(N, L, M, O) {
            if (N.removeEventListener) {
                N.removeEventListener(L, M, O)
            } else {
                if (N.detachEvent) {
                    N.detachEvent("on" + L, M)
                } else {
                    N["on" + L] = false
                }
            }
        }
        ;
        this.unbindAll = function() {
            for (var L = 0; L < J.events.length; L++) {
                var M = J.events[L];
                (M.q) ? M.e.unbind(M.n, M.f) : J._unbind(M.e, M.n, M.f, M.b)
            }
        }
        ;
        this.cancelEvent = function(L) {
            var L = (L.original) ? L.original : (L) ? L : window.event || false;
            if (!L) {
                return false
            }
            if (L.preventDefault) {
                L.preventDefault()
            }
            if (L.stopPropagation) {
                L.stopPropagation()
            }
            if (L.preventManipulation) {
                L.preventManipulation()
            }
            L.cancelBubble = true;
            L.cancel = true;
            L.returnValue = false;
            return false
        }
        ;
        this.stopPropagation = function(L) {
            var L = (L.original) ? L.original : (L) ? L : window.event || false;
            if (!L) {
                return false
            }
            if (L.stopPropagation) {
                return L.stopPropagation()
            }
            if (L.cancelBubble) {
                L.cancelBubble = true
            }
            return false
        }
        ;
        this.showRail = function() {
            if ((J.page.maxh != 0) && (J.ispage || J.win.css("display") != "none")) {
                J.visibility = true;
                J.rail.visibility = true;
                J.rail.css("display", "block")
            }
            return J
        }
        ;
        this.showRailHr = function() {
            if (!J.railh) {
                return J
            }
            if ((J.page.maxw != 0) && (J.ispage || J.win.css("display") != "none")) {
                J.railh.visibility = true;
                J.railh.css("display", "block")
            }
            return J
        }
        ;
        this.hideRail = function() {
            J.visibility = false;
            J.rail.visibility = false;
            J.rail.css("display", "none");
            return J
        }
        ;
        this.hideRailHr = function() {
            if (!J.railh) {
                return J
            }
            J.railh.visibility = false;
            J.railh.css("display", "none");
            return J
        }
        ;
        this.show = function() {
            J.hidden = false;
            J.locked = false;
            return J.showRail().showRailHr()
        }
        ;
        this.hide = function() {
            J.hidden = true;
            J.locked = true;
            return J.hideRail().hideRailHr()
        }
        ;
        this.toggle = function() {
            return (J.hidden) ? J.show() : J.hide()
        }
        ;
        this.remove = function() {
            J.stop();
            if (J.cursortimeout) {
                clearTimeout(J.cursortimeout)
            }
            J.doZoomOut();
            J.unbindAll();
            if (I.isie9) {
                J.win[0].detachEvent("onpropertychange", J.onAttributeChange)
            }
            if (J.observer !== false) {
                J.observer.disconnect()
            }
            if (J.observerremover !== false) {
                J.observerremover.disconnect()
            }
            J.events = null;
            if (J.cursor) {
                J.cursor.remove()
            }
            if (J.cursorh) {
                J.cursorh.remove()
            }
            if (J.rail) {
                J.rail.remove()
            }
            if (J.railh) {
                J.railh.remove()
            }
            if (J.zoom) {
                J.zoom.remove()
            }
            for (var M = 0; M < J.saved.css.length; M++) {
                var O = J.saved.css[M];
                O[0].css(O[1], (typeof O[2] == "undefined") ? "" : O[2])
            }
            J.saved = false;
            J.me.data("__nicescroll", "");
            var L = e.nicescroll;
            L.each(function(Q) {
                if (!this) {
                    return
                }
                if (this.id === J.id) {
                    delete L[Q];
                    for (var P = ++Q; P < L.length; P++,
                        Q++) {
                        L[Q] = L[P]
                    }
                    L.length--;
                    if (L.length) {
                        delete L[L.length]
                    }
                }
            });
            for (var N in J) {
                J[N] = null;
                delete J[N]
            }
            J = null
        }
        ;
        this.scrollstart = function(L) {
            this.onscrollstart = L;
            return J
        }
        ;
        this.scrollend = function(L) {
            this.onscrollend = L;
            return J
        }
        ;
        this.scrollcancel = function(L) {
            this.onscrollcancel = L;
            return J
        }
        ;
        this.zoomin = function(L) {
            this.onzoomin = L;
            return J
        }
        ;
        this.zoomout = function(L) {
            this.onzoomout = L;
            return J
        }
        ;
        this.isScrollable = function(N) {
            var O = (N.target) ? N.target : N;
            if (O.nodeName == "OPTION") {
                return true
            }
            while (O && (O.nodeType == 1) && !(/^BODY|HTML/.test(O.nodeName))) {
                var L = e(O);
                var M = L.css("overflowY") || L.css("overflowX") || L.css("overflow") || "";
                if (/scroll|auto/.test(M)) {
                    return (O.clientHeight != O.scrollHeight)
                }
                O = (O.parentNode) ? O.parentNode : false
            }
            return false
        }
        ;
        this.getViewport = function(N) {
            var O = (N && N.parentNode) ? N.parentNode : false;
            while (O && (O.nodeType == 1) && !(/^BODY|HTML/.test(O.nodeName))) {
                var L = e(O);
                if (/fixed|absolute/.test(L.css("position"))) {
                    return L
                }
                var M = L.css("overflowY") || L.css("overflowX") || L.css("overflow") || "";
                if ((/scroll|auto/.test(M)) && (O.clientHeight != O.scrollHeight)) {
                    return L
                }
                if (L.getNiceScroll().length > 0) {
                    return L
                }
                O = (O.parentNode) ? O.parentNode : false
            }
            return (O) ? e(O) : false
        }
        ;
        this.triggerScrollEnd = function() {
            if (!J.onscrollend) {
                return
            }
            var M = J.getScrollLeft();
            var L = J.getScrollTop();
            var N = {
                type: "scrollend",
                current: {
                    x: M,
                    y: L
                },
                end: {
                    x: M,
                    y: L
                }
            };
            J.onscrollend.call(J, N)
        }
        ;
        function C(Q, O, P) {
            var N, M;
            var L = 1;
            if (Q.deltaMode == 0) {
                N = -Math.floor(Q.deltaX * (J.opt.mousescrollstep / (18 * 3)));
                M = -Math.floor(Q.deltaY * (J.opt.mousescrollstep / (18 * 3)))
            } else {
                if (Q.deltaMode == 1) {
                    N = -Math.floor(Q.deltaX * J.opt.mousescrollstep);
                    M = -Math.floor(Q.deltaY * J.opt.mousescrollstep)
                }
            }
            if (O && J.opt.oneaxismousemode && (N == 0) && M) {
                N = M;
                M = 0
            }
            if (N) {
                if (J.scrollmom) {
                    J.scrollmom.stop()
                }
                J.lastdeltax += N;
                J.debounced("mousewheelx", function() {
                    var R = J.lastdeltax;
                    J.lastdeltax = 0;
                    if (!J.rail.drag) {
                        J.doScrollLeftBy(R)
                    }
                }, 15)
            }
            if (M) {
                if (J.opt.nativeparentscrolling && P && !J.ispage && !J.zoomactive) {
                    if (M < 0) {
                        if (J.getScrollTop() >= J.page.maxh) {
                            return true
                        }
                    } else {
                        if (J.getScrollTop() <= 0) {
                            return true
                        }
                    }
                }
                if (J.scrollmom) {
                    J.scrollmom.stop()
                }
                J.lastdeltay += M;
                J.debounced("mousewheely", function() {
                    var R = J.lastdeltay;
                    J.lastdeltay = 0;
                    if (!J.rail.drag) {
                        J.doScrollBy(R)
                    }
                }, 15)
            }
            Q.stopImmediatePropagation();
            return Q.preventDefault()
        }
        this.onmousewheel = function(O) {
            if (J.wheelprevented) {
                return
            }
            if (J.locked) {
                J.debounced("checkunlock", J.resize, 250);
                return true
            }
            if (J.rail.drag) {
                return J.cancelEvent(O)
            }
            if (J.opt.oneaxismousemode == "auto" && O.deltaX != 0) {
                J.opt.oneaxismousemode = false
            }
            if (J.opt.oneaxismousemode && O.deltaX == 0) {
                if (!J.rail.scrollable) {
                    if (J.railh && J.railh.scrollable) {
                        return J.onmousewheelhr(O)
                    } else {
                        return true
                    }
                }
            }
            var M = +(new Date());
            var L = false;
            if (J.opt.preservenativescrolling && ((J.checkarea + 600) < M)) {
                J.nativescrollingarea = J.isScrollable(O);
                L = true
            }
            J.checkarea = M;
            if (J.nativescrollingarea) {
                return true
            }
            var N = C(O, false, L);
            if (N) {
                J.checkarea = 0
            }
            return N
        }
        ;
        this.onmousewheelhr = function(N) {
            if (J.wheelprevented) {
                return
            }
            if (J.locked || !J.railh.scrollable) {
                return true
            }
            if (J.rail.drag) {
                return J.cancelEvent(N)
            }
            var M = +(new Date());
            var L = false;
            if (J.opt.preservenativescrolling && ((J.checkarea + 600) < M)) {
                J.nativescrollingarea = J.isScrollable(N);
                L = true
            }
            J.checkarea = M;
            if (J.nativescrollingarea) {
                return true
            }
            if (J.locked) {
                return J.cancelEvent(N)
            }
            return C(N, true, L)
        }
        ;
        this.stop = function() {
            J.cancelScroll();
            if (J.scrollmon) {
                J.scrollmon.stop()
            }
            J.cursorfreezed = false;
            J.scroll.y = Math.round(J.getScrollTop() * (1 / J.scrollratio.y));
            J.noticeCursor();
            return J
        }
        ;
        this.getTransitionSpeed = function(M) {
            var N = Math.round(J.opt.scrollspeed * 10);
            var L = Math.min(N, Math.round((M / 20) * J.opt.scrollspeed));
            return (L > 20) ? L : 0
        }
        ;
        if (!J.opt.smoothscroll) {
            this.doScrollLeft = function(L, M) {
                var N = J.getScrollTop();
                J.doScrollPos(L, N, M)
            }
            ;
            this.doScrollTop = function(N, M) {
                var L = J.getScrollLeft();
                J.doScrollPos(L, N, M)
            }
            ;
            this.doScrollPos = function(M, P, N) {
                var L = (M > J.page.maxw) ? J.page.maxw : M;
                if (L < 0) {
                    L = 0
                }
                var O = (P > J.page.maxh) ? J.page.maxh : P;
                if (O < 0) {
                    O = 0
                }
                J.synched("scroll", function() {
                    J.setScrollTop(O);
                    J.setScrollLeft(L)
                })
            }
            ;
            this.cancelScroll = function() {}
        } else {
            if (J.ishwscroll && I.hastransition && J.opt.usetransition) {
                this.prepareTransition = function(O, L) {
                    var N = (L) ? ((O > 20) ? O : 0) : J.getTransitionSpeed(O);
                    var M = (N) ? I.prefixstyle + "transform " + N + "ms ease-out" : "";
                    if (!J.lasttransitionstyle || J.lasttransitionstyle != M) {
                        J.lasttransitionstyle = M;
                        J.doc.css(I.transitionstyle, M)
                    }
                    return N
                }
                ;
                this.doScrollLeft = function(L, M) {
                    var N = (J.scrollrunning) ? J.newscrolly : J.getScrollTop();
                    J.doScrollPos(L, N, M)
                }
                ;
                this.doScrollTop = function(N, M) {
                    var L = (J.scrollrunning) ? J.newscrollx : J.getScrollLeft();
                    J.doScrollPos(L, N, M)
                }
                ;
                this.doScrollPos = function(L, P, O) {
                    var M = J.getScrollTop();
                    var N = J.getScrollLeft();
                    if (((J.newscrolly - M) * (P - M) < 0) || ((J.newscrollx - N) * (L - N) < 0)) {
                        J.cancelScroll()
                    }
                    if (J.opt.bouncescroll == false) {
                        if (P < 0) {
                            P = 0
                        } else {
                            if (P > J.page.maxh) {
                                P = J.page.maxh
                            }
                        }
                        if (L < 0) {
                            L = 0
                        } else {
                            if (L > J.page.maxw) {
                                L = J.page.maxw
                            }
                        }
                    }
                    if (J.scrollrunning && L == J.newscrollx && P == J.newscrolly) {
                        return false
                    }
                    J.newscrolly = P;
                    J.newscrollx = L;
                    J.newscrollspeed = O || false;
                    if (J.timer) {
                        return false
                    }
                    J.timer = setTimeout(function() {
                        var W = J.getScrollTop();
                        var U = J.getScrollLeft();
                        var X = {};
                        X.x = L - U;
                        X.y = P - W;
                        X.px = U;
                        X.py = W;
                        var Q = Math.round(Math.sqrt(Math.pow(X.x, 2) + Math.pow(X.y, 2)));
                        var S = (J.newscrollspeed && J.newscrollspeed > 1) ? J.newscrollspeed : J.getTransitionSpeed(Q);
                        if (J.newscrollspeed && J.newscrollspeed <= 1) {
                            S *= J.newscrollspeed
                        }
                        J.prepareTransition(S, true);
                        if (J.timerscroll && J.timerscroll.tm) {
                            clearInterval(J.timerscroll.tm)
                        }
                        if (S > 0) {
                            if (!J.scrollrunning && J.onscrollstart) {
                                var V = {
                                    type: "scrollstart",
                                    current: {
                                        x: U,
                                        y: W
                                    },
                                    request: {
                                        x: L,
                                        y: P
                                    },
                                    end: {
                                        x: J.newscrollx,
                                        y: J.newscrolly
                                    },
                                    speed: S
                                };
                                J.onscrollstart.call(J, V)
                            }
                            if (I.transitionend) {
                                if (!J.scrollendtrapped) {
                                    J.scrollendtrapped = true;
                                    J.bind(J.doc, I.transitionend, J.onScrollTransitionEnd, false)
                                }
                            } else {
                                if (J.scrollendtrapped) {
                                    clearTimeout(J.scrollendtrapped)
                                }
                                J.scrollendtrapped = setTimeout(J.onScrollTransitionEnd, S)
                            }
                            var R = W;
                            var T = U;
                            J.timerscroll = {
                                bz: new BezierClass(R,J.newscrolly,S,0,0,0.58,1),
                                bh: new BezierClass(T,J.newscrollx,S,0,0,0.58,1)
                            };
                            if (!J.cursorfreezed) {
                                J.timerscroll.tm = setInterval(function() {
                                    J.showCursor(J.getScrollTop(), J.getScrollLeft())
                                }, 60)
                            }
                        }
                        J.synched("doScroll-set", function() {
                            J.timer = 0;
                            if (J.scrollendtrapped) {
                                J.scrollrunning = true
                            }
                            J.setScrollTop(J.newscrolly);
                            J.setScrollLeft(J.newscrollx);
                            if (!J.scrollendtrapped) {
                                J.onScrollTransitionEnd()
                            }
                        })
                    }, 50)
                }
                ;
                this.cancelScroll = function() {
                    if (!J.scrollendtrapped) {
                        return true
                    }
                    var L = J.getScrollTop();
                    var M = J.getScrollLeft();
                    J.scrollrunning = false;
                    if (!I.transitionend) {
                        clearTimeout(I.transitionend)
                    }
                    J.scrollendtrapped = false;
                    J._unbind(J.doc, I.transitionend, J.onScrollTransitionEnd);
                    J.prepareTransition(0);
                    J.setScrollTop(L);
                    if (J.railh) {
                        J.setScrollLeft(M)
                    }
                    if (J.timerscroll && J.timerscroll.tm) {
                        clearInterval(J.timerscroll.tm)
                    }
                    J.timerscroll = false;
                    J.cursorfreezed = false;
                    J.showCursor(L, M);
                    return J
                }
                ;
                this.onScrollTransitionEnd = function() {
                    if (J.scrollendtrapped) {
                        J._unbind(J.doc, I.transitionend, J.onScrollTransitionEnd)
                    }
                    J.scrollendtrapped = false;
                    J.prepareTransition(0);
                    if (J.timerscroll && J.timerscroll.tm) {
                        clearInterval(J.timerscroll.tm)
                    }
                    J.timerscroll = false;
                    var L = J.getScrollTop();
                    var M = J.getScrollLeft();
                    J.setScrollTop(L);
                    if (J.railh) {
                        J.setScrollLeft(M)
                    }
                    J.noticeCursor(false, L, M);
                    J.cursorfreezed = false;
                    if (L < 0) {
                        L = 0
                    } else {
                        if (L > J.page.maxh) {
                            L = J.page.maxh
                        }
                    }
                    if (M < 0) {
                        M = 0
                    } else {
                        if (M > J.page.maxw) {
                            M = J.page.maxw
                        }
                    }
                    if ((L != J.newscrolly) || (M != J.newscrollx)) {
                        return J.doScrollPos(M, L, J.opt.snapbackspeed)
                    }
                    if (J.onscrollend && J.scrollrunning) {
                        J.triggerScrollEnd()
                    }
                    J.scrollrunning = false
                }
            } else {
                this.doScrollLeft = function(L, M) {
                    var N = (J.scrollrunning) ? J.newscrolly : J.getScrollTop();
                    J.doScrollPos(L, N, M)
                }
                ;
                this.doScrollTop = function(N, M) {
                    var L = (J.scrollrunning) ? J.newscrollx : J.getScrollLeft();
                    J.doScrollPos(L, N, M)
                }
                ;
                this.doScrollPos = function(U, S, Q) {
                    var S = ((typeof S == "undefined") || (S === false)) ? J.getScrollTop(true) : S;
                    if ((J.timer) && (J.newscrolly == S) && (J.newscrollx == U)) {
                        return true
                    }
                    if (J.timer) {
                        h(J.timer)
                    }
                    J.timer = 0;
                    var T = J.getScrollTop();
                    var W = J.getScrollLeft();
                    if (((J.newscrolly - T) * (S - T) < 0) || ((J.newscrollx - W) * (U - W) < 0)) {
                        J.cancelScroll()
                    }
                    J.newscrolly = S;
                    J.newscrollx = U;
                    if (!J.bouncescroll || !J.rail.visibility) {
                        if (J.newscrolly < 0) {
                            J.newscrolly = 0
                        } else {
                            if (J.newscrolly > J.page.maxh) {
                                J.newscrolly = J.page.maxh
                            }
                        }
                    }
                    if (!J.bouncescroll || !J.railh.visibility) {
                        if (J.newscrollx < 0) {
                            J.newscrollx = 0
                        } else {
                            if (J.newscrollx > J.page.maxw) {
                                J.newscrollx = J.page.maxw
                            }
                        }
                    }
                    J.dst = {};
                    J.dst.x = U - W;
                    J.dst.y = S - T;
                    J.dst.px = W;
                    J.dst.py = T;
                    var P = Math.round(Math.sqrt(Math.pow(J.dst.x, 2) + Math.pow(J.dst.y, 2)));
                    J.dst.ax = J.dst.x / P;
                    J.dst.ay = J.dst.y / P;
                    var V = 0;
                    var O = P;
                    if (J.dst.x == 0) {
                        V = T;
                        O = S;
                        J.dst.ay = 1;
                        J.dst.py = 0
                    } else {
                        if (J.dst.y == 0) {
                            V = W;
                            O = U;
                            J.dst.ax = 1;
                            J.dst.px = 0
                        }
                    }
                    var L = J.getTransitionSpeed(P);
                    if (Q && Q <= 1) {
                        L *= Q
                    }
                    if (L > 0) {
                        J.bzscroll = (J.bzscroll) ? J.bzscroll.update(O, L) : new BezierClass(V,O,L,0,1,0,1)
                    } else {
                        J.bzscroll = false
                    }
                    if (J.timer) {
                        return
                    }
                    if ((T == J.page.maxh && S >= J.page.maxh) || (W == J.page.maxw && U >= J.page.maxw)) {
                        J.checkContentSize()
                    }
                    var R = 1;
                    function N() {
                        if (J.cancelAnimationFrame) {
                            return true
                        }
                        J.scrollrunning = true;
                        R = 1 - R;
                        if (R) {
                            return (J.timer = g(N) || 1)
                        }
                        var X = 0;
                        var aa = sy = J.getScrollTop();
                        if (J.dst.ay) {
                            aa = (J.bzscroll) ? J.dst.py + (J.bzscroll.getNow() * J.dst.ay) : J.newscrolly;
                            var Z = aa - sy;
                            if ((Z < 0 && aa < J.newscrolly) || (Z > 0 && aa > J.newscrolly)) {
                                aa = J.newscrolly
                            }
                            J.setScrollTop(aa);
                            if (aa == J.newscrolly) {
                                X = 1
                            }
                        } else {
                            X = 1
                        }
                        var Y = sx = J.getScrollLeft();
                        if (J.dst.ax) {
                            Y = (J.bzscroll) ? J.dst.px + (J.bzscroll.getNow() * J.dst.ax) : J.newscrollx;
                            var Z = Y - sx;
                            if ((Z < 0 && Y < J.newscrollx) || (Z > 0 && Y > J.newscrollx)) {
                                Y = J.newscrollx
                            }
                            J.setScrollLeft(Y);
                            if (Y == J.newscrollx) {
                                X += 1
                            }
                        } else {
                            X += 1
                        }
                        if (X == 2) {
                            J.timer = 0;
                            J.cursorfreezed = false;
                            J.bzscroll = false;
                            J.scrollrunning = false;
                            if (aa < 0) {
                                aa = 0
                            } else {
                                if (aa > J.page.maxh) {
                                    aa = J.page.maxh
                                }
                            }
                            if (Y < 0) {
                                Y = 0
                            } else {
                                if (Y > J.page.maxw) {
                                    Y = J.page.maxw
                                }
                            }
                            if ((Y != J.newscrollx) || (aa != J.newscrolly)) {
                                J.doScrollPos(Y, aa)
                            } else {
                                if (J.onscrollend) {
                                    J.triggerScrollEnd()
                                }
                            }
                        } else {
                            J.timer = g(N) || 1
                        }
                    }
                    J.cancelAnimationFrame = false;
                    J.timer = 1;
                    if (J.onscrollstart && !J.scrollrunning) {
                        var M = {
                            type: "scrollstart",
                            current: {
                                x: W,
                                y: T
                            },
                            request: {
                                x: U,
                                y: S
                            },
                            end: {
                                x: J.newscrollx,
                                y: J.newscrolly
                            },
                            speed: L
                        };
                        J.onscrollstart.call(J, M)
                    }
                    N();
                    if ((T == J.page.maxh && S >= T) || (W == J.page.maxw && U >= W)) {
                        J.checkContentSize()
                    }
                    J.noticeCursor()
                }
                ;
                this.cancelScroll = function() {
                    if (J.timer) {
                        h(J.timer)
                    }
                    J.timer = 0;
                    J.bzscroll = false;
                    J.scrollrunning = false;
                    return J
                }
            }
        }
        this.doScrollBy = function(L, N) {
            var P = 0;
            if (N) {
                P = Math.floor((J.scroll.y - L) * J.scrollratio.y)
            } else {
                var O = (J.timer) ? J.newscrolly : J.getScrollTop(true);
                P = O - L
            }
            if (J.bouncescroll) {
                var M = Math.round(J.view.h / 2);
                if (P < -M) {
                    P = -M
                } else {
                    if (P > (J.page.maxh + M)) {
                        P = (J.page.maxh + M)
                    }
                }
            }
            J.cursorfreezed = false;
            py = J.getScrollTop(true);
            if (P < 0 && py <= 0) {
                return J.noticeCursor()
            } else {
                if (P > J.page.maxh && py >= J.page.maxh) {
                    J.checkContentSize();
                    return J.noticeCursor()
                }
            }
            J.doScrollTop(P)
        }
        ;
        this.doScrollLeftBy = function(M, O) {
            var L = 0;
            if (O) {
                L = Math.floor((J.scroll.x - M) * J.scrollratio.x)
            } else {
                var P = (J.timer) ? J.newscrollx : J.getScrollLeft(true);
                L = P - M
            }
            if (J.bouncescroll) {
                var N = Math.round(J.view.w / 2);
                if (L < -N) {
                    L = -N
                } else {
                    if (L > (J.page.maxw + N)) {
                        L = (J.page.maxw + N)
                    }
                }
            }
            J.cursorfreezed = false;
            px = J.getScrollLeft(true);
            if (L < 0 && px <= 0) {
                return J.noticeCursor()
            } else {
                if (L > J.page.maxw && px >= J.page.maxw) {
                    return J.noticeCursor()
                }
            }
            J.doScrollLeft(L)
        }
        ;
        this.doScrollTo = function(N, L) {
            var M = (L) ? Math.round(N * J.scrollratio.y) : N;
            if (M < 0) {
                M = 0
            } else {
                if (M > J.page.maxh) {
                    M = J.page.maxh
                }
            }
            J.cursorfreezed = false;
            J.doScrollTop(N)
        }
        ;
        this.checkContentSize = function() {
            var L = J.getContentSize();
            if ((L.h != J.page.h) || (L.w != J.page.w)) {
                J.resize(false, L)
            }
        }
        ;
        J.onscroll = function(L) {
            if (J.rail.drag) {
                return
            }
            if (!J.cursorfreezed) {
                J.synched("scroll", function() {
                    J.scroll.y = Math.round(J.getScrollTop() * (1 / J.scrollratio.y));
                    if (J.railh) {
                        J.scroll.x = Math.round(J.getScrollLeft() * (1 / J.scrollratio.x))
                    }
                    J.noticeCursor()
                })
            }
        }
        ;
        J.bind(J.docscroll, "scroll", J.onscroll);
        this.doZoomIn = function(Q) {
            if (J.zoomactive) {
                return
            }
            J.zoomactive = true;
            J.zoomrestore = {
                style: {}
            };
            var L = ["position", "top", "left", "zIndex", "backgroundColor", "marginTop", "marginBottom", "marginLeft", "marginRight"];
            var P = J.win[0].style;
            for (var N in L) {
                var M = L[N];
                J.zoomrestore.style[M] = (typeof P[M] != "undefined") ? P[M] : ""
            }
            J.zoomrestore.style.width = J.win.css("width");
            J.zoomrestore.style.height = J.win.css("height");
            J.zoomrestore.padding = {
                w: J.win.outerWidth() - J.win.width(),
                h: J.win.outerHeight() - J.win.height()
            };
            if (I.isios4) {
                J.zoomrestore.scrollTop = e(window).scrollTop();
                e(window).scrollTop(0)
            }
            J.win.css({
                position: (I.isios4) ? "absolute" : "fixed",
                top: 0,
                left: 0,
                "z-index": d + 100,
                margin: "0px"
            });
            var O = J.win.css("backgroundColor");
            if (O == "" || /transparent|rgba\(0, 0, 0, 0\)|rgba\(0,0,0,0\)/.test(O)) {
                J.win.css("backgroundColor", "#fff")
            }
            J.rail.css({
                "z-index": d + 101
            });
            J.zoom.css({
                "z-index": d + 102
            });
            J.zoom.css("backgroundPosition", "0px -18px");
            J.resizeZoom();
            if (J.onzoomin) {
                J.onzoomin.call(J)
            }
            return J.cancelEvent(Q)
        }
        ;
        this.doZoomOut = function(L) {
            if (!J.zoomactive) {
                return
            }
            J.zoomactive = false;
            J.win.css("margin", "");
            J.win.css(J.zoomrestore.style);
            if (I.isios4) {
                e(window).scrollTop(J.zoomrestore.scrollTop)
            }
            J.rail.css({
                "z-index": J.zindex
            });
            J.zoom.css({
                "z-index": J.zindex
            });
            J.zoomrestore = false;
            J.zoom.css("backgroundPosition", "0px 0px");
            J.onResize();
            if (J.onzoomout) {
                J.onzoomout.call(J)
            }
            return J.cancelEvent(L)
        }
        ;
        this.doZoom = function(L) {
            return (J.zoomactive) ? J.doZoomOut(L) : J.doZoomIn(L)
        }
        ;
        this.resizeZoom = function() {
            if (!J.zoomactive) {
                return
            }
            var L = J.getScrollTop();
            J.win.css({
                width: e(window).width() - J.zoomrestore.padding.w + "px",
                height: e(window).height() - J.zoomrestore.padding.h + "px"
            });
            J.onResize();
            J.setScrollTop(Math.min(J.page.maxh, L))
        }
        ;
        this.init();
        e.nicescroll.push(this)
    };
    var s = function(z) {
        var v = this;
        this.nc = z;
        this.lastx = 0;
        this.lasty = 0;
        this.speedx = 0;
        this.speedy = 0;
        this.lasttime = 0;
        this.steptime = 0;
        this.snapx = false;
        this.snapy = false;
        this.demulx = 0;
        this.demuly = 0;
        this.lastscrollx = -1;
        this.lastscrolly = -1;
        this.chkx = 0;
        this.chky = 0;
        this.timer = 0;
        this.time = function() {
            return +new Date()
        }
        ;
        this.reset = function(C, B) {
            v.stop();
            var A = v.time();
            v.steptime = 0;
            v.lasttime = A;
            v.speedx = 0;
            v.speedy = 0;
            v.lastx = C;
            v.lasty = B;
            v.lastscrollx = -1;
            v.lastscrolly = -1
        }
        ;
        this.update = function(G, F) {
            var A = v.time();
            v.steptime = A - v.lasttime;
            v.lasttime = A;
            var H = F - v.lasty;
            var I = G - v.lastx;
            var D = v.nc.getScrollTop();
            var E = v.nc.getScrollLeft();
            var B = D + H;
            var C = E + I;
            v.snapx = (C < 0) || (C > v.nc.page.maxw);
            v.snapy = (B < 0) || (B > v.nc.page.maxh);
            v.speedx = I;
            v.speedy = H;
            v.lastx = G;
            v.lasty = F
        }
        ;
        this.stop = function() {
            v.nc.unsynched("domomentum2d");
            if (v.timer) {
                clearTimeout(v.timer)
            }
            v.timer = 0;
            v.lastscrollx = -1;
            v.lastscrolly = -1
        }
        ;
        this.doSnapy = function(B, C) {
            var A = false;
            if (C < 0) {
                C = 0;
                A = true
            } else {
                if (C > v.nc.page.maxh) {
                    C = v.nc.page.maxh;
                    A = true
                }
            }
            if (B < 0) {
                B = 0;
                A = true
            } else {
                if (B > v.nc.page.maxw) {
                    B = v.nc.page.maxw;
                    A = true
                }
            }
            (A) ? v.nc.doScrollPos(B, C, v.nc.opt.snapbackspeed) : v.nc.triggerScrollEnd()
        }
        ;
        this.doMomentum = function(B) {
            var N = v.time();
            var C = (B) ? N + B : v.lasttime;
            var D = v.nc.getScrollLeft();
            var O = v.nc.getScrollTop();
            var J = v.nc.page.maxh;
            var A = v.nc.page.maxw;
            v.speedx = (A > 0) ? Math.min(60, v.speedx) : 0;
            v.speedy = (J > 0) ? Math.min(60, v.speedy) : 0;
            var I = C && (N - C) <= 60;
            if ((O < 0) || (O > J) || (D < 0) || (D > A)) {
                I = false
            }
            var K = (v.speedy && I) ? v.speedy : false;
            var L = (v.speedx && I) ? v.speedx : false;
            if (K || L) {
                var M = Math.max(16, v.steptime);
                if (M > 50) {
                    var G = M / 50;
                    v.speedx *= G;
                    v.speedy *= G;
                    M = 50
                }
                v.demulxy = 0;
                v.lastscrollx = v.nc.getScrollLeft();
                v.chkx = v.lastscrollx;
                v.lastscrolly = v.nc.getScrollTop();
                v.chky = v.lastscrolly;
                var F = v.lastscrollx;
                var E = v.lastscrolly;
                var H = function() {
                    var P = ((v.time() - N) > 600) ? 0.04 : 0.02;
                    if (v.speedx) {
                        F = Math.floor(v.lastscrollx - (v.speedx * (1 - v.demulxy)));
                        v.lastscrollx = F;
                        if ((F < 0) || (F > A)) {
                            P = 0.1
                        }
                    }
                    if (v.speedy) {
                        E = Math.floor(v.lastscrolly - (v.speedy * (1 - v.demulxy)));
                        v.lastscrolly = E;
                        if ((E < 0) || (E > J)) {
                            P = 0.1
                        }
                    }
                    v.demulxy = Math.min(1, v.demulxy + P);
                    v.nc.synched("domomentum2d", function() {
                        if (v.speedx) {
                            var R = v.nc.getScrollLeft();
                            if (R != v.chkx) {
                                v.stop()
                            }
                            v.chkx = F;
                            v.nc.setScrollLeft(F)
                        }
                        if (v.speedy) {
                            var Q = v.nc.getScrollTop();
                            if (Q != v.chky) {
                                v.stop()
                            }
                            v.chky = E;
                            v.nc.setScrollTop(E)
                        }
                        if (!v.timer) {
                            v.nc.hideCursor();
                            v.doSnapy(F, E)
                        }
                    });
                    if (v.demulxy < 1) {
                        v.timer = setTimeout(H, M)
                    } else {
                        v.stop();
                        v.nc.hideCursor();
                        v.doSnapy(F, E)
                    }
                };
                H()
            } else {
                v.doSnapy(v.nc.getScrollLeft(), v.nc.getScrollTop())
            }
        }
    };
    var l = m.fn.scrollTop;
    m.cssHooks.pageYOffset = {
        get: function(B, A, v) {
            var z = e.data(B, "__nicescroll") || false;
            return (z && z.ishwscroll) ? z.getScrollTop() : l.call(B)
        },
        set: function(z, A) {
            var v = e.data(z, "__nicescroll") || false;
            (v && v.ishwscroll) ? v.setScrollTop(parseInt(A)) : l.call(z, A);
            return this
        }
    };
    m.fn.scrollTop = function(z) {
        if (typeof z == "undefined") {
            var v = (this[0]) ? e.data(this[0], "__nicescroll") || false : false;
            return (v && v.ishwscroll) ? v.getScrollTop() : l.call(this)
        } else {
            return this.each(function() {
                var A = e.data(this, "__nicescroll") || false;
                (A && A.ishwscroll) ? A.setScrollTop(parseInt(z)) : l.call(e(this), z)
            })
        }
    }
    ;
    var r = m.fn.scrollLeft;
    e.cssHooks.pageXOffset = {
        get: function(B, A, v) {
            var z = e.data(B, "__nicescroll") || false;
            return (z && z.ishwscroll) ? z.getScrollLeft() : r.call(B)
        },
        set: function(z, A) {
            var v = e.data(z, "__nicescroll") || false;
            (v && v.ishwscroll) ? v.setScrollLeft(parseInt(A)) : r.call(z, A);
            return this
        }
    };
    m.fn.scrollLeft = function(z) {
        if (typeof z == "undefined") {
            var v = (this[0]) ? e.data(this[0], "__nicescroll") || false : false;
            return (v && v.ishwscroll) ? v.getScrollLeft() : r.call(this)
        } else {
            return this.each(function() {
                var A = e.data(this, "__nicescroll") || false;
                (A && A.ishwscroll) ? A.setScrollLeft(parseInt(z)) : r.call(e(this), z)
            })
        }
    }
    ;
    var y = function(B) {
        var z = this;
        this.length = 0;
        this.name = "nicescrollarray";
        this.each = function(E) {
            for (var C = 0, D = 0; C < z.length; C++) {
                E.call(z[C], D++)
            }
            return z
        }
        ;
        this.push = function(C) {
            z[z.length] = C;
            z.length++
        }
        ;
        this.eq = function(C) {
            return z[C]
        }
        ;
        if (B) {
            for (var v = 0; v < B.length; v++) {
                var A = e.data(B[v], "__nicescroll") || false;
                if (A) {
                    this[this.length] = A;
                    this.length++
                }
            }
        }
        return this
    };
    function o(B, v, A) {
        for (var z = 0; z < v.length; z++) {
            A(B, v[z])
        }
    }
    o(y.prototype, ["show", "hide", "toggle", "onResize", "resize", "remove", "stop", "doScrollPos"], function(v, z) {
        v[z] = function() {
            var A = arguments;
            return this.each(function() {
                this[z].apply(this, A)
            })
        }
    });
    m.fn.getNiceScroll = function(v) {
        if (typeof v == "undefined") {
            return new y(this)
        } else {
            var z = this[v] && e.data(this[v], "__nicescroll") || false;
            return z
        }
    }
    ;
    m.extend(m.expr[":"], {
        nicescroll: function(v) {
            return (e.data(v, "__nicescroll")) ? true : false
        }
    });
    e.fn.niceScroll = function(B, z) {
        if (typeof z == "undefined") {
            if ((typeof B == "object") && !("jquery"in B)) {
                z = B;
                B = false
            }
        }
        var v = new y();
        if (typeof z == "undefined") {
            z = {}
        }
        if (B || false) {
            z.doc = e(B);
            z.win = e(this)
        }
        var A = !("doc"in z);
        if (!A && !("win"in z)) {
            z.win = e(this)
        }
        this.each(function() {
            var C = e(this).data("__nicescroll") || false;
            if (!C) {
                z.doc = (A) ? e(this) : z.doc;
                C = new b(z,e(this));
                e(this).data("__nicescroll", C)
            }
            v.push(C)
        });
        return (v.length == 1) ? v[0] : v
    }
    ;
    window.NiceScroll = {
        getjQuery: function() {
            return m
        }
    };
    if (!e.nicescroll) {
        e.nicescroll = new y();
        e.nicescroll.options = c
    }
}));
