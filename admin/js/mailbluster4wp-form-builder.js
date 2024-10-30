(function ($) {
  "use strict";

  $(function () {
    // shortcode copy
    const copyBtn = $("#copy-btn");
    let timer;
    copyBtn.on("click", function (e) {
      e.preventDefault();
      const clipboard = new ClipboardJS("#copy-btn");
      clipboard.on('success', function(e) {
        copyBtn.text("Copied");
      if (timer) {
        clearTimeout(timer);
      }
      timer = setTimeout(function () {
        copyBtn.text("Copy");
      }, 1000 * 3);
      e.clearSelection();
    });
      
    });
    // subscribe button text title and description live change
    const messageSubscribe = $("#mb4wp-form-submit-message");
    const builderSubscribe = $("#mb4wp_builder_subscribe_btn");
    const title = $("#title");
    const builderTitle = $("#mb4wp-builder-title");
    const des = $("#mb4wp-description");
    const builderDes = $("#mb4wp_builder_description");
    // subscribe button live change
    messageSubscribe.on("input", function () {
      const textOnly = $($.parseHTML(messageSubscribe.val())).text();
      builderSubscribe.text(textOnly);
    });
    // title live change
    title.on("input", function () {
      builderTitle.text(title.val());
    });
    // description live change
    des.on("input", function () {
      const textOnly = $($.parseHTML(des.val())).text();
      builderDes.text(textOnly);
    });
    // showing textarea depending on consent checkbox in settings tab
    const consentCheckbox = $("#mb4wp-form-consent-checkbox");
    const mbqEditor = $("#mbq_editor");
    const formBuilderConsent = $("#mb4wp_form_builder_consent");
    const builder_form_checkbox_label = $("#mb4wp_builder_form_checkbox_label");
    
    // quill js toolbar hide first time if checkbox not checked
    if(!consentCheckbox.prop("checked")){
      $(".settings").find(".ql-toolbar").css("display", "none");
    }
    if (consentCheckbox) {
      consentCheckbox.on("change", function () {
        if (consentCheckbox.prop("checked")) {
          $(".settings").find(".ql-toolbar").css("display", "block");
          mbqEditor.css("display", "block");
          formBuilderConsent.css("display", "block");
        } else {
          $(".settings").find(".ql-toolbar").css("display", "none");
          mbqEditor.css("display", "none");
          formBuilderConsent.css("display", "none");
        }
      });
    }

    // showing branding depending on branding checkbox in builder tab
    const brandingCheckbox = $("#mb4wp-form-branding");
    const builder_form_branding = $("#mb4wp_builder_form_branding");
    brandingCheckbox.on("change", function () {
      if (brandingCheckbox.prop("checked")) {
        builder_form_branding.css("display", "block");
      } else {
        builder_form_branding.css("display", "none");
      }
    });

    // showing RedirectURL textarea depending on RedirectURL checkbox in settings tab
    const redirectURLCheckbox = $("#mb4wp-form-redirectURL");
    const redirectURLTextarea = $("#redirectURL_textarea");
    redirectURLCheckbox.on("change", function () {
      if (redirectURLCheckbox.prop("checked")) {
        redirectURLTextarea.css("display", "block");
      } else {
        redirectURLTextarea.css("display", "none");
      }
    });

    // Showing theme customization option depending on custom theme option
    const mb4wpFormTheme = $("#mb4wp-form-theme");
    const customDesignProperty = $(".custom-theme");
    mb4wpFormTheme.on('change', function(e){
      if('custom' === e.target.value){
        customDesignProperty.css({display:"table-row"});
      } else {
        customDesignProperty.css({display:"none"});
      }
    });

    $(document).on('click','.check_image_container', function (e){
      e.preventDefault();
      $(this).siblings('button[type="button"]').text($(this).siblings('input').val());

      $(this).siblings('button').css('display', 'block');
      $(this).siblings('input').attr('type', 'hidden');
      $(this).siblings('.edit_image_container').css('display', 'block');
      $(this).css('display', 'none');
    })

    //Assigning Element Object
    var $appendArea = $(".mb4wp-form"),
      $optionPanelPredefined = $(".mb4wp-fmbldr-predefined-fields"),
      $sortable = $("#sortable");

    const buttonList = $optionPanelPredefined.find(".mb4wp-field-button");
    const inputList = $appendArea.find("input[type='text']");

    const inputListValueArr = [];
    inputList.each(function (i, input) { inputListValueArr.push($(input).attr('id')) });
    const inputListValueStr = inputListValueArr.toString();

    buttonList.each(function (index, button){
      const value = $(button).val();
      if(inputListValueStr.includes(value)) {
        $(button).attr("disabled", true);
        const editIcon = $(button).closest($(".mb4wp-predefined-button")).find(".edit_image_container");
        if(editIcon){
          editIcon.attr("disabled", true);
        }
      }
    })

    $(document).on("click",".edit_image_container", function (){
      const id = $(this).data("id");
      const fieldLabel = $(this).data("fieldlabel");
      const fieldMergeTag = $(this).closest($(".mb4wp-predefined-button"))
      .find(".mb4wp-field-button").val();
      if (id){
        $("body").append(mb4wp_render_modal(id, fieldLabel, fieldMergeTag));
      } else {
        // button hide and input field visible for option editing
        $(this).siblings('input').attr('type', 'text');
        $(this).siblings('button').css('display', 'none');
        $(this).siblings('.check_image_container').css('display', 'block');
        $(this).css('display', 'none');
      }
      return false;
    })

    $(".edit_image_container").siblings('input').on('keyup', function(e){
      const event = e
      const buttonVal = $(this).siblings('button').val();

      $('.mb4wp-label').each(function(){

        if (buttonVal == $(this).attr('for')) {

          if($(this).text().substr(-1) === '*' && '*' === $(this).siblings('input[type="hidden"]').val().substr(-1)){
            $(this).text(event.target.value + '*');
            $(this).siblings('input[type="hidden"]').val(event.target.value + '*');
          } else {
            $(this).text(event.target.value);
            $(this).siblings('input[type="hidden"]').val(event.target.value);
          }
        }
      })
    })

    $(document).on("click", "#mb4wp-custom-field-add", function(){
      const id = '';
      const fieldLabel = '';
      const fieldMergeTag = '';
      $("body").append(mb4wp_render_addField_modal(id, fieldLabel, fieldMergeTag));
    })

    // modal disappear on close button click
    $(document).on("click", ".mb4wp-modal-close", function(){
      $(".mb4wp-dialog").remove();
    })

    // delete modal disappear on close button click
    $(document).on("click", ".mb4wp-delete-modal-close", function(){
      $(".mb4wp-delete-dialog").remove();
    })

    // get modal field value
    const observer = new MutationObserver(function(){
      if($(".mb4wp-modal-dialog")){
        $(document).on("submit", ".mb4wp-modal-dialog", function(e){
          e.preventDefault();
          const form = $(".mb4wp_dialog_form")
            .find('input[name^=mb4wp_dialog_form]')
            .serialize();

          var data = {};
          data["action"] = "mb4wp_dialog_form_process";
          data["method"] = "PUT";
          data["security"] = mb4wpAjaxDialogForm.form_nonce;
          data["formData"] = form;
          
          $.ajax({
            url: mb4wpAjaxDialogForm.ajax_url,
            type: "POST",
            data: data,
            success:function ( res ) {
              mb4wp_setCookie("mb4wp-dialog-msg", res, 30);
              $(".mb4wp-dialog").remove();
              location.reload();
            },
            error: function ( res ){
              mb4wp_setCookie("mb4wp-dialog-msg", res, 30);
              $(".mb4wp-dialog").remove();
              location.reload();
            }
          })
        })
        observer.disconnect();
      }
    })

    const target = document.querySelector("body");
    const config = { childList: true };

    observer.observe(target, config);

    $(document).on("click", '.mb4wp-button-warning[value="Delete Field"][type="button"]', function(){
      // $(".mb4wp-dialog").remove();
      $("body").append(mb4wp_render_delete_modal());
    })

    // DELETE the custom field
    $(document).on("click", '.mb4wp-button-warning[value="Delete Field"][type="submit"]', function(){
      const form = $(".mb4wp_dialog_form")
            .find('input[name^=mb4wp_dialog_form]')
            .serialize();
          
          var data = {};
          data["action"] = "mb4wp_dialog_form_process";
          data["method"] = "DELETE";
          data["security"] = mb4wpAjaxDialogForm.form_nonce;
          data["formData"] = form;
          
          $.ajax({
            url: mb4wpAjaxDialogForm.ajax_url,
            type: "POST",
            data: data,
            success:function ( res ) {
              mb4wp_setCookie("mb4wp-dialog-msg", res, 30);
              $(".mb4wp-delete-dialog").remove();
              $(".mb4wp-dialog").remove();
              location.reload();
            },
            error: function ( res ){
              mb4wp_setCookie("mb4wp-dialog-msg", res, 30);
              location.reload();
            }
          })
    })

    // generate merge tag from label
    $(document).on("blur", "#fieldLabel", function (){
      const fieldLabel = $(this).val();
      if(!$("#fieldMergeTag").val()){
        $("#fieldMergeTag").val(fieldLabel.toLowerCase().replace(/\s+/g, "_"));
      }
    })

    var $predefinedButton = $optionPanelPredefined.find(".mb4wp-field-button");
    $predefinedButton.on("click", function () {
      var currentButtonMargeTag = $(this).val(),
        currentButtonValue = $(this).siblings("button.edit_image_container").data("fieldlabel");

      $appendArea.append(renderHTML(currentButtonValue, currentButtonMargeTag, "disabled"));
      $(this).attr("disabled", true);
      const editIcon = $(this).closest($(".mb4wp-predefined-button")).find(".edit_image_container");
      if(editIcon){
        editIcon.attr("disabled", true);
      }
    });

    $(document).on("click", ".mb4wp-fmbldr-close", function (e) {
      var $currentCloseItem = $(this).closest(".mb4wp-fmbldr-text-inputs");
      var $closestDynamic = $(this).closest(
        ".mb4wp-fmbldr-dynamic-form-fields"
      );
      var currentItemInputId = $currentCloseItem.find("input").attr("id");

      if (currentItemInputId !== "email") {
        const button = $optionPanelPredefined.find("button[value=" + currentItemInputId + "]");
        button.attr("disabled", false);
        const editIcon = $(button).closest($(".mb4wp-predefined-button")).find(".edit_image_container");
        if(editIcon){
          editIcon.attr("disabled", false);
        }
        $closestDynamic.remove();
      }
    });
    $sortable.sortable({
      // revert: true,
      start: function (event, ui) {
        var dataStart = ui.item.index();
        ui.item.data("start_pos", dataStart);
      },
    });
    $sortable.disableSelection();
    $(document).on("click", ".required-field", function () {

      const checkbox = $(this).find($('input[type="checkbox"]'));
      const currentCloseParent = checkbox.closest($('.mb4wp-fmbldr-text-inputs'));
      const itemLabel = currentCloseParent.find($('.mb4wp-label'));
      const itemHiddenInput = currentCloseParent.find($('input[type="hidden"'));

      if(checkbox.prop('checked')){
        if( '*' == (itemLabel.text().slice(-1)) && '*' == (itemHiddenInput.val().slice(-1))){
          return;
        }
        itemLabel.text(itemLabel.text() + '*');
        itemHiddenInput.val(itemHiddenInput.val() + '*');
      } else {
        if( '*' == (itemLabel.text().slice(-1)) && '*' == (itemHiddenInput.val().slice(-1))){
          itemLabel.text(itemLabel.text().slice(0, -1));
          itemHiddenInput.val(itemHiddenInput.val().slice(0, -1));
        }
      }
    })
  });

  function mb4wp_render_delete_modal() {
    return (
      `<div class="mb4wp-delete-dialog">
        <div class="mb4wp-overlay"></div>
        <div class="mb4wp-modal-dialog">
          <div class="mb4wp-modal-content mb4wp-delete-box">
            <div class="mb4wp-modal-header">
              <h2>Delete Field</h2>
              <div class="dashicons dashicons-no-alt mb4wp-delete-modal-close"></div>
            </div>
            <div class="mb4wp-delete-content">
              <p>This action will delete the field along with all the data associated with it. It might take some time depending on the number of leads.</p>
              <p>Are you sure you want to proceed?</p>
            </div>
            <div class="mb4wp-modal-footer">
              <input type="button" value="Cancel" class="button mb4wp-delete-modal-close">
              <input type="submit" value="Delete Field" name="delete" class="mb4wp-button-warning mb4wp-ml-5">
            </div>
          </div>
        </div>
      </div>`
    )
  }

  function mb4wp_render_addField_modal( id, fieldLabel, fieldMergeTag ) {

    return (
      `<div class="mb4wp-dialog">
        <div class="mb4wp-overlay"></div>
        <form class="mb4wp-modal-dialog mb4wp_dialog_form" method="post">
          <div class="mb4wp-modal-content">
            <div class="mb4wp-modal-header">
              <h2>Add New Field</h2>
              <div class="dashicons dashicons-no-alt mb4wp-modal-close"></div>
            </div>
            <div class="mb4wp-modal-body">
              <input type="hidden" name="mb4wp_dialog_form[id]" value${id}>
              <label for="fieldLabel">Field Label</label>
              <input type="text" name="mb4wp_dialog_form[fieldLabel]" id="fieldLabel" value="${fieldLabel}">
              <label for="fieldMergeTag">Field Merge Tag</label>
              <input type="text" name="mb4wp_dialog_form[fieldMergeTag]" id="fieldMergeTag" value="${fieldMergeTag}">
            </div>
            <div class="mb4wp-modal-footer mb4wp-pt-1">
              <input type="button" value="Cancel" class="button mb4wp-modal-close">
              <input type="submit" value="Add Field" name="save" class="button button-primary mb4wp-ml-5">
            </div>
          </div>
        </form>
      </div>`
    )
  }

  function mb4wp_render_modal(id, fieldLabel, fieldMergeTag) {
    return (
      `<div class="mb4wp-dialog">
        <div class="mb4wp-overlay"></div>
        <form class="mb4wp-modal-dialog mb4wp_dialog_form" method="post">
          <div class="mb4wp-modal-content">
            <div class="mb4wp-modal-header">
              <h2>Update Field</h2>
              <div class="dashicons dashicons-no-alt mb4wp-modal-close"></div>
            </div>
            <div class="mb4wp-modal-body">
              <input type="hidden" name="mb4wp_dialog_form[id]" value="${id}">
              <label for="fieldLabel">Field Label</label>
              <input type="text" name="mb4wp_dialog_form[fieldLabel]" id="fieldLabel" value="${fieldLabel}">
              <label for="fieldMergeTag">Field Merge Tag</label>
              <input type="text" name="mb4wp_dialog_form[fieldMergeTag]" id="fieldMergeTag" value="${fieldMergeTag}">
            </div>
            <div class="mb4wp-modal-footer mb4wp-pt-1">
              <div class="mb4wp-flex-1">
                <input type="button" value="Delete Field" name="delete" class="mb4wp-button-warning button-large">
              </div>
              <input type="button" value="Cancel" class="button mb4wp-modal-close">
              <input type="submit" value="Save Field" name="save" class="button button-primary mb4wp-ml-5">
            </div>
          </div>
        </form>
      </div>`
    )
  }

  function renderHTML(clickFieldValue, clickFieldMergeTag, readonly) {
    return (
      `<div class="mb4wp-fmbldr-dynamic-form-fields">
        <div class="mb4wp-fmbldr-text-inputs">
          <label for="${ clickFieldMergeTag }" class="mb4wp-label">${ clickFieldValue }</label>
          <input
            type="text"
            id="${clickFieldMergeTag}"
            class="regular-text"${readonly}
          >
          <input
            type="hidden"
            name="mb4wp_form_builder_options[${clickFieldMergeTag}]"
            value="${clickFieldValue}"
          >
          <span style="margin-left: 3px;" class="dashicons dashicons-dismiss mb4wp-fmbldr-close"></span>
          <span class="dashicons dashicons-move"></span>
          <p class="required-field">
            <input value="checked" name="required_${ clickFieldMergeTag }" type="checkbox" id="required_${ clickFieldMergeTag }">
            <label for="required_${ clickFieldMergeTag }">Required field</label>
          </p>
        </div>
      </div>`
    );
  }

  function mb4wp_setCookie(cookieName, cookieValue, expiryInSeconds){
    const expiry = new Date();
    expiry.setTime(expiry.getTime() + 1000 * expiryInSeconds);
    document.cookie = cookieName + "=" + escape(cookieValue)
      + ";expires=" + expiry.toGMTString() + "; path=/";
  }
})(jQuery);
