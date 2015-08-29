<?php
/*DBLogic.php
 * Provides easy DB access with DB protection mechanisim
 * TODO: implement clean the output
 */

class DB {
    private static $connection; //private - no access to outsiders
    
    function __construct ($TINA = false) {
            $options = array(
                PDO::ATTR_EMULATE_PREPARES      => false,
                PDO::MYSQL_ATTR_INIT_COMMAND    => "SET NAMES utf8",
                PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION
            );
            try {
                if (!$TINA) { //false - default
                    //$host,$user,$password,$db
                    self::$connection = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DB, DB_USERNAME, DB_PASSWORD, $options); 
                    // "::" is the Scope Resolution Operator aka access class variables (and static functions)
                } else {
                    //setup TINA
                    echo "TINA!!!!!";
                }
            } catch (PDOException $e) {
                if (CONFIG_DEV_ENV == true){
                /* @var $errorMessageSpecific type */
                $errorMessageSpecific = $e->getMessage();
                }
                $errorMessage = "There was an error connecting to the database.";
                include "404.php";
                exit;
            }
    }
    function isError() {
        if (self::$errorMessage  === NULL){
            return false;
        } else {
            return self::$errorMessage;
        }
    }
 

    /**
     * Runs a select query like: "SELECT $column FROM $table WHERE $whereValues;"
     * 
     * @param string  $columns The columns to be selected in the SQL query. In the form: "xx, yyy, max(zzz) etc"
     * @param string $tables The tables to be selected by the SQL query. in the form of "xx, yyy, zzz etc"
     * @param array $whereValuesArray  The input for the where clause. form $column => $vlaue
     * @param boolean $singleRow return one row of many? true is the default (single row)
     * @return array $results The results, eg result[15]['column'] or result['column']
     */
    public function select($columns, $tables, array $whereValuesArray, $singleRow=True) {
        assert(is_string($columns));
        assert(is_bool($singleRow));
        $where = self::prepareWhereValuesSQL($whereValuesArray); //the values
        $stmt = self::$connection->prepare("SELECT $columns FROM $tables WHERE $where;") or die('Problem preparing query');
        $stmt = self::bindParams($stmt, $whereValuesArray);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($singleRow && ($results)) {   //true and are actaully results
            $results = $results[0];   //return normal array instead
        }
        return $results;
    }
    /**
     * Runs a select query like: "SELECT $column FROM $table WHERE $whereValues ORDER BY $sortColumn"
     * 
     * @param string  $columns The columns to be selected in the SQL query. In the form: "xx, yyy, max(zzz) etc"
     * @param string $tables The tables to be selected by the SQL query. in the form of "xx, yyy, zzz etc"
     * @param array $whereValuesArray  The input for the where clause. form $column => $value
     * @param string $sortColumn The name of the column to sort by
     * @param boolean $singleRow return one row of many? true is the default (single row)
     * @return array $results The results, eg result[15]['column'] or result['column']
     */
    public function selectOrder($columns, $tables, array $whereValuesArray, $sortColumn, $singleRow=True) {
        assert(is_string($columns));
        assert(is_string($tables));
        assert(is_string($sortColumn));
        assert(is_bool($singleRow));
        $where = self::prepareWhereValuesSQL($whereValuesArray); //the values
        $stmt = self::$connection->prepare("SELECT $columns FROM $tables WHERE $where ORDER BY $sortColumn") or die('Problem preparing query');
        $stmt = self::bindParams($stmt, $whereValuesArray);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($singleRow && ($results)) {   //true and are actaully results
            $results = $results[0];   //return normal array instead
        }
        return $results;
    }
    
    /**
     * Runs a select query like: "SELECT $column FROM $table WHERE $whereValues AND $whereColumns ORDER BY $sortColumn"
     * 
     * @param string  $columns The columns to be selected in the SQL query. In the form: "xx, yyy, max(zzz) etc"
     * @param string $tables The tables to be selected by the SQL query. in the form of "xx, yyy, zzz etc"
     * @param array $whereValuesArray  The input for the where clause. form $column => $value
     * @param array $whereColumnsArray The where matching tables to be selected by the SQL query. in the form of $column => $otherColumn
     * @param string $sortColumn The name of the column to sort by
     * @param boolean $singleRow return one row or many? true is the default (single row)
     * @return array $results The results, eg result[15]['column'] or result['column']
     */
    public function selectWithColumns($columns, $tables, array $whereValuesArray, array $whereColumnsArray, $singleRow=True) {
        assert(is_string($columns));
        assert(is_string($tables));
        assert(is_bool($singleRow));
        $where = self::prepareWhereValuesSQL($whereValuesArray); //the values
        $where = self::prepareWhereColumnsSQL($whereColumnsArray, $where); //the columns
        $stmt = self::$connection->prepare("SELECT $columns FROM $tables WHERE $where;") or die('Problem preparing query');
        $stmt = self::bindParams($stmt, $whereValuesArray);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($singleRow && ($results)) {   //true and are actaully results
            $results = $results[0];   //return normal array instead
        }
        return $results;
    }
    /**
     * Runs a select query like: "SELECT $column FROM $table WHERE $whereValues & $whereColumns ORDER BY $sortColumn"
     * 
     * @param string  $columns The columns to be selected in the SQL query. In the form: "xx, yyy, max(zzz) etc"
     * @param string $tables The tables to be selected by the SQL query. in the form of "xx, yyy, zzz etc"
     * @param array $whereValuesArray  The input for the where clause. form $column => $vlaue
     * @param array $whereColumnsArray The where matching tables to be selected by the SQL query. in the form of $column => $otherColumn
     * @param string $sortColumn The name of the column to sort by
     * @param boolean $singleRow return one row of many? true is the default (single row)
     * @return array $results The results, eg result[15]['column'] or result['column']
     */
    public function selectWithColumnsOrder($columns, $tables, array $whereValuesArray, array $whereColumnsArray, $sortColumn, $singleRow=True) {
        assert(is_string($columns));
        assert(is_string($tables));
        assert(is_string($sortColumn));
        assert(is_bool($singleRow));
        $where = self::prepareWhereValuesSQL($whereValuesArray); //the values
        $where = self::prepareWhereColumnsSQL($whereColumnsArray, $where); //the columns
        $stmt = self::$connection->prepare("SELECT $columns FROM $tables WHERE $where ORDER BY $sortColumn") or die('Problem preparing query');
        $stmt = self::bindParams($stmt, $whereValuesArray);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($singleRow && ($results)) {   //true and are actaully results
            $results = $results[0];   //return normal array instead
        }
        return $results;
    }
    /**
     * Runs a select query like: "SELECT $column FROM $table WHERE $whereValues & $whereColumns OR ($whereValues & $whereColumns) ORDER BY $sortColumn"
     * 
     * @param string  $columns The columns to be selected in the SQL query. In the form: "xx, yyy, max(zzz) etc"
     * @param string $tables The tables to be selected by the SQL query. in the form of "xx, yyy, zzz etc"
     * @param array $whereValuesArray  The input for the where clause. form $column => $vlaue
     * @param array $whereColumnsArray The where matching tables to be selected by the SQL query. in the form of $column => $otherColumn
     * @param array $whereValuesArray2  The input for the where clause (after the OR). form $column => $vlaue
     * @param array $whereColumnsArray2 The where matching tables(after the OR) to be selected by the SQL query. in the form of $column => $otherColumn    
     * @param string $sortColumn The name of the column to sort by
     * @param boolean $singleRow return one row of many? true is the default (single row)
     * @return array $results The results, eg result[15]['column'] or result['column']
     */
    public function selectWithColumnsOrSort($columns, $tables, array $whereValuesArray, array $whereColumnsArray,
            array $whereValuesArray2, array $whereColumnsArray2, $sortColumn, $singleRow=True) {
        assert(is_string($columns));
        assert(is_string($tables));
        assert(is_string($sortColumn));
        assert(is_bool($singleRow));
        $where = self::prepareWhereValuesSQL($whereValuesArray); //the values
        $where = self::prepareWhereColumnsSQL($whereColumnsArray, $where); //the columns
        $where .= " OR (";
        $where = self::prepareWhereValuesSQL($whereValuesArray2, $where); //the values
        $where = self::prepareWhereColumnsSQL($whereColumnsArray2, $where); //the columns
        $where .= ")";
        $stmt = self::$connection->prepare("SELECT $columns FROM $tables WHERE $where ORDER BY $sortColumn;") or die('Problem preparing query');
        $stmt = self::bindParams($stmt, $whereValuesArray);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($singleRow && ($results)) {   //true and are actaully results
            $results = $results[0];   //return normal array instead
        }
        return $results;
    }
    /**
     * Runs a select query like: "SELECT $column FROM $table WHERE $whereValues & $whereColumns OR ($whereValues & $whereColumns)"
     * 
     * @param string  $columns The columns to be selected in the SQL query. In the form: "xx, yyy, max(zzz) etc"
     * @param string $tables The tables to be selected by the SQL query. in the form of "xx, yyy, zzz etc"
     * @param array $whereValuesArray  The input for the where clause. form $column => $value
     * @param array $whereColumnsArray The where matching tables to be selected by the SQL query. in the form of $column => $otherColumn
     * @param array $whereValuesArray2  The input for the where clause (after the OR). form $column => $value
     * @param array $whereColumnsArray2 The where matching tables(after the OR) to be selected by the SQL query. in the form of $column => $otherColumn    
     * @param string $sortColumn The name of teh column to sort by
     * @param boolean $singleRow return one row of many? true is the default (single row)
     * @return array $results The results, eg result[15]['column'] or result['column']
     */
    public function selectWithColumnsOr($columns, $tables, array $whereValuesArray, array $whereColumnsArray,
            array $whereValuesArray2, array $whereColumnsArray2, $singleRow=True) {
        assert(is_string($columns));
        assert(is_string($tables));
        assert(is_bool($singleRow));
        $where = self::prepareWhereValuesSQL($whereValuesArray); //the values
        $where = self::prepareWhereColumnsSQL($whereColumnsArray, $where); //the columns
        $where .= " OR (";
        $where = self::prepareWhereValuesSQL($whereValuesArray2, $where); //the values
        $where = self::prepareWhereColumnsSQL($whereColumnsArray2, $where); //the columns
        $where .= ")";
        $stmt = self::$connection->prepare("SELECT $columns FROM $tables WHERE $where;") or die('Problem preparing query');
        $stmt = self::bindParams($stmt, $whereValuesArray);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($singleRow && ($results)) {   //true and are actaully results
            $results = $results[0];   //return normal array instead
        }
        return $results;
    }
    /**
     * Runs a select query like: "SELECT DISTINCT $column FROM $table WHERE $whereValues & $whereColumns OR ($whereValues & $whereColumns)"
     * 
     * @param string  $columns The columns to be selected in the SQL query. In the form: "xx, yyy, max(zzz) etc"
     * @param string $tables The tables to be selected by the SQL query. in the form of "xx, yyy, zzz etc"
     * @param array $whereValuesArray  The input for the where clause. form $column => $value
     * @param array $whereColumnsArray The where matching tables to be selected by the SQL query. in the form of $column => $otherColumn
     * @param array $whereValuesArray2  The input for the where clause (after the OR). form $column => $value
     * @param array $whereColumnsArray2 The where matching tables(after the OR) to be selected by the SQL query. in the form of $column => $otherColumn    
     * @param string $sortColumn The name of teh column to sort by
     * @param boolean $singleRow return one row of many? true is the default (single row)
     * @return array $results The results, eg result[15]['column'] or result['column']
     */
    public function selectDistinctWithColumnsOr($columns, $tables, array $whereValuesArray, array $whereColumnsArray,
            array $whereValuesArray2, array $whereColumnsArray2, $singleRow=True) {
        assert(is_string($columns));
        assert(is_string($tables));
        assert(is_bool($singleRow));
        $where = self::prepareWhereValuesSQL($whereValuesArray); //the values
        $where = self::prepareWhereColumnsSQL($whereColumnsArray, $where); //the columns
        $where .= " OR (";
        $where = self::prepareWhereValuesSQL($whereValuesArray2, $where); //the values
        $where = self::prepareWhereColumnsSQL($whereColumnsArray2, $where); //the columns
        $where .= ")";
        $stmt = self::$connection->prepare("SELECT DISTINCT $columns FROM $tables WHERE $where;") or die('Problem preparing query');
        $stmt = self::bindParams($stmt, $whereValuesArray);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($singleRow && ($results)) {   //true and are actaully results
            $results = $results[0];   //return normal array instead
        }
        return $results;
    }
    /**
     * Runs a select ALL query like: ""SELECT * FROM $tables;""
     * 
     * @param string $tables The tables to be selected by the SQL query. in the form of "xx, yyy, zzz etc"
     * @return array $results The results, eg result[15]['column'] or result['column']
     */
    public function selectAll($tables) {
        assert(is_string($tables));
        $stmt = self::$connection->prepare("SELECT * FROM $tables;") or die('Problem preparing query');
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }
    /**
     * Runs a insert like: "insert into $table ($columns) values ($values);"
     * 
     * @param string $tables The tables to be selected by the SQL query. in the form of "xx, yyy, zzz etc"
     * @param array $whereValuesArray  The input for the where clause. form $column => $value
     * @param array $whereColumnsArray The where matching tables to be selected by the SQL query. in the form of $column => $otherColumn
     * @return string $lastInsertID Returns the primary key of the insertion (eg quiz_id)
     */
    public function insert(array $insertArray, $tables) {
        assert(is_string($tables));
        $columns = self::prepareInsertColumns($insertArray);
        $values = self::prepareInsertValues($insertArray);
        echo "insert into $tables ($columns) values ($values);";
        $stmt = self::$connection->prepare("insert into $tables ($columns) values ($values);") or die('Problem preparing query');
        $stmt = self::bindParams($stmt, $insertArray);
        $stmt->execute();
        return $lastInsertID = self::$connection->lastInsertID();
    }
