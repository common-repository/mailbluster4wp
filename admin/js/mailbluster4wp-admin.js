(function ($) {
  "use strict";

  /**
   * All of the code for your admin-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */
  $(function () {
    var $navTabParent = $("#mailbluster-form-options");
    var $navTabWrapper = $navTabParent.find(".nav-tab-wrapper");
    $navTabWrapper.find(".nav-tab").on("click", function (e) {
      e.preventDefault();
      var $this = $(this);
      var $dataTrigger = $($this.data("trigger"));

      if ($this.hasClass("mb4wp-nav-tab-active")) return null;
      // console.log($dataTrigger);

      $navTabWrapper.find(".nav-tab").removeClass("mb4wp-nav-tab-active");
      // $this.addClass('nav-tab-active');
      $this.addClass("mb4wp-nav-tab-active");

      $navTabParent.find(".mb4wp-container").addClass("hidden");
      $dataTrigger.removeClass("hidden");
    });

    var toolbarOptions = [['bold', 'italic', 'underline', 'link']];
    var quill = new Quill('#mbq_editor', {
      modules: {
        toolbar: toolbarOptions,    // Snow includes toolbar by default
      },
      formats: {
        bold: false
      },
      theme: 'snow'
    });
    const tooltip = quill.theme.tooltip;
    const input = tooltip.root.querySelector("input[data-link]");
    input.dataset.link = `eg: ${ document.location.origin }`;

    const consentTextarea = $("#consent_textarea");
    const builder_form_checkbox_label = $("#mb4wp_builder_form_checkbox_label");
    quill.on('text-change', function() {
      const p = Array.from(document.querySelector(".ql-editor").children);
      const content = p.map(el => el.innerHTML);
      consentTextarea.val(`${content.map(el=> `<p>${el}</p>`).join("")}`);
      builder_form_checkbox_label.html(`${content.map(el=> `<p>${el}</p>`).join("")}`);
    });
  });
})(jQuery);
