<?php

namespace PHPMaker2021\tooms;

// Page object
$CfUserSearch = &$Page;
?>
<script>
if (!ew.vars.tables.cf_user) ew.vars.tables.cf_user = <?= JsonEncode(GetClientVar("tables", "cf_user")) ?>;
var currentForm, currentPageID;
var fcf_usersearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    fcf_usersearch = currentAdvancedSearchForm = new ew.Form("fcf_usersearch", "search");
    <?php } else { ?>
    fcf_usersearch = currentForm = new ew.Form("fcf_usersearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.cf_user.fields;
    fcf_usersearch.addFields([
        ["empl_id", [], fields.empl_id.isInvalid],
        ["name", [], fields.name.isInvalid],
        ["default_site", [], fields.default_site.isInvalid],
        ["default_language", [], fields.default_language.isInvalid],
        ["sys_admin", [], fields.sys_admin.isInvalid],
        ["status", [], fields.status.isInvalid],
        ["supv_empl_id", [], fields.supv_empl_id.isInvalid],
        ["shift", [], fields.shift.isInvalid],
        ["work_area", [], fields.work_area.isInvalid],
        ["work_grp", [], fields.work_grp.isInvalid],
        ["last_login_site", [], fields.last_login_site.isInvalid],
        ["last_login", [ew.Validators.datetime(0)], fields.last_login.isInvalid],
        ["last_pwd_changed", [ew.Validators.datetime(0)], fields.last_pwd_changed.isInvalid],
        ["audit_user", [], fields.audit_user.isInvalid],
        ["audit_date", [ew.Validators.datetime(0)], fields.audit_date.isInvalid],
        ["RowID", [ew.Validators.float], fields.RowID.isInvalid],
        ["expired_date", [ew.Validators.datetime(0)], fields.expired_date.isInvalid],
        ["column1", [], fields.column1.isInvalid],
        ["column2", [], fields.column2.isInvalid],
        ["column3", [], fields.column3.isInvalid],
        ["column4", [], fields.column4.isInvalid],
        ["column5", [], fields.column5.isInvalid],
        ["cf_user_failed_attempt", [ew.Validators.float], fields.cf_user_failed_attempt.isInvalid],
        ["cf_user_locked", [], fields.cf_user_locked.isInvalid],
        ["_password", [], fields._password.isInvalid],
        ["user_password", [], fields.user_password.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        fcf_usersearch.setInvalid();
    });

    // Validate form
    fcf_usersearch.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm(),
            $fobj = $(fobj),
            rowIndex = "";
        $fobj.data("rowindex", rowIndex);

        // Validate fields
        if (!this.validateFields(rowIndex))
            return false;

        // Call Form_CustomValidate event
        if (!this.customValidate(fobj)) {
            this.focus();
            return false;
        }
        return true;
    }

    // Form_CustomValidate
    fcf_usersearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcf_usersearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fcf_usersearch");
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
<form name="fcf_usersearch" id="fcf_usersearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cf_user">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->empl_id->Visible) { // empl_id ?>
    <div id="r_empl_id" class="form-group row">
        <label for="x_empl_id" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_empl_id"><?= $Page->empl_id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_empl_id" id="z_empl_id" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->empl_id->cellAttributes() ?>>
            <span id="el_cf_user_empl_id" class="ew-search-field">
<input type="<?= $Page->empl_id->getInputTextType() ?>" data-table="cf_user" data-field="x_empl_id" name="x_empl_id" id="x_empl_id" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->empl_id->getPlaceHolder()) ?>" value="<?= $Page->empl_id->EditValue ?>"<?= $Page->empl_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->empl_id->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->name->Visible) { // name ?>
    <div id="r_name" class="form-group row">
        <label for="x_name" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_name"><?= $Page->name->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_name" id="z_name" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->name->cellAttributes() ?>>
            <span id="el_cf_user_name" class="ew-search-field">
<input type="<?= $Page->name->getInputTextType() ?>" data-table="cf_user" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->name->getPlaceHolder()) ?>" value="<?= $Page->name->EditValue ?>"<?= $Page->name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->name->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->default_site->Visible) { // default_site ?>
    <div id="r_default_site" class="form-group row">
        <label for="x_default_site" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_default_site"><?= $Page->default_site->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_default_site" id="z_default_site" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->default_site->cellAttributes() ?>>
            <span id="el_cf_user_default_site" class="ew-search-field">
