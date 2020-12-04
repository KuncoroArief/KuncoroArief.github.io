<?php

namespace PHPMaker2021\tooms;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class CfUserDelete extends CfUser
{
    use MessagesTrait;

    // Page ID
    public $PageID = "delete";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'cf_user';

    // Page object name
    public $PageObjName = "CfUserDelete";

    // Rendering View
    public $RenderingView = false;

    // Page headings
    public $Heading = "";
    public $Subheading = "";
    public $PageHeader;
    public $PageFooter;

    // Page terminated
    private $terminated = false;

    // Page heading
    public function pageHeading()
    {
        global $Language;
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading()
    {
        global $Language;
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        if ($this->TableName) {
            return $Language->phrase($this->PageID);
        }
        return "";
    }

    // Page name
    public function pageName()
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl()
    {
        $url = ScriptName() . "?";
        if ($this->UseTokenInUrl) {
            $url .= "t=" . $this->TableVar . "&"; // Add page token
        }
        return $url;
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<p id="ew-page-header">' . $header . '</p>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<p id="ew-page-footer">' . $footer . '</p>';
        }
    }

    // Validate page request
    protected function isPageRequest()
    {
        global $CurrentForm;
        if ($this->UseTokenInUrl) {
            if ($CurrentForm) {
                return ($this->TableVar == $CurrentForm->getValue("t"));
            }
            if (Get("t") !== null) {
                return ($this->TableVar == Get("t"));
            }
        }
        return true;
    }

    // Constructor
    public function __construct()
    {
        global $Language, $DashboardReport, $DebugTimer;
        global $UserTable;

        // Initialize
        $GLOBALS["Page"] = &$this;
        $this->TokenTimeout = SessionTimeoutTime();

        // Language object
        $Language = Container("language");

        // Parent constuctor
        parent::__construct();

        // Table object (cf_user)
        if (!isset($GLOBALS["cf_user"]) || get_class($GLOBALS["cf_user"]) == PROJECT_NAMESPACE . "cf_user") {
            $GLOBALS["cf_user"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'cf_user');
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] = $GLOBALS["Conn"] ?? $this->getConnection();

        // User table object
        $UserTable = Container("usertable");
    }

    // Get content from stream
    public function getContents($stream = null): string
    {
        global $Response;
        return is_object($Response) ? $Response->getBody() : ob_get_clean();
    }

