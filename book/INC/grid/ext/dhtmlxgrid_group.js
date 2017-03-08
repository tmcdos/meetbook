/*
===================================================================
Copyright Dinamenta, UAB http://www.dhtmlx.com
This code is obfuscated and not allowed for any purposes except 
using on sites which belongs to Dinamenta, UAB

Please contact sales@dhtmlx.com to obtain necessary 
license for usage of dhtmlx components.
===================================================================
*/
dhtmlXGridObject.prototype.unGroup = function ()
{
  if (this.pj) this._dndProblematic = !1, delete this.pj, delete this._gIndex, this.aC && this._mirror_rowsCol(), this.forEachRow(function (a)
  {
    this.bj[a].style.display = ""
  }), this._reset_view(), this.callEvent("onGridReconstructed", []), this.callEvent("onUnGroup", [])
};

dhtmlXGridObject.prototype._mirror_rowsCol = function ()
{
  this.aC.pj = this.pj;
  this.aC._gIndex = this._gIndex;
  this.aD = dhtmlxArray();
  for (var a = 0; a < this.am.length; a++) this.am[a].te || this.aD.push(this.am[a]);
  this.aC.aD = dhtmlxArray();
  for (a = 0; a < this.aC.am.length; a++) this.aC.am[a].te || this.aC.aD.push(this.aC.am[a])
};

dhtmlXGridObject.prototype.groupBy = function (a, d)
{
  this.pj && this.unGroup();
  this._dndProblematic = !0;
  this.pj = {};
  if (!d) for (var d = ["#title"], b = 1; b < this._cCount; b++) d.push("#cspan");
  this._gmask = document.createElement("TR");
  this._gmask.origin = d;
  for (var c, e = 0, b = 0; b < d.length; b++) 
    if (d[b] == "#cspan") c.colSpan = (parseInt(c.colSpan) || 1) + 1;
    else
    {
      c = document.createElement("TD");
      c._cellIndex = b;
      if (this._hrrar[b]) c.style.display = "none";
      c.className = "group_row";
      c.innerHTML = "&nbsp;";
      d[b] == "#title" ? this._gmask.ahi = e : c.align = this.cellAlign[b] || "left";
      this._gmask.appendChild(c);
      if (d[b].indexOf("#stat") == 0) this._gmask._math = !0, c._counter = [this["_g_" + d[b].replace("#", "")], b, e];
      e++
    }
  for (var f in this.pj) this.pj[f] = this.undefined;
  this._gIndex = a;
  if (this.aC && !this.nh) this.aC.pj = [], this.aC._gIndex = this._gIndex;
  this.CE = function (a, b)
  {
    var c = this.am[a + b];
    return c && (c.style.display == "none" || c.te) ? this.CE(a + b, b) : c
  };
  if (!this.__sortRowsBG) 
  {
    this.zf = dhtmlXHeir( {}, this.zf); 
    this.zf.k38_0_0 = function ()
    {
      if (this.editor && this.editor.combo) this.editor.Kc();
      else
      {
        var a = this.row.rowIndex;
        if (a)
        {
          var b = this.CE(a - 1, -1);
          b && this.eQ(b, this.cell._cellIndex, !0)
        }
      }
    };
    this.zf.k13_1_0 = this.zf.k13_0_1 = function () {};
    this.zf.k40_0_0 = function ()
    {
      if (this.editor && this.editor.combo) this.editor.eW();
      else
      {
        var a = this.row.rowIndex;
        if (a)
        {
          var b = this.CE(a - 1, 1);
          b && this.eQ(b, this.cell._cellIndex, !0)
        }
      }
    };
    this.attachEvent("onFilterStart", function ()
    {
      if (this.pj) this.pj = this.undefined;
      return !0
    });
    this.attachEvent("onFilterEnd", function ()
    {
      typeof this._gIndex != "undefined" && this.groupBy(this._gIndex, this._gmask.origin)
    });
    this.sortRows_bg = this.xK, this.xK = function (a, b, c)
    {
      if (typeof this.pj == "undefined") return this.sortRows_bg.apply(this, arguments);
      this.callEvent("onBeforeSorting", [a, b || "str", c || "asc"])
    };
    this.attachEvent("onBeforeSorting", function (a, b, c)
    {
      if (typeof this.pj == "undefined") return !0;
      a == this._gIndex ? this._sortByGroup(a, b, c) : this._sortInGroup(a, b, c);
      this.co(!0, a, c);
      if (this.aC) this._mirror_rowsCol(), this.aC.pj = [], this.aC._reset_view();
      this.co(!0, a, c);
      this.callEvent("onAfterSorting", [a, b, c]);
      return !1
    });
    this.attachEvent("onClearAll", function ()
    {
      this.unGroup()
    });
    this.attachEvent("onBeforeRowDeleted", function (a)
    {
      if (!this.pj) return !0;
      if (!this.bj[a]) return !0;
      var b = this.cells(a, this._gIndex).getValue();
      b === "" && (b = " ");
      var c = this.pj[b];
      this._dec_group(c);
      return !0
    });
    this.attachEvent("onAfterRowDeleted", function ()
    {
      this.updateGroups()
    });
    this.attachEvent("onCheckbox", function (a, b, c)
    {
      this.callEvent("onEditCell", [2, a, b, c ? 1 : 0, c ? 0 : 1])
    });
    this.attachEvent("onXLE", this.updateGroups), this.attachEvent("onColumnHidden", this.hideGroupColumn), this.attachEvent("onEditCell", function (a, b, c, d, e)
    {
      if (!this.pj) return !0;
      if (a == 2 && d != e && c == this._gIndex)
      {
        e === "" && (e = " ");
        this._dec_group(this.pj[e]);
        var f = this.bj[b],
          j = this.am.bP(f),
          k = this._inc_group(d),
          g = this.am[k];
        if (f == g) g = g.nextSibling;
        var l = f.parentNode,
          n = f.rowIndex;
        l.removeChild(f);
        g ? l.insertBefore(f, g) : l.appendChild(f);
        this.am.ml(k, f);
        k < j && j++;
        this.am.an(j, f);
        this._fixAlterCss()
      }
      else a == 2 && d != e && (this.updateGroups(), this._updateGroupView(this.pj[this.cells(b, this._gIndex).getValue() || " "]));
      return !0
    });
    this.__sortRowsBG = !0;
  }
  this._groupExisting();
  if (this._hrrar) for (b = 0; b < this._hrrar.length; b++) this._hrrar[b] && this.hideGroupColumn(b, !0);
  this.callEvent("onGroup", []);
  (this.abK || this.Gd) && this.setSizes()
};

