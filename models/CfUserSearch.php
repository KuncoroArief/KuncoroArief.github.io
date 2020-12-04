<?php

namespace PHPMaker2021\tooms;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class CfUserSearch extends CfUser
{
    use MessagesTrait;

    // Page ID
    public $PageID = "search";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'cf_user';

    // Page object name
    public $PageObjName = "CfUserSearch";

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
    public $FormClassName = "ew-horizontal ew-form ew-search-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;

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

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        if ($this->isPageRequest()) {
            // Get action
            $this->CurrentAction = Post("action");
            if ($this->isSearch()) {
                // Build search string for advanced search, remove blank field
                $this->loadSearchValues(); // Get search values
                if ($this->validateSearch()) {
                    $srchStr = $this->buildAdvancedSearch();
                } else {
                    $srchStr = "";
                }
                if ($srchStr != "") {
                    $srchStr = $this->getUrlParm($srchStr);
                    $srchStr = "CfUserList" . "?" . $srchStr;
                    $this->terminate($srchStr); // Go to list page
                    return;
                }
            }
        }

        // Restore search settings from Session
        if (!$this->hasInvalidFields()) {
            $this->loadAdvancedSearch();
        }

        // Render row for search
        $this->RowType = ROWTYPE_SEARCH;
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

    // Build advanced search
    protected function buildAdvancedSearch()
    {
        $srchUrl = "";
        $this->buildSearchUrl($srchUrl, $this->empl_id); // empl_id
        $this->buildSearchUrl($srchUrl, $this->name); // name
        $this->buildSearchUrl($srchUrl, $this->default_site); // default_site
        $this->buildSearchUrl($srchUrl, $this->default_language); // default_language
        $this->buildSearchUrl($srchUrl, $this->sys_admin); // sys_admin
        $this->buildSearchUrl($srchUrl, $this->status); // status
        $this->buildSearchUrl($srchUrl, $this->supv_empl_id); // supv_empl_id
        $this->buildSearchUrl($srchUrl, $this->shift); // shift
        $this->buildSearchUrl($srchUrl, $this->work_area); // work_area
        $this->buildSearchUrl($srchUrl, $this->work_grp); // work_grp
        $this->buildSearchUrl($srchUrl, $this->last_login_site); // last_login_site
        $this->buildSearchUrl($srchUrl, $this->last_login); // last_login
        $this->buildSearchUrl($srchUrl, $this->last_pwd_changed); // last_pwd_changed
        $this->buildSearchUrl($srchUrl, $this->audit_user); // audit_user
        $this->buildSearchUrl($srchUrl, $this->audit_date); // audit_date
        $this->buildSearchUrl($srchUrl, $this->RowID); // RowID
        $this->buildSearchUrl($srchUrl, $this->expired_date); // expired_date
        $this->buildSearchUrl($srchUrl, $this->column1); // column1
        $this->buildSearchUrl($srchUrl, $this->column2); // column2
        $this->buildSearchUrl($srchUrl, $this->column3); // column3
        $this->buildSearchUrl($srchUrl, $this->column4); // column4
        $this->buildSearchUrl($srchUrl, $this->column5); // column5
        $this->buildSearchUrl($srchUrl, $this->cf_user_failed_attempt); // cf_user_failed_attempt
        $this->buildSearchUrl($srchUrl, $this->cf_user_locked); // cf_user_locked
        $this->buildSearchUrl($srchUrl, $this->_password); // password
        $this->buildSearchUrl($srchUrl, $this->user_password); // user_password
        if ($srchUrl != "") {
            $srchUrl .= "&";
        }
        $srchUrl .= "cmd=search";
        return $srchUrl;
    }

