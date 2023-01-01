<?php

class tableData extends Database {
    private $connection;
    private $raw_query = array();
    private $select = "";
    private $from = "";
    private $where = "";
    private $paging = "";
    private $sorting = "";
    private $params = array();
    private $raw_sql = null;
    private $search_value = null;
    private $columns = array();
    private $searchable = array();
    private $total_rows;
    private $limit = 0;
    private $offset = 0;

    public function __construct(array $query)
    {
        try {
            parent::__construct();
            $this->connection = $this->conn;

            // set raw query
            $this->raw_query = $query;

            // set data tables columns
            $this->setColumns();

            // compose query
            $this->setQueryParams();
            $this->setQuerySelect();
            $this->setQueryFrom();
            $this->setQueryWhere();
            $this->setQueryPaging();
            $this->setQuerySorting();

            // set total rows
            $this->setTotalRows();
        } catch (PDOException $e) {
            error_log("Failed to connect to database: ".$e->getMessage());
        }
    }

    /**
     * Set query select
     */
    private function setQuerySelect()
    {
        if (!isset($this->query['select']) && count($this->getColumns()) <= 0) {
            throw new Exception(basename(__FILE__, '.php') . " error: " . "The SELECT statement was not found! Be sure to pass the columns array or set the select value in the query array");
        }

        $this->select = isset($this->query['select']) ? $this->query["select"] : " SELECT " . implode(" , ", $this->getColumns());
    }

    /**
     * Set query from
     */
    private function setQueryFrom()
    {
        $this->from = self::required($this->raw_query, "from");
    }

    /**
     * Set query where
     */
    private function setQueryWhere()
    {
        // get where condition passed during instantiation
        $where = self::optional($this->raw_query, "where");

        // check if a search value is passed
        $this->search_value = self::optional($_REQUEST['search'], "value");

        if ($this->search_value) {
            $searchable_columns_statements = array();

            // get searchable fields from data tables plugin
            foreach ($this->searchable as $column) {
                /**
                 * Replace dot by underscore, in case a column name is table.field
                 * (e.g: users.name). This is needed to avoid an error during the
                 * binding process
                 */
                $column_formatted = str_replace(".", "_", $column);

                $searchable_columns_statements[] = " $column like :$column_formatted ";
                $this->params[$column_formatted] = trim("%" . $this->search_value . "%");
            }

            if (count($searchable_columns_statements) > 0) {
                $searchable_where = implode(" OR ", $searchable_columns_statements);
                $this->where = is_null($where) ? " WHERE $searchable_where " :  " $where AND (" . implode(" OR ", $searchable_columns_statements) . ")";
            }
        } else {
            $this->where = $where;
        }
    }

    /**
     * Set query paging
     *
     * Compose the pagination for the results.
     */
    private function setQueryPaging()
    {
        $this->limit = self::optional($_REQUEST, 'length', 10);
        $this->offset = self::optional($_REQUEST, 'start', 0);
        $this->paging = " LIMIT {$this->limit} OFFSET {$this->offset} ";
    }

    /**
     * Set query sorting
     */
    private function setQuerySorting()
    {
        // get column and direction (order)
        $items = self::optional($_REQUEST, 'order', array());

        if (count($items) > 0) {
            $fields = array();

            foreach ($items as $item) {
                $column_index = $item['column'];
                $field_name = $this->columns[$column_index];
                $fields[] = $field_name . " " . $item['dir'];
            }
            $str = implode(',', $fields);
            $this->sorting = " ORDER BY $str";
        }
    }

    /**
     * Set query parameters
     *
     * Parameters that are used in the where statement to filter the results
     */
    private function setQueryParams()
    {
        $this->params = self::optional($this->raw_query, 'params', array());
    }

    /**
     * Set Columns
     *
     * Set columns property value from the request array passed by data table client api
     */
    private function setColumns()
    {
        $cols = self::optional($_REQUEST, 'columns', array());

        foreach ($cols as $column) {
            if (!empty($column['name'])) {
                $columns[] = $column['name'];

                if (filter_var($column['searchable'], FILTER_VALIDATE_BOOLEAN)) {
                    $searchable[] = $column['name'];
                }
            }
            
        }

        $this->columns = $columns;
        $this->searchable = $searchable;
    }

