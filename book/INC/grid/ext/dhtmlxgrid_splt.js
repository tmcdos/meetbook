/*
===================================================================
Copyright Dinamenta, UAB http://www.dhtmlx.com
This code is obfuscated and not allowed for any purposes except 
using on sites which belongs to Dinamenta, UAB

Please contact sales@dhtmlx.com to obtain necessary 
license for usage of dhtmlx components.
===================================================================
*/
dhtmlXGridObject.prototype._init_point_bspl = dhtmlXGridObject.prototype.Yz;
dhtmlXGridObject.prototype.Yz = function ()
{
  this._split_later && this.tT(this._split_later);
  (this.Yz = this._init_point_bspl) && this.Yz()
};

dhtmlXGridObject.prototype.tT = function (e)
{
  function m(a)
  {
    var b = a.wheelDelta / -40;
    if (a.wheelDelta === window.undefined) b = a.detail;
    var d = v.HF;
    d.scrollTop += b * 40;
    a.preventDefault && a.preventDefault()
  }

  function r(a, b)
  {
    b.style.whiteSpace = "";
    var d = b.nextSibling,
      c = b.parentNode;
    a.parentNode.insertBefore(b, a);
    d ? c.insertBefore(a, d) : c.appendChild(a);
    var e = a.style.display;
    a.style.display = b.style.display;
    b.style.display = e
  }

  function o(a, b, d, c)
  {
    var i = Array(e).join(this.gX),
      f = [];
    if (a == 2) for (var g = 0; g < e; g++)
    {
      var h = b[a - 1].cells[b[a - 1].bq ? b[a - 1].bq[g] : g];
      if (h.rowSpan && h.rowSpan > 1) f[h._cellIndex] = h.rowSpan - 1, c[a - 1].cells[c[a - 1].bq ? c[a - 1].bq[g] : g].rowSpan = h.rowSpan, h.rowSpan = 1
    }
    for (; a < b.length; a++)
    {
      this.aC.attachHeader(i, null, d);
      for (var c = c || this.aC.ftr.childNodes[0].rows, j = e, l = 0, k = 0; k < j; k++) if (f[k])
      {
        f[k] -= 1;
        if (_isIE || aq && qG >= 1.9 || cU)
        {
          var m = document.createElement("TD");
          if (aq) m.style.display = "none";
          b[a].insertBefore(m, b[a].cells[0])
        }
        l++
      }
      else
      {
        var p = c[a].cells[k - l],
          n = b[a].cells[k - (_isIE ? 0 : l)],
          o = n.rowSpan;
        r(p, n);
        if (o > 1) f[k] = o - 1, n.rowSpan = o;
        if (c[a].cells[k].colSpan > 1)
        {
          b[a].cells[k].colSpan = c[a].cells[k].colSpan;
          j -= c[a].cells[k].colSpan - 1;
          for (g = 1; g < c[a].cells[k].colSpan; g++) c[a].removeChild(c[a].cells[k + 1])
        }
      }
    }
  }
  if (!this.obj.rows[0]) return this._split_later = e;
  var e = parseInt(e);
  var l = document.createElement("DIV");
  this.aL.appendChild(l);
  var j = document.createElement("DIV");
  this.aL.appendChild(j);
  for (var g = this.aL.childNodes.length - 3; g >= 0; g--) j.insertBefore(this.aL.childNodes[g], j.firstChild);
  this.aL.style.position = "relative";
  this.globalBox = this.aL;
  this.aL = j;
  j.grid = this;
  l.style.cssText += "border:0px solid red !important;";
  j.style.cssText += "border:0px solid red !important;";
  j.style.top = "0px";
  j.style.position = "absolute";
  l.style.position = "absolute";
  l.style.top = "0px";
  l.style.left = "0px";
  l.style.zIndex = 11;
  j.style.height = l.style.height = this.globalBox.clientHeight;
  this.aC = new dhtmlXGridObject(l);
  this.aC.setSkin("not_existing_skin");
  this.globalBox = this.aC.globalBox = this.globalBox;
  this.aC.aC = this;
  this.aC.nh = !0;
  this._treeC = this.cellType.bP("tree");
  this.aC.gX = this.gX;
  this.aC.customGroupFormat = this.customGroupFormat;
  this.aC.eg = this.eg;
  this.aC._customSorts = this._customSorts;
  this.aC.sB = this.sB;
  this.aC.FB = this.FB;
  this.aC.Zl = this.Zl;
  this.aC.clists = this.clists;
  this.aC.fldSort = [];
  this.aC.gN = this.gN;
  if ((this.aC.hu = this.hu) || this._erspan)
  {
    this.attachEvent("onCellChanged", this._correctRowHeight);
    this.attachEvent("onRowAdded", this._correctRowHeight);
    var s = function ()
    {
      this.forEachRow(function (a)
      {
        this._correctRowHeight(a)
      })
    };
    this.attachEvent("onPageChanged", s);
    this.attachEvent("onXLE", s);
    this.attachEvent("onResizeEnd", s);
    this.Hv || this.attachEvent("onAfterSorting", s);
    this.attachEvent("onDistributedEnd", s)
  }
  this.attachEvent("onGridReconstructed", function ()
  {
    this.aC.HF.scrollTop = this.HF.scrollTop
  });
  this.aC.loadedKidsHash = this.loadedKidsHash;
  if (this._h2) this.aC._h2 = this._h2;
  this.aC._dInc = this._dInc;
  var p = [
    [],
    [],
    [],
    [],
    [],
    [],
    []
  ],
    q = "hdrLabels,initCellWidth,cellType,cellAlign,cellVAlign,fldSort,columnColor".split(","),
    t = "setHeader,setInitWidths,setColTypes,setColAlign,setColVAlign,setColSorting,setColumnColor".split(",");
  this.aC.callEvent = function ()
  {
    this.aC._split_event = !0;
    arguments[0] == "onGridReconstructed" && this.aC.callEvent.apply(this, arguments);
    return this.aC.callEvent.apply(this.aC, arguments)
  };
  this.UJ && this.aC.enableLightMouseNavigation(!0);
  (this.__cssEven || this.iI) && this.aC.attachEvent("onGridReconstructed", function ()
  {
    this._fixAlterCss()
  });
  this.aC.hC = this.hC;
  this.aC.iI = this.iI;
  this.aC.Cw = this.Cw;
  this.aC.oB = this.oB;
  this.aC._edtc = this._edtc;
  this.agg && this.aC.enableStableSorting(!0);
  this.aC.aU = this.aU;
  this.aC.aP = this.aP;
  this.aC.UB = this.UB;
  this.aC._maskArr = this._maskArr;
  this.aC.RY = this.RY;
  this.aC.combos = this.combos;
  for (var n = 0, w = this.globalBox.offsetWidth, g = 0; g < e; g++)
  {
    for (var h = 0; h < q.length; h++)
    { 
      this[q[h]] && (p[h][g] = this[q[h]][g]);
      typeof p[h][g] == "string" && (p[h][g] = p[h][g].replace(RegExp("\\" + this.gX, "g"), "\\" + this.gX));
    }
    aq && (p[1][g] *= 1);
    this.fL == "%" ? (p[1][g] = Math.round(parseInt(this[q[1]][g]) * w / 100), n += p[1][g]) : n += parseInt(this[q[1]][g]);
    this.setColumnHidden(g, !0)
  }
  for (h = 0; h < q.length; h++)
  {
    var u = p[h].join(this.gX);
    if (t[h] != "setHeader")
    {
      if (u != "") this.aC[t[h]](u)
    }
    else this.aC[t[h]](u, null, this.Oi)
  }
  this.aC.dA = this.dA;
  this.aC.qZ = this.qZ;
  n = Math.min(this.globalBox.offsetWidth, n);
  j.style.left = n + "px";
  l.style.width = n + "px";
  j.style.width = Math.max(this.globalBox.offsetWidth - n, 0);
  if (this.CP) this.aC.CP = !0;
  this.aC.init();
  this.nz && this.ae.eH(this.aC.aL, this);
  this.aC.HF.style.overflow = "hidden";
  this.aC.HF.style.overflowX = "scroll";
  this.aC.ig = this.ig || 20;
  this.aC.ahK = this.ahK;
  var v = this;
  dhtmlxEvent(this.aC.HF, "mousewheel", m);
  dhtmlxEvent(this.aC.HF, "DOMMouseScroll", m);
  this.hdr.rows.length > 2 && o.call(this, 2, this.hdr.rows, "_aHead", this.aC.hdr.rows);
  if (this.ftr) o.call(this, 1, this.ftr.childNodes[0].rows, "_aFoot"), this.aC.ftr.parentNode.style.bottom = (aq ? 2 : 1) + "px";
  if (this.saveSizeToCookie) 
  {
    this.saveSizeToCookie = function (a, b)
    {
      if (this.nh) return this.aC.saveSizeToCookie.apply(this.aC, arguments);
      if (!a) a = this.aL.id;
      for (var d = [], c = "cellWidthPX", i = 0; i < this[c].length; i++) d[i] = i < e ? this.aC[c][i] : this[c][i];
      d = d.join(",");
      this.setCookie(a, b, 0, d);
      d = (this.initCellWidth || []).join(",");
      this.setCookie(a, b, 1, d);
      return !0
    };
    this.loadSizeFromCookie = function (a)
    {
      if (!a) a = this.aL.id;
      var b = this._getCookie(a, 1);
      if (b)
      {
        this.initCellWidth = b.split(",");
        var b = this._getCookie(a, 0),
          d = "cellWidthPX";
        this.fL = "px";
        var c = 0;
        if (b && b.length) for (var b = b.split(","), i = 0; i < b.length; i++) i < e ? (this.aC[d][i] = b[i], c += b[i] * 1) : this[d][i] = b[i];
        this.aC.aL.style.width = c + "px";
        this.aC.HF.style.width = c + "px";
        var f = this.globalBox.childNodes[1];
        f.style.left = c - (aq ? 0 : 0) + "px";
        if (this.ftr) this.ftr.style.left = c - (aq ? 0 : 0) + "px";
        f.style.width = this.globalBox.offsetWidth - c + "px";
        this.setSizes();
        return !0
      }
    };
    this.aC.onRSE = this.onRSE;
  }
  this.setCellTextStyleA = this.setCellTextStyle;
  this.setCellTextStyle = function (a, b, d)
  {
    b < e && this.aC.setCellTextStyle(a, b, d);
    this.setCellTextStyleA(a, b, d)
  };
  this.setRowTextBoldA = this.tG;
  this.tG = function (a)
  {
    this.setRowTextBoldA(a);
    this.aC.tG(a)
  };
  this.setRowColorA = this.EA;
  this.EA = function (a, b)
  {
    this.setRowColorA(a, b);
    this.aC.EA(a, b)
  };
  this.setRowHiddenA = this.sO;
  this.sO = function (a, b)
  {
    this.setRowHiddenA(a, b);
    this.aC.sO(a, b)
  };
  this.setRowTextNormalA = this.rn;
  this.rn = function (a)
  {
    this.setRowTextNormalA(a);
    this.aC.rn(a)
  };
  this.Dk = function (a)
  {
    function b(a)
    {
      for (var b = 0; b < a.childNodes.length; b++) 
        if (a.childNodes[b].hy) return d[d.length] = a.idd
    }
    var d = [];
    this.forEachRow(function (c)
    {
      var e = this.bj[c];
      var f = this.aC.bj[c];
      if (!(e.tagName != "TR" || f.tagName != "TR")) a && e.adM ? d[d.length] = e.idd : b(e) || b(f)
    });
    return d.join(this.gX)
  };
  this.setRowTextStyleA = this.setRowTextStyle;
  this.setRowTextStyle = function (a, b)
  {
    this.setRowTextStyleA(a, b);
    this.aC.bj[a] && this.aC.setRowTextStyle(a, b)
  };
  this.lockRowA = this.at;
  this.at = function (a, b)
  {
    this.lockRowA(a, b);
    this.aC.at(a, b)
  };
  this.yI = function (a)
  {
    return a < e ? parseInt(this.aC.cellWidthPX[a]) : parseInt(this.cellWidthPX[a])
  };
  this.adE = function (a)
  {
    return this.aC.adE.apply(a < e ? this.aC : this, arguments)
  };
  this.setColWidthA = this.aC.setColWidthA = this.setColWidth;
  this.setColWidth = function (a, b)
  {
    a *= 1;
    a < e ? this.aC.setColWidthA(a, b) : this.setColWidthA(a, b);
    a + 1 <= e && this.aC.rk(Math.min(this.aC.HF.offsetWidth, this.aC.obj.offsetWidth))
  };
  this.adjustColumnSizeA = this.nT;
  this.setColumnLabelA = this.adA;
  this.adA = function (a, b, d, c)
  {
    var i = this;
    if (a < e) i = this.aC;
    return this.setColumnLabelA.apply(i, [a, b, d, c])
  };
  this.nT = function (a, b)
  {
    if (a < e)
    {
      if (_isIE) this.aC.obj.style.tableLayout = "";
      this.aC.nT(a, b);
      if (_isIE) this.aC.obj.style.tableLayout = "fixed";
      this.aC.rk()
    }
    else return this.adjustColumnSizeA(a, b)
  };
  var f = "cells";
  this._bfs_cells = this[f];
  this[f] = function ()
  {
    return arguments[1] < e ? this.aC.cells.apply(this.aC, arguments) : this._bfs_cells.apply(this, arguments)
  };
  this._bfs_isColumnHidden = this.Fs;
  this.Fs = function ()
  {
    return parseInt(arguments[0]) < e ? this.aC.Fs.apply(this.aC, arguments) : this._bfs_isColumnHidden.apply(this, arguments)
  };
  this._bfs_setColumnHidden = this.setColumnHidden;
  this.setColumnHidden = function ()
  {
    return parseInt(arguments[0]) < e ? (this.aC.setColumnHidden.apply(this.aC, arguments), this.aC.rk()) : this._bfs_setColumnHidden.apply(this, arguments)
  };
  f = "cells2";
  this._bfs_cells2 = this[f];
  this[f] = function ()
  {
    return arguments[1] < e ? this.aC.cells2.apply(this.aC, arguments) : this._bfs_cells2.apply(this, arguments)
  };
  f = "cells3";
  this._bfs_cells3 = this[f];
  this[f] = function (a, b)
  {
    if (arguments[1] < e && this.aC.bj[arguments[0].idd])
    {
      if (this.aC.bj[a.idd] && this.aC.bj[a.idd].childNodes.length == 0) return this._bfs_cells3.apply(this, arguments);
      arguments[0] = arguments[0].idd;
      return this.aC.cells.apply(this.aC, arguments)
    }
    else return this._bfs_cells3.apply(this, arguments)
  };
  f = "changeRowId";
  this._bfs_changeRowId = this[f];
  this[f] = function ()
  {
    this._bfs_changeRowId.apply(this, arguments);
    this.aC.bj[arguments[0]] && this.aC.changeRowId.apply(this.aC, arguments)
  };
  this.aC.dz = function (a)
  {
    var b = this.bj[a];
    !b && this.aC.bj[a] && (b = this.aC.dz(a));
    if (b)
    {
      if (b.tagName != "TR")
      {
        for (var d = 0; d < this.aD.length; d++) if (this.aD[d] && this.aD[d].idd == a) return this.render_row(d);
        if (this._h2) return this.render_row(null, b.idd)
      }
      return b
    }
    return null
  };
  if (this.collapseKids) 
  {
    this.aC._bfs_collapseKids = this.collapseKids;
    this.aC.collapseKids = function (a)
    {
      return this.aC.collapseKids.apply(this.aC, [this.aC.bj[a.idd]])
    };
    this._bfs_collapseKids = this.collapseKids;
    this.collapseKids = function ()
    {
      var a = this._bfs_collapseKids.apply(this, arguments);
      this.aC._h2syncModel();
      this.Cw || this.aC._fixAlterCss()
    };
    this.aC._bfs_expandKids = this.expandKids; 
    this.aC.expandKids = function (a)
    {
      this.aC.expandKids.apply(this.aC, [this.aC.bj[a.idd]]);
      this.Cw || this.aC._fixAlterCss()
    };
    this._bfs_expandAll = this.expandAll;
    this.expandAll = function ()
    {
      this._bfs_expandAll();
      this.aC._h2syncModel();
      this.Cw || this.aC._fixAlterCss()
    };
    this._bfs_collapseAll = this.collapseAll;
    this.collapseAll = function ()
    {
      this._bfs_collapseAll();
      this.aC._h2syncModel();
      this.Cw || this.aC._fixAlterCss()
    };
    this._bfs_expandKids = this.expandKids;
    this.expandKids = function ()
    {
      var a = this._bfs_expandKids.apply(this, arguments);
      this.aC._h2syncModel();
      this.Cw || this.aC._fixAlterCss()
    };
    this.aC._h2syncModel = function ()
    {
      this.aC.aW ? this.aC.uR() : this.uR()
    };
    this.cO = function (a)
    {
      return this.aC.cO(a)
    };
  }
  if (this.Qh) 
  {
    this._setRowHoverA = this.aC._setRowHoverA = this.ff;
    this._unsetRowHoverA = this.aC._unsetRowHoverA = this.Rz;
    this.ff = this.aC.ff = function ()
    {
      var a = this.grid;
      a._setRowHoverA.apply(this, arguments);
      var b = _isIE ? event.srcElement : arguments[0].target;
      (b = a.aC.bj[a.bw(b, "TD").parentNode.idd]) && a.aC._setRowHoverA.apply(a.aC.obj, [
      {
        target: b.childNodes[0]
      },
      arguments[1]])
    };
    this.Rz = this.aC.Rz = function ()
    {
      var a = arguments[1] ? this : this.grid;
      a._unsetRowHoverA.apply(this, arguments);
      a.aC._unsetRowHoverA.apply(a.aC.obj, arguments)
    };
    this.aC.enableRowsHover(!0, this.Lk);
    this.enableRowsHover(!1);
    this.enableRowsHover(!0, this.aC.Lk);
  }
  this.cO = function (a)
  {
    if (a.update && a.id != 0)
    {
      if (this.bj[a.id].imgTag) this.bj[a.id].imgTag.src = this.eg + a.state + ".gif";
      if (this.aC.bj[a.id] && this.aC.bj[a.id].imgTag) this.aC.bj[a.id].imgTag.src = this.eg + a.state + ".gif";
      a.update = !1
    }
  };
  this.copy_row = function (a)
  {
    var b = a.cloneNode(!0);
    b.WX = a.WX;
    var d = e;
    b._attrs = {};
    b.Vn = a.Vn;
    if (this.CP) for (var c = d = 0; d < b.childNodes.length && c < e; c += b.childNodes[d].colSpan || 1) d++;
    for (; b.childNodes.length > d;) b.removeChild(b.childNodes[b.childNodes.length - 1]);
    for (var i = d, c = 0; c < i; c++) if (this.nz && this.ae.dS(b.childNodes[c], this), b.childNodes[c].style.display = this.aC._hrrar ? this.aC._hrrar[c] ? "none" : "" : "", b.childNodes[c]._cellIndex = c, b.childNodes[c].combo_value = a.childNodes[c].combo_value, b.childNodes[c].mG = a.childNodes[c].mG, b.childNodes[c].zk = a.childNodes[c].zk, b.childNodes[c]._brval = a.childNodes[c]._brval, b.childNodes[c]._attrs = a.childNodes[c]._attrs, b.childNodes[c].chstate = a.childNodes[c].chstate, a._attrs.style && (b.childNodes[c].style.cssText += ";" + a._attrs.style), b.childNodes[c].colSpan > 1) this.bq = this.aC.bq;
    if (this._h2 && this._treeC < e)
    {
      var f = this._h2.get[a.idd];
      b.imgTag = b.childNodes[this._treeC].childNodes[0].childNodes[f.gR];
      b.JT = b.childNodes[this._treeC].childNodes[0].childNodes[f.gR + 2]
    }
    b.idd = a.idd;
    b.grid = this.aC;
    return b
  };
  f = "_insertRowAt";
  this._bfs_insertRowAt = this[f];
  this[f] = function ()
  {
    var a = this._bfs_insertRowAt.apply(this, arguments);
    arguments[0] = this.copy_row(arguments[0]);
    var b = this.aC._insertRowAt.apply(this.aC, arguments);
    if (a._fhd) b.parentNode.removeChild(b), this.aC.am.an(this.aC.am.bP(b)), a._fhd = !1;
    return a
  };
  this._bfs_setSizes = this.setSizes;
  this.setSizes = function ()
  {
    if (!this.Hc) this._bfs_setSizes(this, arguments), this.sync_headers(), this.sync_scroll() && this.abK && this.setSizes(), this.aC.aL.style.height = this.aL.style.height, this.aC.HF.style.height = this.HF.style.height, this.aC.kR.style.height = this.kR.style.height, this.aC.HF.scrollTop = this.HF.scrollTop, this.aC.setColumnSizes(this.aC.aL.clientWidth), this.globalBox.style.width = parseInt(this.aL.style.width) + parseInt(this.aC.aL.style.width), this.globalBox.style.height = this.aL.style.height
  };
  this.sync_scroll = this.aC.sync_scroll = function (a)
  {
    var b = this.HF.style.overflowX;
    if (this.obj.offsetWidth <= this.HF.offsetWidth)
    {
      if (!a) return this.aC.sync_scroll(!0);
      this.HF.style.overflowX = "hidden";
      this.aC.HF.style.overflowX = "hidden"
    }
    else this.HF.style.overflowX = "scroll", this.aC.HF.style.overflowX = "scroll";
    return b != this.HF.style.overflowX
  };
  this.sync_headers = this.aC.sync_headers = function ()
  {
    if (!(this.sB || this.aC.hdr.scrollHeight == this.hdr.offsetHeight)) for (var a = 1; a < this.hdr.rows.length; a++)
    {
      var b = this.hdr.rows[a].scrollHeight,
        d = this.aC.hdr.rows[a].scrollHeight;
      if (b != d) this.aC.hdr.rows[a].style.height = this.hdr.rows[a].style.height = Math.max(b, d) + "px";
      if (window._KHTMLrv) this.aC.hdr.rows[a].childNodes[0].style.height = this.hdr.rows[a].childNodes[e].style.height = Math.max(b, d) + "px"
    }
  };
  this.aC._bfs_setSizes = this.aC.setSizes;
  this.aC.setSizes = function ()
  {
    this.aC.Hc || this.aC.setSizes()
  };
  f = "_doOnScroll";
  this._bfs__doOnScroll = this[f];
  this[f] = function ()
  {
    this._bfs__doOnScroll.apply(this, arguments);
    this.aC.HF.scrollTop = this.HF.scrollTop;
    this.aC._doOnScroll.apply(this.aC, arguments)
  };
  f = "selectAll";
  this._bfs__selectAll = this[f];
  this[f] = function ()
  {
    this._bfs__selectAll.apply(this, arguments);
    this._bfs__selectAll.apply(this.aC, arguments)
  };
  f = "doClick";
  this._bfs_doClick = this[f];
  this[f] = function ()
  {
    this._bfs_doClick.apply(this, arguments);
    if (arguments[0].tagName == "TD")
    {
      var a = arguments[0]._cellIndex >= e;
      if (arguments[0].parentNode.idd)
      {
        if (!a) arguments[0].className = arguments[0].className.replace(/cellselected/g, "");
        this.aC.bj[arguments[0].parentNode.idd] || this.aC.render_row(this.getRowIndex(arguments[0].parentNode.idd));
        arguments[0] = this.aC.cells(arguments[0].parentNode.idd, a ? 0 : arguments[0]._cellIndex).cell;
        if (a) this.aC.cell = null;
        this.aC._bfs_doClick.apply(this.aC, arguments);
        a ? this.aC.cell = this.cell : this.cell = this.aC.cell;
        this.aC.Ti && clearTimeout(this.aC.Ti);
        a ? (arguments[0].className = arguments[0].className.replace(/cellselected/g, ""), globalActiveDHTMLGridObject = this, this.aC.cell = this.cell) : this.HF.scrollTop = this.aC.HF.scrollTop
      }
    }
  };
  this.aC._bfs_doClick = this.aC[f];
  this.aC[f] = function ()
  {
    this._bfs_doClick.apply(this, arguments);
    if (arguments[0].tagName == "TD")
    {
      var a = arguments[0]._cellIndex < e;
      if (arguments[0].parentNode.idd && (arguments[0] = this.aC._bfs_cells(arguments[0].parentNode.idd, a ? e : arguments[0]._cellIndex).cell, this.aC.cell = null, this.aC._bfs_doClick.apply(this.aC, arguments), this.aC.cell = this.cell, this.aC.Ti && clearTimeout(this.aC.Ti), a)) arguments[0].className = arguments[0].className.replace(/cellselected/g, ""), globalActiveDHTMLGridObject = this, this.aC.cell = this.cell, this.aC.HF.scrollTop = this.HF.scrollTop
    }
  };
  this.clearSelectionA = this.pL;
  this.pL = function (a)
  {
    a && this.aC.pL();
    this.clearSelectionA()
  };
  this.moveRowUpA = this.zs;
  this.zs = function (a)
  {
    this._h2 || this.aC.zs(a);
    this.moveRowUpA(a);
    this._h2 && this.aC._h2syncModel()
  };
  this.moveRowDownA = this.uQ;
  this.uQ = function (a)
  {
    this._h2 || this.aC.uQ(a);
    this.moveRowDownA(a);
    this._h2 && this.aC._h2syncModel()
  };
  this.aC.getUserData = function ()
  {
    return this.aC.getUserData.apply(this.aC, arguments)
  };
  this.aC.setUserData = function ()
  {
    return this.aC.setUserData.apply(this.aC, arguments)
  };
  this.getSortingStateA = this.xu;
  this.xu = function ()
  {
    var a = this.getSortingStateA();
    return a.length != 0 ? a : this.aC.xu()
  };
  this.setSortImgStateA = this.aC.setSortImgStateA = this.co;
  this.co = function (a, b, d, c)
  {
    this.setSortImgStateA(a, b, d, c);
    b * 1 < e ? (this.aC.setSortImgStateA(a, b, d, c), this.setSortImgStateA(!1)) : this.aC.setSortImgStateA(!1)
  };
  this.aC.doColResizeA = this.aC.EO;
  this.aC.EO = function (a, b, d, c, f)
  {
    var g = -1;
    var h = 0;
    if (arguments[1]._cellIndex == e - 1)
    {
      g = this._initalSplR + (a.clientX - c);
      if (!this._initalSplF) this._initalSplF = arguments[3] + this.HF.scrollWidth - this.HF.offsetWidth;
      if (this.HF.scrollWidth == this.HF.offsetWidth && (this.aC.alter_split_resize || a.clientX - c > 0)) arguments[3] = this._initalSplF || arguments[3]
    }
    else if (this.obj.offsetWidth < this.aL.offsetWidth) g = this.obj.offsetWidth;
    h = this.doColResizeA.apply(this, arguments);
    this.rk(g);
    this.resized = this.aC.resized = 1;
    return h
  };
  this.aC.bX = function (a)
  {
    var b = a.target || a.srcElement;
    b.tagName != "TD" && (b = this.bw(b, "TD"));
    if (!(b.tagName == "TD" && this.qZ && !this.qZ[b._cellIndex]))
    {
      var d = (a.layerX || 0) + (!_isIE && a.target.tagName == "DIV" ? b.offsetLeft : 0),
        c = parseInt(this.fI(b, this.kR));
      b.style.cursor = b.offsetWidth - (a.offsetX || (c - d) * -1) < (cU ? 20 : 10) || this.aL.offsetWidth - (a.offsetX ? a.offsetX + b.offsetLeft : d) + this.HF.scrollLeft - 0 < (cU ? 20 : 10) ? "E-resize" : "default";
      if (cU) this.kR.scrollLeft = this.HF.scrollLeft
    }
  };
  this.aC.startColResizeA = this.aC.eJ;
  this.aC.eJ = function (a)
  {
    var b = this.startColResizeA(a);
    this._initalSplR = this.aL.offsetWidth;
    this._initalSplF = null;
    if (this.aL.onmousemove)
    {
      var d = this.aL.parentNode;
      if (d._aggrid) return b;
      d._aggrid = d.grid;
      d.grid = this;
      this.aL.parentNode.onmousemove = this.aL.onmousemove;
      this.aL.onmousemove = null
    }
    return b
  };
  this.aC.stopColResizeA = this.aC.fN;
  this.aC.fN = function (a)
  {
    if (this.aL.parentNode.onmousemove)
    {
      var b = this.aL.parentNode;
      b.grid = b._aggrid;
      b._aggrid = null;
      this.aL.onmousemove = this.aL.parentNode.onmousemove;
      this.aL.parentNode.onmousemove = null;
      this.obj.offsetWidth < this.aL.offsetWidth && this.rk(this.obj.offsetWidth)
    }
    return this.stopColResizeA(a)
  };
  this.doKeyA = this.bQ;
  this.aC.doKeyA = this.aC.bQ;
  this.aC.bQ = this.bQ = function (a)
  {
    if (!a) return !0;
    if (this.Zl) return !0;
    if ((a.target || a.srcElement).value !== window.undefined)
    {
      var b = a.target || a.srcElement;
      if (!b.parentNode || b.parentNode.className.indexOf("editable") == -1) return !0
    }
    switch (a.keyCode)
    {
    case 9:
      if (a.shiftKey) if (this.nh)
      {
        if (this.cell && this.cell._cellIndex == 0)
        {
          a.preventDefault && a.preventDefault();
          if (c = this.aC.aD[this.aC.getRowIndex(this.cell.parentNode.idd) - 1])
          {
            this.aC.aqG(c.idd);
            for (d = this.aC._cCount - 1; c.childNodes[d].style.display == "none";) d--;
            this.aC.eQ(this.aC.getRowIndex(c.idd), d, !1, !1, !0)
          }
          return !1
        }
      }
      else
      {
        if (this.cell && this.cell._cellIndex == e) return a.preventDefault && a.preventDefault(), this.aC.eQ(this.getRowIndex(this.cell.parentNode.idd), e - 1, !1, !1, !0), !1
      }
      else if (this.nh)
      {
        if (this.cell && this.cell._cellIndex == e - 1)
        {
          a.preventDefault && a.preventDefault();
          for (var d = e; this.aC._hrrar && this.aC._hrrar[d];) d++;
          this.aC.eQ(this.aC.getRowIndex(this.cell.parentNode.idd), d, !1, !1, !0);
          return !1
        }
        else var c = this.doKeyA(a);
        globalActiveDHTMLGridObject = this;
        return c
      }
      else if (this.cell)
      {
        for (d = this.cell._cellIndex + 1; this.am[0].childNodes[d] && this.am[0].childNodes[d].style.display == "none";) d++;
        if (d == this.am[0].childNodes.length && (a.preventDefault && a.preventDefault(), c = this.aD[this.getRowIndex(this.cell.parentNode.idd) + 1])) return this.aqG(c.idd), this.aC.eQ(this.aC.getRowIndex(c.idd), 0, !1, !1, !0), !1
      }
    }
    return this.doKeyA(a)
  };
  this.editCellA = this.gh;
  this.gh = function ()
  {
    return this.cell && this.cell.parentNode.grid != this ? this.aC.gh() : this.editCellA()
  };
  this.deleteRowA = this.deleteRow;
  this.deleteRow = function (a, b)
  {
    if (this.deleteRowA(a, b) === !1) return !1;
    this.aC.bj[a] && this.aC.deleteRow(a)
  };
  this.clearAllA = this.clearAll;
  this.clearAll = function ()
  {
    this.clearAllA();
    this.aC.clearAll()
  };
  this.editStopA = this.editStop;
  this.editStop = function ()
  {
    this.aC.editor ? this.aC.editStop() : this.editStopA()
  };
  this.attachEvent("onAfterSorting", function (a)
  {
    a >= e && this.aC.co(!1)
  });
  this.aC.adU = function (a, b)
  {
    this.aC.adU.call(this.aC, a, b, this.aC.hdr.rows[0].cells[a]);
    if (this.fldSort[a] != "na" && this.aC.ij)
    {
      var d = this.aC.xu()[1];
      this.aC.co(!1);
      this.co(!0, a, d)
    }
  };
  this.sortTreeRowsA = this.sT;
  this.aC.sortTreeRowsA = this.aC.sT;
  this.sT = this.aC.sT = function (a, b, d, c)
  {
    if (this.nh) return this.aC.sT(a, b, d, c);
    this.sortTreeRowsA(a, b, d, c);
    this.aC._h2syncModel();
    this.aC.setSortImgStateA(!1);
    this.aC.ij = null
  };
  this.aC._fillers = [];
  this.aC.aD = this.aD;
  this.attachEvent("onClearAll", function ()
  {
    this.aC.aD = this.aD
  });
  this._add_filler_s = this._add_filler;
  this._add_filler = function (a, b, d, c)
  {
    if (!this.aC._fillers) this.aC._fillers = [];
    if (this.nh || !c)
    {
      var e;
      if (d || !this.aC._fillers.length)
      {
        if (d && d.idd) e = this.aC.bj[d.idd];
        else if (d && d.nextSibling) e = {}, e.nextSibling = this.aC.bj[d.nextSibling.idd], e.parentNode = e.nextSibling.parentNode;
        this.aC._fillers.push(this.aC._add_filler(a, b, e))
      }
    }
    return this._add_filler_s.apply(this, arguments)
  };
  this._add_from_buffer_s = this._add_from_buffer;
  this._add_from_buffer = function ()
  {
    var a = this._add_from_buffer_s.apply(this, arguments);
    a != -1 && (this.aC._add_from_buffer.apply(this.aC, arguments), this.hu && this._correctRowHeight(this.aD[arguments[0]].idd));
    return a
  };
  this.aC.render_row = function (a)
  {
    var b = this.aC.render_row(a);
    return b == -1 ? -1 : b ? this.bj[b.idd] = this.bj[b.idd] || this.aC.copy_row(b) : null
  };
  this._reset_view_s = this._reset_view;
  this._reset_view = function ()
  {
    this.aC._reset_view(!0);
    this.aC._fillers = [];
    this._reset_view_s()
  };
  this.moveColumn_s = this.moveColumn;
  this.moveColumn = function (a, b)
  {
    if (b >= e) return this.moveColumn_s(a, b)
  };
  this.attachEvent("onCellChanged", function (a, b, d)
  {
    if (this._split_event && b < e && this.bj[a])
    {
      var c = this.aC.bj[a];
      if (c)
      {
        var c = c.bq ? c.childNodes[c.bq[b]] : c.childNodes[b],
          f = this.bj[a].childNodes[b];
        f._treeCell && f.firstChild.lastChild ? f.firstChild.lastChild.innerHTML = d : f.innerHTML = c.innerHTML;
        f.mG = !1;
        f.combo_value = c.combo_value;
        f.chstate = c.chstate
      }
    }
  });
  this.aC.combos = this.combos;
  this.setSizes();
  this.aD[0] && this._reset_view();
  this.attachEvent("onXLE", function ()
  {
    this.aC.rk()
  });
  this.aC.rk()
};

