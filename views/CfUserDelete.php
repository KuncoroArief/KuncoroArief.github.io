<?php

namespace PHPMaker2021\tooms;

// Page object
$CfUserDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcf_userdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fcf_userdelete = currentForm = new ew.Form("fcf_userdelete", "delete");
    loadjs.done("fcf_userdelete");
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
<form name="fcf_userdelete" id="fcf_userdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cf_user">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid">
<div class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table class="table ew-table">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->empl_id->Visible) { // empl_id ?>
        <th class="<?= $Page->empl_id->headerCellClass() ?>"><span id="elh_cf_user_empl_id" class="cf_user_empl_id"><?= $Page->empl_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->name->Visible) { // name ?>
        <th class="<?= $Page->name->headerCellClass() ?>"><span id="elh_cf_user_name" class="cf_user_name"><?= $Page->name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->default_site->Visible) { // default_site ?>
        <th class="<?= $Page->default_site->headerCellClass() ?>"><span id="elh_cf_user_default_site" class="cf_user_default_site"><?= $Page->default_site->caption() ?></span></th>
<?php } ?>
<?php if ($Page->default_language->Visible) { // default_language ?>
        <th class="<?= $Page->default_language->headerCellClass() ?>"><span id="elh_cf_user_default_language" class="cf_user_default_language"><?= $Page->default_language->caption() ?></span></th>
<?php } ?>
<?php if ($Page->sys_admin->Visible) { // sys_admin ?>
        <th class="<?= $Page->sys_admin->headerCellClass() ?>"><span id="elh_cf_user_sys_admin" class="cf_user_sys_admin"><?= $Page->sys_admin->caption() ?></span></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th class="<?= $Page->status->headerCellClass() ?>"><span id="elh_cf_user_status" class="cf_user_status"><?= $Page->status->caption() ?></span></th>
<?php } ?>
<?php if ($Page->supv_empl_id->Visible) { // supv_empl_id ?>
        <th class="<?= $Page->supv_empl_id->headerCellClass() ?>"><span id="elh_cf_user_supv_empl_id" class="cf_user_supv_empl_id"><?= $Page->supv_empl_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->shift->Visible) { // shift ?>
        <th class="<?= $Page->shift->headerCellClass() ?>"><span id="elh_cf_user_shift" class="cf_user_shift"><?= $Page->shift->caption() ?></span></th>
<?php } ?>
<?php if ($Page->work_area->Visible) { // work_area ?>
        <th class="<?= $Page->work_area->headerCellClass() ?>"><span id="elh_cf_user_work_area" class="cf_user_work_area"><?= $Page->work_area->caption() ?></span></th>
<?php } ?>
<?php if ($Page->work_grp->Visible) { // work_grp ?>
        <th class="<?= $Page->work_grp->headerCellClass() ?>"><span id="elh_cf_user_work_grp" class="cf_user_work_grp"><?= $Page->work_grp->caption() ?></span></th>
<?php } ?>
<?php if ($Page->last_login_site->Visible) { // last_login_site ?>
        <th class="<?= $Page->last_login_site->headerCellClass() ?>"><span id="elh_cf_user_last_login_site" class="cf_user_last_login_site"><?= $Page->last_login_site->caption() ?></span></th>
<?php } ?>
<?php if ($Page->last_login->Visible) { // last_login ?>
        <th class="<?= $Page->last_login->headerCellClass() ?>"><span id="elh_cf_user_last_login" class="cf_user_last_login"><?= $Page->last_login->caption() ?></span></th>
<?php } ?>
<?php if ($Page->last_pwd_changed->Visible) { // last_pwd_changed ?>
        <th class="<?= $Page->last_pwd_changed->headerCellClass() ?>"><span id="elh_cf_user_last_pwd_changed" class="cf_user_last_pwd_changed"><?= $Page->last_pwd_changed->caption() ?></span></th>
<?php } ?>
<?php if ($Page->audit_user->Visible) { // audit_user ?>
        <th class="<?= $Page->audit_user->headerCellClass() ?>"><span id="elh_cf_user_audit_user" class="cf_user_audit_user"><?= $Page->audit_user->caption() ?></span></th>
<?php } ?>
<?php if ($Page->audit_date->Visible) { // audit_date ?>
        <th class="<?= $Page->audit_date->headerCellClass() ?>"><span id="elh_cf_user_audit_date" class="cf_user_audit_date"><?= $Page->audit_date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->RowID->Visible) { // RowID ?>
        <th class="<?= $Page->RowID->headerCellClass() ?>"><span id="elh_cf_user_RowID" class="cf_user_RowID"><?= $Page->RowID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->expired_date->Visible) { // expired_date ?>
        <th class="<?= $Page->expired_date->headerCellClass() ?>"><span id="elh_cf_user_expired_date" class="cf_user_expired_date"><?= $Page->expired_date->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while (!$Page->Recordset->EOF) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->Recordset);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->empl_id->Visible) { // empl_id ?>
        <td <?= $Page->empl_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_empl_id" class="cf_user_empl_id">
<span<?= $Page->empl_id->viewAttributes() ?>>
<?= $Page->empl_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->name->Visible) { // name ?>
        <td <?= $Page->name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_name" class="cf_user_name">
<span<?= $Page->name->viewAttributes() ?>>
<?= $Page->name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->default_site->Visible) { // default_site ?>
        <td <?= $Page->default_site->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_default_site" class="cf_user_default_site">
<span<?= $Page->default_site->viewAttributes() ?>>
<?= $Page->default_site->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->default_language->Visible) { // default_language ?>
        <td <?= $Page->default_language->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_default_language" class="cf_user_default_language">
<span<?= $Page->default_language->viewAttributes() ?>>
<?= $Page->default_language->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->sys_admin->Visible) { // sys_admin ?>
        <td <?= $Page->sys_admin->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_sys_admin" class="cf_user_sys_admin">
<span<?= $Page->sys_admin->viewAttributes() ?>>
<?= $Page->sys_admin->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <td <?= $Page->status->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_status" class="cf_user_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->supv_empl_id->Visible) { // supv_empl_id ?>
        <td <?= $Page->supv_empl_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_supv_empl_id" class="cf_user_supv_empl_id">
<span<?= $Page->supv_empl_id->viewAttributes() ?>>
<?= $Page->supv_empl_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->shift->Visible) { // shift ?>
        <td <?= $Page->shift->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_shift" class="cf_user_shift">
<span<?= $Page->shift->viewAttributes() ?>>
<?= $Page->shift->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->work_area->Visible) { // work_area ?>
        <td <?= $Page->work_area->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_work_area" class="cf_user_work_area">
<span<?= $Page->work_area->viewAttributes() ?>>
<?= $Page->work_area->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->work_grp->Visible) { // work_grp ?>
        <td <?= $Page->work_grp->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_work_grp" class="cf_user_work_grp">
<span<?= $Page->work_grp->viewAttributes() ?>>
<?= $Page->work_grp->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->last_login_site->Visible) { // last_login_site ?>
        <td <?= $Page->last_login_site->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_last_login_site" class="cf_user_last_login_site">
<span<?= $Page->last_login_site->viewAttributes() ?>>
<?= $Page->last_login_site->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->last_login->Visible) { // last_login ?>
        <td <?= $Page->last_login->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_last_login" class="cf_user_last_login">
<span<?= $Page->last_login->viewAttributes() ?>>
<?= $Page->last_login->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->last_pwd_changed->Visible) { // last_pwd_changed ?>
        <td <?= $Page->last_pwd_changed->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_last_pwd_changed" class="cf_user_last_pwd_changed">
<span<?= $Page->last_pwd_changed->viewAttributes() ?>>
<?= $Page->last_pwd_changed->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->audit_user->Visible) { // audit_user ?>
        <td <?= $Page->audit_user->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_audit_user" class="cf_user_audit_user">
<span<?= $Page->audit_user->viewAttributes() ?>>
<?= $Page->audit_user->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->audit_date->Visible) { // audit_date ?>
        <td <?= $Page->audit_date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_audit_date" class="cf_user_audit_date">
<span<?= $Page->audit_date->viewAttributes() ?>>
<?= $Page->audit_date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->RowID->Visible) { // RowID ?>
        <td <?= $Page->RowID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_RowID" class="cf_user_RowID">
<span<?= $Page->RowID->viewAttributes() ?>>
<?= $Page->RowID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->expired_date->Visible) { // expired_date ?>
        <td <?= $Page->expired_date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_expired_date" class="cf_user_expired_date">
<span<?= $Page->expired_date->viewAttributes() ?>>
<?= $Page->expired_date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
    $Page->Recordset->moveNext();
}
$Page->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
