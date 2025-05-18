var arCU = {
    prevOrder: null,
    ajaxUrl: null,
    addTitle: null,
    editTitle: null,
    successSaveMessage: null,
    successOrderMessage: null,
    successDeleteMessage: null,
    errorMessage: null,
    nonce: null,
    form: {
        _processErrors: function(formId, data){
            arCU.form._clearErrors(formId);
            if (data.success == 0){
                jQuery.each(data.errors, function(index){
                    jQuery('#' + formId + '_' + index).parents('.field').addClass('has-error');
                    jQuery('#' + formId + '_' + index).parents('.field').find('.errors').html(data.errors[index].join('<br/>'));
                });
                arCU.showErrorMessage(arCU.errorMessage);
                return true;
            }
            return false;
        },
        _clearErrors: function(formId){
            jQuery('#' + formId + '-form .field.has-error').removeClass('has-error');
        },
        _resetForm: function(formId){
            jQuery('#' + formId + '-form [data-default]').each(function(){
                var attr = jQuery(this).attr('data-default');
                if (typeof attr !== typeof undefined && attr !== false) {
                    jQuery(this).val(jQuery(this).data('default'));
                }
            });
            jQuery('#' + formId + '-form .arcu-img-preview').html('');
            arCU.form._clearErrors(formId);
        },
        _getFormData: function(formId){
            var params = {};
            jQuery('#' + formId + '-form [data-serializable="true"]').each(function(){
                if (jQuery(this).data('lang-field')) {
                    if (typeof params[jQuery(this).attr('name')] == 'undefined'){
                        params[jQuery(this).attr('name')] = {};
                    }
                    var lang = jQuery(this).data('lang-code');
                    params[jQuery(this).attr('name')][lang] = jQuery(this).val();
                } else {
                    if (jQuery(this).attr('type') == 'checkbox'){
                        params[jQuery(this).attr('name')] = jQuery(this).is(':checked')? 1 : 0;
                    } else {
                        params[jQuery(this).attr('name')] = jQuery(this).val();
                    }
                }
            });

            return params;
        },
        _populateForm: function(formId, data){
            jQuery.each(data, function(i){
                var fieldId = '#' + formId + '_' + i;
                if (typeof data[i] == 'object' && data[i] !== null) {
                    jQuery.each(data[i], function(ii){
                        var fieldId = '#' + formId + '_' + i + '_' + ii;
                        jQuery(fieldId).val(data[i][ii]);
                    });
                } else {
                    if (jQuery(fieldId).attr('type') == 'checkbox'){
                        if (data[i] == "1" || data[i] == 1){
                            jQuery(fieldId).prop('checked', true);
                        } else {
                            jQuery(fieldId).prop('checked', false);
                        }
                    } else {
                        jQuery(fieldId).val(data[i]);
                    }
                }
            });
        },
        reset: function(alert){
            if (confirm(alert)) {
                arCU.blockUI('#arcontactus-forms');
                jQuery.ajax({
                    type: 'GET',
                    url: arCU.ajaxUrl,
                    dataType: 'json',
                    data: {
                        action : 'arcontactus_reset_forms',
                        _wpnonce: arCU.nonce,
                        ajax : true
                    },
                    success: function(data){
                        jQuery('#arcontactus-forms').replaceWith(data.content);
                        arCU.init();
                        arCU.unblockUI('#arcontactus-forms');
                    }
                }).fail(function(){
                    arCU.unblockUI('#arcontactus-forms');
                    arCU.showErrorMessage(arCU.errorMessage);
                });
            }
        },
        reload: function(){
            arCU.blockUI('#arcontactus-forms');
            jQuery.ajax({
                type: 'GET',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_reload_forms',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                },
                success: function(data){
                    jQuery('#arcontactus-forms').replaceWith(data.content);
                    arCU.init();
                    arCU.unblockUI('#arcontactus-forms');
                }
            }).fail(function(){
                arCU.unblockUI('#arcontactus-forms');
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        edit: function(id){
            arCU.blockUI('#arcontactus-forms');
            arCU.form._resetForm('arcontactus_form');
            jQuery.ajax({
                type: 'GET',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_form_edit',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    id: id
                },
                success: function(data){
                    jQuery('#arcontactus-form-modal').uiModal({
                        closable: false
                    }).uiModal('show');
                    jQuery('#arcontactus_form-form .perfex-field-assign select').html('');
                    jQuery('#arcontactus_form-form .perfex-field-assign select').append('<option value="">-- not assigned --</option>');
                    jQuery.each(data.fields, function(i){
                        jQuery('#arcontactus_form-form .perfex-field-assign select').append('<option value="' + i + '">' + i + '</option>');
                    });
                    arCU.form._populateForm('arcontactus_form', data);
                    changeFormIntegrations();
                    if (data.icon_img) {
                        jQuery('#arcontactus_form_icon_img-preview').html(data.icon_img_preview);
                    }
                    if (data.button_icon_img) {
                        jQuery('#arcontactus_form_button_icon_img-preview').html(data.button_icon_img_preview);
                    }
                    jQuery('#arcontactus_form_icon_svg-dropdown').dropdown('set selected', data.icon_svg);
                    jQuery('#arcontactus_form_button_icon_svg-dropdown').dropdown('set selected', data.button_icon_svg);
                    headerLayoutChanged();
                    headerIconTypeChanged();
                    buttonIconTypeChanged();
                    arCU.unblockUI('#arcontactus-forms');
                }
            }).fail(function(){
                arCU.unblockUI('#arcontactus-forms');
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        save: function(){
            var params = arCU.form._getFormData('arcontactus_form');
            arCU.blockUI('#arcontactus-form-modal');
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_form_save',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    data: params,
                    lang: jQuery('#arcontactus_id_lang').val(),
                    id: jQuery('#arcontactus_form_id').val()
                },
                success: function(data){
                    arCU.unblockUI('#arcontactus-form-modal');
                    if (!arCU.form._processErrors('arcontactus_form', data)){
                        arCU.showSuccessMessage(arCU.successSaveMessage);
                        jQuery('#arcontactus-form-modal').uiModal('hide');
                        arCU.form.reload();
                    }
                }
            }).fail(function(){
                arCU.unblockUI('#arcontactus-form-modal');
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        field: {
            edit: function(formId, id){
                arCU.blockUI('#arcontactus-forms');
                arCU.form._resetForm('arcontactus_field');
                jQuery.ajax({
                    type: 'GET',
                    url: arCU.ajaxUrl,
                    dataType: 'json',
                    data: {
                        action : 'arcontactus_field_edit',
                        _wpnonce: arCU.nonce,
                        ajax : true,
                        formId: formId,
                        id: id
                    },
                    success: function(data){
                        jQuery('#arcontactus_field_form_id').val(formId);
                        jQuery('#arcontactus-field-modal').uiModal({
                            closable: false
                        }).uiModal('show');
                        arCU.form._populateForm('arcontactus_field', data);
                        fieldTypeChanged();
                        changeMask();
                        changeValidationType();
                        arCU.unblockUI('#arcontactus-forms');
                    }
                }).fail(function(){
                    arCU.unblockUI('#arcontactus-forms');
                    arCU.showErrorMessage(arCU.errorMessage);
                });
            },
            save: function(){
                var params = arCU.form._getFormData('arcontactus_field');
                arCU.blockUI('#arcontactus-field-modal');
                jQuery.ajax({
                    type: 'POST',
                    url: arCU.ajaxUrl,
                    dataType: 'json',
                    data: {
                        action : 'arcontactus_field_save',
                        _wpnonce: arCU.nonce,
                        ajax : true,
                        data: params,
                        lang: jQuery('#arcontactus_id_lang').val(),
                        id: jQuery('#arcontactus_field_id').val(),
                        formId: jQuery('#arcontactus_field_form_id').val(),
                    },
                    success: function(data){
                        arCU.unblockUI('#arcontactus-field-modal');
                        if (!arCU.form._processErrors('arcontactus_field', data)){
                            arCU.showSuccessMessage(arCU.successSaveMessage);
                            jQuery('#arcontactus-field-modal').uiModal('hide');
                            arCU.form.reload();
                        }
                    }
                }).fail(function(){
                    arCU.unblockUI('#arcontactus-field-modal');
                    arCU.showErrorMessage(arCU.errorMessage);
                });
            },
            add: function(formId){
                arCU.form._resetForm('arcontactus_field');
                jQuery('#arcontactus_field_form_id').val(formId);
                jQuery('#arcontactus-field-modal').uiModal({
                    closable: false
                }).uiModal('show');
                fieldTypeChanged();
                changeMask();
                changeValidationType();
            },
            reorder: function(formId) {
                var fields = [];
                jQuery('#arcu-form-' + formId + ' .arcu-form-field-group').each(function(){
                    fields.push(jQuery(this).attr('data-id'));
                });
                jQuery.ajax({
                    type: 'POST',
                    url: arCU.ajaxUrl,
                    dataType: 'json',
                    data: {
                        action : 'arcontactus_field_reorder',
                        _wpnonce: arCU.nonce,
                        ajax : true,
                        formId: formId,
                        fields: fields
                    },
                    success: function(data){
                        arCU.unblockUI('#arcontactus-forms');
                        arCU.showSuccessMessage(arCU.successSaveMessage);
                        arCU.form.reload();
                    }
                }).fail(function(){
                    arCU.unblockUI('#arcontactus-forms');
                    arCU.showErrorMessage(arCU.errorMessage);
                });
            },
            remove: function(formId, id){
                if (confirm('Remove the field?')) {
                    arCU.blockUI('#arcontactus-forms');
                    jQuery.ajax({
                        type: 'POST',
                        url: arCU.ajaxUrl,
                        dataType: 'json',
                        data: {
                            action : 'arcontactus_field_remove',
                            _wpnonce: arCU.nonce,
                            ajax : true,
                            id: id,
                            formId: formId,
                        },
                        success: function(data){
                            arCU.unblockUI('#arcontactus-forms');
                            if (data.success) {
                                arCU.showSuccessMessage(arCU.successSaveMessage);
                                arCU.form.reload();
                            } else {
                                alert(data.message);
                            }
                        }
                    }).fail(function(){
                        arCU.unblockUI('#arcontactus-forms');
                        arCU.showErrorMessage(arCU.errorMessage);
                    });
                }
            }
        },
        button: {
            edit: function(formId, id){
                arCU.blockUI('#arcontactus-forms');
                arCU.form._resetForm('arcontactus_button');
                jQuery.ajax({
                    type: 'GET',
                    url: arCU.ajaxUrl,
                    dataType: 'json',
                    data: {
                        action : 'arcontactus_button_edit',
                        _wpnonce: arCU.nonce,
                        ajax : true,
                        formId: formId,
                        id: id
                    },
                    success: function(data){
                        jQuery('#arcontactus_button_form_id').val(formId);
                        jQuery('#arcontactus-button-modal').uiModal({
                            closable: false
                        }).uiModal('show');
                        arCU.form._populateForm('arcontactus_button', data);
                        buttonTypeChanged();
                        arCU.unblockUI('#arcontactus-forms');
                    }
                }).fail(function(){
                    arCU.unblockUI('#arcontactus-forms');
                    arCU.showErrorMessage(arCU.errorMessage);
                });
            },
            save: function(formId){
                var params = arCU.form._getFormData('arcontactus_button');
                arCU.blockUI('#arcontactus-button-modal');
                jQuery.ajax({
                    type: 'POST',
                    url: arCU.ajaxUrl,
                    dataType: 'json',
                    data: {
                        action : 'arcontactus_button_save',
                        _wpnonce: arCU.nonce,
                        ajax : true,
                        data: params,
                        lang: jQuery('#arcontactus_id_lang').val(),
                        id: jQuery('#arcontactus_button_id').val(),
                        formId: jQuery('#arcontactus_button_form_id').val(),
                    },
                    success: function(data){
                        arCU.unblockUI('#arcontactus-button-modal');
                        if (!arCU.form._processErrors('arcontactus_button', data)){
                            arCU.showSuccessMessage(arCU.successSaveMessage);
                            jQuery('#arcontactus-button-modal').uiModal('hide');
                            arCU.form.reload();
                        }
                    }
                }).fail(function(){
                    arCU.unblockUI('#arcontactus-button-modal');
                    arCU.showErrorMessage(arCU.errorMessage);
                });
            },
            add: function(formId){
                arCU.form._resetForm('arcontactus_button');
                jQuery('#arcontactus_button_form_id').val(formId);
                jQuery('#arcontactus-button-modal').uiModal({
                    closable: false
                }).uiModal('show');
                buttonTypeChanged();
            },
            reorder: function(formId) {
                var buttons = [];
                jQuery('#arcu-form-' + formId + ' .arcu-form-button-group').each(function(){
                    buttons.push(jQuery(this).attr('data-id'));
                });
                jQuery.ajax({
                    type: 'POST',
                    url: arCU.ajaxUrl,
                    dataType: 'json',
                    data: {
                        action : 'arcontactus_button_reorder',
                        _wpnonce: arCU.nonce,
                        ajax : true,
                        formId: formId,
                        buttons: buttons
                    },
                    success: function(data){
                        arCU.unblockUI('#arcontactus-forms');
                        arCU.showSuccessMessage(arCU.successSaveMessage);
                        arCU.form.reload();
                    }
                }).fail(function(){
                    arCU.unblockUI('#arcontactus-forms');
                    arCU.showErrorMessage(arCU.errorMessage);
                });
            },
            remove: function(formId, id){
                if (confirm('Remove the button?')) {
                    arCU.blockUI('#arcontactus-forms');
                    jQuery.ajax({
                        type: 'POST',
                        url: arCU.ajaxUrl,
                        dataType: 'json',
                        data: {
                            action : 'arcontactus_button_remove',
                            _wpnonce: arCU.nonce,
                            ajax : true,
                            id: id,
                            formId: formId,
                        },
                        success: function(data){
                            arCU.unblockUI('#arcontactus-forms');
                            arCU.showSuccessMessage(arCU.successSaveMessage);
                            arCU.form.reload();
                        }
                    }).fail(function(){
                        arCU.unblockUI('#arcontactus-forms');
                        arCU.showErrorMessage(arCU.errorMessage);
                    });
                }
            }
        }
    },
    switchLang: function(lang){
        jQuery('.arcu-lang-content .arcu-lang-field').removeClass('active').addClass('hidden');
        jQuery('.arcu-lang-content .arcu-lang-field[data-lang-code="' + lang + '"]').addClass('active').removeClass('hidden');
        jQuery('.arcu-lang').each(function(){
            jQuery(this).find('.menu .item').removeClass('active').removeClass('selected');
            jQuery(this).find('.menu .item[data-lang-code="' + lang + '"]').addClass('active').addClass('selected');
            jQuery(this).find('.text').html(jQuery(this).find('.menu .item[data-lang-code="' + lang + '"]').html());
        });
    },
    activate: function(){
        arCU.blockUI('#arcontactus-plugin-container');
        jQuery('#arcontactus_activation_error').text('');
        jQuery.ajax({
            type: 'GET',
            url: arCU.ajaxUrl,
            dataType: 'json',
            data: {
                action : 'arcontactus_activate',
                _wpnonce: arCU.nonce,
                ajax : true,
                code: jQuery('#arcontactus_pcode').val()
            },
            success: function(data){
                arCU.unblockUI('#arcontactus-plugin-container');
                if (data.success == 0){
                    jQuery('#arcontactus_activation_error').text(data.error);
                }else{
                    alert('Plugin activated. Thank you. Page will be reloaded after you close this modal.');
                    location.reload();
                }
            }
        }).fail(function(){
            arCU.unblockUI('#arcontactus-plugin-container');
            arCU.showErrorMessage(arCU.errorMessage);
        });
    },
    deactivate: function(){
        arCU.blockUI('#arcontactus-plugin-container');
        jQuery('#arcontactus_activation_error').text('');
        jQuery.ajax({
            type: 'GET',
            url: arCU.ajaxUrl,
            dataType: 'json',
            data: {
                action : 'arcontactus_deactivate',
                _wpnonce: arCU.nonce,
                ajax : true
            },
            success: function(data){
                arCU.unblockUI('#arcontactus-plugin-container');
                if (data.success == 0){
                    jQuery('#arcontactus_activation_error').text(data.error);
                }else{
                    alert('Plugin deactivated. Page will be reloaded after you close this modal.');
                    location.reload();
                }
            }
        }).fail(function(){
            arCU.unblockUI('#arcontactus-plugin-container');
            arCU.showErrorMessage(arCU.errorMessage);
        });
    },
    comment: {
        edit: function(id){
            jQuery.ajax({
                type: 'GET',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_edit_comment',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    id: id
                },
                success: function(data){
                    jQuery('#arcu-comment-modal').uiModal({
                        closable: false
                    }).uiModal('show');
                    jQuery('#arcu-request-item-content').html(data.header);
                    jQuery('#arcu-comment_comment').val(data.model.comment);
                    id: jQuery('#arcu-comment_id').val(data.model.id);
                }
            }).fail(function(){
                arCU.unblockUI('#arcontactus-prompt-modal');
                arCU.showErrorMessage(arCU.errorMessage);
            });
            
        },
        save: function(){
            var id = jQuery('#arcu-comment_id').val();
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_save_comment',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    comment: jQuery('#arcu-comment_comment').val(),
                    id: id
                },
                success: function(data){
                    jQuery('#arcu-comment-' + id).replaceWith(data.content);
                    jQuery('#arcu-comment-modal').uiModal('hide');
                }
            }).fail(function(){
                arCU.unblockUI('#arcontactus-prompt-modal');
                arCU.showErrorMessage(arCU.errorMessage);
            });
            
        }
    },
    prompt: {
        add: function(){
            arCU.prompt.resetForm();
            jQuery('#arcontactus-prompt-modal-title').html(arCU.addTitle);
            jQuery('#arcontactus-prompt-modal').uiModal({
                closable: false
            }).uiModal('show');
        },
        populateForm: function(data){
            jQuery.each(data, function(i){
                var fieldId = '#arcontactus_prompt_' + i;
                if (typeof data[i] == 'object') {
                    jQuery.each(data[i], function(ii){
                        var fieldId = '#arcontactus_prompt_' + i + '_' + ii;
                        jQuery(fieldId).val(data[i][ii]);
                    });
                } else {
                    jQuery(fieldId).val(data[i]);
                }
            });
        },
        edit: function(id){
            arCU.prompt.resetForm();
            jQuery('#arcontactus-prompt-modal-title').html(arCU.editTitle);
            arCU.blockUI('#arcontactus-prompt-table');
            jQuery.ajax({
                type: 'GET',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_edit_prompt_item',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    id: id
                },
                success: function(data){
                    jQuery('#arcontactus-prompt-modal').uiModal({
                        closable: false
                    }).uiModal('show');
                    arCU.prompt.populateForm(data);
                    arCU.unblockUI('#arcontactus-prompt-table');
                }
            }).fail(function(){
                arCU.unblockUI('#arcontactus-prompt-modal');
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        toggle: function(id){
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_switch_prompt_item',
                    _wpnonce: arCU.nonce,
                    id: id,
                    ajax : true
                },
                success: function(data){
                    arCU.prompt.reload();
                }
            }).fail(function(){
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        _getFormData: function(){
            var params = {};
            jQuery('#arcontactus-prompt-form [data-serializable="true"]').each(function(){
                if (jQuery(this).data('lang-field')) {
                    if (typeof params[jQuery(this).attr('name')] == 'undefined'){
                        params[jQuery(this).attr('name')] = {};
                    }
                    var lang = jQuery(this).data('lang-code');
                    params[jQuery(this).attr('name')][lang] = jQuery(this).val();
                } else {
                    params[jQuery(this).attr('name')] = jQuery(this).val();
                }
            });
            console.log(params);
            return params;
        },
        save: function(){
            var params = arCU.prompt._getFormData();
            arCU.blockUI('#arcontactus-prompt-modal');
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_save_prompt_item',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    data: params,
                    id: jQuery('#arcontactus_prompt_id').val()
                },
                success: function(data){
                    arCU.unblockUI('#arcontactus-prompt-modal');
                    if (!arCU.prompt.processErrors(data)){
                        arCU.showSuccessMessage(arCU.successSaveMessage);
                        jQuery('#arcontactus-prompt-modal').uiModal('hide');
                        arCU.prompt.reload();
                    }
                }
            }).fail(function(){
                arCU.unblockUI('#arcontactus-prompt-modal');
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        clearErrors: function(){
            jQuery('#arcontactus-prompt-form .field.has-error').removeClass('has-error');
        },
        processErrors: function(data){
            arCU.prompt.clearErrors();
            if (data.success == 0){
                jQuery.each(data.errors, function(index){
                    jQuery('#arcontactus_prompt_'+index).parents('.field').addClass('has-error');
                    jQuery('#arcontactus_prompt_'+index).parents('.field').find('.errors').html(data.errors[index].join('<br/>'));
                });
                arCU.showErrorMessage(arCU.errorMessage);
                return true;
            }
            return false;
        },
        remove: function(id){
            if (!confirm('Delete this item?')){
                return false;
            }
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_delete_prompt_item',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    id: id
                },
                success: function(data){
                    arCU.showSuccessMessage(arCU.successDeleteMessage);
                    arCU.prompt.reload(true);
                }
            }).fail(function(){
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        updateOrder: function(table, silent){
            var positions = [];
            jQuery(table).find('tbody tr').each(function(index){
                var order = index + 1;
                var id = jQuery(this).data('id');
                positions.push(id + '_' + order);
            });
            arCU.blockUI(table);
            if (arCU.prevOrder != positions){
                jQuery.ajax({
                    type: 'POST',
                    url: arCU.ajaxUrl,
                    dataType: 'json',
                    data: {
                        action : 'arcontactus_reorder_prompt_items',
                        _wpnonce: arCU.nonce,
                        ajax : true,
                        data: positions
                    },
                    success: function(data){
                        arCU.unblockUI(table);
                        arCU.prevOrder = positions;
                        if (!silent){
                            //arCU.showSuccessMessage(arCU.successOrderMessage);
                        }
                        jQuery(table).find('tbody tr').each(function(index){
                            var order = index + 1;
                            jQuery(this).find('.position').text(order);
                        });
                    }
                }).fail(function(){
                    arCU.unblockUI(table);
                    arCU.showErrorMessage(arCU.errorMessage);
                });
            }
        },
        reload: function(reorder){
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    _ajax_nonce: arCU.nonce,
                    _wpnonce: arCU.nonce,
                    action : 'arcontactus_reload_prompt_items',
                    ajax : true,
                },
                success: function(data){
                    jQuery('#arcontactus-prompt-table').replaceWith(data.content);
                    arCU.init();
                    if (reorder){
                        arCU.prompt.updateOrder('#arcontactus-prompt-table', true);
                    }
                }
            }).fail(function(){
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        resetForm: function(){
            jQuery('#arcontactus-prompt-form [data-default]').each(function(){
                var attr = jQuery(this).attr('data-default');
                if (typeof attr !== typeof undefined && attr !== false) {
                    jQuery(this).val(jQuery(this).data('default'));
                }
            });
            arCU.prompt.clearErrors();
        },
    },
    callback: {
        updateCounter: function(){
            jQuery.ajax({
                type: 'GET',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_callback_count',
                    _wpnonce: arCU.nonce,
                    location: window.location.href,
                    ajax : true
                },
                success: function(data){
                    jQuery('#arcontactus-cb-count .update-count').text(data.count);
                    jQuery('#arcontactus-email-count .update-count').text(data.emailCount);
                    if (data.count == 0){
                        jQuery('#arcontactus-cb-count').hide();
                    }else{
                        jQuery('#arcontactus-cb-count').css({
                            display: 'inline'
                        });
                    }
                    if (data.emailCount == 0){
                        jQuery('#arcontactus-email-count').hide();
                    }else{
                        jQuery('#arcontactus-email-count').css({
                            display: 'inline'
                        });
                    }
                }
            }).fail(function(){
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        export: function(type){
            arCU.blockUI('#arcontactus-requests-form');
            arCU.blockUI('#arcontactus-email-requests-form');
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_export_callback',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    type: type,
                    location: window.location.href
                },
                success: function(data){
                    arCU.unblockUI('#arcontactus-requests-form');
                    arCU.unblockUI('#arcontactus-email-requests-form');
                    window.location = data.file;
                }
            }).fail(function(){
                arCU.unblockUI('#arcontactus-requests-form');
                arCU.unblockUI('#arcontactus-email-requests-form');
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        reload: function(){
            arCU.blockUI('#arcontactus-requests-form');
            arCU.blockUI('#arcontactus-email-requests-form');
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_reload_callback',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    location: window.location.href
                },
                success: function(data){
                    arCU.unblockUI('#arcontactus-requests-form');
                    arCU.unblockUI('#arcontactus-email-requests-form');
                    jQuery('#arcontactus-requests-form').replaceWith(data.content);
                    jQuery('#arcontactus-email-requests-form').replaceWith(data.emailContent);
                }
            }).fail(function(){
                arCU.unblockUI('#arcontactus-requests-form');
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        toggle: function(id, status){
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_switch_callback',
                    _wpnonce: arCU.nonce,
                    id: id,
                    status: status,
                    location: window.location.href,
                    ajax : true
                },
                success: function(data){
                    arCU.callback.reload();
                    arCU.callback.updateCounter();
                }
            }).fail(function(){
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        remove: function(id){
            if (!confirm('Delete this item?')){
                return false;
            }
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                location: window.location.href,
                dataType: 'json',
                data: {
                    action : 'arcontactus_delete_callbacks',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    id: id
                },
                success: function(data){
                    arCU.showSuccessMessage(arCU.successDeleteMessage);
                    arCU.callback.reload();
                }
            }).fail(function(){
                arCU.showErrorMessage(arCU.errorMessage);
            });
        }
    },
    refreshImage: function(the_id, previewEl){
        var data = {
            action: 'arcontactus_preview',
            id: the_id,
            _wpnonce: arCU.nonce,
            ajax : true
        };

        jQuery.ajax({
            type: 'GET',
            url: arCU.ajaxUrl,
            dataType: 'json',
            data: data,
            success: function(data){
                jQuery('#' + previewEl).html(data.data.image);
            }
        }).fail(function(){
            
        });
    },
    init: function(){
        arCU.callback.updateCounter();
        setInterval(function(){
            arCU.callback.updateCounter();
        }, 5000);
        jQuery('#acrontactus-menu a').on('click', function(){
            var target = jQuery(this).data('target');
            if (!target){
                return true;
            }
            jQuery('#acrontactus-menu .active').removeClass('active');
            jQuery(this).addClass('active');
            jQuery('.arconfig-panel').addClass('hidden');
            jQuery(target).removeClass('hidden');
        });
        jQuery('.ui.checkbox').checkbox();
        jQuery('#arcontactus-tabs').addClass('active');
        
        switchButtonIconType();
        switchMobileButtonIconType();
        switchMenuStyle();
        arContactUsIconChanged();
        jQuery('.ui.toggle.checkbox').on('click', function(){
            arContactUsSwitchFields();
        });
        jQuery('#ARCUB_BUTTON_ICON_TYPE').change(function(){
            switchButtonIconType();
        });
        jQuery('#ARCUMB_BUTTON_ICON_TYPE').change(function(){
            switchMobileButtonIconType();
        });
        
        jQuery('#arcontactus_type').change(function(){
            arcontactusChangeType();
        });
        jQuery('.field_menu_popup_style select').change(function(){
            switchMenuStyle();
        });
        jQuery('#arcontactus_brand_color').change(function(){
            jQuery('#arcontactus_color').val(jQuery(this).val());
            document.getElementById('arcontactus_color').jscolor.importColor();
        });
        jQuery('#ARCUMB_POSITION').change(function(){
            arcontactusMobilePositionChange();
        });
        
        jQuery('#ARCUM_MENU_POPUP_STYLE').change(function(){
            arcontactusChangeMenuStyle();
        });
        
        jQuery('#ARCUMM_MENU_POPUP_STYLE').change(function(){
            arcontactusChangeMobileMenuStyle();
        });
        
        arcontactusMobilePositionChange();
        arcontactusChangeType();
        arcontactusChangeMenuStyle();
        arcontactusChangeMobileMenuStyle();
        jQuery(".arcu-form-fields").sortable({	
            axis: "y",
            handle: ".arcu-move-handle",
            update: function(event, ui){
                var id = jQuery(ui.item).parents('.arcu-form').attr('data-id');
                arCU.form.field.reorder(id);
            }
        });
        jQuery(".arcu-form-buttons").sortable({	
            axis: "y",
            handle: ".arcu-move-handle",
            update: function(event, ui){
                var id = jQuery(ui.item).parents('.arcu-form').attr('data-id');
                arCU.form.button.reorder(id);
            }
        });
        jQuery("#arcontactus-prompt-table tbody").sortable({	
            axis: "y",
            handle: ".drag-handle",
            update: function(event, ui){
                arCU.prompt.updateOrder(jQuery("#arcontactus-prompt-table"), false);
            }
        });
        jQuery("#arcontactus-menu-items tbody").sortable({	
            axis: "y",
            handle: ".drag-handle",
            update: function(event, ui){
                arCU.updateOrder(jQuery("#arcontactus-menu-items"), false);
            }
        });
        jQuery('#arcontactus-modal').on('shown.bs.modal', function () {
            jQuery('#fa5-container').scrollTo(0);
            if (jQuery('#fa5 ul li.active').length){
                jQuery('#fa5-container').scrollTo(jQuery('#fa5 ul li.active').position().top - jQuery('#fa5 ul li.active').height() - 30);
            }
        });
    },
    add: function(){
        arCU.resetForm();
        jQuery('#arcontactus-modal-title').html(arCU.addTitle);
        jQuery('#arcontactus-modal').uiModal({
            closable: false
        }).uiModal('show');
    },
    populateForm: function(data){
        jQuery.each(data, function(i){
            var fieldId = '#arcontactus_' + i;
            if (i == 'icon'){
                jQuery('#arcontactus-icon-dropdown').dropdown('set selected', data[i]);
            }else if(i == 'color'){
                document.getElementById('arcontactus_color').jscolor.fromString(data[i]);
            }else{
                if (typeof data[i] == 'object') {
                    jQuery.each(data[i], function(ii){
                        var fieldId = '#arcontactus_' + i + '_' + ii;
                        if (jQuery(fieldId).attr('type') == 'checkbox'){
                            if (data[i][ii] == "1" || data[i][ii] == 1){
                                jQuery(fieldId).prop('checked', true);
                            } else {
                                jQuery(fieldId).prop('checked', false);
                            }
                        } else {
                            jQuery(fieldId).val(data[i][ii]);
                        }
                    });
                } else {
                    jQuery(fieldId).val(data[i]);
                }
            }
        });
        
        jQuery('#arcontactus_color').trigger('keyup');
        arcontactusChangeType();
    },
    edit: function(id){
        arCU.resetForm();
        jQuery('#arcontactus-modal-title').html(arCU.editTitle);
        arCU.blockUI('#arcontactus-menu-items');
        jQuery.ajax({
            type: 'GET',
            url: arCU.ajaxUrl,
            dataType: 'json',
            data: {
                action : 'arcontactus_edit_menu_item',
                _wpnonce: arCU.nonce,
                ajax : true,
                id: id
            },
            success: function(data){
                jQuery('#arcontactus-modal').uiModal({
                    closable: false
                }).uiModal('show');
                arCU.populateForm(data);
                arContactUsIconChanged();
                if (data.params && data.params.image_preview){
                    jQuery('#arcontactus_params_icon_img-preview').html(data.params.image_preview);
                } else {
                    jQuery('#arcontactus_params_icon_img-preview').html('');
                }
                arcontactusChangeVisibilityType();
                scheduleChanged();
                arCU.unblockUI('#arcontactus-menu-items');
                jQuery('#arcu-shortcode-group').removeClass('hidden');
            }
        }).fail(function(){
            arCU.unblockUI('#arcontactus-modal');
            arCU.showErrorMessage(arCU.errorMessage);
        });
    },
    toggle: function(id){
        jQuery.ajax({
            type: 'POST',
            url: arCU.ajaxUrl,
            dataType: 'json',
            data: {
                action : 'arcontactus_switch_menu_item',
                _wpnonce: arCU.nonce,
                id: id,
                ajax : true
            },
            success: function(data){
                arCU.reload();
            }
        }).fail(function(){
            arCU.showErrorMessage(arCU.errorMessage);
        });
    },
    _getFormData: function(){
        var params = {};
        jQuery('#arcontactus-form [data-serializable="true"]').each(function(){
            if (jQuery(this).data('lang-field')) {
                if (typeof params[jQuery(this).attr('name')] == 'undefined'){
                    params[jQuery(this).attr('name')] = {};
                }
                var lang = jQuery(this).data('lang-code');
                params[jQuery(this).attr('name')][lang] = jQuery(this).val();
            } else {
                if (jQuery(this).attr('type') == 'checkbox'){
                    params[jQuery(this).attr('name')] = jQuery(this).is(':checked')? 1 : 0;
                } else {
                    params[jQuery(this).attr('name')] = jQuery(this).val();
                }
            }
        });
        
        return params;
    },
    save: function(){
        var params = arCU._getFormData();
        arCU.blockUI('#arcontactus-modal');
        jQuery.ajax({
            type: 'POST',
            url: arCU.ajaxUrl,
            dataType: 'json',
            data: {
                action : 'arcontactus_save_menu_item',
                _wpnonce: arCU.nonce,
                ajax : true,
                data: params,
                lang: jQuery('#arcontactus_id_lang').val(),
                id: jQuery('#arcontactus_id').val()
            },
            success: function(data){
                arCU.unblockUI('#arcontactus-modal');
                if (!arCU.processErrors(data)){
                    arCU.showSuccessMessage(arCU.successSaveMessage);
                    jQuery('#arcontactus-modal').uiModal('hide');
                    arCU.reload();
                }
            }
        }).fail(function(){
            arCU.unblockUI('#arcontactus-modal');
            arCU.showErrorMessage(arCU.errorMessage);
        });
    },
    clearErrors: function(data){
        jQuery('#arcontactus-form .field.has-error').removeClass('has-error');
    },
    processErrors: function(data){
        arCU.clearErrors();
        if (data.success == 0){
            jQuery.each(data.errors, function(index){
                jQuery('#arcontactus_'+index).parents('.field').addClass('has-error');
                jQuery('#arcontactus_'+index).parents('.field').find('.errors').html(data.errors[index].join('<br/>'));
            });
            arCU.showErrorMessage(arCU.errorMessage);
            return true;
        }
        return false;
    },
    remove: function(id){
        if (!confirm('Delete this item?')){
            return false;
        }
        jQuery.ajax({
            type: 'POST',
            url: arCU.ajaxUrl,
            dataType: 'json',
            data: {
                action : 'arcontactus_delete_menu_item',
                _wpnonce: arCU.nonce,
                ajax : true,
                id: id
            },
            success: function(data){
                arCU.showSuccessMessage(arCU.successDeleteMessage);
                arCU.reload(true);
            }
        }).fail(function(){
            arCU.showErrorMessage(arCU.errorMessage);
        });
    },
    updateOrder: function(table, silent){
        var positions = [];
        jQuery(table).find('tbody tr').each(function(index){
            var order = index + 1;
            var id = jQuery(this).data('id');
            positions.push(id + '_' + order);
        });
        arCU.blockUI(table);
        if (arCU.prevOrder != positions){
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_reorder_menu_items',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    data: positions
                },
                success: function(data){
                    arCU.unblockUI(table);
                    arCU.prevOrder = positions;
                    if (!silent){
                        //arCU.showSuccessMessage(arCU.successOrderMessage);
                    }
                    jQuery(table).find('tbody tr').each(function(index){
                        var order = index + 1;
                        jQuery(this).find('.position').text(order);
                    });
                }
            }).fail(function(){
                arCU.unblockUI(table);
                arCU.showErrorMessage(arCU.errorMessage);
            });
        }
    },
    reload: function(reorder){
        arCU.blockUI('#arcontactus-menu-items');
        jQuery.ajax({
            type: 'POST',
            url: arCU.ajaxUrl,
            dataType: 'json',
            data: {
                _ajax_nonce: arCU.nonce,
                _wpnonce: arCU.nonce,
                action : 'arcontactus_reload_menu_items',
                ajax : true,
            },
            success: function(data){
                arCU.unblockUI('#arcontactus-menu-items');
                jQuery('#arcontactus-menu-items').replaceWith(data.content);
                arCU.init();
                if (reorder){
                    arCU.updateOrder('#arcontactus-menu-items', true);
                }
            }
        }).fail(function(){
            arCU.showErrorMessage(arCU.errorMessage);
        });
    },
    resetForm: function(){
        jQuery('#arcontactus-form [data-default]').each(function(){
            var attr = jQuery(this).attr('data-default');
            if (jQuery(this).attr('type') == 'checkbox') {
                if (typeof attr !== typeof undefined && attr !== false) {
                    if (attr == '1') {
                        jQuery(this).prop('checked', true);
                    } else {
                        jQuery(this).prop('checked', false);
                    }
                }
            } else {
                if (typeof attr !== typeof undefined && attr !== false) {
                    jQuery(this).val(jQuery(this).data('default'));
                }
            }
        });
        jQuery('#arcu-shortcode-group').addClass('hidden');
        jQuery('#arcontactus-icon-dropdown').dropdown('set selected', '');
        document.getElementById('arcontactus_color').jscolor.fromString('000000');
        jQuery('#arcontactus_params_icon_img-preview').html('');
        arCU.clearErrors();
        arcontactusChangeType();
        arContactUsIconChanged();
        scheduleChanged();
    },
    blockUI: function(selector){
        jQuery(selector).addClass('ar-blocked');
        jQuery(selector).find('.ar-loading').remove();
        jQuery(selector).append('<div class="ar-loading"><div class="ar-loading-inner">Loading...</div></div>');
    },
    unblockUI: function(selector){
        jQuery(selector).find('.ar-loading').remove();
        jQuery(selector).removeClass('ar-blocked');
    },
    showSuccessMessage: function(mesage){
        
    },
    showErrorMessage: function(message){
        
    }
};

