/*!
* inputmask.min.js
* https://github.com/RobinHerbots/Inputmask
* Copyright (c) 2010 - 2017 Robin Herbots
* Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php)
* Version: 3.3.11
*/

!function(e) {
    "function" == typeof define && define.amd ? define(["./dependencyLibs/inputmask.dependencyLib", "./global/window", "./global/document"], e) : "object" == typeof exports ? module.exports = e(require("./dependencyLibs/inputmask.dependencyLib"), require("./global/window"), require("./global/document")) : window.Inputmask = e(window.dependencyLib || jQuery, window, document)
}(function(e, t, n, i) {
    function a(t, n, o) {
        if (!(this instanceof a))
            return new a(t,n,o);
        this.el = i,
            this.events = {},
            this.maskset = i,
            this.refreshValue = !1,
        !0 !== o && (e.isPlainObject(t) ? n = t : (n = n || {}).alias = t,
            this.opts = e.extend(!0, {}, this.defaults, n),
            this.noMasksCache = n && n.definitions !== i,
            this.userOptions = n || {},
            this.isRTL = this.opts.numericInput,
            r(this.opts.alias, n, this.opts))
    }
    function r(t, n, o) {
        var s = a.prototype.aliases[t];
        return s ? (s.alias && r(s.alias, i, o),
            e.extend(!0, o, s),
            e.extend(!0, o, n),
            !0) : (null === o.mask && (o.mask = t),
            !1)
    }
    function o(t, n) {
        function r(t, r, o) {
            var s = !1;
            if (null !== t && "" !== t || ((s = null !== o.regex) ? t = (t = o.regex).replace(/^(\^)(.*)(\$)$/, "$2") : (s = !0,
                t = ".*")),
            1 === t.length && !1 === o.greedy && 0 !== o.repeat && (o.placeholder = ""),
            o.repeat > 0 || "*" === o.repeat || "+" === o.repeat) {
                var l = "*" === o.repeat ? 0 : "+" === o.repeat ? 1 : o.repeat;
                t = o.groupmarker.start + t + o.groupmarker.end + o.quantifiermarker.start + l + "," + o.repeat + o.quantifiermarker.end
            }
            var c, u = s ? "regex_" + o.regex : o.numericInput ? t.split("").reverse().join("") : t;
            return a.prototype.masksCache[u] === i || !0 === n ? (c = {
                mask: t,
                maskToken: a.prototype.analyseMask(t, s, o),
                validPositions: {},
                _buffer: i,
                buffer: i,
                tests: {},
                metadata: r,
                maskLength: i
            },
            !0 !== n && (a.prototype.masksCache[u] = c,
                c = e.extend(!0, {}, a.prototype.masksCache[u]))) : c = e.extend(!0, {}, a.prototype.masksCache[u]),
                c
        }
        if (e.isFunction(t.mask) && (t.mask = t.mask(t)),
            e.isArray(t.mask)) {
            if (t.mask.length > 1) {
                t.keepStatic = null === t.keepStatic || t.keepStatic;
                var o = t.groupmarker.start;
                return e.each(t.numericInput ? t.mask.reverse() : t.mask, function(n, a) {
                    o.length > 1 && (o += t.groupmarker.end + t.alternatormarker + t.groupmarker.start),
                        a.mask === i || e.isFunction(a.mask) ? o += a : o += a.mask
                }),
                    o += t.groupmarker.end,
                    r(o, t.mask, t)
            }
            t.mask = t.mask.pop()
        }
        return t.mask && t.mask.mask !== i && !e.isFunction(t.mask.mask) ? r(t.mask.mask, t.mask, t) : r(t.mask, t.mask, t)
    }
    function s(r, o, l) {
        function h(e, t, n) {
            t = t || 0;
            var a, r, o, s = [], c = 0, u = v();
            do {
                !0 === e && m().validPositions[c] ? (r = (o = m().validPositions[c]).match,
                    a = o.locator.slice(),
                    s.push(!0 === n ? o.input : !1 === n ? r.nativeDef : G(c, r))) : (r = (o = y(c, a, c - 1)).match,
                    a = o.locator.slice(),
                (!1 === l.jitMasking || c < u || "number" == typeof l.jitMasking && isFinite(l.jitMasking) && l.jitMasking > c) && s.push(!1 === n ? r.nativeDef : G(c, r))),
                    c++
            } while ((W === i || c < W) && (null !== r.fn || "" !== r.def) || t > c);return "" === s[s.length - 1] && s.pop(),
                m().maskLength = c + 1,
                s
        }
        function m() {
            return o
        }
        function d(e) {
            var t = m();
            t.buffer = i,
            !0 !== e && (t.validPositions = {},
                t.p = 0)
        }
        function v(e, t, n) {
            var a = -1
                , r = -1
                , o = n || m().validPositions;
            e === i && (e = -1);
            for (var s in o) {
                var l = parseInt(s);
                o[l] && (t || !0 !== o[l].generatedInput) && (l <= e && (a = l),
                l >= e && (r = l))
            }
            return -1 !== a && e - a > 1 || r < e ? a : r
        }
        function g(t, n, a, r) {
            var o, s = t, c = e.extend(!0, {}, m().validPositions), u = !1;
            for (m().p = t,
                     o = n - 1; o >= s; o--)
                m().validPositions[o] !== i && (!0 !== a && (!m().validPositions[o].match.optionality && function(e) {
                    var t = m().validPositions[e];
                    if (t !== i && null === t.match.fn) {
                        var n = m().validPositions[e - 1]
                            , a = m().validPositions[e + 1];
                        return n !== i && a !== i
                    }
                    return !1
                }(o) || !1 === l.canClearPosition(m(), o, v(), r, l)) || delete m().validPositions[o]);
            for (d(!0),
                     o = s + 1; o <= v(); ) {
                for (; m().validPositions[s] !== i; )
                    s++;
                if (o < s && (o = s + 1),
                m().validPositions[o] === i && O(o))
                    o++;
                else {
                    var f = y(o);
                    !1 === u && c[s] && c[s].match.def === f.match.def ? (m().validPositions[s] = e.extend(!0, {}, c[s]),
                        m().validPositions[s].input = f.input,
                        delete m().validPositions[o],
                        o++) : P(s, f.match.def) ? !1 !== M(s, f.input || G(o), !0) && (delete m().validPositions[o],
                        o++,
                        u = !0) : O(o) || (o++,
                        s--),
                        s++
                }
            }
            d(!0)
        }
        function k(e, t) {
            for (var n, a = e, r = v(), o = m().validPositions[r] || E(0)[0], s = o.alternation !== i ? o.locator[o.alternation].toString().split(",") : [], c = 0; c < a.length && (!((n = a[c]).match && (l.greedy && !0 !== n.match.optionalQuantifier || (!1 === n.match.optionality || !1 === n.match.newBlockMarker) && !0 !== n.match.optionalQuantifier) && (o.alternation === i || o.alternation !== n.alternation || n.locator[o.alternation] !== i && w(n.locator[o.alternation].toString().split(","), s))) || !0 === t && (null !== n.match.fn || /[0-9a-bA-Z]/.test(n.match.def))); c++)
                ;
            return n
        }
        function y(e, t, n) {
            return m().validPositions[e] || k(E(e, t ? t.slice() : t, n))
        }
        function b(e) {
            return m().validPositions[e] ? m().validPositions[e] : E(e)[0]
        }
        function P(e, t) {
            for (var n = !1, i = E(e), a = 0; a < i.length; a++)
                if (i[a].match && i[a].match.def === t) {
                    n = !0;
                    break
                }
            return n
        }
        function E(t, n, a) {
            function r(n, a, o, c) {
                function f(o, c, v) {
                    function g(t, n) {
                        var i = 0 === e.inArray(t, n.matches);
                        return i || e.each(n.matches, function(e, a) {
                            if (!0 === a.isQuantifier && (i = g(t, n.matches[e - 1])))
                                return !1
                        }),
                            i
                    }
                    function k(t, n, a) {
                        var r, o;
                        if (m().validPositions[t - 1] && a && m().tests[t])
                            for (var s = m().validPositions[t - 1].locator, l = m().tests[t][0].locator, c = 0; c < a; c++)
                                if (s[c] !== l[c])
                                    return s.slice(a + 1);
                        return (m().tests[t] || m().validPositions[t]) && e.each(m().tests[t] || [m().validPositions[t]], function(e, t) {
                            var s = a !== i ? a : t.alternation
                                , l = t.locator[s] !== i ? t.locator[s].toString().indexOf(n) : -1;
                            (o === i || l < o) && -1 !== l && (r = t,
                                o = l)
                        }),
                            r ? r.locator.slice((a !== i ? a : r.alternation) + 1) : a !== i ? k(t, n) : i
                    }
                    if (u > 1e4)
                        throw "Inputmask: There is probably an error in your mask definition or in the code. Create an issue on github with an example of the mask you are using. " + m().mask;
                    if (u === t && o.matches === i)
                        return p.push({
                            match: o,
                            locator: c.reverse(),
                            cd: d
                        }),
                            !0;
                    if (o.matches !== i) {
                        if (o.isGroup && v !== o) {
                            if (o = f(n.matches[e.inArray(o, n.matches) + 1], c))
                                return !0
                        } else if (o.isOptional) {
                            var y = o;
                            if (o = r(o, a, c, v)) {
                                if (s = p[p.length - 1].match,
                                    !g(s, y))
                                    return !0;
                                h = !0,
                                    u = t
                            }
                        } else if (o.isAlternator) {
                            var b, P = o, E = [], C = p.slice(), A = c.length, _ = a.length > 0 ? a.shift() : -1;
                            if (-1 === _ || "string" == typeof _) {
                                var x, w = u, M = a.slice(), O = [];
                                if ("string" == typeof _)
                                    O = _.split(",");
                                else
                                    for (x = 0; x < P.matches.length; x++)
                                        O.push(x);
                                for (var S = 0; S < O.length; S++) {
                                    if (x = parseInt(O[S]),
                                        p = [],
                                        a = k(u, x, A) || M.slice(),
                                    !0 !== (o = f(P.matches[x] || n.matches[x], [x].concat(c), v) || o) && o !== i && O[O.length - 1] < P.matches.length) {
                                        var D = e.inArray(o, n.matches) + 1;
                                        n.matches.length > D && (o = f(n.matches[D], [D].concat(c.slice(1, c.length)), v)) && (O.push(D.toString()),
                                            e.each(p, function(e, t) {
                                                t.alternation = c.length - 1
                                            }))
                                    }
                                    b = p.slice(),
                                        u = w,
                                        p = [];
                                    for (var j = 0; j < b.length; j++) {
                                        var T = b[j]
                                            , G = !1;
                                        T.alternation = T.alternation || A;
                                        for (var L = 0; L < E.length; L++) {
                                            var B = E[L];
                                            if ("string" != typeof _ || -1 !== e.inArray(T.locator[T.alternation].toString(), O)) {
                                                if (function(e, t) {
                                                    return e.match.nativeDef === t.match.nativeDef || e.match.def === t.match.nativeDef || e.match.nativeDef === t.match.def
                                                }(T, B)) {
                                                    G = !0,
                                                    T.alternation === B.alternation && -1 === B.locator[B.alternation].toString().indexOf(T.locator[T.alternation]) && (B.locator[B.alternation] = B.locator[B.alternation] + "," + T.locator[T.alternation],
                                                        B.alternation = T.alternation),
                                                    T.match.nativeDef === B.match.def && (T.locator[T.alternation] = B.locator[B.alternation],
                                                        E.splice(E.indexOf(B), 1, T));
                                                    break
                                                }
                                                if (T.match.def === B.match.def) {
                                                    G = !1;
                                                    break
                                                }
                                                if (function(e, n) {
                                                    return null === e.match.fn && null !== n.match.fn && n.match.fn.test(e.match.def, m(), t, !1, l, !1)
                                                }(T, B) || function(e, n) {
                                                    return null !== e.match.fn && null !== n.match.fn && n.match.fn.test(e.match.def.replace(/[\[\]]/g, ""), m(), t, !1, l, !1)
                                                }(T, B)) {
                                                    T.alternation === B.alternation && -1 === T.locator[T.alternation].toString().indexOf(B.locator[B.alternation].toString().split("")[0]) && (T.na = T.na || T.locator[T.alternation].toString(),
                                                    -1 === T.na.indexOf(T.locator[T.alternation].toString().split("")[0]) && (T.na = T.na + "," + T.locator[B.alternation].toString().split("")[0]),
                                                        G = !0,
                                                        T.locator[T.alternation] = B.locator[B.alternation].toString().split("")[0] + "," + T.locator[T.alternation],
                                                        E.splice(E.indexOf(B), 0, T));
                                                    break
                                                }
                                            }
                                        }
                                        G || E.push(T)
                                    }
                                }
                                "string" == typeof _ && (E = e.map(E, function(t, n) {
                                    if (isFinite(n)) {
                                        var a = t.alternation
                                            , r = t.locator[a].toString().split(",");
                                        t.locator[a] = i,
                                            t.alternation = i;
                                        for (var o = 0; o < r.length; o++)
                                            -1 !== e.inArray(r[o], O) && (t.locator[a] !== i ? (t.locator[a] += ",",
                                                t.locator[a] += r[o]) : t.locator[a] = parseInt(r[o]),
                                                t.alternation = a);
                                        if (t.locator[a] !== i)
                                            return t
                                    }
                                })),
                                    p = C.concat(E),
                                    u = t,
                                    h = p.length > 0,
                                    o = E.length > 0,
                                    a = M.slice()
                            } else
                                o = f(P.matches[_] || n.matches[_], [_].concat(c), v);
                            if (o)
                                return !0
                        } else if (o.isQuantifier && v !== n.matches[e.inArray(o, n.matches) - 1])
                            for (var I = o, F = a.length > 0 ? a.shift() : 0; F < (isNaN(I.quantifier.max) ? F + 1 : I.quantifier.max) && u <= t; F++) {
                                var N = n.matches[e.inArray(I, n.matches) - 1];
                                if (o = f(N, [F].concat(c), N)) {
                                    if (s = p[p.length - 1].match,
                                        s.optionalQuantifier = F > I.quantifier.min - 1,
                                        g(s, N)) {
                                        if (F > I.quantifier.min - 1) {
                                            h = !0,
                                                u = t;
                                            break
                                        }
                                        return !0
                                    }
                                    return !0
                                }
                            }
                        else if (o = r(o, a, c, v))
                            return !0
                    } else
                        u++
                }
                for (var v = a.length > 0 ? a.shift() : 0; v < n.matches.length; v++)
                    if (!0 !== n.matches[v].isQuantifier) {
                        var g = f(n.matches[v], [v].concat(o), c);
                        if (g && u === t)
                            return g;
                        if (u > t)
                            break
                    }
            }
            function o(e) {
                if (l.keepStatic && t > 0 && e.length > 1 + ("" === e[e.length - 1].match.def ? 1 : 0) && !0 !== e[0].match.optionality && !0 !== e[0].match.optionalQuantifier && null === e[0].match.fn && !/[0-9a-bA-Z]/.test(e[0].match.def)) {
                    if (m().validPositions[t - 1] === i)
                        return [k(e)];
                    if (m().validPositions[t - 1].alternation === e[0].alternation)
                        return [k(e)];
                    if (m().validPositions[t - 1])
                        return [k(e)]
                }
                return e
            }
            var s, c = m().maskToken, u = n ? a : 0, f = n ? n.slice() : [0], p = [], h = !1, d = n ? n.join("") : "";
            if (t > -1) {
                if (n === i) {
                    for (var v, g = t - 1; (v = m().validPositions[g] || m().tests[g]) === i && g > -1; )
                        g--;
                    v !== i && g > -1 && (f = function(t) {
                        var n = [];
                        return e.isArray(t) || (t = [t]),
                        t.length > 0 && (t[0].alternation === i ? 0 === (n = k(t.slice()).locator.slice()).length && (n = t[0].locator.slice()) : e.each(t, function(e, t) {
                            if ("" !== t.def)
                                if (0 === n.length)
                                    n = t.locator.slice();
                                else
                                    for (var i = 0; i < n.length; i++)
                                        t.locator[i] && -1 === n[i].toString().indexOf(t.locator[i]) && (n[i] += "," + t.locator[i])
                        })),
                            n
                    }(v),
                        d = f.join(""),
                        u = g)
                }
                if (m().tests[t] && m().tests[t][0].cd === d)
                    return o(m().tests[t]);
                for (var y = f.shift(); y < c.length && !(r(c[y], f, [y]) && u === t || u > t); y++)
                    ;
            }
            return (0 === p.length || h) && p.push({
                match: {
                    fn: null,
                    cardinality: 0,
                    optionality: !0,
                    casing: null,
                    def: "",
                    placeholder: ""
                },
                locator: [],
                cd: d
            }),
                n !== i && m().tests[t] ? o(e.extend(!0, [], p)) : (m().tests[t] = e.extend(!0, [], p),
                    o(m().tests[t]))
        }
        function C() {
            return m()._buffer === i && (m()._buffer = h(!1, 1),
            m().buffer === i && (m().buffer = m()._buffer.slice())),
                m()._buffer
        }
        function A(e) {
            return m().buffer !== i && !0 !== e || (m().buffer = h(!0, v(), !0)),
                m().buffer
        }
        function _(e, t, n) {
            var a, r;
            if (!0 === e)
                d(),
                    e = 0,
                    t = n.length;
            else
                for (a = e; a < t; a++)
                    delete m().validPositions[a];
            for (r = e,
                     a = e; a < t; a++)
                if (d(!0),
                n[a] !== l.skipOptionalPartCharacter) {
                    var o = M(r, n[a], !0, !0);
                    !1 !== o && (d(!0),
                        r = o.caret !== i ? o.caret : o.pos + 1)
                }
        }
        function x(t, n, i) {
            switch (l.casing || n.casing) {
                case "upper":
                    t = t.toUpperCase();
                    break;
                case "lower":
                    t = t.toLowerCase();
                    break;
                case "title":
                    var r = m().validPositions[i - 1];
                    t = 0 === i || r && r.input === String.fromCharCode(a.keyCode.SPACE) ? t.toUpperCase() : t.toLowerCase();
                    break;
                default:
                    if (e.isFunction(l.casing)) {
                        var o = Array.prototype.slice.call(arguments);
                        o.push(m().validPositions),
                            t = l.casing.apply(this, o)
                    }
            }
            return t
        }
        function w(t, n, a) {
            for (var r, o = l.greedy ? n : n.slice(0, 1), s = !1, c = a !== i ? a.split(",") : [], u = 0; u < c.length; u++)
                -1 !== (r = t.indexOf(c[u])) && t.splice(r, 1);
            for (var f = 0; f < t.length; f++)
                if (-1 !== e.inArray(t[f], o)) {
                    s = !0;
                    break
                }
            return s
        }
        function M(t, n, r, o, s, c) {
            function u(e) {
                var t = z ? e.begin - e.end > 1 || e.begin - e.end == 1 : e.end - e.begin > 1 || e.end - e.begin == 1;
                return t && 0 === e.begin && e.end === m().maskLength ? "full" : t
            }
            function f(n, a, r) {
                var s = !1;
                return e.each(E(n), function(c, f) {
                    for (var h = f.match, k = a ? 1 : 0, y = "", b = h.cardinality; b > k; b--)
                        y += j(n - (b - 1));
                    if (a && (y += a),
                        A(!0),
                    !1 !== (s = null != h.fn ? h.fn.test(y, m(), n, r, l, u(t)) : (a === h.def || a === l.skipOptionalPartCharacter) && "" !== h.def && {
                        c: G(n, h, !0) || h.def,
                        pos: n
                    })) {
                        var P = s.c !== i ? s.c : a;
                        P = P === l.skipOptionalPartCharacter && null === h.fn ? G(n, h, !0) || h.def : P;
                        var E = n
                            , C = A();
                        if (s.remove !== i && (e.isArray(s.remove) || (s.remove = [s.remove]),
                            e.each(s.remove.sort(function(e, t) {
                                return t - e
                            }), function(e, t) {
                                g(t, t + 1, !0)
                            })),
                        s.insert !== i && (e.isArray(s.insert) || (s.insert = [s.insert]),
                            e.each(s.insert.sort(function(e, t) {
                                return e - t
                            }), function(e, t) {
                                M(t.pos, t.c, !0, o)
                            })),
                            s.refreshFromBuffer) {
                            var w = s.refreshFromBuffer;
                            if (_(!0 === w ? w : w.start, w.end, C),
                            s.pos === i && s.c === i)
                                return s.pos = v(),
                                    !1;
                            if ((E = s.pos !== i ? s.pos : n) !== n)
                                return s = e.extend(s, M(E, P, !0, o)),
                                    !1
                        } else if (!0 !== s && s.pos !== i && s.pos !== n && (E = s.pos,
                            _(n, E, A().slice()),
                        E !== n))
                            return s = e.extend(s, M(E, P, !0)),
                                !1;
                        return (!0 === s || s.pos !== i || s.c !== i) && (c > 0 && d(!0),
                        p(E, e.extend({}, f, {
                            input: x(P, h, E)
                        }), o, u(t)) || (s = !1),
                            !1)
                    }
                }),
                    s
            }
            function p(t, n, a, r) {
                if (r || l.insertMode && m().validPositions[t] !== i && a === i) {
                    var o, s = e.extend(!0, {}, m().validPositions), c = v(i, !0);
                    for (o = t; o <= c; o++)
                        delete m().validPositions[o];
                    m().validPositions[t] = e.extend(!0, {}, n);
                    var u, f = !0, p = m().validPositions, g = !1, k = m().maskLength;
                    for (o = u = t; o <= c; o++) {
                        var y = s[o];
                        if (y !== i)
                            for (var b = u; b < m().maskLength && (null === y.match.fn && p[o] && (!0 === p[o].match.optionalQuantifier || !0 === p[o].match.optionality) || null != y.match.fn); ) {
                                if (b++,
                                !1 === g && s[b] && s[b].match.def === y.match.def)
                                    m().validPositions[b] = e.extend(!0, {}, s[b]),
                                        m().validPositions[b].input = y.input,
                                        h(b),
                                        u = b,
                                        f = !0;
                                else if (P(b, y.match.def)) {
                                    var E = M(b, y.input, !0, !0);
                                    f = !1 !== E,
                                        u = E.caret || E.insert ? v() : b,
                                        g = !0
                                } else if (!(f = !0 === y.generatedInput) && b >= m().maskLength - 1)
                                    break;
                                if (m().maskLength < k && (m().maskLength = k),
                                    f)
                                    break
                            }
                        if (!f)
                            break
                    }
                    if (!f)
                        return m().validPositions = e.extend(!0, {}, s),
                            d(!0),
                            !1
                } else
                    m().validPositions[t] = e.extend(!0, {}, n);
                return d(!0),
                    !0
            }
            function h(t) {
                for (var n = t - 1; n > -1 && !m().validPositions[n]; n--)
                    ;
                var a, r;
                for (n++; n < t; n++)
                    m().validPositions[n] === i && (!1 === l.jitMasking || l.jitMasking > n) && ("" === (r = E(n, y(n - 1).locator, n - 1).slice())[r.length - 1].match.def && r.pop(),
                    (a = k(r)) && (a.match.def === l.radixPointDefinitionSymbol || !O(n, !0) || e.inArray(l.radixPoint, A()) < n && a.match.fn && a.match.fn.test(G(n), m(), n, !1, l)) && !1 !== (C = f(n, G(n, a.match, !0) || (null == a.match.fn ? a.match.def : "" !== G(n) ? G(n) : A()[n]), !0)) && (m().validPositions[C.pos || n].generatedInput = !0))
            }
            r = !0 === r;
            var b = t;
            t.begin !== i && (b = z && !u(t) ? t.end : t.begin);
            var C = !0
                , D = e.extend(!0, {}, m().validPositions);
            if (e.isFunction(l.preValidation) && !r && !0 !== o && !0 !== c && (C = l.preValidation(A(), b, n, u(t), l)),
            !0 === C) {
                if (h(b),
                u(t) && (K(i, a.keyCode.DELETE, t, !0, !0),
                    b = m().p),
                b < m().maskLength && (W === i || b < W) && (C = f(b, n, r),
                (!r || !0 === o) && !1 === C && !0 !== c)) {
                    var T = m().validPositions[b];
                    if (!T || null !== T.match.fn || T.match.def !== n && n !== l.skipOptionalPartCharacter) {
                        if ((l.insertMode || m().validPositions[S(b)] === i) && !O(b, !0))
                            for (var L = b + 1, B = S(b); L <= B; L++)
                                if (!1 !== (C = f(L, n, r))) {
                                    !function(t, n) {
                                        var a = m().validPositions[n];
                                        if (a)
                                            for (var r = a.locator, o = r.length, s = t; s < n; s++)
                                                if (m().validPositions[s] === i && !O(s, !0)) {
                                                    var l = E(s).slice()
                                                        , c = k(l, !0)
                                                        , u = -1;
                                                    "" === l[l.length - 1].match.def && l.pop(),
                                                        e.each(l, function(e, t) {
                                                            for (var n = 0; n < o; n++) {
                                                                if (t.locator[n] === i || !w(t.locator[n].toString().split(","), r[n].toString().split(","), t.na)) {
                                                                    var a = r[n]
                                                                        , s = c.locator[n]
                                                                        , l = t.locator[n];
                                                                    a - s > Math.abs(a - l) && (c = t);
                                                                    break
                                                                }
                                                                u < n && (u = n,
                                                                    c = t)
                                                            }
                                                        }),
                                                        (c = e.extend({}, c, {
                                                            input: G(s, c.match, !0) || c.match.def
                                                        })).generatedInput = !0,
                                                        p(s, c, !0),
                                                        m().validPositions[n] = i,
                                                        f(n, a.input, !0)
                                                }
                                    }(b, C.pos !== i ? C.pos : L),
                                        b = L;
                                    break
                                }
                    } else
                        C = {
                            caret: S(b)
                        }
                }
                !1 === C && l.keepStatic && !r && !0 !== s && (C = function(t, n, a) {
                    var r, s, c, u, f, p, h, g, k = e.extend(!0, {}, m().validPositions), y = !1, b = v();
                    for (u = m().validPositions[b]; b >= 0; b--)
                        if ((c = m().validPositions[b]) && c.alternation !== i) {
                            if (r = b,
                                s = m().validPositions[r].alternation,
                            u.locator[c.alternation] !== c.locator[c.alternation])
                                break;
                            u = c
                        }
                    if (s !== i) {
                        g = parseInt(r);
                        var P = u.locator[u.alternation || s] !== i ? u.locator[u.alternation || s] : h[0];
                        P.length > 0 && (P = P.split(",")[0]);
                        var C = m().validPositions[g]
                            , A = m().validPositions[g - 1];
                        e.each(E(g, A ? A.locator : i, g - 1), function(r, c) {
                            h = c.locator[s] ? c.locator[s].toString().split(",") : [];
                            for (var u = 0; u < h.length; u++) {
                                var b = []
                                    , E = 0
                                    , A = 0
                                    , _ = !1;
                                if (P < h[u] && (c.na === i || -1 === e.inArray(h[u], c.na.split(",")) || -1 === e.inArray(P.toString(), h))) {
                                    m().validPositions[g] = e.extend(!0, {}, c);
                                    var x = m().validPositions[g].locator;
                                    for (m().validPositions[g].locator[s] = parseInt(h[u]),
                                             null == c.match.fn ? (C.input !== c.match.def && (_ = !0,
                                             !0 !== C.generatedInput && b.push(C.input)),
                                                 A++,
                                                 m().validPositions[g].generatedInput = !/[0-9a-bA-Z]/.test(c.match.def),
                                                 m().validPositions[g].input = c.match.def) : m().validPositions[g].input = C.input,
                                             f = g + 1; f < v(i, !0) + 1; f++)
                                        (p = m().validPositions[f]) && !0 !== p.generatedInput && /[0-9a-bA-Z]/.test(p.input) ? b.push(p.input) : f < t && E++,
                                            delete m().validPositions[f];
                                    for (_ && b[0] === c.match.def && b.shift(),
                                             d(!0),
                                             y = !0; b.length > 0; ) {
                                        var w = b.shift();
                                        if (w !== l.skipOptionalPartCharacter && !(y = M(v(i, !0) + 1, w, !1, o, !0)))
                                            break
                                    }
                                    if (y) {
                                        m().validPositions[g].locator = x;
                                        var O = v(t) + 1;
                                        for (f = g + 1; f < v() + 1; f++)
                                            ((p = m().validPositions[f]) === i || null == p.match.fn) && f < t + (A - E) && A++;
                                        y = M((t += A - E) > O ? O : t, n, a, o, !0)
                                    }
                                    if (y)
                                        return !1;
                                    d(),
                                        m().validPositions = e.extend(!0, {}, k)
                                }
                            }
                        })
                    }
                    return y
                }(b, n, r)),
                !0 === C && (C = {
                    pos: b
                })
            }
            if (e.isFunction(l.postValidation) && !1 !== C && !r && !0 !== o && !0 !== c) {
                var I = l.postValidation(A(!0), C, l);
                if (I.refreshFromBuffer && I.buffer) {
                    var F = I.refreshFromBuffer;
                    _(!0 === F ? F : F.start, F.end, I.buffer)
                }
                C = !0 === I ? C : I
            }
            return C && C.pos === i && (C.pos = b),
            !1 !== C && !0 !== c || (d(!0),
                m().validPositions = e.extend(!0, {}, D)),
                C
        }
        function O(e, t) {
            var n = y(e).match;
            if ("" === n.def && (n = b(e).match),
            null != n.fn)
                return n.fn;
            if (!0 !== t && e > -1) {
                var i = E(e);
                return i.length > 1 + ("" === i[i.length - 1].match.def ? 1 : 0)
            }
            return !1
        }
        function S(e, t) {
            var n = m().maskLength;
            if (e >= n)
                return n;
            var i = e;
            for (E(n + 1).length > 1 && (h(!0, n + 1, !0),
                n = m().maskLength); ++i < n && (!0 === t && (!0 !== b(i).match.newBlockMarker || !O(i)) || !0 !== t && !O(i)); )
                ;
            return i
        }
        function D(e, t) {
            var n, i = e;
            if (i <= 0)
                return 0;
            for (; --i > 0 && (!0 === t && !0 !== b(i).match.newBlockMarker || !0 !== t && !O(i) && ((n = E(i)).length < 2 || 2 === n.length && "" === n[1].match.def)); )
                ;
            return i
        }
        function j(e) {
            return m().validPositions[e] === i ? G(e) : m().validPositions[e].input
        }
        function T(t, n, a, r, o) {
            if (r && e.isFunction(l.onBeforeWrite)) {
                var s = l.onBeforeWrite.call(Z, r, n, a, l);
                if (s) {
                    if (s.refreshFromBuffer) {
                        var c = s.refreshFromBuffer;
                        _(!0 === c ? c : c.start, c.end, s.buffer || n),
                            n = A(!0)
                    }
                    a !== i && (a = s.caret !== i ? s.caret : a)
                }
            }
            t !== i && (t.inputmask._valueSet(n.join("")),
                a === i || r !== i && "blur" === r.type ? V(t, a, 0 === n.length) : p && r && "input" === r.type ? setTimeout(function() {
                    I(t, a)
                }, 0) : I(t, a),
            !0 === o && (J = !0,
                e(t).trigger("input")))
        }
        function G(t, n, a) {
            if ((n = n || b(t).match).placeholder !== i || !0 === a)
                return e.isFunction(n.placeholder) ? n.placeholder(l) : n.placeholder;
            if (null === n.fn) {
                if (t > -1 && m().validPositions[t] === i) {
                    var r, o = E(t), s = [];
                    if (o.length > 1 + ("" === o[o.length - 1].match.def ? 1 : 0))
                        for (var c = 0; c < o.length; c++)
                            if (!0 !== o[c].match.optionality && !0 !== o[c].match.optionalQuantifier && (null === o[c].match.fn || r === i || !1 !== o[c].match.fn.test(r.match.def, m(), t, !0, l)) && (s.push(o[c]),
                            null === o[c].match.fn && (r = o[c]),
                            s.length > 1 && /[0-9a-bA-Z]/.test(s[0].match.def)))
                                return l.placeholder.charAt(t % l.placeholder.length)
                }
                return n.def
            }
            return l.placeholder.charAt(t % l.placeholder.length)
        }
        function L(t, r, o, s, c) {
            function u(e, t) {
                return -1 !== C().slice(e, S(e)).join("").indexOf(t) && !O(e) && b(e).match.nativeDef === t.charAt(t.length - 1)
            }
            var f = s.slice()
                , p = ""
                , h = -1
                , g = i;
            if (d(),
            o || !0 === l.autoUnmask)
                h = S(h);
            else {
                var k = C().slice(0, S(-1)).join("")
                    , P = f.join("").match(new RegExp("^" + a.escapeRegex(k),"g"));
                P && P.length > 0 && (f.splice(0, P.length * k.length),
                    h = S(h))
            }
            if (-1 === h ? (m().p = S(h),
                h = 0) : m().p = h,
                e.each(f, function(n, a) {
                    if (a !== i)
                        if (m().validPositions[n] === i && f[n] === G(n) && O(n, !0) && !1 === M(n, f[n], !0, i, i, !0))
                            m().p++;
                        else {
                            var r = new e.Event("_checkval");
                            r.which = a.charCodeAt(0),
                                p += a;
                            var s = v(i, !0)
                                , c = m().validPositions[s]
                                , k = y(s + 1, c ? c.locator.slice() : i, s);
                            if (!u(h, p) || o || l.autoUnmask) {
                                var b = o ? n : null == k.match.fn && k.match.optionality && s + 1 < m().p ? s + 1 : m().p;
                                g = ne.keypressEvent.call(t, r, !0, !1, o, b),
                                    h = b + 1,
                                    p = ""
                            } else
                                g = ne.keypressEvent.call(t, r, !0, !1, !0, s + 1);
                            if (!1 !== g && !o && e.isFunction(l.onBeforeWrite)) {
                                var P = g;
                                if (g = l.onBeforeWrite.call(Z, r, A(), g.forwardPosition, l),
                                (g = e.extend(P, g)) && g.refreshFromBuffer) {
                                    var E = g.refreshFromBuffer;
                                    _(!0 === E ? E : E.start, E.end, g.buffer),
                                        d(!0),
                                    g.caret && (m().p = g.caret,
                                        g.forwardPosition = g.caret)
                                }
                            }
                        }
                }),
                r) {
                var E = i;
                n.activeElement === t && g && (E = l.numericInput ? D(g.forwardPosition) : g.forwardPosition),
                    T(t, A(), E, c || new e.Event("checkval"), c && "input" === c.type)
            }
        }
        function B(t) {
            if (t) {
                if (t.inputmask === i)
                    return t.value;
                t.inputmask && t.inputmask.refreshValue && ne.setValueEvent.call(t)
            }
            var n = []
                , a = m().validPositions;
            for (var r in a)
                a[r].match && null != a[r].match.fn && n.push(a[r].input);
            var o = 0 === n.length ? "" : (z ? n.reverse() : n).join("");
            if (e.isFunction(l.onUnMask)) {
                var s = (z ? A().slice().reverse() : A()).join("");
                o = l.onUnMask.call(Z, s, o, l)
            }
            return o
        }
        function I(e, a, r, o) {
            function s(e) {
                return !0 === o || !z || "number" != typeof e || l.greedy && "" === l.placeholder || (e = A().join("").length - e),
                    e
            }
            var u;
            if (a === i)
                return e.setSelectionRange ? (a = e.selectionStart,
                    r = e.selectionEnd) : t.getSelection ? (u = t.getSelection().getRangeAt(0)).commonAncestorContainer.parentNode !== e && u.commonAncestorContainer !== e || (a = u.startOffset,
                    r = u.endOffset) : n.selection && n.selection.createRange && (r = (a = 0 - (u = n.selection.createRange()).duplicate().moveStart("character", -e.inputmask._valueGet().length)) + u.text.length),
                    {
                        begin: s(a),
                        end: s(r)
                    };
            if (a.begin !== i && (r = a.end,
                a = a.begin),
            "number" == typeof a) {
                a = s(a),
                    r = "number" == typeof (r = s(r)) ? r : a;
                var f = parseInt(((e.ownerDocument.defaultView || t).getComputedStyle ? (e.ownerDocument.defaultView || t).getComputedStyle(e, null) : e.currentStyle).fontSize) * r;
                if (e.scrollLeft = f > e.scrollWidth ? f : 0,
                c || !1 !== l.insertMode || a !== r || r++,
                    e.setSelectionRange)
                    e.selectionStart = a,
                        e.selectionEnd = r;
                else if (t.getSelection) {
                    if (u = n.createRange(),
                    e.firstChild === i || null === e.firstChild) {
                        var p = n.createTextNode("");
                        e.appendChild(p)
                    }
                    u.setStart(e.firstChild, a < e.inputmask._valueGet().length ? a : e.inputmask._valueGet().length),
                        u.setEnd(e.firstChild, r < e.inputmask._valueGet().length ? r : e.inputmask._valueGet().length),
                        u.collapse(!0);
                    var h = t.getSelection();
                    h.removeAllRanges(),
                        h.addRange(u)
                } else
                    e.createTextRange && ((u = e.createTextRange()).collapse(!0),
                        u.moveEnd("character", r),
                        u.moveStart("character", a),
                        u.select());
                V(e, {
                    begin: a,
                    end: r
                })
            }
        }
        function F(t) {
            var n, a, r = A(), o = r.length, s = v(), l = {}, c = m().validPositions[s], u = c !== i ? c.locator.slice() : i;
            for (n = s + 1; n < r.length; n++)
                u = (a = y(n, u, n - 1)).locator.slice(),
                    l[n] = e.extend(!0, {}, a);
            var f = c && c.alternation !== i ? c.locator[c.alternation] : i;
            for (n = o - 1; n > s && (((a = l[n]).match.optionality || a.match.optionalQuantifier && a.match.newBlockMarker || f && (f !== l[n].locator[c.alternation] && null != a.match.fn || null === a.match.fn && a.locator[c.alternation] && w(a.locator[c.alternation].toString().split(","), f.toString().split(",")) && "" !== E(n)[0].def)) && r[n] === G(n, a.match)); n--)
                o--;
            return t ? {
                l: o,
                def: l[o] ? l[o].match : i
            } : o
        }
        function N(e) {
            for (var t, n = F(), a = e.length, r = m().validPositions[v()]; n < a && !O(n, !0) && (t = r !== i ? y(n, r.locator.slice(""), r) : b(n)) && !0 !== t.match.optionality && (!0 !== t.match.optionalQuantifier && !0 !== t.match.newBlockMarker || n + 1 === a && "" === (r !== i ? y(n + 1, r.locator.slice(""), r) : b(n + 1)).match.def); )
                n++;
            for (; (t = m().validPositions[n - 1]) && t && t.match.optionality && t.input === l.skipOptionalPartCharacter; )
                n--;
            return e.splice(n),
                e
        }
        function R(t) {
            if (e.isFunction(l.isComplete))
                return l.isComplete(t, l);
            if ("*" === l.repeat)
                return i;
            var n = !1
                , a = F(!0)
                , r = D(a.l);
            if (a.def === i || a.def.newBlockMarker || a.def.optionality || a.def.optionalQuantifier) {
                n = !0;
                for (var o = 0; o <= r; o++) {
                    var s = y(o).match;
                    if (null !== s.fn && m().validPositions[o] === i && !0 !== s.optionality && !0 !== s.optionalQuantifier || null === s.fn && t[o] !== G(o, s)) {
                        n = !1;
                        break
                    }
                }
            }
            return n
        }
        function K(t, n, r, o, s) {
            if ((l.numericInput || z) && (n === a.keyCode.BACKSPACE ? n = a.keyCode.DELETE : n === a.keyCode.DELETE && (n = a.keyCode.BACKSPACE),
                z)) {
                var c = r.end;
                r.end = r.begin,
                    r.begin = c
            }
            n === a.keyCode.BACKSPACE && (r.end - r.begin < 1 || !1 === l.insertMode) ? (r.begin = D(r.begin),
            m().validPositions[r.begin] !== i && m().validPositions[r.begin].input === l.groupSeparator && r.begin--) : n === a.keyCode.DELETE && r.begin === r.end && (r.end = O(r.end, !0) && m().validPositions[r.end] && m().validPositions[r.end].input !== l.radixPoint ? r.end + 1 : S(r.end) + 1,
            m().validPositions[r.begin] !== i && m().validPositions[r.begin].input === l.groupSeparator && r.end++),
                g(r.begin, r.end, !1, o),
            !0 !== o && function() {
                if (l.keepStatic) {
                    for (var n = [], a = v(-1, !0), r = e.extend(!0, {}, m().validPositions), o = m().validPositions[a]; a >= 0; a--) {
                        var s = m().validPositions[a];
                        if (s) {
                            if (!0 !== s.generatedInput && /[0-9a-bA-Z]/.test(s.input) && n.push(s.input),
                                delete m().validPositions[a],
                            s.alternation !== i && s.locator[s.alternation] !== o.locator[s.alternation])
                                break;
                            o = s
                        }
                    }
                    if (a > -1)
                        for (m().p = S(v(-1, !0)); n.length > 0; ) {
                            var c = new e.Event("keypress");
                            c.which = n.pop().charCodeAt(0),
                                ne.keypressEvent.call(t, c, !0, !1, !1, m().p)
                        }
                    else
                        m().validPositions = e.extend(!0, {}, r)
                }
            }();
            var u = v(r.begin, !0);
            if (u < r.begin)
                m().p = S(u);
            else if (!0 !== o && (m().p = r.begin,
            !0 !== s))
                for (; m().p < u && m().validPositions[m().p] === i; )
                    m().p++
        }
        function U(i) {
            function a(e) {
                var t, a = n.createElement("span");
                for (var o in r)
                    isNaN(o) && -1 !== o.indexOf("font") && (a.style[o] = r[o]);
                a.style.textTransform = r.textTransform,
                    a.style.letterSpacing = r.letterSpacing,
                    a.style.position = "absolute",
                    a.style.height = "auto",
                    a.style.width = "auto",
                    a.style.visibility = "hidden",
                    a.style.whiteSpace = "nowrap",
                    n.body.appendChild(a);
                var s, l = i.inputmask._valueGet(), c = 0;
                for (t = 0,
                         s = l.length; t <= s; t++) {
                    if (a.innerHTML += l.charAt(t) || "_",
                    a.offsetWidth >= e) {
                        var u = e - c
                            , f = a.offsetWidth - e;
                        a.innerHTML = l.charAt(t),
                            t = (u -= a.offsetWidth / 3) < f ? t - 1 : t;
                        break
                    }
                    c = a.offsetWidth
                }
                return n.body.removeChild(a),
                    t
            }
            var r = (i.ownerDocument.defaultView || t).getComputedStyle(i, null)
                , o = n.createElement("div");
            o.style.width = r.width,
                o.style.textAlign = r.textAlign,
                (q = n.createElement("div")).className = "im-colormask",
                i.parentNode.insertBefore(q, i),
                i.parentNode.removeChild(i),
                q.appendChild(o),
                q.appendChild(i),
                i.style.left = o.offsetLeft + "px",
                e(i).on("click", function(e) {
                    return I(i, a(e.clientX)),
                        ne.clickEvent.call(i, [e])
                }),
                e(i).on("keydown", function(e) {
                    e.shiftKey || !1 === l.insertMode || setTimeout(function() {
                        V(i)
                    }, 0)
                })
        }
        function V(e, t, a) {
            function r() {
                p || null !== s.fn && c.input !== i ? p && (null !== s.fn && c.input !== i || "" === s.def) && (p = !1,
                    f += "</span>") : (p = !0,
                    f += "<span class='im-static'>")
            }
            function o(i) {
                !0 !== i && h !== t.begin || n.activeElement !== e || (f += "<span class='im-caret' style='border-right-width: 1px;border-right-style: solid;'></span>")
            }
            var s, c, u, f = "", p = !1, h = 0;
            if (q !== i) {
                var d = A();
                if (t === i ? t = I(e) : t.begin === i && (t = {
                    begin: t,
                    end: t
                }),
                !0 !== a) {
                    var g = v();
                    do {
                        o(),
                            m().validPositions[h] ? (c = m().validPositions[h],
                                s = c.match,
                                u = c.locator.slice(),
                                r(),
                                f += d[h]) : (c = y(h, u, h - 1),
                                s = c.match,
                                u = c.locator.slice(),
                            (!1 === l.jitMasking || h < g || "number" == typeof l.jitMasking && isFinite(l.jitMasking) && l.jitMasking > h) && (r(),
                                f += G(h, s))),
                            h++
                    } while ((W === i || h < W) && (null !== s.fn || "" !== s.def) || g > h || p);-1 === f.indexOf("im-caret") && o(!0),
                    p && r()
                }
                var k = q.getElementsByTagName("div")[0];
                k.innerHTML = f,
                    e.inputmask.positionColorMask(e, k)
            }
        }
        o = o || this.maskset,
            l = l || this.opts;
        var H, Q, W, q, Z = this, $ = this.el, z = this.isRTL, X = !1, J = !1, Y = !1, ee = !1, te = {
            on: function(t, n, r) {
                var o = function(t) {
                    if (this.inputmask === i && "FORM" !== this.nodeName) {
                        var n = e.data(this, "_inputmask_opts");
                        n ? new a(n).mask(this) : te.off(this)
                    } else {
                        if ("setvalue" === t.type || "FORM" === this.nodeName || !(this.disabled || this.readOnly && !("keydown" === t.type && t.ctrlKey && 67 === t.keyCode || !1 === l.tabThrough && t.keyCode === a.keyCode.TAB))) {
                            switch (t.type) {
                                case "input":
                                    if (!0 === J)
                                        return J = !1,
                                            t.preventDefault();
                                    break;
                                case "keydown":
                                    X = !1,
                                        J = !1;
                                    break;
                                case "keypress":
                                    if (!0 === X)
                                        return t.preventDefault();
                                    X = !0;
                                    break;
                                case "click":
                                    if (u || f) {
                                        var o = this
                                            , s = arguments;
                                        return setTimeout(function() {
                                            r.apply(o, s)
                                        }, 0),
                                            !1
                                    }
                            }
                            var c = r.apply(this, arguments);
                            return !1 === c && (t.preventDefault(),
                                t.stopPropagation()),
                                c
                        }
                        t.preventDefault()
                    }
                };
                t.inputmask.events[n] = t.inputmask.events[n] || [],
                    t.inputmask.events[n].push(o),
                    -1 !== e.inArray(n, ["submit", "reset"]) ? null !== t.form && e(t.form).on(n, o) : e(t).on(n, o)
            },
            off: function(t, n) {
                if (t.inputmask && t.inputmask.events) {
                    var i;
                    n ? (i = [])[n] = t.inputmask.events[n] : i = t.inputmask.events,
                        e.each(i, function(n, i) {
                            for (; i.length > 0; ) {
                                var a = i.pop();
                                -1 !== e.inArray(n, ["submit", "reset"]) ? null !== t.form && e(t.form).off(n, a) : e(t).off(n, a)
                            }
                            delete t.inputmask.events[n]
                        })
                }
            }
        }, ne = {
            keydownEvent: function(t) {
                var i = this
                    , r = e(i)
                    , o = t.keyCode
                    , s = I(i);
                if (o === a.keyCode.BACKSPACE || o === a.keyCode.DELETE || f && o === a.keyCode.BACKSPACE_SAFARI || t.ctrlKey && o === a.keyCode.X && !function(e) {
                    var t = n.createElement("input")
                        , i = "on" + e
                        , a = i in t;
                    return a || (t.setAttribute(i, "return;"),
                        a = "function" == typeof t[i]),
                        t = null,
                        a
                }("cut"))
                    t.preventDefault(),
                        K(i, o, s),
                        T(i, A(!0), m().p, t, i.inputmask._valueGet() !== A().join("")),
                        i.inputmask._valueGet() === C().join("") ? r.trigger("cleared") : !0 === R(A()) && r.trigger("complete");
                else if (o === a.keyCode.END || o === a.keyCode.PAGE_DOWN) {
                    t.preventDefault();
                    var c = S(v());
                    l.insertMode || c !== m().maskLength || t.shiftKey || c--,
                        I(i, t.shiftKey ? s.begin : c, c, !0)
                } else
                    o === a.keyCode.HOME && !t.shiftKey || o === a.keyCode.PAGE_UP ? (t.preventDefault(),
                        I(i, 0, t.shiftKey ? s.begin : 0, !0)) : (l.undoOnEscape && o === a.keyCode.ESCAPE || 90 === o && t.ctrlKey) && !0 !== t.altKey ? (L(i, !0, !1, H.split("")),
                        r.trigger("click")) : o !== a.keyCode.INSERT || t.shiftKey || t.ctrlKey ? !0 === l.tabThrough && o === a.keyCode.TAB ? (!0 === t.shiftKey ? (null === b(s.begin).match.fn && (s.begin = S(s.begin)),
                        s.end = D(s.begin, !0),
                        s.begin = D(s.end, !0)) : (s.begin = S(s.begin, !0),
                        s.end = S(s.begin, !0),
                    s.end < m().maskLength && s.end--),
                    s.begin < m().maskLength && (t.preventDefault(),
                        I(i, s.begin, s.end))) : t.shiftKey || !1 === l.insertMode && (o === a.keyCode.RIGHT ? setTimeout(function() {
                        var e = I(i);
                        I(i, e.begin)
                    }, 0) : o === a.keyCode.LEFT && setTimeout(function() {
                        var e = I(i);
                        I(i, z ? e.begin + 1 : e.begin - 1)
                    }, 0)) : (l.insertMode = !l.insertMode,
                        I(i, l.insertMode || s.begin !== m().maskLength ? s.begin : s.begin - 1));
                l.onKeyDown.call(this, t, A(), I(i).begin, l),
                    Y = -1 !== e.inArray(o, l.ignorables)
            },
            keypressEvent: function(t, n, r, o, s) {
                var c = this
                    , u = e(c)
                    , f = t.which || t.charCode || t.keyCode;
                if (!(!0 === n || t.ctrlKey && t.altKey) && (t.ctrlKey || t.metaKey || Y))
                    return f === a.keyCode.ENTER && H !== A().join("") && (H = A().join(""),
                        setTimeout(function() {
                            u.trigger("change")
                        }, 0)),
                        !0;
                if (f) {
                    46 === f && !1 === t.shiftKey && "" !== l.radixPoint && (f = l.radixPoint.charCodeAt(0));
                    var p, h = n ? {
                        begin: s,
                        end: s
                    } : I(c), v = String.fromCharCode(f);
                    m().writeOutBuffer = !0;
                    var g = M(h, v, o);
                    if (!1 !== g && (d(!0),
                        p = g.caret !== i ? g.caret : n ? g.pos + 1 : S(g.pos),
                        m().p = p),
                    !1 !== r && (setTimeout(function() {
                        l.onKeyValidation.call(c, f, g, l)
                    }, 0),
                    m().writeOutBuffer && !1 !== g)) {
                        var k = A();
                        T(c, k, l.numericInput && g.caret === i ? D(p) : p, t, !0 !== n),
                        !0 !== n && setTimeout(function() {
                            !0 === R(k) && u.trigger("complete")
                        }, 0)
                    }
                    if (t.preventDefault(),
                        n)
                        return !1 !== g && (g.forwardPosition = p),
                            g
                }
            },
            pasteEvent: function(n) {
                var i, a = this, r = n.originalEvent || n, o = e(a), s = a.inputmask._valueGet(!0), c = I(a);
                z && (i = c.end,
                    c.end = c.begin,
                    c.begin = i);
                var u = s.substr(0, c.begin)
                    , f = s.substr(c.end, s.length);
                if (u === (z ? C().reverse() : C()).slice(0, c.begin).join("") && (u = ""),
                f === (z ? C().reverse() : C()).slice(c.end).join("") && (f = ""),
                z && (i = u,
                    u = f,
                    f = i),
                t.clipboardData && t.clipboardData.getData)
                    s = u + t.clipboardData.getData("Text") + f;
                else {
                    if (!r.clipboardData || !r.clipboardData.getData)
                        return !0;
                    s = u + r.clipboardData.getData("text/plain") + f
                }
                var p = s;
                if (e.isFunction(l.onBeforePaste)) {
                    if (!1 === (p = l.onBeforePaste.call(Z, s, l)))
                        return n.preventDefault();
                    p || (p = s)
                }
                return L(a, !1, !1, z ? p.split("").reverse() : p.toString().split("")),
                    T(a, A(), S(v()), n, H !== A().join("")),
                !0 === R(A()) && o.trigger("complete"),
                    n.preventDefault()
            },
            inputFallBackEvent: function(t) {
                var n = this
                    , i = n.inputmask._valueGet();
                if (A().join("") !== i) {
                    var r = I(n);
                    if (!1 === function(t, n, i) {
                        if ("." === n.charAt(i.begin - 1) && "" !== l.radixPoint && ((n = n.split(""))[i.begin - 1] = l.radixPoint.charAt(0),
                            n = n.join("")),
                        n.charAt(i.begin - 1) === l.radixPoint && n.length > A().length) {
                            var a = new e.Event("keypress");
                            return a.which = l.radixPoint.charCodeAt(0),
                                ne.keypressEvent.call(t, a, !0, !0, !1, i.begin - 1),
                                !1
                        }
                    }(n, i, r))
                        return !1;
                    if (i = i.replace(new RegExp("(" + a.escapeRegex(C().join("")) + ")*"), ""),
                    !1 === function(t, n, i) {
                        if (u) {
                            var a = n.replace(A().join(""), "");
                            if (1 === a.length) {
                                var r = new e.Event("keypress");
                                return r.which = a.charCodeAt(0),
                                    ne.keypressEvent.call(t, r, !0, !0, !1, m().validPositions[i.begin - 1] ? i.begin : i.begin - 1),
                                    !1
                            }
                        }
                    }(n, i, r))
                        return !1;
                    r.begin > i.length && (I(n, i.length),
                        r = I(n));
                    var o = A().join("")
                        , s = i.substr(0, r.begin)
                        , c = i.substr(r.begin)
                        , f = o.substr(0, r.begin)
                        , p = o.substr(r.begin)
                        , h = r
                        , d = ""
                        , v = !1;
                    if (s !== f) {
                        h.begin = 0;
                        for (var g = (v = s.length >= f.length) ? s.length : f.length, k = 0; s.charAt(k) === f.charAt(k) && k < g; k++)
                            h.begin++;
                        v && (d += s.slice(h.begin, h.end))
                    }
                    c !== p && (c.length > p.length ? v && (h.end = h.begin) : c.length < p.length ? h.end += p.length - c.length : c.charAt(0) !== p.charAt(0) && h.end++),
                        T(n, A(), h),
                        d.length > 0 ? e.each(d.split(""), function(t, i) {
                            var a = new e.Event("keypress");
                            a.which = i.charCodeAt(0),
                                Y = !1,
                                ne.keypressEvent.call(n, a)
                        }) : (h.begin === h.end - 1 && I(n, D(h.begin + 1), h.end),
                            t.keyCode = a.keyCode.DELETE,
                            ne.keydownEvent.call(n, t)),
                        t.preventDefault()
                }
            },
            setValueEvent: function(t) {
                this.inputmask.refreshValue = !1;
                var n = this
                    , i = n.inputmask._valueGet(!0);
                e.isFunction(l.onBeforeMask) && (i = l.onBeforeMask.call(Z, i, l) || i),
                    i = i.split(""),
                    L(n, !0, !1, z ? i.reverse() : i),
                    H = A().join(""),
                (l.clearMaskOnLostFocus || l.clearIncomplete) && n.inputmask._valueGet() === C().join("") && n.inputmask._valueSet("")
            },
            focusEvent: function(e) {
                var t = this
                    , n = t.inputmask._valueGet();
                l.showMaskOnFocus && (!l.showMaskOnHover || l.showMaskOnHover && "" === n) && (t.inputmask._valueGet() !== A().join("") ? T(t, A(), S(v())) : !1 === ee && I(t, S(v()))),
                !0 === l.positionCaretOnTab && !1 === ee && "" !== n && (T(t, A(), I(t)),
                    ne.clickEvent.apply(t, [e, !0])),
                    H = A().join("")
            },
            mouseleaveEvent: function(e) {
                var t = this;
                if (ee = !1,
                l.clearMaskOnLostFocus && n.activeElement !== t) {
                    var i = A().slice()
                        , a = t.inputmask._valueGet();
                    a !== t.getAttribute("placeholder") && "" !== a && (-1 === v() && a === C().join("") ? i = [] : N(i),
                        T(t, i))
                }
            },
            clickEvent: function(t, a) {
                function r(t) {
                    if ("" !== l.radixPoint) {
                        var n = m().validPositions;
                        if (n[t] === i || n[t].input === G(t)) {
                            if (t < S(-1))
                                return !0;
                            var a = e.inArray(l.radixPoint, A());
                            if (-1 !== a) {
                                for (var r in n)
                                    if (a < r && n[r].input !== G(r))
                                        return !1;
                                return !0
                            }
                        }
                    }
                    return !1
                }
                var o = this;
                setTimeout(function() {
                    if (n.activeElement === o) {
                        var e = I(o);
                        if (a && (z ? e.end = e.begin : e.begin = e.end),
                        e.begin === e.end)
                            switch (l.positionCaretOnClick) {
                                case "none":
                                    break;
                                case "radixFocus":
                                    if (r(e.begin)) {
                                        var t = A().join("").indexOf(l.radixPoint);
                                        I(o, l.numericInput ? S(t) : t);
                                        break
                                    }
                                default:
                                    var s = e.begin
                                        , c = v(s, !0)
                                        , u = S(c);
                                    if (s < u)
                                        I(o, O(s, !0) || O(s - 1, !0) ? s : S(s));
                                    else {
                                        var f = m().validPositions[c]
                                            , p = y(u, f ? f.match.locator : i, f)
                                            , h = G(u, p.match);
                                        if ("" !== h && A()[u] !== h && !0 !== p.match.optionalQuantifier && !0 !== p.match.newBlockMarker || !O(u, !0) && p.match.def === h) {
                                            var d = S(u);
                                            (s >= d || s === u) && (u = d)
                                        }
                                        I(o, u)
                                    }
                            }
                    }
                }, 0)
            },
            dblclickEvent: function(e) {
                var t = this;
                setTimeout(function() {
                    I(t, 0, S(v()))
                }, 0)
            },
            cutEvent: function(i) {
                var r = this
                    , o = e(r)
                    , s = I(r)
                    , l = i.originalEvent || i
                    , c = t.clipboardData || l.clipboardData
                    , u = z ? A().slice(s.end, s.begin) : A().slice(s.begin, s.end);
                c.setData("text", z ? u.reverse().join("") : u.join("")),
                n.execCommand && n.execCommand("copy"),
                    K(r, a.keyCode.DELETE, s),
                    T(r, A(), m().p, i, H !== A().join("")),
                r.inputmask._valueGet() === C().join("") && o.trigger("cleared")
            },
            blurEvent: function(t) {
                var n = e(this)
                    , a = this;
                if (a.inputmask) {
                    var r = a.inputmask._valueGet()
                        , o = A().slice();
                    "" !== r && (l.clearMaskOnLostFocus && (-1 === v() && r === C().join("") ? o = [] : N(o)),
                    !1 === R(o) && (setTimeout(function() {
                        n.trigger("incomplete")
                    }, 0),
                    l.clearIncomplete && (d(),
                        o = l.clearMaskOnLostFocus ? [] : C().slice())),
                        T(a, o, i, t)),
                    H !== A().join("") && (H = o.join(""),
                        n.trigger("change"))
                }
            },
            mouseenterEvent: function(e) {
                var t = this;
                ee = !0,
                n.activeElement !== t && l.showMaskOnHover && t.inputmask._valueGet() !== A().join("") && T(t, A())
            },
            submitEvent: function(e) {
                H !== A().join("") && Q.trigger("change"),
                l.clearMaskOnLostFocus && -1 === v() && $.inputmask._valueGet && $.inputmask._valueGet() === C().join("") && $.inputmask._valueSet(""),
                l.removeMaskOnSubmit && ($.inputmask._valueSet($.inputmask.unmaskedvalue(), !0),
                    setTimeout(function() {
                        T($, A())
                    }, 0))
            },
            resetEvent: function(e) {
                $.inputmask.refreshValue = !0,
                    setTimeout(function() {
                        Q.trigger("setvalue")
                    }, 0)
            }
        };
        a.prototype.positionColorMask = function(e, t) {
            e.style.left = t.offsetLeft + "px"
        }
        ;
        var ie;
        if (r !== i)
            switch (r.action) {
                case "isComplete":
                    return $ = r.el,
                        R(A());
                case "unmaskedvalue":
                    return $ !== i && r.value === i || (ie = r.value,
                        ie = (e.isFunction(l.onBeforeMask) ? l.onBeforeMask.call(Z, ie, l) || ie : ie).split(""),
                        L(i, !1, !1, z ? ie.reverse() : ie),
                    e.isFunction(l.onBeforeWrite) && l.onBeforeWrite.call(Z, i, A(), 0, l)),
                        B($);
                case "mask":
                    !function(t) {
                        te.off(t);
                        var a = function(t, a) {
                            var r = t.getAttribute("type")
                                , o = "INPUT" === t.tagName && -1 !== e.inArray(r, a.supportsInputType) || t.isContentEditable || "TEXTAREA" === t.tagName;
                            if (!o)
                                if ("INPUT" === t.tagName) {
                                    var s = n.createElement("input");
                                    s.setAttribute("type", r),
                                        o = "text" === s.type,
                                        s = null
                                } else
                                    o = "partial";
                            return !1 !== o ? function(t) {
                                function r() {
                                    return this.inputmask ? this.inputmask.opts.autoUnmask ? this.inputmask.unmaskedvalue() : -1 !== v() || !0 !== a.nullable ? n.activeElement === this && a.clearMaskOnLostFocus ? (z ? N(A().slice()).reverse() : N(A().slice())).join("") : s.call(this) : "" : s.call(this)
                                }
                                function o(t) {
                                    l.call(this, t),
                                    this.inputmask && e(this).trigger("setvalue")
                                }
                                var s, l;
                                if (!t.inputmask.__valueGet) {
                                    if (!0 !== a.noValuePatching) {
                                        if (Object.getOwnPropertyDescriptor) {
                                            "function" != typeof Object.getPrototypeOf && (Object.getPrototypeOf = "object" == typeof "test".__proto__ ? function(e) {
                                                        return e.__proto__
                                                    }
                                                    : function(e) {
                                                        return e.constructor.prototype
                                                    }
                                            );
                                            var c = Object.getPrototypeOf ? Object.getOwnPropertyDescriptor(Object.getPrototypeOf(t), "value") : i;
                                            c && c.get && c.set ? (s = c.get,
                                                l = c.set,
                                                Object.defineProperty(t, "value", {
                                                    get: r,
                                                    set: o,
                                                    configurable: !0
                                                })) : "INPUT" !== t.tagName && (s = function() {
                                                return this.textContent
                                            }
                                                ,
                                                l = function(e) {
                                                    this.textContent = e
                                                }
                                                ,
                                                Object.defineProperty(t, "value", {
                                                    get: r,
                                                    set: o,
                                                    configurable: !0
                                                }))
                                        } else
                                            n.__lookupGetter__ && t.__lookupGetter__("value") && (s = t.__lookupGetter__("value"),
                                                l = t.__lookupSetter__("value"),
                                                t.__defineGetter__("value", r),
                                                t.__defineSetter__("value", o));
                                        t.inputmask.__valueGet = s,
                                            t.inputmask.__valueSet = l
                                    }
                                    t.inputmask._valueGet = function(e) {
                                        return z && !0 !== e ? s.call(this.el).split("").reverse().join("") : s.call(this.el)
                                    }
                                        ,
                                        t.inputmask._valueSet = function(e, t) {
                                            l.call(this.el, null === e || e === i ? "" : !0 !== t && z ? e.split("").reverse().join("") : e)
                                        }
                                        ,
                                    s === i && (s = function() {
                                        return this.value
                                    }
                                        ,
                                        l = function(e) {
                                            this.value = e
                                        }
                                        ,
                                        function(t) {
                                            if (e.valHooks && (e.valHooks[t] === i || !0 !== e.valHooks[t].inputmaskpatch)) {
                                                var n = e.valHooks[t] && e.valHooks[t].get ? e.valHooks[t].get : function(e) {
                                                        return e.value
                                                    }
                                                    , r = e.valHooks[t] && e.valHooks[t].set ? e.valHooks[t].set : function(e, t) {
                                                        return e.value = t,
                                                            e
                                                    }
                                                ;
                                                e.valHooks[t] = {
                                                    get: function(e) {
                                                        if (e.inputmask) {
                                                            if (e.inputmask.opts.autoUnmask)
                                                                return e.inputmask.unmaskedvalue();
                                                            var t = n(e);
                                                            return -1 !== v(i, i, e.inputmask.maskset.validPositions) || !0 !== a.nullable ? t : ""
                                                        }
                                                        return n(e)
                                                    },
                                                    set: function(t, n) {
                                                        var i, a = e(t);
                                                        return i = r(t, n),
                                                        t.inputmask && a.trigger("setvalue"),
                                                            i
                                                    },
                                                    inputmaskpatch: !0
                                                }
                                            }
                                        }(t.type),
                                        function(t) {
                                            te.on(t, "mouseenter", function(t) {
                                                var n = e(this);
                                                this.inputmask._valueGet() !== A().join("") && n.trigger("setvalue")
                                            })
                                        }(t))
                                }
                            }(t) : t.inputmask = i,
                                o
                        }(t, l);
                        if (!1 !== a && ($ = t,
                            Q = e($),
                        -1 === (W = $ !== i ? $.maxLength : i) && (W = i),
                        !0 === l.colorMask && U($),
                        p && ($.hasOwnProperty("inputmode") && ($.inputmode = l.inputmode,
                            $.setAttribute("inputmode", l.inputmode)),
                        "rtfm" === l.androidHack && (!0 !== l.colorMask && U($),
                            $.type = "password")),
                        !0 === a && (te.on($, "submit", ne.submitEvent),
                            te.on($, "reset", ne.resetEvent),
                            te.on($, "mouseenter", ne.mouseenterEvent),
                            te.on($, "blur", ne.blurEvent),
                            te.on($, "focus", ne.focusEvent),
                            te.on($, "mouseleave", ne.mouseleaveEvent),
                        !0 !== l.colorMask && te.on($, "click", ne.clickEvent),
                            te.on($, "dblclick", ne.dblclickEvent),
                            te.on($, "paste", ne.pasteEvent),
                            te.on($, "dragdrop", ne.pasteEvent),
                            te.on($, "drop", ne.pasteEvent),
                            te.on($, "cut", ne.cutEvent),
                            te.on($, "complete", l.oncomplete),
                            te.on($, "incomplete", l.onincomplete),
                            te.on($, "cleared", l.oncleared),
                            p || !0 === l.inputEventOnly ? $.removeAttribute("maxLength") : (te.on($, "keydown", ne.keydownEvent),
                                te.on($, "keypress", ne.keypressEvent)),
                            te.on($, "compositionstart", e.noop),
                            te.on($, "compositionupdate", e.noop),
                            te.on($, "compositionend", e.noop),
                            te.on($, "keyup", e.noop),
                            te.on($, "input", ne.inputFallBackEvent),
                            te.on($, "beforeinput", e.noop)),
                            te.on($, "setvalue", ne.setValueEvent),
                            H = C().join(""),
                        "" !== $.inputmask._valueGet(!0) || !1 === l.clearMaskOnLostFocus || n.activeElement === $)) {
                            var r = e.isFunction(l.onBeforeMask) ? l.onBeforeMask.call(Z, $.inputmask._valueGet(!0), l) || $.inputmask._valueGet(!0) : $.inputmask._valueGet(!0);
                            "" !== r && L($, !0, !1, z ? r.split("").reverse() : r.split(""));
                            var o = A().slice();
                            H = o.join(""),
                            !1 === R(o) && l.clearIncomplete && d(),
                            l.clearMaskOnLostFocus && n.activeElement !== $ && (-1 === v() ? o = [] : N(o)),
                                T($, o),
                            n.activeElement === $ && I($, S(v()))
                        }
                    }($);
                    break;
                case "format":
                    return ie = (e.isFunction(l.onBeforeMask) ? l.onBeforeMask.call(Z, r.value, l) || r.value : r.value).split(""),
                        L(i, !0, !1, z ? ie.reverse() : ie),
                        r.metadata ? {
                            value: z ? A().slice().reverse().join("") : A().join(""),
                            metadata: s.call(this, {
                                action: "getmetadata"
                            }, o, l)
                        } : z ? A().slice().reverse().join("") : A().join("");
                case "isValid":
                    r.value ? (ie = r.value.split(""),
                        L(i, !0, !0, z ? ie.reverse() : ie)) : r.value = A().join("");
                    for (var ae = A(), re = F(), oe = ae.length - 1; oe > re && !O(oe); oe--)
                        ;
                    return ae.splice(re, oe + 1 - re),
                    R(ae) && r.value === A().join("");
                case "getemptymask":
                    return C().join("");
                case "remove":
                    if ($ && $.inputmask) {
                        Q = e($),
                            $.inputmask._valueSet(l.autoUnmask ? B($) : $.inputmask._valueGet(!0)),
                            te.off($);
                        Object.getOwnPropertyDescriptor && Object.getPrototypeOf ? Object.getOwnPropertyDescriptor(Object.getPrototypeOf($), "value") && $.inputmask.__valueGet && Object.defineProperty($, "value", {
                            get: $.inputmask.__valueGet,
                            set: $.inputmask.__valueSet,
                            configurable: !0
                        }) : n.__lookupGetter__ && $.__lookupGetter__("value") && $.inputmask.__valueGet && ($.__defineGetter__("value", $.inputmask.__valueGet),
                            $.__defineSetter__("value", $.inputmask.__valueSet)),
                            $.inputmask = i
                    }
                    return $;
                case "getmetadata":
                    if (e.isArray(o.metadata)) {
                        var se = h(!0, 0, !1).join("");
                        return e.each(o.metadata, function(e, t) {
                            if (t.mask === se)
                                return se = t,
                                    !1
                        }),
                            se
                    }
                    return o.metadata
            }
    }
    var l = navigator.userAgent
        , c = /mobile/i.test(l)
        , u = /iemobile/i.test(l)
        , f = /iphone/i.test(l) && !u
        , p = /android/i.test(l) && !u;
    return a.prototype = {
        dataAttribute: "data-inputmask",
        defaults: {
            placeholder: "_",
            optionalmarker: {
                start: "[",
                end: "]"
            },
            quantifiermarker: {
                start: "{",
                end: "}"
            },
            groupmarker: {
                start: "(",
                end: ")"
            },
            alternatormarker: "|",
            escapeChar: "\\",
            mask: null,
            regex: null,
            oncomplete: e.noop,
            onincomplete: e.noop,
            oncleared: e.noop,
            repeat: 0,
            greedy: !0,
            autoUnmask: !1,
            removeMaskOnSubmit: !1,
            clearMaskOnLostFocus: !0,
            insertMode: !0,
            clearIncomplete: !1,
            alias: null,
            onKeyDown: e.noop,
            onBeforeMask: null,
            onBeforePaste: function(t, n) {
                return e.isFunction(n.onBeforeMask) ? n.onBeforeMask.call(this, t, n) : t
            },
            onBeforeWrite: null,
            onUnMask: null,
            showMaskOnFocus: !0,
            showMaskOnHover: !0,
            onKeyValidation: e.noop,
            skipOptionalPartCharacter: " ",
            numericInput: !1,
            rightAlign: !1,
            undoOnEscape: !0,
            radixPoint: "",
            radixPointDefinitionSymbol: i,
            groupSeparator: "",
            keepStatic: null,
            positionCaretOnTab: !0,
            tabThrough: !1,
            supportsInputType: ["text", "tel", "password"],
            ignorables: [8, 9, 13, 19, 27, 33, 34, 35, 36, 37, 38, 39, 40, 45, 46, 93, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 0, 229],
            isComplete: null,
            canClearPosition: e.noop,
            preValidation: null,
            postValidation: null,
            staticDefinitionSymbol: i,
            jitMasking: !1,
            nullable: !0,
            inputEventOnly: !1,
            noValuePatching: !1,
            positionCaretOnClick: "lvp",
            casing: null,
            inputmode: "verbatim",
            colorMask: !1,
            androidHack: !1,
            importDataAttributes: !0
        },
        definitions: {
            9: {
                validator: "[0-9１-９]",
                cardinality: 1,
                definitionSymbol: "*"
            },
            a: {
                validator: "[A-Za-zА-яЁёÀ-ÿµ]",
                cardinality: 1,
                definitionSymbol: "*"
            },
            "*": {
                validator: "[0-9１-９A-Za-zА-яЁёÀ-ÿµ]",
                cardinality: 1
            }
        },
        aliases: {},
        masksCache: {},
        mask: function(l) {
            function c(n, a, o, s) {
                function l(e, a) {
                    null !== (a = a !== i ? a : n.getAttribute(s + "-" + e)) && ("string" == typeof a && (0 === e.indexOf("on") ? a = t[a] : "false" === a ? a = !1 : "true" === a && (a = !0)),
                        o[e] = a)
                }
                if (!0 === a.importDataAttributes) {
                    var c, u, f, p, h = n.getAttribute(s);
                    if (h && "" !== h && (h = h.replace(new RegExp("'","g"), '"'),
                        u = JSON.parse("{" + h + "}")),
                        u) {
                        f = i;
                        for (p in u)
                            if ("alias" === p.toLowerCase()) {
                                f = u[p];
                                break
                            }
                    }
                    l("alias", f),
                    o.alias && r(o.alias, o, a);
                    for (c in a) {
                        if (u) {
                            f = i;
                            for (p in u)
                                if (p.toLowerCase() === c.toLowerCase()) {
                                    f = u[p];
                                    break
                                }
                        }
                        l(c, f)
                    }
                }
                return e.extend(!0, a, o),
                ("rtl" === n.dir || a.rightAlign) && (n.style.textAlign = "right"),
                ("rtl" === n.dir || a.numericInput) && (n.dir = "ltr",
                    n.removeAttribute("dir"),
                    a.isRTL = !0),
                    a
            }
            var u = this;
            return "string" == typeof l && (l = n.getElementById(l) || n.querySelectorAll(l)),
                l = l.nodeName ? [l] : l,
                e.each(l, function(t, n) {
                    var r = e.extend(!0, {}, u.opts);
                    c(n, r, e.extend(!0, {}, u.userOptions), u.dataAttribute);
                    var l = o(r, u.noMasksCache);
                    l !== i && (n.inputmask !== i && (n.inputmask.opts.autoUnmask = !0,
                        n.inputmask.remove()),
                        n.inputmask = new a(i,i,!0),
                        n.inputmask.opts = r,
                        n.inputmask.noMasksCache = u.noMasksCache,
                        n.inputmask.userOptions = e.extend(!0, {}, u.userOptions),
                        n.inputmask.isRTL = r.isRTL || r.numericInput,
                        n.inputmask.el = n,
                        n.inputmask.maskset = l,
                        e.data(n, "_inputmask_opts", r),
                        s.call(n.inputmask, {
                            action: "mask"
                        }))
                }),
                l && l[0] ? l[0].inputmask || this : this
        },
        option: function(t, n) {
            return "string" == typeof t ? this.opts[t] : "object" == typeof t ? (e.extend(this.userOptions, t),
            this.el && !0 !== n && this.mask(this.el),
                this) : void 0
        },
        unmaskedvalue: function(e) {
            return this.maskset = this.maskset || o(this.opts, this.noMasksCache),
                s.call(this, {
                    action: "unmaskedvalue",
                    value: e
                })
        },
        remove: function() {
            return s.call(this, {
                action: "remove"
            })
        },
        getemptymask: function() {
            return this.maskset = this.maskset || o(this.opts, this.noMasksCache),
                s.call(this, {
                    action: "getemptymask"
                })
        },
        hasMaskedValue: function() {
            return !this.opts.autoUnmask
        },
        isComplete: function() {
            return this.maskset = this.maskset || o(this.opts, this.noMasksCache),
                s.call(this, {
                    action: "isComplete"
                })
        },
        getmetadata: function() {
            return this.maskset = this.maskset || o(this.opts, this.noMasksCache),
                s.call(this, {
                    action: "getmetadata"
                })
        },
        isValid: function(e) {
            return this.maskset = this.maskset || o(this.opts, this.noMasksCache),
                s.call(this, {
                    action: "isValid",
                    value: e
                })
        },
        format: function(e, t) {
            return this.maskset = this.maskset || o(this.opts, this.noMasksCache),
                s.call(this, {
                    action: "format",
                    value: e,
                    metadata: t
                })
        },
        analyseMask: function(t, n, r) {
            function o(e, t, n, i) {
                this.matches = [],
                    this.openGroup = e || !1,
                    this.alternatorGroup = !1,
                    this.isGroup = e || !1,
                    this.isOptional = t || !1,
                    this.isQuantifier = n || !1,
                    this.isAlternator = i || !1,
                    this.quantifier = {
                        min: 1,
                        max: 1
                    }
            }
            function s(t, o, s) {
                s = s !== i ? s : t.matches.length;
                var l = t.matches[s - 1];
                if (n)
                    0 === o.indexOf("[") || b && /\\d|\\s|\\w]/i.test(o) || "." === o ? t.matches.splice(s++, 0, {
                        fn: new RegExp(o,r.casing ? "i" : ""),
                        cardinality: 1,
                        optionality: t.isOptional,
                        newBlockMarker: l === i || l.def !== o,
                        casing: null,
                        def: o,
                        placeholder: i,
                        nativeDef: o
                    }) : (b && (o = o[o.length - 1]),
                        e.each(o.split(""), function(e, n) {
                            l = t.matches[s - 1],
                                t.matches.splice(s++, 0, {
                                    fn: null,
                                    cardinality: 0,
                                    optionality: t.isOptional,
                                    newBlockMarker: l === i || l.def !== n && null !== l.fn,
                                    casing: null,
                                    def: r.staticDefinitionSymbol || n,
                                    placeholder: r.staticDefinitionSymbol !== i ? n : i,
                                    nativeDef: n
                                })
                        })),
                        b = !1;
                else {
                    var c = (r.definitions ? r.definitions[o] : i) || a.prototype.definitions[o];
                    if (c && !b) {
                        for (var u = c.prevalidator, f = u ? u.length : 0, p = 1; p < c.cardinality; p++) {
                            var h = f >= p ? u[p - 1] : []
                                , m = h.validator
                                , d = h.cardinality;
                            t.matches.splice(s++, 0, {
                                fn: m ? "string" == typeof m ? new RegExp(m,r.casing ? "i" : "") : new function() {
                                        this.test = m
                                    }
                                    : new RegExp("."),
                                cardinality: d || 1,
                                optionality: t.isOptional,
                                newBlockMarker: l === i || l.def !== (c.definitionSymbol || o),
                                casing: c.casing,
                                def: c.definitionSymbol || o,
                                placeholder: c.placeholder,
                                nativeDef: o
                            }),
                                l = t.matches[s - 1]
                        }
                        t.matches.splice(s++, 0, {
                            fn: c.validator ? "string" == typeof c.validator ? new RegExp(c.validator,r.casing ? "i" : "") : new function() {
                                    this.test = c.validator
                                }
                                : new RegExp("."),
                            cardinality: c.cardinality,
                            optionality: t.isOptional,
                            newBlockMarker: l === i || l.def !== (c.definitionSymbol || o),
                            casing: c.casing,
                            def: c.definitionSymbol || o,
                            placeholder: c.placeholder,
                            nativeDef: o
                        })
                    } else
                        t.matches.splice(s++, 0, {
                            fn: null,
                            cardinality: 0,
                            optionality: t.isOptional,
                            newBlockMarker: l === i || l.def !== o && null !== l.fn,
                            casing: null,
                            def: r.staticDefinitionSymbol || o,
                            placeholder: r.staticDefinitionSymbol !== i ? o : i,
                            nativeDef: o
                        }),
                            b = !1
                }
            }
            function l(t) {
                t && t.matches && e.each(t.matches, function(e, a) {
                    var o = t.matches[e + 1];
                    (o === i || o.matches === i || !1 === o.isQuantifier) && a && a.isGroup && (a.isGroup = !1,
                    n || (s(a, r.groupmarker.start, 0),
                    !0 !== a.openGroup && s(a, r.groupmarker.end))),
                        l(a)
                })
            }
            function c() {
                if (E.length > 0) {
                    if (m = E[E.length - 1],
                        s(m, p),
                        m.isAlternator) {
                        d = E.pop();
                        for (var e = 0; e < d.matches.length; e++)
                            d.matches[e].isGroup = !1;
                        E.length > 0 ? (m = E[E.length - 1]).matches.push(d) : P.matches.push(d)
                    }
                } else
                    s(P, p)
            }
            function u(e) {
                e.matches = e.matches.reverse();
                for (var t in e.matches)
                    if (e.matches.hasOwnProperty(t)) {
                        var n = parseInt(t);
                        if (e.matches[t].isQuantifier && e.matches[n + 1] && e.matches[n + 1].isGroup) {
                            var a = e.matches[t];
                            e.matches.splice(t, 1),
                                e.matches.splice(n + 1, 0, a)
                        }
                        e.matches[t].matches !== i ? e.matches[t] = u(e.matches[t]) : e.matches[t] = function(e) {
                            return e === r.optionalmarker.start ? e = r.optionalmarker.end : e === r.optionalmarker.end ? e = r.optionalmarker.start : e === r.groupmarker.start ? e = r.groupmarker.end : e === r.groupmarker.end && (e = r.groupmarker.start),
                                e
                        }(e.matches[t])
                    }
                return e
            }
            var f, p, h, m, d, v, g, k = /(?:[?*+]|\{[0-9\+\*]+(?:,[0-9\+\*]*)?\})|[^.?*+^${[]()|\\]+|./g, y = /\[\^?]?(?:[^\\\]]+|\\[\S\s]?)*]?|\\(?:0(?:[0-3][0-7]{0,2}|[4-7][0-7]?)?|[1-9][0-9]*|x[0-9A-Fa-f]{2}|u[0-9A-Fa-f]{4}|c[A-Za-z]|[\S\s]?)|\((?:\?[:=!]?)?|(?:[?*+]|\{[0-9]+(?:,[0-9]*)?\})\??|[^.?*+^${[()|\\]+|./g, b = !1, P = new o, E = [], C = [];
            for (n && (r.optionalmarker.start = i,
                r.optionalmarker.end = i); f = n ? y.exec(t) : k.exec(t); ) {
                if (p = f[0],
                    n)
                    switch (p.charAt(0)) {
                        case "?":
                            p = "{0,1}";
                            break;
                        case "+":
                        case "*":
                            p = "{" + p + "}"
                    }
                if (b)
                    c();
                else
                    switch (p.charAt(0)) {
                        case r.escapeChar:
                            b = !0,
                            n && c();
                            break;
                        case r.optionalmarker.end:
                        case r.groupmarker.end:
                            if (h = E.pop(),
                                h.openGroup = !1,
                            h !== i)
                                if (E.length > 0) {
                                    if ((m = E[E.length - 1]).matches.push(h),
                                        m.isAlternator) {
                                        d = E.pop();
                                        for (var A = 0; A < d.matches.length; A++)
                                            d.matches[A].isGroup = !1,
                                                d.matches[A].alternatorGroup = !1;
                                        E.length > 0 ? (m = E[E.length - 1]).matches.push(d) : P.matches.push(d)
                                    }
                                } else
                                    P.matches.push(h);
                            else
                                c();
                            break;
                        case r.optionalmarker.start:
                            E.push(new o(!1,!0));
                            break;
                        case r.groupmarker.start:
                            E.push(new o(!0));
                            break;
                        case r.quantifiermarker.start:
                            var _ = new o(!1,!1,!0)
                                , x = (p = p.replace(/[{}]/g, "")).split(",")
                                , w = isNaN(x[0]) ? x[0] : parseInt(x[0])
                                , M = 1 === x.length ? w : isNaN(x[1]) ? x[1] : parseInt(x[1]);
                            if ("*" !== M && "+" !== M || (w = "*" === M ? 0 : 1),
                                _.quantifier = {
                                    min: w,
                                    max: M
                                },
                            E.length > 0) {
                                var O = E[E.length - 1].matches;
                                (f = O.pop()).isGroup || ((g = new o(!0)).matches.push(f),
                                    f = g),
                                    O.push(f),
                                    O.push(_)
                            } else
                                (f = P.matches.pop()).isGroup || (n && null === f.fn && "." === f.def && (f.fn = new RegExp(f.def,r.casing ? "i" : "")),
                                    (g = new o(!0)).matches.push(f),
                                    f = g),
                                    P.matches.push(f),
                                    P.matches.push(_);
                            break;
                        case r.alternatormarker:
                            if (E.length > 0) {
                                var S = (m = E[E.length - 1]).matches[m.matches.length - 1];
                                v = m.openGroup && (S.matches === i || !1 === S.isGroup && !1 === S.isAlternator) ? E.pop() : m.matches.pop()
                            } else
                                v = P.matches.pop();
                            if (v.isAlternator)
                                E.push(v);
                            else if (v.alternatorGroup ? (d = E.pop(),
                                v.alternatorGroup = !1) : d = new o(!1,!1,!1,!0),
                                d.matches.push(v),
                                E.push(d),
                                v.openGroup) {
                                v.openGroup = !1;
                                var D = new o(!0);
                                D.alternatorGroup = !0,
                                    E.push(D)
                            }
                            break;
                        default:
                            c()
                    }
            }
            for (; E.length > 0; )
                h = E.pop(),
                    P.matches.push(h);
            return P.matches.length > 0 && (l(P),
                C.push(P)),
            (r.numericInput || r.isRTL) && u(C[0]),
                C
        }
    },
        a.extendDefaults = function(t) {
            e.extend(!0, a.prototype.defaults, t)
        }
        ,
        a.extendDefinitions = function(t) {
            e.extend(!0, a.prototype.definitions, t)
        }
        ,
        a.extendAliases = function(t) {
            e.extend(!0, a.prototype.aliases, t)
        }
        ,
        a.format = function(e, t, n) {
            return a(t).format(e, n)
        }
        ,
        a.unmask = function(e, t) {
            return a(t).unmaskedvalue(e)
        }
        ,
        a.isValid = function(e, t) {
            return a(t).isValid(e)
        }
        ,
        a.remove = function(t) {
            e.each(t, function(e, t) {
                t.inputmask && t.inputmask.remove()
            })
        }
        ,
        a.escapeRegex = function(e) {
            var t = ["/", ".", "*", "+", "?", "|", "(", ")", "[", "]", "{", "}", "\\", "$", "^"];
            return e.replace(new RegExp("(\\" + t.join("|\\") + ")","gim"), "\\$1")
        }
        ,
        a.keyCode = {
            ALT: 18,
            BACKSPACE: 8,
            BACKSPACE_SAFARI: 127,
            CAPS_LOCK: 20,
            COMMA: 188,
            COMMAND: 91,
            COMMAND_LEFT: 91,
            COMMAND_RIGHT: 93,
            CONTROL: 17,
            DELETE: 46,
            DOWN: 40,
            END: 35,
            ENTER: 13,
            ESCAPE: 27,
            HOME: 36,
            INSERT: 45,
            LEFT: 37,
            MENU: 93,
            NUMPAD_ADD: 107,
            NUMPAD_DECIMAL: 110,
            NUMPAD_DIVIDE: 111,
            NUMPAD_ENTER: 108,
            NUMPAD_MULTIPLY: 106,
            NUMPAD_SUBTRACT: 109,
            PAGE_DOWN: 34,
            PAGE_UP: 33,
            PERIOD: 190,
            RIGHT: 39,
            SHIFT: 16,
            SPACE: 32,
            TAB: 9,
            UP: 38,
            WINDOWS: 91,
            X: 88
        },
        a
});
