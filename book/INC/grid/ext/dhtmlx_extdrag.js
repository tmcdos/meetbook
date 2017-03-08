/*
===================================================================
Copyright Dinamenta, UAB http://www.dhtmlx.com
This code is obfuscated and not allowed for any purposes except 
using on sites which belongs to Dinamenta, UAB

Please contact sales@dhtmlx.com to obtain necessary 
license for usage of dhtmlx components.
===================================================================
*/
function dhx_ext_check(a, b, c, d)
{
  for (var f = c.acE == "tree" ? "id" : "idd", a = [], e = 0; e < c.jG.length; e++) a[a.length] = c.jG[e][f];
  return !this._onDrInFuncA || this._onDrInFuncA(a, b, c, d) ? dhx_allow_drag() : dhx_deny_drop()
}

function dhx_deny_drop()
{
  window.aj.al.firstChild.rows[0].cells[0].className = "dragAccessD";
  return !1
}

function dhx_allow_drag()
{
  window.aj.al.firstChild.rows[0].cells[0].className = "dragAccessA";
  return !0
};

try
{
  _isIE && document.execCommand("BackgroundImageCache", !1, !0)
}

catch (e$$5) {}

if (window.dhtmlXGridObject) 
{
  dhtmlXGridObject.prototype.rowToDragElement = function ()
  {
    var a = this.jG.length,
      b = "",
      b = a == 1 ? a + " " + (this._dratA || "message") : a + " " + (this._dratB || "messages");
    return "<table cellspacing='0' cellpadding='0'><tbody><tr><td class='dragAccessD'>&nbsp</td><td class='dragTextCell'>" + b + "</td></tbody><table>"
  };

  dhtmlXGridObject.prototype._init_point_bd = dhtmlXGridObject.prototype.Yz, dhtmlXGridObject.prototype.Yz = function ()
  {
    this.attachEvent("onDragIn", dhx_ext_check);
    this._init_point_bd && this._init_point_bd()
  };
  
  dhtmlXGridObject.prototype.setDragText = function (a, b)
  {
    this._dratA = a;
    this._dratB = b ? b : a + "s"
  };
}

if (window.dhtmlXTreeObject) 
{
  dhtmlXTreeObject.prototype.gL = function (a, b)
  {
    if (!this.dADTempOff) return null;
    var c = a.parentObject;
    if (!this.callEvent("onBeforeDrag", [c.id])) return null;
    c.i_sel || this._selectItem(c, b);
    this._checkMSelectionLogic();
    var d = document.createElement("div"),
      f = this._selected.length,
      e = "",
      e = f == 1 ? f + " " + (this._dratA || "message") : f + " " + (this._dratB || "messages");
    d.innerHTML = "<table cellspacing='0' cellpadding='0'><tbody><tr><td class='dragAccessD'>&nbsp</td><td class='dragTextCell'>" + e + "</td></tbody><table>";
    d.style.position = "absolute";
    d.className = "dragSpanDiv";
    this.jG = [].concat(this._selected);
    return d
  };
  dhtmlXTreeObject.prototype.setDragText = function (a, b)
  {
    this._dratA = a;
    this._dratB = b ? b : a + "s"
  };
  dhtmlXTreeObject.prototype._onDrInFunc = dhx_ext_check, dhtmlXTreeObject.prototype.setOnDragIn = function (a)
  {
    this._onDrInFuncA = typeof a == "function" ? a : eval(a)
  };
}

dhtmlDragAndDropObject.prototype.hq = function ()
{
  dhx_deny_drop()
};