<<<<<<< HEAD
     /**
     * Runs a delete like: "delete from $tables where $whereValuesArray AND "
     * 
     * @param string $tables The tables to be selected by the SQL query. in the form of "xx, yyy, zzz etc"
     * @param array $deleteValues  The input for the where clause. form $column => $value
     * @param array $deleteColumns The where matching tables to be selected by the SQL query. in the form of $column => $otherColumn
     * @return string $lastInsertID Returns the primary key of the insertion (eg quiz_id)
     */
    public function delete($tables, $deleteValues, $deleteColumns) {
        /* @var $where string */
        $where = self::prepareWhereValuesSQL($deleteValues); //the values
        $where = self::prepareWhereColumnsSQL($deleteColumns, $where); //the columns
        $stmt = self::$connection->prepare("delete from $tables where $where;") or die('Problem preparing query');
        $stmt = self::bindParams($stmt, $deleteValues);
        $stmt->execute();  //send the values separately
        return $lastInsertID = self::$connection->lastInsertID(); //return the ID of the user in the database.
=======
 
    public function delete($dataArray, $table) {
        if (!is_string ($table)) {
            die("A string was not passed to the Select function on DB class");
        }
        $where = "";
        foreach ($dataArray as $column => $value) {      //$value not used - it's in $data
            $where .= ($where == "") ? "" : " AND ";
            $where .= "$column = :$column";
        }      
        $stmt = self::$connection->prepare("DELETE FROM $table WHERE " . $where . ";") or die('Problem preparing query');
        $stmt->execute($dataArray);      
>>>>>>> origin/master
    }

    /**
     * Runs a select query like: "SELECT DISTINCT $column FROM $table WHERE $whereValues & $whereColumns"
     * 
     * @param string  $columns The columns to be selected in the SQL query. In the form: "xx, yyy, max(zzz) etc"
     * @param string $tables The tables to be selected by the SQL query. in the form of "xx, yyy, zzz etc"
     * @param array $whereValuesArray  The input for the where clause. form $column => $value
     * @param array $whereColumnsArray The where matching tables to be selected by the SQL query. in the form of $column => $otherColumn   
     * @param boolean $singleRow return one row of many? true is the default (single row)
     * @return array $results The results, eg result[15]['column'] or result['column']
     */
    public function selectDistinct($columns, $tables, array $whereValuesArray, array $whereColumnsArray, $singleRow=True) {
        assert(is_string($columns));
        assert(is_string($tables));
        assert(is_bool($singleRow));
        $where = self::prepareWhereValuesSQL($whereValuesArray); //the values
        $where = self::prepareWhereColumnsSQL($whereColumnsArray, $where); //the columns
        $stmt = self::$connection->prepare("SELECT DISTINCT $columns FROM $tables WHERE " . $where . ";") or die('Problem preparing query');
        $stmt = self::bindParams($stmt, $whereValuesArray);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($singleRow && ($results)) {   //true and are actaully results
            $results = $results[0];   //return normal array instead
        }
        return $results;
    }
    /**
     * Runs a select query like: "SELECT DISTINCT $column FROM $table WHERE $whereValues & $whereColumns"
     * 
     * @param string  $columns The columns to be selected in the SQL query. In the form: "xx, yyy, max(zzz) etc"
     * @param string $tables The tables to be selected by the SQL query. in the form of "xx, yyy, zzz etc"
     * @param array $whereValuesArray  The input for the where clause. form $column => $value
     * @param array $whereColumnsArray The where matching tables to be selected by the SQL query. in the form of $column => $otherColumn   
     * @param string $sortColumn The name of teh column to sort by
     * @param boolean $singleRow return one row of many? true is the default (single row)
     * @return array $results The results, eg result[15]['column'] or result['column']
     */
    public function selectDistinctOrder($columns, $tables, array $whereValuesArray, array $whereColumnsArray, $sortColumn, $singleRow=True) {
        assert(is_string($columns));
        assert(is_string($tables));
        assert(is_string($sortColumn));
        assert(is_bool($singleRow));
        $where = self::prepareWhereValuesSQL($whereValuesArray); //the values
        $where = self::prepareWhereColumnsSQL($whereColumnsArray, $where); //the columns
        $stmt = self::$connection->prepare("SELECT DISTINCT $columns FROM $tables WHERE " . $where . "ORDER BY $sortColumn;") or die('Problem preparing query');
        $stmt = self::bindParams($stmt, $whereValuesArray);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($singleRow && ($results)) {   //true and are actaully results
            $results = $results[0];   //return normal array instead
        }
        return $results;
    }

    /**
     * Runs a FULL outer join query like: ""SELECT $column FROM $table LEFT JOIN $joinTable ON $joinWhere LEFT JOIN $joinTable2 ON $joinWhere2 WHERE $where;""
     * 
     * @param string $columns The columns to be selected in the SQL query. In the form: "xx, yyy, max(zzz) etc"
     * @param string $tables The tables to be selected by the SQL query. in the form of "xx, yyy, zzz etc"
     * @param array $whereData  The input for the where clause. form $column => $value
     * @param string $joinTable The table for the 1st join "LEFT JOIN $joinTable ON"
     * @param array $tableArray The 1st ON (where) matching tables to be selected by the SQL query. in the form of $column => $otherColumn 
     * @param string $joinTable2 The table for the 2nd join "LEFT JOIN $joinTable ON"  
     * @param array $tableArray2 The 2nd ON (where) matching tables to be selected by the SQL query. in the form of $column => $otherColumn  
     * @param boolean $singleRow return one row of many? true is the default (single row)
      * @return array $results The results, eg result[15]['column'] or result['column']
     */
    public function selectFullOuterJoin($columns, $tables, array $whereData, $joinTable, 
            $tableArray, $joinTable2, array $tableArray2,$singleRow=True) {
        assert(is_string($columns));
        assert(is_string($tables));
        assert(is_string($joinTable));
        assert(is_string($joinTable2));
        assert(is_bool($singleRow));
        $where = self::prepareWhereValuesSQL($whereData);
        $joinWhere = self::prepareWhereColumnsSQL($tableArray);
        $joinWhere2 = self::prepareWhereColumnsSQL($tableArray2);
        $sql = "SELECT $columns FROM $tables " . 
                "LEFT JOIN $joinTable ON $joinWhere " . 
                "LEFT JOIN $joinTable2 ON $joinWhere2 WHERE $where;";
        $stmt = self::$connection->prepare($sql) or die('Problem preparing query');
        $stmt = self::bindParams($stmt, $whereData);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($singleRow && ($results)) {   //true and are actaully results
            $results = $results[0];   //return normal array instead
        }
        return $results;
    }
    
    /**
     * Cleans the output (to the web broswer) by running htmlentities on it fisrt (stop cross site scripting)
     * 
     * @param string $output The data to be cleaned
     * @return string The cleaned data
     */
    private function cleanTheOutput($output){
        assert(is_string($output));
        return htmlentities($output); //convert html entitiles like "<" to &lt;
    }
    /**
     * Converts the Where data array to a string in preapation for PDO
     * 
     * @param array $whereColumnsArray An assoicative array in the form of $column(or table.column) => $value 
     * @param string $where a string of the existing where sql query, values will be added on. (optional)
     * @return string $where Part of the SQL query
     */
    private static function prepareWhereColumnsSQL(array $whereColumnsArray, $where = ""){
        assert(is_string($where));
        foreach ($whereColumnsArray as $columnTemp => $valueTemp) {      //build coloumn where query
            $where .= ($where == "") ? "" : " AND ";
            $where .= "$columnTemp = $valueTemp";
        }
        return $where;
    }
    
    /**
     * Converts the Where data array to a string in preapation for PDO
     * 
     * @param $whereValuesArray An assoicative array in the form of $column(or table.column) => $value 
     * @param $where a string of the existing where sql query, values will be added on. (optional)
     * @return $where Part of the SQL query
     */
    private static function prepareWhereValuesSQL(array $whereValuesArray, $where = ""){
        assert(is_string($where));
        foreach ($whereValuesArray as $columnTemp => $valueTemp) {      //$value not used - it's in $data
            $where .= ($where == "") ? "" : " AND ";
            $where .= "$columnTemp = :" . self::prepareColumnNameForBinding($columnTemp); //replace dot with underscore for table.column
        }
        return $where;
    }

    /**
     * Binds the variables in the PDO connection
     * 
     * @param $stmt The statement as prepared previsously by PDO.
     * @param $whereValuesArray An assoicative array in the form of $column(or table.column) => $value
     * @return $stmt The binded stmt
     */
    private static function bindParams(PDOStatement $stmt, array $whereValuesArray){
        foreach ($whereValuesArray as $columnTemp => $valueTemp) {      //$value not used - it's in $data
            $stmt->bindValue(':' . self::prepareColumnNameForBinding($columnTemp), $valueTemp);
        }
        return $stmt;
    }
    /**
     * Prepares the coloumn name (table.column) by replacing the dot with a underscore (doesn't affact the query)
     * 
     * @param $columnName The name of the column to be renamed
     * @return $stmt The binded stmt
     */
    private static function prepareColumnNameForBinding($columnName){
        assert(is_string($columnName));
        $columnName =  preg_replace('/\\./', '_', $columnName);
        return $columnName;
    }
    /**
     * Prepares the Insert values by creating the approiate placeholders for binding
     * 
     * @param array $insertArray  The input for the where clause. form $column => $value
     * @param string $values The existing values if any need adding on (optional)
     * @return string $lastInsertID Returns the primary key of the insertion (eg quiz_id)
     */
    private static function prepareInsertValues (array $insertArray, $values = ""){
        assert(is_string($values));
        foreach ($insertArray as $column => $valueTemp) { //$value not used, it's in execute
            $values .= ($values == "") ? "" : ", ";
            $values .= ":".self::prepareColumnNameForBinding($column);
        }
        return $values;
    }
    /**
     * Prepares the insert columns by building string with commas in-between
     * 
     * @param array $insertArray  The input for the where clause. form $column => $value
     * @param string $columns The existing columns if any need adding on (optional)
     * @return string $columns A string with words and commas for the SQL query
     */
    private static function prepareInsertColumns (array $insertArray, $columns = ""){
        assert(is_string($columns));
        foreach ($insertArray as $column =>$valueTemp) { //$value not used, it's in execute
            $columns .= ($columns == "") ? "" : ", ";
            $columns .= $column;
        }
        return $columns;
    }
}