function arcontactusChangeVisibilityType(){
    if (jQuery('#arcontactus_params_always').is(':checked')) {
        jQuery('#arcu-item-schedule').addClass('hidden');
    } else {
        jQuery('#arcu-item-schedule').removeClass('hidden');
    }
}

function arcontactusChangeMenuStyle(){
    if (jQuery('#ARCUM_MENU_POPUP_STYLE').val() == 'no-background'){
        jQuery('#ArContactUsConfigMenu .field_item_border_style, #ArContactUsConfigMenu .field_item_border_color, #ArContactUsConfigMenu .field_menu_header_on, #ArContactUsConfigMenu .field_menu_header, #ArContactUsConfigMenu .field_header_close, #ArContactUsConfigMenu .field_header_close_bg, #ArContactUsConfigMenu .field_header_close_color').addClass('hidden');
    } else {
        jQuery('#ArContactUsConfigMenu .field_item_border_style, #ArContactUsConfigMenu .field_item_border_color, #ArContactUsConfigMenu .field_menu_header_on, #ArContactUsConfigMenu .field_menu_header, #ArContactUsConfigMenu .field_header_close, #ArContactUsConfigMenu .field_header_close_bg, #ArContactUsConfigMenu .field_header_close_color').removeClass('hidden');
    }
    arContactUsSwitchFields();
}