    /**
     * Get columns
     *
     * @return array
     */
    private function getColumns()
    {
        return $this->columns;
    }

    /**
     * Set Total Rows
     */
    private function setTotalRows()
    {
        // Get the raw variables passed
        $where = self::optional($this->raw_query, "where");
        $params = self::optional($this->raw_query, "params", array());

        $sql = "SELECT COUNT(*) {$this->from} $where";
        
        $stmt  = $this->conn->prepare($sql);
        $stmt->execute($params);
        $this->total_rows = $stmt->fetchColumn();
    }

    /**
     * Get total rows filtered
     *
     * @return integer
     */
    private function getTotalRowsFiltered()
    {
        $sql = "SELECT COUNT(*) {$this->from} {$this->where}";
        $stmt  = $this->connection->prepare($sql);
        $stmt->execute($this->params);

        return $stmt->fetchColumn();
    }

    /**
     * Get total rows
     *
     * @return integer
     */
    public function getTotalRows()
    {
        return $this->total_rows;
    }

    /**
     * Get the json representation of the query result needed by data tables
     * @return json
     */
    public function get()
    {
        // data processing
        $results = $this->search();
        $data = array();
        
        // get rows
        foreach ($results as $row) {
            $nestedData = array();

            // get column's index
            foreach ($this->columns as $index => $value) {
                $nestedData[] = $row[$index];
            }

            $data[] = $nestedData;
        }

        // total rows filtered
        $total_rows_filtered = ($this->search_value) ? $this->getTotalRowsFiltered() : null;

        // data
        $json_data = array(
            /**
             * For every request/draw by client side , they send a number as a parameter, when they receive
             * a response/data they first check the draw number, so we are sending same number in draw.
             */
            "draw" => intval(self::optional($_REQUEST, 'draw', 0)),
            "recordsTotal" => intval($this->getTotalRows()), //total number of records before filtering
            "recordsFiltered" => intval(($total_rows_filtered) ? $total_rows_filtered : $this->getTotalRows()), //total number of records after searching
            "data" => $data
        );

        return $json_data;
        // echo json_encode($json_data);  //send data as json format
    }

    /**
     * Search for some value
     *
     * @return array
     */
    public function search()
    {
        $sql =  $this->select . $this->from . $this->where . $this->sorting . $this->paging;
        // echo $sql;exit;
        $stmt  = $this->connection->prepare($sql);
        $stmt->execute($this->params);
        $this->raw_sql = $sql;

        return $stmt->fetchAll();
    }

    /**
     * Get raw sql
     *
     * @return string
     */
    public function getRawSql()
    {
        $sql = $this->raw_sql;
        foreach ($this->params as $key => $value) {
            $sql = str_replace(":$key", $value, $sql);
        }

        return $sql;
    }

    /**
     * Get a parameter
     *
     * This method is used to validate if a required parameter was passed from the client,
     * if it is found then return its value.
     *
     * @param  array $data  The array with all the parameters
     * @param  string $key  The key to validate if is in the array of passed parameters
     * @return string
     */
    public static function required($data, $key = null)
    {
        try {
            if (!isset($data[$key])) {
                throw new Exception("Missing required parameter '{$key}' in request");
            } else {
                return $data[$key];
            }
        } catch (Exception $e) {
            header("Status: 500 Server Error");
            echo $e->getMessage();
        }
    }

    /**
     * Get optional parameters
     *
     * This method is check if an optional parameter was passed from the client,
     * if it is found return its value otherwise return a default value.
     *
     * @param  array $data          [description]
     * @param  string $key           [description]
     * @param  string $default_value [description]
     * @return string                [description]
     */
    public static function optional($data, $key, $default_value = null)
    {
        if (!isset($data[$key])) {
            return $default_value;
        } else {
            return $data[$key];
        }
    }
}