dhtmlXGridObject.prototype._sortInGroup = function (a, d, b)
{
  var c = this._groups_get();
  c.reverse();
  for (var e = 0; e < c.length; e++)
  {
    for (var f = c[e].te._childs, m = {}, h = 0; h < f.length; h++)
    {
      var i = this.cells3(f[h], a);
      m[f[h].idd] = i.getDate ? i.getDate() : i.getValue()
    }
    this.aeo(a, d, b, m, f)
  }
  this._groups_put(c);
  this.setSizes();
  this.callEvent("onGridReconstructed", [])
};

dhtmlXGridObject.prototype._sortByGroup = function (a, d, b)
{
  for (var c = this._groups_get(), e = [], f = 0; f < c.length; f++) c[f].idd = "_sort_" + f, e["_sort_" + f] = c[f].te.text;
  this.aeo(a, d, b, e, c);
  this._groups_put(c);
  this.callEvent("onGridReconstructed", []);
  this.setSizes()
};

dhtmlXGridObject.prototype._inc_group = function (a, d, b)
{
  a === "" && (a = " ");
  this.pj[a] || (this.pj[a] = {
    text: a,
    row: this._addPseudoRow(),
    count: 0,
    state: d ? "plus" : "minus"
  });
  var c = this.pj[a];
  c.row.te = c;
  var e = this.am.bP(c.row) + c.count + 1;
  c.count++;
  b || (this._updateGroupView(c), this.updateGroups());
  return e
};

dhtmlXGridObject.prototype._dec_group = function (a)
{
  if (a) return a.count--, a.count == 0 ? (a.row.parentNode.removeChild(a.row), this.am.an(this.am.bP(a.row)), delete this.pj[a.text]) : this._updateGroupView(a), this.aC && !this.nh && this.aC._dec_group(this.aC.pj[a.text]), this.updateGroups(), !0
};