<input type="<?= $Page->default_site->getInputTextType() ?>" data-table="cf_user" data-field="x_default_site" name="x_default_site" id="x_default_site" size="30" maxlength="4" placeholder="<?= HtmlEncode($Page->default_site->getPlaceHolder()) ?>" value="<?= $Page->default_site->EditValue ?>"<?= $Page->default_site->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->default_site->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->default_language->Visible) { // default_language ?>
    <div id="r_default_language" class="form-group row">
        <label for="x_default_language" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_default_language"><?= $Page->default_language->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_default_language" id="z_default_language" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->default_language->cellAttributes() ?>>
            <span id="el_cf_user_default_language" class="ew-search-field">
<input type="<?= $Page->default_language->getInputTextType() ?>" data-table="cf_user" data-field="x_default_language" name="x_default_language" id="x_default_language" size="30" maxlength="40" placeholder="<?= HtmlEncode($Page->default_language->getPlaceHolder()) ?>" value="<?= $Page->default_language->EditValue ?>"<?= $Page->default_language->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->default_language->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->sys_admin->Visible) { // sys_admin ?>
    <div id="r_sys_admin" class="form-group row">
        <label for="x_sys_admin" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_sys_admin"><?= $Page->sys_admin->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_sys_admin" id="z_sys_admin" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->sys_admin->cellAttributes() ?>>
            <span id="el_cf_user_sys_admin" class="ew-search-field">
<input type="<?= $Page->sys_admin->getInputTextType() ?>" data-table="cf_user" data-field="x_sys_admin" name="x_sys_admin" id="x_sys_admin" size="30" maxlength="1" placeholder="<?= HtmlEncode($Page->sys_admin->getPlaceHolder()) ?>" value="<?= $Page->sys_admin->EditValue ?>"<?= $Page->sys_admin->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->sys_admin->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <div id="r_status" class="form-group row">
        <label for="x_status" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_status"><?= $Page->status->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_status" id="z_status" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->status->cellAttributes() ?>>
            <span id="el_cf_user_status" class="ew-search-field">
<input type="<?= $Page->status->getInputTextType() ?>" data-table="cf_user" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="3" placeholder="<?= HtmlEncode($Page->status->getPlaceHolder()) ?>" value="<?= $Page->status->EditValue ?>"<?= $Page->status->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->status->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->supv_empl_id->Visible) { // supv_empl_id ?>
    <div id="r_supv_empl_id" class="form-group row">
        <label for="x_supv_empl_id" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_supv_empl_id"><?= $Page->supv_empl_id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_supv_empl_id" id="z_supv_empl_id" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->supv_empl_id->cellAttributes() ?>>
            <span id="el_cf_user_supv_empl_id" class="ew-search-field">
<input type="<?= $Page->supv_empl_id->getInputTextType() ?>" data-table="cf_user" data-field="x_supv_empl_id" name="x_supv_empl_id" id="x_supv_empl_id" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->supv_empl_id->getPlaceHolder()) ?>" value="<?= $Page->supv_empl_id->EditValue ?>"<?= $Page->supv_empl_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->supv_empl_id->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->shift->Visible) { // shift ?>
    <div id="r_shift" class="form-group row">
        <label for="x_shift" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_shift"><?= $Page->shift->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_shift" id="z_shift" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->shift->cellAttributes() ?>>
            <span id="el_cf_user_shift" class="ew-search-field">
<input type="<?= $Page->shift->getInputTextType() ?>" data-table="cf_user" data-field="x_shift" name="x_shift" id="x_shift" size="30" maxlength="1" placeholder="<?= HtmlEncode($Page->shift->getPlaceHolder()) ?>" value="<?= $Page->shift->EditValue ?>"<?= $Page->shift->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->shift->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->work_area->Visible) { // work_area ?>
    <div id="r_work_area" class="form-group row">
        <label for="x_work_area" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_work_area"><?= $Page->work_area->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_work_area" id="z_work_area" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->work_area->cellAttributes() ?>>
            <span id="el_cf_user_work_area" class="ew-search-field">
<input type="<?= $Page->work_area->getInputTextType() ?>" data-table="cf_user" data-field="x_work_area" name="x_work_area" id="x_work_area" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->work_area->getPlaceHolder()) ?>" value="<?= $Page->work_area->EditValue ?>"<?= $Page->work_area->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->work_area->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->work_grp->Visible) { // work_grp ?>
    <div id="r_work_grp" class="form-group row">
        <label for="x_work_grp" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_work_grp"><?= $Page->work_grp->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_work_grp" id="z_work_grp" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->work_grp->cellAttributes() ?>>
            <span id="el_cf_user_work_grp" class="ew-search-field">
