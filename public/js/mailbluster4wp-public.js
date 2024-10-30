(function ($) {
  "use strict";

  /**
   * All of the code for your public-facing JavaScript source
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
   * Ideally, it is not considered best practice to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */
  $(function () {
    const consentCheckbox = $("#mb4wp_public_consent_checkbox");
    const subscribeButton = $("#mb4wp_subscribe");
    if("disabled" === subscribeButton.attr("disabled") && (subscribeButton.data("theme") === "light" || subscribeButton.data("theme") === "dark")){
      subscribeButton.css({
        "background-color": "#79AFEA",
        "border": "1px solid #79AFEA",
        "cursor": "default"
      })
    } else if("disabled" === subscribeButton.attr("disabled") && subscribeButton.data("theme") === "custom"){
      subscribeButton.css({
        "opacity": ".5",
        "cursor": "default"
      })
    }
    consentCheckbox.on("change", function () {
      if (consentCheckbox.prop("checked")) {
        subscribeButton.prop("disabled", false);
        if(subscribeButton.data("theme") === "light" || subscribeButton.data("theme") === "dark"){
          subscribeButton.css({
            "background-color": "#057BDE",
            "border": "1px solid #057BDE",
            "cursor": "pointer"
          })
        } else if(subscribeButton.data("theme") === "custom"){
          subscribeButton.css({
            "opacity": "1",
            "cursor": "pointer"
          })
        }
      } else {
        subscribeButton.prop("disabled", true);
        if(subscribeButton.data("theme") === "light" || subscribeButton.data("theme") === "dark"){
          subscribeButton.css({
            "background-color": "#79AFEA",
            "border-color": "#79AFEA",
            "cursor": "default"
          })
        } else if(subscribeButton.data("theme") === "custom"){
          subscribeButton.css({
            "opacity": ".5",
            "cursor": "default"
          })
        }
      }
    });
    var $form = $(".mb4wp-s-form");

    $form.each(function () {
      var $this = $(this);
      var post_id = $this.find(".mb4wp-form-post-id").val();
      var $submit = $(".mb4wp-subscribe");
      $this.on("submit", function (e) {
        e.preventDefault();
        var key = "mb4wp_sform_" + post_id,
          formData = $this
            .find('input[name^="' + key + '"], select[name^="' + key + '"]')
            .serialize();

        var data = {};
        data["action"] = "mb4wp_form_process";
        data["security"] = mb4wpAjaxForm.form_nonce;
        data[key] = formData;
        data["post_id"] = post_id;

        $.ajax({
          url: mb4wpAjaxForm.ajax_url,
          type: "POST",
          data: data,
          success: function (response) {
            $this.find("input").val("");
            $this
              .find("select")
              .find("option:first")
              .attr("selected", "selected");
            $this.hide();
            $this.parent().find("#mb4wp-form-messages").html(response);
          },
          error: function (response) {
            $this.parent().find("#mb4wp-form-messages").html(response);
          },
        });
      });
    });
  });
})(jQuery);
