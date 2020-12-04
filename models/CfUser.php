<?php

namespace PHPMaker2021\tooms;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for cf_user
 */
class CfUser extends DbTable
{
    protected $SqlFrom = "";
    protected $SqlSelect = null;
    protected $SqlSelectList = null;
    protected $SqlWhere = "";
    protected $SqlGroupBy = "";
    protected $SqlHaving = "";
    protected $SqlOrderBy = "";
    public $UseSessionForListSql = true;

    // Column CSS classes
    public $LeftColumnClass = "col-sm-2 col-form-label ew-label";
    public $RightColumnClass = "col-sm-10";
    public $OffsetColumnClass = "col-sm-10 offset-sm-2";
    public $TableLeftColumnClass = "w-col-2";

    // Export
    public $ExportDoc;

    // Fields
    public $empl_id;
    public $name;
    public $default_site;
    public $default_language;
    public $sys_admin;
    public $status;
    public $supv_empl_id;
    public $shift;
    public $work_area;
    public $work_grp;
    public $last_login_site;
    public $last_login;
    public $last_pwd_changed;
    public $audit_user;
    public $audit_date;
    public $RowID;
    public $expired_date;
    public $column1;
    public $column2;
    public $column3;
    public $column4;
    public $column5;
    public $cf_user_failed_attempt;
    public $cf_user_locked;
    public $_password;
    public $user_password;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'cf_user';
        $this->TableName = 'cf_user';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "[dbo].[cf_user]";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)
        $this->ExportExcelPageOrientation = ""; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = ""; // Page size (PhpSpreadsheet only)
        $this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = false; // Allow detail add
        $this->DetailEdit = false; // Allow detail edit
        $this->DetailView = false; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this->TableVar);

        // empl_id
        $this->empl_id = new DbField('cf_user', 'cf_user', 'x_empl_id', 'empl_id', '[empl_id]', '[empl_id]', 200, 50, -1, false, '[empl_id]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->empl_id->IsPrimaryKey = true; // Primary key field
        $this->empl_id->Nullable = false; // NOT NULL field
        $this->empl_id->Required = true; // Required field
        $this->empl_id->Sortable = true; // Allow sort
        $this->Fields['empl_id'] = &$this->empl_id;

        // name
        $this->name = new DbField('cf_user', 'cf_user', 'x_name', 'name', '[name]', '[name]', 200, 50, -1, false, '[name]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->name->Sortable = true; // Allow sort
        $this->Fields['name'] = &$this->name;

        // default_site
        $this->default_site = new DbField('cf_user', 'cf_user', 'x_default_site', 'default_site', '[default_site]', '[default_site]', 200, 4, -1, false, '[default_site]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->default_site->Sortable = true; // Allow sort
        $this->Fields['default_site'] = &$this->default_site;

        // default_language
        $this->default_language = new DbField('cf_user', 'cf_user', 'x_default_language', 'default_language', '[default_language]', '[default_language]', 202, 40, -1, false, '[default_language]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->default_language->Sortable = true; // Allow sort
        $this->Fields['default_language'] = &$this->default_language;

        // sys_admin
        $this->sys_admin = new DbField('cf_user', 'cf_user', 'x_sys_admin', 'sys_admin', '[sys_admin]', '[sys_admin]', 200, 1, -1, false, '[sys_admin]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->sys_admin->Sortable = true; // Allow sort
        $this->Fields['sys_admin'] = &$this->sys_admin;

        // status
        $this->status = new DbField('cf_user', 'cf_user', 'x_status', 'status', '[status]', '[status]', 200, 3, -1, false, '[status]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->status->Sortable = true; // Allow sort
        $this->Fields['status'] = &$this->status;

        // supv_empl_id
        $this->supv_empl_id = new DbField('cf_user', 'cf_user', 'x_supv_empl_id', 'supv_empl_id', '[supv_empl_id]', '[supv_empl_id]', 200, 25, -1, false, '[supv_empl_id]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->supv_empl_id->Sortable = true; // Allow sort
        $this->Fields['supv_empl_id'] = &$this->supv_empl_id;

        // shift
        $this->shift = new DbField('cf_user', 'cf_user', 'x_shift', 'shift', '[shift]', '[shift]', 200, 1, -1, false, '[shift]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->shift->Sortable = true; // Allow sort
        $this->Fields['shift'] = &$this->shift;

        // work_area
        $this->work_area = new DbField('cf_user', 'cf_user', 'x_work_area', 'work_area', '[work_area]', '[work_area]', 200, 15, -1, false, '[work_area]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->work_area->Sortable = true; // Allow sort
        $this->Fields['work_area'] = &$this->work_area;

        // work_grp
        $this->work_grp = new DbField('cf_user', 'cf_user', 'x_work_grp', 'work_grp', '[work_grp]', '[work_grp]', 200, 15, -1, false, '[work_grp]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->work_grp->Sortable = true; // Allow sort
        $this->Fields['work_grp'] = &$this->work_grp;

        // last_login_site
        $this->last_login_site = new DbField('cf_user', 'cf_user', 'x_last_login_site', 'last_login_site', '[last_login_site]', '[last_login_site]', 200, 4, -1, false, '[last_login_site]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->last_login_site->Sortable = true; // Allow sort
        $this->Fields['last_login_site'] = &$this->last_login_site;

        // last_login
        $this->last_login = new DbField('cf_user', 'cf_user', 'x_last_login', 'last_login', '[last_login]', CastDateFieldForLike("[last_login]", 0, "DB"), 135, 8, 0, false, '[last_login]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->last_login->Sortable = true; // Allow sort
        $this->last_login->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['last_login'] = &$this->last_login;

        // last_pwd_changed
        $this->last_pwd_changed = new DbField('cf_user', 'cf_user', 'x_last_pwd_changed', 'last_pwd_changed', '[last_pwd_changed]', CastDateFieldForLike("[last_pwd_changed]", 0, "DB"), 135, 8, 0, false, '[last_pwd_changed]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->last_pwd_changed->Sortable = true; // Allow sort
        $this->last_pwd_changed->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['last_pwd_changed'] = &$this->last_pwd_changed;

        // audit_user
        $this->audit_user = new DbField('cf_user', 'cf_user', 'x_audit_user', 'audit_user', '[audit_user]', '[audit_user]', 200, 50, -1, false, '[audit_user]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->audit_user->Sortable = true; // Allow sort
        $this->Fields['audit_user'] = &$this->audit_user;

        // audit_date
        $this->audit_date = new DbField('cf_user', 'cf_user', 'x_audit_date', 'audit_date', '[audit_date]', CastDateFieldForLike("[audit_date]", 0, "DB"), 135, 8, 0, false, '[audit_date]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->audit_date->Sortable = true; // Allow sort
        $this->audit_date->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['audit_date'] = &$this->audit_date;

        // RowID
        $this->RowID = new DbField('cf_user', 'cf_user', 'x_RowID', 'RowID', '[RowID]', 'CAST([RowID] AS NVARCHAR)', 131, 8, -1, false, '[RowID]', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->RowID->IsAutoIncrement = true; // Autoincrement field
        $this->RowID->Nullable = false; // NOT NULL field
        $this->RowID->Sortable = true; // Allow sort
        $this->RowID->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->Fields['RowID'] = &$this->RowID;

        // expired_date
        $this->expired_date = new DbField('cf_user', 'cf_user', 'x_expired_date', 'expired_date', '[expired_date]', CastDateFieldForLike("[expired_date]", 0, "DB"), 135, 8, 0, false, '[expired_date]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->expired_date->Sortable = true; // Allow sort
        $this->expired_date->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['expired_date'] = &$this->expired_date;

        // column1
        $this->column1 = new DbField('cf_user', 'cf_user', 'x_column1', 'column1', '[column1]', '[column1]', 200, 60, -1, false, '[column1]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->column1->Sortable = false; // Allow sort
        $this->Fields['column1'] = &$this->column1;

        // column2
        $this->column2 = new DbField('cf_user', 'cf_user', 'x_column2', 'column2', '[column2]', '[column2]', 200, 60, -1, false, '[column2]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->column2->Sortable = false; // Allow sort
        $this->Fields['column2'] = &$this->column2;

        // column3
        $this->column3 = new DbField('cf_user', 'cf_user', 'x_column3', 'column3', '[column3]', '[column3]', 200, 60, -1, false, '[column3]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->column3->Sortable = false; // Allow sort
        $this->Fields['column3'] = &$this->column3;

        // column4
        $this->column4 = new DbField('cf_user', 'cf_user', 'x_column4', 'column4', '[column4]', '[column4]', 200, 60, -1, false, '[column4]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->column4->Sortable = false; // Allow sort
        $this->Fields['column4'] = &$this->column4;

        // column5
        $this->column5 = new DbField('cf_user', 'cf_user', 'x_column5', 'column5', '[column5]', '[column5]', 200, 60, -1, false, '[column5]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->column5->Sortable = false; // Allow sort
        $this->Fields['column5'] = &$this->column5;

        // cf_user_failed_attempt
        $this->cf_user_failed_attempt = new DbField('cf_user', 'cf_user', 'x_cf_user_failed_attempt', 'cf_user_failed_attempt', '[cf_user_failed_attempt]', 'CAST([cf_user_failed_attempt] AS NVARCHAR)', 131, 8, -1, false, '[cf_user_failed_attempt]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cf_user_failed_attempt->Sortable = false; // Allow sort
        $this->cf_user_failed_attempt->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->cf_user_failed_attempt->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->Fields['cf_user_failed_attempt'] = &$this->cf_user_failed_attempt;

        // cf_user_locked
        $this->cf_user_locked = new DbField('cf_user', 'cf_user', 'x_cf_user_locked', 'cf_user_locked', '[cf_user_locked]', '[cf_user_locked]', 200, 1, -1, false, '[cf_user_locked]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cf_user_locked->Sortable = false; // Allow sort
        $this->Fields['cf_user_locked'] = &$this->cf_user_locked;

        // password
        $this->_password = new DbField('cf_user', 'cf_user', 'x__password', 'password', '[password]', '[password]', 200, 100, -1, false, '[password]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->_password->Sortable = false; // Allow sort
        $this->Fields['password'] = &$this->_password;

        // user_password
        $this->user_password = new DbField('cf_user', 'cf_user', 'x_user_password', 'user_password', '[user_password]', '[user_password]', 200, 100, -1, false, '[user_password]', false, false, false, 'FORMATTED TEXT', 'TEXT');
        if (Config("ENCRYPTED_PASSWORD")) {
            $this->user_password->Raw = true;
        }
        $this->user_password->Required = true; // Required field
        $this->user_password->Sortable = false; // Allow sort
        $this->Fields['user_password'] = &$this->user_password;
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
    public function setLeftColumnClass($class)
    {
        if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
            $this->LeftColumnClass = $class . " col-form-label ew-label";
            $this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - (int)$match[2]);
            $this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace("col-", "offset-", $class);
            $this->TableLeftColumnClass = preg_replace('/^col-\w+-(\d+)$/', "w-col-$1", $class); // Change to w-col-*
        }
    }

    // Single column sort
    public function updateSort(&$fld)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $fld->setSort($curSort);
            $orderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            $this->setSessionOrderBy($orderBy); // Save to Session
        } else {
            $fld->setSort("");
        }
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "[dbo].[cf_user]";
    }

    public function sqlFrom() // For backward compatibility
    {
        return $this->getSqlFrom();
    }

    public function setSqlFrom($v)
    {
        $this->SqlFrom = $v;
    }

    public function getSqlSelect() // Select
    {
        return $this->SqlSelect ?? $this->getQueryBuilder()->select("*");
    }

    public function sqlSelect() // For backward compatibility
    {
        return $this->getSqlSelect();
    }

    public function setSqlSelect($v)
    {
        $this->SqlSelect = $v;
    }

    public function getSqlWhere() // Where
    {
        $where = ($this->SqlWhere != "") ? $this->SqlWhere : "";
        $this->DefaultFilter = "";
        AddFilter($where, $this->DefaultFilter);
        return $where;
    }

    public function sqlWhere() // For backward compatibility
    {
        return $this->getSqlWhere();
    }

    public function setSqlWhere($v)
    {
        $this->SqlWhere = $v;
    }

    public function getSqlGroupBy() // Group By
    {
        return ($this->SqlGroupBy != "") ? $this->SqlGroupBy : "";
    }

    public function sqlGroupBy() // For backward compatibility
    {
        return $this->getSqlGroupBy();
    }

    public function setSqlGroupBy($v)
    {
        $this->SqlGroupBy = $v;
    }

    public function getSqlHaving() // Having
    {
        return ($this->SqlHaving != "") ? $this->SqlHaving : "";
    }

    public function sqlHaving() // For backward compatibility
    {
        return $this->getSqlHaving();
    }

    public function setSqlHaving($v)
    {
        $this->SqlHaving = $v;
    }

    public function getSqlOrderBy() // Order By
    {
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : $this->DefaultSort;
    }

    public function sqlOrderBy() // For backward compatibility
    {
        return $this->getSqlOrderBy();
    }

    public function setSqlOrderBy($v)
    {
        $this->SqlOrderBy = $v;
    }

    // Apply User ID filters
    public function applyUserIDFilters($filter)
    {
        return $filter;
    }

    // Check if User ID security allows view all
    public function userIDAllow($id = "")
    {
        $allow = $this->UserIDAllowSecurity;
        switch ($id) {
            case "add":
            case "copy":
            case "gridadd":
            case "register":
            case "addopt":
                return (($allow & 1) == 1);
            case "edit":
            case "gridedit":
            case "update":
            case "changepassword":
            case "resetpassword":
                return (($allow & 4) == 4);
            case "delete":
                return (($allow & 2) == 2);
            case "view":
                return (($allow & 32) == 32);
            case "search":
                return (($allow & 64) == 64);
            default:
                return (($allow & 8) == 8);
        }
    }

    /**
     * Get record count
     *
     * @param string|QueryBuilder $sql SQL or QueryBuilder
     * @param mixed $c Connection
     * @return int
     */
    public function getRecordCount($sql, $c = null)
    {
        $cnt = -1;
        $rs = null;
        if ($sql instanceof \Doctrine\DBAL\Query\QueryBuilder) { // Query builder
            $sql = $sql->resetQueryPart("orderBy")->getSQL();
        }
        $pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            ($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') &&
            preg_match($pattern, $sql) && !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sql) &&
            !preg_match('/^\s*select\s+distinct\s+/i', $sql) && !preg_match('/\s+order\s+by\s+/i', $sql)
        ) {
            $sqlwrk = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sql);
        } else {
            $sqlwrk = "SELECT COUNT(*) FROM (" . $sql . ") COUNT_TABLE";
        }
        $conn = $c ?? $this->getConnection();
        $rs = $conn->executeQuery($sqlwrk);
        $cnt = $rs->fetchColumn();
        if ($cnt !== false) {
            return (int)$cnt;
        }

        // Unable to get count by SELECT COUNT(*), execute the SQL to get record count directly
        return ExecuteRecordCount($sql, $conn);
    }

    // Get SQL
    public function getSql($where, $orderBy = "")
    {
        return $this->buildSelectSql(
            $this->getSqlSelect(),
            $this->getSqlFrom(),
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $where,
            $orderBy
        )->getSQL();
    }

    // Table SQL
    public function getCurrentSql()
    {
        $filter = $this->CurrentFilter;
        $filter = $this->applyUserIDFilters($filter);
        $sort = $this->getSessionOrderBy();
        return $this->getSql($filter, $sort);
    }

    /**
     * Table SQL with List page filter
     *
     * @return QueryBuilder
     */
    public function getListSql()
    {
        $filter = $this->UseSessionForListSql ? $this->getSessionWhere() : "";
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->getSqlSelect();
        $from = $this->getSqlFrom();
        $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        $this->Sort = $sort;
        return $this->buildSelectSql(
            $select,
            $from,
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $filter,
            $sort
        );
    }

    // Get ORDER BY clause
    public function getOrderBy()
    {
        $orderBy = $this->getSqlOrderBy();
        $sort = $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Get record count based on filter (for detail record count in master table pages)
    public function loadRecordCount($filter)
    {
        $origFilter = $this->CurrentFilter;
        $this->CurrentFilter = $filter;
        $this->recordsetSelecting($this->CurrentFilter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
        $cnt = $this->getRecordCount($sql);
        $this->CurrentFilter = $origFilter;
        return $cnt;
    }

    // Get record count (for current List page)
    public function listRecordCount()
    {
        $filter = $this->getSessionWhere();
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        $cnt = $this->getRecordCount($sql);
        return $cnt;
    }

    /**
     * INSERT statement
     *
     * @param mixed $rs
     * @return QueryBuilder
     */
    protected function insertSql(&$rs)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom) {
                continue;
            }
            if (Config("ENCRYPTED_PASSWORD") && $name == Config("LOGIN_PASSWORD_FIELD_NAME")) {
                $value = Config("CASE_SENSITIVE_PASSWORD") ? EncryptPassword($value) : EncryptPassword(strtolower($value));
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->setValue($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        return $queryBuilder;
    }

    // Insert
    public function insert(&$rs)
    {
        $conn = $this->getConnection();
        $success = $this->insertSql($rs)->execute();
        if ($success) {
            // Get insert id if necessary
            $this->RowID->setDbValue($conn->lastInsertId());
            $rs['RowID'] = $this->RowID->DbValue;
        }
        return $success;
    }

    /**
     * UPDATE statement
     *
     * @param array $rs Data to be updated
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    protected function updateSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom || $this->Fields[$name]->IsAutoIncrement) {
                continue;
            }
            if (Config("ENCRYPTED_PASSWORD") && $name == Config("LOGIN_PASSWORD_FIELD_NAME")) {
                if ($value == $this->Fields[$name]->OldValue) { // No need to update hashed password if not changed
                    continue;
                }
                $value = Config("CASE_SENSITIVE_PASSWORD") ? EncryptPassword($value) : EncryptPassword(strtolower($value));
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->set($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        AddFilter($filter, $where);
        if ($filter != "") {
            $queryBuilder->where($filter);
        }
        return $queryBuilder;
    }

    // Update
    public function update(&$rs, $where = "", $rsold = null, $curfilter = true)
    {
        // If no field is updated, execute may return 0. Treat as success
        $success = $this->updateSql($rs, $where, $curfilter)->execute();
        $success = ($success > 0) ? $success : true;
        return $success;
    }

    /**
     * DELETE statement
     *
     * @param array $rs Key values
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    protected function deleteSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($this->UpdateTable);
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        if ($rs) {
            if (array_key_exists('empl_id', $rs)) {
                AddFilter($where, QuotedName('empl_id', $this->Dbid) . '=' . QuotedValue($rs['empl_id'], $this->empl_id->DataType, $this->Dbid));
            }
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        AddFilter($filter, $where);
        return $queryBuilder->where($filter != "" ? $filter : "0=1");
    }

    // Delete
    public function delete(&$rs, $where = "", $curfilter = false)
    {
        $success = true;
        if ($success) {
            $success = $this->deleteSql($rs, $where, $curfilter)->execute();
        }
        return $success;
    }

    // Load DbValue from recordset or array
    protected function loadDbValues($row)
    {
        if (!is_array($row)) {
            return;
        }
        $this->empl_id->DbValue = $row['empl_id'];
        $this->name->DbValue = $row['name'];
        $this->default_site->DbValue = $row['default_site'];
        $this->default_language->DbValue = $row['default_language'];
        $this->sys_admin->DbValue = $row['sys_admin'];
        $this->status->DbValue = $row['status'];
        $this->supv_empl_id->DbValue = $row['supv_empl_id'];
        $this->shift->DbValue = $row['shift'];
        $this->work_area->DbValue = $row['work_area'];
        $this->work_grp->DbValue = $row['work_grp'];
        $this->last_login_site->DbValue = $row['last_login_site'];
        $this->last_login->DbValue = $row['last_login'];
        $this->last_pwd_changed->DbValue = $row['last_pwd_changed'];
        $this->audit_user->DbValue = $row['audit_user'];
        $this->audit_date->DbValue = $row['audit_date'];
        $this->RowID->DbValue = $row['RowID'];
        $this->expired_date->DbValue = $row['expired_date'];
        $this->column1->DbValue = $row['column1'];
        $this->column2->DbValue = $row['column2'];
        $this->column3->DbValue = $row['column3'];
        $this->column4->DbValue = $row['column4'];
        $this->column5->DbValue = $row['column5'];
        $this->cf_user_failed_attempt->DbValue = $row['cf_user_failed_attempt'];
        $this->cf_user_locked->DbValue = $row['cf_user_locked'];
        $this->_password->DbValue = $row['password'];
        $this->user_password->DbValue = $row['user_password'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "[empl_id] = '@empl_id@'";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->empl_id->CurrentValue : $this->empl_id->OldValue;
        if (EmptyValue($val)) {
            return "";
        } else {
            $keys[] = $val;
        }
        return implode(Config("COMPOSITE_KEY_SEPARATOR"), $keys);
    }

    // Set Key
    public function setKey($key, $current = false)
    {
        $this->OldKey = strval($key);
        $keys = explode(Config("COMPOSITE_KEY_SEPARATOR"), $this->OldKey);
        if (count($keys) == 1) {
            if ($current) {
                $this->empl_id->CurrentValue = $keys[0];
            } else {
                $this->empl_id->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('empl_id', $row) ? $row['empl_id'] : null;
        } else {
            $val = $this->empl_id->OldValue !== null ? $this->empl_id->OldValue : $this->empl_id->CurrentValue;
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@empl_id@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
        }
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $referUrl = ReferUrl();
        $referPageName = ReferPageName();
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if ($referUrl != "" && $referPageName != CurrentPageName() && $referPageName != "login") { // Referer not same page or login page
            $_SESSION[$name] = $referUrl; // Save to Session
        }
        return $_SESSION[$name] ?? GetUrl("CfUserList");
    }

    // Set return page URL
    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        if ($pageName == "CfUserView") {
            return $Language->phrase("View");
        } elseif ($pageName == "CfUserEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "CfUserAdd") {
            return $Language->phrase("Add");
        } else {
            return "";
        }
    }

    // API page name
    public function getApiPageName($action)
    {
        switch (strtolower($action)) {
            case Config("API_VIEW_ACTION"):
                return "CfUserView";
            case Config("API_ADD_ACTION"):
                return "CfUserAdd";
            case Config("API_EDIT_ACTION"):
                return "CfUserEdit";
            case Config("API_DELETE_ACTION"):
                return "CfUserDelete";
            case Config("API_LIST_ACTION"):
                return "CfUserList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "CfUserList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("CfUserView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("CfUserView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "CfUserAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "CfUserAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("CfUserEdit", $this->getUrlParm($parm));
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=edit"));
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("CfUserAdd", $this->getUrlParm($parm));
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=copy"));
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl()
    {
        return $this->keyUrl("CfUserDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "empl_id:" . JsonEncode($this->empl_id->CurrentValue, "string");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->empl_id->CurrentValue !== null) {
            $url .= "/" . rawurlencode($this->empl_id->CurrentValue);
        } else {
            return "javascript:ew.alert(ew.language.phrase('InvalidRecord'));";
        }
        if ($parm != "") {
            $url .= "?" . $parm;
        }
        return $url;
    }

    // Render sort
    public function renderSort($fld)
    {
        $classId = $fld->TableVar . "_" . $fld->Param;
        $scriptId = str_replace("%id%", $classId, "tpc_%id%");
        $scriptStart = $this->UseCustomTemplate ? "<template id=\"" . $scriptId . "\">" : "";
        $scriptEnd = $this->UseCustomTemplate ? "</template>" : "";
        $jsSort = " class=\"ew-pointer\" onclick=\"ew.sort(event, '" . $this->sortUrl($fld) . "', 1);\"";
        if ($this->sortUrl($fld) == "") {
            $html = <<<NOSORTHTML
{$scriptStart}<div class="ew-table-header-caption">{$fld->caption()}</div>{$scriptEnd}
NOSORTHTML;
        } else {
            if ($fld->getSort() == "ASC") {
                $sortIcon = '<i class="fas fa-sort-up"></i>';
            } elseif ($fld->getSort() == "DESC") {
                $sortIcon = '<i class="fas fa-sort-down"></i>';
            } else {
                $sortIcon = '';
            }
            $html = <<<SORTHTML
{$scriptStart}<div{$jsSort}><div class="ew-table-header-btn"><span class="ew-table-header-caption">{$fld->caption()}</span><span class="ew-table-header-sort">{$sortIcon}</span></div></div>{$scriptEnd}
SORTHTML;
        }
        return $html;
    }

    // Sort URL
    public function sortUrl($fld)
    {
        if (
            $this->CurrentAction || $this->isExport() ||
            in_array($fld->Type, [141, 201, 203, 128, 204, 205])
        ) { // Unsortable data type
                return "";
        } elseif ($fld->Sortable) {
            $urlParm = $this->getUrlParm("order=" . urlencode($fld->Name) . "&amp;ordertype=" . $fld->getNextSort());
            return $this->addMasterUrl(CurrentPageName() . "?" . $urlParm);
        } else {
            return "";
        }
    }

    // Get record keys from Post/Get/Session
    public function getRecordKeys()
    {
        $arKeys = [];
        $arKey = [];
        if (Param("key_m") !== null) {
            $arKeys = Param("key_m");
            $cnt = count($arKeys);
        } else {
            if (($keyValue = Param("empl_id") ?? Route("empl_id")) !== null) {
                $arKeys[] = $keyValue;
            } elseif (IsApi() && (($keyValue = Key(0) ?? Route(2)) !== null)) {
                $arKeys[] = $keyValue;
            } else {
                $arKeys = null; // Do not setup
            }

            //return $arKeys; // Do not return yet, so the values will also be checked by the following code
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                $ar[] = $key;
            }
        }
        return $ar;
    }

    // Get filter from record keys
    public function getFilterFromRecordKeys($setCurrent = true)
    {
        $arKeys = $this->getRecordKeys();
        $keyFilter = "";
        foreach ($arKeys as $key) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            if ($setCurrent) {
                $this->empl_id->CurrentValue = $key;
            } else {
                $this->empl_id->OldValue = $key;
            }
            $keyFilter .= "(" . $this->getRecordFilter() . ")";
        }
        return $keyFilter;
    }

    // Load recordset based on filter
    public function &loadRs($filter)
    {
        $sql = $this->getSql($filter); // Set up filter (WHERE Clause)
        $conn = $this->getConnection();
        $stmt = $conn->executeQuery($sql);
        return $stmt;
    }

    // Load row values from record
    public function loadListRowValues(&$rs)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
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

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

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

        // Call Row Rendered event
        $this->rowRendered();

        // Save data for Custom Template
        $this->Rows[] = $this->customTemplateFieldValues();
    }

    // Render edit row values
    public function renderEditRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // empl_id
        $this->empl_id->EditAttrs["class"] = "form-control";
        $this->empl_id->EditCustomAttributes = "";
        if (!$this->empl_id->Raw) {
            $this->empl_id->CurrentValue = HtmlDecode($this->empl_id->CurrentValue);
        }
        $this->empl_id->EditValue = $this->empl_id->CurrentValue;
        $this->empl_id->PlaceHolder = RemoveHtml($this->empl_id->caption());

        // name
        $this->name->EditAttrs["class"] = "form-control";
        $this->name->EditCustomAttributes = "";
        if (!$this->name->Raw) {
            $this->name->CurrentValue = HtmlDecode($this->name->CurrentValue);
        }
        $this->name->EditValue = $this->name->CurrentValue;
        $this->name->PlaceHolder = RemoveHtml($this->name->caption());

        // default_site
        $this->default_site->EditAttrs["class"] = "form-control";
        $this->default_site->EditCustomAttributes = "";
        if (!$this->default_site->Raw) {
            $this->default_site->CurrentValue = HtmlDecode($this->default_site->CurrentValue);
        }
        $this->default_site->EditValue = $this->default_site->CurrentValue;
        $this->default_site->PlaceHolder = RemoveHtml($this->default_site->caption());

        // default_language
        $this->default_language->EditAttrs["class"] = "form-control";
        $this->default_language->EditCustomAttributes = "";
        if (!$this->default_language->Raw) {
            $this->default_language->CurrentValue = HtmlDecode($this->default_language->CurrentValue);
        }
        $this->default_language->EditValue = $this->default_language->CurrentValue;
        $this->default_language->PlaceHolder = RemoveHtml($this->default_language->caption());

        // sys_admin
        $this->sys_admin->EditAttrs["class"] = "form-control";
        $this->sys_admin->EditCustomAttributes = "";
        if (!$this->sys_admin->Raw) {
            $this->sys_admin->CurrentValue = HtmlDecode($this->sys_admin->CurrentValue);
        }
        $this->sys_admin->EditValue = $this->sys_admin->CurrentValue;
        $this->sys_admin->PlaceHolder = RemoveHtml($this->sys_admin->caption());

        // status
        $this->status->EditAttrs["class"] = "form-control";
        $this->status->EditCustomAttributes = "";
        if (!$this->status->Raw) {
            $this->status->CurrentValue = HtmlDecode($this->status->CurrentValue);
        }
        $this->status->EditValue = $this->status->CurrentValue;
        $this->status->PlaceHolder = RemoveHtml($this->status->caption());

        // supv_empl_id
        $this->supv_empl_id->EditAttrs["class"] = "form-control";
        $this->supv_empl_id->EditCustomAttributes = "";
        if (!$this->supv_empl_id->Raw) {
            $this->supv_empl_id->CurrentValue = HtmlDecode($this->supv_empl_id->CurrentValue);
        }
        $this->supv_empl_id->EditValue = $this->supv_empl_id->CurrentValue;
        $this->supv_empl_id->PlaceHolder = RemoveHtml($this->supv_empl_id->caption());

        // shift
        $this->shift->EditAttrs["class"] = "form-control";
        $this->shift->EditCustomAttributes = "";
        if (!$this->shift->Raw) {
            $this->shift->CurrentValue = HtmlDecode($this->shift->CurrentValue);
        }
        $this->shift->EditValue = $this->shift->CurrentValue;
        $this->shift->PlaceHolder = RemoveHtml($this->shift->caption());

        // work_area
        $this->work_area->EditAttrs["class"] = "form-control";
        $this->work_area->EditCustomAttributes = "";
        if (!$this->work_area->Raw) {
            $this->work_area->CurrentValue = HtmlDecode($this->work_area->CurrentValue);
        }
        $this->work_area->EditValue = $this->work_area->CurrentValue;
        $this->work_area->PlaceHolder = RemoveHtml($this->work_area->caption());

        // work_grp
        $this->work_grp->EditAttrs["class"] = "form-control";
        $this->work_grp->EditCustomAttributes = "";
        if (!$this->work_grp->Raw) {
            $this->work_grp->CurrentValue = HtmlDecode($this->work_grp->CurrentValue);
        }
        $this->work_grp->EditValue = $this->work_grp->CurrentValue;
        $this->work_grp->PlaceHolder = RemoveHtml($this->work_grp->caption());

        // last_login_site
        $this->last_login_site->EditAttrs["class"] = "form-control";
        $this->last_login_site->EditCustomAttributes = "";
        if (!$this->last_login_site->Raw) {
            $this->last_login_site->CurrentValue = HtmlDecode($this->last_login_site->CurrentValue);
        }
        $this->last_login_site->EditValue = $this->last_login_site->CurrentValue;
        $this->last_login_site->PlaceHolder = RemoveHtml($this->last_login_site->caption());

        // last_login
        $this->last_login->EditAttrs["class"] = "form-control";
        $this->last_login->EditCustomAttributes = "";
        $this->last_login->EditValue = FormatDateTime($this->last_login->CurrentValue, 8);
        $this->last_login->PlaceHolder = RemoveHtml($this->last_login->caption());

        // last_pwd_changed
        $this->last_pwd_changed->EditAttrs["class"] = "form-control";
        $this->last_pwd_changed->EditCustomAttributes = "";
        $this->last_pwd_changed->EditValue = FormatDateTime($this->last_pwd_changed->CurrentValue, 8);
        $this->last_pwd_changed->PlaceHolder = RemoveHtml($this->last_pwd_changed->caption());

        // audit_user
        $this->audit_user->EditAttrs["class"] = "form-control";
        $this->audit_user->EditCustomAttributes = "";
        if (!$this->audit_user->Raw) {
            $this->audit_user->CurrentValue = HtmlDecode($this->audit_user->CurrentValue);
        }
        $this->audit_user->EditValue = $this->audit_user->CurrentValue;
        $this->audit_user->PlaceHolder = RemoveHtml($this->audit_user->caption());

        // audit_date
        $this->audit_date->EditAttrs["class"] = "form-control";
        $this->audit_date->EditCustomAttributes = "";
        $this->audit_date->EditValue = FormatDateTime($this->audit_date->CurrentValue, 8);
        $this->audit_date->PlaceHolder = RemoveHtml($this->audit_date->caption());

        // RowID
        $this->RowID->EditAttrs["class"] = "form-control";
        $this->RowID->EditCustomAttributes = "";
        $this->RowID->EditValue = $this->RowID->CurrentValue;
        $this->RowID->PlaceHolder = RemoveHtml($this->RowID->caption());

        // expired_date
        $this->expired_date->EditAttrs["class"] = "form-control";
        $this->expired_date->EditCustomAttributes = "";
        $this->expired_date->EditValue = FormatDateTime($this->expired_date->CurrentValue, 8);
        $this->expired_date->PlaceHolder = RemoveHtml($this->expired_date->caption());

        // column1
        $this->column1->EditAttrs["class"] = "form-control";
        $this->column1->EditCustomAttributes = "";
        if (!$this->column1->Raw) {
            $this->column1->CurrentValue = HtmlDecode($this->column1->CurrentValue);
        }
        $this->column1->EditValue = $this->column1->CurrentValue;
        $this->column1->PlaceHolder = RemoveHtml($this->column1->caption());

        // column2
        $this->column2->EditAttrs["class"] = "form-control";
        $this->column2->EditCustomAttributes = "";
        if (!$this->column2->Raw) {
            $this->column2->CurrentValue = HtmlDecode($this->column2->CurrentValue);
        }
        $this->column2->EditValue = $this->column2->CurrentValue;
        $this->column2->PlaceHolder = RemoveHtml($this->column2->caption());

        // column3
        $this->column3->EditAttrs["class"] = "form-control";
        $this->column3->EditCustomAttributes = "";
        if (!$this->column3->Raw) {
            $this->column3->CurrentValue = HtmlDecode($this->column3->CurrentValue);
        }
        $this->column3->EditValue = $this->column3->CurrentValue;
        $this->column3->PlaceHolder = RemoveHtml($this->column3->caption());

        // column4
        $this->column4->EditAttrs["class"] = "form-control";
        $this->column4->EditCustomAttributes = "";
        if (!$this->column4->Raw) {
            $this->column4->CurrentValue = HtmlDecode($this->column4->CurrentValue);
        }
        $this->column4->EditValue = $this->column4->CurrentValue;
        $this->column4->PlaceHolder = RemoveHtml($this->column4->caption());

        // column5
        $this->column5->EditAttrs["class"] = "form-control";
        $this->column5->EditCustomAttributes = "";
        if (!$this->column5->Raw) {
            $this->column5->CurrentValue = HtmlDecode($this->column5->CurrentValue);
        }
        $this->column5->EditValue = $this->column5->CurrentValue;
        $this->column5->PlaceHolder = RemoveHtml($this->column5->caption());

        // cf_user_failed_attempt
        $this->cf_user_failed_attempt->EditAttrs["class"] = "form-control";
        $this->cf_user_failed_attempt->EditCustomAttributes = "";
        $this->cf_user_failed_attempt->EditValue = $this->cf_user_failed_attempt->CurrentValue;
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
        $this->cf_user_locked->EditValue = $this->cf_user_locked->CurrentValue;
        $this->cf_user_locked->PlaceHolder = RemoveHtml($this->cf_user_locked->caption());

        // password
        $this->_password->EditAttrs["class"] = "form-control";
        $this->_password->EditCustomAttributes = "";
        if (!$this->_password->Raw) {
            $this->_password->CurrentValue = HtmlDecode($this->_password->CurrentValue);
        }
        $this->_password->EditValue = $this->_password->CurrentValue;
        $this->_password->PlaceHolder = RemoveHtml($this->_password->caption());

        // user_password
        $this->user_password->EditAttrs["class"] = "form-control";
        $this->user_password->EditCustomAttributes = "";
        if (!$this->user_password->Raw) {
            $this->user_password->CurrentValue = HtmlDecode($this->user_password->CurrentValue);
        }
        $this->user_password->EditValue = $this->user_password->CurrentValue;
        $this->user_password->PlaceHolder = RemoveHtml($this->user_password->caption());

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
        // Call Row Rendered event
        $this->rowRendered();
    }

    // Export data in HTML/CSV/Word/Excel/Email/PDF format
    public function exportDocument($doc, $recordset, $startRec = 1, $stopRec = 1, $exportPageType = "")
    {
        if (!$recordset || !$doc) {
            return;
        }
        if (!$doc->ExportCustom) {
            // Write header
            $doc->exportTableHeader();
            if ($doc->Horizontal) { // Horizontal format, write header
                $doc->beginExportRow();
                if ($exportPageType == "view") {
                    $doc->exportCaption($this->empl_id);
                    $doc->exportCaption($this->name);
                    $doc->exportCaption($this->default_site);
                    $doc->exportCaption($this->default_language);
                    $doc->exportCaption($this->sys_admin);
                    $doc->exportCaption($this->status);
                    $doc->exportCaption($this->supv_empl_id);
                    $doc->exportCaption($this->shift);
                    $doc->exportCaption($this->work_area);
                    $doc->exportCaption($this->work_grp);
                    $doc->exportCaption($this->last_login_site);
                    $doc->exportCaption($this->last_login);
                    $doc->exportCaption($this->last_pwd_changed);
                    $doc->exportCaption($this->audit_user);
                    $doc->exportCaption($this->audit_date);
                    $doc->exportCaption($this->RowID);
                    $doc->exportCaption($this->expired_date);
                    $doc->exportCaption($this->column1);
                    $doc->exportCaption($this->column2);
                    $doc->exportCaption($this->column3);
                    $doc->exportCaption($this->column4);
                    $doc->exportCaption($this->column5);
                    $doc->exportCaption($this->cf_user_failed_attempt);
                    $doc->exportCaption($this->cf_user_locked);
                    $doc->exportCaption($this->_password);
                    $doc->exportCaption($this->user_password);
                } else {
                    $doc->exportCaption($this->empl_id);
                    $doc->exportCaption($this->name);
                    $doc->exportCaption($this->default_site);
                    $doc->exportCaption($this->default_language);
                    $doc->exportCaption($this->sys_admin);
                    $doc->exportCaption($this->status);
                    $doc->exportCaption($this->supv_empl_id);
                    $doc->exportCaption($this->shift);
                    $doc->exportCaption($this->work_area);
                    $doc->exportCaption($this->work_grp);
                    $doc->exportCaption($this->last_login_site);
                    $doc->exportCaption($this->last_login);
                    $doc->exportCaption($this->last_pwd_changed);
                    $doc->exportCaption($this->audit_user);
                    $doc->exportCaption($this->audit_date);
                    $doc->exportCaption($this->RowID);
                    $doc->exportCaption($this->expired_date);
                }
                $doc->endExportRow();
            }
        }

        // Move to first record
        $recCnt = $startRec - 1;
        $stopRec = ($stopRec > 0) ? $stopRec : PHP_INT_MAX;
        while (!$recordset->EOF && $recCnt < $stopRec) {
            $row = $recordset->fields;
            $recCnt++;
            if ($recCnt >= $startRec) {
                $rowCnt = $recCnt - $startRec + 1;

                // Page break
                if ($this->ExportPageBreakCount > 0) {
                    if ($rowCnt > 1 && ($rowCnt - 1) % $this->ExportPageBreakCount == 0) {
                        $doc->exportPageBreak();
                    }
                }
                $this->loadListRowValues($row);

                // Render row
                $this->RowType = ROWTYPE_VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->empl_id);
                        $doc->exportField($this->name);
                        $doc->exportField($this->default_site);
                        $doc->exportField($this->default_language);
                        $doc->exportField($this->sys_admin);
                        $doc->exportField($this->status);
                        $doc->exportField($this->supv_empl_id);
                        $doc->exportField($this->shift);
                        $doc->exportField($this->work_area);
                        $doc->exportField($this->work_grp);
                        $doc->exportField($this->last_login_site);
                        $doc->exportField($this->last_login);
                        $doc->exportField($this->last_pwd_changed);
                        $doc->exportField($this->audit_user);
                        $doc->exportField($this->audit_date);
                        $doc->exportField($this->RowID);
                        $doc->exportField($this->expired_date);
                        $doc->exportField($this->column1);
                        $doc->exportField($this->column2);
                        $doc->exportField($this->column3);
                        $doc->exportField($this->column4);
                        $doc->exportField($this->column5);
                        $doc->exportField($this->cf_user_failed_attempt);
                        $doc->exportField($this->cf_user_locked);
                        $doc->exportField($this->_password);
                        $doc->exportField($this->user_password);
                    } else {
                        $doc->exportField($this->empl_id);
                        $doc->exportField($this->name);
                        $doc->exportField($this->default_site);
                        $doc->exportField($this->default_language);
                        $doc->exportField($this->sys_admin);
                        $doc->exportField($this->status);
                        $doc->exportField($this->supv_empl_id);
                        $doc->exportField($this->shift);
                        $doc->exportField($this->work_area);
                        $doc->exportField($this->work_grp);
                        $doc->exportField($this->last_login_site);
                        $doc->exportField($this->last_login);
                        $doc->exportField($this->last_pwd_changed);
                        $doc->exportField($this->audit_user);
                        $doc->exportField($this->audit_date);
                        $doc->exportField($this->RowID);
                        $doc->exportField($this->expired_date);
                    }
                    $doc->endExportRow($rowCnt);
                }
            }

            // Call Row Export server event
            if ($doc->ExportCustom) {
                $this->rowExport($row);
            }
            $recordset->moveNext();
        }
        if (!$doc->ExportCustom) {
            $doc->exportTableFooter();
        }
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        // No binary fields
        return false;
    }

    // Table level events

    // Recordset Selecting event
    public function recordsetSelecting(&$filter)
    {
        // Enter your code here
    }

    // Recordset Selected event
    public function recordsetSelected(&$rs)
    {
        //Log("Recordset Selected");
    }

    // Recordset Search Validated event
    public function recordsetSearchValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Recordset Searching event
    public function recordsetSearching(&$filter)
    {
        // Enter your code here
    }

    // Row_Selecting event
    public function rowSelecting(&$filter)
    {
        // Enter your code here
    }

    // Row Selected event
    public function rowSelected(&$rs)
    {
        //Log("Row Selected");
    }

    // Row Inserting event
    public function rowInserting($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew)
    {
        //Log("Row Inserted");
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Updated event
    public function rowUpdated($rsold, &$rsnew)
    {
        //Log("Row Updated");
    }

    // Row Update Conflict event
    public function rowUpdateConflict($rsold, &$rsnew)
    {
        // Enter your code here
        // To ignore conflict, set return value to false
        return true;
    }

    // Grid Inserting event
    public function gridInserting()
    {
        // Enter your code here
        // To reject grid insert, set return value to false
        return true;
    }

    // Grid Inserted event
    public function gridInserted($rsnew)
    {
        //Log("Grid Inserted");
    }

    // Grid Updating event
    public function gridUpdating($rsold)
    {
        // Enter your code here
        // To reject grid update, set return value to false
        return true;
    }

    // Grid Updated event
    public function gridUpdated($rsold, $rsnew)
    {
        //Log("Grid Updated");
    }

    // Row Deleting event
    public function rowDeleting(&$rs)
    {
        // Enter your code here
        // To cancel, set return value to False
        return true;
    }

    // Row Deleted event
    public function rowDeleted(&$rs)
    {
        //Log("Row Deleted");
    }

    // Email Sending event
    public function emailSending($email, &$args)
    {
        //var_dump($email); var_dump($args); exit();
        return true;
    }

    // Lookup Selecting event
    public function lookupSelecting($fld, &$filter)
    {
        //var_dump($fld->Name, $fld->Lookup, $filter); // Uncomment to view the filter
        // Enter your code here
    }

    // Row Rendering event
    public function rowRendering()
    {
        // Enter your code here
    }

    // Row Rendered event
    public function rowRendered()
    {
        // To view properties of field class, use:
        //var_dump($this-><FieldName>);
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
