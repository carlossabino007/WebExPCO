/**
 * jQuery popup boxes plugin
 * 
 * Requires jQuery and jQuery UI 
 */
(function ($) {
  $.extend({
    prompt: function(msg, defaultValue, options) {
      var dfd = new $.Deferred(),
        $promtDiv = $('<div />', { title: msg }),
        _ok = function() {
          dfd.resolve($promtDiv.find('input').val());
          $promtDiv.remove();
        },
        _close = function() {
          dfd.reject();
          $promtDiv.remove();
        },
        settings = $.extend({
          close: _close,
          buttons: {
            "OK": _ok,
            "Cancel": _close
          }
        }, $.popupDialog, options),
        $input = $('<input />', {
          type: "text",
          val: defaultValue || "",
          keypress: function(e) {
            if (e.which === 13) {
              _ok();
            }
          }
        }).focus();
      $promtDiv
        .append($input)
        .appendTo('body')
        .dialog(settings)
        .find('input')
          .focus()
          .select();
      return dfd.promise();
    },
    confirm: function(msg, titleMsg, options) {
      var dfd = new $.Deferred(),
        $confirmDiv = $('<div />', {title: titleMsg || ""}),
        resolveFunction = function(respoveMethod) {
          return function() {
            dfd[respoveMethod]();
            $confirmDiv.remove();
          };
        },
        settings = $.extend({
          close: resolveFunction("reject"),
          buttons: {
            "OK": resolveFunction("resolve"),
            "Cancel":  resolveFunction("reject")
          }
        }, $.popupDialog, options);
      $confirmDiv
        .append(msg)
        .appendTo('body')
        .dialog(settings);
      return dfd.promise();
    },
    alert: function(msg, titleMsg, options) {
      var dfd = new $.Deferred(),
        $alertDiv = $('<div />', { title: titleMsg || "" }),
        resolveFunction = function(msg) {
          return function() {
            dfd.resolve(msg);
            $alertDiv.remove();
          };
        },
        settings = $.extend({
          close: resolveFunction("Close"),
          buttons: {
            "OK": resolveFunction("OK")
          }
        }, $.popupDialog, options);
      $alertDiv
        .append(msg)
        .appendTo('body')
        .dialog(settings);
      return dfd.promise();
    },
    popupDialog: {
      modal: true,
      width: 350,
      height: 200
    }
  });
}(jQuery));