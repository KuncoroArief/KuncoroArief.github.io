<?php

namespace PHPMaker2021\tooms;

// Page object
$CfUserList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcf_userlist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fcf_userlist = currentForm = new ew.Form("fcf_userlist", "list");
    fcf_userlist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fcf_userlist");
});
var fcf_userlistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fcf_userlistsrch = currentSearchForm = new ew.Form("fcf_userlistsrch");

    // Dynamic selection lists

    // Filters
    fcf_userlistsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fcf_userlistsrch");
});
</script>
<style type="text/css">
.ew-table-preview-row { /* main table preview row color */
    background-color: #FFFFFF; /* preview row color */
}
.ew-table-preview-row .ew-grid {
    display: table;
}
</style>
<div id="ew-preview" class="d-none"><!-- preview -->
    <div class="ew-nav-tabs"><!-- .ew-nav-tabs -->
        <ul class="nav nav-tabs"></ul>
        <div class="tab-content"><!-- .tab-content -->
            <div class="tab-pane fade active show"></div>
        </div><!-- /.tab-content -->
    </div><!-- /.ew-nav-tabs -->
</div><!-- /preview -->
<script>
loadjs.ready("head", function() {
    ew.PREVIEW_PLACEMENT = ew.CSS_FLIP ? "right" : "left";
    ew.PREVIEW_SINGLE_ROW = false;
    ew.PREVIEW_OVERLAY = false;
    loadjs(ew.PATH_BASE + "js/ewpreview.js", "preview");
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
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction) { ?>
<form name="fcf_userlistsrch" id="fcf_userlistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl() ?>">
<div id="fcf_userlistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="cf_user">
    <div class="ew-extended-search">
<div id="xsr_<?= $Page->SearchRowCount + 1 ?>" class="ew-row d-sm-flex">
    <div class="ew-quick-search input-group">
        <input type="text" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>">
        <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
        <div class="input-group-append">
            <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
            <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false"><span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span></button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this);"><?= $Language->phrase("QuickSearchAuto") ?></a>
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "=") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, '=');"><?= $Language->phrase("QuickSearchExact") ?></a>
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "AND") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, 'AND');"><?= $Language->phrase("QuickSearchAll") ?></a>
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "OR") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, 'OR');"><?= $Language->phrase("QuickSearchAny") ?></a>
            </div>
        </div>
    </div>
</div>
    </div><!-- /.ew-extended-search -->
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> cf_user">
<form name="fcf_userlist" id="fcf_userlist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cf_user">
<div id="gmp_cf_user" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_cf_userlist" class="table ew-table"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = ROWTYPE_HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->empl_id->Visible) { // empl_id ?>
        <th data-name="empl_id" class="<?= $Page->empl_id->headerCellClass() ?>"><div id="elh_cf_user_empl_id" class="cf_user_empl_id"><?= $Page->renderSort($Page->empl_id) ?></div></th>
