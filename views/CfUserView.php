<?php

namespace PHPMaker2021\tooms;

// Page object
$CfUserView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcf_userview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fcf_userview = currentForm = new ew.Form("fcf_userview", "view");
    loadjs.done("fcf_userview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fcf_userview" id="fcf_userview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cf_user">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->empl_id->Visible) { // empl_id ?>
    <tr id="r_empl_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_empl_id"><?= $Page->empl_id->caption() ?></span></td>
        <td data-name="empl_id" <?= $Page->empl_id->cellAttributes() ?>>
<span id="el_cf_user_empl_id">
<span<?= $Page->empl_id->viewAttributes() ?>>
<?= $Page->empl_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->name->Visible) { // name ?>
    <tr id="r_name">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_name"><?= $Page->name->caption() ?></span></td>
        <td data-name="name" <?= $Page->name->cellAttributes() ?>>
<span id="el_cf_user_name">
<span<?= $Page->name->viewAttributes() ?>>
<?= $Page->name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->default_site->Visible) { // default_site ?>
    <tr id="r_default_site">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_default_site"><?= $Page->default_site->caption() ?></span></td>
        <td data-name="default_site" <?= $Page->default_site->cellAttributes() ?>>
<span id="el_cf_user_default_site">
<span<?= $Page->default_site->viewAttributes() ?>>
<?= $Page->default_site->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->default_language->Visible) { // default_language ?>
    <tr id="r_default_language">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_default_language"><?= $Page->default_language->caption() ?></span></td>
        <td data-name="default_language" <?= $Page->default_language->cellAttributes() ?>>
<span id="el_cf_user_default_language">
<span<?= $Page->default_language->viewAttributes() ?>>
<?= $Page->default_language->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sys_admin->Visible) { // sys_admin ?>
    <tr id="r_sys_admin">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_sys_admin"><?= $Page->sys_admin->caption() ?></span></td>
        <td data-name="sys_admin" <?= $Page->sys_admin->cellAttributes() ?>>
<span id="el_cf_user_sys_admin">
<span<?= $Page->sys_admin->viewAttributes() ?>>
<?= $Page->sys_admin->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <tr id="r_status">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_status"><?= $Page->status->caption() ?></span></td>
        <td data-name="status" <?= $Page->status->cellAttributes() ?>>
<span id="el_cf_user_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->supv_empl_id->Visible) { // supv_empl_id ?>
    <tr id="r_supv_empl_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_supv_empl_id"><?= $Page->supv_empl_id->caption() ?></span></td>
        <td data-name="supv_empl_id" <?= $Page->supv_empl_id->cellAttributes() ?>>
<span id="el_cf_user_supv_empl_id">
<span<?= $Page->supv_empl_id->viewAttributes() ?>>
<?= $Page->supv_empl_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->shift->Visible) { // shift ?>
    <tr id="r_shift">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_shift"><?= $Page->shift->caption() ?></span></td>
        <td data-name="shift" <?= $Page->shift->cellAttributes() ?>>
<span id="el_cf_user_shift">
<span<?= $Page->shift->viewAttributes() ?>>
<?= $Page->shift->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->work_area->Visible) { // work_area ?>
    <tr id="r_work_area">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_work_area"><?= $Page->work_area->caption() ?></span></td>
        <td data-name="work_area" <?= $Page->work_area->cellAttributes() ?>>
<span id="el_cf_user_work_area">
<span<?= $Page->work_area->viewAttributes() ?>>
<?= $Page->work_area->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->work_grp->Visible) { // work_grp ?>
    <tr id="r_work_grp">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_work_grp"><?= $Page->work_grp->caption() ?></span></td>
        <td data-name="work_grp" <?= $Page->work_grp->cellAttributes() ?>>
<span id="el_cf_user_work_grp">
<span<?= $Page->work_grp->viewAttributes() ?>>
<?= $Page->work_grp->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->last_login_site->Visible) { // last_login_site ?>
    <tr id="r_last_login_site">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_last_login_site"><?= $Page->last_login_site->caption() ?></span></td>
        <td data-name="last_login_site" <?= $Page->last_login_site->cellAttributes() ?>>
<span id="el_cf_user_last_login_site">
<span<?= $Page->last_login_site->viewAttributes() ?>>
<?= $Page->last_login_site->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->last_login->Visible) { // last_login ?>
    <tr id="r_last_login">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_last_login"><?= $Page->last_login->caption() ?></span></td>
        <td data-name="last_login" <?= $Page->last_login->cellAttributes() ?>>
<span id="el_cf_user_last_login">
<span<?= $Page->last_login->viewAttributes() ?>>
<?= $Page->last_login->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->last_pwd_changed->Visible) { // last_pwd_changed ?>
    <tr id="r_last_pwd_changed">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_last_pwd_changed"><?= $Page->last_pwd_changed->caption() ?></span></td>
        <td data-name="last_pwd_changed" <?= $Page->last_pwd_changed->cellAttributes() ?>>
<span id="el_cf_user_last_pwd_changed">
<span<?= $Page->last_pwd_changed->viewAttributes() ?>>
<?= $Page->last_pwd_changed->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->audit_user->Visible) { // audit_user ?>
    <tr id="r_audit_user">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_audit_user"><?= $Page->audit_user->caption() ?></span></td>
        <td data-name="audit_user" <?= $Page->audit_user->cellAttributes() ?>>
<span id="el_cf_user_audit_user">
<span<?= $Page->audit_user->viewAttributes() ?>>
<?= $Page->audit_user->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->audit_date->Visible) { // audit_date ?>
    <tr id="r_audit_date">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_audit_date"><?= $Page->audit_date->caption() ?></span></td>
        <td data-name="audit_date" <?= $Page->audit_date->cellAttributes() ?>>
<span id="el_cf_user_audit_date">
<span<?= $Page->audit_date->viewAttributes() ?>>
<?= $Page->audit_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->RowID->Visible) { // RowID ?>
    <tr id="r_RowID">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_RowID"><?= $Page->RowID->caption() ?></span></td>
        <td data-name="RowID" <?= $Page->RowID->cellAttributes() ?>>
<span id="el_cf_user_RowID">
<span<?= $Page->RowID->viewAttributes() ?>>
<?= $Page->RowID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->expired_date->Visible) { // expired_date ?>
    <tr id="r_expired_date">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_expired_date"><?= $Page->expired_date->caption() ?></span></td>
        <td data-name="expired_date" <?= $Page->expired_date->cellAttributes() ?>>
<span id="el_cf_user_expired_date">
<span<?= $Page->expired_date->viewAttributes() ?>>
<?= $Page->expired_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->column1->Visible) { // column1 ?>
    <tr id="r_column1">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_column1"><?= $Page->column1->caption() ?></span></td>
        <td data-name="column1" <?= $Page->column1->cellAttributes() ?>>
<span id="el_cf_user_column1">
<span<?= $Page->column1->viewAttributes() ?>>
<?= $Page->column1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->column2->Visible) { // column2 ?>
    <tr id="r_column2">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_column2"><?= $Page->column2->caption() ?></span></td>
        <td data-name="column2" <?= $Page->column2->cellAttributes() ?>>
<span id="el_cf_user_column2">
<span<?= $Page->column2->viewAttributes() ?>>
<?= $Page->column2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->column3->Visible) { // column3 ?>
    <tr id="r_column3">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_column3"><?= $Page->column3->caption() ?></span></td>
        <td data-name="column3" <?= $Page->column3->cellAttributes() ?>>
<span id="el_cf_user_column3">
<span<?= $Page->column3->viewAttributes() ?>>
<?= $Page->column3->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->column4->Visible) { // column4 ?>
    <tr id="r_column4">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_column4"><?= $Page->column4->caption() ?></span></td>
        <td data-name="column4" <?= $Page->column4->cellAttributes() ?>>
<span id="el_cf_user_column4">
<span<?= $Page->column4->viewAttributes() ?>>
<?= $Page->column4->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->column5->Visible) { // column5 ?>
    <tr id="r_column5">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_column5"><?= $Page->column5->caption() ?></span></td>
        <td data-name="column5" <?= $Page->column5->cellAttributes() ?>>
<span id="el_cf_user_column5">
<span<?= $Page->column5->viewAttributes() ?>>
<?= $Page->column5->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cf_user_failed_attempt->Visible) { // cf_user_failed_attempt ?>
    <tr id="r_cf_user_failed_attempt">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_cf_user_failed_attempt"><?= $Page->cf_user_failed_attempt->caption() ?></span></td>
        <td data-name="cf_user_failed_attempt" <?= $Page->cf_user_failed_attempt->cellAttributes() ?>>
<span id="el_cf_user_cf_user_failed_attempt">
<span<?= $Page->cf_user_failed_attempt->viewAttributes() ?>>
<?= $Page->cf_user_failed_attempt->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cf_user_locked->Visible) { // cf_user_locked ?>
    <tr id="r_cf_user_locked">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_cf_user_locked"><?= $Page->cf_user_locked->caption() ?></span></td>
        <td data-name="cf_user_locked" <?= $Page->cf_user_locked->cellAttributes() ?>>
<span id="el_cf_user_cf_user_locked">
<span<?= $Page->cf_user_locked->viewAttributes() ?>>
<?= $Page->cf_user_locked->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_password->Visible) { // password ?>
    <tr id="r__password">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user__password"><?= $Page->_password->caption() ?></span></td>
        <td data-name="_password" <?= $Page->_password->cellAttributes() ?>>
<span id="el_cf_user__password">
<span<?= $Page->_password->viewAttributes() ?>>
<?= $Page->_password->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->user_password->Visible) { // user_password ?>
    <tr id="r_user_password">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cf_user_user_password"><?= $Page->user_password->caption() ?></span></td>
        <td data-name="user_password" <?= $Page->user_password->cellAttributes() ?>>
<span id="el_cf_user_user_password">
<span<?= $Page->user_password->viewAttributes() ?>>
<?= $Page->user_password->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
