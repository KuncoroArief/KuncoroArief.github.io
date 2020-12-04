<?php

namespace PHPMaker2021\tooms;

// Page object
$CfUserAdd = &$Page;
?>
<script>
if (!ew.vars.tables.cf_user) ew.vars.tables.cf_user = <?= JsonEncode(GetClientVar("tables", "cf_user")) ?>;
var currentForm, currentPageID;
var fcf_useradd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fcf_useradd = currentForm = new ew.Form("fcf_useradd", "add");

    // Add fields
    var fields = ew.vars.tables.cf_user.fields;
    fcf_useradd.addFields([
        ["empl_id", [fields.empl_id.required ? ew.Validators.required(fields.empl_id.caption) : null], fields.empl_id.isInvalid],
        ["name", [fields.name.required ? ew.Validators.required(fields.name.caption) : null], fields.name.isInvalid],
        ["default_site", [fields.default_site.required ? ew.Validators.required(fields.default_site.caption) : null], fields.default_site.isInvalid],
        ["default_language", [fields.default_language.required ? ew.Validators.required(fields.default_language.caption) : null], fields.default_language.isInvalid],
        ["sys_admin", [fields.sys_admin.required ? ew.Validators.required(fields.sys_admin.caption) : null], fields.sys_admin.isInvalid],
        ["status", [fields.status.required ? ew.Validators.required(fields.status.caption) : null], fields.status.isInvalid],
        ["supv_empl_id", [fields.supv_empl_id.required ? ew.Validators.required(fields.supv_empl_id.caption) : null], fields.supv_empl_id.isInvalid],
        ["shift", [fields.shift.required ? ew.Validators.required(fields.shift.caption) : null], fields.shift.isInvalid],
        ["work_area", [fields.work_area.required ? ew.Validators.required(fields.work_area.caption) : null], fields.work_area.isInvalid],
        ["work_grp", [fields.work_grp.required ? ew.Validators.required(fields.work_grp.caption) : null], fields.work_grp.isInvalid],
        ["last_login_site", [fields.last_login_site.required ? ew.Validators.required(fields.last_login_site.caption) : null], fields.last_login_site.isInvalid],
        ["last_login", [fields.last_login.required ? ew.Validators.required(fields.last_login.caption) : null, ew.Validators.datetime(0)], fields.last_login.isInvalid],
        ["last_pwd_changed", [fields.last_pwd_changed.required ? ew.Validators.required(fields.last_pwd_changed.caption) : null, ew.Validators.datetime(0)], fields.last_pwd_changed.isInvalid],
        ["audit_user", [fields.audit_user.required ? ew.Validators.required(fields.audit_user.caption) : null], fields.audit_user.isInvalid],
        ["audit_date", [fields.audit_date.required ? ew.Validators.required(fields.audit_date.caption) : null, ew.Validators.datetime(0)], fields.audit_date.isInvalid],
        ["expired_date", [fields.expired_date.required ? ew.Validators.required(fields.expired_date.caption) : null, ew.Validators.datetime(0)], fields.expired_date.isInvalid],
        ["column1", [fields.column1.required ? ew.Validators.required(fields.column1.caption) : null], fields.column1.isInvalid],
        ["column2", [fields.column2.required ? ew.Validators.required(fields.column2.caption) : null], fields.column2.isInvalid],
        ["column3", [fields.column3.required ? ew.Validators.required(fields.column3.caption) : null], fields.column3.isInvalid],
        ["column4", [fields.column4.required ? ew.Validators.required(fields.column4.caption) : null], fields.column4.isInvalid],
        ["column5", [fields.column5.required ? ew.Validators.required(fields.column5.caption) : null], fields.column5.isInvalid],
        ["cf_user_failed_attempt", [fields.cf_user_failed_attempt.required ? ew.Validators.required(fields.cf_user_failed_attempt.caption) : null, ew.Validators.float], fields.cf_user_failed_attempt.isInvalid],
        ["cf_user_locked", [fields.cf_user_locked.required ? ew.Validators.required(fields.cf_user_locked.caption) : null], fields.cf_user_locked.isInvalid],
        ["_password", [fields._password.required ? ew.Validators.required(fields._password.caption) : null], fields._password.isInvalid],
        ["user_password", [fields.user_password.required ? ew.Validators.required(fields.user_password.caption) : null], fields.user_password.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcf_useradd,
            fobj = f.getForm(),
            $fobj = $(fobj),
            $k = $fobj.find("#" + f.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            f.setInvalid(rowIndex);
        }
    });

    // Validate form
    fcf_useradd.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm(),
            $fobj = $(fobj);
        if ($fobj.find("#confirm").val() == "confirm")
            return true;
        var addcnt = 0,
            $k = $fobj.find("#" + this.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1, // Check rowcnt == 0 => Inline-Add
            gridinsert = ["insert", "gridinsert"].includes($fobj.find("#action").val()) && $k[0];
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            $fobj.data("rowindex", rowIndex);

            // Validate fields
            if (!this.validateFields(rowIndex))
                return false;

            // Call Form_CustomValidate event
            if (!this.customValidate(fobj)) {
                this.focus();
                return false;
            }
        }

        // Process detail forms
        var dfs = $fobj.find("input[name='detailpage']").get();
        for (var i = 0; i < dfs.length; i++) {
            var df = dfs[i],
                val = df.value,
                frm = ew.forms.get(val);
            if (val && frm && !frm.validate())
                return false;
        }
        return true;
    }

    // Form_CustomValidate
    fcf_useradd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcf_useradd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fcf_useradd");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fcf_useradd" id="fcf_useradd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cf_user">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->empl_id->Visible) { // empl_id ?>
    <div id="r_empl_id" class="form-group row">
        <label id="elh_cf_user_empl_id" for="x_empl_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->empl_id->caption() ?><?= $Page->empl_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->empl_id->cellAttributes() ?>>
<span id="el_cf_user_empl_id">
<input type="<?= $Page->empl_id->getInputTextType() ?>" data-table="cf_user" data-field="x_empl_id" name="x_empl_id" id="x_empl_id" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->empl_id->getPlaceHolder()) ?>" value="<?= $Page->empl_id->EditValue ?>"<?= $Page->empl_id->editAttributes() ?> aria-describedby="x_empl_id_help">
<?= $Page->empl_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->empl_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->name->Visible) { // name ?>
    <div id="r_name" class="form-group row">
        <label id="elh_cf_user_name" for="x_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->name->caption() ?><?= $Page->name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->name->cellAttributes() ?>>
<span id="el_cf_user_name">
<input type="<?= $Page->name->getInputTextType() ?>" data-table="cf_user" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->name->getPlaceHolder()) ?>" value="<?= $Page->name->EditValue ?>"<?= $Page->name->editAttributes() ?> aria-describedby="x_name_help">
<?= $Page->name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->default_site->Visible) { // default_site ?>
    <div id="r_default_site" class="form-group row">
        <label id="elh_cf_user_default_site" for="x_default_site" class="<?= $Page->LeftColumnClass ?>"><?= $Page->default_site->caption() ?><?= $Page->default_site->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->default_site->cellAttributes() ?>>
<span id="el_cf_user_default_site">
<input type="<?= $Page->default_site->getInputTextType() ?>" data-table="cf_user" data-field="x_default_site" name="x_default_site" id="x_default_site" size="30" maxlength="4" placeholder="<?= HtmlEncode($Page->default_site->getPlaceHolder()) ?>" value="<?= $Page->default_site->EditValue ?>"<?= $Page->default_site->editAttributes() ?> aria-describedby="x_default_site_help">
<?= $Page->default_site->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->default_site->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->default_language->Visible) { // default_language ?>
    <div id="r_default_language" class="form-group row">
        <label id="elh_cf_user_default_language" for="x_default_language" class="<?= $Page->LeftColumnClass ?>"><?= $Page->default_language->caption() ?><?= $Page->default_language->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->default_language->cellAttributes() ?>>
<span id="el_cf_user_default_language">
<input type="<?= $Page->default_language->getInputTextType() ?>" data-table="cf_user" data-field="x_default_language" name="x_default_language" id="x_default_language" size="30" maxlength="40" placeholder="<?= HtmlEncode($Page->default_language->getPlaceHolder()) ?>" value="<?= $Page->default_language->EditValue ?>"<?= $Page->default_language->editAttributes() ?> aria-describedby="x_default_language_help">
<?= $Page->default_language->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->default_language->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sys_admin->Visible) { // sys_admin ?>
    <div id="r_sys_admin" class="form-group row">
        <label id="elh_cf_user_sys_admin" for="x_sys_admin" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sys_admin->caption() ?><?= $Page->sys_admin->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->sys_admin->cellAttributes() ?>>
<span id="el_cf_user_sys_admin">
<input type="<?= $Page->sys_admin->getInputTextType() ?>" data-table="cf_user" data-field="x_sys_admin" name="x_sys_admin" id="x_sys_admin" size="30" maxlength="1" placeholder="<?= HtmlEncode($Page->sys_admin->getPlaceHolder()) ?>" value="<?= $Page->sys_admin->EditValue ?>"<?= $Page->sys_admin->editAttributes() ?> aria-describedby="x_sys_admin_help">
<?= $Page->sys_admin->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->sys_admin->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <div id="r_status" class="form-group row">
        <label id="elh_cf_user_status" for="x_status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->status->caption() ?><?= $Page->status->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->status->cellAttributes() ?>>
<span id="el_cf_user_status">
<input type="<?= $Page->status->getInputTextType() ?>" data-table="cf_user" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="3" placeholder="<?= HtmlEncode($Page->status->getPlaceHolder()) ?>" value="<?= $Page->status->EditValue ?>"<?= $Page->status->editAttributes() ?> aria-describedby="x_status_help">
<?= $Page->status->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->status->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->supv_empl_id->Visible) { // supv_empl_id ?>
    <div id="r_supv_empl_id" class="form-group row">
        <label id="elh_cf_user_supv_empl_id" for="x_supv_empl_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->supv_empl_id->caption() ?><?= $Page->supv_empl_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->supv_empl_id->cellAttributes() ?>>
<span id="el_cf_user_supv_empl_id">
<input type="<?= $Page->supv_empl_id->getInputTextType() ?>" data-table="cf_user" data-field="x_supv_empl_id" name="x_supv_empl_id" id="x_supv_empl_id" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->supv_empl_id->getPlaceHolder()) ?>" value="<?= $Page->supv_empl_id->EditValue ?>"<?= $Page->supv_empl_id->editAttributes() ?> aria-describedby="x_supv_empl_id_help">
<?= $Page->supv_empl_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->supv_empl_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->shift->Visible) { // shift ?>
    <div id="r_shift" class="form-group row">
        <label id="elh_cf_user_shift" for="x_shift" class="<?= $Page->LeftColumnClass ?>"><?= $Page->shift->caption() ?><?= $Page->shift->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->shift->cellAttributes() ?>>
<span id="el_cf_user_shift">
<input type="<?= $Page->shift->getInputTextType() ?>" data-table="cf_user" data-field="x_shift" name="x_shift" id="x_shift" size="30" maxlength="1" placeholder="<?= HtmlEncode($Page->shift->getPlaceHolder()) ?>" value="<?= $Page->shift->EditValue ?>"<?= $Page->shift->editAttributes() ?> aria-describedby="x_shift_help">
<?= $Page->shift->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->shift->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->work_area->Visible) { // work_area ?>
    <div id="r_work_area" class="form-group row">
        <label id="elh_cf_user_work_area" for="x_work_area" class="<?= $Page->LeftColumnClass ?>"><?= $Page->work_area->caption() ?><?= $Page->work_area->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->work_area->cellAttributes() ?>>
<span id="el_cf_user_work_area">
<input type="<?= $Page->work_area->getInputTextType() ?>" data-table="cf_user" data-field="x_work_area" name="x_work_area" id="x_work_area" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->work_area->getPlaceHolder()) ?>" value="<?= $Page->work_area->EditValue ?>"<?= $Page->work_area->editAttributes() ?> aria-describedby="x_work_area_help">
<?= $Page->work_area->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->work_area->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->work_grp->Visible) { // work_grp ?>
    <div id="r_work_grp" class="form-group row">
        <label id="elh_cf_user_work_grp" for="x_work_grp" class="<?= $Page->LeftColumnClass ?>"><?= $Page->work_grp->caption() ?><?= $Page->work_grp->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->work_grp->cellAttributes() ?>>
<span id="el_cf_user_work_grp">
<input type="<?= $Page->work_grp->getInputTextType() ?>" data-table="cf_user" data-field="x_work_grp" name="x_work_grp" id="x_work_grp" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->work_grp->getPlaceHolder()) ?>" value="<?= $Page->work_grp->EditValue ?>"<?= $Page->work_grp->editAttributes() ?> aria-describedby="x_work_grp_help">
<?= $Page->work_grp->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->work_grp->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->last_login_site->Visible) { // last_login_site ?>
    <div id="r_last_login_site" class="form-group row">
        <label id="elh_cf_user_last_login_site" for="x_last_login_site" class="<?= $Page->LeftColumnClass ?>"><?= $Page->last_login_site->caption() ?><?= $Page->last_login_site->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->last_login_site->cellAttributes() ?>>
<span id="el_cf_user_last_login_site">
<input type="<?= $Page->last_login_site->getInputTextType() ?>" data-table="cf_user" data-field="x_last_login_site" name="x_last_login_site" id="x_last_login_site" size="30" maxlength="4" placeholder="<?= HtmlEncode($Page->last_login_site->getPlaceHolder()) ?>" value="<?= $Page->last_login_site->EditValue ?>"<?= $Page->last_login_site->editAttributes() ?> aria-describedby="x_last_login_site_help">
<?= $Page->last_login_site->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->last_login_site->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->last_login->Visible) { // last_login ?>
    <div id="r_last_login" class="form-group row">
        <label id="elh_cf_user_last_login" for="x_last_login" class="<?= $Page->LeftColumnClass ?>"><?= $Page->last_login->caption() ?><?= $Page->last_login->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->last_login->cellAttributes() ?>>
<span id="el_cf_user_last_login">
<input type="<?= $Page->last_login->getInputTextType() ?>" data-table="cf_user" data-field="x_last_login" name="x_last_login" id="x_last_login" placeholder="<?= HtmlEncode($Page->last_login->getPlaceHolder()) ?>" value="<?= $Page->last_login->EditValue ?>"<?= $Page->last_login->editAttributes() ?> aria-describedby="x_last_login_help">
<?= $Page->last_login->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->last_login->getErrorMessage() ?></div>
<?php if (!$Page->last_login->ReadOnly && !$Page->last_login->Disabled && !isset($Page->last_login->EditAttrs["readonly"]) && !isset($Page->last_login->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcf_useradd", "datetimepicker"], function() {
    ew.createDateTimePicker("fcf_useradd", "x_last_login", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->last_pwd_changed->Visible) { // last_pwd_changed ?>
    <div id="r_last_pwd_changed" class="form-group row">
        <label id="elh_cf_user_last_pwd_changed" for="x_last_pwd_changed" class="<?= $Page->LeftColumnClass ?>"><?= $Page->last_pwd_changed->caption() ?><?= $Page->last_pwd_changed->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->last_pwd_changed->cellAttributes() ?>>
<span id="el_cf_user_last_pwd_changed">
<input type="<?= $Page->last_pwd_changed->getInputTextType() ?>" data-table="cf_user" data-field="x_last_pwd_changed" name="x_last_pwd_changed" id="x_last_pwd_changed" placeholder="<?= HtmlEncode($Page->last_pwd_changed->getPlaceHolder()) ?>" value="<?= $Page->last_pwd_changed->EditValue ?>"<?= $Page->last_pwd_changed->editAttributes() ?> aria-describedby="x_last_pwd_changed_help">
<?= $Page->last_pwd_changed->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->last_pwd_changed->getErrorMessage() ?></div>
<?php if (!$Page->last_pwd_changed->ReadOnly && !$Page->last_pwd_changed->Disabled && !isset($Page->last_pwd_changed->EditAttrs["readonly"]) && !isset($Page->last_pwd_changed->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcf_useradd", "datetimepicker"], function() {
    ew.createDateTimePicker("fcf_useradd", "x_last_pwd_changed", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->audit_user->Visible) { // audit_user ?>
    <div id="r_audit_user" class="form-group row">
        <label id="elh_cf_user_audit_user" for="x_audit_user" class="<?= $Page->LeftColumnClass ?>"><?= $Page->audit_user->caption() ?><?= $Page->audit_user->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->audit_user->cellAttributes() ?>>
<span id="el_cf_user_audit_user">
<input type="<?= $Page->audit_user->getInputTextType() ?>" data-table="cf_user" data-field="x_audit_user" name="x_audit_user" id="x_audit_user" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->audit_user->getPlaceHolder()) ?>" value="<?= $Page->audit_user->EditValue ?>"<?= $Page->audit_user->editAttributes() ?> aria-describedby="x_audit_user_help">
<?= $Page->audit_user->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->audit_user->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->audit_date->Visible) { // audit_date ?>
    <div id="r_audit_date" class="form-group row">
        <label id="elh_cf_user_audit_date" for="x_audit_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->audit_date->caption() ?><?= $Page->audit_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->audit_date->cellAttributes() ?>>
<span id="el_cf_user_audit_date">
<input type="<?= $Page->audit_date->getInputTextType() ?>" data-table="cf_user" data-field="x_audit_date" name="x_audit_date" id="x_audit_date" placeholder="<?= HtmlEncode($Page->audit_date->getPlaceHolder()) ?>" value="<?= $Page->audit_date->EditValue ?>"<?= $Page->audit_date->editAttributes() ?> aria-describedby="x_audit_date_help">
<?= $Page->audit_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->audit_date->getErrorMessage() ?></div>
<?php if (!$Page->audit_date->ReadOnly && !$Page->audit_date->Disabled && !isset($Page->audit_date->EditAttrs["readonly"]) && !isset($Page->audit_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcf_useradd", "datetimepicker"], function() {
    ew.createDateTimePicker("fcf_useradd", "x_audit_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->expired_date->Visible) { // expired_date ?>
    <div id="r_expired_date" class="form-group row">
        <label id="elh_cf_user_expired_date" for="x_expired_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->expired_date->caption() ?><?= $Page->expired_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->expired_date->cellAttributes() ?>>
<span id="el_cf_user_expired_date">
<input type="<?= $Page->expired_date->getInputTextType() ?>" data-table="cf_user" data-field="x_expired_date" name="x_expired_date" id="x_expired_date" placeholder="<?= HtmlEncode($Page->expired_date->getPlaceHolder()) ?>" value="<?= $Page->expired_date->EditValue ?>"<?= $Page->expired_date->editAttributes() ?> aria-describedby="x_expired_date_help">
<?= $Page->expired_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->expired_date->getErrorMessage() ?></div>
<?php if (!$Page->expired_date->ReadOnly && !$Page->expired_date->Disabled && !isset($Page->expired_date->EditAttrs["readonly"]) && !isset($Page->expired_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcf_useradd", "datetimepicker"], function() {
    ew.createDateTimePicker("fcf_useradd", "x_expired_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->column1->Visible) { // column1 ?>
    <div id="r_column1" class="form-group row">
        <label id="elh_cf_user_column1" for="x_column1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->column1->caption() ?><?= $Page->column1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->column1->cellAttributes() ?>>
<span id="el_cf_user_column1">
<input type="<?= $Page->column1->getInputTextType() ?>" data-table="cf_user" data-field="x_column1" name="x_column1" id="x_column1" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->column1->getPlaceHolder()) ?>" value="<?= $Page->column1->EditValue ?>"<?= $Page->column1->editAttributes() ?> aria-describedby="x_column1_help">
<?= $Page->column1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->column1->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->column2->Visible) { // column2 ?>
    <div id="r_column2" class="form-group row">
        <label id="elh_cf_user_column2" for="x_column2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->column2->caption() ?><?= $Page->column2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->column2->cellAttributes() ?>>
<span id="el_cf_user_column2">
<input type="<?= $Page->column2->getInputTextType() ?>" data-table="cf_user" data-field="x_column2" name="x_column2" id="x_column2" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->column2->getPlaceHolder()) ?>" value="<?= $Page->column2->EditValue ?>"<?= $Page->column2->editAttributes() ?> aria-describedby="x_column2_help">
<?= $Page->column2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->column2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->column3->Visible) { // column3 ?>
    <div id="r_column3" class="form-group row">
        <label id="elh_cf_user_column3" for="x_column3" class="<?= $Page->LeftColumnClass ?>"><?= $Page->column3->caption() ?><?= $Page->column3->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->column3->cellAttributes() ?>>
<span id="el_cf_user_column3">
<input type="<?= $Page->column3->getInputTextType() ?>" data-table="cf_user" data-field="x_column3" name="x_column3" id="x_column3" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->column3->getPlaceHolder()) ?>" value="<?= $Page->column3->EditValue ?>"<?= $Page->column3->editAttributes() ?> aria-describedby="x_column3_help">
<?= $Page->column3->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->column3->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->column4->Visible) { // column4 ?>
    <div id="r_column4" class="form-group row">
        <label id="elh_cf_user_column4" for="x_column4" class="<?= $Page->LeftColumnClass ?>"><?= $Page->column4->caption() ?><?= $Page->column4->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->column4->cellAttributes() ?>>
<span id="el_cf_user_column4">
<input type="<?= $Page->column4->getInputTextType() ?>" data-table="cf_user" data-field="x_column4" name="x_column4" id="x_column4" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->column4->getPlaceHolder()) ?>" value="<?= $Page->column4->EditValue ?>"<?= $Page->column4->editAttributes() ?> aria-describedby="x_column4_help">
<?= $Page->column4->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->column4->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->column5->Visible) { // column5 ?>
    <div id="r_column5" class="form-group row">
        <label id="elh_cf_user_column5" for="x_column5" class="<?= $Page->LeftColumnClass ?>"><?= $Page->column5->caption() ?><?= $Page->column5->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->column5->cellAttributes() ?>>
<span id="el_cf_user_column5">
<input type="<?= $Page->column5->getInputTextType() ?>" data-table="cf_user" data-field="x_column5" name="x_column5" id="x_column5" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->column5->getPlaceHolder()) ?>" value="<?= $Page->column5->EditValue ?>"<?= $Page->column5->editAttributes() ?> aria-describedby="x_column5_help">
<?= $Page->column5->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->column5->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cf_user_failed_attempt->Visible) { // cf_user_failed_attempt ?>
    <div id="r_cf_user_failed_attempt" class="form-group row">
        <label id="elh_cf_user_cf_user_failed_attempt" for="x_cf_user_failed_attempt" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cf_user_failed_attempt->caption() ?><?= $Page->cf_user_failed_attempt->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cf_user_failed_attempt->cellAttributes() ?>>
<span id="el_cf_user_cf_user_failed_attempt">
<input type="<?= $Page->cf_user_failed_attempt->getInputTextType() ?>" data-table="cf_user" data-field="x_cf_user_failed_attempt" name="x_cf_user_failed_attempt" id="x_cf_user_failed_attempt" size="30" placeholder="<?= HtmlEncode($Page->cf_user_failed_attempt->getPlaceHolder()) ?>" value="<?= $Page->cf_user_failed_attempt->EditValue ?>"<?= $Page->cf_user_failed_attempt->editAttributes() ?> aria-describedby="x_cf_user_failed_attempt_help">
<?= $Page->cf_user_failed_attempt->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cf_user_failed_attempt->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cf_user_locked->Visible) { // cf_user_locked ?>
    <div id="r_cf_user_locked" class="form-group row">
        <label id="elh_cf_user_cf_user_locked" for="x_cf_user_locked" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cf_user_locked->caption() ?><?= $Page->cf_user_locked->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cf_user_locked->cellAttributes() ?>>
<span id="el_cf_user_cf_user_locked">
<input type="<?= $Page->cf_user_locked->getInputTextType() ?>" data-table="cf_user" data-field="x_cf_user_locked" name="x_cf_user_locked" id="x_cf_user_locked" size="30" maxlength="1" placeholder="<?= HtmlEncode($Page->cf_user_locked->getPlaceHolder()) ?>" value="<?= $Page->cf_user_locked->EditValue ?>"<?= $Page->cf_user_locked->editAttributes() ?> aria-describedby="x_cf_user_locked_help">
<?= $Page->cf_user_locked->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cf_user_locked->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_password->Visible) { // password ?>
    <div id="r__password" class="form-group row">
        <label id="elh_cf_user__password" for="x__password" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_password->caption() ?><?= $Page->_password->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_password->cellAttributes() ?>>
<span id="el_cf_user__password">
<input type="<?= $Page->_password->getInputTextType() ?>" data-table="cf_user" data-field="x__password" name="x__password" id="x__password" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->_password->getPlaceHolder()) ?>" value="<?= $Page->_password->EditValue ?>"<?= $Page->_password->editAttributes() ?> aria-describedby="x__password_help">
<?= $Page->_password->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_password->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->user_password->Visible) { // user_password ?>
    <div id="r_user_password" class="form-group row">
        <label id="elh_cf_user_user_password" for="x_user_password" class="<?= $Page->LeftColumnClass ?>"><?= $Page->user_password->caption() ?><?= $Page->user_password->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->user_password->cellAttributes() ?>>
<span id="el_cf_user_user_password">
<input type="<?= $Page->user_password->getInputTextType() ?>" data-table="cf_user" data-field="x_user_password" name="x_user_password" id="x_user_password" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->user_password->getPlaceHolder()) ?>" value="<?= $Page->user_password->EditValue ?>"<?= $Page->user_password->editAttributes() ?> aria-describedby="x_user_password_help">
<?= $Page->user_password->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->user_password->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("AddBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
    </div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("cf_user");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
