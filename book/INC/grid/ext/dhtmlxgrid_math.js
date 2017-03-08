/*
===================================================================
Copyright Dinamenta, UAB http://www.dhtmlx.com
This code is obfuscated and not allowed for any purposes except 
using on sites which belongs to Dinamenta, UAB

Please contact sales@dhtmlx.com to obtain necessary 
license for usage of dhtmlx components.
===================================================================
*/
function eXcell_math(a)
{
  if (a) this.cell = a, this.grid = this.cell.parentNode.grid;
  this.edit = function ()
  {
    this.grid.editor = new eXcell_ed(this.cell);
    this.grid.editor.fix_self = !0;
    this.grid.editor.getValue = this.cell.eN ?
    function ()
    {
      return this.cell.eN
    } : this.getValue;
    this.grid.editor.setValue = this.setValue;
    this.grid.editor.edit()
  };
  this.isDisabled = function ()
  {
    return !this.grid._mathEdit
  };
  this.setValue = function (a)
  {
    a = this.grid.MB(a, this.cell, this.fix_self);
    this.grid.dA[this.cell._cellIndex] ? this.grid.adH(this.cell, this.grid.dA[this.cell._cellIndex]).setValue(a) : (this.dq(a), this.cell.mG = !1)
  };
  this.getValue = function ()
  {
    return this.grid.dA[this.cell._cellIndex] ? this.grid.adH(this.cell, this.grid.dA[this.cell._cellIndex]).getValue() : this.cell.innerHTML
  }
}

eXcell_math.prototype = new gD;
dhtmlXGridObject.prototype._init_point_bm = dhtmlXGridObject.prototype.Yz;

dhtmlXGridObject.prototype.Yz = function ()
{
  this._mat_links = {};
  this._aggregators = [];
  this.attachEvent("onClearAll", function ()
  {
    this._mat_links = {};
    this._aggregators = []
  });
  this.attachEvent("onCellChanged", function (a, b)
  {
    if (this._mat_links[a])
    {
      var d = this._mat_links[a][b];
      if (d) for (var c = 0; c < d.length; c++) d[c].parentNode && this.adH(d[c]).setValue(this._calcSCL(d[c]))
    }
    if (!this._parsing && this._aggregators[b])
    {
      var e = this._h2.get[a].parent.id;
      if (e != 0)
      {
        var f = this.cells(e, b);
        f.setValue(this._calcSCL(f.cell))
      }
    }
  });
  this.attachEvent("onAfterRowDeleted", function (a, b)
  {
    if (b != 0 && !this._parsing && this._aggregators.length) for (var d = 0; d < this._aggregators.length; d++) if (this._aggregators[d])
    {
      var c = this.cells(b, d);
      c.setValue(this._calcSCL(c.cell))
    }
    return !0
  });
  this.attachEvent("onXLE", function ()
  {
    for (var a = 0; a < this._aggregators.length; a++) this._aggregators[a] && this._h2.so(0, function (b)
    {
      if (b.bu.length != 0)
      {
        var d = this.cells(b.id, a);
        d.setValue(this._calcSCL(d.cell))
      }
    }, this)
  });
  (this.Yz = this._init_point_bm) && this.Yz()
};

dhtmlXGridObject.prototype.enableMathSerialization = function (a)
{
  this.oX = ap(a)
};

dhtmlXGridObject.prototype.setMathRound = function (a)
{
  this._roundDl = a;
  this._roundD = Math.pow(10, a)
};

dhtmlXGridObject.prototype.enableMathEditing = function (a)
{
  this._mathEdit = ap(a)
};

dhtmlXGridObject.prototype._calcSCL = function (a)
{
  if (!a.Lq) return this.adH(a).getValue();
  try
  {
    dhtmlx.agrid = this;
    var b = eval(a.Lq)
  }
  catch (d)
  {
    return "#SCL"
  }
  if (this._roundD)
  {
    var c = Math.abs(b) < 1 ? "0" : "";
    b < 0 && (c = "-" + c);
    b = Math.round(Math.abs(b) * this._roundD).toString();
    if (b == 0) return 0;
    if (this._roundDl > 0)
    {
      var e = b.length - this._roundDl;
      e < 0 && (b = ("000000000" + b).substring(9 + e), e = 0);
      return c + b.substring(0, e) + "." + b.substring(e, b.length)
    }
    return c + b
  }
  return b
};