function arcontactusChangeMobileMenuStyle(){
    if (jQuery('#ARCUMM_MENU_POPUP_STYLE').val() == 'no-background'){
        jQuery('#ArContactUsConfigMobileMenu .field_item_border_style, #ArContactUsConfigMobileMenu .field_item_border_color, #ArContactUsConfigMobileMenu .field_menu_header_on, #ArContactUsConfigMobileMenu .field_menu_header, #ArContactUsConfigMobileMenu .field_header_close, #ArContactUsConfigMobileMenu .field_header_close_bg, #ArContactUsConfigMobileMenu .field_header_close_color').addClass('hidden');
    } else {
        jQuery('#ArContactUsConfigMobileMenu .field_item_border_style, #ArContactUsConfigMobileMenu .field_item_border_color, #ArContactUsConfigMobileMenu .field_menu_header_on, #ArContactUsConfigMobileMenu .field_menu_header, #ArContactUsConfigMobileMenu .field_header_close, #ArContactUsConfigMobileMenu .field_header_close_bg, #ArContactUsConfigMobileMenu .field_header_close_color').removeClass('hidden');
    }
    arContactUsSwitchFields();
}

function arcontactusMobilePositionChange(){
    if (jQuery('#ARCUMB_POSITION').val() == 'storefront') {
        jQuery('#ArContactUsConfigMobileButton .field_x_offset, #ArContactUsConfigMobileButton .field_y_offset, #ArContactUsConfigMobileButton .field_pulsate_speed, #ArContactUsConfigMobileButton .field_icon_animation_pause, #ArContactUsConfigMobileButton .field_button_size, #ArContactUsConfigMobileButton .field_icon_speed, #ArContactUsConfigMobileButton .field_text, #ArContactUsConfigMobileButton .field_drag').addClass('hidden');
        jQuery('#ArContactUsConfigMobileButton .field_storefront_pos').removeClass('hidden');
    } else {
        jQuery('#ArContactUsConfigMobileButton .field_x_offset, #ArContactUsConfigMobileButton .field_y_offset, #ArContactUsConfigMobileButton .field_pulsate_speed, #ArContactUsConfigMobileButton .field_icon_animation_pause, #ArContactUsConfigMobileButton .field_button_size, #ArContactUsConfigMobileButton .field_icon_speed, #ArContactUsConfigMobileButton .field_text, #ArContactUsConfigMobileButton .field_drag').removeClass('hidden');
        jQuery('#ArContactUsConfigMobileButton .field_storefront_pos').addClass('hidden');
    }
}

