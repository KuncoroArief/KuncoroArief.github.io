<?php

namespace PHPMaker2021\tooms;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class CfUserEdit extends CfUser
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'cf_user';

    // Page object name
    public $PageObjName = "CfUserEdit";

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

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $row = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page
                    $row["caption"] = $this->getModalCaption($pageName);
                    if ($pageName == "CfUserView") {
                        $row["view"] = "1";
                    }
                } else { // List page should not be shown as modal => error
                    $row["error"] = $this->getFailureMessage();
                    $this->clearFailureMessage();
                }
                WriteJson($row);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
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

    // Lookup data
    public function lookup()
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = Post("field");
        $lookup = $this->Fields[$fieldName]->Lookup;

        // Get lookup parameters
        $lookupType = Post("ajax", "unknown");
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal")) {
            $searchValue = Post("sv", "");
            $pageSize = Post("recperpage", 10);
            $offset = Post("start", 0);
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = Param("q", "");
            $pageSize = Param("n", -1);
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
            $start = Param("start", -1);
            $start = is_numeric($start) ? (int)$start : -1;
            $page = Param("page", -1);
            $page = is_numeric($page) ? (int)$page : -1;
            $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        }
        $userSelect = Decrypt(Post("s", ""));
        $userFilter = Decrypt(Post("f", ""));
        $userOrderBy = Decrypt(Post("o", ""));
        $keys = Post("keys");
        $lookup->LookupType = $lookupType; // Lookup type
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = Post("v0", Post("lookupValue", ""));
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = Post("v" . $i, "");
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        $lookup->toJson($this); // Use settings from current page
    }
    public $FormClassName = "ew-horizontal ew-form ew-edit-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $HashValue; // Hash Value

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm,
            $SkipHeaderFooter;

        // Is modal
        $this->IsModal = Param("modal") == "1";

        // Create form object
        $CurrentForm = new HttpForm();
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
        $this->column1->setVisibility();
        $this->column2->setVisibility();
        $this->column3->setVisibility();
        $this->column4->setVisibility();
        $this->column5->setVisibility();
        $this->cf_user_failed_attempt->setVisibility();
        $this->cf_user_locked->setVisibility();
        $this->_password->setVisibility();
        $this->user_password->setVisibility();
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

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $this->FormClassName = "ew-form ew-edit-form ew-horizontal";
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("empl_id") ?? Key(0) ?? Route(2)) !== null) {
                $this->empl_id->setQueryStringValue($keyValue);
                $this->empl_id->setOldValue($this->empl_id->QueryStringValue);
            } elseif (Post("empl_id") !== null) {
                $this->empl_id->setFormValue(Post("empl_id"));
                $this->empl_id->setOldValue($this->empl_id->FormValue);
            } else {
                $loaded = false; // Unable to load key
            }

            // Load record
            if ($loaded) {
                $loaded = $this->loadRow();
            }
            if (!$loaded) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                $this->terminate();
                return;
            }
            $this->CurrentAction = "update"; // Update record directly
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $postBack = true;
        } else {
            if (Post("action") !== null) {
                $this->CurrentAction = Post("action"); // Get action code
                if (!$this->isShow()) { // Not reload record, handle as postback
                    $postBack = true;
                }

                // Get key from Form
                $this->setKey(Post($this->OldKeyName), $this->isShow());
            } else {
                $this->CurrentAction = "show"; // Default action is display

                // Load key from QueryString
                $loadByQuery = false;
                if (($keyValue = Get("empl_id") ?? Route("empl_id")) !== null) {
                    $this->empl_id->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->empl_id->CurrentValue = null;
                }
            }

            // Load recordset
            if ($this->isShow()) {
                // Load current record
                $loaded = $this->loadRow();
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values

            // Overwrite record, reload hash value
            if ($this->isOverwrite()) {
                $this->loadRowHash();
                $this->CurrentAction = "update";
            }
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues();
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = ""; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                if (!$loaded) { // Load record based on key
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("CfUserList"); // No matching record, return to list
                    return;
                }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "CfUserList") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                $this->SendEmail = true; // Send email on update success
                if ($this->editRow()) { // Update record based on key
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Update success
                    }
                    if (IsApi()) {
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl); // Return to caller
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->getFailureMessage() == $Language->phrase("NoRecord")) {
                    $this->terminate($returnUrl); // Return to caller
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Restore form values if update failed
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = ROWTYPE_EDIT; // Render as Edit
        $this->resetAttributes();
        $this->renderRow();

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

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'empl_id' first before field var 'x_empl_id'
        $val = $CurrentForm->hasValue("empl_id") ? $CurrentForm->getValue("empl_id") : $CurrentForm->getValue("x_empl_id");
        if (!$this->empl_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->empl_id->Visible = false; // Disable update for API request
            } else {
                $this->empl_id->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_empl_id")) {
            $this->empl_id->setOldValue($CurrentForm->getValue("o_empl_id"));
        }

        // Check field name 'name' first before field var 'x_name'
        $val = $CurrentForm->hasValue("name") ? $CurrentForm->getValue("name") : $CurrentForm->getValue("x_name");
        if (!$this->name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->name->Visible = false; // Disable update for API request
            } else {
                $this->name->setFormValue($val);
            }
        }

        // Check field name 'default_site' first before field var 'x_default_site'
        $val = $CurrentForm->hasValue("default_site") ? $CurrentForm->getValue("default_site") : $CurrentForm->getValue("x_default_site");
        if (!$this->default_site->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->default_site->Visible = false; // Disable update for API request
            } else {
                $this->default_site->setFormValue($val);
            }
        }

        // Check field name 'default_language' first before field var 'x_default_language'
        $val = $CurrentForm->hasValue("default_language") ? $CurrentForm->getValue("default_language") : $CurrentForm->getValue("x_default_language");
        if (!$this->default_language->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->default_language->Visible = false; // Disable update for API request
            } else {
                $this->default_language->setFormValue($val);
            }
        }

        // Check field name 'sys_admin' first before field var 'x_sys_admin'
        $val = $CurrentForm->hasValue("sys_admin") ? $CurrentForm->getValue("sys_admin") : $CurrentForm->getValue("x_sys_admin");
        if (!$this->sys_admin->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->sys_admin->Visible = false; // Disable update for API request
            } else {
                $this->sys_admin->setFormValue($val);
            }
        }

        // Check field name 'status' first before field var 'x_status'
        $val = $CurrentForm->hasValue("status") ? $CurrentForm->getValue("status") : $CurrentForm->getValue("x_status");
        if (!$this->status->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->status->Visible = false; // Disable update for API request
            } else {
                $this->status->setFormValue($val);
            }
        }

        // Check field name 'supv_empl_id' first before field var 'x_supv_empl_id'
        $val = $CurrentForm->hasValue("supv_empl_id") ? $CurrentForm->getValue("supv_empl_id") : $CurrentForm->getValue("x_supv_empl_id");
        if (!$this->supv_empl_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->supv_empl_id->Visible = false; // Disable update for API request
            } else {
                $this->supv_empl_id->setFormValue($val);
            }
        }

        // Check field name 'shift' first before field var 'x_shift'
        $val = $CurrentForm->hasValue("shift") ? $CurrentForm->getValue("shift") : $CurrentForm->getValue("x_shift");
        if (!$this->shift->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->shift->Visible = false; // Disable update for API request
            } else {
                $this->shift->setFormValue($val);
            }
        }

        // Check field name 'work_area' first before field var 'x_work_area'
        $val = $CurrentForm->hasValue("work_area") ? $CurrentForm->getValue("work_area") : $CurrentForm->getValue("x_work_area");
        if (!$this->work_area->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->work_area->Visible = false; // Disable update for API request
            } else {
                $this->work_area->setFormValue($val);
            }
        }

        // Check field name 'work_grp' first before field var 'x_work_grp'
        $val = $CurrentForm->hasValue("work_grp") ? $CurrentForm->getValue("work_grp") : $CurrentForm->getValue("x_work_grp");
        if (!$this->work_grp->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->work_grp->Visible = false; // Disable update for API request
            } else {
                $this->work_grp->setFormValue($val);
            }
        }

        // Check field name 'last_login_site' first before field var 'x_last_login_site'
        $val = $CurrentForm->hasValue("last_login_site") ? $CurrentForm->getValue("last_login_site") : $CurrentForm->getValue("x_last_login_site");
        if (!$this->last_login_site->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->last_login_site->Visible = false; // Disable update for API request
            } else {
                $this->last_login_site->setFormValue($val);
            }
        }

        // Check field name 'last_login' first before field var 'x_last_login'
        $val = $CurrentForm->hasValue("last_login") ? $CurrentForm->getValue("last_login") : $CurrentForm->getValue("x_last_login");
        if (!$this->last_login->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->last_login->Visible = false; // Disable update for API request
            } else {
                $this->last_login->setFormValue($val);
            }
            $this->last_login->CurrentValue = UnFormatDateTime($this->last_login->CurrentValue, 0);
        }

        // Check field name 'last_pwd_changed' first before field var 'x_last_pwd_changed'
        $val = $CurrentForm->hasValue("last_pwd_changed") ? $CurrentForm->getValue("last_pwd_changed") : $CurrentForm->getValue("x_last_pwd_changed");
        if (!$this->last_pwd_changed->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->last_pwd_changed->Visible = false; // Disable update for API request
            } else {
                $this->last_pwd_changed->setFormValue($val);
            }
            $this->last_pwd_changed->CurrentValue = UnFormatDateTime($this->last_pwd_changed->CurrentValue, 0);
        }

        // Check field name 'audit_user' first before field var 'x_audit_user'
        $val = $CurrentForm->hasValue("audit_user") ? $CurrentForm->getValue("audit_user") : $CurrentForm->getValue("x_audit_user");
        if (!$this->audit_user->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->audit_user->Visible = false; // Disable update for API request
            } else {
                $this->audit_user->setFormValue($val);
            }
        }

        // Check field name 'audit_date' first before field var 'x_audit_date'
        $val = $CurrentForm->hasValue("audit_date") ? $CurrentForm->getValue("audit_date") : $CurrentForm->getValue("x_audit_date");
        if (!$this->audit_date->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->audit_date->Visible = false; // Disable update for API request
            } else {
                $this->audit_date->setFormValue($val);
            }
            $this->audit_date->CurrentValue = UnFormatDateTime($this->audit_date->CurrentValue, 0);
        }

        // Check field name 'RowID' first before field var 'x_RowID'
        $val = $CurrentForm->hasValue("RowID") ? $CurrentForm->getValue("RowID") : $CurrentForm->getValue("x_RowID");
        if (!$this->RowID->IsDetailKey) {
            $this->RowID->setFormValue($val);
        }

        // Check field name 'expired_date' first before field var 'x_expired_date'
        $val = $CurrentForm->hasValue("expired_date") ? $CurrentForm->getValue("expired_date") : $CurrentForm->getValue("x_expired_date");
        if (!$this->expired_date->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->expired_date->Visible = false; // Disable update for API request
            } else {
                $this->expired_date->setFormValue($val);
            }
            $this->expired_date->CurrentValue = UnFormatDateTime($this->expired_date->CurrentValue, 0);
        }

        // Check field name 'column1' first before field var 'x_column1'
        $val = $CurrentForm->hasValue("column1") ? $CurrentForm->getValue("column1") : $CurrentForm->getValue("x_column1");
        if (!$this->column1->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->column1->Visible = false; // Disable update for API request
            } else {
                $this->column1->setFormValue($val);
            }
        }

        // Check field name 'column2' first before field var 'x_column2'
        $val = $CurrentForm->hasValue("column2") ? $CurrentForm->getValue("column2") : $CurrentForm->getValue("x_column2");
        if (!$this->column2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->column2->Visible = false; // Disable update for API request
            } else {
                $this->column2->setFormValue($val);
            }
        }

        // Check field name 'column3' first before field var 'x_column3'
        $val = $CurrentForm->hasValue("column3") ? $CurrentForm->getValue("column3") : $CurrentForm->getValue("x_column3");
        if (!$this->column3->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->column3->Visible = false; // Disable update for API request
            } else {
                $this->column3->setFormValue($val);
            }
        }

        // Check field name 'column4' first before field var 'x_column4'
        $val = $CurrentForm->hasValue("column4") ? $CurrentForm->getValue("column4") : $CurrentForm->getValue("x_column4");
        if (!$this->column4->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->column4->Visible = false; // Disable update for API request
            } else {
                $this->column4->setFormValue($val);
            }
        }

        // Check field name 'column5' first before field var 'x_column5'
        $val = $CurrentForm->hasValue("column5") ? $CurrentForm->getValue("column5") : $CurrentForm->getValue("x_column5");
        if (!$this->column5->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->column5->Visible = false; // Disable update for API request
            } else {
                $this->column5->setFormValue($val);
            }
        }

        // Check field name 'cf_user_failed_attempt' first before field var 'x_cf_user_failed_attempt'
        $val = $CurrentForm->hasValue("cf_user_failed_attempt") ? $CurrentForm->getValue("cf_user_failed_attempt") : $CurrentForm->getValue("x_cf_user_failed_attempt");
        if (!$this->cf_user_failed_attempt->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cf_user_failed_attempt->Visible = false; // Disable update for API request
            } else {
                $this->cf_user_failed_attempt->setFormValue($val);
            }
        }

        // Check field name 'cf_user_locked' first before field var 'x_cf_user_locked'
        $val = $CurrentForm->hasValue("cf_user_locked") ? $CurrentForm->getValue("cf_user_locked") : $CurrentForm->getValue("x_cf_user_locked");
        if (!$this->cf_user_locked->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cf_user_locked->Visible = false; // Disable update for API request
            } else {
                $this->cf_user_locked->setFormValue($val);
            }
        }

        // Check field name 'password' first before field var 'x__password'
        $val = $CurrentForm->hasValue("password") ? $CurrentForm->getValue("password") : $CurrentForm->getValue("x__password");
        if (!$this->_password->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_password->Visible = false; // Disable update for API request
            } else {
                $this->_password->setFormValue($val);
            }
        }

        // Check field name 'user_password' first before field var 'x_user_password'
        $val = $CurrentForm->hasValue("user_password") ? $CurrentForm->getValue("user_password") : $CurrentForm->getValue("x_user_password");
        if (!$this->user_password->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->user_password->Visible = false; // Disable update for API request
            } else {
                $this->user_password->setFormValue($val);
            }
        }
        if (!$this->isOverwrite()) {
            $this->HashValue = $CurrentForm->getValue("k_hash");
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->empl_id->CurrentValue = $this->empl_id->FormValue;
        $this->name->CurrentValue = $this->name->FormValue;
        $this->default_site->CurrentValue = $this->default_site->FormValue;
        $this->default_language->CurrentValue = $this->default_language->FormValue;
        $this->sys_admin->CurrentValue = $this->sys_admin->FormValue;
        $this->status->CurrentValue = $this->status->FormValue;
        $this->supv_empl_id->CurrentValue = $this->supv_empl_id->FormValue;
        $this->shift->CurrentValue = $this->shift->FormValue;
        $this->work_area->CurrentValue = $this->work_area->FormValue;
        $this->work_grp->CurrentValue = $this->work_grp->FormValue;
        $this->last_login_site->CurrentValue = $this->last_login_site->FormValue;
        $this->last_login->CurrentValue = $this->last_login->FormValue;
        $this->last_login->CurrentValue = UnFormatDateTime($this->last_login->CurrentValue, 0);
        $this->last_pwd_changed->CurrentValue = $this->last_pwd_changed->FormValue;
        $this->last_pwd_changed->CurrentValue = UnFormatDateTime($this->last_pwd_changed->CurrentValue, 0);
        $this->audit_user->CurrentValue = $this->audit_user->FormValue;
        $this->audit_date->CurrentValue = $this->audit_date->FormValue;
        $this->audit_date->CurrentValue = UnFormatDateTime($this->audit_date->CurrentValue, 0);
        $this->RowID->CurrentValue = $this->RowID->FormValue;
        $this->expired_date->CurrentValue = $this->expired_date->FormValue;
        $this->expired_date->CurrentValue = UnFormatDateTime($this->expired_date->CurrentValue, 0);
        $this->column1->CurrentValue = $this->column1->FormValue;
        $this->column2->CurrentValue = $this->column2->FormValue;
        $this->column3->CurrentValue = $this->column3->FormValue;
        $this->column4->CurrentValue = $this->column4->FormValue;
        $this->column5->CurrentValue = $this->column5->FormValue;
        $this->cf_user_failed_attempt->CurrentValue = $this->cf_user_failed_attempt->FormValue;
        $this->cf_user_locked->CurrentValue = $this->cf_user_locked->FormValue;
        $this->_password->CurrentValue = $this->_password->FormValue;
        $this->user_password->CurrentValue = $this->user_password->FormValue;
        if (!$this->isOverwrite()) {
            $this->HashValue = $CurrentForm->getValue("k_hash");
        }
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
            if (!$this->EventCancelled) {
                $this->HashValue = $this->getRowHash($row); // Get hash value for record
            }
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

    // Load old record
    protected function loadOldRecord()
    {
        // Load old record
        $this->OldRecordset = null;
        $validKey = $this->OldKey != "";
        if ($validKey) {
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $this->OldRecordset = LoadRecordset($sql, $conn);
        }
        $this->loadRowValues($this->OldRecordset); // Load row values
        return $validKey;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Convert decimal values if posted back
        if ($this->cf_user_failed_attempt->FormValue == $this->cf_user_failed_attempt->CurrentValue && is_numeric(ConvertToFloatString($this->cf_user_failed_attempt->CurrentValue))) {
            $this->cf_user_failed_attempt->CurrentValue = ConvertToFloatString($this->cf_user_failed_attempt->CurrentValue);
        }

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

        // column2

        // column3

        // column4

        // column5

        // cf_user_failed_attempt

        // cf_user_locked

        // password

        // user_password
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

            // column1
            $this->column1->ViewValue = $this->column1->CurrentValue;
            $this->column1->ViewCustomAttributes = "";

            // column2
            $this->column2->ViewValue = $this->column2->CurrentValue;
            $this->column2->ViewCustomAttributes = "";

            // column3
            $this->column3->ViewValue = $this->column3->CurrentValue;
            $this->column3->ViewCustomAttributes = "";

            // column4
            $this->column4->ViewValue = $this->column4->CurrentValue;
            $this->column4->ViewCustomAttributes = "";

            // column5
            $this->column5->ViewValue = $this->column5->CurrentValue;
            $this->column5->ViewCustomAttributes = "";

            // cf_user_failed_attempt
            $this->cf_user_failed_attempt->ViewValue = $this->cf_user_failed_attempt->CurrentValue;
            $this->cf_user_failed_attempt->ViewValue = FormatNumber($this->cf_user_failed_attempt->ViewValue, 2, -2, -2, -2);
            $this->cf_user_failed_attempt->ViewCustomAttributes = "";

            // cf_user_locked
            $this->cf_user_locked->ViewValue = $this->cf_user_locked->CurrentValue;
            $this->cf_user_locked->ViewCustomAttributes = "";

            // password
            $this->_password->ViewValue = $this->_password->CurrentValue;
            $this->_password->ViewCustomAttributes = "";

            // user_password
            $this->user_password->ViewValue = $this->user_password->CurrentValue;
            $this->user_password->ViewCustomAttributes = "";

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

            // column1
            $this->column1->LinkCustomAttributes = "";
            $this->column1->HrefValue = "";
            $this->column1->TooltipValue = "";

            // column2
            $this->column2->LinkCustomAttributes = "";
            $this->column2->HrefValue = "";
            $this->column2->TooltipValue = "";

            // column3
            $this->column3->LinkCustomAttributes = "";
            $this->column3->HrefValue = "";
            $this->column3->TooltipValue = "";

            // column4
            $this->column4->LinkCustomAttributes = "";
            $this->column4->HrefValue = "";
            $this->column4->TooltipValue = "";

            // column5
            $this->column5->LinkCustomAttributes = "";
            $this->column5->HrefValue = "";
            $this->column5->TooltipValue = "";

            // cf_user_failed_attempt
            $this->cf_user_failed_attempt->LinkCustomAttributes = "";
            $this->cf_user_failed_attempt->HrefValue = "";
            $this->cf_user_failed_attempt->TooltipValue = "";

            // cf_user_locked
            $this->cf_user_locked->LinkCustomAttributes = "";
            $this->cf_user_locked->HrefValue = "";
            $this->cf_user_locked->TooltipValue = "";

            // password
            $this->_password->LinkCustomAttributes = "";
            $this->_password->HrefValue = "";
            $this->_password->TooltipValue = "";

            // user_password
            $this->user_password->LinkCustomAttributes = "";
            $this->user_password->HrefValue = "";
            $this->user_password->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // empl_id
            $this->empl_id->EditAttrs["class"] = "form-control";
            $this->empl_id->EditCustomAttributes = "";
            if (!$this->empl_id->Raw) {
                $this->empl_id->CurrentValue = HtmlDecode($this->empl_id->CurrentValue);
            }
            $this->empl_id->EditValue = HtmlEncode($this->empl_id->CurrentValue);
            $this->empl_id->PlaceHolder = RemoveHtml($this->empl_id->caption());

            // name
            $this->name->EditAttrs["class"] = "form-control";
            $this->name->EditCustomAttributes = "";
            if (!$this->name->Raw) {
                $this->name->CurrentValue = HtmlDecode($this->name->CurrentValue);
            }
            $this->name->EditValue = HtmlEncode($this->name->CurrentValue);
            $this->name->PlaceHolder = RemoveHtml($this->name->caption());

            // default_site
            $this->default_site->EditAttrs["class"] = "form-control";
            $this->default_site->EditCustomAttributes = "";
            if (!$this->default_site->Raw) {
                $this->default_site->CurrentValue = HtmlDecode($this->default_site->CurrentValue);
            }
            $this->default_site->EditValue = HtmlEncode($this->default_site->CurrentValue);
            $this->default_site->PlaceHolder = RemoveHtml($this->default_site->caption());

            // default_language
            $this->default_language->EditAttrs["class"] = "form-control";
            $this->default_language->EditCustomAttributes = "";
            if (!$this->default_language->Raw) {
                $this->default_language->CurrentValue = HtmlDecode($this->default_language->CurrentValue);
            }
            $this->default_language->EditValue = HtmlEncode($this->default_language->CurrentValue);
            $this->default_language->PlaceHolder = RemoveHtml($this->default_language->caption());

            // sys_admin
            $this->sys_admin->EditAttrs["class"] = "form-control";
            $this->sys_admin->EditCustomAttributes = "";
            if (!$this->sys_admin->Raw) {
                $this->sys_admin->CurrentValue = HtmlDecode($this->sys_admin->CurrentValue);
            }
            $this->sys_admin->EditValue = HtmlEncode($this->sys_admin->CurrentValue);
            $this->sys_admin->PlaceHolder = RemoveHtml($this->sys_admin->caption());

            // status
            $this->status->EditAttrs["class"] = "form-control";
            $this->status->EditCustomAttributes = "";
            if (!$this->status->Raw) {
                $this->status->CurrentValue = HtmlDecode($this->status->CurrentValue);
            }
            $this->status->EditValue = HtmlEncode($this->status->CurrentValue);
            $this->status->PlaceHolder = RemoveHtml($this->status->caption());

            // supv_empl_id
            $this->supv_empl_id->EditAttrs["class"] = "form-control";
            $this->supv_empl_id->EditCustomAttributes = "";
            if (!$this->supv_empl_id->Raw) {
                $this->supv_empl_id->CurrentValue = HtmlDecode($this->supv_empl_id->CurrentValue);
            }
            $this->supv_empl_id->EditValue = HtmlEncode($this->supv_empl_id->CurrentValue);
            $this->supv_empl_id->PlaceHolder = RemoveHtml($this->supv_empl_id->caption());

            // shift
            $this->shift->EditAttrs["class"] = "form-control";
            $this->shift->EditCustomAttributes = "";
            if (!$this->shift->Raw) {
                $this->shift->CurrentValue = HtmlDecode($this->shift->CurrentValue);
            }
            $this->shift->EditValue = HtmlEncode($this->shift->CurrentValue);
            $this->shift->PlaceHolder = RemoveHtml($this->shift->caption());

            // work_area
            $this->work_area->EditAttrs["class"] = "form-control";
            $this->work_area->EditCustomAttributes = "";
            if (!$this->work_area->Raw) {
                $this->work_area->CurrentValue = HtmlDecode($this->work_area->CurrentValue);
            }
            $this->work_area->EditValue = HtmlEncode($this->work_area->CurrentValue);
            $this->work_area->PlaceHolder = RemoveHtml($this->work_area->caption());

            // work_grp
            $this->work_grp->EditAttrs["class"] = "form-control";
            $this->work_grp->EditCustomAttributes = "";
            if (!$this->work_grp->Raw) {
                $this->work_grp->CurrentValue = HtmlDecode($this->work_grp->CurrentValue);
            }
            $this->work_grp->EditValue = HtmlEncode($this->work_grp->CurrentValue);
            $this->work_grp->PlaceHolder = RemoveHtml($this->work_grp->caption());

            // last_login_site
            $this->last_login_site->EditAttrs["class"] = "form-control";
            $this->last_login_site->EditCustomAttributes = "";
            if (!$this->last_login_site->Raw) {
                $this->last_login_site->CurrentValue = HtmlDecode($this->last_login_site->CurrentValue);
            }
            $this->last_login_site->EditValue = HtmlEncode($this->last_login_site->CurrentValue);
            $this->last_login_site->PlaceHolder = RemoveHtml($this->last_login_site->caption());

            // last_login
            $this->last_login->EditAttrs["class"] = "form-control";
            $this->last_login->EditCustomAttributes = "";
            $this->last_login->EditValue = HtmlEncode(FormatDateTime($this->last_login->CurrentValue, 8));
            $this->last_login->PlaceHolder = RemoveHtml($this->last_login->caption());

            // last_pwd_changed
            $this->last_pwd_changed->EditAttrs["class"] = "form-control";
            $this->last_pwd_changed->EditCustomAttributes = "";
            $this->last_pwd_changed->EditValue = HtmlEncode(FormatDateTime($this->last_pwd_changed->CurrentValue, 8));
            $this->last_pwd_changed->PlaceHolder = RemoveHtml($this->last_pwd_changed->caption());

            // audit_user
            $this->audit_user->EditAttrs["class"] = "form-control";
            $this->audit_user->EditCustomAttributes = "";
            if (!$this->audit_user->Raw) {
                $this->audit_user->CurrentValue = HtmlDecode($this->audit_user->CurrentValue);
            }
            $this->audit_user->EditValue = HtmlEncode($this->audit_user->CurrentValue);
            $this->audit_user->PlaceHolder = RemoveHtml($this->audit_user->caption());

            // audit_date
            $this->audit_date->EditAttrs["class"] = "form-control";
            $this->audit_date->EditCustomAttributes = "";
            $this->audit_date->EditValue = HtmlEncode(FormatDateTime($this->audit_date->CurrentValue, 8));
            $this->audit_date->PlaceHolder = RemoveHtml($this->audit_date->caption());

            // RowID
            $this->RowID->EditAttrs["class"] = "form-control";
            $this->RowID->EditCustomAttributes = "";
            $this->RowID->EditValue = HtmlEncode($this->RowID->CurrentValue);
            $this->RowID->PlaceHolder = RemoveHtml($this->RowID->caption());

            // expired_date
            $this->expired_date->EditAttrs["class"] = "form-control";
            $this->expired_date->EditCustomAttributes = "";
            $this->expired_date->EditValue = HtmlEncode(FormatDateTime($this->expired_date->CurrentValue, 8));
            $this->expired_date->PlaceHolder = RemoveHtml($this->expired_date->caption());

            // column1
            $this->column1->EditAttrs["class"] = "form-control";
            $this->column1->EditCustomAttributes = "";
            if (!$this->column1->Raw) {
                $this->column1->CurrentValue = HtmlDecode($this->column1->CurrentValue);
            }
            $this->column1->EditValue = HtmlEncode($this->column1->CurrentValue);
            $this->column1->PlaceHolder = RemoveHtml($this->column1->caption());

            // column2
            $this->column2->EditAttrs["class"] = "form-control";
            $this->column2->EditCustomAttributes = "";
            if (!$this->column2->Raw) {
                $this->column2->CurrentValue = HtmlDecode($this->column2->CurrentValue);
            }
            $this->column2->EditValue = HtmlEncode($this->column2->CurrentValue);
            $this->column2->PlaceHolder = RemoveHtml($this->column2->caption());

            // column3
            $this->column3->EditAttrs["class"] = "form-control";
            $this->column3->EditCustomAttributes = "";
            if (!$this->column3->Raw) {
                $this->column3->CurrentValue = HtmlDecode($this->column3->CurrentValue);
            }
            $this->column3->EditValue = HtmlEncode($this->column3->CurrentValue);
            $this->column3->PlaceHolder = RemoveHtml($this->column3->caption());

            // column4
            $this->column4->EditAttrs["class"] = "form-control";
            $this->column4->EditCustomAttributes = "";
            if (!$this->column4->Raw) {
                $this->column4->CurrentValue = HtmlDecode($this->column4->CurrentValue);
            }
            $this->column4->EditValue = HtmlEncode($this->column4->CurrentValue);
            $this->column4->PlaceHolder = RemoveHtml($this->column4->caption());

            // column5
            $this->column5->EditAttrs["class"] = "form-control";
            $this->column5->EditCustomAttributes = "";
            if (!$this->column5->Raw) {
                $this->column5->CurrentValue = HtmlDecode($this->column5->CurrentValue);
            }
            $this->column5->EditValue = HtmlEncode($this->column5->CurrentValue);
            $this->column5->PlaceHolder = RemoveHtml($this->column5->caption());

            // cf_user_failed_attempt
            $this->cf_user_failed_attempt->EditAttrs["class"] = "form-control";
            $this->cf_user_failed_attempt->EditCustomAttributes = "";
            $this->cf_user_failed_attempt->EditValue = HtmlEncode($this->cf_user_failed_attempt->CurrentValue);
            $this->cf_user_failed_attempt->PlaceHolder = RemoveHtml($this->cf_user_failed_attempt->caption());
            if (strval($this->cf_user_failed_attempt->EditValue) != "" && is_numeric($this->cf_user_failed_attempt->EditValue)) {
                $this->cf_user_failed_attempt->EditValue = FormatNumber($this->cf_user_failed_attempt->EditValue, -2, -2, -2, -2);
            }

            // cf_user_locked
            $this->cf_user_locked->EditAttrs["class"] = "form-control";
            $this->cf_user_locked->EditCustomAttributes = "";
            if (!$this->cf_user_locked->Raw) {
                $this->cf_user_locked->CurrentValue = HtmlDecode($this->cf_user_locked->CurrentValue);
            }
            $this->cf_user_locked->EditValue = HtmlEncode($this->cf_user_locked->CurrentValue);
            $this->cf_user_locked->PlaceHolder = RemoveHtml($this->cf_user_locked->caption());

            // password
            $this->_password->EditAttrs["class"] = "form-control";
            $this->_password->EditCustomAttributes = "";
            if (!$this->_password->Raw) {
                $this->_password->CurrentValue = HtmlDecode($this->_password->CurrentValue);
            }
            $this->_password->EditValue = HtmlEncode($this->_password->CurrentValue);
            $this->_password->PlaceHolder = RemoveHtml($this->_password->caption());

            // user_password
            $this->user_password->EditAttrs["class"] = "form-control";
            $this->user_password->EditCustomAttributes = "";
            if (!$this->user_password->Raw) {
                $this->user_password->CurrentValue = HtmlDecode($this->user_password->CurrentValue);
            }
            $this->user_password->EditValue = HtmlEncode($this->user_password->CurrentValue);
            $this->user_password->PlaceHolder = RemoveHtml($this->user_password->caption());

            // Edit refer script

            // empl_id
            $this->empl_id->LinkCustomAttributes = "";
            $this->empl_id->HrefValue = "";

            // name
            $this->name->LinkCustomAttributes = "";
            $this->name->HrefValue = "";

            // default_site
            $this->default_site->LinkCustomAttributes = "";
            $this->default_site->HrefValue = "";

            // default_language
            $this->default_language->LinkCustomAttributes = "";
            $this->default_language->HrefValue = "";

            // sys_admin
            $this->sys_admin->LinkCustomAttributes = "";
            $this->sys_admin->HrefValue = "";

            // status
            $this->status->LinkCustomAttributes = "";
            $this->status->HrefValue = "";

            // supv_empl_id
            $this->supv_empl_id->LinkCustomAttributes = "";
            $this->supv_empl_id->HrefValue = "";

            // shift
            $this->shift->LinkCustomAttributes = "";
            $this->shift->HrefValue = "";

            // work_area
            $this->work_area->LinkCustomAttributes = "";
            $this->work_area->HrefValue = "";

            // work_grp
            $this->work_grp->LinkCustomAttributes = "";
            $this->work_grp->HrefValue = "";

            // last_login_site
            $this->last_login_site->LinkCustomAttributes = "";
            $this->last_login_site->HrefValue = "";

            // last_login
            $this->last_login->LinkCustomAttributes = "";
            $this->last_login->HrefValue = "";

            // last_pwd_changed
            $this->last_pwd_changed->LinkCustomAttributes = "";
            $this->last_pwd_changed->HrefValue = "";

            // audit_user
            $this->audit_user->LinkCustomAttributes = "";
            $this->audit_user->HrefValue = "";

            // audit_date
            $this->audit_date->LinkCustomAttributes = "";
            $this->audit_date->HrefValue = "";

            // RowID
            $this->RowID->LinkCustomAttributes = "";
            $this->RowID->HrefValue = "";

            // expired_date
            $this->expired_date->LinkCustomAttributes = "";
            $this->expired_date->HrefValue = "";

            // column1
            $this->column1->LinkCustomAttributes = "";
            $this->column1->HrefValue = "";

            // column2
            $this->column2->LinkCustomAttributes = "";
            $this->column2->HrefValue = "";

            // column3
            $this->column3->LinkCustomAttributes = "";
            $this->column3->HrefValue = "";

            // column4
            $this->column4->LinkCustomAttributes = "";
            $this->column4->HrefValue = "";

            // column5
            $this->column5->LinkCustomAttributes = "";
            $this->column5->HrefValue = "";

            // cf_user_failed_attempt
            $this->cf_user_failed_attempt->LinkCustomAttributes = "";
            $this->cf_user_failed_attempt->HrefValue = "";

            // cf_user_locked
            $this->cf_user_locked->LinkCustomAttributes = "";
            $this->cf_user_locked->HrefValue = "";

            // password
            $this->_password->LinkCustomAttributes = "";
            $this->_password->HrefValue = "";

            // user_password
            $this->user_password->LinkCustomAttributes = "";
            $this->user_password->HrefValue = "";
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if ($this->empl_id->Required) {
            if (!$this->empl_id->IsDetailKey && EmptyValue($this->empl_id->FormValue)) {
                $this->empl_id->addErrorMessage(str_replace("%s", $this->empl_id->caption(), $this->empl_id->RequiredErrorMessage));
            }
        }
        if (!$this->empl_id->Raw && Config("REMOVE_XSS") && CheckUsername($this->empl_id->FormValue)) {
            $this->empl_id->addErrorMessage($Language->phrase("InvalidUsernameChars"));
        }
        if ($this->name->Required) {
            if (!$this->name->IsDetailKey && EmptyValue($this->name->FormValue)) {
                $this->name->addErrorMessage(str_replace("%s", $this->name->caption(), $this->name->RequiredErrorMessage));
            }
        }
        if ($this->default_site->Required) {
            if (!$this->default_site->IsDetailKey && EmptyValue($this->default_site->FormValue)) {
                $this->default_site->addErrorMessage(str_replace("%s", $this->default_site->caption(), $this->default_site->RequiredErrorMessage));
            }
        }
        if ($this->default_language->Required) {
            if (!$this->default_language->IsDetailKey && EmptyValue($this->default_language->FormValue)) {
                $this->default_language->addErrorMessage(str_replace("%s", $this->default_language->caption(), $this->default_language->RequiredErrorMessage));
            }
        }
        if ($this->sys_admin->Required) {
            if (!$this->sys_admin->IsDetailKey && EmptyValue($this->sys_admin->FormValue)) {
                $this->sys_admin->addErrorMessage(str_replace("%s", $this->sys_admin->caption(), $this->sys_admin->RequiredErrorMessage));
            }
        }
        if ($this->status->Required) {
            if (!$this->status->IsDetailKey && EmptyValue($this->status->FormValue)) {
                $this->status->addErrorMessage(str_replace("%s", $this->status->caption(), $this->status->RequiredErrorMessage));
            }
        }
        if ($this->supv_empl_id->Required) {
            if (!$this->supv_empl_id->IsDetailKey && EmptyValue($this->supv_empl_id->FormValue)) {
                $this->supv_empl_id->addErrorMessage(str_replace("%s", $this->supv_empl_id->caption(), $this->supv_empl_id->RequiredErrorMessage));
            }
        }
        if ($this->shift->Required) {
            if (!$this->shift->IsDetailKey && EmptyValue($this->shift->FormValue)) {
                $this->shift->addErrorMessage(str_replace("%s", $this->shift->caption(), $this->shift->RequiredErrorMessage));
            }
        }
        if ($this->work_area->Required) {
            if (!$this->work_area->IsDetailKey && EmptyValue($this->work_area->FormValue)) {
                $this->work_area->addErrorMessage(str_replace("%s", $this->work_area->caption(), $this->work_area->RequiredErrorMessage));
            }
        }
        if ($this->work_grp->Required) {
            if (!$this->work_grp->IsDetailKey && EmptyValue($this->work_grp->FormValue)) {
                $this->work_grp->addErrorMessage(str_replace("%s", $this->work_grp->caption(), $this->work_grp->RequiredErrorMessage));
            }
        }
        if ($this->last_login_site->Required) {
            if (!$this->last_login_site->IsDetailKey && EmptyValue($this->last_login_site->FormValue)) {
                $this->last_login_site->addErrorMessage(str_replace("%s", $this->last_login_site->caption(), $this->last_login_site->RequiredErrorMessage));
            }
        }
        if ($this->last_login->Required) {
            if (!$this->last_login->IsDetailKey && EmptyValue($this->last_login->FormValue)) {
                $this->last_login->addErrorMessage(str_replace("%s", $this->last_login->caption(), $this->last_login->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->last_login->FormValue)) {
            $this->last_login->addErrorMessage($this->last_login->getErrorMessage(false));
        }
        if ($this->last_pwd_changed->Required) {
            if (!$this->last_pwd_changed->IsDetailKey && EmptyValue($this->last_pwd_changed->FormValue)) {
                $this->last_pwd_changed->addErrorMessage(str_replace("%s", $this->last_pwd_changed->caption(), $this->last_pwd_changed->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->last_pwd_changed->FormValue)) {
            $this->last_pwd_changed->addErrorMessage($this->last_pwd_changed->getErrorMessage(false));
        }
        if ($this->audit_user->Required) {
            if (!$this->audit_user->IsDetailKey && EmptyValue($this->audit_user->FormValue)) {
                $this->audit_user->addErrorMessage(str_replace("%s", $this->audit_user->caption(), $this->audit_user->RequiredErrorMessage));
            }
        }
        if ($this->audit_date->Required) {
            if (!$this->audit_date->IsDetailKey && EmptyValue($this->audit_date->FormValue)) {
                $this->audit_date->addErrorMessage(str_replace("%s", $this->audit_date->caption(), $this->audit_date->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->audit_date->FormValue)) {
            $this->audit_date->addErrorMessage($this->audit_date->getErrorMessage(false));
        }
        if ($this->RowID->Required) {
            if (!$this->RowID->IsDetailKey && EmptyValue($this->RowID->FormValue)) {
                $this->RowID->addErrorMessage(str_replace("%s", $this->RowID->caption(), $this->RowID->RequiredErrorMessage));
            }
        }
        if ($this->expired_date->Required) {
            if (!$this->expired_date->IsDetailKey && EmptyValue($this->expired_date->FormValue)) {
                $this->expired_date->addErrorMessage(str_replace("%s", $this->expired_date->caption(), $this->expired_date->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->expired_date->FormValue)) {
            $this->expired_date->addErrorMessage($this->expired_date->getErrorMessage(false));
        }
        if ($this->column1->Required) {
            if (!$this->column1->IsDetailKey && EmptyValue($this->column1->FormValue)) {
                $this->column1->addErrorMessage(str_replace("%s", $this->column1->caption(), $this->column1->RequiredErrorMessage));
            }
        }
        if ($this->column2->Required) {
            if (!$this->column2->IsDetailKey && EmptyValue($this->column2->FormValue)) {
                $this->column2->addErrorMessage(str_replace("%s", $this->column2->caption(), $this->column2->RequiredErrorMessage));
            }
        }
        if ($this->column3->Required) {
            if (!$this->column3->IsDetailKey && EmptyValue($this->column3->FormValue)) {
                $this->column3->addErrorMessage(str_replace("%s", $this->column3->caption(), $this->column3->RequiredErrorMessage));
            }
        }
        if ($this->column4->Required) {
            if (!$this->column4->IsDetailKey && EmptyValue($this->column4->FormValue)) {
                $this->column4->addErrorMessage(str_replace("%s", $this->column4->caption(), $this->column4->RequiredErrorMessage));
            }
        }
        if ($this->column5->Required) {
            if (!$this->column5->IsDetailKey && EmptyValue($this->column5->FormValue)) {
                $this->column5->addErrorMessage(str_replace("%s", $this->column5->caption(), $this->column5->RequiredErrorMessage));
            }
        }
        if ($this->cf_user_failed_attempt->Required) {
            if (!$this->cf_user_failed_attempt->IsDetailKey && EmptyValue($this->cf_user_failed_attempt->FormValue)) {
                $this->cf_user_failed_attempt->addErrorMessage(str_replace("%s", $this->cf_user_failed_attempt->caption(), $this->cf_user_failed_attempt->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->cf_user_failed_attempt->FormValue)) {
            $this->cf_user_failed_attempt->addErrorMessage($this->cf_user_failed_attempt->getErrorMessage(false));
        }
        if ($this->cf_user_locked->Required) {
            if (!$this->cf_user_locked->IsDetailKey && EmptyValue($this->cf_user_locked->FormValue)) {
                $this->cf_user_locked->addErrorMessage(str_replace("%s", $this->cf_user_locked->caption(), $this->cf_user_locked->RequiredErrorMessage));
            }
        }
        if ($this->_password->Required) {
            if (!$this->_password->IsDetailKey && EmptyValue($this->_password->FormValue)) {
                $this->_password->addErrorMessage(str_replace("%s", $this->_password->caption(), $this->_password->RequiredErrorMessage));
            }
        }
        if ($this->user_password->Required) {
            if (!$this->user_password->IsDetailKey && EmptyValue($this->user_password->FormValue)) {
                $this->user_password->addErrorMessage(str_replace("%s", $this->user_password->caption(), $this->user_password->RequiredErrorMessage));
            }
        }
        if (!$this->user_password->Raw && Config("REMOVE_XSS") && CheckPassword($this->user_password->FormValue)) {
            $this->user_password->addErrorMessage($Language->phrase("InvalidPasswordChars"));
        }

        // Return validate result
        $validateForm = !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssoc($sql);
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            $editRow = false; // Update Failed
        } else {
            // Save old values
            $this->loadDbValues($rsold);
            $rsnew = [];

            // empl_id
            $this->empl_id->setDbValueDef($rsnew, $this->empl_id->CurrentValue, "", $this->empl_id->ReadOnly);

            // name
            $this->name->setDbValueDef($rsnew, $this->name->CurrentValue, null, $this->name->ReadOnly);

            // default_site
            $this->default_site->setDbValueDef($rsnew, $this->default_site->CurrentValue, null, $this->default_site->ReadOnly);

            // default_language
            $this->default_language->setDbValueDef($rsnew, $this->default_language->CurrentValue, null, $this->default_language->ReadOnly);

            // sys_admin
            $this->sys_admin->setDbValueDef($rsnew, $this->sys_admin->CurrentValue, null, $this->sys_admin->ReadOnly);

            // status
            $this->status->setDbValueDef($rsnew, $this->status->CurrentValue, null, $this->status->ReadOnly);

            // supv_empl_id
            $this->supv_empl_id->setDbValueDef($rsnew, $this->supv_empl_id->CurrentValue, null, $this->supv_empl_id->ReadOnly);

            // shift
            $this->shift->setDbValueDef($rsnew, $this->shift->CurrentValue, null, $this->shift->ReadOnly);

            // work_area
            $this->work_area->setDbValueDef($rsnew, $this->work_area->CurrentValue, null, $this->work_area->ReadOnly);

            // work_grp
            $this->work_grp->setDbValueDef($rsnew, $this->work_grp->CurrentValue, null, $this->work_grp->ReadOnly);

            // last_login_site
            $this->last_login_site->setDbValueDef($rsnew, $this->last_login_site->CurrentValue, null, $this->last_login_site->ReadOnly);

            // last_login
            $this->last_login->setDbValueDef($rsnew, UnFormatDateTime($this->last_login->CurrentValue, 0), null, $this->last_login->ReadOnly);

            // last_pwd_changed
            $this->last_pwd_changed->setDbValueDef($rsnew, UnFormatDateTime($this->last_pwd_changed->CurrentValue, 0), null, $this->last_pwd_changed->ReadOnly);

            // audit_user
            $this->audit_user->setDbValueDef($rsnew, $this->audit_user->CurrentValue, null, $this->audit_user->ReadOnly);

            // audit_date
            $this->audit_date->setDbValueDef($rsnew, UnFormatDateTime($this->audit_date->CurrentValue, 0), null, $this->audit_date->ReadOnly);

            // expired_date
            $this->expired_date->setDbValueDef($rsnew, UnFormatDateTime($this->expired_date->CurrentValue, 0), null, $this->expired_date->ReadOnly);

            // column1
            $this->column1->setDbValueDef($rsnew, $this->column1->CurrentValue, null, $this->column1->ReadOnly);

            // column2
            $this->column2->setDbValueDef($rsnew, $this->column2->CurrentValue, null, $this->column2->ReadOnly);

            // column3
            $this->column3->setDbValueDef($rsnew, $this->column3->CurrentValue, null, $this->column3->ReadOnly);

            // column4
            $this->column4->setDbValueDef($rsnew, $this->column4->CurrentValue, null, $this->column4->ReadOnly);

            // column5
            $this->column5->setDbValueDef($rsnew, $this->column5->CurrentValue, null, $this->column5->ReadOnly);

            // cf_user_failed_attempt
            $this->cf_user_failed_attempt->setDbValueDef($rsnew, $this->cf_user_failed_attempt->CurrentValue, null, $this->cf_user_failed_attempt->ReadOnly);

            // cf_user_locked
            $this->cf_user_locked->setDbValueDef($rsnew, $this->cf_user_locked->CurrentValue, null, $this->cf_user_locked->ReadOnly);

            // password
            $this->_password->setDbValueDef($rsnew, $this->_password->CurrentValue, null, $this->_password->ReadOnly);

            // user_password
            $this->user_password->setDbValueDef($rsnew, $this->user_password->CurrentValue, null, $this->user_password->ReadOnly || Config("ENCRYPTED_PASSWORD") && $rsold['user_password'] == $this->user_password->CurrentValue);

            // Check hash value
            $rowHasConflict = (!IsApi() && $this->getRowHash($rsold) != $this->HashValue);

            // Call Row Update Conflict event
            if ($rowHasConflict) {
                $rowHasConflict = $this->rowUpdateConflict($rsold, $rsnew);
            }
            if ($rowHasConflict) {
                $this->setFailureMessage($Language->phrase("RecordChangedByOtherUser"));
                $this->UpdateConflict = "U";
                return false; // Update Failed
            }

            // Call Row Updating event
            $updateRow = $this->rowUpdating($rsold, $rsnew);

            // Check for duplicate key when key changed
            if ($updateRow) {
                $newKeyFilter = $this->getRecordFilter($rsnew);
                if ($newKeyFilter != $oldKeyFilter) {
                    $rsChk = $this->loadRs($newKeyFilter)->fetch();
                    if ($rsChk !== false) {
                        $keyErrMsg = str_replace("%f", $newKeyFilter, $Language->phrase("DupKey"));
                        $this->setFailureMessage($keyErrMsg);
                        $updateRow = false;
                    }
                }
            }
            if ($updateRow) {
                if (count($rsnew) > 0) {
                    $editRow = $this->update($rsnew, "", $rsold);
                } else {
                    $editRow = true; // No field to update
                }
                if ($editRow) {
                }
            } else {
                if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                    // Use the message, do nothing
                } elseif ($this->CancelMessage != "") {
                    $this->setFailureMessage($this->CancelMessage);
                    $this->CancelMessage = "";
                } else {
                    $this->setFailureMessage($Language->phrase("UpdateCancelled"));
                }
                $editRow = false;
            }
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($rsold, $rsnew);
        }

        // Clean upload path if any
        if ($editRow) {
        }

        // Write JSON for API request
        if (IsApi() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $editRow;
    }

    // Load row hash
    protected function loadRowHash()
    {
        $filter = $this->getRecordFilter();

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $row = $conn->fetchAssoc($sql);
        $this->HashValue = $row ? $this->getRowHash($row) : ""; // Get hash value for record
    }

    // Get Row Hash
    public function getRowHash(&$rs)
    {
        if (!$rs) {
            return "";
        }
        $row = ($rs instanceof Recordset) ? $rs->fields : $rs;
        $hash = "";
        $hash .= GetFieldHash($row['empl_id']); // empl_id
        $hash .= GetFieldHash($row['name']); // name
        $hash .= GetFieldHash($row['default_site']); // default_site
        $hash .= GetFieldHash($row['default_language']); // default_language
        $hash .= GetFieldHash($row['sys_admin']); // sys_admin
        $hash .= GetFieldHash($row['status']); // status
        $hash .= GetFieldHash($row['supv_empl_id']); // supv_empl_id
        $hash .= GetFieldHash($row['shift']); // shift
        $hash .= GetFieldHash($row['work_area']); // work_area
        $hash .= GetFieldHash($row['work_grp']); // work_grp
        $hash .= GetFieldHash($row['last_login_site']); // last_login_site
        $hash .= GetFieldHash($row['last_login']); // last_login
        $hash .= GetFieldHash($row['last_pwd_changed']); // last_pwd_changed
        $hash .= GetFieldHash($row['audit_user']); // audit_user
        $hash .= GetFieldHash($row['audit_date']); // audit_date
        $hash .= GetFieldHash($row['expired_date']); // expired_date
        $hash .= GetFieldHash($row['column1']); // column1
        $hash .= GetFieldHash($row['column2']); // column2
        $hash .= GetFieldHash($row['column3']); // column3
        $hash .= GetFieldHash($row['column4']); // column4
        $hash .= GetFieldHash($row['column5']); // column5
        $hash .= GetFieldHash($row['cf_user_failed_attempt']); // cf_user_failed_attempt
        $hash .= GetFieldHash($row['cf_user_locked']); // cf_user_locked
        $hash .= GetFieldHash($row['password']); // password
        $hash .= GetFieldHash($row['user_password']); // user_password
        return md5($hash);
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("CfUserList"), "", $this->TableVar, true);
        $pageId = "edit";
        $Breadcrumb->add("edit", $pageId, $url);
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

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        if ($this->isPageRequest()) { // Validate request
            $startRec = Get(Config("TABLE_START_REC"));
            $pageNo = Get(Config("TABLE_PAGE_NO"));
            if ($pageNo !== null) { // Check for "pageno" parameter first
                if (is_numeric($pageNo)) {
                    $this->StartRecord = ($pageNo - 1) * $this->DisplayRecords + 1;
                    if ($this->StartRecord <= 0) {
                        $this->StartRecord = 1;
                    } elseif ($this->StartRecord >= (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1) {
                        $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1;
                    }
                    $this->setStartRecordNumber($this->StartRecord);
                }
            } elseif ($startRec !== null) { // Check for "start" parameter
                $this->StartRecord = $startRec;
                $this->setStartRecordNumber($this->StartRecord);
            }
        }
        $this->StartRecord = $this->getStartRecordNumber();

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || $this->StartRecord == "") { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
            $this->setStartRecordNumber($this->StartRecord);
        } elseif ($this->StartRecord > $this->TotalRecords) { // Avoid starting record > total records
            $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to last page first record
            $this->setStartRecordNumber($this->StartRecord);
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
            $this->setStartRecordNumber($this->StartRecord);
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

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in CustomError
        return true;
    }
}
