# ci4-AlphaModel
CodeIgniter 4 model class for basic database operations.

## Features:
- **C.R.U.D**
 Make CREATE, INSERT, UPDATE and DELETE queries with simple functions. Optional WHERE, LIMIT, ORDER BY clauses are also available for query specifications.
 
- **PAGINATION**
 Multiple pagination functionality can be achieved with this class. Example:
 ````php
  // Import the model class into your controller.
  use App\Models\AlphaModel;
  
  // Create an instance of the model class imported.
  $myModel = new AlphaModel();
 ````
 In creating the pagination functionality, firstly a fake table is set using the setFakeTable(string $table) method.\
 NOTE:\
 -> The table passed as an argument to this method must not be a table in your database ( guess thats why its called a fake table )\
	   -> This method will create a VIEW in your database with the method argument passed.
 ````php
  // Set the fake table.
  $myModel->setFakeTable("recent");
  
  // Query to paginate from.
  $query = "SELECT * FROM `myTable` WHERE `id` = '1'";
  
  // call the paginateQuery method with query string, order column and order format(ASC, DESC, RANDOM).
  $myModel->paginateQuery($query, "id", "DESC");

  // Call CodeIgniter 4 pager service. 
  $pager = \Config\Services::pager();
 ````	   
 
 Pass the pagination details to view via array. Example:
 ````php
  // Array to pass into view.
  $data = array(
		'$result'	=> $myModel->paginate(3), //See CodeIgniter 4 pagination guide.
		'pager'     => $myModel->pager		  //See CodeIgniter 4 pagination guide.
	);
  return view('myView', $data);
 ````
 
- **DATATABLE** 
 Functions for making dynamic (jQuery ajax) datatable are also available. Example:
 
 Provided you have a html table.
 <table id="user_table">
  <thead>
	<tr>
	  <th>id</th>
	  <th>Name</th>
	  <th>Email</th>
	</tr>
  </thead>
</table>
 
 The script for rendering AJAX Datatable:
 ````
  <script>
    $(document).ready(function() {
      var dataTable = $('#user_table').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
          url:"<?= base_url(); ?>/admin/make_user_table",
          type:"POST"
        }
      });
	});
  </script>
 ````
 
 In the make_user_table() method of your admin controller:
 ````php
  $selections = array("id","name","email");  // Database colums to get data from
  $searchCols = array("name","email");		// Database colums to search data from
  $orderCol = array("id","name","email");	// Database colums to set orderable
  $table = 						// Database Table to get data from
  $priKeyCol = "id"					// Database Table primary key column name

  // Call the makeDataTable(), allDataTableResult(), filterDataTable() method (example below).
  $fetch_data = $myModel->makeDataTable($selections, $table, $searchCols, $orderCol, $priKeyCol, $where_arr=[]);
  $data = array();
  foreach($fetch_data as $row) {
	$sub_array[] = $row->id;
	$sub_array[] = $row->name;
	$sub_array[] = $row->email;
	$data[] = $sub_array;
  }
  $output = array(
  "draw"				=> intval($_POST["draw"]),
  "recordsTotal"		=> $myModel->allDataTableResult($table, $where_arr = []),
  "recordsFiltered"		=> $myModel->filterDataTable($selections, $table, $searchCols, $orderCol, $priKeyCol, $where_arr=[]),
  "data"				=> $data
  );
  echo json_encode($output);
 ````
 
- **OTHER** 
 Other functions to query database. Example:\
 **getAverage()** method to get average of all database column field.\
 **getColumnCount()** method to get count of all database column field.\
 **getSum()** method to get sum of all database column field.\
 **verifyData()** method to confirm if data(s) is present in a databaseb table.\
 

##  License
- **ci4-AlphaModel** is released under the [MIT License](https://github.com/dev-charles15531/ci4-AlphaModel/blob/master/LICENSE).

Created by **[Charles Paul](https://github.com/dev-charles15531)**