function arContactUsIconChanged(){
    var iconType = jQuery('#arcontactus_params_icon_type').val();
    switch(iconType){
        case "0":
            jQuery('#arcu-fa5').addClass('hidden');
            jQuery('#arcu-item-image').addClass('hidden');
            jQuery('#arcu-item-svg').removeClass('hidden');
            break;
        case "1":
            jQuery('#arcu-fa5').removeClass('hidden');
            jQuery('#arcu-item-image').addClass('hidden');
            jQuery('#arcu-item-svg').addClass('hidden');
            break;
        case "2":
            jQuery('#arcu-fa5').addClass('hidden');
            jQuery('#arcu-item-image').removeClass('hidden');
            jQuery('#arcu-item-svg').addClass('hidden');
            break;
    }
}

function arcontactusChangeType(){
    var val = jQuery('#arcontactus_type').val();
    jQuery('#arcu-js-group').removeClass('hidden');
    switch(val){
        case "0": // link
            jQuery('.arcu-link-group').removeClass('hidden');
            //jQuery('#arcu-js-group').addClass('hidden');
            jQuery('#arcu-integration-group').addClass('hidden');
            jQuery('#arcu-content-group').addClass('hidden');
            jQuery('#arcu-form-group').addClass('hidden');
            break;
        case "1": // integration
            jQuery('.arcu-link-group').addClass('hidden');
            //jQuery('#arcu-js-group').addClass('hidden');
            jQuery('#arcu-integration-group').removeClass('hidden');
            jQuery('#arcu-content-group').addClass('hidden');
            jQuery('#arcu-form-group').addClass('hidden');
            break;
        case "2": // js
            jQuery('.arcu-link-group').addClass('hidden');
            //jQuery('#arcu-js-group').removeClass('hidden');
            jQuery('#arcu-integration-group').addClass('hidden');
            jQuery('#arcu-content-group').addClass('hidden');
            jQuery('#arcu-form-group').addClass('hidden');
            break;
        case "3": // form
            jQuery('.arcu-link-group').addClass('hidden');
            //jQuery('#arcu-js-group').addClass('hidden');
            jQuery('#arcu-integration-group').addClass('hidden');
            jQuery('#arcu-content-group').addClass('hidden');
            jQuery('#arcu-form-group').removeClass('hidden');
            break;
        case "4": // content
            jQuery('.arcu-link-group').addClass('hidden');
            jQuery('#arcu-js-group').addClass('hidden');
            jQuery('#arcu-integration-group').addClass('hidden');
            jQuery('#arcu-content-group').removeClass('hidden');
            jQuery('#arcu-form-group').addClass('hidden');
            break;
    }
}