dhtmlXGridObject.prototype._insertRowAt_gA = dhtmlXGridObject.prototype._insertRowAt;

dhtmlXGridObject.prototype._insertRowAt = function (a, d, b)
{
  if (typeof this.pj != "undefined")
  {
    var c = this.nh ? this.aC._bfs_cells(a.idd, this._gIndex).getValue() : this._bfs_cells3 ? this._bfs_cells3(a, this._gIndex).getValue() : this.cells3(a, this._gIndex).getValue();
    c || (c = " ");
    d = this._inc_group(c, a.style.display == "none")
  }
  var e = this._insertRowAt_gA(a, d, b);
  typeof this.pj != "undefined" && (this.expandGroup(c), this._updateGroupView(this.pj[c]), this.updateGroups());
  return e
};

dhtmlXGridObject.prototype._updateGroupView = function (a)
{
  if (this.aC && !this.nh) return a.row.firstChild.innerHTML = "&nbsp;";
  var d = this._gmask || this.aC._gmask,
    b = "<img style='margin-bottom:-4px' src='" + this.eg + a.state + ".gif'> ";
  b += this.customGroupFormat ? this.customGroupFormat(a.text, a.count) : a.text + " ( " + a.count + " ) ";
  a.row.childNodes[d.ahi].innerHTML = b
};

dhtmlXGridObject.prototype._addPseudoRow = function (a)
{
  for (var d = this._gmask || this.aC._gmask, b = d.cloneNode(!0), c = 0; c < b.childNodes.length; c++) if (b.childNodes[c]._cellIndex = d.childNodes[c]._cellIndex, this.nh) b.childNodes[c].style.display = "";
  var e = this;
  b.onclick = function (a)
  {
    if (e.callEvent("onGroupClick", [this.te.text])) e.aC && e.nh ? e.aC._switchGroupState(e.aC.pj[this.te.text].row) : e._switchGroupState(this), (a || event).cancelBubble = "true"
  };
  b.ondblclick = function (a)
  {
    (a || event).cancelBubble = "true"
  };
  a || (cn ? this.obj.appendChild(b) : this.obj.firstChild.appendChild(b), this.am.push(b));
  return b
};

dhtmlXGridObject.prototype._groups_get = function ()
{
  var a = [];
  this._temp_par = this.obj.parentNode;
  this._temp_par.removeChild(this.obj);
  for (var d = [], b = this.am.length - 1; b >= 0; b--) this.am[b].te ? (this.am[b].te._childs = d, d = [], a.push(this.am[b])) : d.push(this.am[b]), this.am[b].parentNode.removeChild(this.am[b]);
  return a
};

dhtmlXGridObject.prototype._groups_put = function (a)
{
  var d = this.am.stablesort;
  this.am = new dhtmlxArray(0);
  this.am.stablesort = d;
  for (var b = 0; b < a.length; b++)
  {
    var c = a[b].te;
    this.obj.firstChild.appendChild(c.row);
    this.am.push(c.row);
    c.row.idd = null;
    for (var e = 0; e < c._childs.length; e++) this.obj.firstChild.appendChild(c._childs[e]), this.am.push(c._childs[e]);
    delete c._childs
  }
  this._temp_par.appendChild(this.obj)
};

dhtmlXGridObject.prototype._groupExisting = function (a)
{
  if (this.iD())
  {
    a = [];
    this._temp_par = this.obj.parentNode;
    this._temp_par.removeChild(this.obj);
    for (var d = [], b = this.am.length, c = 0; c < b; c++)
    {
      var e = this.cells4(this.am[c].childNodes[this._gIndex]).getValue();
      this.am[c].style.display = "";
      e || (e = " ");
      if (!this.pj[e])
      {
        this.pj[e] = {
          text: e,
          row: this._addPseudoRow(!0),
          count: 0,
          state: "minus"
        };
        var f = this.pj[e];
        f.row.te = f;
        this.pj[e]._childs = [];
        a.push(f.row)
      }
      this.pj[e].count++;
      this.pj[e]._childs.push(this.am[c]);
      this.am[c].parentNode.removeChild(this.am[c])
    }
    for (c = 0; c < a.length; c++) this._updateGroupView(a[c].te);
    this._groups_put(a);
    if (this.aC && !this.nh) this._mirror_rowsCol(), this.aC.pj = [], this.aC._reset_view();
    this.callEvent("onGridReconstructed", []);
    this.updateGroups()
  }
};

