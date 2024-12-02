<?php
/**
 * Author: Kierra White
 * Date: 11/15/24
 * File: pastry_model.class.php
 * Description: This class handles interactions with the Pastries table
 */
class PastryModel {
    private Database $db; // Database object
    private mysqli $dbConnection; // Database connection object
    private string $table; // Table name
    static private ?PastryModel $_instance = null;

    public function __construct() {
        $this->db = Database::getDatabase(); // Get the database instance
        $this->dbConnection = $this->db->getConnection(); // Get the database connection
        $this->table = $this->db->getPastriesTable(); // Get the Pastries table name from the Database class

        //Escapes special characters in a string for use in an SQL statement. This stops SQL inject in POST vars.
        foreach ($_POST as $key => $value) {
            $_POST[$key] = $this->dbConnection->real_escape_string($value);
        }

        //Escapes special characters in a string for use in an SQL statement. This stops SQL Injection in GET vars
        foreach ($_GET as $key => $value) {
            $_GET[$key] = $this->dbConnection->real_escape_string($value);
        }


    }


    //static method to ensure there is just one PastryModel instance
    public static function getPastryModel(): PastryModel
    {
        if (self::$_instance == NULL) {
            self::$_instance = new PastryModel();
        }
        return self::$_instance;
    }

    // Method to get a pastry by ID
    public function get_pastry_by_id($pastryId) {
        // SQL select statement
        $sql = "SELECT * FROM $this->table WHERE pastry_id = $pastryId";

        // Execute the query
        $result = $this->dbConnection->query($sql);

        //if the result failed, return false
        if (!$result->num_rows == 0)
            return 0;
        //handle the result
        //create an array to store all returned pastries
        $pastries = array();

        //loop through all rows in returned recordsets
        while ($row = $result->fetch_object()) {
                $pastry = new Pastry(
                    $row->category_id,
                    $row->name,
                    $row->description,
                    $row->image_path,
                    $row->price,
                    $row->in_menu
                );
                $pastry->setPastryID($row->pastry_id);
                $pastries[] = $pastry;
            }
        return $pastries;
    }

    // Method to get all pastries
    public function get_all_pastries() {
        // SQL select statement
        $sql = "SELECT * FROM $this->table ORDER BY name ASC";

        // Execute the query
        $result = $this->dbConnection->query($sql);
        $pastries = [];

        if ($result) {
            while ($row = $result->fetch_object()) {
                $pastry = new Pastry(
                    $row->category_id,
                    $row->name,
                    $row->description,
                    $row->image_path,
                    $row->price,
                    $row->in_menu
                );
                $pastry->setPastryID($row->pastry_id);
                $pastries[] = $pastry;
            }
        }

        return $pastries;
    }

    //Method to search database for pastries that match words in name. Return an array of pastries if succeed; false otherwise
    public function search_pastries($terms): bool|array|int{
        $terms = explode (" ", $terms);
        //select statement for and search
        $sql = "SELECT * FROM " . $this->table . " WHERE 1";
        //Condition statement for each term in the search
        foreach ($terms as $term){
            $sql .= "AND name LIKE '%'" . $terms . "'%'";
        }
        //execute the query
        $result = $this->dbConnection->query($sql);
        //the search failed, return false
        if (!result){
            return false;
        }
        //the search succeeded, but no pastry was found.
        if ($result->num_rows ==0)
            return 0;
        //the search succeeded, and found at least one pastry
        //create an array to store all the returned pastries
        $pastries = array();

        //loop through all rows in returned recordsets
        while ($row = $result->fetch_object()) {
                $pastry = new Pastry(
                    $row->category_id,
                    $row->name,
                    $row->description,
                    $row->image_path,
                    $row->price,
                    $row->in_menu
                );
            //set the id for the pastry
                $pastry->setPastryID($row->pastry_id);
                // add pastry into the array
                $pastries[] = $pastry;
            }
        return $pastries;
    }
        

    // Method to update a pastry by ID
    public function update_pastry($pastryId): mysqli_result |bool {
        //if the script did not recieved post data, display an error message and then terminate the script immediately
        if (!filter_has_var(INPUT_POST, 'name') ||
            !filter_has_var(INPUT_POST, 'category_id') ||
            !filter_has_var(INPUT_POST, 'description') ||
            !filter_has_var(INPUT_POST, 'price') ||
            !filter_has_var(INPUT_POST, 'image_path') ||
            !filter_has_var(INPUT_POST, 'in_menu'))
        {
            return false;
        }
        $name = $this->dbConnection-> real_escape_string(trim(filter_input(INPUT_POST, 'name', FILTER_SANTIZE_STRING)));
        $categoryId = $this->dbConnection-> real_escape_string(trim(filter_input(INPUT_POST, 'category_id', FILTER_SANTIZE_STRING)));
        $description = $this->dbConnection-> real_escape_string(trim(filter_input(INPUT_POST, 'description', FILTER_SANTIZE_STRING)));
        $price = $this->dbConnection-> real_escape_string(trim(filter_input(INPUT_POST, 'price', FILTER_SANTIZE_STRING)));
        $imagePath = $this->dbConnection-> real_escape_string(trim(filter_input(INPUT_POST, 'image_path', FILTER_SANTIZE_STRING)));
        $inMenu = $this->dbConnection-> real_escape_string(trim(filter_input(INPUT_POST, 'in_menu', FILTER_SANTIZE_STRING)));
        
        // SQL update statement
        $sql = "UPDATE $this->table 
                SET name = '$name', category_id = $categoryId, description = '$description', price = $price, 
                 imagePath = '$imagePath', in_menu = '$in_menu'
                WHERE pastry_id = $pastryId";

        // Execute the query and return the result
        return $this->dbConnection->query($sql);

    }

    //Need to Update these next

    // Method to add a new pastry
    public function add_pastry($name, $description, $price, $categoryId, $in_menu = 1, $imagePath = null) {
        // SQL insert statement
        $sql = "INSERT INTO $this->table (name, description, price, category_id, in_menu, imagePath)
                VALUES ('$name', '$description', $price, $categoryId, $in_menu, '$imagePath')";

        // Execute the query and return the result
        $query = $this->dbConnection->query($sql);

        if ($query === true) {
            return true;
        }
        return false;
    }

    // Method to delete a pastry by ID
    public function delete_pastry($pastryId) {
        // SQL delete statement
        $sql = "DELETE FROM $this->table WHERE pastry_id = $pastryId";

        // Execute the query and return the result
        $query = $this->dbConnection->query($sql);

        if ($query === true) {
            return true;
        }
        return false;
    }
}
?>