function arContactUsSwitchFields(){
    if (jQuery('.field_email #ARCUP_EMAIL').is(':checked')){
        jQuery('.field_email_list').removeClass('hidden');
    }else{
        jQuery('.field_email_list').addClass('hidden');
    }
    if (jQuery('.field_recaptcha #ARCUP_RECAPTCHA').is(':checked')){
        jQuery('.field_key, .field_secret, .field_hide_recaptcha, .field_recaptcha_init, .field_recaptcha_treshold, .field_recaptcha_error').removeClass('hidden');
    }else{
        jQuery('.field_key, .field_secret, .field_hide_recaptcha, .field_recaptcha_init, .field_recaptcha_treshold, .field_recaptcha_error').addClass('hidden');
    }
    if (jQuery('.field_onesignal #ARCUP_ONESIGNAL').is(':checked')){
        jQuery('.field_onesignal_app_id, .field_onesignal_api_key, .field_onesignal_title, .field_onesignal_message').removeClass('hidden');
    }else{
        jQuery('.field_onesignal_app_id, .field_onesignal_api_key, .field_onesignal_title, .field_onesignal_message').addClass('hidden');
    }
    if (jQuery('.field_perfex #ARCUP_PERFEX').is(':checked')){
        jQuery('.field_perfex_alert, .field_perfex_url, .field_perfex_token').removeClass('hidden');
    }else{
        jQuery('.field_perfex_alert, .field_perfex_url, .field_perfex_token').addClass('hidden');
    }
    
    if (jQuery('.field_loop #ARCUPR_LOOP').is(':checked')){
        jQuery('.field_close_last').addClass('hidden');
    }else{
        jQuery('.field_close_last').removeClass('hidden');
    }

    if (jQuery('.field_twilio #ARCUP_TWILIO').is(':checked')){
        jQuery('.field_twilio_api_key, .field_twilio_auth_token, .field_twilio_phone, .field_twilio_tophone, .field_twilio_message').removeClass('hidden');
    }else{
        jQuery('.field_twilio_api_key, .field_twilio_auth_token, .field_twilio_phone, .field_twilio_tophone, .field_twilio_message').addClass('hidden');
    }

    if (jQuery('#ARCUL_TAWK_TO_ON').is(':checked')){
        jQuery('.field_tawk_to_site_id, .field_tawk_to_userinfo, .field_tawk_to_widget').removeClass('hidden');
    }else{
        jQuery('.field_tawk_to_site_id, .field_tawk_to_userinfo, .field_tawk_to_widget').addClass('hidden');
    }

    if (jQuery('#ARCUL_CRISP_ON').is(':checked')){
        jQuery('.field_crisp_site_id').removeClass('hidden');
    }else{
        jQuery('.field_crisp_site_id').addClass('hidden');
    }

    if (jQuery('#ARCUL_INTERCOM_ON').is(':checked')){
        jQuery('.field_intercom_app_id').removeClass('hidden');
    }else{
        jQuery('.field_intercom_app_id').addClass('hidden');
    }

    if (jQuery('#ARCUL_FB_ON').is(':checked')){
        jQuery('.field_fb_page_id, .field_fb_init, .field_fb_lang, .field_fb_color').removeClass('hidden');
    }else{
        jQuery('.field_fb_page_id, .field_fb_init, .field_fb_lang, .field_fb_color').addClass('hidden');
    }

    if (jQuery('#ARCUL_VK_ON').is(':checked')){
        jQuery('.field_vk_page_id').removeClass('hidden');
    }else{
        jQuery('.field_vk_page_id').addClass('hidden');
    }

    if (jQuery('#ARCUL_ZOPIM_ON').is(':checked')){
        jQuery('.field_zopim_id, .field_zopim_userinfo').removeClass('hidden');
    }else{
        jQuery('.field_zopim_id, .field_zopim_userinfo').addClass('hidden');
    }

    if (jQuery('#ARCUL_SKYPE_ON').is(':checked')){
        jQuery('.field_skype_type, .field_skype_id, .field_skype_message_color').removeClass('hidden');
    }else{
        jQuery('.field_skype_type, .field_skype_id, .field_skype_message_color').addClass('hidden');
    }

    if (jQuery('#ARCUL_ZALO_ON').is(':checked')){
        jQuery('.field_zalo_id, .field_zalo_welcome, .field_zalo_width, .field_zalo_height').removeClass('hidden');
    }else{
        jQuery('.field_zalo_id, .field_zalo_welcome, .field_zalo_width, .field_zalo_height').addClass('hidden');
    }

    if (jQuery('#ARCUL_ZALO_ON').is(':checked')){
        jQuery('.field_zalo_id, .field_zalo_userinfo, .field_zalo_welcome, .field_zalo_width, .field_zalo_height').removeClass('hidden');
    }else{
        jQuery('.field_zalo_id, .field_zalo_userinfo, .field_zalo_welcome, .field_zalo_width, .field_zalo_height').addClass('hidden');
    }

    if (jQuery('#ARCUL_LHC_ON').is(':checked')){
        jQuery('.field_lhc_uri, .field_lhc_width, .field_lhc_height, .field_lhc_popup_width, .field_lhc_popup_height').removeClass('hidden');
    }else{
        jQuery('.field_lhc_uri, .field_lhc_width, .field_lhc_height, .field_lhc_popup_width, .field_lhc_popup_height').addClass('hidden');
    }

    if (jQuery('#ARCUL_SS_ON').is(':checked')){
        jQuery('.field_ss_key, .field_ss_userinfo').removeClass('hidden');
    }else{
        jQuery('.field_ss_key, .field_ss_userinfo').addClass('hidden');
    }

    if (jQuery('#ARCUL_LC_ON').is(':checked')){
        jQuery('.field_lc_key, .field_lc_userinfo').removeClass('hidden');
    }else{
        jQuery('.field_lc_key, .field_lc_userinfo').addClass('hidden');
    }

    if (jQuery('#ARCUL_LCP_ON').is(':checked')){
        jQuery('.field_lcp_uri').removeClass('hidden');
    }else{
        jQuery('.field_lcp_uri').addClass('hidden');
    }

    if (jQuery('#ARCUL_LZ_ON').is(':checked')){
        jQuery('.field_lz_id').removeClass('hidden');
    }else{
        jQuery('.field_lz_id').addClass('hidden');
    }

    if (jQuery('#ARCUL_TIDIO_ON').is(':checked')){
        jQuery('.field_tidio_key, .field_tidio_userinfo').removeClass('hidden');
    }else{
        jQuery('.field_tidio_key, .field_tidio_userinfo').addClass('hidden');
    }

    if (jQuery('#ARCUL_JIVOSITE_ON').is(':checked')){
        jQuery('.field_jivosite_id, .field_jivosite_userinfo').removeClass('hidden');
    }else{
        jQuery('.field_jivosite_id, .field_jivosite_userinfo').addClass('hidden');
    }

    if (jQuery('#ARCUL_ZOHO_ON').is(':checked')){
        jQuery('.field_zoho_id, .field_zoho_host').removeClass('hidden');
    }else{
        jQuery('.field_zoho_id, .field_zoho_host').addClass('hidden');
    }

    if (jQuery('#ARCUL_FC_ON').is(':checked')){
        jQuery('.field_fc_token, .field_fc_host, .field_fc_userinfo').removeClass('hidden');
    }else{
        jQuery('.field_fc_token, .field_fc_host, .field_fc_userinfo').addClass('hidden');
    }

    if (jQuery('#ARCUL_PHPLIVE_ON').is(':checked')){
        jQuery('.field_phplive_src, .field_phplive_userinfo').removeClass('hidden');
    }else{
        jQuery('.field_phplive_src, .field_phplive_userinfo').addClass('hidden');
    }

    if (jQuery('#ARCUL_PALDESK_ON').is(':checked')){
        jQuery('.field_paldesk_key, .field_paldesk_userinfo').removeClass('hidden');
    }else{
        jQuery('.field_paldesk_key, .field_paldesk_userinfo').addClass('hidden');
    }

    if (jQuery('#ARCUP_PHONE_MASK_ON').is(':checked')){
        jQuery('.field_maskedinput, .field_phone_mask').removeClass('hidden');
    }else{
        jQuery('.field_maskedinput, .field_phone_mask').addClass('hidden');
    }

    if (jQuery('#ARCUP_GDPR').is(':checked')){
        jQuery('.field_gdpr_title').removeClass('hidden');
    }else{
        jQuery('.field_gdpr_title').addClass('hidden');
    }

    if (jQuery('#ARCUP_NAME').is(':checked')){
        jQuery('.field_name_required, .field_name_title, .field_name_placeholder, .field_name_validation').removeClass('hidden');
        switchValidationFields();
    }else{
        jQuery('.field_name_required, .field_name_title, .field_name_placeholder, .field_name_validation, .field_name_max_len, .field_name_filter_laters').addClass('hidden');
    }


    if (jQuery('#ARCUP_EMAIL_FIELD').is(':checked')){
        jQuery('.field_email_required, .field_email_title, .field_email_placeholder').removeClass('hidden');
    }else{
        jQuery('.field_email_required, .field_email_title, .field_email_placeholder').addClass('hidden');
    }

    if (jQuery('#ARCUP_TG').is(':checked')){
        jQuery('.field_tg_token, .field_tg_chat_id, .field_tg_text').removeClass('hidden');
    }else{
        jQuery('.field_tg_token, .field_tg_chat_id, .field_tg_text').addClass('hidden');
    }

    if (jQuery('#ARCUM_MENU_HEADER_ON').is(':checked') && jQuery('#ARCUM_MENU_POPUP_STYLE').val() != 'no-background'){
        jQuery('#ArContactUsConfigMenu .field_menu_header, #ArContactUsConfigMenu .field_menu_subheader, #ArContactUsConfigMenu .field_header_close, #ArContactUsConfigMenu .field_header_close_bg, #ArContactUsConfigMenu .field_header_close_color').removeClass('hidden');
        jQuery('#ArContactUsConfigMenu .field_menu_header_layout, #ArContactUsConfigMenu .field_menu_header_icon_type, #ArContactUsConfigMenu .field_menu_header_icon_svg, #ArContactUsConfigMenu .field_menu_header_icon_img').removeClass('hidden');
        desktopHeaderLayoutChanged();
        if (jQuery('#ARCUM_MENU_HEADER_LAYOUT').val() != 'noicon') {
            desktopHeaderIconTypeChanged();
        }
    }else{
        jQuery('#ArContactUsConfigMenu .field_menu_header, #ArContactUsConfigMenu .field_menu_subheader, #ArContactUsConfigMenu .field_header_close, #ArContactUsConfigMenu .field_header_close_bg, #ArContactUsConfigMenu .field_header_close_color').addClass('hidden');
        jQuery('#ArContactUsConfigMenu .field_menu_header_layout, #ArContactUsConfigMenu .field_menu_header_icon_type, #ArContactUsConfigMenu .field_menu_header_icon_svg, #ArContactUsConfigMenu .field_menu_header_icon_img').addClass('hidden');
    }
    
    if (jQuery('#ARCUMM_MENU_HEADER_ON').is(':checked') && jQuery('#ARCUMM_MENU_POPUP_STYLE').val() != 'no-background'){
        jQuery('#ArContactUsConfigMobileMenu .field_menu_header, #ArContactUsConfigMobileMenu .field_menu_subheader, #ArContactUsConfigMobileMenu .field_header_close, #ArContactUsConfigMobileMenu .field_header_close_bg, #ArContactUsConfigMenu .field_header_close_color').removeClass('hidden');
        jQuery('#ArContactUsConfigMobileMenu .field_menu_header_layout, #ArContactUsConfigMobileMenu .field_menu_header_icon_type, #ArContactUsConfigMobileMenu .field_menu_header_icon_svg, #ArContactUsConfigMobileMenu .field_menu_header_icon_img').removeClass('hidden');
        mobileHeaderLayoutChanged();
        if (jQuery('#ARCUMM_MENU_HEADER_LAYOUT').val() != 'noicon') {
            mobileHeaderIconTypeChanged();
        }
    }else{
        jQuery('#ArContactUsConfigMobileMenu .field_menu_header, #ArContactUsConfigMobileMenu .field_menu_subheader, #ArContactUsConfigMobileMenu .field_header_close, #ArContactUsConfigMobileMenu .field_header_close_bg, #ArContactUsConfigMenu .field_header_close_color').addClass('hidden');
        jQuery('#ArContactUsConfigMobileMenu .field_menu_header_layout, #ArContactUsConfigMobileMenu .field_menu_header_icon_type, #ArContactUsConfigMobileMenu .field_menu_header_icon_svg, #ArContactUsConfigMobileMenu .field_menu_header_icon_img').addClass('hidden');
    }
    
    if (jQuery('#ARCUMM_MENU_HEADER_ON').is(':checked')){
        jQuery('#ArContactUsConfigMobileMenu .field_menu_header,#ArContactUsConfigMobileMenu .field_header_close, #ArContactUsConfigMobileMenu .field_header_close_bg, #ArContactUsConfigMobileMenu .field_header_close_color').removeClass('hidden');
    }else{
        jQuery('#ArContactUsConfigMobileMenu .field_menu_header,#ArContactUsConfigMobileMenu .field_header_close, #ArContactUsConfigMobileMenu .field_header_close_bg, #ArContactUsConfigMobileMenu .field_header_close_color').addClass('hidden');
    }

    if (jQuery('#ARCUG_SANDBOX').is(':checked')){
        jQuery('.field_allowed_ips').removeClass('hidden');
    }else{
        jQuery('.field_allowed_ips').addClass('hidden');
    }
}