dhtmlXGridObject.prototype._switchGroupState = function (a)
{
  var d = a.te;
  if (this.aC && !this.nh) d.state = this.aC.pj[a.te.text].row.te.state, this.aC._switchGroupState(this.aC.pj[a.te.text].row);
  var b = this.am.bP(d.row) + 1;
  d.state = d.state == "minus" ? "plus" : "minus";
  for (var c = d.state == "plus" ? "none" : ""; this.am[b] && !this.am[b].te;) this.am[b].style.display = c, b++;
  this._updateGroupView(d);
  this.callEvent("onGroupStateChanged", [d.text, d.state == "minus"]);
  this.setSizes()
};

dhtmlXGridObject.prototype.expandGroup = function (a)
{
  this.pj[a].state == "plus" && this._switchGroupState(this.pj[a].row)
};

dhtmlXGridObject.prototype.collapseGroup = function (a)
{
  this.pj[a].state == "minus" && this._switchGroupState(this.pj[a].row)
};

dhtmlXGridObject.prototype.expandAllGroups = function ()
{
  for (var a in this.pj) this.pj[a] && this.pj[a].state == "plus" && this._switchGroupState(this.pj[a].row)
};

dhtmlXGridObject.prototype.collapseAllGroups = function ()
{
  for (var a in this.pj) this.pj[a] && this.pj[a].state == "minus" && this._switchGroupState(this.pj[a].row)
};

dhtmlXGridObject.prototype.hideGroupColumn = function (a, d)
{
  if (!this.aC)
  {
    for (var b = -1, c = this._gmask.childNodes, e = 0; e < c.length; e++) if (c[e]._cellIndex == a)
    {
      b = e;
      break
    }
    if (b != -1) for (var f in this.pj) this.pj[f].row.childNodes[b].style.display = d ? "none" : ""
  }
};

dhtmlXGridObject.prototype.groupStat = function (a, d, b)
{
  var b = this["_g_" + (b || "stat_total")],
    c = 0,
    e = 0;
  this.forEachRowInGroup(a, function (a)
  {
    c = b(c, this.cells(a, d).getValue() * 1, e);
    e++
  });
  return c
};

dhtmlXGridObject.prototype.forEachRowInGroup = function (a, d)
{
  var b = this.pj[a].row.nextSibling;
  if (b) for (; b && !b.te;) d.call(this, b.idd), b = b.nextSibling;
  else
  {
    var c = this.pj[a]._childs;
    if (c) for (var e = 0; e < c.length; e++) d.call(this, c[e].idd)
  }
};

dhtmlXGridObject.prototype.updateGroups = function ()
{
  if (this._gmask && this._gmask._math && !this._parsing) for (var a = this._gmask.childNodes, d = 0; d < a.length; d++) a[d]._counter && this._b_processing.apply(this, a[d]._counter)
};

dhtmlXGridObject.prototype._b_processing = function (a, d, b)
{
  var c = 0,
    e = 0;
  this.XC[this.cellType[d]] || this.adH(
  {
    parentNode: {
      grid: this
    }
  }, this.cellType[d]);
  for (var f = this.am.length - 1; f >= 0; f--) this.am[f].te ? (this.adH(this.am[f].childNodes[b], this.cellType[d]).setValue(c), e = c = 0) : (c = a(c, this.cells3(this.am[f], d).getValue() * 1, e), e++)
};

dhtmlXGridObject.prototype._g_stat_total = function (a, d)
{
  return a + d
};

dhtmlXGridObject.prototype._g_stat_min = function (a, d, b)
{
  b || (a = Infinity);
  return Math.min(a, d)
};

dhtmlXGridObject.prototype._g_stat_max = function (a, d, b)
{
  b || (a = -Infinity);
  return Math.max(a, d)
};

dhtmlXGridObject.prototype._g_stat_average = function (a, d, b)
{
  return (a * b + d) / (b + 1)
};

dhtmlXGridObject.prototype._g_stat_count = function (a)
{
  return a++
};
