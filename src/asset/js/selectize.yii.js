(function ($) {
  "use strict";

  $.fn.yiiSelectize = function (options) {
    return this.each(function () {
      if (this.selectize) {
        return;
      }

      var element = this;
      var labels = Array.from(
        document.getElementsByTagName("label"),
      ).filter(function (label) {
        return label.htmlFor === element.id;
      });
      var onInitialize = options.onInitialize;
      $(element).selectize(
        $.extend({}, options, {
          onInitialize: function () {
            var control = this;
            control.settings.onInitialize = onInitialize;
            var destroyed = false;
            var request;
            var listId = element.id + "-listbox";

            control.$dropdown_content.attr({
              id: listId,
              role: "listbox",
              "aria-multiselectable": String(
                control.settings.mode === "multi",
              ),
            });
            control.$control_input.attr({
              role: "combobox",
              "aria-autocomplete": "list",
              "aria-haspopup": "listbox",
              "aria-controls": listId,
              "aria-expanded": "false",
            });
            [
              "aria-label",
              "aria-labelledby",
              "aria-describedby",
              "title",
            ].forEach(function (attribute) {
              var value = element.getAttribute(attribute);
              if (value !== null) {
                control.$control_input.attr(attribute, value);
              }
            });

            function updateAccessibility() {
              control.$control_input.attr({
                "aria-required": String(
                  control.isRequired ||
                    element.getAttribute(
                      "aria-required",
                    ) === "true",
                ),
                "aria-disabled": String(control.isDisabled),
                "aria-invalid":
                  element.getAttribute("aria-invalid") ||
                  "false",
              });
              control.$dropdown_content
                .find("[data-selectable]")
                .each(function (index) {
                  this.id = listId + "-option-" + index;
                  this.setAttribute("role", "option");
                  this.setAttribute(
                    "aria-selected",
                    String(
                      control.items.indexOf(
                        this.getAttribute("data-value"),
                      ) !== -1,
                    ),
                  );
                });
              var active =
                control.$activeOption &&
                control.$activeOption[0];
              if (control.isOpen && active) {
                control.$control_input.attr(
                  "aria-activedescendant",
                  active.id,
                );
              } else {
                control.$control_input.removeAttr(
                  "aria-activedescendant",
                );
              }
            }

            [
              "refreshOptions",
              "setActiveOption",
              "setValue",
              "addItem",
              "removeItem",
              "clear",
              "enable",
              "disable",
            ].forEach(function (method) {
              var original = control[method];
              control[method] = function () {
                var result = original.apply(this, arguments);
                updateAccessibility();
                return result;
              };
            });
            control.on("change", updateAccessibility);
            control.on("dropdown_open", function () {
              control.$control_input.attr(
                "aria-expanded",
                "true",
              );
              updateAccessibility();
            });
            control.on("dropdown_close", function () {
              control.$control_input.attr(
                "aria-expanded",
                "false",
              );
              updateAccessibility();
            });
            var observer = new MutationObserver(
              updateAccessibility,
            );
            observer.observe(element, {
              attributes: true,
              attributeFilter: ["aria-invalid"],
            });

            control.loadRemote = function (
              url,
              data,
              query,
              callback,
            ) {
              if (destroyed) {
                return;
              }
              if (request) {
                request.abort();
              }
              request = $.getJSON(url, data)
                .done(function (rows) {
                  if (!destroyed) {
                    if (!Array.isArray(rows)) {
                      delete control.loadedSearches[
                        query
                      ];
                    }
                    callback(
                      Array.isArray(rows) ? rows : [],
                    );
                  }
                })
                .fail(function () {
                  if (!destroyed) {
                    delete control.loadedSearches[query];
                    callback();
                  }
                });
            };
            control.on("destroy", function () {
              destroyed = true;
              observer.disconnect();
              if (request) {
                request.abort();
              }
              labels.forEach(function (label) {
                label.htmlFor = element.id;
              });
            });
            updateAccessibility();
            if (onInitialize) {
              onInitialize.call(control);
            }
          },
        }),
      );
    });
  };
})(jQuery);