function desktopHeaderLayoutChanged() {
    console.log('desktopHeaderLayoutChanged');
    switch(jQuery('#ARCUM_MENU_HEADER_LAYOUT').val()){
        case 'noicon':
            jQuery('#ArContactUsConfigMenu .field_menu_header_icon_type, #ArContactUsConfigMenu .field_menu_header_icon_svg, #ArContactUsConfigMenu .field_menu_header_icon_img').addClass('hidden');
            break;
        default:
            jQuery('#ArContactUsConfigMenu .field_menu_header_icon_type, #ArContactUsConfigMenu .field_menu_header_icon_svg, #ArContactUsConfigMenu .field_menu_header_icon_img').removeClass('hidden');
            desktopHeaderIconTypeChanged();
    }
}

function desktopHeaderIconTypeChanged() {
    console.log('desktopHeaderIconTypeChanged');
    switch(jQuery('#ARCUM_MENU_HEADER_ICON_TYPE').val()){
        case 'svg':
            jQuery('#ArContactUsConfigMenu .field_menu_header_icon_svg').removeClass('hidden');
            jQuery('#ArContactUsConfigMenu .field_menu_header_icon_img').addClass('hidden');
            break;
        case 'img':
            jQuery('#ArContactUsConfigMenu .field_menu_header_icon_svg').addClass('hidden');
            jQuery('#ArContactUsConfigMenu .field_menu_header_icon_img').removeClass('hidden');
            break;
    }
}