<?php } ?>
<?php if ($Page->name->Visible) { // name ?>
        <th data-name="name" class="<?= $Page->name->headerCellClass() ?>"><div id="elh_cf_user_name" class="cf_user_name"><?= $Page->renderSort($Page->name) ?></div></th>
<?php } ?>
<?php if ($Page->default_site->Visible) { // default_site ?>
        <th data-name="default_site" class="<?= $Page->default_site->headerCellClass() ?>"><div id="elh_cf_user_default_site" class="cf_user_default_site"><?= $Page->renderSort($Page->default_site) ?></div></th>
<?php } ?>
<?php if ($Page->default_language->Visible) { // default_language ?>
        <th data-name="default_language" class="<?= $Page->default_language->headerCellClass() ?>"><div id="elh_cf_user_default_language" class="cf_user_default_language"><?= $Page->renderSort($Page->default_language) ?></div></th>
<?php } ?>
<?php if ($Page->sys_admin->Visible) { // sys_admin ?>
        <th data-name="sys_admin" class="<?= $Page->sys_admin->headerCellClass() ?>"><div id="elh_cf_user_sys_admin" class="cf_user_sys_admin"><?= $Page->renderSort($Page->sys_admin) ?></div></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th data-name="status" class="<?= $Page->status->headerCellClass() ?>"><div id="elh_cf_user_status" class="cf_user_status"><?= $Page->renderSort($Page->status) ?></div></th>
<?php } ?>
<?php if ($Page->supv_empl_id->Visible) { // supv_empl_id ?>
        <th data-name="supv_empl_id" class="<?= $Page->supv_empl_id->headerCellClass() ?>"><div id="elh_cf_user_supv_empl_id" class="cf_user_supv_empl_id"><?= $Page->renderSort($Page->supv_empl_id) ?></div></th>
<?php } ?>
<?php if ($Page->shift->Visible) { // shift ?>
        <th data-name="shift" class="<?= $Page->shift->headerCellClass() ?>"><div id="elh_cf_user_shift" class="cf_user_shift"><?= $Page->renderSort($Page->shift) ?></div></th>
<?php } ?>
<?php if ($Page->work_area->Visible) { // work_area ?>
        <th data-name="work_area" class="<?= $Page->work_area->headerCellClass() ?>"><div id="elh_cf_user_work_area" class="cf_user_work_area"><?= $Page->renderSort($Page->work_area) ?></div></th>
<?php } ?>
<?php if ($Page->work_grp->Visible) { // work_grp ?>
        <th data-name="work_grp" class="<?= $Page->work_grp->headerCellClass() ?>"><div id="elh_cf_user_work_grp" class="cf_user_work_grp"><?= $Page->renderSort($Page->work_grp) ?></div></th>
<?php } ?>
<?php if ($Page->last_login_site->Visible) { // last_login_site ?>
        <th data-name="last_login_site" class="<?= $Page->last_login_site->headerCellClass() ?>"><div id="elh_cf_user_last_login_site" class="cf_user_last_login_site"><?= $Page->renderSort($Page->last_login_site) ?></div></th>
<?php } ?>
<?php if ($Page->last_login->Visible) { // last_login ?>
        <th data-name="last_login" class="<?= $Page->last_login->headerCellClass() ?>"><div id="elh_cf_user_last_login" class="cf_user_last_login"><?= $Page->renderSort($Page->last_login) ?></div></th>
<?php } ?>
<?php if ($Page->last_pwd_changed->Visible) { // last_pwd_changed ?>
        <th data-name="last_pwd_changed" class="<?= $Page->last_pwd_changed->headerCellClass() ?>"><div id="elh_cf_user_last_pwd_changed" class="cf_user_last_pwd_changed"><?= $Page->renderSort($Page->last_pwd_changed) ?></div></th>
<?php } ?>
<?php if ($Page->audit_user->Visible) { // audit_user ?>
        <th data-name="audit_user" class="<?= $Page->audit_user->headerCellClass() ?>"><div id="elh_cf_user_audit_user" class="cf_user_audit_user"><?= $Page->renderSort($Page->audit_user) ?></div></th>
<?php } ?>
<?php if ($Page->audit_date->Visible) { // audit_date ?>
        <th data-name="audit_date" class="<?= $Page->audit_date->headerCellClass() ?>"><div id="elh_cf_user_audit_date" class="cf_user_audit_date"><?= $Page->renderSort($Page->audit_date) ?></div></th>
<?php } ?>
<?php if ($Page->RowID->Visible) { // RowID ?>
        <th data-name="RowID" class="<?= $Page->RowID->headerCellClass() ?>"><div id="elh_cf_user_RowID" class="cf_user_RowID"><?= $Page->renderSort($Page->RowID) ?></div></th>
<?php } ?>
<?php if ($Page->expired_date->Visible) { // expired_date ?>
        <th data-name="expired_date" class="<?= $Page->expired_date->headerCellClass() ?>"><div id="elh_cf_user_expired_date" class="cf_user_expired_date"><?= $Page->renderSort($Page->expired_date) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody>
<?php
if ($Page->ExportAll && $Page->isExport()) {
    $Page->StopRecord = $Page->TotalRecords;
} else {
    // Set the last record to display
    if ($Page->TotalRecords > $Page->StartRecord + $Page->DisplayRecords - 1) {
        $Page->StopRecord = $Page->StartRecord + $Page->DisplayRecords - 1;
    } else {
        $Page->StopRecord = $Page->TotalRecords;
    }
}
$Page->RecordCount = $Page->StartRecord - 1;
if ($Page->Recordset && !$Page->Recordset->EOF) {
    // Nothing to do
} elseif (!$Page->AllowAddDeleteRow && $Page->StopRecord == 0) {
    $Page->StopRecord = $Page->GridAddRowCount;
}

// Initialize aggregate
$Page->RowType = ROWTYPE_AGGREGATEINIT;
$Page->resetAttributes();
$Page->renderRow();
while ($Page->RecordCount < $Page->StopRecord) {
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->RowCount++;

        // Set up key count
        $Page->KeyCount = $Page->RowIndex;

        // Init row class and style
        $Page->resetAttributes();
        $Page->CssClass = "";
        if ($Page->isGridAdd()) {
            $Page->loadRowValues(); // Load default values
            $Page->OldKey = "";
            $Page->setKey($Page->OldKey);
        } else {
            $Page->loadRowValues($Page->Recordset); // Load row values
            if ($Page->isGridEdit()) {
                $Page->OldKey = $Page->getKey(true); // Get from CurrentValue
                $Page->setKey($Page->OldKey);
            }
        }
        $Page->RowType = ROWTYPE_VIEW; // Render view

        // Set up row id / data-rowindex
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_cf_user", "data-rowtype" => $Page->RowType]);

        // Render row
        $Page->renderRow();

        // Render list options
        $Page->renderListOptions();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->empl_id->Visible) { // empl_id ?>
        <td data-name="empl_id" <?= $Page->empl_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_empl_id">
<span<?= $Page->empl_id->viewAttributes() ?>>
<?= $Page->empl_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->name->Visible) { // name ?>
        <td data-name="name" <?= $Page->name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_name">
<span<?= $Page->name->viewAttributes() ?>>
<?= $Page->name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->default_site->Visible) { // default_site ?>
        <td data-name="default_site" <?= $Page->default_site->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_default_site">
<span<?= $Page->default_site->viewAttributes() ?>>
<?= $Page->default_site->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->default_language->Visible) { // default_language ?>
        <td data-name="default_language" <?= $Page->default_language->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_default_language">
<span<?= $Page->default_language->viewAttributes() ?>>
<?= $Page->default_language->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->sys_admin->Visible) { // sys_admin ?>
        <td data-name="sys_admin" <?= $Page->sys_admin->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_sys_admin">
<span<?= $Page->sys_admin->viewAttributes() ?>>
<?= $Page->sys_admin->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->status->Visible) { // status ?>
        <td data-name="status" <?= $Page->status->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->supv_empl_id->Visible) { // supv_empl_id ?>
        <td data-name="supv_empl_id" <?= $Page->supv_empl_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_supv_empl_id">
<span<?= $Page->supv_empl_id->viewAttributes() ?>>
<?= $Page->supv_empl_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->shift->Visible) { // shift ?>
        <td data-name="shift" <?= $Page->shift->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_shift">
<span<?= $Page->shift->viewAttributes() ?>>
<?= $Page->shift->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->work_area->Visible) { // work_area ?>
        <td data-name="work_area" <?= $Page->work_area->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_work_area">
<span<?= $Page->work_area->viewAttributes() ?>>
<?= $Page->work_area->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->work_grp->Visible) { // work_grp ?>
        <td data-name="work_grp" <?= $Page->work_grp->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_work_grp">
<span<?= $Page->work_grp->viewAttributes() ?>>
<?= $Page->work_grp->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->last_login_site->Visible) { // last_login_site ?>
        <td data-name="last_login_site" <?= $Page->last_login_site->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_last_login_site">
<span<?= $Page->last_login_site->viewAttributes() ?>>
<?= $Page->last_login_site->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->last_login->Visible) { // last_login ?>
        <td data-name="last_login" <?= $Page->last_login->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_last_login">
<span<?= $Page->last_login->viewAttributes() ?>>
<?= $Page->last_login->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->last_pwd_changed->Visible) { // last_pwd_changed ?>
        <td data-name="last_pwd_changed" <?= $Page->last_pwd_changed->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_last_pwd_changed">
<span<?= $Page->last_pwd_changed->viewAttributes() ?>>
<?= $Page->last_pwd_changed->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->audit_user->Visible) { // audit_user ?>
        <td data-name="audit_user" <?= $Page->audit_user->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_audit_user">
<span<?= $Page->audit_user->viewAttributes() ?>>
<?= $Page->audit_user->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->audit_date->Visible) { // audit_date ?>
        <td data-name="audit_date" <?= $Page->audit_date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_audit_date">
<span<?= $Page->audit_date->viewAttributes() ?>>
<?= $Page->audit_date->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->RowID->Visible) { // RowID ?>
        <td data-name="RowID" <?= $Page->RowID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_RowID">
<span<?= $Page->RowID->viewAttributes() ?>>
<?= $Page->RowID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->expired_date->Visible) { // expired_date ?>
        <td data-name="expired_date" <?= $Page->expired_date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cf_user_expired_date">
<span<?= $Page->expired_date->viewAttributes() ?>>
<?= $Page->expired_date->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    }
    if (!$Page->isGridAdd()) {
        $Page->Recordset->moveNext();
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if (!$Page->CurrentAction) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Page->Recordset) {
    $Page->Recordset->close();
}
?>
<?php if (!$Page->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$Page->isGridAdd()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl() ?>">
<?= $Page->Pager->render() ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } ?>
<?php if ($Page->TotalRecords == 0 && !$Page->CurrentAction) { // Show other options ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
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
<?php } ?>