    // Is terminated
    public function isTerminated()
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string $url URL for direction
     * @return void
     */
    public function terminate($url = "")
    {
        if ($this->terminated) {
            return;
        }
        global $ExportFileName, $TempImages, $DashboardReport;

        // Page is terminated
        $this->terminated = true;

         // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }

        // Global Page Unloaded event (in userfn*.php)
        Page_Unloaded();

        // Export
        if ($this->CustomExport && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, Config("EXPORT_CLASSES"))) {
            $content = $this->getContents();
            if ($ExportFileName == "") {
                $ExportFileName = $this->TableVar;
            }
            $class = PROJECT_NAMESPACE . Config("EXPORT_CLASSES." . $this->CustomExport);
            if (class_exists($class)) {
                $doc = new $class(Container("cf_user"));
                $doc->Text = @$content;
                if ($this->isExport("email")) {
                    echo $this->exportEmail($doc->Text);
                } else {
                    $doc->export();
                }
                DeleteTempImages(); // Delete temp images
                return;
            }
        }
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection
        CloseConnections();

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show error
                WriteJson(array_merge(["success" => false], $this->getMessages()));
            }
            return;
        }

        // Go to URL if specified
        if ($url != "") {
            if (!Config("DEBUG") && ob_get_length()) {
                ob_end_clean();
            }
            SaveDebugMessage();
            Redirect(GetUrl($url));
        }
        return; // Return to controller
    }

    // Get records from recordset
    protected function getRecordsFromRecordset($rs, $current = false)
    {
        $rows = [];
        if (is_object($rs)) { // Recordset
            while ($rs && !$rs->EOF) {
                $this->loadRowValues($rs); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($rs->fields);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
                $rs->moveNext();
            }
        } elseif (is_array($rs)) {
            foreach ($rs as $ar) {
                $row = $this->getRecordFromArray($ar);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get record from array
    protected function getRecordFromArray($ar)
    {
        $row = [];
        if (is_array($ar)) {
            foreach ($ar as $fldname => $val) {
                if (array_key_exists($fldname, $this->Fields) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                    $fld = &$this->Fields[$fldname];
                    if ($fld->HtmlTag == "FILE") { // Upload field
                        if (EmptyValue($val)) {
                            $row[$fldname] = null;
                        } else {
                            if ($fld->DataType == DATATYPE_BLOB) {
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . $fld->Param . "/" . rawurlencode($this->getRecordKeyValue($ar))));
                                $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                            } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $val)));
                                $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                            } else { // Multiple files
                                $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                                $ar = [];
                                foreach ($files as $file) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                                    if (!EmptyValue($file)) {
                                        $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
                                    }
                                }
                                $row[$fldname] = $ar;
                            }
                        }
                    } else {
                        $row[$fldname] = $val;
                    }
                }
            }
        }
        return $row;
    }

    // Get record key value from array
    protected function getRecordKeyValue($ar)
    {
        $key = "";
        if (is_array($ar)) {
            $key .= @$ar['empl_id'];
        }
        return $key;
    }

    /**
     * Hide fields for add/edit
     *
     * @return void
     */
    protected function hideFieldsForAddEdit()
    {
        if ($this->isAddOrEdit()) {
            $this->RowID->Visible = false;
        }
    }
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $TotalRecords = 0;
    public $RecordCount;
    public $RecKeys = [];
    public $StartRowCount = 1;
    public $RowCount = 0;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm;
        $this->CurrentAction = Param("action"); // Set up current action
        $this->empl_id->setVisibility();
        $this->name->setVisibility();
        $this->default_site->setVisibility();
        $this->default_language->setVisibility();
        $this->sys_admin->setVisibility();
        $this->status->setVisibility();
        $this->supv_empl_id->setVisibility();
        $this->shift->setVisibility();
        $this->work_area->setVisibility();
        $this->work_grp->setVisibility();
        $this->last_login_site->setVisibility();
        $this->last_login->setVisibility();
        $this->last_pwd_changed->setVisibility();
        $this->audit_user->setVisibility();
        $this->audit_date->setVisibility();
        $this->RowID->setVisibility();
        $this->expired_date->setVisibility();
        $this->column1->Visible = false;
        $this->column2->Visible = false;
        $this->column3->Visible = false;
        $this->column4->Visible = false;
        $this->column5->Visible = false;
        $this->cf_user_failed_attempt->Visible = false;
        $this->cf_user_locked->Visible = false;
        $this->_password->Visible = false;
        $this->user_password->Visible = false;
        $this->hideFieldsForAddEdit();

        // Do not use lookup cache
        $this->setUseLookupCache(false);

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Set up lookup cache

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Load key parameters
        $this->RecKeys = $this->getRecordKeys(); // Load record keys
        $filter = $this->getFilterFromRecordKeys();
        if ($filter == "") {
            $this->terminate("CfUserList"); // Prevent SQL injection, return to list
            return;
        }

        // Set up filter (WHERE Clause)
        $this->CurrentFilter = $filter;

        // Get action
        if (IsApi()) {
            $this->CurrentAction = "delete"; // Delete record directly
        } elseif (Post("action") !== null) {
            $this->CurrentAction = Post("action");
        } elseif (Get("action") == "1") {
            $this->CurrentAction = "delete"; // Delete record directly
        } else {
            $this->CurrentAction = "show"; // Display record
        }
        if ($this->isDelete()) {
            $this->SendEmail = true; // Send email on delete success
            if ($this->deleteRows()) { // Delete rows
                if ($this->getSuccessMessage() == "") {
                    $this->setSuccessMessage($Language->phrase("DeleteSuccess")); // Set up success message
                }
                if (IsApi()) {
                    $this->terminate(true);
                    return;
                } else {
                    $this->terminate($this->getReturnUrl()); // Return to caller
                    return;
                }
            } else { // Delete failed
                if (IsApi()) {
                    $this->terminate();
                    return;
                }
                $this->CurrentAction = "show"; // Display record
            }
        }
        if ($this->isShow()) { // Load records for display
            if ($this->Recordset = $this->loadRecordset()) {
                $this->TotalRecords = $this->Recordset->recordCount(); // Get record count
            }
            if ($this->TotalRecords <= 0) { // No record found, exit
                if ($this->Recordset) {
                    $this->Recordset->close();
                }
                $this->terminate("CfUserList"); // Return to list
                return;
            }
        }

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Pass table and field properties to client side
            $this->toClientVar(["tableCaption"], ["caption", "Required", "IsInvalid", "Raw"]);

            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            Page_Rendering();

            // Page Rendering event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }
        }
    }

    // Load recordset
    public function loadRecordset($offset = -1, $rowcnt = -1)
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load recordset
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $stmt = $sql->execute();
        $rs = new Recordset($stmt, $sql);

        // Call Recordset Selected event
        $this->recordsetSelected($rs);
        return $rs;
    }

    /**
     * Load row based on key values
     *
     * @return void
     */
    public function loadRow()
    {
        global $Security, $Language;
        $filter = $this->getRecordFilter();

        // Call Row Selecting event
        $this->rowSelecting($filter);

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $res = false;
        $row = $conn->fetchAssoc($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }
        return $res;
    }

    /**
     * Load row values from recordset or record
     *
     * @param Recordset|array $rs Record
     * @return void
     */
    public function loadRowValues($rs = null)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            $row = $this->newRow();
        }

        // Call Row Selected event
        $this->rowSelected($row);
        if (!$rs) {
            return;
        }
        $this->empl_id->setDbValue($row['empl_id']);
        $this->name->setDbValue($row['name']);
        $this->default_site->setDbValue($row['default_site']);
        $this->default_language->setDbValue($row['default_language']);
        $this->sys_admin->setDbValue($row['sys_admin']);
        $this->status->setDbValue($row['status']);
        $this->supv_empl_id->setDbValue($row['supv_empl_id']);
        $this->shift->setDbValue($row['shift']);
        $this->work_area->setDbValue($row['work_area']);
        $this->work_grp->setDbValue($row['work_grp']);
        $this->last_login_site->setDbValue($row['last_login_site']);
        $this->last_login->setDbValue($row['last_login']);
        $this->last_pwd_changed->setDbValue($row['last_pwd_changed']);
        $this->audit_user->setDbValue($row['audit_user']);
        $this->audit_date->setDbValue($row['audit_date']);
        $this->RowID->setDbValue($row['RowID']);
        $this->expired_date->setDbValue($row['expired_date']);
        $this->column1->setDbValue($row['column1']);
        $this->column2->setDbValue($row['column2']);
        $this->column3->setDbValue($row['column3']);
        $this->column4->setDbValue($row['column4']);
        $this->column5->setDbValue($row['column5']);
        $this->cf_user_failed_attempt->setDbValue($row['cf_user_failed_attempt']);
        $this->cf_user_locked->setDbValue($row['cf_user_locked']);
        $this->_password->setDbValue($row['password']);
        $this->user_password->setDbValue($row['user_password']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['empl_id'] = null;
        $row['name'] = null;
        $row['default_site'] = null;
        $row['default_language'] = null;
        $row['sys_admin'] = null;
        $row['status'] = null;
        $row['supv_empl_id'] = null;
        $row['shift'] = null;
        $row['work_area'] = null;
        $row['work_grp'] = null;
        $row['last_login_site'] = null;
        $row['last_login'] = null;
        $row['last_pwd_changed'] = null;
        $row['audit_user'] = null;
        $row['audit_date'] = null;
        $row['RowID'] = null;
        $row['expired_date'] = null;
        $row['column1'] = null;
        $row['column2'] = null;
        $row['column3'] = null;
        $row['column4'] = null;
        $row['column5'] = null;
        $row['cf_user_failed_attempt'] = null;
        $row['cf_user_locked'] = null;
        $row['password'] = null;
        $row['user_password'] = null;
        return $row;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // empl_id

        // name

        // default_site

        // default_language

        // sys_admin

        // status

        // supv_empl_id

        // shift

        // work_area

        // work_grp

        // last_login_site

        // last_login

        // last_pwd_changed

        // audit_user

        // audit_date

        // RowID

        // expired_date

        // column1
        $this->column1->CellCssStyle = "white-space: nowrap;";

        // column2
        $this->column2->CellCssStyle = "white-space: nowrap;";

        // column3
        $this->column3->CellCssStyle = "white-space: nowrap;";

        // column4
        $this->column4->CellCssStyle = "white-space: nowrap;";

        // column5
        $this->column5->CellCssStyle = "white-space: nowrap;";

        // cf_user_failed_attempt
        $this->cf_user_failed_attempt->CellCssStyle = "white-space: nowrap;";

        // cf_user_locked
        $this->cf_user_locked->CellCssStyle = "white-space: nowrap;";

        // password
        $this->_password->CellCssStyle = "white-space: nowrap;";

        // user_password
        $this->user_password->CellCssStyle = "white-space: nowrap;";
        if ($this->RowType == ROWTYPE_VIEW) {
            // empl_id
            $this->empl_id->ViewValue = $this->empl_id->CurrentValue;
            $this->empl_id->ViewCustomAttributes = "";

            // name
            $this->name->ViewValue = $this->name->CurrentValue;
            $this->name->ViewCustomAttributes = "";

            // default_site
            $this->default_site->ViewValue = $this->default_site->CurrentValue;
            $this->default_site->ViewCustomAttributes = "";

            // default_language
            $this->default_language->ViewValue = $this->default_language->CurrentValue;
            $this->default_language->ViewCustomAttributes = "";

            // sys_admin
            $this->sys_admin->ViewValue = $this->sys_admin->CurrentValue;
            $this->sys_admin->ViewCustomAttributes = "";

            // status
            $this->status->ViewValue = $this->status->CurrentValue;
            $this->status->ViewCustomAttributes = "";

            // supv_empl_id
            $this->supv_empl_id->ViewValue = $this->supv_empl_id->CurrentValue;
            $this->supv_empl_id->ViewCustomAttributes = "";

            // shift
            $this->shift->ViewValue = $this->shift->CurrentValue;
            $this->shift->ViewCustomAttributes = "";

            // work_area
            $this->work_area->ViewValue = $this->work_area->CurrentValue;
            $this->work_area->ViewCustomAttributes = "";

            // work_grp
            $this->work_grp->ViewValue = $this->work_grp->CurrentValue;
            $this->work_grp->ViewCustomAttributes = "";

            // last_login_site
            $this->last_login_site->ViewValue = $this->last_login_site->CurrentValue;
            $this->last_login_site->ViewCustomAttributes = "";

            // last_login
            $this->last_login->ViewValue = $this->last_login->CurrentValue;
            $this->last_login->ViewValue = FormatDateTime($this->last_login->ViewValue, 0);
            $this->last_login->ViewCustomAttributes = "";

            // last_pwd_changed
            $this->last_pwd_changed->ViewValue = $this->last_pwd_changed->CurrentValue;
            $this->last_pwd_changed->ViewValue = FormatDateTime($this->last_pwd_changed->ViewValue, 0);
            $this->last_pwd_changed->ViewCustomAttributes = "";

            // audit_user
            $this->audit_user->ViewValue = $this->audit_user->CurrentValue;
            $this->audit_user->ViewCustomAttributes = "";

            // audit_date
            $this->audit_date->ViewValue = $this->audit_date->CurrentValue;
            $this->audit_date->ViewValue = FormatDateTime($this->audit_date->ViewValue, 0);
            $this->audit_date->ViewCustomAttributes = "";

            // RowID
            $this->RowID->ViewValue = $this->RowID->CurrentValue;
            $this->RowID->ViewCustomAttributes = "";

            // expired_date
            $this->expired_date->ViewValue = $this->expired_date->CurrentValue;
            $this->expired_date->ViewValue = FormatDateTime($this->expired_date->ViewValue, 0);
            $this->expired_date->ViewCustomAttributes = "";

            // empl_id
            $this->empl_id->LinkCustomAttributes = "";
            $this->empl_id->HrefValue = "";
            $this->empl_id->TooltipValue = "";

            // name
            $this->name->LinkCustomAttributes = "";
            $this->name->HrefValue = "";
            $this->name->TooltipValue = "";

            // default_site
            $this->default_site->LinkCustomAttributes = "";
            $this->default_site->HrefValue = "";
            $this->default_site->TooltipValue = "";

            // default_language
            $this->default_language->LinkCustomAttributes = "";
            $this->default_language->HrefValue = "";
            $this->default_language->TooltipValue = "";

            // sys_admin
            $this->sys_admin->LinkCustomAttributes = "";
            $this->sys_admin->HrefValue = "";
            $this->sys_admin->TooltipValue = "";

            // status
            $this->status->LinkCustomAttributes = "";
            $this->status->HrefValue = "";
            $this->status->TooltipValue = "";

            // supv_empl_id
            $this->supv_empl_id->LinkCustomAttributes = "";
            $this->supv_empl_id->HrefValue = "";
            $this->supv_empl_id->TooltipValue = "";

            // shift
            $this->shift->LinkCustomAttributes = "";
            $this->shift->HrefValue = "";
            $this->shift->TooltipValue = "";

            // work_area
            $this->work_area->LinkCustomAttributes = "";
            $this->work_area->HrefValue = "";
            $this->work_area->TooltipValue = "";

            // work_grp
            $this->work_grp->LinkCustomAttributes = "";
            $this->work_grp->HrefValue = "";
            $this->work_grp->TooltipValue = "";

            // last_login_site
            $this->last_login_site->LinkCustomAttributes = "";
            $this->last_login_site->HrefValue = "";
            $this->last_login_site->TooltipValue = "";

            // last_login
            $this->last_login->LinkCustomAttributes = "";
            $this->last_login->HrefValue = "";
            $this->last_login->TooltipValue = "";

            // last_pwd_changed
            $this->last_pwd_changed->LinkCustomAttributes = "";
            $this->last_pwd_changed->HrefValue = "";
            $this->last_pwd_changed->TooltipValue = "";

            // audit_user
            $this->audit_user->LinkCustomAttributes = "";
            $this->audit_user->HrefValue = "";
            $this->audit_user->TooltipValue = "";

            // audit_date
            $this->audit_date->LinkCustomAttributes = "";
            $this->audit_date->HrefValue = "";
            $this->audit_date->TooltipValue = "";

            // RowID
            $this->RowID->LinkCustomAttributes = "";
            $this->RowID->HrefValue = "";
            $this->RowID->TooltipValue = "";

            // expired_date
            $this->expired_date->LinkCustomAttributes = "";
            $this->expired_date->HrefValue = "";
            $this->expired_date->TooltipValue = "";
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Delete records based on current filter
    protected function deleteRows()
    {
        global $Language, $Security;
        if (!$Security->canDelete()) {
            $this->setFailureMessage($Language->phrase("NoDeletePermission")); // No delete permission
            return false;
        }
        $deleteRows = true;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $rows = $conn->fetchAll($sql);
        if (count($rows) == 0) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
            return false;
        }
        $conn->beginTransaction();

        // Clone old rows
        $rsold = $rows;

        // Call row deleting event
        if ($deleteRows) {
            foreach ($rsold as $row) {
                $deleteRows = $this->rowDeleting($row);
                if (!$deleteRows) {
                    break;
                }
            }
        }
        if ($deleteRows) {
            $key = "";
            foreach ($rsold as $row) {
                $thisKey = "";
                if ($thisKey != "") {
                    $thisKey .= Config("COMPOSITE_KEY_SEPARATOR");
                }
                $thisKey .= $row['empl_id'];
                if (Config("DELETE_UPLOADED_FILES")) { // Delete old files
                    $this->deleteUploadedFiles($row);
                }
                $deleteRows = $this->delete($row); // Delete
                if ($deleteRows === false) {
                    break;
                }
                if ($key != "") {
                    $key .= ", ";
                }
                $key .= $thisKey;
            }
        }
        if (!$deleteRows) {
            // Set up error message
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("DeleteCancelled"));
            }
        }
        if ($deleteRows) {
            $conn->commit(); // Commit the changes
        } else {
            $conn->rollback(); // Rollback changes
        }

        // Call Row Deleted event
        if ($deleteRows) {
            foreach ($rsold as $row) {
                $this->rowDeleted($row);
            }
        }

        // Write JSON for API request
        if (IsApi() && $deleteRows) {
            $row = $this->getRecordsFromRecordset($rsold);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $deleteRows;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("CfUserList"), "", $this->TableVar, true);
        $pageId = "delete";
        $Breadcrumb->add("delete", $pageId, $url);
    }

    // Setup lookup options
    public function setupLookupOptions($fld)
    {
        if ($fld->Lookup !== null && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $sql = $fld->Lookup->getSql(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if ($fld->UseLookupCache && $sql != "" && count($fld->Lookup->Options) == 0) {
                $totalCnt = $this->getRecordCount($sql, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($sql)->fetchAll(\PDO::FETCH_BOTH);
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row);
                    $ar[strval($row[0])] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Page Load event
    public function pageLoad()
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url)
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'|'warning'
    public function messageShowing(&$msg, $type)
    {
        if ($type == 'success') {
            //$msg = "your success message";
        } elseif ($type == 'failure') {
            //$msg = "your failure message";
        } elseif ($type == 'warning') {
            //$msg = "your warning message";
        } else {
            //$msg = "your message";
        }
    }

    // Page Render event
    public function pageRender()
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }
}