function mobileHeaderLayoutChanged() {
    switch(jQuery('#ARCUMM_MENU_HEADER_LAYOUT').val()){
        case 'noicon':
            jQuery('#ArContactUsConfigMobileMenu .field_menu_header_icon_type, #ArContactUsConfigMobileMenu .field_menu_header_icon_svg, #ArContactUsConfigMobileMenu .field_menu_header_icon_img').addClass('hidden');
            break;
        default:
            jQuery('#ArContactUsConfigMobileMenu .field_menu_header_icon_type, #ArContactUsConfigMobileMenu .field_menu_header_icon_svg, #ArContactUsConfigMobileMenu .field_menu_header_icon_img').removeClass('hidden');
            mobileHeaderIconTypeChanged();
    }
}

function mobileHeaderIconTypeChanged() {
    switch(jQuery('#ARCUMM_MENU_HEADER_ICON_TYPE').val()){
        case 'svg':
            jQuery('#ArContactUsConfigMobileMenu .field_menu_header_icon_svg').removeClass('hidden');
            jQuery('#ArContactUsConfigMobileMenu .field_menu_header_icon_img').addClass('hidden');
            break;
        case 'img':
            jQuery('#ArContactUsConfigMobileMenu .field_menu_header_icon_svg').addClass('hidden');
            jQuery('#ArContactUsConfigMobileMenu .field_menu_header_icon_img').removeClass('hidden');
            break;
    }
}

function switchButtonIconType(){
    if (jQuery('#ARCUB_BUTTON_ICON_TYPE').val() == 'built-in') {
        jQuery('#ArContactUsConfigButton .field_button_icon').removeClass('hidden');
        jQuery('#ArContactUsConfigButton .field_button_icon_img').addClass('hidden');
    } else {
        jQuery('#ArContactUsConfigButton .field_button_icon_img').removeClass('hidden');
        jQuery('#ArContactUsConfigButton .field_button_icon').addClass('hidden');
    }
}