dhtmlXGridObject.prototype._countTotal = function (a, b)
{
  for (var d = 0, c = this._h2.get[a], e = 0; e < c.bu.length; e++)
  {
    if (!c.bu[e].buff) break;
    if (c.bu[e].buff._parser)
    {
      this._h2.so(a, function (a)
      {
        a.bu.length == 0 && (d += parseInt(this._get_cell_value(a.buff, b), 10))
      }, this);
      break
    }
    d += parseInt(this._get_cell_value(c.bu[e].buff, b), 10)
  }
  return d
};

dhtmlXGridObject.prototype.MB = function (a, b, d)
{
  if (a === null || a === window.undefined) return a;
  a = a.toString();
  if (a.indexOf("=") != 0)
  {
    this._reLink([], b);
    if (d) b.Lq = b.eN = null;
    return a
  }
  b.eN = a;
  var c = null,
    a = a.replace("=", "");
  if (a.indexOf("sum") != -1)
  {
    a = a.replace("sum", "(dhtmlx.agrid._countTotal('" + b.parentNode.idd + "'," + b._cellIndex + "))");
    if (!this._aggregators) this._aggregators = [];
    this._aggregators[b._cellIndex] = "sum";
    b.Lq = a;
    return this._parsing ? "" : this._calcSCL(b)
  }
  if (a.indexOf("[[") != -1)
  {
    var e = /(\[\[([^\,]*)\,([^\]]*)]\])/g;
    dhtmlx.agrid = this;
    c = c || [];
    a = a.replace(e, function (a, d, e, f)
    {
      if (e == "-") e = b.parentNode.idd;
      e.indexOf("#") == 0 && (e = dhtmlx.agrid.bB(e.replace("#", "")));
      c[c.length] = [e, f];
      return '(parseFloat(dhtmlx.agrid.cells("' + e + '",' + f + ").getValue(),10))"
    })
  }
  if (a.indexOf(":") != -1)
  {
    e = /:(\w+)/g;
    dhtmlx.agrid = this;
    var f = b.parentNode.idd,
      c = c || [],
      a = a.replace(e, function (a, b)
      {
        c[c.length] = [f, dhtmlx.agrid.getColIndexById(b)];
        return '(parseFloat(dhtmlx.agrid.cells("' + f + '",dhtmlx.agrid.getColIndexById("' + b + '")).getValue(),10))'
      })
  }
  else e = /c([0-9]+)/g, dhtmlx.agrid = this, f = b.parentNode.idd, c = c || [], a = a.replace(e, function (a, b)
  {
    c[c.length] = [f, b];
    return '(parseFloat(dhtmlx.agrid.cells("' + f + '",' + b + ").getValue(),10))"
  });
  this._reLink(c, b);
  b.Lq = a;
  return this._calcSCL(b)
};

dhtmlXGridObject.prototype._reLink = function (a, b)
{
  if (a.length) for (var d = 0; d < a.length; d++)
  {
    this._mat_links[a[d][0]] || (this._mat_links[a[d][0]] = {});
    var c = this._mat_links[a[d][0]];
    c[a[d][1]] || (c[a[d][1]] = []);
    c[a[d][1]].push(b)
  }
};

cn &&
function ()
{
  var a = String.prototype.replace;
  String.prototype.replace = function (b, d)
  {
    if (typeof d != "function") return a.apply(this, arguments);
    var c = "" + this,
      e = d;
    if (!(b instanceof RegExp))
    {
      var f = c.indexOf(b);
      return f == -1 ? c : a.apply(c, [b, e(b, f, c)])
    }
    for (var g = b, i = [], h = g.lastIndex, j;
    (j = g.exec(c)) != null;)
    {
      var f = j.index,
        k = j.concat(f, c);
      i.push(c.slice(h, f), e.apply(null, k).toString());
      if (g.global) h = g.lastIndex;
      else
      {
        h += RegExp.lastMatch.length;
        break
      }
    }
    i.push(c.slice(h));
    return i.join("")
  }
}();