    // Build search URL
    protected function buildSearchUrl(&$url, &$fld, $oprOnly = false)
    {
        global $CurrentForm;
        $wrk = "";
        $fldParm = $fld->Param;
        $fldVal = $CurrentForm->getValue("x_$fldParm");
        $fldOpr = $CurrentForm->getValue("z_$fldParm");
        $fldCond = $CurrentForm->getValue("v_$fldParm");
        $fldVal2 = $CurrentForm->getValue("y_$fldParm");
        $fldOpr2 = $CurrentForm->getValue("w_$fldParm");
        if (is_array($fldVal)) {
            $fldVal = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal);
        }
        if (is_array($fldVal2)) {
            $fldVal2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal2);
        }
        $fldOpr = strtoupper(trim($fldOpr));
        $fldDataType = ($fld->IsVirtual) ? DATATYPE_STRING : $fld->DataType;
        if ($fldOpr == "BETWEEN") {
            $isValidValue = ($fldDataType != DATATYPE_NUMBER) ||
                ($fldDataType == DATATYPE_NUMBER && $this->searchValueIsNumeric($fld, $fldVal) && $this->searchValueIsNumeric($fld, $fldVal2));
            if ($fldVal != "" && $fldVal2 != "" && $isValidValue) {
                $wrk = "x_" . $fldParm . "=" . urlencode($fldVal) .
                    "&y_" . $fldParm . "=" . urlencode($fldVal2) .
                    "&z_" . $fldParm . "=" . urlencode($fldOpr);
            }
        } else {
            $isValidValue = ($fldDataType != DATATYPE_NUMBER) ||
                ($fldDataType == DATATYPE_NUMBER && $this->searchValueIsNumeric($fld, $fldVal));
            if ($fldVal != "" && $isValidValue && IsValidOperator($fldOpr, $fldDataType)) {
                $wrk = "x_" . $fldParm . "=" . urlencode($fldVal) .
                    "&z_" . $fldParm . "=" . urlencode($fldOpr);
            } elseif ($fldOpr == "IS NULL" || $fldOpr == "IS NOT NULL" || ($fldOpr != "" && $oprOnly && IsValidOperator($fldOpr, $fldDataType))) {
                $wrk = "z_" . $fldParm . "=" . urlencode($fldOpr);
            }
            $isValidValue = ($fldDataType != DATATYPE_NUMBER) ||
                ($fldDataType == DATATYPE_NUMBER && $this->searchValueIsNumeric($fld, $fldVal2));
            if ($fldVal2 != "" && $isValidValue && IsValidOperator($fldOpr2, $fldDataType)) {
                if ($wrk != "") {
                    $wrk .= "&v_" . $fldParm . "=" . urlencode($fldCond) . "&";
                }
                $wrk .= "y_" . $fldParm . "=" . urlencode($fldVal2) .
                    "&w_" . $fldParm . "=" . urlencode($fldOpr2);
            } elseif ($fldOpr2 == "IS NULL" || $fldOpr2 == "IS NOT NULL" || ($fldOpr2 != "" && $oprOnly && IsValidOperator($fldOpr2, $fldDataType))) {
                if ($wrk != "") {
                    $wrk .= "&v_" . $fldParm . "=" . urlencode($fldCond) . "&";
                }
                $wrk .= "w_" . $fldParm . "=" . urlencode($fldOpr2);
            }
        }
        if ($wrk != "") {
            if ($url != "") {
                $url .= "&";
            }
            $url .= $wrk;
        }
    }

    // Check if search value is numeric
    protected function searchValueIsNumeric($fld, $value)
    {
        if (IsFloatFormat($fld->Type)) {
            $value = ConvertToFloatString($value);
        }
        return is_numeric($value);
    }

    // Load search values for validation
    protected function loadSearchValues()
    {
        // Load search values
        $hasValue = false;
        if ($this->empl_id->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->name->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->default_site->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->default_language->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->sys_admin->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->status->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->supv_empl_id->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->shift->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->work_area->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->work_grp->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->last_login_site->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->last_login->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->last_pwd_changed->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->audit_user->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->audit_date->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->RowID->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->expired_date->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->column1->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->column2->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->column3->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->column4->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->column5->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->cf_user_failed_attempt->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->cf_user_locked->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->_password->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->user_password->AdvancedSearch->post()) {
            $hasValue = true;
        }
        return $hasValue;
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
        } elseif ($this->RowType == ROWTYPE_SEARCH) {
            // empl_id
            $this->empl_id->EditAttrs["class"] = "form-control";
            $this->empl_id->EditCustomAttributes = "";
            if (!$this->empl_id->Raw) {
                $this->empl_id->AdvancedSearch->SearchValue = HtmlDecode($this->empl_id->AdvancedSearch->SearchValue);
            }
            $this->empl_id->EditValue = HtmlEncode($this->empl_id->AdvancedSearch->SearchValue);
            $this->empl_id->PlaceHolder = RemoveHtml($this->empl_id->caption());

            // name
            $this->name->EditAttrs["class"] = "form-control";
            $this->name->EditCustomAttributes = "";
            if (!$this->name->Raw) {
                $this->name->AdvancedSearch->SearchValue = HtmlDecode($this->name->AdvancedSearch->SearchValue);
            }
            $this->name->EditValue = HtmlEncode($this->name->AdvancedSearch->SearchValue);
            $this->name->PlaceHolder = RemoveHtml($this->name->caption());

            // default_site
            $this->default_site->EditAttrs["class"] = "form-control";
            $this->default_site->EditCustomAttributes = "";
            if (!$this->default_site->Raw) {
                $this->default_site->AdvancedSearch->SearchValue = HtmlDecode($this->default_site->AdvancedSearch->SearchValue);
            }
            $this->default_site->EditValue = HtmlEncode($this->default_site->AdvancedSearch->SearchValue);
            $this->default_site->PlaceHolder = RemoveHtml($this->default_site->caption());

            // default_language
            $this->default_language->EditAttrs["class"] = "form-control";
            $this->default_language->EditCustomAttributes = "";
            if (!$this->default_language->Raw) {
                $this->default_language->AdvancedSearch->SearchValue = HtmlDecode($this->default_language->AdvancedSearch->SearchValue);
            }
            $this->default_language->EditValue = HtmlEncode($this->default_language->AdvancedSearch->SearchValue);
            $this->default_language->PlaceHolder = RemoveHtml($this->default_language->caption());

            // sys_admin
            $this->sys_admin->EditAttrs["class"] = "form-control";
            $this->sys_admin->EditCustomAttributes = "";
            if (!$this->sys_admin->Raw) {
                $this->sys_admin->AdvancedSearch->SearchValue = HtmlDecode($this->sys_admin->AdvancedSearch->SearchValue);
            }
            $this->sys_admin->EditValue = HtmlEncode($this->sys_admin->AdvancedSearch->SearchValue);
            $this->sys_admin->PlaceHolder = RemoveHtml($this->sys_admin->caption());

            // status
            $this->status->EditAttrs["class"] = "form-control";
            $this->status->EditCustomAttributes = "";
            if (!$this->status->Raw) {
                $this->status->AdvancedSearch->SearchValue = HtmlDecode($this->status->AdvancedSearch->SearchValue);
            }
            $this->status->EditValue = HtmlEncode($this->status->AdvancedSearch->SearchValue);
            $this->status->PlaceHolder = RemoveHtml($this->status->caption());

            // supv_empl_id
            $this->supv_empl_id->EditAttrs["class"] = "form-control";
            $this->supv_empl_id->EditCustomAttributes = "";
            if (!$this->supv_empl_id->Raw) {
                $this->supv_empl_id->AdvancedSearch->SearchValue = HtmlDecode($this->supv_empl_id->AdvancedSearch->SearchValue);
            }
            $this->supv_empl_id->EditValue = HtmlEncode($this->supv_empl_id->AdvancedSearch->SearchValue);
            $this->supv_empl_id->PlaceHolder = RemoveHtml($this->supv_empl_id->caption());

            // shift
            $this->shift->EditAttrs["class"] = "form-control";
            $this->shift->EditCustomAttributes = "";
            if (!$this->shift->Raw) {
                $this->shift->AdvancedSearch->SearchValue = HtmlDecode($this->shift->AdvancedSearch->SearchValue);
            }
            $this->shift->EditValue = HtmlEncode($this->shift->AdvancedSearch->SearchValue);
            $this->shift->PlaceHolder = RemoveHtml($this->shift->caption());

            // work_area
            $this->work_area->EditAttrs["class"] = "form-control";
            $this->work_area->EditCustomAttributes = "";
            if (!$this->work_area->Raw) {
                $this->work_area->AdvancedSearch->SearchValue = HtmlDecode($this->work_area->AdvancedSearch->SearchValue);
            }
            $this->work_area->EditValue = HtmlEncode($this->work_area->AdvancedSearch->SearchValue);
            $this->work_area->PlaceHolder = RemoveHtml($this->work_area->caption());

            // work_grp
            $this->work_grp->EditAttrs["class"] = "form-control";
            $this->work_grp->EditCustomAttributes = "";
            if (!$this->work_grp->Raw) {
                $this->work_grp->AdvancedSearch->SearchValue = HtmlDecode($this->work_grp->AdvancedSearch->SearchValue);
            }
            $this->work_grp->EditValue = HtmlEncode($this->work_grp->AdvancedSearch->SearchValue);
            $this->work_grp->PlaceHolder = RemoveHtml($this->work_grp->caption());

            // last_login_site
            $this->last_login_site->EditAttrs["class"] = "form-control";
            $this->last_login_site->EditCustomAttributes = "";
            if (!$this->last_login_site->Raw) {
                $this->last_login_site->AdvancedSearch->SearchValue = HtmlDecode($this->last_login_site->AdvancedSearch->SearchValue);
            }
            $this->last_login_site->EditValue = HtmlEncode($this->last_login_site->AdvancedSearch->SearchValue);
            $this->last_login_site->PlaceHolder = RemoveHtml($this->last_login_site->caption());

            // last_login
            $this->last_login->EditAttrs["class"] = "form-control";
            $this->last_login->EditCustomAttributes = "";
            $this->last_login->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->last_login->AdvancedSearch->SearchValue, 0), 8));
            $this->last_login->PlaceHolder = RemoveHtml($this->last_login->caption());

            // last_pwd_changed
            $this->last_pwd_changed->EditAttrs["class"] = "form-control";
            $this->last_pwd_changed->EditCustomAttributes = "";
            $this->last_pwd_changed->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->last_pwd_changed->AdvancedSearch->SearchValue, 0), 8));
            $this->last_pwd_changed->PlaceHolder = RemoveHtml($this->last_pwd_changed->caption());

            // audit_user
            $this->audit_user->EditAttrs["class"] = "form-control";
            $this->audit_user->EditCustomAttributes = "";
            if (!$this->audit_user->Raw) {
                $this->audit_user->AdvancedSearch->SearchValue = HtmlDecode($this->audit_user->AdvancedSearch->SearchValue);
            }
            $this->audit_user->EditValue = HtmlEncode($this->audit_user->AdvancedSearch->SearchValue);
            $this->audit_user->PlaceHolder = RemoveHtml($this->audit_user->caption());

            // audit_date
            $this->audit_date->EditAttrs["class"] = "form-control";
            $this->audit_date->EditCustomAttributes = "";
            $this->audit_date->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->audit_date->AdvancedSearch->SearchValue, 0), 8));
            $this->audit_date->PlaceHolder = RemoveHtml($this->audit_date->caption());

            // RowID
            $this->RowID->EditAttrs["class"] = "form-control";
            $this->RowID->EditCustomAttributes = "";
            $this->RowID->EditValue = HtmlEncode($this->RowID->AdvancedSearch->SearchValue);
            $this->RowID->PlaceHolder = RemoveHtml($this->RowID->caption());

            // expired_date
            $this->expired_date->EditAttrs["class"] = "form-control";
            $this->expired_date->EditCustomAttributes = "";
            $this->expired_date->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->expired_date->AdvancedSearch->SearchValue, 0), 8));
            $this->expired_date->PlaceHolder = RemoveHtml($this->expired_date->caption());

            // column1
            $this->column1->EditAttrs["class"] = "form-control";
            $this->column1->EditCustomAttributes = "";
            if (!$this->column1->Raw) {
                $this->column1->AdvancedSearch->SearchValue = HtmlDecode($this->column1->AdvancedSearch->SearchValue);
            }
            $this->column1->EditValue = HtmlEncode($this->column1->AdvancedSearch->SearchValue);
            $this->column1->PlaceHolder = RemoveHtml($this->column1->caption());

            // column2
            $this->column2->EditAttrs["class"] = "form-control";
            $this->column2->EditCustomAttributes = "";
            if (!$this->column2->Raw) {
                $this->column2->AdvancedSearch->SearchValue = HtmlDecode($this->column2->AdvancedSearch->SearchValue);
            }
            $this->column2->EditValue = HtmlEncode($this->column2->AdvancedSearch->SearchValue);
            $this->column2->PlaceHolder = RemoveHtml($this->column2->caption());

            // column3
            $this->column3->EditAttrs["class"] = "form-control";
            $this->column3->EditCustomAttributes = "";
            if (!$this->column3->Raw) {
                $this->column3->AdvancedSearch->SearchValue = HtmlDecode($this->column3->AdvancedSearch->SearchValue);
            }
            $this->column3->EditValue = HtmlEncode($this->column3->AdvancedSearch->SearchValue);
            $this->column3->PlaceHolder = RemoveHtml($this->column3->caption());

            // column4
            $this->column4->EditAttrs["class"] = "form-control";
            $this->column4->EditCustomAttributes = "";
            if (!$this->column4->Raw) {
                $this->column4->AdvancedSearch->SearchValue = HtmlDecode($this->column4->AdvancedSearch->SearchValue);
            }
            $this->column4->EditValue = HtmlEncode($this->column4->AdvancedSearch->SearchValue);
            $this->column4->PlaceHolder = RemoveHtml($this->column4->caption());

            // column5
            $this->column5->EditAttrs["class"] = "form-control";
            $this->column5->EditCustomAttributes = "";
            if (!$this->column5->Raw) {
                $this->column5->AdvancedSearch->SearchValue = HtmlDecode($this->column5->AdvancedSearch->SearchValue);
            }
            $this->column5->EditValue = HtmlEncode($this->column5->AdvancedSearch->SearchValue);
            $this->column5->PlaceHolder = RemoveHtml($this->column5->caption());

            // cf_user_failed_attempt
            $this->cf_user_failed_attempt->EditAttrs["class"] = "form-control";
            $this->cf_user_failed_attempt->EditCustomAttributes = "";
            $this->cf_user_failed_attempt->EditValue = HtmlEncode($this->cf_user_failed_attempt->AdvancedSearch->SearchValue);
            $this->cf_user_failed_attempt->PlaceHolder = RemoveHtml($this->cf_user_failed_attempt->caption());

            // cf_user_locked
            $this->cf_user_locked->EditAttrs["class"] = "form-control";
            $this->cf_user_locked->EditCustomAttributes = "";
            if (!$this->cf_user_locked->Raw) {
                $this->cf_user_locked->AdvancedSearch->SearchValue = HtmlDecode($this->cf_user_locked->AdvancedSearch->SearchValue);
            }
            $this->cf_user_locked->EditValue = HtmlEncode($this->cf_user_locked->AdvancedSearch->SearchValue);
            $this->cf_user_locked->PlaceHolder = RemoveHtml($this->cf_user_locked->caption());

            // password
            $this->_password->EditAttrs["class"] = "form-control";
            $this->_password->EditCustomAttributes = "";
            if (!$this->_password->Raw) {
                $this->_password->AdvancedSearch->SearchValue = HtmlDecode($this->_password->AdvancedSearch->SearchValue);
            }
            $this->_password->EditValue = HtmlEncode($this->_password->AdvancedSearch->SearchValue);
            $this->_password->PlaceHolder = RemoveHtml($this->_password->caption());

            // user_password
            $this->user_password->EditAttrs["class"] = "form-control";
            $this->user_password->EditCustomAttributes = "";
            if (!$this->user_password->Raw) {
                $this->user_password->AdvancedSearch->SearchValue = HtmlDecode($this->user_password->AdvancedSearch->SearchValue);
            }
            $this->user_password->EditValue = HtmlEncode($this->user_password->AdvancedSearch->SearchValue);
            $this->user_password->PlaceHolder = RemoveHtml($this->user_password->caption());
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate search
    protected function validateSearch()
    {
        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if (!CheckDate($this->last_login->AdvancedSearch->SearchValue)) {
            $this->last_login->addErrorMessage($this->last_login->getErrorMessage(false));
        }
        if (!CheckDate($this->last_pwd_changed->AdvancedSearch->SearchValue)) {
            $this->last_pwd_changed->addErrorMessage($this->last_pwd_changed->getErrorMessage(false));
        }
        if (!CheckDate($this->audit_date->AdvancedSearch->SearchValue)) {
            $this->audit_date->addErrorMessage($this->audit_date->getErrorMessage(false));
        }
        if (!CheckNumber($this->RowID->AdvancedSearch->SearchValue)) {
            $this->RowID->addErrorMessage($this->RowID->getErrorMessage(false));
        }
        if (!CheckDate($this->expired_date->AdvancedSearch->SearchValue)) {
            $this->expired_date->addErrorMessage($this->expired_date->getErrorMessage(false));
        }
        if (!CheckNumber($this->cf_user_failed_attempt->AdvancedSearch->SearchValue)) {
            $this->cf_user_failed_attempt->addErrorMessage($this->cf_user_failed_attempt->getErrorMessage(false));
        }

        // Return validate result
        $validateSearch = !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateSearch = $validateSearch && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateSearch;
    }

    // Load advanced search
    public function loadAdvancedSearch()
    {
        $this->empl_id->AdvancedSearch->load();
        $this->name->AdvancedSearch->load();
        $this->default_site->AdvancedSearch->load();
        $this->default_language->AdvancedSearch->load();
        $this->sys_admin->AdvancedSearch->load();
        $this->status->AdvancedSearch->load();
        $this->supv_empl_id->AdvancedSearch->load();
        $this->shift->AdvancedSearch->load();
        $this->work_area->AdvancedSearch->load();
        $this->work_grp->AdvancedSearch->load();
        $this->last_login_site->AdvancedSearch->load();
        $this->last_login->AdvancedSearch->load();
        $this->last_pwd_changed->AdvancedSearch->load();
        $this->audit_user->AdvancedSearch->load();
        $this->audit_date->AdvancedSearch->load();
        $this->RowID->AdvancedSearch->load();
        $this->expired_date->AdvancedSearch->load();
        $this->column1->AdvancedSearch->load();
        $this->column2->AdvancedSearch->load();
        $this->column3->AdvancedSearch->load();
        $this->column4->AdvancedSearch->load();
        $this->column5->AdvancedSearch->load();
        $this->cf_user_failed_attempt->AdvancedSearch->load();
        $this->cf_user_locked->AdvancedSearch->load();
        $this->_password->AdvancedSearch->load();
        $this->user_password->AdvancedSearch->load();
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("CfUserList"), "", $this->TableVar, true);
        $pageId = "search";
        $Breadcrumb->add("search", $pageId, $url);
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

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in CustomError
        return true;
    }
}