<input type="<?= $Page->work_grp->getInputTextType() ?>" data-table="cf_user" data-field="x_work_grp" name="x_work_grp" id="x_work_grp" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->work_grp->getPlaceHolder()) ?>" value="<?= $Page->work_grp->EditValue ?>"<?= $Page->work_grp->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->work_grp->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->last_login_site->Visible) { // last_login_site ?>
    <div id="r_last_login_site" class="form-group row">
        <label for="x_last_login_site" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_last_login_site"><?= $Page->last_login_site->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_last_login_site" id="z_last_login_site" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->last_login_site->cellAttributes() ?>>
            <span id="el_cf_user_last_login_site" class="ew-search-field">
<input type="<?= $Page->last_login_site->getInputTextType() ?>" data-table="cf_user" data-field="x_last_login_site" name="x_last_login_site" id="x_last_login_site" size="30" maxlength="4" placeholder="<?= HtmlEncode($Page->last_login_site->getPlaceHolder()) ?>" value="<?= $Page->last_login_site->EditValue ?>"<?= $Page->last_login_site->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->last_login_site->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->last_login->Visible) { // last_login ?>
    <div id="r_last_login" class="form-group row">
        <label for="x_last_login" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_last_login"><?= $Page->last_login->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_last_login" id="z_last_login" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->last_login->cellAttributes() ?>>
            <span id="el_cf_user_last_login" class="ew-search-field">
<input type="<?= $Page->last_login->getInputTextType() ?>" data-table="cf_user" data-field="x_last_login" name="x_last_login" id="x_last_login" placeholder="<?= HtmlEncode($Page->last_login->getPlaceHolder()) ?>" value="<?= $Page->last_login->EditValue ?>"<?= $Page->last_login->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->last_login->getErrorMessage(false) ?></div>
<?php if (!$Page->last_login->ReadOnly && !$Page->last_login->Disabled && !isset($Page->last_login->EditAttrs["readonly"]) && !isset($Page->last_login->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcf_usersearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fcf_usersearch", "x_last_login", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->last_pwd_changed->Visible) { // last_pwd_changed ?>
    <div id="r_last_pwd_changed" class="form-group row">
        <label for="x_last_pwd_changed" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_last_pwd_changed"><?= $Page->last_pwd_changed->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_last_pwd_changed" id="z_last_pwd_changed" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->last_pwd_changed->cellAttributes() ?>>
            <span id="el_cf_user_last_pwd_changed" class="ew-search-field">
<input type="<?= $Page->last_pwd_changed->getInputTextType() ?>" data-table="cf_user" data-field="x_last_pwd_changed" name="x_last_pwd_changed" id="x_last_pwd_changed" placeholder="<?= HtmlEncode($Page->last_pwd_changed->getPlaceHolder()) ?>" value="<?= $Page->last_pwd_changed->EditValue ?>"<?= $Page->last_pwd_changed->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->last_pwd_changed->getErrorMessage(false) ?></div>
<?php if (!$Page->last_pwd_changed->ReadOnly && !$Page->last_pwd_changed->Disabled && !isset($Page->last_pwd_changed->EditAttrs["readonly"]) && !isset($Page->last_pwd_changed->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcf_usersearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fcf_usersearch", "x_last_pwd_changed", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->audit_user->Visible) { // audit_user ?>
    <div id="r_audit_user" class="form-group row">
        <label for="x_audit_user" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_audit_user"><?= $Page->audit_user->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_audit_user" id="z_audit_user" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->audit_user->cellAttributes() ?>>
            <span id="el_cf_user_audit_user" class="ew-search-field">
<input type="<?= $Page->audit_user->getInputTextType() ?>" data-table="cf_user" data-field="x_audit_user" name="x_audit_user" id="x_audit_user" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->audit_user->getPlaceHolder()) ?>" value="<?= $Page->audit_user->EditValue ?>"<?= $Page->audit_user->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->audit_user->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->audit_date->Visible) { // audit_date ?>
    <div id="r_audit_date" class="form-group row">
        <label for="x_audit_date" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_audit_date"><?= $Page->audit_date->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_audit_date" id="z_audit_date" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->audit_date->cellAttributes() ?>>
            <span id="el_cf_user_audit_date" class="ew-search-field">
<input type="<?= $Page->audit_date->getInputTextType() ?>" data-table="cf_user" data-field="x_audit_date" name="x_audit_date" id="x_audit_date" placeholder="<?= HtmlEncode($Page->audit_date->getPlaceHolder()) ?>" value="<?= $Page->audit_date->EditValue ?>"<?= $Page->audit_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->audit_date->getErrorMessage(false) ?></div>
<?php if (!$Page->audit_date->ReadOnly && !$Page->audit_date->Disabled && !isset($Page->audit_date->EditAttrs["readonly"]) && !isset($Page->audit_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcf_usersearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fcf_usersearch", "x_audit_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->RowID->Visible) { // RowID ?>
    <div id="r_RowID" class="form-group row">
        <label for="x_RowID" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_RowID"><?= $Page->RowID->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_RowID" id="z_RowID" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->RowID->cellAttributes() ?>>
            <span id="el_cf_user_RowID" class="ew-search-field">
<input type="<?= $Page->RowID->getInputTextType() ?>" data-table="cf_user" data-field="x_RowID" name="x_RowID" id="x_RowID" placeholder="<?= HtmlEncode($Page->RowID->getPlaceHolder()) ?>" value="<?= $Page->RowID->EditValue ?>"<?= $Page->RowID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->RowID->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->expired_date->Visible) { // expired_date ?>
    <div id="r_expired_date" class="form-group row">
        <label for="x_expired_date" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_expired_date"><?= $Page->expired_date->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_expired_date" id="z_expired_date" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->expired_date->cellAttributes() ?>>
            <span id="el_cf_user_expired_date" class="ew-search-field">
<input type="<?= $Page->expired_date->getInputTextType() ?>" data-table="cf_user" data-field="x_expired_date" name="x_expired_date" id="x_expired_date" placeholder="<?= HtmlEncode($Page->expired_date->getPlaceHolder()) ?>" value="<?= $Page->expired_date->EditValue ?>"<?= $Page->expired_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->expired_date->getErrorMessage(false) ?></div>
<?php if (!$Page->expired_date->ReadOnly && !$Page->expired_date->Disabled && !isset($Page->expired_date->EditAttrs["readonly"]) && !isset($Page->expired_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcf_usersearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fcf_usersearch", "x_expired_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->column1->Visible) { // column1 ?>
    <div id="r_column1" class="form-group row">
        <label for="x_column1" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_column1"><?= $Page->column1->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_column1" id="z_column1" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->column1->cellAttributes() ?>>
            <span id="el_cf_user_column1" class="ew-search-field">
<input type="<?= $Page->column1->getInputTextType() ?>" data-table="cf_user" data-field="x_column1" name="x_column1" id="x_column1" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->column1->getPlaceHolder()) ?>" value="<?= $Page->column1->EditValue ?>"<?= $Page->column1->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->column1->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->column2->Visible) { // column2 ?>
    <div id="r_column2" class="form-group row">
        <label for="x_column2" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_column2"><?= $Page->column2->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_column2" id="z_column2" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->column2->cellAttributes() ?>>
            <span id="el_cf_user_column2" class="ew-search-field">
<input type="<?= $Page->column2->getInputTextType() ?>" data-table="cf_user" data-field="x_column2" name="x_column2" id="x_column2" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->column2->getPlaceHolder()) ?>" value="<?= $Page->column2->EditValue ?>"<?= $Page->column2->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->column2->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->column3->Visible) { // column3 ?>
    <div id="r_column3" class="form-group row">
        <label for="x_column3" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_column3"><?= $Page->column3->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_column3" id="z_column3" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->column3->cellAttributes() ?>>
            <span id="el_cf_user_column3" class="ew-search-field">
<input type="<?= $Page->column3->getInputTextType() ?>" data-table="cf_user" data-field="x_column3" name="x_column3" id="x_column3" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->column3->getPlaceHolder()) ?>" value="<?= $Page->column3->EditValue ?>"<?= $Page->column3->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->column3->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->column4->Visible) { // column4 ?>
    <div id="r_column4" class="form-group row">
        <label for="x_column4" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_column4"><?= $Page->column4->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_column4" id="z_column4" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->column4->cellAttributes() ?>>
            <span id="el_cf_user_column4" class="ew-search-field">
<input type="<?= $Page->column4->getInputTextType() ?>" data-table="cf_user" data-field="x_column4" name="x_column4" id="x_column4" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->column4->getPlaceHolder()) ?>" value="<?= $Page->column4->EditValue ?>"<?= $Page->column4->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->column4->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->column5->Visible) { // column5 ?>
    <div id="r_column5" class="form-group row">
        <label for="x_column5" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_column5"><?= $Page->column5->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_column5" id="z_column5" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->column5->cellAttributes() ?>>
            <span id="el_cf_user_column5" class="ew-search-field">
<input type="<?= $Page->column5->getInputTextType() ?>" data-table="cf_user" data-field="x_column5" name="x_column5" id="x_column5" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->column5->getPlaceHolder()) ?>" value="<?= $Page->column5->EditValue ?>"<?= $Page->column5->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->column5->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->cf_user_failed_attempt->Visible) { // cf_user_failed_attempt ?>
    <div id="r_cf_user_failed_attempt" class="form-group row">
        <label for="x_cf_user_failed_attempt" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_cf_user_failed_attempt"><?= $Page->cf_user_failed_attempt->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_cf_user_failed_attempt" id="z_cf_user_failed_attempt" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cf_user_failed_attempt->cellAttributes() ?>>
            <span id="el_cf_user_cf_user_failed_attempt" class="ew-search-field">
<input type="<?= $Page->cf_user_failed_attempt->getInputTextType() ?>" data-table="cf_user" data-field="x_cf_user_failed_attempt" name="x_cf_user_failed_attempt" id="x_cf_user_failed_attempt" size="30" placeholder="<?= HtmlEncode($Page->cf_user_failed_attempt->getPlaceHolder()) ?>" value="<?= $Page->cf_user_failed_attempt->EditValue ?>"<?= $Page->cf_user_failed_attempt->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cf_user_failed_attempt->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->cf_user_locked->Visible) { // cf_user_locked ?>
    <div id="r_cf_user_locked" class="form-group row">
        <label for="x_cf_user_locked" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_cf_user_locked"><?= $Page->cf_user_locked->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_cf_user_locked" id="z_cf_user_locked" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cf_user_locked->cellAttributes() ?>>
            <span id="el_cf_user_cf_user_locked" class="ew-search-field">
<input type="<?= $Page->cf_user_locked->getInputTextType() ?>" data-table="cf_user" data-field="x_cf_user_locked" name="x_cf_user_locked" id="x_cf_user_locked" size="30" maxlength="1" placeholder="<?= HtmlEncode($Page->cf_user_locked->getPlaceHolder()) ?>" value="<?= $Page->cf_user_locked->EditValue ?>"<?= $Page->cf_user_locked->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cf_user_locked->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->_password->Visible) { // password ?>
    <div id="r__password" class="form-group row">
        <label for="x__password" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user__password"><?= $Page->_password->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z__password" id="z__password" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_password->cellAttributes() ?>>
            <span id="el_cf_user__password" class="ew-search-field">
<input type="<?= $Page->_password->getInputTextType() ?>" data-table="cf_user" data-field="x__password" name="x__password" id="x__password" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->_password->getPlaceHolder()) ?>" value="<?= $Page->_password->EditValue ?>"<?= $Page->_password->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->_password->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->user_password->Visible) { // user_password ?>
    <div id="r_user_password" class="form-group row">
        <label for="x_user_password" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cf_user_user_password"><?= $Page->user_password->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_user_password" id="z_user_password" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->user_password->cellAttributes() ?>>
            <span id="el_cf_user_user_password" class="ew-search-field">
<input type="<?= $Page->user_password->getInputTextType() ?>" data-table="cf_user" data-field="x_user_password" name="x_user_password" id="x_user_password" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->user_password->getPlaceHolder()) ?>" value="<?= $Page->user_password->EditValue ?>"<?= $Page->user_password->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->user_password->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("Search") ?></button>
        <button class="btn btn-default ew-btn" name="btn-reset" id="btn-reset" type="button" onclick="location.reload();"><?= $Language->phrase("Reset") ?></button>
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
