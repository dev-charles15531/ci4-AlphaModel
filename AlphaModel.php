<?php
/**
 *** AlphaModel ***
 * @author Charles Paul, dev.charles15531@gmail.com
 * @version 1.0
 * @license MIT
 * 
 * ********FUNCTIONS LIST****************************
 * - allDataTableResult(array $where_arr, String $table)
 * - deleteData(String $priKeyName, String $priKeyValue, $table)
 * - filterDataTable(array $selections, array $where_arr, String $table, array $searchCols, array $orderCol, String $priKeyName)
 * - getAllData(String $table, array $where_arr, String $orderBy, String $limit)
 * - getAverage(String $table, String $avgCol, array $where_arr)
 * - getColumnCount(String $table, String $countCol, array $where_arr)
 * - getSpecifiedData(String $col, array $where_arr, String $orderBy, String $limit, $table)
 * - getSum(String $table, String $sumCol, array $where_arr)
 * - insertData(array $data , String $table)
 * - insertMultiData(array $data, String $table)
 * - makeDataTable(array $selections, array $where_arr, String $table, array $searchCols, array $orderCol, String $priKeyName)
 * - makeDataTableQuery(String $table, array $selections, array $where_arr, array $searchCols, array $orderCol, String $priKeyName)
 * - paginateQuery(String $query, String $orderCol, String $orderMethod)
 * - setData(String $setCol, String $setVal, String $priKeyName, String $priKeyValue, String $table)
 * - setFakeTable(String $table)
 * - updateData(array $data, String $priKeyName, String $priKeyValue, String $table)
 * - verifyData(array $whereCondition, String $table)
 * ***************************************************
 */

namespace App\Models;
use CodeIgniter\Model;

class AlphaModel extends Model {

  /**
   * @param $table => This table will be set with the setTable() method when the pagination functionality is to be implemented.
   * XXX Do not delete this decleration if you would want to use the pagination function
   */
  protected $table;

  //-----------------------------------------------------------------------------------------------------------------------------------------
  /**
   * This method gets the count of all query.
   * @param $table => The database table to work with.
   * @param $where_arr => WHERE clause to select with if any.
   * @return Query count.
   */
  public function allDataTableResult(String $table, array $where_arr = []) {
    $builder = $this->db->table(($table));
    $builder->select("*");
    if(count($where_arr) != 0) {
      $builder->where($where_arr);
    }
    return $builder->countAllResults();
  }

