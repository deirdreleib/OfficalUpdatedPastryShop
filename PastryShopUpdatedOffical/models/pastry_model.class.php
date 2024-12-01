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

    // Method to get a pastry by ID
    public function get_pastry_by_id($pastryId) {
        // SQL select statement
        $sql = "SELECT * FROM $this->table WHERE pastry_id = $pastryId";

        // Execute the query
        $result = $this->dbConnection->query($sql);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
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

    // Method to update a pastry by ID
    public function update_pastry($pastryId, $name, $description, $price, $categoryId, $in_menu, $imagePath) {
        // SQL update statement
        $sql = "UPDATE $this->table 
                SET name = '$name', description = '$description', price = $price, category_id = $categoryId,
                    in_menu = $in_menu, imagePath = '$imagePath'
                WHERE pastry_id = $pastryId";

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