dhtmlXGridObject.prototype.rk = function (e)
{
  e = e || this.obj.scrollWidth - this.HF.scrollLeft;
  e = Math.min(this.globalBox.offsetWidth, e);
  if (e > -1)
  {
    this.aL.style.width = e + "px";
    this.HF.style.width = e + "px";
    var m = (this.globalBox.offsetWidth - this.globalBox.clientWidth) / 2;
    this.aC.aL.style.left = e + "px";
    this.aC.aL.style.width = Math.max(0, this.globalBox.offsetWidth - e - (this.quirks ? 0 : 2) * m) + "px";
    if (this.aC.ftr) this.aC.ftr.parentNode.style.width = this.aC.aL.style.width;
    if (_isIE)
    {
      var r = _isIE && !window.xmlHttpRequest,
        m = this.globalBox.offsetWidth - this.globalBox.clientWidth;
      this.aC.kR.style.width = this.aC.HF.style.width = Math.max(0, this.globalBox.offsetWidth - (r ? m : 0) - e) + "px"
    }
  }
};

dhtmlXGridObject.prototype._correctRowHeight = function (e)
{
  if (this.bj[e] && this.aC.bj[e])
  {
    var m = this.bj[e].offsetHeight,
      r = this.aC.bj[e].offsetHeight,
      o = Math.max(m, r);
    if (o && (this.bj[e].style.height = this.aC.bj[e].style.height = o + "px", window._KHTMLrv)) this.bj[e].childNodes[this.aC._cCount].style.height = this.aC.bj[e].firstChild.style.height = o + "px"
  }
};