function switchMobileButtonIconType(){
    if (jQuery('#ARCUMB_BUTTON_ICON_TYPE').val() == 'built-in') {
        jQuery('#ArContactUsConfigMobileButton .field_button_icon').removeClass('hidden');
        jQuery('#ArContactUsConfigMobileButton .field_button_icon_img').addClass('hidden');
    } else {
        jQuery('#ArContactUsConfigMobileButton .field_button_icon_img').removeClass('hidden');
        jQuery('#ArContactUsConfigMobileButton .field_button_icon').addClass('hidden');
    }
}

function switchValidationFields(){
    if (jQuery('#ARCUP_NAME_VALIDATION').is(':checked')){
        jQuery('.field_name_max_len, .field_name_filter_laters').removeClass('hidden');
    }else{
        jQuery('.field_name_max_len, .field_name_filter_laters').addClass('hidden');
    }
}

function switchMenuStyle(){
    if (jQuery('#ARCUM_MENU_POPUP_STYLE').val() == 'popup') {
        jQuery('#ArContactUsConfigMenu .field_sidebar_animation').addClass('hidden');
        jQuery('#ArContactUsConfigMenu .field_popup_animation').removeClass('hidden');
    } else {
        jQuery('#ArContactUsConfigMenu .field_sidebar_animation').removeClass('hidden');
        jQuery('#ArContactUsConfigMenu .field_popup_animation').addClass('hidden');
    }

    if (jQuery('#ARCUMM_MENU_POPUP_STYLE').val() == 'popup') {
        jQuery('#ArContactUsConfigMobileMenu .field_sidebar_animation').addClass('hidden');
        jQuery('#ArContactUsConfigMobileMenu .field_popup_animation').removeClass('hidden');
    } else {
        jQuery('#ArContactUsConfigMobileMenu .field_sidebar_animation').removeClass('hidden');
        jQuery('#ArContactUsConfigMobileMenu .field_popup_animation').addClass('hidden');
    }
}

function scheduleChanged() {
    jQuery('.arcu-schedule-checkbox').each(function(){
        var $input = jQuery(this).find('input');
        if ($input.val() == 0) {
            jQuery(this).removeClass('checked');
            jQuery(this).parent().removeClass('checked');
        } else {
            jQuery(this).addClass('checked');
            jQuery(this).parent().addClass('checked');
        }
    });
}

function buttonTypeChanged() {
    switch(jQuery('#arcontactus_button_type').val()) {
        case 'submit':
            jQuery('#arcontactus_form_url-group').addClass('hidden');
            break;
        case 'link':
            jQuery('#arcontactus_form_url-group').removeClass('hidden');
            break;
    }
}

function fieldTypeChanged() {
    var value = jQuery('#arcontactus_field_type').val();
    
    switch(value) {
        case 'text':
        case 'tel':
        case 'email':
            jQuery('#arcontactus_field_values-group').addClass('hidden');
            jQuery('#arcontactus_field_value-group').removeClass('hidden');
            jQuery('#arcontactus_field_placeholder-group').removeClass('hidden');
            jQuery('#arcontactus_field_mask-group').removeClass('hidden');
            jQuery('#arcontactus_field_validation-group').removeClass('hidden');
            break;
        case 'textarea':
            jQuery('#arcontactus_field_values-group').addClass('hidden');
            jQuery('#arcontactus_field_value-group').removeClass('hidden');
            jQuery('#arcontactus_field_placeholder-group').removeClass('hidden');
            jQuery('#arcontactus_field_mask-group').addClass('hidden');
            jQuery('#arcontactus_field_validation-group').removeClass('hidden');
            break;
        case 'select':
            jQuery('#arcontactus_field_values-group').removeClass('hidden');
            jQuery('#arcontactus_field_value-group').addClass('hidden');
            jQuery('#arcontactus_field_placeholder-group').addClass('hidden');
            jQuery('#arcontactus_field_mask-group').addClass('hidden');
            jQuery('#arcontactus_field_validation-group').addClass('hidden');
            break;
        case 'checkbox':
            jQuery('#arcontactus_field_values-group').addClass('hidden');
            jQuery('#arcontactus_field_value-group').removeClass('hidden');
            jQuery('#arcontactus_field_placeholder-group').addClass('hidden');
            jQuery('#arcontactus_field_mask-group').addClass('hidden');
            jQuery('#arcontactus_field_validation-group').addClass('hidden');
            break;
    }
}

function changeMask() {
    if (jQuery('#arcontactus_field_mask_on').is(':checked')) {
        jQuery('#arcontactus_field_mask_on-group').removeClass('hidden');
    } else {
        jQuery('#arcontactus_field_mask_on-group').addClass('hidden');
    }
}

function changeValidationType() {
    var val = jQuery('#arcontactus_field_validation').val();
    if (val == 'advanced') {
        jQuery('#arcontactus_field_preg-group').removeClass('hidden');
    } else {
        jQuery('#arcontactus_field_preg-group').addClass('hidden');
    }
}

function changeFormIntegrations() {
    if (jQuery('#arcontactus_form_twilio_on').is(':checked')) {
        jQuery('.arcu-form-twilio-group').removeClass('hidden');
    } else {
        jQuery('.arcu-form-twilio-group').addClass('hidden');
    }
    
    if (jQuery('#arcontactus_form_tg_on').is(':checked')) {
        jQuery('.arcu-form-tg-group').removeClass('hidden');
    } else {
        jQuery('.arcu-form-tg-group').addClass('hidden');
    }
    
    if (jQuery('#arcontactus_form_onesignal_on').is(':checked')) {
        jQuery('.arcu-form-onesignal-group').removeClass('hidden');
    } else {
        jQuery('.arcu-form-onesignal-group').addClass('hidden');
    }
    
    if (jQuery('#arcontactus_form_webhook_on').is(':checked')) {
        jQuery('.arcu-form-webhook-group').removeClass('hidden');
    } else {
        jQuery('.arcu-form-webhook-group').addClass('hidden');
    }
    
    if (jQuery('#arcontactus_form_email_on').is(':checked')) {
        jQuery('.arcu-form-email-group').removeClass('hidden');
    } else {
        jQuery('.arcu-form-email-group').addClass('hidden');
    }
    if (jQuery('#arcontactus_form_perfex_on').is(':checked')) {
        jQuery('.arcu-form-perfex-group').removeClass('hidden');
    } else {
        jQuery('.arcu-form-perfex-group').addClass('hidden');
    }
}

function headerLayoutChanged() {
    switch(jQuery('#arcontactus_form_layout').val()) {
        case '1':
            jQuery('#arcu-form-icon-group').addClass('hidden');
            jQuery('#arcu-form-header-group').addClass('hidden');
            break;
        case '2':
            jQuery('#arcu-form-icon-group').addClass('hidden');
            jQuery('#arcu-form-header-group').removeClass('hidden');
            break;
        default:
            jQuery('#arcu-form-icon-group').removeClass('hidden');
            jQuery('#arcu-form-header-group').removeClass('hidden');
    }
}

function buttonIconTypeChanged() {
    jQuery('#arcu-form-button-svg').addClass('hidden');
    jQuery('#arcu-form-button-fa5').addClass('hidden');
    jQuery('#arcu-form-button-image').addClass('hidden');
    var iconType = jQuery('#arcontactus_form_button_icon_type').val();
    if (iconType == '') {
        return false;
    }
    jQuery('#arcu-form-button-' + iconType).removeClass('hidden');
}

function headerIconTypeChanged() {
    jQuery('#arcu-form-header-svg').addClass('hidden');
    jQuery('#arcu-form-header-fa5').addClass('hidden');
    jQuery('#arcu-form-header-image').addClass('hidden');
    var iconType = jQuery('#arcontactus_form_icon_type').val();
    if (iconType == '') {
        return false;
    }
    jQuery('#arcu-form-header-' + iconType).removeClass('hidden');
}

function desktopMenuLayoutChanged() {
    console.log('desktopMenuLayoutChanged');
    switch(jQuery('#ARCUM_MENU_LAYOUT').val()){
        case 'default':
            jQuery('#ArContactUsConfigMenu .field_icons_title').addClass('hidden');
            break;
        case 'personal':
            jQuery('#ArContactUsConfigMenu .field_icons_title').removeClass('hidden');
            break;
    }
    switchWelcomeMenu();
}

function mobileMenuLayoutChanged() {
    console.log('mobileMenuLayoutChanged');
    switch(jQuery('#ARCUMM_MENU_LAYOUT').val()){
        case 'default':
            jQuery('#ArContactUsConfigMobileMenu .field_icons_title').addClass('hidden');
            break;
        case 'personal':
            jQuery('#ArContactUsConfigMobileMenu .field_icons_title').removeClass('hidden');
            break;
    }
    switchWelcomeMenu();
}

function switchWelcomeMenu(){
    if (jQuery('#ARCUMM_MENU_LAYOUT').val() == 'personal' || jQuery('#ARCUM_MENU_LAYOUT').val() == 'personal') {
        jQuery('#acrontactus-menu [data-target="#arcontactus-welcome"]').removeClass('hidden');
    } else {
        jQuery('#acrontactus-menu [data-target="#arcontactus-welcome"]').addClass('hidden');
    }
}