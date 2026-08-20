(function (window, document, $) {
  "use strict";

  var bootstrap = window.bootstrap || (window.tabler && window.tabler.bootstrap);

  if (!bootstrap) {
    return;
  }

  window.bootstrap = bootstrap;

  function getTarget(element) {
    return element.getAttribute("data-bs-target") || element.getAttribute("data-target") || element.getAttribute("href");
  }

  function normalizeLegacyAttributes(root) {
    root.querySelectorAll('[data-toggle="modal"], [data-toggle="collapse"]').forEach(function (element) {
      var toggle = element.getAttribute("data-toggle");
      var target = element.getAttribute("data-target");
      element.setAttribute("data-bs-toggle", toggle);
      if (target) {
        element.setAttribute("data-bs-target", target);
      }
    });

    root.querySelectorAll('[data-dismiss="modal"]').forEach(function (element) {
      element.setAttribute("data-bs-dismiss", "modal");
    });
  }

  normalizeLegacyAttributes(document);

  window.setTimeout(function () {
    document.querySelectorAll(".preloader").forEach(function (element) {
      element.remove();
    });
  }, 250);

  if (window.MutationObserver) {
    new MutationObserver(function (mutations) {
      mutations.forEach(function (mutation) {
        mutation.addedNodes.forEach(function (node) {
          if (node.nodeType === 1) {
            normalizeLegacyAttributes(node.parentNode || node);
          }
        });
      });
    }).observe(document.body, { childList: true, subtree: true });
  }

  if ($) {
    $.fn.modal = function (action) {
      return this.each(function () {
        var modal = bootstrap.Modal.getOrCreateInstance(this);
        if (action === "hide") {
          modal.hide();
        } else {
          modal.show();
        }
      });
    };
  }

  document.addEventListener("click", function (event) {
    var trigger = event.target.closest('[data-card-widget="collapse"]');
    if (trigger) {
      event.preventDefault();
      var card = trigger.closest(".card");
      if (card) {
        card.querySelectorAll(".card-body, .card-footer").forEach(function (element) {
          element.classList.toggle("d-none");
        });
      }
      return;
    }

    var dismiss = event.target.closest('[data-dismiss="modal"], [data-bs-dismiss="modal"]');
    if (dismiss) {
      var modalElement = dismiss.closest(".modal");
      if (modalElement) {
        bootstrap.Modal.getOrCreateInstance(modalElement).hide();
      }
    }

  });

  document.addEventListener("keydown", function (event) {
    if (event.key !== "Escape") {
      return;
    }

    document.querySelectorAll(".modal").forEach(function (modalElement) {
      if (getComputedStyle(modalElement).display !== "none") {
        bootstrap.Modal.getOrCreateInstance(modalElement).hide();
      }
    });
  });
})(window, document, window.jQuery);