  //-----------------------------------------------------------------------------------------------------------------------------------------
  /**
   * This method deleted data from the db table with a where clause.
   * @param $priKeyName => The name of the primary key column in db.
   * @param $priKeyValue => The value of the primary key where update needs to be made.
   * @param $table => The database table to work with.
   * @return Bool TRUE or FALSE if the data gets updated or doesn't.
   */
  public function deleteData(String $priKeyName, String $priKeyValue, String $table) {
    $builder = $this->db->table($table);
    $builder->where($priKeyName, $priKeyValue);
    $query = $builder->delete();
    if($query) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  //-----------------------------------------------------------------------------------------------------------------------------------------
  /**
   * This method filter the rows from the query generated from the makeDataTableQuery() method.
   * All the function parameter is similar to the makeDataTableQuery() method.
   * @return Rows from the generated query
   */
  public function filterDataTable(array $selections, String $table, array $searchCols, array $orderCol, String $priKeyName, array $where_arr=[]) {
    $retBuilder = $this->makeDataTableQuery($selections, $table, $searchCols, $orderCol, $priKeyName, $where_arr);
    $query = $retBuilder->get();
    return $query->getRow();
  }

  //-----------------------------------------------------------------------------------------------------------------------------------------
  /**
   * This method gets all the available data from a db table.
   * @param $table => The database table to work with.
   * @param $where_arr => For setting WHERE clause(s) where key and value represents the table column name and value respectively.
   *         eg. ['id' => 1, 'price' => 200] or ['id !=' => 1, 'price >=' => 200] see the Codeigniter 4 Query Builder Class.
   * @param $orderBy => For setting an ORDER BY clause eg. "id DESC" comma seperator for multiple order clauses
   *        eg. "id DESC, price ASC, ..."
   * @param $limit => For limiting the number of row result to return.
   * @return Query result in array format.
   */
  public function getAllData(String $table, array $where_arr=[], String $orderBy="", String $limit="") {
    $builder = $this->db->table($table);
    $builder->select("*");
    if(count($where_arr) != 0)
      $builder->where($where_arr);
    if(! empty($orderBy))
      $builder->orderBy($orderBy);
    if(! empty($limit))
      $builder->limit($limit);
    $query = $builder->get();
    $result = $query->getResult();
    if(count($result) > 0) {
      return $result;
    }
    else {
      return false;
    }
  }

  //-----------------------------------------------------------------------------------------------------------------------------------------
  /**
   * This method gets the average of a column fields from a db table.
   * @param $table => The database table to work with.
   * @param $avgCol => The column to get fields average.
   * @param $where_arr => For setting WHERE clause(s) where key and value represents the table column name and value respectively.
   *         eg. ['id' => 1, 'price' => 200] or ['id !=' => 1, 'price >=' => 200] see the Codeigniter 4 Query Builder Class.
   * @return Query result in array format.
   */
  public function getAverage(String $table, String $avgCol, array $where_arr=[]) {
    $builder = $this->db->table($table);
    $builder->selectAvg($avgCol);
    if(count($where_arr) != 0)
      $builder->where($where_arr);
    $query = $builder->get();
    $result = $query->getResult();
    if(count($result) > 0) {
      return $result;
    }
    else {
      return false;
    }
  }

  //-----------------------------------------------------------------------------------------------------------------------------------------
  /**
   * This method gets the count of a column fields from a db table.
   * @param $table => The database table to work with.
   * @param $countCol => The column to get fields average.
   * @param $where_arr => For setting WHERE clause(s) where key and value represents the table column name and value respectively.
   *         eg. ['id' => 1, 'price' => 200] or ['id !=' => 1, 'price >=' => 200] see the Codeigniter 4 Query Builder Class.
   * @return Query result in array format.
   */
  public function getColumnCount(String $table, String $countCol, array $where_arr=[]) {
    $builder = $this->db->table($table);
    $builder->selectCount($countCol);
    if(count($where_arr) != 0)
      $builder->where($where_arr);
    $query = $builder->get();
    $result = $query->getResult();
    if(count($result) > 0) {
      return $result;
    }
    else {
      return false;
    }
  }

  //-----------------------------------------------------------------------------------------------------------------------------------------
  /**
   * This method returns specified data from the db table.
   * @param $col => Columns to select from the db table. Comma seperator for multiple cols eg "id, price, ..."
   * @param $where_arr => For setting WHERE clause(s) where key and value represents the table column name and value respectively.
   *        eg. ['id' => 1, 'price' => 200] or ['id !=' => 1, 'price >=' => 200] see the Codeigniter 4 Query Builder Class.
   * @param $orderBy => For setting an ORDER BY clause eg. "id DESC" comma seperator for multiple order clauses
   *        eg. "id DESC, price ASC, ..."
   * @param $limit => For limiting the number of row result to return.
   * @param $table => The database table to work with.
   * @return Query result in array format.
   */
  public function getSpecifiedData(String $col, array $where_arr, String $orderBy="", String $limit="", String $table) {
    $countWhere_arr = count($where_arr);
    if($countWhere_arr != 0) {
        $builder = $this->db->table($table);
        $builder->select($col);
        $builder->where($where_arr);
        if(! empty($orderBy))
          $builder->orderBy($orderBy);
        if(! empty($limit))
          $builder->limit($limit);
        $query = $builder->get();
        $result = $query->getResult();
        if(count($result) > 0) {
            return $result;
        }
        else {
            return false;
        }
    }
    else {
        throw new \Exception("Empty array passed as an argument.");
    }
  } 

  //-----------------------------------------------------------------------------------------------------------------------------------------
  /**
   * This method gets the summation of a column fields from a db table.
   * @param $table => The database table to work with.
   * @param $sumCol => The column to get fields sum.
   * @param $where_arr => For setting WHERE clause(s) where key and value represents the table column name and value respectively.
   *         eg. ['id' => 1, 'price' => 200] or ['id !=' => 1, 'price >=' => 200] see the Codeigniter 4 Query Builder Class.
   * @return Query result in array format.
   */
  public function getSum(String $table, String $sumCol, array $where_arr=[]) {
    $builder = $this->db->table($table);
    $builder->selectSum($sumCol);
    if(count($where_arr) != 0)
      $builder->where($where_arr);
    $query = $builder->get();
    $result = $query->getResult();
    if(count($result) > 0) {
      return $result;
    }
    else {
      return false;
    }
  }  

  //-----------------------------------------------------------------------------------------------------------------------------------------
  /**
   * This method inserts an associative array data into a  db table.
   * @param $data => Data to insert where key and value represents the table column name and value respectively.
   *         eg. ['id' => 1, 'price' => 200] see the Codeigniter 4 Query Builder Class.
   * @param $table => The database table to work with.
   * @return Bool TRUE or FALSE if the data gets inserted or doesn't.
   */
  public function insertData(array $data = [], String $table) {
    $dataCount = count($data);

    if($dataCount != 0) {
      $builder = $this->db->table($table);
      $query = $builder->insert($data);
      if($query) {
        return TRUE;
      }
      else {
        return FALSE;
      }
    }
    else {
      throw new \Exception("Null array passed as an argument.");
    }
  }

  //-----------------------------------------------------------------------------------------------------------------------------------------
  /**
   * This method inserts multidimensional associative array data into a  db table.
   * @param $data => Data to insert where key and value represents the table column name and value respectively.
   *         eg. [ ['id' => 1, 'price' => 200], ['id' => 2, 'price' => 400] ] see the Codeigniter 4 Query Builder Class.
   * @param $table => The database table to work with.
   * @return Bool TRUE or FALSE if the data gets inserted or doesn't.
   */
  public function insertMultiData(array $data = [], String $table) {
    $dataCount = count($data);

    if($dataCount != 0) {
      $builder = $this->db->table($table);
      $query = $builder->insertBatch($data);
      if($query) {
        return TRUE;
      }
      else {
        return FALSE;
      }
    }
    else {
      throw new \Exception("Null array passed as an argument.");
    }
  }

  //-----------------------------------------------------------------------------------------------------------------------------------------
  /**
   * This method gets the result from the query generated from the method below.
   * NOTE: This method is to be used with datatables only !
   * @param $table => The database table to work with.
   * @param $selections => The table columns to select.
   * @param $where_arr => WHERE clause to select with if any.
   * @param $searchCols => Two elements array for search operation.
   * @param $orderCol => The colums for ordering, in array format.
   * @param $priKeyName => The primary key column name.
   * @return Result of the resulting query.
   */
  public function makeDataTable(array $selections, String $table, array $searchCols, array $orderCol, String $priKeyName, array $where_arr=[]) {
    $retBuilder = $this->makeDataTableQuery($selections, $table, $searchCols, $orderCol, $priKeyName, $where_arr);
    if(count($where_arr) != 0)
      $retBuilder->where($where_arr);
    if($_POST["length"] != -1) {
      $retBuilder->limit($_POST["length"], $_POST["start"]);
    }
    $query = $retBuilder->get();
    return $query->getResult();
  }

  //-----------------------------------------------------------------------------------------------------------------------------------------
  /**
   * This method generates query for making datatables.
   * All the function parameter is similar to the method above.
   * @return Builder instance of the generated query.
   */
  private function makeDataTableQuery(array $selections, String $table, array $searchCols, array $orderCol, String $priKeyName, array $where_arr=[]) {
    $builder = $this->db->table($table);
    $builder->select($selections);
    if(count($where_arr) != 0)
      $builder->where($where_arr);
    if(isset($_POST["search"]["value"])) {
      if(count($searchCols) == 2) {
        $builder->like($searchCols[0], $_POST["search"]["value"]);
        $builder->orLike($searchCols[1], $_POST["search"]["value"]);
      }
      elseif(count($searchCols) == 1) {
        $builder->like($searchCols[0], $_POST["search"]["value"]);
      }
      else {
        throw new \Exception("Third function argument must be an array of one or two elements.");
      }
    }
    if(isset($_POST["order"])){
      $builder->orderBy($orderCol[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    }
    else {
      $builder->orderBy($priKeyName, "DESC");
    }
    return $builder;
  }
  
  //-----------------------------------------------------------------------------------------------------------------------------------------
  /**
   * This method generates the query result to be paginated.
   * @param $query => The query to execute in the correct syntax.
   * @param $orderCol => Column for ordering result.
   * @param $orderMethod => Order method. eg ASC, DESC ...
   */
  public function paginateQuery(String $query, String $orderCol, String $orderMethod) {
    if(! empty($this->table)) {
      $query = $this->db->query("CREATE OR REPLACE VIEW ".$this->table." AS SELECT * FROM".
                                "(".
                                  $query.
                                  ")dum
                                ORDER BY ".$orderCol." ".$orderMethod."");
    }
    else {
      throw new \Exception("The setFakeTable() method not called.");
    }
  }

  //-----------------------------------------------------------------------------------------------------------------------------------------
  /**
   * This method sets a single data into a db table with a where clause.
   * @param $setCol => The name of the column data is to be set.
   * @param $setVal => The value to be set.
   * @param $priKeyName => The name of the primary key column in db.
   * @param $priKeyValue => The value of the primary key where update needs to be made.
   * @param $table => The database table to work with.
   * @return Bool TRUE or FALSE if the data gets updated or doesn't.
   */
  public function setData(String $setCol, String $setVal, String $priKeyName, String $priKeyValue, String $table) {
    $builder = $this->db->table($table);
    $builder->set($setCol, $setVal);
    $builder->where($priKeyName, $priKeyValue);
    $query = $builder->update();
    if($query) {
    return TRUE;
    }
    else {
      return FALSE;
    }
  }

  //-----------------------------------------------------------------------------------------------------------------------------------------
  /**
   * This method sets the fake table for the pagination functionality.
   * @param $table => This argument must not be a real table from your database and not really the table you want to get the pagination data from.
   */
  public function setFakeTable(String $table) {
    $this->table = $table;
  }

  //-----------------------------------------------------------------------------------------------------------------------------------------
  /**
   * This method updates data into a  db table with a where clause.
   * @param $data => Data to update where key and value represents the table column name and value respectively.
   *         eg.['id' => 1, 'price' => 200] ] see the Codeigniter 4 Query Builder Class.
   * @param $priKeyName => The name of the primary key column in db.
   * @param $priKeyValue => The value of the primary key where update needs to be made.
   * @param $table => The database table to work with.
   * @return Bool TRUE or FALSE if the data gets updated or doesn't.
   */
  public function updateData(array $data = [], String $priKeyName, String $priKeyValue, String $table) {
    $dataCount = count($data);

    if($dataCount != 0) {
      $builder = $this->db->table($table);
      $builder->where($priKeyName, $priKeyValue);
      $query = $builder->update($data);
      if($query) {
        return TRUE;
      }
      else {
        return FALSE;
      }
    }
    else {
      throw new \Exception("Null array passed as first argument.");
    }
  }

  //-----------------------------------------------------------------------------------------------------------------------------------------
  /**
   * This method checks to confirm if data(s) is present in a db table.
   * @param $whereCondition => For setting WHERE clause(s) where key and value represents the table column name and value respectively.
   *        eg. ['id' => 1, 'price' => 200] or ['id !=' => 1, 'price >=' => 200] see the Codeigniter 4 Query Builder Class.
   * @param $table => The database table to work with.
   * @return TRUE or FALSE if the data gets found or doesn't.
   */
  public function verifyData(array $whereCondition, String $table) {
    $builder = $this->db->table($table);
    $builder->select("*");
    $builder->where($whereCondition);
    $query = $builder->get();
    $result = $query->getResult();

    if(count($result) > 0) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

}
